# Sistem Inventory

## Require

- PHP v8.1+
- MySQL / MariaDB
- Postman
- Composer

## Sistem SSR Frontend

1. Setelah melakukan clone silahkan masuk ke folder **web**
2. Buka _command prompt / terminal_ yang mengarah pada folder **web** yang sudah di clone
3. Pada terminal ketikan `composer install` untuk menginstal depedency yang diperlukan
4. Tunggu composer menginstall depedency
5. Jika sudah ada folder **vendor**, lanjutkan masuk ke folder _fixing_
6. Salin/Pindahkan folder **yii2-admin** ke folder **vendor/mdmsoft** lalu paste
7. Kembali ke folder _fixing_, salin/pindahkan folder **yii2-dynamicform** ke folder **vendor/wbraganca** lalu paste
8. Buat Database menggunakan _Sistem RDBMS_ (MySqlog/Navicat/HeidiSQL/DBeaver/PHPMyAdmin)
9. Masuk ke folder **common/config** yang berada didalam folder _web_
10. Buka file **main-local.php**
11. Ubah bagian `define('SYS_DB', 'mysql:host=[YOUR IP/HOST DB];port=[YOUR PORT DB];dbname=[YOUR DB NAME]');`
12. Lanjut ubah bagian `define('SYS_USERNAME', '[YOUR DB USERNAME]');`
13. Lanjut ubah bagian `define('SYS_PASSWORD', '[YOUR DB PASSWORD]');`

## Migration Database

1. Buka _command prompt / terminal_ yang mengarah pada folder **web** yang sudah di clone
2. Jalankan perintah `php yii migrate --interactive=0` terlebih dulu
3. Jalankan perintah `php yii migrate --migrationPath=@mdm/autonumber/migrations` untuk membuat tabel Auto Number
