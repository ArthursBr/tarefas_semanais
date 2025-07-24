<?php
class Database {
    private $pdo;
    
    public function __construct() {
        try {
            $this->pdo = new PDO('sqlite:tarefas.db');
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->createTables();
        } catch (Exception $e) {
            die("Erro de banco: " . $e->getMessage());
        }
    }
    
    private function createTables() {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP
        )");
        
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS tasks (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            task_name TEXT NOT NULL,
            day_of_week TEXT NOT NULL,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP
        )");
    }
    
    public function getCollection($name) {
        return new SQLiteCollection($this->pdo, $name);
    }
}

class SQLiteCollection {
    private $pdo;
    private $table;
    
    public function __construct($pdo, $table) {
        $this->pdo = $pdo;
        $this->table = $table;
    }
    
    public function findOne($filter) {
        $key = array_keys($filter)[0];
        $value = $filter[$key];
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE {$key} = ? LIMIT 1");
        $stmt->execute([$value]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // CORREÇÃO: Sempre retornar o resultado como está
        return $result ?: null;
    }
    
    public function insertOne($document) {
        $keys = implode(',', array_keys($document));
        $placeholders = ':' . implode(', :', array_keys($document));
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} ({$keys}) VALUES ({$placeholders})");
        $stmt->execute($document);
        
        return $this->pdo->lastInsertId();
    }
    
    public function find($filter) {
        $key = array_keys($filter)[0];
        $value = $filter[$key];
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE {$key} = ?");
        $stmt->execute([$value]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function deleteOne($filter) {
        $key = array_keys($filter)[0];
        $value = $filter[$key];
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE {$key} = ?");
        $stmt->execute([$value]);
        
        return $stmt->rowCount();
    }
}
?>