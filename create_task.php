<?php
require_once "config/database.php";

header("Content-Type: application/json");

// ==========================
// READ JSON INPUT (IMPORTANT)
// ==========================
$data = json_decode(file_get_contents("php://input"), true);

// ==========================
// VALIDATION (BASIC SAFETY)
// ==========================
if (
    !isset($data['title']) ||
    !isset($data['assigned_to']) ||
    !isset($data['priority']) ||
    !isset($data['due_date'])
) {
    echo json_encode([
        "success" => false,
        "message" => "Missing required fields"
    ]);
    exit;
}

// ==========================
// ASSIGN VARIABLES
// ==========================
$title = $data['title'];
$description = $data['description'] ?? "";
$assigned_to = $data['assigned_to'];
$priority = $data['priority'];
$due_date = $data['due_date'];

$project_id = 1; // FIXED for your project

// ==========================
// INSERT TASK INTO DB
// ==========================
try {

    $sql = "INSERT INTO tasks 
        (project_id, title, description, assigned_to, priority, due_date, status)
        VALUES (?, ?, ?, ?, ?, ?, 'todo')";

    $stmt = $pdo->prepare($sql);

    $result = $stmt->execute([
        $project_id,
        $title,
        $description,
        $assigned_to,
        $priority,
        $due_date
    ]);

    // ==========================
    // RESPONSE
    // ==========================
    echo json_encode([
        "success" => $result,
        "message" => "Task created successfully"
    ]);

} catch (Exception $e) {

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
?>