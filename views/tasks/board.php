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
    </style>
</head>

<body>

<h2>Kanban Task Board</h2>

<div class="board">

    <!-- TODO -->
    <div class="col">
        <h3>To Do</h3>

        <?php foreach($todo as $t): ?>
            <div class="task">
    <?= $t['title'] ?>

    <button onclick="move(<?= $t['id'] ?>,'in-progress')">→</button>
    <button onclick="move(<?= $t['id'] ?>,'todo')">←</button>
    <button onclick="move(<?= $t['id'] ?>,'done')">✓</button>
</div>
        <?php endforeach; ?>

    </div>

    <!-- IN PROGRESS -->
    <div class="col">
        <h3>In Progress</h3>

        <?php foreach($inprogress as $t): ?>
            <div class="task">
    <?= $t['title'] ?>

    <button onclick="move(<?= $t['id'] ?>,'in-progress')">→</button>
    <button onclick="move(<?= $t['id'] ?>,'todo')">←</button>
    <button onclick="move(<?= $t['id'] ?>,'done')">✓</button>
</div>
        <?php endforeach; ?>

    </div>

    <!-- DONE -->
    <div class="col">
        <h3>Done</h3>

        <?php foreach($done as $t): ?>
            <div class="task">
    <?= $t['title'] ?>

    <button onclick="move(<?= $t['id'] ?>,'in-progress')">→</button>
    <button onclick="move(<?= $t['id'] ?>,'todo')">←</button>
    <button onclick="move(<?= $t['id'] ?>,'done')">✓</button>
</div>
        <?php endforeach; ?>

    </div>

</div>
<script>
async function move(task_id, status) {

    let res = await fetch("update_status.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            task_id: task_id,
            status: status
        })
    });

    let data = await res.json();

    if(data.success) {
        location.reload(); // simple MVC safe method
    }
}
</script>
</body>
</html>