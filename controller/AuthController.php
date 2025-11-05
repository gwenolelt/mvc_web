<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../model/Utilisateur.php';
require_once __DIR__ . '/../config/database.php';

class AuthController {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            // Connexion BDD
            $database = new Database();
            $db = $database->getConnection();

            // Passez la connexion au modèle
            $utilisateurModel = new Utilisateur($db);
            $user = $utilisateurModel->getUser($username);
            
            if ($user && password_verify($password, $user->uti_mdp)) {
                $_SESSION['user'] = $user;
                header("Location: index.php");
                exit;
            } else {
                $erreur = "Identifiants invalides";
            }
        }
        include __DIR__ . '/../view/login.php';
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?action=login");
        exit;
    }
}
