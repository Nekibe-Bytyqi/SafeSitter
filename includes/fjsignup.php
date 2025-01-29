<?php

if ($_SERVER["REQUEST_METHOD"]=== "POST"){
$firstname = $_POST["first-name"];
$lastname = $_POST["last-name"];
$email = $_POST["email"];
$password = $_POST["password"];
$confirmpassword   = $_POST["confirm-password"];
$country = $_POST["country"];
$birthMonth = $_POST["month"];
$birthDay = $_POST["day"];
$birthYear = $_POST["year"];
$city = $_POST["city"];

if (!empty($birthMonth) && !empty($birthDay) && !empty($birthYear)) {
    $birthdate = $birthYear . '-' . str_pad($birthMonth, 2, '0', STR_PAD_LEFT) . '-' . str_pad($birthDay, 2, '0', STR_PAD_LEFT);
} else {
    $birthdate = null;
}

try{
    require_once 'databasehandler.php';
    require_once 'signup_model.php';
    require_once 'signup_controller.php';

    $errors = [];

   if(is_input_empty2($firstname,$lastname,$email,$password,$confirmpassword,$country,$city,$birthdate)){
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

   if (is_age_invalid($birthdate)){
    $errors["birthdate_invalid"] = "Birthdate is invalid or user younger than 18!";

   }

   require_once 'config_session.php';

   if ($errors){
    $_SESSION["error_signup"] = $errors;
    header("Location: ../findajob.php");
    die();

   }
   create_nanny( $pdo, $firstname,$lastname, $email, $password, $country, $city,$birthdate);
   header ("Location: ../nannydashboard.php");
   $pdo=null;
   $stmt=null;
   die();

}
catch (PDOException $e){
    die("Connection failed: " . $e->getMessage());
}


}
else{
    header ("Location: ../findajob.php");
    die();
}