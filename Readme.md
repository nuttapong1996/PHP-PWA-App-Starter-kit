
    <h1>PHP Web Api</h1>
    <h3>Feature</h3>
    <ul>
        <li>ปรับปรุงการโครงสร้างใหม่ในรูปแบบ MVC : Model View Controller</li>
        <li>ระบบ Jwt Authentication ทั้ง Frontend และ Backend.</li>
        <li>มีการ Refresh Access token และ Renew Refresh token ทั้ง Client และ Server</li>
        <li>ผู้ใช้งานสามารถจัดการ Login session</li>
        <li>ผู้ใช้งานสามารถจัดการ รายการแจ้งเตือนนได้ เช่นการ Unsubscription</li>
        <li>สามารถแสดงหน้า Offline ได้เหมือน App</li>
        <li>สามารถส่งแจ้งเตือนได้แม้จะปิดหน้าเว็บไปแล้วก็ตาม (Web Push)</li>
        <li>มีตัวอย่าง API สำหรับการส่งแจ้งเตือนให้ โดยสามารถเรียกใช้ได้จากโปรแกรม Postman (จะมีบรรยายไว้ที่หัวข้อ API)
        </li>
        <li>สามารถใช้งานได้บน Google chrome รวมทั้ง Android และ ios 16 ขึ้นไป โดยสามารถกดติดตั้ง หรือ Add to Home ได้เลย
        </li>
    </ul>
    <h3>ความต้องการ</h5>
        <ul>
            <li>
                <h5>PHP version 8.1 ขึ้นไป </h5>
            </li>
            <h5>PHP extensions: ส่วนเสริมของ PHP</h5>
            <ul>
                <li>bcmath and/or gmp</li>
                <li>mbstring</li>
                <li>curl</li>
                <li>openssl (สำคัญ) </li>
            </ul>
            <li>
                <h5>Xampp (สำหรับการรันเซิฟเวอร์)</h5>
            </li>
            <li>
                <h5>composer (สำหรับการติดตั้ง Library)</h5>
            </li>
        </ul>
        <h3>ก่อนใช้งาน</h3>
        <h5>การติดตั้ง Openssl บน Windows ( โดยใช้ Xampp)</h5>
        <ol>
            <li>ไปที่ Environment Variable</li>
            <li>ในหัวข้อของ System variables > คลิกเลือก New </li>
            <h6>กรอกข้อมูลดังนี้</h6>
            <ul>
                <li>Variable name : OPENSSL_CONF</li>
                <li>Variable value : C:\xampp\apache\conf\openssl.cnf</li>
            </ul>
            <li>กด OK และ รีสตาร์ทคอมพิวเตอร์</li>
        </ol>
        <h5>สร้าง Database ใน mysql</h5>
        <ol>
            <li>สร้างฐานข้อมูลชื่อ noti_db ใน mysql</li>
            <li>ทำการ import database จาก โฟลเดอร์ configs โดยใช้ phpmyadmin หรือ Navicat ก็ได้ </li>
        </ol>
        <h3>การใช้งาน</h3>
        <ol>
            <li>clone หรือ ดาวโหลด Repository นี้</li>
            <li>แตกไฟล์ หรือ นำไปวางที่ C:/xampp/htdocs</li>
            <li>เปิด Terminal และ พิมพ์คำสั่ง composer install (ต้องติดตั้ง composer ก่อน)</li>
            <li>เข้าไปเปลี่ยนชื่อของแอพในไฟล์ manifest.json ในหัวข้อของ name และ short_name</li>
            <li>เข้าไปเปลี่ยชื่อของ cache ของแอพในไฟล์ service-worker.js ในหัวข้อของ CACHE_NAME</li>
            <li>สร้างไฟล์ .env ในโฟลเดอร์ includes</li>
            <li>ใส่โค๊ดดังนี้ลงไป</li>
            <code>
                APP_NAME= ชื่อของแอพหรือเว็บ <br>
                APP_DOMAIN = โดเมนเนม <br>
                BASE_PATH = /ชื่อโฟลเดอร์ของโปรเจ็ค <br>
                DB_DSN = mysql <br>
                DB_HOST=localhost  <br>
                DB_DATABASE=noti_db  <br>
                DB_USERNAME=root <br>
                DB_PASSWORD= <br>
                DB_PORT= 3306 <br>
                VAPID_PUBLIC_KEY =  <br>
                VAPID_PRIVATE_KEY =  <br>
                SECRET_KEY =  สามารถสร้างจาก <a href="https://it-tools.tech/token-generator?length=64">เว็บนี้</a><br>
            </code>
            <li>ทำการสร้าง VAPID Key โดยให้ทำการรันไฟล์ make-vapid.php ที่อยู่ในโฟลเดอร์ configs โดยสามารถรันจาก Browser หรือผ่าน Terminal
                ก็ได้โดยใช้คำสั่ง php vapid.php </li>
            <li>คัดลอก public_key และ private_key ที่ถูกสร้างจากข้อก่อนหน้านี้ไปใส่ในไฟล์ .env ดัวอย่างนี้</li>
            <code>
            DB_HOST=localhost <br>
            DB_USERNAME=root <br>
            DB_PASSWORD= <br>
            DB_DATABASE=noti_db <br>
            VAPID_PUBLIC_KEY =  public_key ที่ถูกคัดลอกมา <br>
            VAPID_PRIVATE_KEY = private_key ที่ถูกคัดลอกมา<br>
        </code>
        </ol>
