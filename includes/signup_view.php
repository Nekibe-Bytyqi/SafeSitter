<?php

declare(strict_types=1);

function check_signup_errors(){
    if(isset( $_SESSION["error_signup"])){
        $errors = $_SESSION["error_signup"];

        echo "<br>";
        foreach($errors as $error){
            echo '<p class="form-error" style="color: red; font-weight: bold; margin-bottom: 10px; position: absolute; top: 0; left: 0; width: 100%; text-align: center; margin-top:100px;">' . $error . '</p>';
        }

        unset($_SESSION["error_signup"]);
    }
} 