<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);

    include "../db_connection.php";

    session_start();
    if(isset($_SESSION['user_id'])){

        $user_id = $_SESSION['user_id'];
        

        $db = db_connect();
        $query = $db->prepare("SELECT * FROM users WHERE user_id = ?");
        $query->execute([$user_id]);
        $row_count = $query->rowCount();//Row count

        if($row_count > 0){
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $id = $row['user_id'];
            $name = $row['name'];
            $email = $row['email'];
            $role = $row['role'];

        }

        
    }else{

        $error_msg = "Invalid page, please go to login page.";
    }


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile</title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.*/css/pico.min.css">
</head>
<body>

<main class="container">
    <article class="grid">
        <section>
            <hgroup>
                <h1>Profile</h1>
                <p>Here are your account details.</p>
            </hgroup>
            <div>
                <?php 
                    if (isset($name)) {
                        echo "<strong>Name:</strong> $name <br/>";
                        echo "<strong>Email:</strong> $email <br/>";
                        echo "<strong>Role:</strong> $role <br/>";
                    }
                ?>
            </div>
            <p style="color: red; margin-top: 20px;">
                <?php 
                    if (isset($error_msg)) {
                        echo $error_msg; 
                    }
                ?>
            </p>
        </section>
    </article>
</main>

</body>
</html>
