<?php

include('config_session.php');
include('databasehandler.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start(); 
}

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($email === 'admin@safesitter.com' && $password === 'Admin1234.') {
        $_SESSION['user_id'] = 1; 
        $_SESSION['user_type'] = 'admin'; 
        
        header('Location: admin.php');
        exit;
    }

    try {
        
      
        $query = "SELECT * FROM parents WHERE email = :email LIMIT 1";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id']; 
                $_SESSION['user_type'] = 'parent'; 

                header('Location: parentdashboard.php');
                exit;
            } else {
                $error_message = 'Invalid credentials.';
            }
        } else {
          
          
            $query = "SELECT * FROM nannies WHERE email = :email LIMIT 1";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                if (password_verify($password, $user['password'])) {
                    $_SESSION['nanny_id'] = $user['nanny_id'];  
                    $_SESSION['user_type'] = 'nanny';  

                    header('Location: nannydashboard.php');
                    exit;
                } else {
                    $error_message = 'Invalid credentials.';
                }
            } else {
                $error_message = 'User does not exist.';
            }
        }
    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage();
    }
}

if (isset($error_message)) {
    echo '<p>' . $error_message . '</p>';
}
?>


         