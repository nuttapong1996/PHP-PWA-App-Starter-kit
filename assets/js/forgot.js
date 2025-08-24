import { validateInput, validateForm } from "./module/ui.js";
import Swal from "./module/sweetalert2.all.min+esm.js"
document.addEventListener('DOMContentLoaded', () => {

    const forgotForm = document.getElementById('forgotForm');
    const userCode = document.getElementById('userCode');
    const userIdenCode = document.getElementById('userIdenCode');

    validateInput(forgotForm);


    forgotForm.addEventListener('submit', (e) => {
        e.preventDefault();
        if (validateForm(forgotForm)) {
            fetch('auth/forgot', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    userCode: userCode.value,
                    userIdenCode: userIdenCode.value
                })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            title: 'Success',
                            text: data.message,
                            icon: 'success',
                            allowOutsideClick: false,
                            timer: 2000,
                            timerProgressBar: true,
                            text: "Redirecting to reset page...",

                        }).then((result) => {
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location.href = `reset/${userCode.value}/${data.resetToken}`;
                            }
                        });
                    } else if (data.status === 'error') {
                        Swal.fire({
                            title: 'Invalid',
                            text: data.message,
                            icon: 'error',
                            confirmButtonText: 'OK',
                            allowOutsideClick: false
                        });
                    }
                })
        } else {
            forgotForm.classList.add('was-validated');
            e.stopPropagation();
        }
    });

});