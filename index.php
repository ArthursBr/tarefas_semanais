<?php
session_start();
require_once 'controller/AuthController.php';
require_once 'controller/TaskController.php';

$authController = new AuthController();
$taskController = new TaskController();

$action = $_GET['action'] ?? 'home';

switch($action) {
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->login();
        } else {
            $authController->showLogin();
        }
        break;
    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->register();
        } else {
            $authController->showRegister();
        }
        break;
    case 'logout':
        $authController->logout();
        break;
    case 'add_task':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $taskController->addTask();
        }
        break;
    case 'remove_task':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $taskController->removeTask();
        }
        break;
    case 'get_tasks':
        $taskController->getTasks();
        break;
    default:
        include 'views/tarefas.php';
        break;
}
?>