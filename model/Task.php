<?php
require_once 'Database.php';

class Task {
    private $collection;
    
    public function __construct() {
        $database = new Database();
        $this->collection = $database->getCollection('tasks');
    }
    
    public function create($userId, $taskName, $dayOfWeek) {
        return $this->collection->insertOne([
            'user_id' => $userId,
            'task_name' => $taskName,
            'day_of_week' => $dayOfWeek
        ]);
    }
    
    public function getByUserId($userId) {
        return $this->collection->find(['user_id' => $userId]);
    }
    
    public function delete($taskId, $userId) {
        return $this->collection->deleteOne(['id' => $taskId]) > 0;
    }
}
?>