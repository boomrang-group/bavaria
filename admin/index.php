<?php
session_start();
require '../config/config.php';

$error = "";
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result && password_verify($password, $result['password'])) {
        $_SESSION['admin_logged'] = true;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Identifiants invalides.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bavaria | Connexion</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body { 
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            margin: 0; 
            padding: 20px;
        }

        .login-card { 
            background: rgba(255, 255, 255, 0.95); 
            padding: 40px 30px; 
            border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.2); 
            width: 100%; 
            max-width: 380px; /* Taille optimale sur PC, fluide sur mobile */
            text-align: center; 
            backdrop-filter: blur(10px);
        }

        h2 { color: #333; margin-top: 0; margin-bottom: 5px; }
        p.subtitle { color: #666; margin-bottom: 25px; font-size: 0.9em; }
        
        input { 
            width: 100%; 
            padding: 14px; 
            margin: 10px 0; 
            border: 2px solid #eee; 
            border-radius: 8px; 
            transition: 0.3s;
            font-size: 1rem; /* Évite le zoom automatique forcé sur iOS */
        }
        input:focus { border-color: #764ba2; outline: none; }
        
        button { 
            width: 100%; 
            padding: 14px; 
            background: #764ba2; 
            border: none; 
            color: white; 
            border-radius: 8px; 
            cursor: pointer; 
            font-size: 16px; 
            font-weight: bold;
            margin-top: 15px; 
            transition: 0.3s;
        }
        button:hover { background: #5a377d; }
        
        .error { 
            color: #ff4757; 
            font-size: 0.85em; 
            margin-top: 10px; 
            background: #ffeef0; 
            padding: 10px; 
            border-radius: 5px; 
            border: 1px solid #ffa3ac;
        }

        /* --- Ajustements pour petits mobiles --- */
        @media (max-width: 400px) {
            .login-card {
                padding: 30px 20px;
            }
            h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

    <div class="login-card">
        <h2>Bienvenue</h2>
        <p class="subtitle">Espace Administration Bavaria</p>
        
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
        
        <form method="POST">
            <input type="text" name="username" placeholder="Nom d'utilisateur" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit" name="login">Connexion</button>
        </form>
    </div>

</body>
</html>