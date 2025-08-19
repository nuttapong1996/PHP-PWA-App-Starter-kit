import { validateInput, validateForm, validatePassword, validateCfPassword, togglePassword } from "./module/ui.js";
import Swal from './module/sweetalert2.all.min+esm.js';

document.addEventListener('DOMContentLoaded', () => {

    const changeForm = document.getElementById('changeForm');
    const OldPass = document.getElementById('OldPass');
    const NewPass = document.getElementById('NewPass');
    const cfPass = document.getElementById('cfPass');

    // Validate Input 
    validateInput(changeForm);

    // Password Input Event.
    OldPass.addEventListener('input', () => {
        validatePassword.call(OldPass);
    });

    OldPass.addEventListener('change', () => {
        checkpass.call(OldPass);
    });
    
    NewPass.addEventListener('input', () => {
        validatePassword.call(OldPass);
    });
    cfPass.addEventListener('input', () => {
        validateCfPassword.call(cfPass);
    });



});


function checkpass() {
    const value = $this.value;
    const feedback = this.closest('.input-group').querySelector('.invalid-feedback');
    let message = '';

    fetch('auth/checkpass', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            OldPass: value
        })
            .then(res => {
                if (!res.ok) {
                    throw new Error('Something went wrong')
                }
                return res.json();
            })
            .then(data => {
                if (data.status === 'valid') {
                    this.classList.add('is-valid');
                    message = 'Current password valid.';
                     feedback.textContent = message;
                }else{
                     this.classList.add('is-invalid');
                     message = 'Current password invalid.';
                     feedback.textContent = message;
                }
            })
            .catch(err => {
                console.log(err);
            })
    });
}