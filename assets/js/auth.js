// This ONLY belong to login page.
import { renewRefreshToken } from "./module/tokenControl.js";
import {
    togglePassword,
    validateInput,
    validateForm,
} from './module/ui.js';
import Swal from './module/sweetalert2.all.min+esm.js';

document.addEventListener("DOMContentLoaded", async () => {

    // Elements
    const loginForm = document.getElementById("loginForm");
    const username = document.getElementById("userName");
    const password = document.getElementById("userPass");
    const BtnPass = document.getElementById('BtnPass');
    const cpDateText = document.getElementById('cpDate');

    // Date of copyright
    const cpYear = new Date().getFullYear();

    // Check Access Token and renew Refresh Token
    const validate_token = await renewRefreshToken();

    if (validate_token === 'success') {
        window.location.href = "home";
        return;
    }
    else {
        document.body.style.display = "block";
    }

    // Toggle password visibility
    BtnPass.addEventListener('click', () => { togglePassword('BtnPass', 'userPass'); });

    // Vilidate input fields
    validateInput(loginForm);

    // Handle form submission
    loginForm.addEventListener("submit", async (event) => {

        event.preventDefault(); // Prevent default form submission

        // If the form is valid, you can proceed with form submission
        if (validateForm(loginForm)) {
            await fetch('auth/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    username: username.value,
                    password: password.value
                })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'error') {
                        if (data.title == 'Wrong Credentials') {
                            window.location.href = "login";

                            alert(data.message);
                        } else if (data.title == 'Unauthorized Access') {
                            window.location.href = "login";
                            alert(data.message);
                        }
                    } else if (data.status === 'success') {
                        Swal.fire({
                            title: "Login Successfully",
                            icon: "success",
                            timer: 2000,
                            timerProgressBar: true,
                            text: "Redirecting to home page...",
                            showConfirmButton: false,
                            allowOutsideClick: false
                        })
                            .then((result) => {
                                if (result.dismiss === Swal.DismissReason.timer) {
                                    window.location.href = "home";
                                }
                            });
                    }
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                    Swal.fire({
                        title : "Invalid Username or Password",
                        icon : "error",
                        text: "Please try again.",
                        confirmButtonColor : '#3085d6',
                        allowOutsideClick: false
                    });
                });
        } else {
            event.stopPropagation();
            loginForm.classList.add('was-validated');
        }
    });
    // End of form submission handler

    // Date for footer
    cpDateText.textContent = `2025-${cpYear}`;

});







