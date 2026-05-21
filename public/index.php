<?php
ob_start();
if (getenv('APP_ENV') === 'production') {
    ini_set('display_errors', '0');
    error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
}
/**
 * Point d'entree unique de l'application (Front Controller)
 */
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/assets.php';

$action = $_GET['action'] ?? 'home';

switch ($action) {
    case 'home':
        require_once __DIR__ . '/../controllers/HomeController.php';
        HomeController::index();
        break;
    case 'contact':
        require_once __DIR__ . '/../controllers/HomeController.php';
        HomeController::contact();
        break;
    case 'legal':
        require_once __DIR__ . '/../controllers/HomeController.php';
        HomeController::legal();
        break;
    case 'confidentialite':
        require_once __DIR__ . '/../controllers/HomeController.php';
        HomeController::privacy();
        break;
    case 'cgv':
        require_once __DIR__ . '/../controllers/HomeController.php';
        HomeController::cgv();
        break;

    case 'login':
        require_once __DIR__ . '/../controllers/AuthController.php';
        AuthController::login();
        break;
    case 'register':
        require_once __DIR__ . '/../controllers/AuthController.php';
        AuthController::register();
        break;
    case 'logout':
        require_once __DIR__ . '/../controllers/AuthController.php';
        AuthController::logout();
        break;
    case 'forgot-password':
        require_once __DIR__ . '/../controllers/AuthController.php';
        AuthController::forgotPassword();
        break;
    case 'reset-password':
        require_once __DIR__ . '/../controllers/AuthController.php';
        AuthController::resetPassword();
        break;

    case 'characters':
        require_once __DIR__ . '/../controllers/CharacterController.php';
        CharacterController::index();
        break;
    case 'character-detail':
        require_once __DIR__ . '/../controllers/CharacterController.php';
        CharacterController::detail();
        break;
    case 'add-review':
        require_once __DIR__ . '/../controllers/CharacterController.php';
        CharacterController::addReview();
        break;

    case 'dashboard':
        require_once __DIR__ . '/../controllers/UserController.php';
        UserController::dashboard();
        break;
    case 'character-create':
        require_once __DIR__ . '/../controllers/UserController.php';
        UserController::createCharacter();
        break;
    case 'character-edit':
        require_once __DIR__ . '/../controllers/UserController.php';
        UserController::editCharacter();
        break;
    case 'character-delete':
        require_once __DIR__ . '/../controllers/UserController.php';
        UserController::deleteCharacter();
        break;
    case 'character-duplicate':
        require_once __DIR__ . '/../controllers/UserController.php';
        UserController::duplicateCharacter();
        break;
    case 'character-share':
        require_once __DIR__ . '/../controllers/UserController.php';
        UserController::shareCharacter();
        break;
    case 'character-add-accessory':
        require_once __DIR__ . '/../controllers/UserController.php';
        UserController::addAccessory();
        break;

    case 'employee-dashboard':
        require_once __DIR__ . '/../controllers/EmployeeController.php';
        EmployeeController::dashboard();
        break;
    case 'employee-validate-character':
        require_once __DIR__ . '/../controllers/EmployeeController.php';
        EmployeeController::validateCharacter();
        break;
    case 'employee-validate-review':
        require_once __DIR__ . '/../controllers/EmployeeController.php';
        EmployeeController::validateReview();
        break;
    case 'employee-add-accessory':
        require_once __DIR__ . '/../controllers/EmployeeController.php';
        EmployeeController::addAccessory();
        break;
    case 'employee-disable-accessory':
        require_once __DIR__ . '/../controllers/EmployeeController.php';
        EmployeeController::disableAccessory();
        break;
    case 'employee-delete-character':
        require_once __DIR__ . '/../controllers/EmployeeController.php';
        EmployeeController::deleteCharacter();
        break;
    case 'employee-suspend-user':
        require_once __DIR__ . '/../controllers/EmployeeController.php';
        EmployeeController::suspendUser();
        break;
    case 'employee-delete-user':
        require_once __DIR__ . '/../controllers/EmployeeController.php';
        EmployeeController::deleteUser();
        break;

    case 'admin-dashboard':
        require_once __DIR__ . '/../controllers/AdminController.php';
        AdminController::dashboard();
        break;
    case 'admin-create-employee':
        require_once __DIR__ . '/../controllers/AdminController.php';
        AdminController::createEmployee();
        break;
    case 'admin-logs':
        require_once __DIR__ . '/../controllers/AdminController.php';
        AdminController::logs();
        break;
    case 'admin-manage-employee':
        require_once __DIR__ . '/../controllers/AdminController.php';
        AdminController::manageEmployee();
        break;

    default:
        http_response_code(404);
        echo 'Page non trouvee';
        break;
}
