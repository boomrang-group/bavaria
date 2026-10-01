<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// ... reste de votre code
session_start();
require 'config/config.php';

$message = "";
$showModal = false;

if (isset($_POST['valider_code'])) {
    $code = $_POST['code_saisi'];
    $stmt = $conn->prepare("SELECT nom_vendeur FROM vendeurs WHERE code_vendeur = ?");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $_SESSION['vendeur_code'] = $code;
    } else {
        $message = "Code invalide. Veuillez réessayer.";
    }
}

// BACKEND MODIFIÉ : Prise en compte de 'nom_complet' et suppression de 'adresse'
if (isset($_POST['envoyer_fiche'])) {
    $stmt = $conn->prepare("INSERT INTO participants (nom_complet, tranche_age, telephone, saveur_choisie, observation, code_vendeur) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $_POST['nom_complet'], $_POST['tranche_age'], $_POST['telephone'], $_POST['saveur_choisie'], $_POST['observation'], $_SESSION['vendeur_code']);
    
    if ($stmt->execute()) {
        $showModal = true;
    } else {
        $message = "Erreur lors de l'enregistrement.";
    }
}

if (isset($_GET['logout'])) { session_destroy(); header("Location: index.php"); exit(); }
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BAVARIA - Collecte</title>
    <style>
    :root {
        --primary: #2c3e50;
        --accent: #3498db;
        --bg: #f4f7f6;
    }

    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', sans-serif;
        background: var(--bg);
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 20px;
        margin: 0;
        min-height: 100vh;
    }

    .card {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 600px;
    }

    input,
    select,
    textarea {
        width: 100%;
        padding: 12px;
        margin: 10px 0;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 1rem;
    }

    button {
        width: 100%;
        padding: 12px;
        background: var(--accent);
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
        font-size: 1rem;
        margin-top: 10px;
    }

    .show-pass {
        cursor: pointer;
        font-size: 0.8rem;
        color: var(--accent);
    }

    .modal {
        display: <?php echo $showModal ? 'flex': 'none'; ?>;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
        padding: 15px;
        z-index: 1000;
    }

    .modal-content {
        background: white;
        padding: 30px;
        border-radius: 12px;
        text-align: center;
        width: 100%;
        max-width: 400px;
    }

    @media (max-width: 576px) {
        body {
            padding: 10px;
        }

        .card {
            padding: 20px 15px;
            border-radius: 8px;
        }

        h2 {
            font-size: 1.5rem;
            margin-top: 5px;
        }

        input, select, textarea, button {
            padding: 14px 12px;
        }
    }
    </style>
</head>

<body>

    <?php if ($showModal): ?>
    <div class="modal">
        <div class="modal-content">
            <h2 style="color: #27ae60; margin-top: 0;">Enregistré avec succès !</h2>
            <button onclick="window.location.href='index.php'">OK</button>
        </div>
    </div>
    <?php endif; ?>

    <div class="card">
        <?php if (!isset($_SESSION['vendeur_code'])): ?>
        <h2>Connexion Vendeur</h2>
        <p style="color:red;"><?php echo $message; ?></p>
        <form method="POST" autocomplete="off">
            <input type="password" name="code_saisi" id="passInput" placeholder="Code vendeur (5 car.)" required
                maxlength="5">
            <button type="submit" name="valider_code">Accéder au formulaire</button>
        </form>
        <?php else: ?>
        <h2>Collecte - BAVARIA</h2>
        <p style="margin-bottom: 20px;"><small>Vendeur : <strong><?php echo $_SESSION['vendeur_code']; ?></strong> | <a href="?logout=true">Déconnexion</a></small>
        </p>
        <form method="POST">
            <input type="text" name="nom_complet" placeholder="Nom Complet" required>

            <select name="tranche_age" required>
                <option value="" disabled selected>Sélectionnez la tranche d'âge</option>
                <option value="0-12 ans">0-12 ans</option>
                <option value="13-18 ans">13-18 ans</option>
                <option value="19-35 ans">19-35 ans</option>
                <option value="36-50 ans">36-50 ans</option>
                <option value="50 ans et plus">50 ans et plus</option>
            </select>

            <input type="text" name="telephone" placeholder="Téléphone">

            <select name="saveur_choisie" required>
                <option value="" disabled selected>Sélectionnez la saveur</option>
                <option>Pomme grande</option>
                <option>Ginger line</option>
                <option>Lemon</option>
                <option>Fraise</option>
                <option>Pêche</option>
                <option>Pomme</option>
            </select>

            <textarea name="observation" placeholder="Observation" rows="3"></textarea>
            <button type="submit" name="envoyer_fiche">Enregistrer la fiche</button>
        </form>
        <?php endif; ?>
    </div>

    <script>
    function togglePass() {
        var x = document.getElementById("passInput");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
    </script>
</body>

</html>