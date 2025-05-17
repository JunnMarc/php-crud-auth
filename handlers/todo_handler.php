<?php
require_once '../config/database.php';

class TodoHandler {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createTodo($user_id, $title, $description) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO todos (user_id, title, description, status) VALUES (?, ?, ?, 'pending')");
            return $stmt->execute([$user_id, $title, $description]);
        } catch(PDOException $e) {
            return false;
        }
    }

    public function getTodos($user_id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM todos WHERE user_id = ? ORDER BY created_at DESC");
            $stmt->execute([$user_id]);
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            return [];
        }
    }

    public function updateTodoStatus($todo_id, $user_id, $status) {
        try {
            $stmt = $this->pdo->prepare("UPDATE todos SET status = ? WHERE id = ? AND user_id = ?");
            return $stmt->execute([$status, $todo_id, $user_id]);
        } catch(PDOException $e) {
            return false;
        }
    }

    public function deleteTodo($todo_id, $user_id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM todos WHERE id = ? AND user_id = ?");
            return $stmt->execute([$todo_id, $user_id]);
        } catch(PDOException $e) {
            return false;
        }
    }
}

$todo = new TodoHandler($pdo);
?> 