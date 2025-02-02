<?php
  
include('config_session.php');
include('databasehandler.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start(); 
}

if (!isset($_SESSION['nanny_id'])) {
    echo 'Please log in first.';
    exit;
}

if (isset($_POST['job_id'])) {
    $job_id = $_POST['job_id'];
    $nanny_id = $_SESSION['nanny_id'];  

    try {
      
        $query = "SELECT * FROM applications WHERE job_id = :job_id AND nanny_id = :nanny_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':job_id', $job_id, PDO::PARAM_INT);
        $stmt->bindParam(':nanny_id', $nanny_id, PDO::PARAM_INT);
        $stmt->execute();

       
        if ($stmt->rowCount() > 0) {
            echo 'You have already applied for this job.';
        } else {
            
            $query = "INSERT INTO applications (job_id, nanny_id) VALUES (:job_id, :nanny_id)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':job_id', $job_id, PDO::PARAM_INT);
            $stmt->bindParam(':nanny_id', $nanny_id, PDO::PARAM_INT);
            $stmt->execute();

            echo 'Application submitted successfully!';
         
            header('Location: applications.php');
            exit;
        }
    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage();
    }
} else {
    echo 'Job ID is missing.';
}
?>
