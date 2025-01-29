<?php

declare(strict_types=1);

function is_input_empty(string $firstname, string $lastname, string $email, string $password, string $confirmpassword, string $country, string $city) {
    if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($confirmpassword) || empty($country) || empty($city)) {
        return true;
    }
    return false;
}

function is_email_invalid(string $email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    }
    return false;
}

function is_password_invalid(string $password): bool {
    if (strlen($password) < 8) {
        return true;
    }
    if (!preg_match('/[A-Z]/', $password)) {
        return true;
    }
    if (!preg_match('/[\W_]/', $password)) {
        return true;
    }
    if (!preg_match('/[0-9]/', $password)) {
        return true;
    }
    return false;
}

function do_passwords_match(string $password, string $confirmpassword): bool {
    return $password === $confirmpassword;
}

function is_email_taken(object $pdo, string $email) {
    if (get_email($pdo, $email)) {
        return true;
    } else {
        return false;
    }
}

function create_parent(object $pdo, string $firstname, string $lastname, string $email, string $password, string $country, string $city) {
    set_parent($pdo, $firstname, $lastname, $email, $password, $country, $city);
}


function is_input_empty2(string $firstname, string $lastname, string $email, string $password, string $confirmpassword, string $country, string $city, string $birthdate) {
    if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($confirmpassword) || empty($country) || empty($city) || empty($birthdate)) {
        return true;
    }
    return false;
}

function is_age_invalid(string $birthdate): bool {
   
    $birthDateObject = DateTime::createFromFormat('Y-m-d', $birthdate);

    if ($birthDateObject === false) {
        return true; 
    }

   
    $currentDate = new DateTime();

    $age = $currentDate->diff($birthDateObject)->y;

    
    return $age <= 18;
}

function create_nanny(object $pdo, string $firstname, string $lastname, string $email, string $password, string $country, string $city, string $birthdate) {
    set_nanny($pdo, $firstname, $lastname, $email, $password, $country, $city, $birthdate);

}





