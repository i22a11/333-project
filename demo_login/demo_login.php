<?php
    //Demo login just to test functionality

    session_start();

    // Simulate a logged-in user by setting session variables
    $_SESSION['user_id'] = 1; // Assign a dummy user ID
    $_SESSION['username'] = 'testuser'; // Assign a dummy username

    echo "User is now logged in as {$_SESSION['username']} (ID: {$_SESSION['user_id']})";
    sleep(2); // Simulate a delay
    header("location: ../base.php");
?>

