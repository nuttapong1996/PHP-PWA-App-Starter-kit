import Swal from './module/sweetalert2.all.min+esm.js';
import { disableNotif } from './module/subscription.js';

document.addEventListener('DOMContentLoaded', async () => {
    const subTable = document.getElementById('subTable');

    await fetch('api/push/getall', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
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
                    <td>${data.response[i].create_at}</td>
                    <td class='text-center'><button id="btnUnsub" class="btn btn-danger btn-sm" data-endpoint="${data.response[i].endpoint}">Unsubscribe</button></td> </td>`;
                    subTable.appendChild(row);
                    const btn = document.getElementById('btnUnsub');
                    btn.addEventListener('click', () => {
                        Swal.fire({
                            title: 'Are you sure?',
                            text: "To Unsubscribe this device",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes'
                        })
                            .then(async (result) => {
                                if (result.isConfirmed) {
                                    await disableNotif(data.response[i].endpoint);
                                    Swal.fire({
                                        title: 'Unsubscribe!',
                                        text: 'You have successfully unsubscribed to notifications',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                }
                            })
                    });
                }
            }else{
                const row = document.createElement('tr');
                row.innerHTML = `<td colspan='5' class='text-center'> No Subscription </td>`
                subTable.appendChild(row);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
        });
});