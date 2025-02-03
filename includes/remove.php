<?php
session_start();
include('databasehandler.php');


if (!isset($_SESSION['nanny_id']) || $_SESSION['user_type'] !== 'nanny') {
    if (isset($_COOKIE['user_id']) && isset($_COOKIE['user_type'])) {
        $_SESSION['nanny_id'] = $_COOKIE['user_id']; 
        $_SESSION['user_type'] = $_COOKIE['user_type'];
    } else {
        header('Location: login.html');
        exit;
    }
}


if (isset($_POST['remove_application'])) {
   
    $job_id = $_POST['job_id'];
    $nanny_id = $_SESSION['nanny_id'];

    try {
       
        $query = "DELETE FROM applications WHERE job_id = :job_id AND nanny_id = :nanny_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':job_id', $job_id, PDO::PARAM_INT);
        $stmt->bindParam(':nanny_id', $nanny_id, PDO::PARAM_INT);
        $stmt->execute();

        
        if ($stmt->rowCount() > 0) {
            header("Location: applications.php");
            exit;
        } else {
            echo "Error: No matching application found.";
        }
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
} else {
    echo "No application selected for removal.";
    exit;
}
?>
