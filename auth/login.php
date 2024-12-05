<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);

    include "../db_connection.php";

    if(isset($_POST['submit'])){

        $email = $_POST['email'];
        $password = $_POST['password'];
        $password = hash('md5',$password);
        

        $error_msg = "";

        $db = db_connect();
        $query = $db->prepare("SELECT * FROM users WHERE email = '".$email."' AND password = '".$password."'");

        //die("SELECT * FROM users WHERE email = '".$email."' AND password = '".$password."'");
        $query->execute();

        $row_count = $query->rowCount();//Row count
        if($row_count > 0){
          $row = $query->fetch(PDO::FETCH_ASSOC);

          session_start();
          $_SESSION['user_id'] = $row['user_id'];

          header('Location:profile.php');
        }else{
          $error_msg = "Invalid username or password";
        }
        

    }


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.*/css/pico.min.css">
</head>
<body>

<main class="container">
    <article class="grid">
        <section>
            <hgroup>
                <h1>Login</h1>
                <p>Please enter your email and password to continue.</p>
            </hgroup>
            <form method="post" action="login.php">
                <label for="email">
                    Email address
                    <input type="email" name="email" id="email" placeholder="Enter email" required>
                </label>
                <label for="password">
                    Password
                    <input type="password" name="password" id="password" placeholder="Enter password" required>
                </label>
                <button type="submit" name="submit" class="contrast">Submit</button>
            </form>
            <p style="color: red;">
                <?php if (isset($error_msg)) echo $error_msg; ?>
            </p>
        </section>
    </article>
</main>

</body>
</html>