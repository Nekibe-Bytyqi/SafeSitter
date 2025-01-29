<?php

require_once 'includes/signup_view.php';
require_once 'includes/config_session.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find A Job</title>
    
    <link rel="stylesheet" href="findajob.css">
    <script src="validationfj.js" defer></script>
</head>
<body>
    <header class="navbar">
        <div class="logo-text">
            <img src="logo.png" alt="Logo" class="logo">
            <span class="text">SafeSitter</span>
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="index.html">Home</a></li>
                <li><a href="aboutus.html">About Us</a></li>
                <li><a href="contactus.html">Contact Us</a></li>
                <li><a href="findananny.php">Find a Nanny</a></li>
                <li class="active"><a href="findajob.php">Find a Job</a></li>
                <li><a href="howitworks.html">How It Works</a></li>
                <li><a href="login.html" class="btn-login">Login</a></li>
            </ul>
        </nav>
    </header>

    <main class="main-content">
        <div class="signup-form-container">
            <h2>Welcome, Nannies!</h2>
            <p class="intro-text">Join our community and connect with families looking for care. Fill in the details below to create your account.</p>
           
            <form  id="form"  action="includes/fjsignup.php" method="post">
               
                <div class="form-group">
                    <label for="first-name">First Name</label>
                    <input type="text" id="first-name" name="first-name" placeholder="Enter your first name" required>
                    <span class="error-message" id="first-name-error"></span>
                </div>
               
                <div class="form-group">
                    <label for="last-name">Last Name</label>
                    <input type="text" id="last-name" name="last-name" placeholder="Enter your last name" required>
                    <span class="error-message" id="last-name-error"></span>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email address" required>
                    <span class="error-message" id="email-error"></span>
                </div>
               
                <div class="form-group">
                  <label for="password">Password</label>
                  <input type="password" id="password" name="password" placeholder="Enter your password" required>
                  <ul class="password-requirements">
                      <li>At least 8 characters</li>
                      <li>At least 1 uppercase letter</li>
                      <li>At least 1 number</li>
                      <li>At least 1 symbol (e.g., @, #, $)</li>
                  </ul>
                  <span class="error-message" id="password-error"></span>
              </div>
              <div class="form-group">
               <label for="confirm-password">Confirm Password</label>
               <input type="password" id="confirm-password" name="confirm-password" placeholder="Re-enter your password" required>
               <span class="error-message" id="confirm-password-error"></span>
           </div>
              
                <div class="form-group">
                  <label for="country">Country</label>
                  <select id="country" name="country" required>
                      <option value="" disabled selected>Select your country</option>
                      <option value="Albania">Albania</option>
                      <option value="Kosovo">Kosovo</option>
                      <option value="Germany">Germany</option>
                      <option value="USA">United States</option>
                      <option value="Canada">Canada</option>
                      <option value="UK">United Kingdom</option>
                      <option value="France">France</option>
                     
                  </select>
                  <span class="error-message" id="country-error"></span>
              </div>
              
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" placeholder="Enter your city" required>
                    <span class="error-message" id="city-error"></span>
                </div>
               
                <div class="form-group">
                  <label for="birth-date">Birth Date</label>
                  <div class="birth-date">
                      <input type="text" id="month" name="month" placeholder="MM" maxlength="2" required>
                      <input type="text" id="day" name="day" placeholder="DD" maxlength="2" required>
                      <input type="text" id="year" name="year" placeholder="YYYY" maxlength="4" required>
                  </div>
                  <p class="form-message">* You must be 18 or older to sign up.</p>
                  <span class="error-message" id="birthdate-error"></span>
              </div>
                
                <div class="form-group terms-container">
                  <label class="terms-label">
                      <input type="checkbox" id="terms" name="terms" required>
                      I agree to the <a href="terms.html" target="_blank">terms of use</a>.
                  </label>
                  <span class="error-message" id="terms-error"></span>
              </div>
                
                <div class="form-group">
                    <button type="submit">Sign Up</button>
                </div>
            </form>
          
            <div class="additional-links">
                <p>Already have an account? <a href="login.html">Log In</a></p>
                <p>Looking for care? <a href="findananny.html">SIGN UP AS A PARENT</a></p>
            </div>
            
            
        </div>
    </main>
    <?php

       check_signup_errors();
       ?>
    
</body>
</html>