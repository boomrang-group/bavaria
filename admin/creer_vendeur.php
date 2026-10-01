<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
    header("Location: login.php");
    exit();
}
require '../config/config.php';

$message = "";
if (isset($_POST['valider'])) {
    $nom = $_POST['nom_vendeur'];
    $code = $_POST['code_vendeur'];

    $stmt = $conn->prepare("INSERT INTO vendeurs (nom_vendeur, code_vendeur) VALUES (?, ?)");
    if ($stmt && $stmt->bind_param("ss", $nom, $code) && $stmt->execute()) {
        $message = "Succès : Vendeur <strong>$nom</strong> ajouté avec le code <strong>$code</strong>.";
    } else {
        $message = "Erreur : Ce code est déjà utilisé ou une erreur est survenue.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Vendeur | H-Event</title>
    <style>
        :root { --primary: #2c3e50; --accent: #3498db; --bg: #f4f7f6; }
        
        * {
            box-sizing: border-box;
        }

        body { 
            font-family: 'Segoe UI', sans-serif; 
            margin: 0; 
            background: var(--bg); 
        }
        
        /* Navbar Responsive */
        .navbar { 
            background: var(--primary); 
            padding: 1rem 2rem; 
            color: white; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
        }

        .navbar-links {
            display: flex;
        }

        .navbar a { 
            color: white; 
            text-decoration: none; 
            margin-left: 20px; 
            font-weight: bold; 
        }
        
        .navbar a:hover { color: var(--accent); }

        /* Formulaire adaptatif */
        .container { 
            max-width: 600px; 
            width: calc(100% - 40px);
            margin: 40px auto; 
            background: white; 
            padding: 30px; 
            border-radius: 12px; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.05); 
        }
        
        h1 {
            margin-top: 0;
            color: var(--primary);
        }

        input { 
            width: 100%; 
            padding: 12px; 
            margin: 10px 0 20px 0; 
            border: 1px solid #ddd; 
            border-radius: 6px; 
            font-size: 1rem;
        }

        button { 
            padding: 12px 20px; 
            border-radius: 6px; 
            border: none; 
            cursor: pointer; 
            font-weight: bold; 
            transition: 0.3s; 
            font-size: 1rem;
        }

        .btn-gen { 
            background: #95a5a6; 
            color: white; 
            width: 100%; /* S'adapte mieux sur tous les écrans */
            margin-bottom: 10px;
        }
        
        .btn-gen:hover { background: #7f8c8d; }

        .btn-save { 
            background: var(--accent); 
            color: white; 
            width: 100%; 
            margin-top: 10px;
        }
        
        .btn-save:hover { background: #2980b9; }

        .alert { 
            padding: 15px; 
            border-radius: 6px; 
            margin-bottom: 20px; 
            background: #e8f8f5; 
            color: #27ae60; 
            border: 1px solid #27ae60; 
        }

        /* --- MEDIA QUERIES (MOBILE) --- */
        @media (max-width: 576px) {
            .navbar {
                flex-direction: column;
                gap: 12px;
                text-align: center;
                padding: 1rem;
            }

            .navbar-links {
                width: 100%;
                justify-content: center;
            }

            .navbar a {
                margin: 0 10px;
            }

            .container {
                margin: 20px auto;
                padding: 20px 15px;
                width: calc(100% - 20px);
            }

            h1 {
                font-size: 1.6rem;
            }
        }
    </style>
</head>
<body>

    <div class="navbar">
        <div><strong>H-EVENT</strong> Administration</div>
        <div class="navbar-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Déconnexion</a>
        </div>
    </div>

    <div class="container">
        <h1>Créer un vendeur</h1>
        
        <?php if ($message) echo "<div class='alert'>$message</div>"; ?>

        <form method="POST">
            <label>Code vendeur :</label>
            <input type="text" name="code_vendeur" id="code_vendeur" readonly required style="background:#f9f9f9;">
            <button type="button" class="btn-gen" onclick="genererCode()">Générer un code aléatoire</button>
            
            <br><br>
            
            <label>Nom du vendeur :</label>
            <input type="text" name="nom_vendeur" placeholder="Entrez le nom complet" required>
            
            <button type="submit" name="valider" class="btn-save">Enregistrer</button>
        </form>
    </div>

    <script>
    function genererCode() {
        const caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let code = '';
        for (let i = 0; i < 5; i++) {
            code += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
        }
        document.getElementById('code_vendeur').value = code;
    }
    // Génération automatique au chargement
    window.onload = genererCode;
    </script>
</body>
</html>