<?php
include('db.php');  // Inclure le fichier de connexion à la base de données

// Gestion de l'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validation simple
    if (!empty($username) && !empty($email) && !empty($password)) {
        // Hachage du mot de passe avant l'enregistrement
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insérer dans la base de données
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashedPassword);

        if ($stmt->execute()) {
            // Rediriger vers index.html après inscription réussie
            header("Location: index.html");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Gestion de la connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validation simple
    if (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $username, $hashedPassword);

        if ($stmt->num_rows > 0) {
            $stmt->fetch();

            // Vérification du mot de passe
            if (password_verify($password, $hashedPassword)) {
                // Rediriger vers index.html après connexion réussie
                header("Location: index.html");
                exit();
            } else {
                echo "Incorrect password.";
            }
        } else {
            echo "No user found with this email.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Binance Login & Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f3eeee;
            color: #fff;
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .auth-container {
            max-width: 420px;
            padding: 40px;
            border-radius: 12px;
            background-color: #1e1e1e;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
        }
        .auth-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .auth-header img {
            width: 150px;
            margin-bottom: 10px;
        }
        .auth-header h1 {
            font-size: 28px;
            color: #fff;
            margin-top: 10px;
        }
        .nav-tabs {
            border-bottom: 2px solid #f0b90b;
        }
        .nav-tabs .nav-link {
            color: #f0b90b;
            font-size: 18px;
            font-weight: bold;
        }
        .nav-tabs .nav-link.active {
            background-color: #141414;
            color: #fff;
            border-color: #f0b90b;
        }
        .form-control {
            margin-bottom: 20px;
            background-color: #efecec;
            border: 1px solid #333;
            color: #fff;
            border-radius: 8px;
            padding: 12px;
        }
        .form-control:focus {
            background-color: #cfc9c9;
            border-color: #f0b90b;
            box-shadow: 0 0 5px rgba(240, 185, 11, 0.5);
        }
        .btn-primary {
            background-color: #f0b90b;
            border-color: #f0b90b;
            border-radius: 8px;
            padding: 12px;
            font-weight: bold;
        }
        .btn-primary:hover {
            background-color: #e5a800;
            border-color: #e5a800;
        }
        .switch-auth {
            text-align: center;
            margin-top: 20px;
        }
        .switch-auth a {
            color: #f0b90b;
            text-decoration: none;
        }
        .switch-auth a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-header">
            
            <h1>Welcome !</h1>
        </div>
        <ul class="nav nav-tabs" id="authTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab" aria-controls="login" aria-selected="true">Login</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="signup-tab" data-bs-toggle="tab" data-bs-target="#signup" type="button" role="tab" aria-controls="signup" aria-selected="false">Sign Up</button>
            </li>
        </ul>
        <div class="tab-content" id="authTabContent">
            <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
                <form method="POST" action="">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                    <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
            <div class="tab-pane fade" id="signup" role="tabpanel" aria-labelledby="signup-tab">
                <form method="POST" action="">
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                    <button type="submit" name="signup" class="btn btn-primary w-100">Sign Up</button>
                </form>
            </div>
        </div>
        <div class="switch-auth">
            <p id="switch-text">Don't have an account? <a href="#signup" id="switch-link">Sign Up</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>