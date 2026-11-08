<?php

$action = $argv[1] ?? '';
$task = $action === "add" ? ($argv[2] ?? '') : '';
$id = in_array($action, [
    'delete',
    'update',
    'mark-as-done',
    'mark-as-complete',
], true) ? (int) ($argv[2] ?? 0) : 0;
$newTask = $argv[3] ?? '';
$filterStatus = $action === "list" ? ($argv[2] ?? '') : '';

$file = __DIR__ . '/../database/tasks.json';

if (!file_exists($file)) {
    file_put_contents($file, json_encode([]));
}

$tasks = json_decode(file_get_contents($file), true);

if (!is_array($tasks)) {
    $tasks = [];
}

function showTasks(string $file, string $status = '')
{
    $tasks = json_decode(file_get_contents($file), true);

    $filteredTasks = array_filter($tasks, function ($value) use ($status) {
        if (!$status) {
            return true;
        }
        return $value['status'] === $status;
    });

    if (count($filteredTasks) === 0) {
        echo "No tasks yet.";
        return;
    }

    foreach ($filteredTasks as $task) {
        echo "[{$task['id']}] {$task['task']} - {$task['status']}" . PHP_EOL;
    }
}

function addTask(string $task, array $tasks, string $file)
{
    if (!$task) {
        echo "Fill the task.";
        return;
    }

    file_put_contents($file, json_encode([
        ...$tasks,
        [
            'id' => empty($tasks) ? 1 : max(array_column($tasks, 'id')) + 1,
            'task' => $task,
            'status' => 'pending',
        ]
    ], JSON_PRETTY_PRINT));

    echo "Task added successfully." . PHP_EOL;
    showTasks($file);
}

function deleteTask(int $id, array $tasks, string $file)
{
    $remainingTasks = array_filter($tasks, function ($value) use ($id) {
        return (int) $value['id'] !== $id;
    });

    if (count($remainingTasks) === count($tasks)) {
        echo "Task not found.";
        return;
    }

    file_put_contents(
        $file,
        json_encode(array_values($remainingTasks), JSON_PRETTY_PRINT)
    );
    echo "Task deleted successfully." . PHP_EOL;
    showTasks($file);
}

function updateTask(int $id, array $tasks, string $file, string $newTask)
{
    if (!$newTask) {
        echo "Fill the task";
        return;
    }

    $key = array_find_key($tasks, function ($item) use ($id) {
        return (int) $item['id'] === $id;
    });

    if ($key === null) {
        echo "No Task found.";
        return;
    }

    $tasks[$key]['task'] = $newTask;
    file_put_contents($file, json_encode($tasks, JSON_PRETTY_PRINT));
    echo "Task updated successfull." . PHP_EOL;
    showTasks($file);
}

function markAs(int $id, array $tasks, string $file, string $status)
{
    if (!$status) {
        echo "Fill the status.";
        return;
    }

    $key = array_find_key($tasks, function ($value) use ($id) {
        return (int) $value['id'] === $id;
    });

    if ($key === null) {
        echo "No task found";
        return;
    }

    $tasks[$key]['status'] = $status;

    file_put_contents($file, json_encode($tasks, JSON_PRETTY_PRINT));

    echo "Task status updated successfully." . PHP_EOL;
    showTasks($file);
}

switch ($action) {
    case "list":
        showTasks($file, $filterStatus ?? '');
        break;

    case "add":
        addTask($task, $tasks, $file);
        break;

    case "update":
        updateTask($id, $tasks, $file, $newTask);
        break;

    case "delete":
        deleteTask($id, $tasks, $file);
        break;

    case "mark-as-done":
        markAs($id, $tasks, $file, "done");
        break;

    default:
        echo "Invalid action.";
        break;
}
