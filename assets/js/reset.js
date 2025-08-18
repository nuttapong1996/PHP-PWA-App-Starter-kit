import { validateInput, validateForm, validatePassword ,togglePassword} from "./module/ui.js";
import Swal from './module/sweetalert2.all.min+esm.js';

document.addEventListener('DOMContentLoaded', () => {

    const resetForm = document.getElementById('resetForm');
    const userPass = document.getElementById('userPass');
    const cfPass = document.getElementById('cfPass');
    const BtnPass = document.getElementById('BtnPass');
    const BtnCfPass = document.getElementById('BtnCfPass');

    
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

   BtnPass.addEventListener('click' ,()=> {
     togglePassword('BtnPass','userPass');
   });

   BtnCfPass.addEventListener('click' ,()=> {
    togglePassword('BtnCfPass','cfPass');
  });

    userPass.addEventListener('input',()=>{
        validatePassword.call(userPass);
    });

    cfPass.addEventListener('input',()=>{
        validatePassword.call(cfPass,userPass);
    
    });

    validateInput(resetForm);

    resetForm.addEventListener('submit', (e) => {
        e.preventDefault();

        if(validateForm(resetForm)){
            fetch('auth/reset' ,{
                method:'POST',
                headers:{
                    'Content-Type':'application/json'
                },
                body:JSON.stringify({
                    UserPass : userPass.value,
                    userCode : userCode
                })
            })
            .then(res => {
                if(!res.ok){
                    throw new Error('Network response was not ok');
                }
                return res.json();
                console.log(res);
            })
            .then(data => {
                if(data.status === 'success'){
                    Swal.fire({
                        icon: "success",
                        title: "Reset password successfully",
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
            });
        }else{
            e.stopPropagation();
            resetForm.classList.add('was-validated');
        }
    });
});