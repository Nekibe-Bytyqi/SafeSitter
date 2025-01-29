<?php

declare(strict_types=1);

function get_email(object $pdo , string $email)

{

$query = " SELECT email  FROM parents where email = :email;";
$stmt = $pdo->prepare($query);
$stmt-> bindParam(":email", $email);
$stmt->execute();

$result = $stmt-> fetch(PDO::FETCH_ASSOC);
return $result;

}

function set_parent(object $pdo,string $firstname,string $lastname,string $email,string $password,string $country,string $city)
{
    $query = "INSERT INTO parents(first_name, last_name, email, password,country, city) VALUES (:firstname, :lastname, :email, :password, :country, :city);";
$stmt = $pdo->prepare($query);
$options =[
    'cost'=> 12

];
$hashedPassword = password_hash($password, PASSWORD_BCRYPT,$options);

$stmt-> bindParam(":firstname", $firstname);
$stmt-> bindParam(":lastname", $lastname);
$stmt-> bindParam(":email", $email);
$stmt-> bindParam(":password", $hashedPassword );
$stmt-> bindParam(":country", $country);
$stmt-> bindParam(":city", $city);
$stmt->execute();



}


function set_nanny(object $pdo,string $firstname,string $lastname,string $email,string $password,string $country,string $city,string $birthdate)
{
    $query = "INSERT INTO nannies(first_name, last_name, email, password,country, city, birth_date) VALUES (:firstname, :lastname, :email, :password, :country, :city, :birthdate);";
$stmt = $pdo->prepare($query);
$options =[
    'cost'=> 12

];
$hashedPassword = password_hash($password, PASSWORD_BCRYPT,$options);

$stmt-> bindParam(":firstname", $firstname);
$stmt-> bindParam(":lastname", $lastname);
$stmt-> bindParam(":email", $email);
$stmt-> bindParam(":password", $hashedPassword );
$stmt-> bindParam(":country", $country);
$stmt-> bindParam(":city", $city);
$stmt->bindParam(":country", $country);
    $stmt->bindParam(":city", $city);
    $stmt->bindParam(":birthdate", $birthdate);
$stmt->execute();



}