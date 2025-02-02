<?php

include('databasehandler.php');

if (isset($_POST['nanny_id']) && !empty($_POST['nanny_id'])) {
    $nanny_id = $_POST['nanny_id']; 

    try {
        
        $sql = "DELETE FROM nannies WHERE nanny_id = :nanny_id";  
        $stmt = $pdo->prepare($sql);

       
        $stmt->bindParam(':nanny_id', $nanny_id, PDO::PARAM_INT);

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
    header("Location: adminnannies.php?message=No+nanny+selected+for+deletion");
    exit;
}
?>