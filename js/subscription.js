// Function Check user subscription to Notification.
export async function checksub() {

    // Create variable (registration) and assign subscription's value from recurent Service Worker.
    const registration = await navigator.serviceWorker.ready;

    // Create variable (subscription) and assign value from variable (registration) .
    const subscription = await registration.pushManager.getSubscription();

    // If there is no subscription return false .
    if (!subscription) {
        return false;
    }

    // Fetch to send Subscription to Server for validation
    await fetch()
    const response = await fetch('api/push/sub', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            endpoint: subscription.endpoint
        })
    });

    // แปลงข้อมูลที่ถูกส่งกลับมาจาก get_sub.php เป็น json
    const data = await response.json();

    // ถ้าเป็น success ให้ return true
    if (data['status'] === 'sub') {
        return true;
    }
}


// Function สมัครการแจ้งเตือน
export async function enableNotif() {
    // ทำการ fetch เพื่อดึงเอา public key สำหรับใช้ในการสร้าง subscription
    const response = await fetch('configs/get-pk.php');
    const applicationServerKey = await response.json();

    // ทำการร้องขอการอนุญาตจาก Browser ให้แสดง Notification
    Notification.requestPermission().then((permission) => {
        // ถ้ามีการอนุญาต
        if (permission === 'granted') {
            // เรียกใช้งาน Service Worker
            navigator.serviceWorker.ready.then((sw) => {
                // subscribe
                sw.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: applicationServerKey['publicKey']
                }).then(async (subscription) => {

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
                        window.location.reload();
                    } else if (data['status'] === 'error') {
                        alert('Not Subscribed');
                    }
                });
            });
        }
    });
}