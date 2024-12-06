<?php
session_start();
require_once '../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $error = '';

    // Validate email format
    if (!preg_match('/^[a-zA-Z0-9._%+-]+@\w*\.uob\.edu\.bh$/', $email)) {
        $error = "Email must be a valid @uob.edu.bh address";
    } elseif (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long";
    } else {
        try {
            $conn = db_connect();
            
            // Check if email already exists
            $stmt = $conn->prepare("SELECT COUNT(*) FROM Users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetchColumn() > 0) {
                $error = "Email already registered";
            } else {
                // Insert new user
                $stmt = $conn->prepare("INSERT INTO Users (name, email, password, role) VALUES (?, ?, ?, 'user')");
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt->execute([$name, $email, $hashed_password]);
                
                // Redirect to login page with success message
                header("Location: login.php?registered=1");
                exit();
            }
        } catch(PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Room Booking System</title>
    <link rel="stylesheet" href="../output.css">
</head>
<body class="bg-zinc-900 min-h-screen">
    <div class="flex min-h-screen items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">
            <div class="bg-zinc-800 px-8 py-10 shadow-md rounded-lg border border-zinc-700">
                <div class="sm:mx-auto sm:w-full sm:max-w-md">
                    <h2 class="text-center text-3xl font-bold tracking-tight text-zinc-100">
                        Create your account
                    </h2>
                    <p class="mt-2 text-center text-sm text-zinc-400">
                        Already have an account?
                        <a href="login.php" class="font-medium text-blue-400 hover:text-blue-300">Sign in</a>
                    </p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="mt-6 rounded-md bg-red-900 p-4">
                        <div class="flex">
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-200"><?php echo htmlspecialchars($error); ?></h3>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <form class="mt-8 space-y-6" method="POST">
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-zinc-300">Full Name</label>
                            <div class="mt-1">
                                <input id="name" name="name" type="text" required
                                    class="block w-full rounded-md bg-zinc-700 border-zinc-600 text-zinc-100 placeholder-zinc-400 focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-4 py-3"
                                    value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                            </div>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-zinc-300">Email address</label>
                            <div class="mt-1">
                                <input id="email" name="email" type="email" required pattern="[a-zA-Z0-9._%+-]+@\w*\.uob\.edu\.bh$"
                                    class="block w-full rounded-md bg-zinc-700 border-zinc-600 text-zinc-100 placeholder-zinc-400 focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-4 py-3"
                                    placeholder="example@uob.edu.bh"
                                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                                <p class="mt-1 text-sm text-zinc-400">Must be a valid @uob.edu.bh email address</p>
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-zinc-300">Password</label>
                            <div class="mt-1">
                                <input id="password" name="password" type="password" required
                                    class="block w-full rounded-md bg-zinc-700 border-zinc-600 text-zinc-100 placeholder-zinc-400 focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-4 py-3">
                            </div>
                        </div>

                        <div>
                            <label for="confirm_password" class="block text-sm font-medium text-zinc-300">Confirm Password</label>
                            <div class="mt-1">
                                <input id="confirm_password" name="confirm_password" type="password" required
                                    class="block w-full rounded-md bg-zinc-700 border-zinc-600 text-zinc-100 placeholder-zinc-400 focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-4 py-3">
                            </div>
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                            Sign up
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
