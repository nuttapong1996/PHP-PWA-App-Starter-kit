import { validateInput, validateForm, validatePassword } from "./module/ui.js";
import Swal from './module/sweetalert2.all.min+esm.js';

document.addEventListener('DOMContentLoaded', () => {

    const resetForm = document.getElementById('resetForm');
    const userPass = document.getElementById('userPass');
    const cfPass = document.getElementById('cfPass');

    // fetch validatre Usercode and Reset Token.
    // แยก userCode และ resetToken จาก path
    const pathParts = window.location.pathname.split('/');
    const userCode = pathParts[3];
    const resetToken = pathParts[4];

    fetch('auth/reset', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            userCode: userCode,
            resetToken: resetToken
        })
    })
        .then(Checkreset => {
            if (!Checkreset.ok) {
                throw new Error('Network response was not ok');
            }
            return Checkreset.json();
        })
        .then(res_check => {
            if (res_check.status !== 'valid') {
                Swal.fire({
                    title: 'Invalid Token',
                    text: res_check.message,
                    icon: 'error',
                    confirmButtonText: 'OK',
                    allowOutsideClick: false
                }).then(() => {
                    window.location.href = 'forgot';
                });
            }
        })


    // Handdling form reset passsword 

    userPass.addEventListener('input',()=>{
        validatePassword.call(userPass);
    });

    cfPass.addEventListener('input',()=>{
        validatePassword(cfPass)
    });


    // validateForm(resetForm);

});