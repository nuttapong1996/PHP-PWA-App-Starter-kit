Requirement 

PHP version 8.1+ 
extensions:
bcmath and/or gmp (optional but better for performance)
mbstring
curl
openssl (with elliptic curve support)
composer



Pre-use
A:การติดตั้ง Openssl บน Windows ( โดยใช้ Xampp)
1.ไปที่ Environment Variable
2.ในหัวข้อของ System variables > คลิกเลือก New 
3. กรอกข้อมูลดังนี้
 3.1.Variable name : OPENSSL_CONF
 3.2.Variable value : C:\xampp\apache\conf\openssl.cnf
4. กด OK และ รีสตาร์ทคอมพิวเตอร์

B:สร้าง Database ใน mysql
1.สร้างฐานข้อมูลชื่อ noti_db ใน mysql
2.ทำการ run Query นี้เพื่อสร้างตาราง

C:สร้างไฟล์ .env ในโฟลเดอร์ includes
1.ใส่โค๊ดดังนี้ลงไป
DB_HOST=localhost
DB_USERNAME=root
DB_PASSWORD= (หากไม่มีก็ไม่ใส่ )
DB_DATABASE=noti_db

D:โหลด Repository นี้
1.พิมพ์คำสั่ง composer install ใน Terminal



