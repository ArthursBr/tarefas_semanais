let removeMode = false;

document.addEventListener('DOMContentLoaded', function() {
    loadTasks();
    
    const addTaskForm = document.getElementById('addTaskForm');
    if (addTaskForm) {
        addTaskForm.addEventListener('submit', function(e) {
            e.preventDefault();
            addTask();
        });
    }
});

function showAddTaskModal() {
    document.getElementById('addTaskModal').style.display = 'block';
}

function closeAddTaskModal() {
    document.getElementById('addTaskModal').style.display = 'none';
    document.getElementById('addTaskForm').reset();
}

function toggleRemoveMode() {
    removeMode = !removeMode;
    const tasks = document.querySelectorAll('.task-item');
    
    tasks.forEach(task => {
        removeMode ? task.classList.add('remove-mode') : task.classList.remove('remove-mode');
    });
}

function addTask() {
    const taskName = document.getElementById('taskName').value;
    const dayOfWeek = document.getElementById('dayOfWeek').value;
    
    fetch('?action=add_task', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `task_name=${encodeURIComponent(taskName)}&day_of_week=${encodeURIComponent(dayOfWeek)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeAddTaskModal();
            loadTasks();
        }
    });
}

function removeTask(taskId) {
    if (!removeMode) return;
    
    if (confirm('Remover esta tarefa?')) {
        fetch('?action=remove_task', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `task_id=${encodeURIComponent(taskId)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) loadTasks();
        });
    }
}

function loadTasks() {
    fetch('?action=get_tasks')
        .then(response => response.json())
        .then(tasks => {
            ['segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'domingo'].forEach(day => {
                const container = document.getElementById(`tasks-${day}`);
                if (container) container.innerHTML = '';
            });
            
            tasks.forEach(task => {
                const taskElement = document.createElement('div');
                taskElement.className = 'task-item';
                taskElement.textContent = task.task_name;
                taskElement.onclick = () => removeTask(task.id);
                
                const dayContainer = document.getElementById(`tasks-${task.day_of_week}`);
                if (dayContainer) dayContainer.appendChild(taskElement);
            });
        });
}

window.onclick = function(event) {
    if (event.target === document.getElementById('addTaskModal')) {
        closeAddTaskModal();
    }
}