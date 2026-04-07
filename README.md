# Sistem Inventory

## Sistem SSR Frontend

1. Setelah melakukan clone silahkan masuk ke folder **web**
2. Buka _command prompt / terminal_ yang mengarah pada folder **web** yang sudah di clone
3. Pada terminal ketikan `composer install` untuk menginstal depedency yang diperlukan
4. Tunggu composer menginstall depedency
5. Jika sudah ada folder **vendor**, lanjutkan masuk ke folder _fixing_
6. Salin/Pindahkan folder **yii2-admin** ke folder **vendor/mdmsoft** lalu paste
7. Kembali ke folder _fixing_, salin/pindahkan folder **yii2-dynamicform** ke folder **vendor/wbraganca** lalu paste
8. Masuk ke folder **common/config** yang berada didalam folder _web_
9. Buka file **main-local.php**
10. Ubah bagian `define('SYS_DB', 'mysql:host=[YOUR IP/HOST DB];port=[YOUR PORT DB];dbname=[YOUR DB NAME]');`
11. Lanjut ubah bagian `define('SYS_USERNAME', '[DB USERNAME]');`
12. Lanjut ubah bagian `define('SYS_PASSWORD', '[DB PASSWORD]');`

## Migration Database

1. Buka _command prompt / terminal_ yang mengarah pada folder **web** yang sudah di clone
2. Jalankan perintah `php yii migrate --migrationPath=@yii/rbac/migrations` untuk membuat tabel RBAC
3. Jalankan perintah `php yii migrate --migrationPath=@mdm/autonumber/migrations` untuk membuat tabel Auto Number
