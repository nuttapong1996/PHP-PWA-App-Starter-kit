document.addEventListener('DOMContentLoaded',async ()=>{
    // ทำการลงทะเบียน Service Worker กับ Browser
    navigator.serviceWorker.register("./service-worker.js");

    // ปุ่ม Subscribe
    const SubBtn = document.getElementById('BtnSub');
    // ปุ่ม Unsubscribe
    const UnsubBtn = document.getElementById('BtnUnsub');
    // input ชื่อผู้ใช้
    const subName = document.getElementById('userName').value;
    
    UnsubBtn.style.display="none"

    const subscribe = await checksub(subName);

        if(subscribe !== true) {
              console.warn('User : ' + subName + ' is not subscribe to notification yet.');
        }else{
            console.info('Subscribed');
        }

    // const registration = await navigator.serviceWorker.ready;
    // const subscription = await registration.pushManager.getSubscription();

    //   //  ถ้าไม่มี subscription ในเครื่อง ให้ return false
    // if (!subscription) {
    //     return false; 
    // }

    // try {
    //     const res = await fetch("api/push/getSub.php",{
    //         method: "POST",
    //         headers:{
    //             "Content-Type" : "application/json"
    //         },
    //         body: JSON.stringify({
    //              subName :subName ,
    //              endpoint : subscription.endpoint
    //         })
    //     });

    //     const data = await res.json();
    //     console.log(data);

    // } catch (error) {
        
    // }
});


async function checksub(subName){
    // ดึง subscription ปัจจุบันจาก Service Worker
    const registration = await navigator.serviceWorker.ready;
    //  ดึงค่า endpoint จาก subscription
    const subscription = await registration.pushManager.getSubscription();

    //  ถ้าไม่มี subscription ในเครื่อง ให้ return false
    if (!subscription) {
        return false; 
    }

    const response =  await fetch('api/push/getSub.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            subName :subName ,
            endpoint: subscription.endpoint
        })
    });

    // แปลงข้อมูลที่ถูกส่งกลับมาจาก get_sub.php เป็น json
    const data = await response.json();

    // ถ้าเป็น success ให้ return true
    if(data ['status'] === 'sub') {
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


