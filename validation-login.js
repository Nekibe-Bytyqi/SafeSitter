document.getElementById('form').addEventListener('submit', (e) => {
    e.preventDefault();
    let hasErrors = false;

   
    document.querySelectorAll('.error-message').forEach(el => el.innerText = '');

   
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;

   
    if (!username) {
        document.getElementById('username-error').innerText = 'Username is required.';
        hasErrors = true;
    }

   
    if (!password) {
        document.getElementById('password-error').innerText = 'Password is required.';
        hasErrors = true;
    }
    if (password.length < 8) {
        document.getElementById('password-error').innerText = 'Password must be at least 8 characters long.';
        hasErrors = true;
    }
    if (!hasErrors) {
        alert("Login successful!");
        form.submit();
    }
});