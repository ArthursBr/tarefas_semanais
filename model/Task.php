<?php
require_once 'Database.php';

class Task {
    private $pdo;
    
    public function __construct() {
        $this->pdo = Database::getConnection();
    }
    
    public function create($userId, $taskName, $dayOfWeek) {
        $stmt = $this->pdo->prepare("INSERT INTO tasks (user_id, task_name, day_of_week) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $taskName, $dayOfWeek]);
        return $this->pdo->lastInsertId();
    }
    
    public function getByUserId($userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM tasks WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function delete($taskId, $userId) {
        $stmt = $this->pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
        $stmt->execute([$taskId, $userId]);
        return $stmt->rowCount() > 0;
    }
}
?>