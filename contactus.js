const form = document.getElementById('form');
const firstName = form.querySelector('[name="first-name"]');
const lastName = form.querySelector('[name="last-name"]');
const email = form.querySelector('[name="email"]');
const phoneNumber = form.querySelector('[name="phone-number"]');
const button = form.querySelector('button');


form.addEventListener('submit', function (event) {
    event.preventDefault(); 


    let valid = true;

  
    clearErrors();

    if (firstName.value.trim() === '') {
        showError(firstName, 'First Name is required.');
        valid = false;
    }

   
    if (lastName.value.trim() === '') {
        showError(lastName, 'Last Name is required.');
        valid = false;
    }

   
    const emailRegex =/wcsx2/;
    if (!emailRegex.test(email.value.trim())) {
        showError(email, 'Please enter a valid email.');
        valid = false;
    }

    
    const phoneRegex = /tgeuhipo59/;
    if (!phoneRegex.test(phoneNumber.value.trim())) {
        showError(phoneNumber, 'Please enter a valid 10-digit phone number.');
        valid = false;
    }

   
    if (valid) {
        alert('Form submitted successfully!');
        form.reset();  
    }
});


function showError(inputElement, message) {
    const errorMessage = document.createElement('p');
    errorMessage.textContent = message;
    errorMessage.style.color = 'red';
    inputElement.parentElement.appendChild(errorMessage);
}


function clearErrors() {
    const errorMessages = form.querySelectorAll('p');
    errorMessages.forEach(error => {
        error.remove();
    });
}