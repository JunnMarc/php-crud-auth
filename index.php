<?php
require_once 'config/database.php';
require_once 'handlers/auth_handler.php';
require_once 'handlers/todo_handler.php';

$auth = new AuthHandler($pdo);
$todo = new TodoHandler($pdo);

// Handle login/register/logout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        if ($auth->login($_POST['username'], $_POST['password'])) {
            header('Location: index.php');
            exit;
        } else {
            $error = "Invalid credentials";
        }
    } elseif (isset($_POST['register'])) {
        if ($auth->register($_POST['username'], $_POST['password'])) {
            $success = "Registration successful! Please login.";
        } else {
            $error = "Registration failed";
        }
    } elseif (isset($_POST['logout'])) {
        $auth->logout();
        header('Location: index.php');
        exit;
    } elseif (isset($_POST['add_todo']) && $auth->isLoggedIn()) {
        if ($todo->createTodo($_SESSION['user_id'], $_POST['title'], $_POST['description'])) {
            $success = "Todo added successfully!";
        } else {
            $error = "Failed to add todo";
        }
    } elseif (isset($_POST['update_status']) && $auth->isLoggedIn()) {
        $todo->updateTodoStatus($_POST['todo_id'], $_SESSION['user_id'], $_POST['status']);
    } elseif (isset($_POST['delete_todo']) && $auth->isLoggedIn()) {
        $todo->deleteTodo($_POST['todo_id'], $_SESSION['user_id']);
    }
}

// Get todos if user is logged in
$todos = $auth->isLoggedIn() ? $todo->getTodos($_SESSION['user_id']) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo App with Auth</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Todo App</a>
            <?php if ($auth->isLoggedIn()): ?>
                <form method="post" class="ms-auto">
                    <span class="navbar-text me-3">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <button type="submit" name="logout" class="btn btn-outline-light">Logout</button>
                </form>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <?php if (!$auth->isLoggedIn()): ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Login</div>
                        <div class="card-body">
                            <form method="post">
                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" name="username" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <button type="submit" name="login" class="btn btn-primary">Login</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Register</div>
                        <div class="card-body">
                            <form method="post">
                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" name="username" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <button type="submit" name="register" class="btn btn-success">Register</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">Add New Todo</div>
                        <div class="card-body">
                            <form method="post">
                                <div class="mb-3">
                                    <label class="form-label">Title</label>
                                    <input type="text" name="title" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" rows="3" required></textarea>
                                </div>
                                <button type="submit" name="add_todo" class="btn btn-primary">Add Todo</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <h3>Your Todos</h3>
                    <?php if (empty($todos)): ?>
                        <p class="text-muted">No todos yet. Add your first todo!</p>
                    <?php else: ?>
                        <?php foreach ($todos as $todo_item): ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($todo_item['title']); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($todo_item['description']); ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <form method="post" class="d-inline">
                                            <input type="hidden" name="todo_id" value="<?php echo $todo_item['id']; ?>">
                                            <select name="status" class="form-select d-inline-block w-auto me-2" onchange="this.form.submit()">
                                                <option value="pending" <?php echo $todo_item['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                <option value="completed" <?php echo $todo_item['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                            </select>
                                            <input type="hidden" name="update_status" value="1">
                                        </form>
                                        <form method="post" class="d-inline">
                                            <input type="hidden" name="todo_id" value="<?php echo $todo_item['id']; ?>">
                                            <button type="submit" name="delete_todo" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 