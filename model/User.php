<?php
require_once 'Database.php';

class User {
    private $collection;
    
    public function __construct() {
        $database = new Database();
        $this->collection = $database->getCollection('users');
    }
    
    public function create($username, $password) {
        // Verificar se campos não estão vazios
        if (empty($username) || empty($password)) {
            return false;
        }
        
        // Verificar se usuário já existe
        $existingUser = $this->collection->findOne(['username' => $username]);
        
        if ($existingUser) {
            return false;
        }
        
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            $userId = $this->collection->insertOne([
                'username' => $username,
                'password' => $hashedPassword
            ]);
            
            return $userId;
        } catch (Exception $e) {
            error_log("Error creating user: " . $e->getMessage());
            return false;
        }
    }
    
    public function authenticate($username, $password) {
        if (empty($username) || empty($password)) {
            return false;
        }
        
        try {
            $user = $this->collection->findOne(['username' => $username]);
            
            if ($user && password_verify($password, $user['password'])) {
                return $user;
            }
            
            return false;
        } catch (Exception $e) {
            error_log("Error authenticating user: " . $e->getMessage());
            return false;
        }
    }
    
    public function findById($id) {
        try {
            return $this->collection->findOne(['id' => $id]);
        } catch (Exception $e) {
            error_log("Error finding user by ID: " . $e->getMessage());
            return false;
        }
    }
}
?>