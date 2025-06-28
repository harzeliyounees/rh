<?php

namespace App\Controllers;

use App\Config\Database;
use App\Models\User;
use PDO;
    function validateUser($data) {
    $errors = [];

    if (empty($data['username'])) {
        $errors[] = "Le nom d'utilisateur est requis.";
    }
    if (empty($data['password'])) {
        $errors[] = "Le mot de passe est requis.";
    }
    if (isset($data['password_confirmation']) && $data['password'] !== $data['password_confirmation']) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }
    if (empty($data['role']) || !in_array($data['role'], ['admin', 'viewer'])) {
        $errors[] = "Le rôle est invalide.";
    }

    return $errors;
}
class AuthController {
    private $user;
    private $db;

    public function __construct() {
        $this->user = new User();
        $this->db = Database::getInstance()->getConnection();
    }

    public function showLogin() {
        require_once __DIR__ . '/../views/auth/login.php';
    }

    

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            // Ajoutez ceci :
            $_SESSION['user'] = (object)[
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role']
            ];
                
               header('Location: /dashboard');
                exit;
            } else {
                $_SESSION['error'] = 'Identifiants invalides';
                header('Location: /login');
                exit;
            }
        }
    }

    public function showRegister() {
        if (!isAdmin()) {
            setFlashMessage('Accès non autorisé', 'danger');
            redirect('/login');
        }
        require_once __DIR__ . '/../views/auth/register.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isAdmin()) {
            redirect('/login');
        }

        $data = [
            'username' => $_POST['username'] ?? '',
            'password' => $_POST['password'] ?? '',
            'password_confirmation' => $_POST['password_confirmation'] ?? '',
            'role' => $_POST['role'] ?? 'viewer'
        ];

        $errors = validateUser($data);

        if (!empty($errors)) {
            setFlashMessage(implode('<br>', $errors), 'danger');
            redirect('/register');
        }

        try {
            if ($this->user->create($data['username'], $data['password'], $data['role'])) {
                setFlashMessage('Utilisateur créé avec succès', 'success');
                redirect('/users');
            } else {
                setFlashMessage('Erreur lors de la création de l\'utilisateur', 'danger');
                redirect('/register');
            }
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) { // Duplicate entry
                setFlashMessage('Ce nom d\'utilisateur existe déjà', 'danger');
            } else {
                setFlashMessage('Une erreur est survenue', 'danger');
                logAction('Register Error', $e->getMessage());
            }
            redirect('/register');
        }
    }

    public function logout() {
        session_destroy();
        redirect('/login');
    }
    

    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isAuthenticated()) {
            redirect('/login');
        }

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            setFlashMessage('Tous les champs sont requis', 'danger');
            redirect('/profile');
        }

        if ($newPassword !== $confirmPassword) {
            setFlashMessage('Les nouveaux mots de passe ne correspondent pas', 'danger');
            redirect('/profile');
        }

        try {
            if ($this->user->updatePassword($_SESSION['user']->getId(), $currentPassword, $newPassword)) {
                setFlashMessage('Mot de passe modifié avec succès', 'success');
                redirect('/logout');
            } else {
                setFlashMessage('Mot de passe actuel incorrect', 'danger');
                redirect('/profile');
            }
        } catch (\Exception $e) {
            setFlashMessage('Une erreur est survenue', 'danger');
            logAction('Password Change Error', $e->getMessage());
            redirect('/profile');
        }
    }
}