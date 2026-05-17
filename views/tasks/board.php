<!DOCTYPE html>
<html>

<head>
    <title>Task Board</title>

    <style>
        .board {
            display: flex;
            gap: 20px;
        }

        .col {
            width: 33%;
            background: #f5f5f5;
            padding: 10px;
            min-height: 400px;
        }

        .task {
            background: white;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        h3 {
            text-align: center;
        }

        .avatar {
            display:inline-block;
            background:#333;
            color:white;
            padding:3px 7px;
            border-radius:50%;
            font-size:12px;
            margin-bottom:5px;
        }
    </style>
</head>

<body>

<button onclick="openModal()">+ New Task</button>

<h2>Kanban Task Board</h2>

<div class="board">

    <!-- TO DO -->
    <div class="col">
        <h3>To Do</h3>

        <?php foreach($todo as $t): ?>
        <div class="task"
             data-id="<?= $t['id'] ?>"
             data-due-date="<?= $t['due_date'] ?>"
             data-status="<?= $t['status'] ?>">

            <div class="avatar">
                <?= strtoupper(substr($t['name'] ?? 'UN', 0, 2)) ?>
            </div>

            <strong><?= $t['title'] ?></strong><br>

            <small><?= $t['due_date'] ?></small><br>

            <!-- MOVE BUTTONS -->
            <button onclick="moveTask(<?= $t['id'] ?>,'in-progress')">→</button>
            <button onclick="moveTask(<?= $t['id'] ?>,'done')">✓</button>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- IN PROGRESS -->
    <div class="col">
        <h3>In Progress</h3>

        <?php foreach($inprogress as $t): ?>
        <div class="task"
             data-id="<?= $t['id'] ?>"
             data-due-date="<?= $t['due_date'] ?>"
             data-status="<?= $t['status'] ?>">

            <div class="avatar">
                <?= strtoupper(substr($t['name'] ?? 'UN', 0, 2)) ?>
            </div>

            <strong><?= $t['title'] ?></strong><br>

            <small><?= $t['due_date'] ?></small><br>

            <!-- MOVE BUTTONS -->
            <button onclick="moveTask(<?= $t['id'] ?>,'todo')">←</button>
            <button onclick="moveTask(<?= $t['id'] ?>,'done')">✓</button>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- DONE -->
    <div class="col">
        <h3>Done</h3>

        <?php foreach($done as $t): ?>
        <div class="task"
             data-id="<?= $t['id'] ?>"
             data-due-date="<?= $t['due_date'] ?>"
             data-status="<?= $t['status'] ?>">

            <div class="avatar">
                <?= strtoupper(substr($t['name'] ?? 'UN', 0, 2)) ?>
            </div>

            <strong><?= $t['title'] ?></strong><br>

            <small><?= $t['due_date'] ?></small><br>

            <!-- MOVE BACK -->
            <button onclick="moveTask(<?= $t['id'] ?>,'in-progress')">←</button>
        </div>
        <?php endforeach; ?>
    </div>

</div>

<!-- ================= MODAL ================= -->
<div id="taskModal" style="display:none; position:fixed; top:20%; left:30%; background:white; padding:20px; border:1px solid black;">

    <h3>Create Task</h3>

    <form id="taskForm">

        <input type="text" name="title" placeholder="Title" required><br><br>

        <textarea name="description" placeholder="Description"></textarea><br><br>

        <!-- ASSIGNED MEMBER -->
        <select name="assigned_to" required>
            <option value="">-- Select Member --</option>
            <?php foreach($members as $m): ?>
                <option value="<?= $m['id'] ?>">
                    <?= htmlspecialchars($m['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <br><br>

        <select name="priority">
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="high">High</option>
        </select>

        <br><br>

        <input type="date" name="due_date" required><br><br>

        <button type="submit">Create</button>
        <button type="button" onclick="closeModal()">Close</button>

    </form>
</div>

<!-- ================= JS ================= -->

<script>
function openModal() {
    document.getElementById("taskModal").style.display = "block";
}

function closeModal() {
    document.getElementById("taskModal").style.display = "none";
}
</script>

<script>
async function moveTask(task_id, status) {

    let res = await fetch("api/tasks/status.php", {
        method: "PUT",
        headers: {"Content-Type":"application/json"},
        body: JSON.stringify({
            task_id,
            status
        })
    });

    let data = await res.json();

    if (data.ok) {
        location.reload();
    } else {
        alert("Move failed");
    }
}
</script>

<script>
document.querySelectorAll(".task").forEach(task => {

    let dueDate = new Date(task.dataset.dueDate);
    let today = new Date();
    today.setHours(0,0,0,0);

    if (dueDate < today && task.dataset.status !== "done") {
        task.style.border = "2px solid red";
    }
});
</script>

<script>
document.getElementById("taskForm").addEventListener("submit", async function(e) {
    e.preventDefault();

    let formData = new FormData(this);
    let data = Object.fromEntries(formData);

    let res = await fetch("create_task.php", {
        method: "POST",
        headers: {"Content-Type":"application/json"},
        body: JSON.stringify(data)
    });

    let result = await res.json();

    if (result.success) {
        alert("Task Created");
        location.reload();
    } else {
        alert("Failed");
    }
});
</script>

</body>
</html>