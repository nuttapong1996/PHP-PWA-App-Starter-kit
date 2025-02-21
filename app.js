document.addEventListener('DOMContentLoaded', async ()=>{

    navigator.serviceWorker.register("service-worker.js");

    const SubBtn = document.getElementById('BtnSub');
    const BtnSend = document.getElementById('BtnSend');

    BtnSend.style.display = 'none';

    SubBtn.addEventListener('click', enableNotif);

    const user_session = await checksession();
    const subscribe = await checksub();

    if(user_session) {
        if(subscribe !== true) {
            SubBtn.style.display = 'block';
            console.warn('Not subscribe yet');
        }else{
            SubBtn.style.display = 'none';
            BtnSend.style.display = 'block';
            console.log('Subscribed');
        }
    }else{
        console.warn('Not login');
    }
    
});


// Function เช็ค login session
async function checksession(){
    // โดยดึงค่ามาจาก Session ที่ login ผ่าน backend ของ  php
    const response =  await fetch('backends/get_session.php');
    const data = await response.json();

    if(data['status'] === 'success') {
        return data['empcode'];
    }
}

// Function เช็ค การ subscription จากฐานข้อมูล
async function checksub(){
     // ดึง subscription ปัจจุบันจาก Service Worker
     const registration = await navigator.serviceWorker.ready;
     const subscription = await registration.pushManager.getSubscription();

     if (!subscription) {
        return false; // ไม่มี subscription ในเครื่อง
    }

    const response =  await fetch('backends/get_sub.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            endpoint: subscription.endpoint
        })
    });

    const data = await response.json();

    if(data ['status'] === 'success') {
        return true;
    }
}

// Function สำหรับส่ง Message จาก Client-side แต่ในการใช้จริงๆ เราจะส่ง Noti ด้าน Server-side
// จึง Comment ไว้ก่อน 
    // async function sendNotif(title, body, url) {
    //     const emp_respone = await fetch('backends/get_empcode.php');
    //     const emp_data = await emp_respone.json();

    //     if(emp_data['status'] === 'success') {
    //         const response =  await fetch('send.php', {
    //             method: 'POST',
    //             headers: {
    //                 'Content-Type': 'application/json'
    //             },
    //             body: JSON.stringify({
    //                 // empcode: empcode,
    //                 title:title,
    //                 body:body,
    //                 url:url
    //             })
    //         });
    //         const send_data = await response.json();

    //         if(send_data['status'] === 'success') {
    //             console.log('Send Success');
    //         }else{
    //             console.log('Send Error');
    //         }
    //     }else{
    //         console.log('Send Error');
    //     }
    // }

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

                    const response = await fetch('backends/subscribe.php', {
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