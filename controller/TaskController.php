<?php
require_once 'model/Task.php';

class TaskController {
    private $taskModel;
    
    public function __construct() {
        $this->taskModel = new Task();
    }
    
    public function addTask() {
        // CORREÇÃO: Verificação mais robusta de sessão
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Usuário não logado']);
            return;
        }
        
        // Verificar se dados foram enviados
        if (!isset($_POST['task_name']) || !isset($_POST['day_of_week'])) {
            echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
            return;
        }
        
        $taskName = trim($_POST['task_name']);
        $dayOfWeek = trim($_POST['day_of_week']);
        $userId = $_SESSION['user_id'];
        
        // Validar dados
        if (empty($taskName) || empty($dayOfWeek)) {
            echo json_encode(['success' => false, 'message' => 'Preencha todos os campos']);
            return;
        }
        
        // DEBUG
        error_log("Adding task - User ID: $userId, Task: $taskName, Day: $dayOfWeek");
        
        $taskId = $this->taskModel->create($userId, $taskName, $dayOfWeek);
        
        if ($taskId) {
            echo json_encode(['success' => true, 'message' => 'Tarefa adicionada com sucesso']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao adicionar tarefa']);
        }
    }
    
    public function removeTask() {
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Usuário não logado']);
            return;
        }
        
        if (!isset($_POST['task_id'])) {
            echo json_encode(['success' => false, 'message' => 'ID da tarefa não informado']);
            return;
        }
        
        $taskId = $_POST['task_id'];
        $userId = $_SESSION['user_id'];
        
        $success = $this->taskModel->delete($taskId, $userId);
        
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Tarefa removida com sucesso']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao remover tarefa']);
        }
    }
    
    public function getTasks() {
        // CORREÇÃO: Verificação e debug de sessão
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            error_log("Get tasks - No user session");
            echo json_encode([]);
            return;
        }
        
        $userId = $_SESSION['user_id'];
        error_log("Get tasks - User ID: $userId");
        
        $tasks = $this->taskModel->getByUserId($userId);
        error_log("Get tasks - Found " . count($tasks) . " tasks");
        
        $formattedTasks = [];
        foreach ($tasks as $task) {
            $formattedTasks[] = [
                'id' => (string)$task['id'],
                'task_name' => $task['task_name'],
                'day_of_week' => $task['day_of_week']
            ];
        }
        
        header('Content-Type: application/json');
        echo json_encode($formattedTasks);
    }
}
?>