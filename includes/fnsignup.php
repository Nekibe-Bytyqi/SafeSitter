<?php

if ($_SERVER["REQUEST_METHOD"]=== "POST"){
$firstname = $_POST["first-name"];
$lastname = $_POST["last-name"];
$email = $_POST["email"];
$password = $_POST["password"];
$confirmpassword   = $_POST["confirm-password"];
$country = $_POST["country"];
$city = $_POST["city"];

try{
    require_once 'databasehandler.php';
    require_once 'signup_model.php';
    require_once 'signup_controller.php';

    $errors = [];

   if(is_input_empty($firstname,$lastname,$email,$password,$confirmpassword,$country,$city)){
    $errors["empty_input"] = "Fill in all fields!";

   }
   if(is_email_invalid($email)){
    $errors["email_invalid"] = "Type a valid email!";

   }

   if(is_password_invalid( $password)){
    $errors["password_invalid"] = "Type a valid password!";

   }

   if(!do_passwords_match( $password,  $confirmpassword)){
    $errors["password_notmatch"] = "Passwords do not match!";

   }

   if(is_email_taken( $pdo, $email)){
    $errors["email_taken"] = "Email is already taken!";

   }

   require_once 'config_session.php';

   if ($errors){
    $_SESSION["error_signup"] = $errors;
    header("Location: ../findananny.php");
    die();

   }
   create_parent( $pdo, $firstname,$lastname, $email, $password, $country, $city);
   header ("Location: parentdashboard.php");
   $pdo=null;
   $stmt=null;
   die();

}
catch (PDOException $e){
    die("Connection failed: " . $e->getMessage());
}


}
else{
    header ("Location: ../findananny.php");
    die();
}