<?php
require_once 'model/Task.php';

class TaskController {
    private $taskModel;
    
    public function __construct() {
        $this->taskModel = new Task();
    }
    
    public function addTask() {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false]);
            return;
        }
        
        $taskId = $this->taskModel->create($_SESSION['user_id'], $_POST['task_name'], $_POST['day_of_week']);
        echo json_encode(['success' => (bool)$taskId]);
    }
    
    public function removeTask() {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false]);
            return;
        }
        
        $success = $this->taskModel->delete($_POST['task_id'], $_SESSION['user_id']);
        echo json_encode(['success' => $success]);
    }
    
    public function getTasks() {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode([]);
            return;
        }
        
        $tasks = $this->taskModel->getByUserId($_SESSION['user_id']);
        echo json_encode($tasks);
    }
}
?>