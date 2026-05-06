# 1. ใช้ Image PHP ที่มี Apache มาให้เลย
FROM php:8.2-apache

# 2. ก๊อปปี้ไฟล์ทั้งหมดใน GitHub (รวมถึง bot.php) ลงไปในเครื่องเซิร์ฟเวอร์
COPY . /var/www/html/

# 3. เปิดสิทธิ์ให้ PHP เขียนไฟล์ได้ (เผื่อคุณยังใช้ last_post.txt)
RUN chmod -R 777 /var/www/html/

# 4. บอกให้ Apache รันที่ Port 80 (ค่าเริ่มต้นของ Render)
EXPOSE 80
