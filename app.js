document.addEventListener('DOMContentLoaded', async ()=>{

    navigator.serviceWorker.register("sw.js");

    const SubBtn = document.getElementById('BtnSub');
    const BtnSend = document.getElementById('BtnSend');
    const BtnLogout = document.getElementById('BtnLogout');
    // const loginform = document.getElementById('login-form');
    const empname = document.getElementById('empname');
    const empcode = await checksession();

    SubBtn.style.display = 'none';
    BtnSend.style.display = 'none';
    empname.innerHTML = 'Login as : ' + empcode;
    SubBtn.addEventListener('click', enableNotif);


    // กำหนดการส่ง push notif ให้กับปุ่ม send
    BtnSend.addEventListener('click', async ()=>{
        await sendNotif('แจ้งเตือนใหม่!', 'สวัสดี : '+empcode, 'index.html?message=123');
    });

    setInterval(async () => {
        // const empcode = await checksession();
        const sub = await checksub();
            if(sub == true) {
                SubBtn.style.display = 'none';
                BtnSend.style.display = 'block';
            }else if(sub == false) {
                SubBtn.style.display = 'block';
            }
    },1000);
});



//  async function login() {
//     const loginform = document.getElementById('login-form');
//     const empcode = document.getElementById('empcode');
//     const password = document.getElementById('password');


//     loginform.addEventListener('submit', async (e) => {
//         e.preventDefault();

//         if(empcode.value === '' || password.value === '') {
//             alert('กรุณากรอกข้อมูลให้ครบ');
//             return;
//         }

//         const response = await fetch('login_proc.php', {
//             method: 'POST',
//             headers:{
//                 'Content-Type': 'application/json'
//             },
//             body: JSON.stringify({
//                 empcode: empcode.value,
//                 password: password.value
//             })
//         });

//         const data = await response.json();

//         // Check login
//         if(data['status'] === 'success') { 
//             alert('Login Success');
//         }
//     })
// }


async function checksession(){
    const response =  await fetch('get_session.php');
    const data = await response.json();

    if(data['status'] === 'success') {
        return data['empcode'];
    }
}

async function checksub(){
     // ดึง subscription ปัจจุบันจาก Service Worker
     const registration = await navigator.serviceWorker.ready;
     const subscription = await registration.pushManager.getSubscription();

     if (!subscription) {
        return false; // ไม่มี subscription ในเครื่อง
    }

    const response =  await fetch('get_sub.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(subscription)
    }
    );
    const data = await response.json();

    if(data ['status'] === 'success') {
        return true;
    }else if(data['status'] === 'error') {
        return false;
    }
}

async function sendNotif(title, body, url) {
    // const empcode = await checksession();

    const emp_respone = await fetch('get_empcode.php');
    const emp_data = await emp_respone.json();

    if(emp_data['status'] === 'success') {
        const response =  await fetch('send.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                // empcode: empcode,
                title:title,
                body:body,
                url:url
            })
        });
        const send_data = await response.json();

        if(send_data['status'] === 'success') {
            console.log('Send Success');
        }else{
            console.log('Send Error');
        }
    }else{
        console.log('Send Error');
    }
}


async function enableNotif() {
    Notification.requestPermission().then((permission)=> {
        if (permission === 'granted') {
            // get service worker
            navigator.serviceWorker.ready.then((sw)=> {
                // subscribe
                sw.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: "BEQdLcaaNBD-nYLwfVdhI8bteRKHIKr4fEn9Dnz6kX5HiRLA64VZlORjXX2ExN9YHKhMmBwHBW1WZOM4zCx11p4"
                }).then( async (subscription)=> {

                    const response = await fetch('subscribe.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(subscription)
                    })
                    const data = await response.json();

                if (data['status'] === 'success') {
                    alert('Subscribed');
                }else if (data['status']=== 'error') {
                    alert('Not Subscribed');
                }

                });
            });
        }
    });
}