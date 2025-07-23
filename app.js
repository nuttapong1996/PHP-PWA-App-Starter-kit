document.addEventListener('DOMContentLoaded', async ()=>{
    // ทำการลงทะเบียน Service Worker กับ Browser
    navigator.serviceWorker.register("service-worker.js");

    const SubBtn = document.getElementById('BtnSub');
    const formSend = document.getElementById('formSend');
    const formUnsub = document.getElementById('formUnsub');

    const user_session = await checksession();
    const subscribe = await checksub();

    SubBtn.addEventListener('click', enableNotif);

    if(user_session) {
        if(subscribe !== true) {
            SubBtn.style.display = 'block';
            console.warn('User : ' + user_session + ' is not subscribe to notification yet.');
        }else{
            SubBtn.style.display = 'none';
            formSend.style.display = 'block';
            formUnsub.style.display = 'block';
            console.info('Subscribed');
        }
    }else{
        console.warn('Not login');
    }
});

// Function เช็ค login session
async function checksession(){
    // โดยดึงค่ามาจาก Session ที่ login ผ่าน backend ของ  php
    const response =  await fetch('backends/get_session.php');

    // แปลงเป็น json
    const data = await response.json();

    // ถ้าเป็น success ให้ return username ของผู้ใช้
    if(data['status'] === 'success') {
        return data['username'];
    }
}

// Function เช็ค การ subscription จากฐานข้อมูล
async function checksub(){
    // ดึง subscription ปัจจุบันจาก Service Worker
    const registration = await navigator.serviceWorker.ready;
    //  ดึงค่า endpoint จาก subscription
    const subscription = await registration.pushManager.getSubscription();

    //  ถ้าไม่มี subscription ในเครื่อง ให้ return false
    if (!subscription) {
        return false; 
    }

    // ทำการ fetch ส่งค่า endpoint ไปเพื่อทำการเทียบกับ endpoint ในฐานข้อมูล ในไฟล์ get_sub.php
    const response =  await fetch('backends/get_sub.php', {
        // ส่งโดยวิธี POST
        method: 'POST',
        // ส่งเป็น JSON
        headers: {
            'Content-Type': 'application/json'
        },
        // ส่ง ค่า endpoint(ที่ถูกสร้างบนเครื่อง) ไปเพื่อทำการเทียบกับ endpoint ในฐานข้อมูล
        body: JSON.stringify({
            // endpoint = ค่าendpoint ที่ถูกสร้างบนเครื่อง
            endpoint: subscription.endpoint
        })
    });

    // แปลงข้อมูลที่ถูกส่งกลับมาจาก get_sub.php เป็น json
    const data = await response.json();

    // ถ้าเป็น success ให้ return true
    if(data ['status'] === 'success') {
        return true;
    }
}

// Function สมัครการแจ้งเตือน
async function enableNotif() {
    // ทำการ fetch เพื่อดึงเอา public key สำหรับใช้ในการสร้าง subscription
    const response = await fetch('configs/get-vapid.php');
    const applicationServerKey = await response.json();

    // ทำการร้องขอการอนุญาตจาก Browser ให้แสดง Notification
    Notification.requestPermission().then((permission)=> {
        // ถ้ามีการอนุญาต
        if (permission === 'granted') {
            // เรียกใช้งาน Service Worker
            navigator.serviceWorker.ready.then((sw)=> {
                // subscribe
                sw.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: applicationServerKey['publicKey']
                }).then( async (subscription)=> {

                    const response = await fetch('api/push/sub.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(subscription)
                    })
                    const data = await response.json();

                if (data['status'] === 'success') {
                    alert('Subscribed');
                    setTimeout(() => {
                        window.location.reload();
                    }, 0);
                }else if (data['status']=== 'error') {
                    alert('Not Subscribed');
                }
                });
            });
        }
    });
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
