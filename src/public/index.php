<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Controllers\AuthController;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

// Start session
session_start();

// For debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create auth controller instance
$authController = new AuthController();

// Basic routing
$request = $_SERVER['REQUEST_URI'];

// Serve static files
if (preg_match('/\.(?:css|js|jpg|jpeg|png|gif)$/', $_SERVER["REQUEST_URI"])) {
    return false;
}
// --- DYNAMIC ROUTES FIRST ---

// Handle /employees/update/{id}
if (preg_match('#^/employees/update/(\d+)$#', $request, $matches)) {
    $employeeId = $matches[1];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once __DIR__ . '/../config/Database.php';
        $db = \App\Config\Database::getInstance()->getConnection();

        $nom = $_POST['nom'] ?? '';
        $prenom = $_POST['prenom'] ?? '';
        $cin = $_POST['cin'] ?? '';
        $dateNaissance = $_POST['dateNaissance'] ?? '';
        $categorie = $_POST['categorie'] ?? '';
        $classe = $_POST['classe'] ?? '';
        $specificite = $_POST['specificite'] ?? '';
        $dateRecrutement = $_POST['dateRecrutement'] ?? '';

        try {
            $stmt = $db->prepare("UPDATE employees SET nom=?, prenom=?, cin=?, categorie=?, classe=?, specificite=?, dateNaissance=?, dateRecrutement=? WHERE id=?");
            $stmt->execute([$nom, $prenom, $cin, $categorie, $classe, $specificite, $dateNaissance, $dateRecrutement, $employeeId]);
            $_SESSION['message'] = "L'employé a été modifié avec succès !";
            $_SESSION['message_type'] = "success";
            header('Location: /employees');
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $_SESSION['message'] = "Erreur : Le CIN existe déjà dans la base de données.";
                $_SESSION['message_type'] = "danger";
                header("Location: /employees/edit/$employeeId");
                exit;
            } else {
                throw $e;
            }
        }
    } else {
        http_response_code(405);
        echo "Méthode non autorisée";
    }
    exit;
}

if (preg_match('#^/conges/edit/(\d+)$#', $request, $matches)) {
    $congeId = $matches[1];
    require_once __DIR__ . '/../config/Database.php';
    $db = \App\Config\Database::getInstance()->getConnection();

    // Fetch the congé
    $stmt = $db->prepare("SELECT * FROM conges WHERE id = ?");
    $stmt->execute([$congeId]);
    $conge = $stmt->fetch(PDO::FETCH_OBJ);

    // Fetch employees for the dropdown
    $stmtEmp = $db->query("SELECT id, nom, prenom FROM employees ORDER BY nom");
    $employees = $stmtEmp->fetchAll(PDO::FETCH_OBJ);

    if (!$conge) {
        http_response_code(404);
        echo "Congé non trouvé";
        exit;
    }

    ob_start();
    require __DIR__ . '/../views/conges/edit.php';
    $content = ob_get_clean();
    require __DIR__ . '/../layouts/main.php';
    exit;
}
if (preg_match('#^/conges/update/(\d+)$#', $request, $matches)) {
    $congeId = $matches[1];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once __DIR__ . '/../config/Database.php';
        $db = \App\Config\Database::getInstance()->getConnection();

        $employee_id = $_POST['employee_id'] ?? '';
        $type = $_POST['type'] ?? '';
        $date_debut = $_POST['dateDebut'] ?? '';
        $date_fin = $_POST['dateFin'] ?? '';
        $statut = $_POST['statut'] ?? 'pending';

        $stmt = $db->prepare("UPDATE conges SET employee_id=?, type=?, date_debut=?, date_fin=?, statut=? WHERE id=?");
        $stmt->execute([$employee_id, $type, $date_debut, $date_fin, $statut, $congeId]);

        $_SESSION['message'] = "Le congé a été modifié avec succès !";
        $_SESSION['message_type'] = "success";
        header('Location: /conges');
        exit;
    } else {
        http_response_code(405);
        echo "Méthode non autorisée";
    }
    exit;
}

if (preg_match('#^/conges/delete/(\d+)$#', $request, $matches)) {
    $congeId = $matches[1];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once __DIR__ . '/../config/Database.php';
        $db = \App\Config\Database::getInstance()->getConnection();

        $stmt = $db->prepare("DELETE FROM conges WHERE id = ?");
        $stmt->execute([$congeId]);

        $_SESSION['message'] = "Le congé a été supprimé avec succès !";
        $_SESSION['message_type'] = "success";
        header('Location: /conges');
        exit;
    } else {
        http_response_code(405);
        echo "Méthode non autorisée";
    }
    exit;
}
// Handle /employees/edit/{id}
if (preg_match('#^/employees/edit/(\d+)$#', $request, $matches)) {
    $employeeId = $matches[1];
    require_once __DIR__ . '/../config/Database.php';
    $db = \App\Config\Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT * FROM employees WHERE id = ?");
    $stmt->execute([$employeeId]);
    $employee = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$employee) {
        http_response_code(404);
        echo "Employé non trouvé";
        exit;
    }

    require __DIR__ . '/../views/employees/edit.php';
    exit;
}
if (preg_match('#^/heures-supp/update/(\d+)$#', $request, $matches)) {
    $hsId = $matches[1];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once __DIR__ . '/../config/Database.php';
        $db = \App\Config\Database::getInstance()->getConnection();

        $employee_id = $_POST['employee_id'] ?? '';
        $date = $_POST['date'] ?? '';
        $heures = $_POST['heures'] ?? '';

        $stmt = $db->prepare("UPDATE heures_supplementaires SET employee_id=?, date=?, heures=? WHERE id=?");
        $stmt->execute([$employee_id, $date, $heures, $hsId]);

        $_SESSION['message'] = "Heure supplémentaire modifiée avec succès !";
        $_SESSION['message_type'] = "success";
        header('Location: /heures-supp');
        exit;
    } else {
        http_response_code(405);
        echo "Méthode non autorisée";
    }
    exit;
}

if (preg_match('#^/heures-supp/delete/(\d+)$#', $request, $matches)) {
    $hsId = $matches[1];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once __DIR__ . '/../config/Database.php';
        $db = \App\Config\Database::getInstance()->getConnection();

        $stmt = $db->prepare("DELETE FROM heures_supplementaires WHERE id = ?");
        $stmt->execute([$hsId]);

        $_SESSION['message'] = "Heure supplémentaire supprimée avec succès !";
        $_SESSION['message_type'] = "success";
        header('Location: /heures-supp');
        exit;
    } else {
        http_response_code(405);
        echo "Méthode non autorisée";
    }
    exit;
}
if (preg_match('#^/heures-supp/edit/(\d+)$#', $request, $matches)) {
    $hsId = $matches[1];
    require_once __DIR__ . '/../config/Database.php';
    $db = \App\Config\Database::getInstance()->getConnection();

    // Fetch the heure supp
    $stmt = $db->prepare("SELECT * FROM heures_supplementaires WHERE id = ?");
    $stmt->execute([$hsId]);
    $heure_supp = $stmt->fetch(PDO::FETCH_OBJ);

    // Fetch employees for the dropdown
    $stmtEmp = $db->query("SELECT id, nom, prenom FROM employees ORDER BY nom");
    $employees = $stmtEmp->fetchAll(PDO::FETCH_OBJ);

    if (!$heure_supp) {
        http_response_code(404);
        echo "Heure supplémentaire non trouvée";
        exit;
    }

    ob_start();
    require __DIR__ . '/../views/heures-supp/edit.php';
    $content = ob_get_clean();
    require __DIR__ . '/../layouts/main.php';
    exit;
}

if (preg_match('#^/heures-nuit/edit/(\d+)$#', $request, $matches)) {
    $hnId = $matches[1];
    require_once __DIR__ . '/../config/Database.php';
    $db = \App\Config\Database::getInstance()->getConnection();

    // Fetch the heure de nuit
    $stmt = $db->prepare("SELECT * FROM heures_nuit WHERE id = ?");
    $stmt->execute([$hnId]);
    $heure_nuit = $stmt->fetch(PDO::FETCH_OBJ);

    // Fetch employees for the dropdown
    $stmtEmp = $db->query("SELECT id, nom, prenom FROM employees ORDER BY nom");
    $employees = $stmtEmp->fetchAll(PDO::FETCH_OBJ);

    if (!$heure_nuit) {
        http_response_code(404);
        echo "Heure de nuit non trouvée";
        exit;
    }

    ob_start();
    require __DIR__ . '/../views/heures-nuit/edit.php';
    $content = ob_get_clean();
    require __DIR__ . '/../layouts/main.php';
    exit;
}

if (preg_match('#^/heures-nuit/update/(\d+)$#', $request, $matches)) {
    $hnId = $matches[1];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once __DIR__ . '/../config/Database.php';
        $db = \App\Config\Database::getInstance()->getConnection();

        $employee_id = $_POST['employee_id'] ?? '';
        $date = $_POST['date'] ?? '';
        $heures = $_POST['heures'] ?? '';

        $stmt = $db->prepare("UPDATE heures_nuit SET employee_id=?, date=?, heures=? WHERE id=?");
        $stmt->execute([$employee_id, $date, $heures, $hnId]);

        $_SESSION['message'] = "Heure de nuit modifiée avec succès !";
        $_SESSION['message_type'] = "success";
        header('Location: /heures-nuit');
        exit;
    } else {
        http_response_code(405);
        echo "Méthode non autorisée";
    }
    exit;
}

if (preg_match('#^/heures-nuit/delete/(\d+)$#', $request, $matches)) {
    $hnId = $matches[1];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once __DIR__ . '/../config/Database.php';
        $db = \App\Config\Database::getInstance()->getConnection();

        $stmt = $db->prepare("DELETE FROM heures_nuit WHERE id = ?");
        $stmt->execute([$hnId]);

        $_SESSION['message'] = "Heure de nuit supprimée avec succès !";
        $_SESSION['message_type'] = "success";
        header('Location: /heures-nuit');
        exit;
    } else {
        http_response_code(405);
        echo "Méthode non autorisée";
    }
    exit;
}

if (preg_match('#^/paniers/edit/(\d+)$#', $request, $matches)) {
    $panierId = $matches[1];
    require_once __DIR__ . '/../config/Database.php';
    $db = \App\Config\Database::getInstance()->getConnection();

    // Fetch the panier
    $stmt = $db->prepare("SELECT * FROM panier WHERE id = ?");
    $stmt->execute([$panierId]);
    $panier = $stmt->fetch(PDO::FETCH_OBJ);

    // Fetch employees for the dropdown
    $stmtEmp = $db->query("SELECT id, nom, prenom FROM employees ORDER BY nom");
    $employees = $stmtEmp->fetchAll(PDO::FETCH_OBJ);

    if (!$panier) {
        http_response_code(404);
        echo "Panier non trouvé";
        exit;
    }

    ob_start();
    require __DIR__ . '/../views/paniers/edit.php';
    $content = ob_get_clean();
    require __DIR__ . '/../layouts/main.php';
    exit;
}

if (preg_match('#^/paniers/update/(\d+)$#', $request, $matches)) {
    $panierId = $matches[1];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once __DIR__ . '/../config/Database.php';
        $db = \App\Config\Database::getInstance()->getConnection();

        $employee_id = $_POST['employee_id'] ?? '';
        $date = $_POST['date'] ?? '';
        $montant = $_POST['montant'] ?? '';

        $stmt = $db->prepare("UPDATE panier SET employee_id=?, date=?, montant=? WHERE id=?");
        $stmt->execute([$employee_id, $date, $montant, $panierId]);

        $_SESSION['message'] = "Panier modifié avec succès !";
        $_SESSION['message_type'] = "success";
        header('Location: /paniers');
        exit;
    } else {
        http_response_code(405);
        echo "Méthode non autorisée";
    }
    exit;
}
if (preg_match('#^/paniers/delete/(\d+)$#', $request, $matches)) {
    $panierId = $matches[1];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once __DIR__ . '/../config/Database.php';
        $db = \App\Config\Database::getInstance()->getConnection();

        $stmt = $db->prepare("DELETE FROM panier WHERE id = ?");
        $stmt->execute([$panierId]);

        $_SESSION['message'] = "Panier supprimé avec succès !";
        $_SESSION['message_type'] = "success";
        header('Location: /paniers');
        exit;
    } else {
        http_response_code(405);
        echo "Méthode non autorisée";
    }
    exit;
}
if (preg_match('#^/users/delete/(\d+)$#', $request, $matches)) {
    $userId = $matches[1];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once __DIR__ . '/../config/Database.php';
        $db = \App\Config\Database::getInstance()->getConnection();

        $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$userId]);

        $_SESSION['message'] = "Utilisateur supprimé avec succès !";
        $_SESSION['message_type'] = "success";
        header('Location: /users');
        exit;
    } else {
        http_response_code(405);
        echo "Méthode non autorisée";
    }
    exit;
}

if (preg_match('#^/users/deactivate/(\d+)$#', $request, $matches)) {
    $userId = $matches[1];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once __DIR__ . '/../config/Database.php';
        $db = \App\Config\Database::getInstance()->getConnection();

        // Make sure your users table has an 'active' column (TINYINT(1) or BOOLEAN)
        $stmt = $db->prepare("UPDATE users SET active = 0 WHERE id = ?");
        $stmt->execute([$userId]);

        $_SESSION['message'] = "Utilisateur désactivé avec succès !";
        $_SESSION['message_type'] = "success";
        header('Location: /users');
        exit;
    } else {
        http_response_code(405);
        echo "Méthode non autorisée";
    }
    exit;
}
switch ($request) {
    case '/':
    case '/login':
        require __DIR__ . '/../views/auth/login.php';
        break;
        
    case '/auth/login':
        $authController->login();
        break;
    case '/employees':
        require_once __DIR__ . '/../config/Database.php';
        $db = \App\Config\Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM employees ORDER BY id DESC");
        $employees = $stmt->fetchAll(PDO::FETCH_OBJ);
        ob_start();
        require __DIR__ . '/../views/employees/index.php'; // Only the content!
        $content = ob_get_clean();
        require __DIR__ . '/../layouts/main.php'; // This has the HTML structure
        break;
    case '/employees/create':
        // Controller or view for creating an employee
        require __DIR__ . '/../views/employees/create.php';
        break;
    case '/employees/edit':
        // Controller or view for editing an employee   
        require __DIR__ . '/../views/employees/edit.php';
        break;
    case '/employees/delete':
        // Controller or view for deleting an employee

        require __DIR__ . '/../views/employees/delete.php';
        break;
    case '/auth/register':
        ob_start();
    require __DIR__ . '/../views/auth/register.php'; // Only the form content!
    $content = ob_get_clean();
    require __DIR__ . '/../layouts/main.php'; // This wraps it in the full template
    break;
    case '/auth/register/submit':
        $authController->register();
        break;

    case '/auth/logout':
        // Handle logout logic

        session_destroy();
        header('Location: /login');
        exit;

case '/profile':
    require_once __DIR__ . '/../config/Database.php';
    $db = \App\Config\Database::getInstance()->getConnection();
    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
        header('Location: /login');
        exit;
    }
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_OBJ);

    ob_start();
    require __DIR__ . '/../views/profile/index.php';
    $content = ob_get_clean();
    require __DIR__ . '/../layouts/main.php';
    break;
    case '/conges':
        require_once __DIR__ . '/../config/Database.php';
        $db = \App\Config\Database::getInstance()->getConnection();
        // Fetch conges with employee info
        $stmt = $db->query("SELECT c.*, e.nom, e.prenom FROM conges c JOIN employees e ON c.employee_id = e.id ORDER BY c.id DESC");
        $conges = $stmt->fetchAll(PDO::FETCH_OBJ);
        ob_start();
        require __DIR__ . '/../views/conges/index.php'; // Only the content!
        $content = ob_get_clean();
        require __DIR__ . '/../layouts/main.php'; // This has the HTML structure
    break;
    case '/conges/create':
    // Fetch employees for the select dropdown
    require_once __DIR__ . '/../config/Database.php';
    $db = \App\Config\Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT id, nom, prenom FROM employees ORDER BY nom");
    $employees = $stmt->fetchAll(PDO::FETCH_OBJ);

    ob_start();
    require __DIR__ . '/../views/conges/create.php';
    $content = ob_get_clean();
    require __DIR__ . '/../layouts/main.php';
    break;
    case '/conges/store':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $employee_id = $_POST['employee_id'] ?? '';
        $type = $_POST['type'] ?? '';
        $date_debut = $_POST['dateDebut'] ?? '';
        $date_fin = $_POST['dateFin'] ?? '';
        $statut = $_POST['statut'] ?? 'pending';

        require_once __DIR__ . '/../config/Database.php';
        $db = \App\Config\Database::getInstance()->getConnection();

        $stmt = $db->prepare("INSERT INTO conges (employee_id, type, date_debut, date_fin, statut) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$employee_id, $type, $date_debut, $date_fin, $statut]);

        $_SESSION['message'] = "Le congé a été créé avec succès !";
        $_SESSION['message_type'] = "success";
        header('Location: /conges');
        exit;
    } else {
        http_response_code(405);
        echo "Méthode non autorisée";
    }
    break;
case '/heures-supp':
    require_once __DIR__ . '/../config/Database.php';
    $db = \App\Config\Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT hs.*, e.nom, e.prenom FROM heures_supplementaires hs JOIN employees e ON hs.employee_id = e.id ORDER BY hs.id DESC");
    $heures_supp = $stmt->fetchAll(PDO::FETCH_OBJ);
    ob_start();
    require __DIR__ . '/../views/heures-supp/index.php';
    $content = ob_get_clean();
    require __DIR__ . '/../layouts/main.php';
    break;
case '/heures-supp/create':
    require_once __DIR__ . '/../config/Database.php';
    $db = \App\Config\Database::getInstance()->getConnection();
    // Fetch employees for the dropdown
    $stmt = $db->query("SELECT id, nom, prenom FROM employees ORDER BY nom");
    $employees = $stmt->fetchAll(PDO::FETCH_OBJ);

    ob_start();
    require __DIR__ . '/../views/heures-supp/create.php';
    $content = ob_get_clean();
    require __DIR__ . '/../layouts/main.php';
    break;
case '/heures-supp/store':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $employee_id = $_POST['employee_id'] ?? '';
        $date = $_POST['date'] ?? '';
        $heures = $_POST['heures'] ?? '';

        require_once __DIR__ . '/../config/Database.php';
        $db = \App\Config\Database::getInstance()->getConnection();

        $stmt = $db->prepare("INSERT INTO heures_supplementaires (employee_id, date, heures) VALUES (?, ?, ?)");
        $stmt->execute([$employee_id, $date, $heures]);

        $_SESSION['message'] = "Heure supplémentaire ajoutée avec succès !";
        $_SESSION['message_type'] = "success";
        header('Location: /heures-supp');
        exit;
    } else {
        http_response_code(405);
        echo "Méthode non autorisée";
    }
    break;
case '/heures-nuit':
    require_once __DIR__ . '/../config/Database.php';
    $db = \App\Config\Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT hn.*, e.nom, e.prenom FROM heures_nuit hn JOIN employees e ON hn.employee_id = e.id ORDER BY hn.id DESC");
    $heures_nuit = $stmt->fetchAll(PDO::FETCH_OBJ);
    ob_start();
    require __DIR__ . '/../views/heures-nuit/index.php';
    $content = ob_get_clean();
    require __DIR__ . '/../layouts/main.php';
    break;
case '/heures-nuit/create':
    require_once __DIR__ . '/../config/Database.php';
    $db = \App\Config\Database::getInstance()->getConnection();
    // Fetch employees for the dropdown
    $stmt = $db->query("SELECT id, nom, prenom FROM employees ORDER BY nom");
    $employees = $stmt->fetchAll(PDO::FETCH_OBJ);

    ob_start();
    require __DIR__ . '/../views/heures-nuit/create.php';
    $content = ob_get_clean();
    require __DIR__ . '/../layouts/main.php';
    break;
case '/heures-nuit/store':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $employee_id = $_POST['employee_id'] ?? '';
        $date = $_POST['date'] ?? '';
        $heures = $_POST['heures'] ?? '';

        require_once __DIR__ . '/../config/Database.php';
        $db = \App\Config\Database::getInstance()->getConnection();

        $stmt = $db->prepare("INSERT INTO heures_nuit (employee_id, date, heures) VALUES (?, ?, ?)");
        $stmt->execute([$employee_id, $date, $heures]);

        $_SESSION['message'] = "Heure de nuit ajoutée avec succès !";
        $_SESSION['message_type'] = "success";
        header('Location: /heures-nuit');
        exit;
    } else {
        http_response_code(405);
        echo "Méthode non autorisée";
    }
    break;

case '/paniers':
    require_once __DIR__ . '/../config/Database.php';
    $db = \App\Config\Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT p.*, e.nom, e.prenom FROM panier p JOIN employees e ON p.employee_id = e.id ORDER BY p.id DESC");
    $paniers = $stmt->fetchAll(PDO::FETCH_OBJ);
    ob_start();
    require __DIR__ . '/../views/paniers/index.php';
    $content = ob_get_clean();
    require __DIR__ . '/../layouts/main.php';
    break;
case '/paniers/store':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $employee_id = $_POST['employee_id'] ?? '';
        $date = $_POST['date'] ?? '';
        $montant = $_POST['montant'] ?? '';

        require_once __DIR__ . '/../config/Database.php';
        $db = \App\Config\Database::getInstance()->getConnection();

        $stmt = $db->prepare("INSERT INTO panier (employee_id, date, montant) VALUES (?, ?, ?)");
        $stmt->execute([$employee_id, $date, $montant]);

        $_SESSION['message'] = "Panier ajouté avec succès !";
        $_SESSION['message_type'] = "success";
        header('Location: /paniers');
        exit;
    } else {
        http_response_code(405);
        echo "Méthode non autorisée";
    }
    break;
case '/paniers/create':
    require_once __DIR__ . '/../config/Database.php';
    $db = \App\Config\Database::getInstance()->getConnection();
    // Fetch employees for the dropdown
    $stmt = $db->query("SELECT id, nom, prenom FROM employees ORDER BY nom");
    $employees = $stmt->fetchAll(PDO::FETCH_OBJ);

    ob_start();
    require __DIR__ . '/../views/paniers/create.php';
    $content = ob_get_clean();
    require __DIR__ . '/../layouts/main.php';
    break;

    case '/deplacements':
        // Controller or view for managing travel expenses
        require __DIR__ . '/../views/deplacements/index.php';
        break;
    case '/logout':
        // Handle logout logic
        session_destroy();
        header('Location: /login');
        exit;
case '/dashboard':
    require_once __DIR__ . '/../config/Database.php';
    $db = \App\Config\Database::getInstance()->getConnection();

    $totalEmployees = $db->query("SELECT COUNT(*) FROM employees")->fetchColumn();
    $totalConges = $db->query("SELECT COUNT(*) FROM conges")->fetchColumn();
    $totalHeuresSupp = $db->query("SELECT COUNT(*) FROM heures_supplementaires")->fetchColumn();
    $totalPaniers = $db->query("SELECT COUNT(*) FROM panier")->fetchColumn();

    ob_start();
    require __DIR__ . '/../views/dashboard/index.php';
    $content = ob_get_clean();
    require __DIR__ . '/../layouts/main.php';
    break;
    case '/settings':
        // Controller or view for settings
        require __DIR__ . '/../views/settings/index.php';
        break;
    case '/about':
        // Controller or view for the about page
        require __DIR__ . '/../views/about/index.php';
        break;
    case '/contact':
        // Controller or view for the contact page
        require __DIR__ . '/../views/contact/index.php';
        break;
    case '/help':

        // Controller or view for the help page
        require __DIR__ . '/../views/help/index.php';

        break;
case '/employees/store':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = $_POST['nom'] ?? '';
        $prenom = $_POST['prenom'] ?? '';
        $cin = $_POST['cin'] ?? '';
        $dateNaissance = $_POST['dateNaissance'] ?? '';
        $categorie = $_POST['categorie'] ?? '';
        $classe = $_POST['classe'] ?? '';
        $specificite = $_POST['specificite'] ?? '';
        $dateRecrutement = $_POST['dateRecrutement'] ?? '';

        require_once __DIR__ . '/../config/Database.php';
        $db = \App\Config\Database::getInstance()->getConnection();

        try {
            $stmt = $db->prepare("INSERT INTO employees (nom, prenom, cin, categorie, classe, specificite, dateNaissance, dateRecrutement) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nom, $prenom, $cin, $categorie, $classe, $specificite, $dateNaissance, $dateRecrutement]);
            $_SESSION['message'] = "L'employé a été créé avec succès !";
            $_SESSION['message_type'] = "success";
            header('Location: /employees');
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $_SESSION['message'] = "Erreur : Le CIN existe déjà dans la base de données.";
                $_SESSION['message_type'] = "danger";
                header('Location: /employees');
                exit;
            } else {
                throw $e;
            }
        }
    } else {
        http_response_code(405);
        echo "Méthode non autorisée";
    }
    break;
case '/users':
    require_once __DIR__ . '/../config/Database.php';
    $db = \App\Config\Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT * FROM users ORDER BY id DESC");
    $users = $stmt->fetchAll(PDO::FETCH_OBJ); // <-- must be this variable name
    ob_start();
    require __DIR__ . '/../views/users/index.php';
    $content = ob_get_clean();
    require __DIR__ . '/../layouts/main.php';
    break;
case '/register':
    header('Location: /auth/register');
    exit;
default:

    http_response_code(404);
    require __DIR__ . '/../views/404.php';
    break;
}