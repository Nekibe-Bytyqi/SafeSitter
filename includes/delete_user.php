<?php

include('databasehandler.php');

if (isset($_POST['id']) && !empty($_POST['id'])) {
    $id = $_POST['id']; 

    try {
       
        $sql = "DELETE FROM parents WHERE id = :id";  
        $stmt = $pdo->prepare($sql);

       
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        
        if ($stmt->execute()) {
            
            header("Location: admin.php?message=User+deleted+successfully");
        } else {
           
            header("Location: admin.php?message=Error+deleting+user");
        }
        exit;
    } catch (PDOException $e) {
        
        echo "Error: " . $e->getMessage();
    }
} else {
    
    header("Location: admin.php?message=No+user+selected+for+deletion");
    exit;
}
?>