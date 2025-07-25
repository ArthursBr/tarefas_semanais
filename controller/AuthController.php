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
        $user = $this->userModel->authenticate($_POST['username'], $_POST['password']);
        
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: /');
        } else {
            echo "<script>alert('Credenciais inválidas!'); window.location.href='?action=login';</script>";
        }
    }
    
    public function register() {
        $userId = $this->userModel->create($_POST['username'], $_POST['password']);
        
        if ($userId) {
            echo "<script>alert('Usuário cadastrado com sucesso!'); window.location.href='?action=login';</script>";
        } else {
            echo "<script>alert('Usuário já existe!'); window.location.href='?action=register';</script>";
        }
    }
    
    public function logout() {
        session_destroy();
        header('Location: /');
    }
}
?>