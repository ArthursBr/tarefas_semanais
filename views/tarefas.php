<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarefas Semanais</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Tarefas Semanais</h1>
            <div class="header-buttons">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span>Olá, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                    <button class="btn-primary" onclick="showAddTaskModal()">Adicionar</button>
                    <button class="btn-secondary" onclick="toggleRemoveMode()">Remover</button>
                    <a href="?action=logout" class="btn-danger">Logout</a>
                <?php else: ?>
                    <a href="?action=login" class="btn-primary">Login</a>
                    <a href="?action=register" class="btn-secondary">Cadastro</a>
                <?php endif; ?>
            </div>
        </header>

        <main class="week-container">
            <div class="day-column" data-day="segunda">
                <h3>Segunda-feira</h3>
                <div class="tasks-list" id="tasks-segunda"></div>
            </div>
            <div class="day-column" data-day="terca">
                <h3>Terça-feira</h3>
                <div class="tasks-list" id="tasks-terca"></div>
            </div>
            <div class="day-column" data-day="quarta">
                <h3>Quarta-feira</h3>
                <div class="tasks-list" id="tasks-quarta"></div>
            </div>
            <div class="day-column" data-day="quinta">
                <h3>Quinta-feira</h3>
                <div class="tasks-list" id="tasks-quinta"></div>
            </div>
            <div class="day-column" data-day="sexta">
                <h3>Sexta-feira</h3>
                <div class="tasks-list" id="tasks-sexta"></div>
            </div>
            <div class="day-column" data-day="sabado">
                <h3>Sábado</h3>
                <div class="tasks-list" id="tasks-sabado"></div>
            </div>
            <div class="day-column" data-day="domingo">
                <h3>Domingo</h3>
                <div class="tasks-list" id="tasks-domingo"></div>
            </div>
        </main>
    </div>

    <!-- Modal para adicionar tarefa -->
    <div id="addTaskModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddTaskModal()">&times;</span>
            <h2>Adicionar Nova Tarefa</h2>
            <form id="addTaskForm">
                <div class="form-group">
                    <label for="taskName">Nome da Tarefa:</label>
                    <input type="text" id="taskName" name="task_name" required>
                </div>
                <div class="form-group">
                    <label for="dayOfWeek">Dia da Semana:</label>
                    <select id="dayOfWeek" name="day_of_week" required>
                        <option value="">Selecione um dia</option>
                        <option value="segunda">Segunda-feira</option>
                        <option value="terca">Terça-feira</option>
                        <option value="quarta">Quarta-feira</option>
                        <option value="quinta">Quinta-feira</option>
                        <option value="sexta">Sexta-feira</option>
                        <option value="sabado">Sábado</option>
                        <option value="domingo">Domingo</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary">Adicionar Tarefa</button>
            </form>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>