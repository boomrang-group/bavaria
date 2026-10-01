<?php
session_start();
if (!isset($_SESSION['admin_logged'])) header("Location: ../admin/");
require '../config/config.php';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Bavaria</title>
    <style>
    :root {
        --primary: #2c3e50;
        --secondary: #3498db;
        --bg: #f4f7f6;
    }

    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', sans-serif;
        margin: 0;
        background: var(--bg);
        color: #333;
    }

    /* Barre de navigation adaptative */
    .nav {
        background: var(--primary);
        padding: 1rem 2rem;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .nav-links {
        display: flex;
        gap: 10px;
    }

    .nav a {
        color: white;
        text-decoration: none;
        padding: 8px 15px;
        border-radius: 4px;
        transition: 0.3s;
        font-size: 0.9rem;
    }

    .nav a:hover {
        background: var(--secondary);
    }

    .container {
        padding: 20px;
        max-width: 1200px;
        margin: auto;
    }

    .card {
        background: white;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
        border-left: 5px solid var(--secondary);
    }

    /* Conteneur indispensable pour empêcher le tableau de casser le design mobile */
    .table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        margin-bottom: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        min-width: 500px; /* Force une largeur minimale interne pour garder le tableau lisible en scroll */
    }

    th {
        background: #f8f9fa;
        padding: 15px;
        text-align: left;
        text-transform: uppercase;
        font-size: 0.85rem;
        color: #7f8c8d;
    }

    td {
        padding: 15px;
        border-bottom: 1px solid #eee;
    }

    tr:hover {
        background: #f1f7fe;
    }

    h1 {
        color: var(--primary);
    }

    h2 {
        border-bottom: 2px solid var(--secondary);
        display: inline-block;
        padding-bottom: 5px;
        margin-bottom: 20px;
        font-size: 1.5rem;
    }

    /* --- MEDIA QUERIES (MOBILE) --- */
    @media (max-width: 768px) {
        .nav {
            flex-direction: column;
            gap: 15px;
            text-align: center;
            padding: 1rem;
        }

        .nav-links {
            width: 100%;
            justify-content: center;
        }

        .container {
            padding: 15px;
        }

        h1 {
            font-size: 1.8rem;
        }

        th, td {
            padding: 12px 10px;
            font-size: 0.9rem;
        }
    }
    </style>
</head>

<body>

    <div class="nav">
        <strong>BAVARIA ADMIN</strong>
        <div class="nav-links">
            <a href="creer_vendeur.php">+ Ajouter Vendeur</a>
            <a href="logout.php" style="background: rgba(255,255,255,0.1);">Déconnexion</a>
        </div>
    </div>

    <div class="container">
        <h1>Tableau de bord</h1>

        <div class="card">
            <h3>Statistiques globales</h3>
            <?php
            $count = $conn->query("SELECT COUNT(*) as total FROM participants")->fetch_assoc();
            echo "<p style='font-size: 2rem; margin: 10px 0 0 0; color: var(--secondary);'><strong>" . $count['total'] . "</strong> <small style='font-size: 1rem; color: #666;'>fiches collectées</small></p>";
            ?>
        </div>

        <h2>Liste des vendeurs</h2>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Nom du vendeur</th>
                        <th>Code unique</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $vendeurs = $conn->query("SELECT * FROM vendeurs ORDER BY nom_vendeur ASC");
                    while($v = $vendeurs->fetch_assoc()) {
                        echo "<tr><td>{$v['nom_vendeur']}</td><td><span style='background:#e8f4fd; padding:4px 8px; border-radius:4px; font-weight:bold;'>{$v['code_vendeur']}</span></td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <h2>Dernières collectes</h2>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Nom Complet</th>
                        <th>Saveur</th>
                        <th>Vendeur</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    /* BACKEND MODIFIÉ : Requête sur la colonne 'nom_complet' uniquement */
                    $data = $conn->query("SELECT nom_complet, saveur_choisie, code_vendeur, date_collecte FROM participants ORDER BY id DESC LIMIT 10");
                    while($row = $data->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['nom_complet']}</td>
                                <td>{$row['saveur_choisie']}</td>
                                <td>{$row['code_vendeur']}</td>
                                <td>" . date('d/m/Y H:i', strtotime($row['date_collecte'])) . "</td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>