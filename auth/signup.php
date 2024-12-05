<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);

    include "../db_connection.php";

    if(isset($_POST['submit'])){

        $email = $_POST['email'];
        $name = $_POST['name'];
        $password = $_POST['password'];
        $cpassword = $_POST['cpassword'];
        

        $error_msg = "";
        $email_status = false;
        $password_status = false;
        $cpassword_status = false;


        //email validation
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_status = true;
        }else{
            $error_msg = $error_msg . "Invalid Email format <br/>";
        }

        //confirm password validation
        if ($password == $cpassword) {
            $password_status = true;
        }else{
            $error_msg = $error_msg . "Confirm password and password does not match<br/>";
        }

        //password validation
        $isValidPassword = filter_var($password, FILTER_VALIDATE_REGEXP, array(
            "options" => array(
                "regexp" => "/(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-])^.+$/"
            )
        ));

        if ($isValidPassword) {
            $cpassword_status = true;
        }else{
            $error_msg = $error_msg . "Password is weak<br/>";
        }

        //check all validation and default to deny
        if($email_status && $password_status && $cpassword_status){

            $db = db_connect();
            $query = $db->prepare('INSERT INTO `users` (`email`, `name`, `password`,`role`) VALUES (?,?,?,?)');
            $query->execute([$email,$name,hash('md5',$password),'user']);
            header('Location:login.php');


        }
        

    }


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.*/css/pico.min.css">
</head>
<body>

<main class="container">
    <article class="grid">
        <section>
            <hgroup>
                <h1>Sign Up</h1>
                <p>Create an account by filling in your details below.</p>
            </hgroup>
            <form method="post" action="signup.php">
                <label for="email">
                    Email address
                    <input type="email" name="email" id="email" placeholder="Enter email" required>
                </label>
                <label for="name">
                    Full Name
                    <input type="text" name="name" id="name" placeholder="Enter full name" required>
                </label>
                <label for="password">
                    Password
                    <input type="password" name="password" id="password" placeholder="Enter password" required>
                </label>
                <label for="cpassword">
                    Confirm Password
                    <input type="password" name="cpassword" id="cpassword" placeholder="Confirm password" required>
                </label>
                <button type="submit" name="submit" class="contrast">Sign Up</button>
            </form>
            <p style="color: red;">
                <?php if (isset($error_msg)) echo $error_msg; ?>
            </p>
        </section>
    </article>
</main>

</body>
</html>
