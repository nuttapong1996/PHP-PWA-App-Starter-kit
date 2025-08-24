import Swal from './module/sweetalert2.all.min+esm.js';
document.addEventListener('DOMContentLoaded', () => {
    const btnLogout = document.getElementById('btnLogout');
    btnLogout.addEventListener('click', () => {
        fetch('auth/logout', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(res => {
                if (!res.ok) {
                    throw new Error('Network response was not ok');
                }
                return res.json();
            })
            .then(data => {
                if (data.code === 200) {
                    Swal.fire({
                        title: data.title,
                        icon: 'success',
                        timer: 2000,
                        timerProgressBar: true,
                        text: data.message,
                        showConfirmButton: false,
                        allowOutsideClick: false
                    })
                        .then((result) => {
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location.href = 'login'
                            }
                        })
                }
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
    });
});