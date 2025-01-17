form.addEventListener('submit', (e) => {
    e.preventDefault();
    let hasErrors = false;

   
    document.querySelectorAll('.error-message').forEach(el => el.innerText = '');

   
    const firstName = document.getElementById('first-name').value.trim();
    const lastName = document.getElementById('last-name').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm-password').value;
    const country = document.getElementById('country').value;
    const city = document.getElementById('city').value.trim();
    

   
    if (!firstName) {
        document.getElementById('first-name-error').innerText = 'First Name is required.';
        hasErrors = true;
    }

   
    if (!lastName) {
        document.getElementById('last-name-error').innerText = 'Last Name is required.';
        hasErrors = true;
    }

    const emailR = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailR.test(email)) {
        document.getElementById('email-error').innerText = 'Please enter a valid email address.';
        hasErrors = true;
    }

   
    if (password.length < 8) {
        document.getElementById('password-error').innerText = 'Password must be at least 8 characters long.';
        hasErrors = true;
    }
    if (!/[A-Z]/.test(password)) {
        document.getElementById('password-error').innerText = 'Password must contain at least one uppercase letter.';
        hasErrors = true;
    }
    if (!/\d/.test(password)) {
        document.getElementById('password-error').innerText = 'Password must contain at least one number.';
        hasErrors = true;
    }
    if (!/[@#$!%^&*.]/.test(password)) {
        document.getElementById('password-error').innerText = 'Password must contain at least one special symbol (e.g., @, #, $).';
        hasErrors = true;
    }
    if (password !== confirmPassword) {
        document.getElementById('confirm-password-error').innerText = 'Passwords do not match.';
        hasErrors = true;
    }

   
    if (!country) {
        document.getElementById('country-error').innerText = 'Please select your country.';
        hasErrors = true;
    }

   
    if (!city) {
        document.getElementById('city-error').innerText = 'City is required.';
        hasErrors = true;
    }

    


    

   
    if (!hasErrors) {
        form.submit();
    }
});