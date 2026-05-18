<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kanban Task Board</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f5f7; margin: 20px; }
        .board-container { display: flex; gap: 20px; align-items: flex-start; }
        .column { flex: 1; background: #ebecf0; padding: 15px; border-radius: 6px; min-height: 500px; }
        .column h3 { margin-top: 0; color: #333; }
        
        /* Task Cards Layout Structural Stylings */
        .task-card { background: #fff; padding: 12px; margin-bottom: 10px; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 2px solid transparent; transition: border-color 0.2s; }
        .priority-badge { display: inline-block; padding: 2px 6px; font-size: 11px; font-weight: bold; border-radius: 3px; text-transform: uppercase; margin-bottom: 5px; }
        
        /* Requirement 1 Styles: Colour Coded Priorities */
        .priority-low { background: #e2e8f0; color: #4a5568; }
        .priority-medium { background: #fef3c7; color: #d97706; }
        .priority-high { background: #fee2e2; color: #dc2626; }
        
        /* Requirement 4 Style: Overdue border styling target */
        .border-red-500 { border-color: #ef4444 !important; }
        
        .card-footer { display: flex; justify-content: space-between; align-items: center; margin-top: 10px; font-size: 12px; color: #666; }
        .initials-avatar { background: #0052cc; color: #fff; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: bold; font-size: 10px; }
        .nav-buttons button { background: #fff; border: 1px solid #ccc; padding: 2px 6px; cursor: pointer; border-radius: 3px; font-weight: bold; }
        .nav-buttons button:hover { background: #f0f0f0; }

        /* Dialog Modal UI Framework */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; }
        .modal-content { background: #fff; padding: 20px; border-radius: 6px; width: 400px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        .btn-primary { background: #0052cc; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; }
        .error-banner { background: #fee2e2; color: #b91c1c; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
    </style>
</head>
<body>

    <h2>Project Task Board</h2>
    
    <button class="btn-primary" onclick="toggleModal(true)" style="margin-bottom: 20px;">+ New Task</button>

    <?php if (!empty($_SESSION['form_errors'])): ?>
        <div class="error-banner">
            <strong>Validation Errors:</strong>
            <ul>
                <?php foreach ($_SESSION['form_errors'] as $err): ?>
                    <li><?= htmlspecialchars($err); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php unset($_SESSION['form_errors']); ?>
    <?php endif; ?>

    <div class="board-container">
        
        <div class="column" id="todo-column">
            <h3>To Do</h3>
            <?php foreach ($todo as $task): ?>
                <?php 
                    $words = explode(" ", $task['name'] ?? 'Unassigned');
                    $initials = strtoupper(substr($words[0][0] ?? '', 0, 1) . substr($words[1][0] ?? '', 0, 1));
                ?>
                <div class="task-card" id="task-<?= $task['id']; ?>" data-due-date="<?= $task['due_date']; ?>" data-status="todo">
                    <span class="priority-badge priority-<?= htmlspecialchars($task['priority']); ?>"><?= htmlspecialchars($task['priority']); ?></span>
                    <h4 style="margin: 5px 0;"><?= htmlspecialchars($task['title']); ?></h4>
                    <p style="font-size:13px; color:#555; margin:5px 0;"><?= htmlspecialchars($task['description']); ?></p>
                    <div class="card-footer">
                        <span class="initials-avatar" title="<?= htmlspecialchars($task['name']); ?>"><?= $initials; ?></span>
                        <small>Due: <?= $task['due_date']; ?></small>
                        <div class="nav-buttons">
                            <button onclick="shiftTask(<?= $task['id']; ?>, 'in-progress')">→</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="column" id="in-progress-column">
            <h3>In Progress</h3>
            <?php foreach ($inprogress as $task): ?>
                <?php 
                    $words = explode(" ", $task['name'] ?? 'Unassigned');
                    $initials = strtoupper(substr($words[0][0] ?? '', 0, 1) . substr($words[1][0] ?? '', 0, 1));
                ?>
                <div class="task-card" id="task-<?= $task['id']; ?>" data-due-date="<?= $task['due_date']; ?>" data-status="in-progress">
                    <span class="priority-badge priority-<?= htmlspecialchars($task['priority']); ?>"><?= htmlspecialchars($task['priority']); ?></span>
                    <h4 style="margin: 5px 0;"><?= htmlspecialchars($task['title']); ?></h4>
                    <p style="font-size:13px; color:#555; margin:5px 0;"><?= htmlspecialchars($task['description']); ?></p>
                    <div class="card-footer">
                        <span class="initials-avatar" title="<?= htmlspecialchars($task['name']); ?>"><?= $initials; ?></span>
                        <small>Due: <?= $task['due_date']; ?></small>
                        <div class="nav-buttons">
                            <button onclick="shiftTask(<?= $task['id']; ?>, 'todo')">←</button>
                            <button onclick="shiftTask(<?= $task['id']; ?>, 'done')">→</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="column" id="done-column">
            <h3>Done</h3>
            <?php foreach ($done as $task): ?>
                <?php 
                    $words = explode(" ", $task['name'] ?? 'Unassigned');
                    $initials = strtoupper(substr($words[0][0] ?? '', 0, 1) . substr($words[1][0] ?? '', 0, 1));
                ?>
                <div class="task-card" id="task-<?= $task['id']; ?>" data-due-date="<?= $task['due_date']; ?>" data-status="done">
                    <span class="priority-badge priority-<?= htmlspecialchars($task['priority']); ?>"><?= htmlspecialchars($task['priority']); ?></span>
                    <h4 style="margin: 5px 0;"><?= htmlspecialchars($task['title']); ?></h4>
                    <p style="font-size:13px; color:#555; margin:5px 0;"><?= htmlspecialchars($task['description']); ?></p>
                    <div class="card-footer">
                        <span class="initials-avatar" title="<?= htmlspecialchars($task['name']); ?>"><?= $initials; ?></span>
                        <small>Due: <?= $task['due_date']; ?></small>
                        <div class="nav-buttons">
                            <button onclick="shiftTask(<?= $task['id']; ?>, 'in-progress')">←</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>

    <div class="modal" id="taskModal">
        <div class="modal-content">
            <h3>Create New Task</h3>
            <form action="/index.php?action=create" method="POST">
                <div class="form-group">
                    <label>Task Title *</label>
                    <input type="text" name="title" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label>Assign Member *</label>
                    <select name="assigned_to" required>
                        <option value="">-- Choose Member --</option>
                        <?php foreach ($members as $m): ?>
                            <option value="<?= $m['id']; ?>"><?= htmlspecialchars($m['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Priority</label>
                    <div style="display:flex; gap:15px; padding-top:5px;">
                        <label style="font-weight:normal;"><input type="radio" name="priority" value="low" checked> Low</label>
                        <label style="font-weight:normal;"><input type="radio" name="priority" value="medium"> Medium</label>
                        <label style="font-weight:normal;"><input type="radio" name="priority" value="high"> High</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Due Date *</label>
                    <input type="date" name="due_date" required>
                </div>
                <div style="text-align: right; margin-top: 20px;">
                    <button type="button" onclick="toggleModal(false)" style="padding: 10px 15px; margin-right: 10px; border:none; border-radius:4px; cursor:pointer;">Cancel</button>
                    <button type="submit" class="btn-primary">Save Task</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Toggle view display state of inline form entry wrapper modal
        function toggleModal(show) {
            document.getElementById('taskModal').style.display = show ? 'flex' : 'none';
        }

        // Requirement 4 Engine: Overdue Highlighting Controller Integration
        document.addEventListener("DOMContentLoaded", function() {
            // Drop hour offsets to guarantee precise baseline day boundaries calculations
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            document.querySelectorAll(".task-card").forEach(card => {
                const status = card.getAttribute("data-status");
                const dateAttr = card.getAttribute("data-due-date");

                if (!dateAttr || status === 'done') return;

                const dueDate = new Date(dateAttr);
                dueDate.setHours(0, 0, 0, 0);

                // Apply critical class flagging if past schedule parameters
                if (dueDate < today) {
                    card.classList.add("border-red-500");
                }
            });
        });

        // Requirement 3 Logic: Intercept clicks and execute programmatic PUT transitions 
        function shiftTask(taskId, targetStatus) {
            fetch('/api/task_board/status.php', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    task_id: taskId,
                    status: targetStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.ok) {
                    // Relocate components safely across view trees using appendChild logic
                    const cardElement = document.getElementById('task-' + taskId);
                    const targetColumn = document.getElementById(data.new_status + '-column');
                    
                    if (cardElement && targetColumn) {
                        // Dynamically updates status tracker tags so validation boundaries calculate cleanly
                        cardElement.setAttribute('data-status', data.new_status);
                        
                        // Re-render button actions dynamically so inline arrows correspond with current positioning adjustments
                        const navContainer = cardElement.querySelector('.nav-buttons');
                        if (data.new_status === 'todo') {
                            navContainer.innerHTML = `<button onclick="shiftTask(${taskId}, 'in-progress')">→</button>`;
                        } else if (data.new_status === 'in-progress') {
                            navContainer.innerHTML = `
                                <button onclick="shiftTask(${taskId}, 'todo')">←</button>
                                <button onclick="shiftTask(${taskId}, 'done')">→</button>
                            `;
                        } else if (data.new_status === 'done') {
                            navContainer.innerHTML = `<button onclick="shiftTask(${taskId}, 'in-progress')">←</button>`;
                        }

                        // Append DOM node to container instantly
                        targetColumn.appendChild(cardElement);
                        
                        // Recalculate overdue highlighting on modified card
                        const today = new Date().setHours(0,0,0,0);
                        const dueDate = new Date(cardElement.getAttribute("data-due-date")).setHours(0,0,0,0);
                        if (data.new_status === 'done') {
                            cardElement.classList.remove("border-red-500");
                        } else if (dueDate < today) {
                            cardElement.classList.add("border-red-500");
                        }
                    }
                } else {
                    alert("Illegal transition move rejected: " + (data.message || "Unknown error"));
                }
            })
            .catch(error => {
                console.error("AJAX Error payload mismatch failure execution trace:", error);
                alert("Network communication failure.");
            });
        }
    </script>
</body>
</html>