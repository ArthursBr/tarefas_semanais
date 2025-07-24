<?php
require_once 'model/User.php';

class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function showLogin() {
        ?>
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Login - Tarefas Semanais</title>
            <link rel="stylesheet" href="css/style.css">
        </head>
        <body>
            <div class="container">
                <div class="auth-form">
                    <h2>Login</h2>
                    <form method="POST" action="?action=login">
                        <div class="form-group">
                            <label for="username">Usuário:</label>
                            <input type="text" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Senha:</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn-primary">Entrar</button>
                    </form>
                    <p><a href="?action=register">Não tem conta? Cadastre-se</a></p>
                    <p><a href="/">Voltar ao início</a></p>
                </div>
            </div>
        </body>
        </html>
        <?php
    }
    
    public function showRegister() {
        ?>
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Cadastro - Tarefas Semanais</title>
            <link rel="stylesheet" href="css/style.css">
        </head>
        <body>
            <div class="container">
                <div class="auth-form">
                    <h2>Cadastro</h2>
                    <form method="POST" action="?action=register">
                        <div class="form-group">
                            <label for="username">Nome de Usuário:</label>
                            <input type="text" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Senha:</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn-primary">Cadastrar</button>
                    </form>
                    <p><a href="?action=login">Já tem conta? Faça login</a></p>
                    <p><a href="/">Voltar ao início</a></p>
                </div>
            </div>
        </body>
        </html>
        <?php
    }
    
    public function login() {
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        $user = $this->userModel->authenticate($username, $password);
        
        if ($user) {
            // CORREÇÃO: Usar 'id' em vez de '_id'
            $_SESSION['user_id'] = (string)$user['id'];
            $_SESSION['username'] = $user['username'];
            
            // DEBUG: Verificar se a sessão foi salva
            error_log("Login successful - User ID: " . $_SESSION['user_id'] . " Username: " . $_SESSION['username']);
            
            header('Location: /');
            exit;
        } else {
            echo "<script>alert('Credenciais inválidas!'); window.location.href='?action=login';</script>";
        }
    }
    
    public function register() {
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        if (empty($username) || empty($password)) {
            echo "<script>alert('Preencha todos os campos!'); window.location.href='?action=register';</script>";
            return;
        }
        
        $userId = $this->userModel->create($username, $password);
        
        if ($userId) {
            echo "<script>alert('Usuário cadastrado com sucesso!'); window.location.href='?action=login';</script>";
        } else {
            echo "<script>alert('Usuário já existe ou erro no cadastro!'); window.location.href='?action=register';</script>";
        }
    }
    
    public function logout() {
        session_destroy();
        header('Location: /');
        exit;
    }
}
?>