import Swal from './module/sweetalert2.all.min+esm.js';
import {
    togglePassword,
    validatePassword,
    validateCfPassword,
    validateInput,
    validateForm,
}
    from './module/ui.js';

document.addEventListener('DOMContentLoaded', () => {

    const form = document.getElementById('registerForm');
    const fName = document.getElementById('fName');
    const userName = document.getElementById('userName');
    const userPass = document.getElementById('userPass');
    const cfPass = document.getElementById('cfPass');
    const userEmail = document.getElementById('userEmail');
    const userIdenCode = document.getElementById('userIdenCode');

    const BtnPass = document.getElementById('BtnPass');
    const BtnCfPass = document.getElementById('BtnCfPass');

    // Toggle password visibility for user password and confirm password fields
    BtnPass.addEventListener('click', () => { togglePassword('BtnPass', 'userPass'); });
    BtnCfPass.addEventListener('click', () => { togglePassword('BtnCfPass', 'cfPass'); });

    // Password Validation
    userPass.addEventListener('input', () => {
        validatePassword.call(userPass);
    });

    // Confirm Password Validation
    cfPass.addEventListener('input', () => {
        validateCfPassword.call(cfPass, userPass);
    });

    // Validate input fields
    validateInput(form);

    // Form submission
    form.addEventListener('submit', (event) => {

        event.preventDefault(); // Prevent default form submission

        if (validateForm(form)) {

            // If the form is valid, you can proceed with form submission
            fetch('auth/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    fName: fName.value,
                    userName: userName.value,
                    userPass: userPass.value,
                    userEmail: userEmail.value,
                    userIdenCode: userIdenCode.value,
                }),
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'error') {
                        if (data.title === 'usercode exists') {
                            Swal.fire({
                                icon: 'warning',
                                title: 'User code is already exists',
                                text: 'Please choose a different one.',
                                confirmButtonText: 'OK',
                                allowOutsideClick: false
                            });
                        } else if (data.title === 'username exists') {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Username is already exists',
                                text: 'Please choose a different one.',
                                confirmButtonText: 'OK',
                                allowOutsideClick: false
                            });
                        } else if (data.title === 'email exists') {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Email is already exists',
                                text: 'Please choose a different one.',
                                confirmButtonText: 'OK',
                                allowOutsideClick: false
                            });
                        } else if (data.title === 'ID card exists') {
                            Swal.fire({
                                icon: 'warning',
                                title: 'ID card is already exists',
                                text: 'Please choose a different one.',
                                confirmButtonText: 'OK',
                                allowOutsideClick: false
                            });
                        }
                    } else if (data.status === 'success') {
                        Swal.fire({
                            icon: "success",
                            title: "Registration successfully",
                            timer: 2000,
                            timerProgressBar: true,
                            text: "Redirecting to Login page...",
                            showConfirmButton: false,
                            allowOutsideClick: false
                        })
                            .then((result) => {
                                if (result.dismiss === Swal.DismissReason.timer) {
                                    window.location.href = "login";
                                }
                            });
                    }
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                    alert('Registration failed. Please try again.'); // Show error message
                });
        } else {
            event.stopPropagation();
            form.classList.add('was-validated'); // Add validation class to the form
        }
    });

});