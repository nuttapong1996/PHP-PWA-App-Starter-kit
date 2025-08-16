import { validateInput, validateForm } from "./module/ui.js";
import Swal from "./module/sweetalert2.all.min+esm.js"

document.addEventListener('DOMContentLoaded', () => {

    const forgotForm = document.getElementById('forgotForm');
    const userCode = document.getElementById('userCode');
    const userIdenCode = document.getElementById('userIdenCode');

    forgotForm.addEventListener('submit', (e) => {
        e.preventDefault();

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
                        confirmButtonText: 'OK',
                        allowOutsideClick: false
                    }).then(() => {
                        window.location.href = `reset/${data.resetToken}`;
                    });
                    
                } else if (data.status === 'error') {
                    Swal.fire({
                        title: 'Invalid',
                        text: data.message,
                        icon: 'error',
                        confirmButtonText: 'OK',
                        allowOutsideClick: false
                    })
                }
            })
    });

});