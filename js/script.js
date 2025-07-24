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
    const modal = document.getElementById('addTaskModal');
    if (modal) {
        modal.style.display = 'block';
    }
}

function closeAddTaskModal() {
    const modal = document.getElementById('addTaskModal');
    if (modal) {
        modal.style.display = 'none';
        document.getElementById('addTaskForm').reset();
    }
}

function toggleRemoveMode() {
    removeMode = !removeMode;
    const tasks = document.querySelectorAll('.task-item');
    const removeButton = document.querySelector('.btn-secondary');
    
    if (removeMode) {
        tasks.forEach(task => {
            task.classList.add('remove-mode');
        });
        if (removeButton) {
            removeButton.textContent = 'Cancelar';
            removeButton.style.background = 'linear-gradient(45deg, #ff6b6b, #ee5a24)';
        }
    } else {
        tasks.forEach(task => {
            task.classList.remove('remove-mode');
        });
        if (removeButton) {
            removeButton.textContent = 'Remover';
            removeButton.style.background = 'linear-gradient(45deg, #f093fb, #f5576c)';
        }
    }
}

function addTask() {
    const taskName = document.getElementById('taskName').value;
    const dayOfWeek = document.getElementById('dayOfWeek').value;
    
    if (!taskName || !dayOfWeek) {
        alert('Preencha todos os campos!');
        return;
    }
    
    fetch('?action=add_task', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `task_name=${encodeURIComponent(taskName)}&day_of_week=${encodeURIComponent(dayOfWeek)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeAddTaskModal();
            loadTasks();
            showNotification('Tarefa adicionada com sucesso!', 'success');
        } else {
            showNotification(data.message || 'Erro ao adicionar tarefa', 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showNotification('Erro ao adicionar tarefa', 'error');
    });
}

function removeTask(taskId) {
    if (!removeMode) return;
    
    if (confirm('Tem certeza que deseja remover esta tarefa?')) {
        fetch('?action=remove_task', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `task_id=${encodeURIComponent(taskId)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadTasks();
                showNotification('Tarefa removida com sucesso!', 'success');
            } else {
                showNotification(data.message || 'Erro ao remover tarefa', 'error');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showNotification('Erro ao remover tarefa', 'error');
        });
    }
}

function loadTasks() {
    fetch('?action=get_tasks')
        .then(response => response.json())
        .then(tasks => {
            const days = ['segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'domingo'];
            days.forEach(day => {
                const container = document.getElementById(`tasks-${day}`);
                if (container) {
                    container.innerHTML = '';
                }
            });
            
            tasks.forEach(task => {
                const taskElement = document.createElement('div');
                taskElement.className = 'task-item';
                taskElement.textContent = task.task_name;
                taskElement.onclick = () => removeTask(task.id);
                
                const dayContainer = document.getElementById(`tasks-${task.day_of_week}`);
                if (dayContainer) {
                    dayContainer.appendChild(taskElement);
                }
            });
        })
        .catch(error => {
            console.error('Erro ao carregar tarefas:', error);
        });
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.padding = '15px 25px';
    notification.style.borderRadius = '8px';
    notification.style.color = 'white';
    notification.style.fontWeight = 'bold';
    notification.style.zIndex = '9999';
    notification.style.opacity = '0';
    notification.style.transform = 'translateX(100%)';
    notification.style.transition = 'all 0.3s ease';
    
    if (type === 'success') {
        notification.style.background = 'linear-gradient(45deg, #4CAF50, #45a049)';
    } else {
        notification.style.background = 'linear-gradient(45deg, #f44336, #da190b)';
    }
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// Fechar modal clicando fora dele
window.onclick = function(event) {
    const modal = document.getElementById('addTaskModal');
    if (event.target === modal) {
        closeAddTaskModal();
    }
}

// Fechar modal com ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeAddTaskModal();
    }
});