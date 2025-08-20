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
                    // const btn = document.getElementById('btnLogout');
                    // btn.addEventListener('click', () => {
                    //     console.log(data.response[i].token_id);
                    // });
                }
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
        });

});