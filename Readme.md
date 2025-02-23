<h1>PHP PWA App Starter Kit</h1>
<h4>หมายเหตุ : ในโปรเจ็คนี้มีการใช้งาน <a href="https://github.com/nuttapong1996/web-push-php"> Web Push library for PHP</a> </h4>
<h3>คุณสมบัติ</h3>
<ul>
    <li>สามารถแสดงหน้า Offline ได้เหมือน App</li>
    <li>สามารถส่งแจ้งเตือนได้แม้จะปิดหน้าเว็บไปแล้วก็ตาม</li>
    <li>มีตัวอย่าง API สำหรับการส่งแจ้งเตือนให้ โดยสามารถเรียกใช้ได้จากโปรแกรม Postman (จะมีบรรยายไว้ที่หัวข้อ API) </li>
    <li>สามารถใช้งานได้บน Google chrome รวมทั้ง Android และ ios 16 ขึ้นไป โดยสามารถกดติดตั้ง หรือ Add to Home ได้เลย</li>
</ul>
<h3>ความต้องการ</h5>
    <ul>
        <li><h5>PHP version 8.1 ขึ้นไป </h5></li>
        <h5>PHP extensions: ส่วนเสริมของ PHP</h5>
            <ol>
                <li>bcmath and/or gmp (จะใช้หรือไม่ใช้ก็ได้ แต่เพื่อประสิทธิภาพที่ดีควรมี)</li>
                <li>mbstring</li>
                <li>curl</li>
                <li>openssl (สำคัญ) </li>
            </ol>
        <li><h5>Xampp (สำหรับการรันเซิฟเวอร์)</h5></li>
        <li><h5>composer (สำหรับการติดตั้ง Library)</h5></li>
    </ul>
    
<h3>ก่อนใช้งาน</h3>
<ul>
   <li><h5>การติดตั้ง Openssl บน Windows ( โดยใช้ Xampp)</h5></li>
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
    <li><h5>สร้าง Database ใน mysql</h5></li>
    <ol>
        <li>สร้างฐานข้อมูลชื่อ noti_db ใน mysql</li>
        <li>ทำการ run Query นี้เพื่อสร้างตาราง</li>
        <li>สร้างไฟล์ .env ในโฟลเดอร์ includes</li>
        <li>ใส่โค๊ดดังนี้ลงไป</li>
        <code>
            DB_HOST=localhost <br>
            DB_USERNAME=root <br>
            DB_PASSWORD= (หากไม่มีก็ไม่ใส่ ) <br>
            DB_DATABASE=noti_db <br>
        </code>
    </ol>
</ul>
<h3>การใช้งาน</h3>
    <ol>
        <li>clone Repository นี้</li>
        <li>พิมพ์คำสั่ง composer install ใน Terminal</li>
    </ol>
