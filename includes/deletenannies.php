<?php
include('databasehandler.php');

if (isset($_POST['email']) && !empty($_POST['email'])) {
    $email = $_POST['email']; 

    try {
        
        $sql = "DELETE FROM nannies WHERE email = :email";  
        $stmt = $pdo->prepare($sql);
    
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        if ($stmt->execute()) {
            header("Location: adminnannies.php?message=Nanny+deleted+successfully");
        } else {
            header("Location: adminnannies.php?message=Error+deleting+nanny");
        }
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: adminnannies.php?message=No+nanny+email+provided+for+deletion");
    exit;
}
?>