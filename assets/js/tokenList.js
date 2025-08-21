import Swal from './module/sweetalert2.all.min+esm.js';
document.addEventListener('DOMContentLoaded', async () => {
    const tokenTable = document.getElementById('tokenTable');

    await fetch('auth/token-list', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then(res => {
            if (!res.ok) {
                throw new Error('HTTP error ' + res.status);
            }
            return res.json();
        })
        .then(data => {
            if (data.code === 200) {
                for (let i = 0; i < data.count; i++) {
                    const row = document.createElement('tr');
                    row.innerHTML = `<td>${i + 1}</td>
                    <td>${data.response[i].device_name}</td>
                    <td>${data.response[i].ip_address}</td>
                    <td>${data.response[i].expires_at}</td>
                    <td class='text-center'>${data.response[i].remark}</td>`;
                    tokenTable.appendChild(row);
                    const btn = document.getElementById('btnLogout');
                    btn.addEventListener('click', () => {
                        Swal.fire({
                            title: 'Are your sure ?',
                            text: 'to delete '+data.response[i].device_name+'\n IP:'+data.response[i].ip_address,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            allowOutsideClick: false
                        })
                            .then(async (result) => {
                                if (result.isConfirmed) {
                                    await fetch('auth/rmtoken', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json'
                                        },
                                        body: JSON.stringify({
                                            tokenid: data.response[i].token_id
                                        })
                                    })
                                        .then(rm => {
                                            if (!rm.ok) {
                                                throw new Error('HTTP Error' + rm.status);
                                            }
                                            return rm.json();
                                        })
                                        .then(rm_data => {
                                            if (rm_data.code === 200) {
                                                Swal.fire({
                                                    title: 'Token deleted !',
                                                    text: 'You have successfully delete the device token.',
                                                    icon: 'success',
                                                    timer: 2000,
                                                    showConfirmButton: false,
                                                    allowOutsideClick: false
                                                })
                                                    .then((result) => {
                                                        if (result.dismiss === Swal.DismissReason.timer) {
                                                            window.location.reload();
                                                        }
                                                    })
                                            }
                                        })
                                }
                            })
                    });
                }
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
        });

});