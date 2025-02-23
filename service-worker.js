// ตั้งชื่อ cache
const CACHE_NAME = "php-push-cache";

// ไฟล์สำหรับแสดงหน้า offline
const OFFLINE_URL = 'offline.php';

// ไฟล์ที่ต้องการ cache
const contentToCache = [
    'offline.php',
    'css/font.css'
];

// ทำการเพิ่ม Event Listener "Push" ให้กับ Service Worker
self.addEventListener("push", (event) => {
    // แปลงข้อมูลให้เป็น json
    const notification = event.data.json();
    // แสดงแจ้งเตือน
    event.waitUntil(self.registration.showNotification(
        // แสดงชื่อแจ้งเตือน
        notification.title, {
        // แสดงรายละเอียด
        body: notification.body,
        // แสดงไอคอน
        icon: "assets/icons/icon.png",
        // กำหนด url
        data: {
            notifURL: notification.url
        }
    }));
});

// เมื่อมีการคลิกที่แท็บแจ้งเตือนให้เปิด url
self.addEventListener("notificationclick", (event) => {
    event.waitUntil(clients.openWindow(event.notification.data.notifURL));
});

// ติดตั้ง Service Worker และทำการ cache ไฟล์
self.addEventListener('install', event => {
	event.waitUntil(
    caches.open(CACHE_NAME).then(cache => {
		contentToCache.forEach(content => {
			cache.add(content).catch(_ => console.error(`Error while caching "${content}"`))
		});
	}));
  console.log('Service Worker installed');
});

// จัดการ Fetch request
self.addEventListener('fetch', event => {
    if (event.request.mode === 'navigate') {
      event.respondWith(
        fetch(event.request).catch(() => {
            return caches.open(CACHE_NAME).then(cache => {
            return cache.match(OFFLINE_URL);
          });
        })
      );
    }
  });
  
// ลบ Cache เก่าที่ไม่ได้ใช้
self.addEventListener('activate', event => {
const cacheWhitelist = [CACHE_NAME];
event.waitUntil(
    caches.keys().then(cacheNames => {
    return Promise.all(
        cacheNames.map(cacheName => {
        if (!cacheWhitelist.includes(cacheName)) {
            return caches.delete(cacheName);
        }
        })
    );
    })
);
});