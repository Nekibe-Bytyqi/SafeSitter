<?php


include_once 'databasehandler.php';

class ContactUs {
    private $pdo;

 
    public function __construct() {
       
        global $pdo;
        $this->pdo = $pdo;
    }

    
    public function submitContactForm($firstName, $lastName, $email, $message) {
        
        $query = "INSERT INTO contact_us (first_name, last_name, email, message) VALUES (:first_name, :last_name, :email, :message)";
        
     
        $stmt = $this->pdo->prepare($query);

      
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':message', $message);

      
        if ($stmt->execute()) {
            return true;  
        } else {
            return false; 
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  
    $firstName = htmlspecialchars(trim($_POST['first-name']));
    $lastName = htmlspecialchars(trim($_POST['last-name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(trim($_POST['Message']));

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit;
    }

   
    $contact = new ContactUs();

 
    if ($contact->submitContactForm($firstName, $lastName, $email, $message)) {
        echo "Thank you for contacting us. We will get back to you shortly.";
    } else {
        echo "There was an error submitting your message. Please try again later.";
    }
}
?>
