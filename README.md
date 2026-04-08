# Sistem Inventory

## Require

- PHP v8.1+
- Flutter SDK
- MySQL / MariaDB
- Postman
- Composer
- Text Editor VSCode
- Laragon / XAMPP (opsional)

## Sistem SSR Backend

1. Setelah melakukan clone silahkan masuk ke folder **web** (Root SSR)
2. Buka _command prompt / terminal_ yang mengarah pada folder **web** (Root SSR) yang sudah di clone
3. Pada terminal ketikan `composer install` untuk menginstal depedency yang diperlukan
4. Tunggu composer menginstall depedency
5. Jika sudah ada folder **vendor**, lanjutkan masuk ke folder _fixing_
6. Salin/Pindahkan folder **yii2-admin** ke folder **vendor/mdmsoft** lalu paste
7. Kembali ke folder _fixing_, salin/pindahkan folder **yii2-dynamicform** ke folder **vendor/wbraganca** lalu paste
8. Buat Database kosong menggunakan _Sistem RDBMS_ (MySqlog/Navicat/HeidiSQL/DBeaver/PHPMyAdmin)
9. Kembali ke folder **web** (Root SSR) lalu masuk ke folder **common/config**
10. Buka file **main-local.php** menggunakan Text Editor
11. Ubah bagian `define('SYS_DB', 'mysql:host=[YOUR IP/HOST DB];port=[YOUR PORT DB];dbname=[YOUR DB NAME]');` tanpa tanda `[]`
12. Lanjut ubah bagian `define('SYS_USERNAME', '[YOUR DB USERNAME]');` tanpa tanda `[]`
13. Lanjut ubah bagian `define('SYS_PASSWORD', '[YOUR DB PASSWORD]');` tanpa tanda `[]`
14. Masih didalam folder yang sama, Buka file **params.php**
15. Ubah _value_ dari _key_ **WebUrl** dengan DNS anda (default **http://localhost:8080**)
16. Login menggunakan username `X0001` dan password `147852` untuk mencoba

## Migration Database

1. Buka _command prompt / terminal_ yang mengarah pada folder **web** yang sudah di clone
2. Jalankan perintah `php yii migrate --interactive=0` terlebih dulu
3. Jalankan perintah `php yii migrate --migrationPath=@mdm/autonumber/migrations` untuk membuat tabel Auto Number

## Running System

- Untuk menjalankan Backend SSR gunakan perintah `php yii serve --docroot="frontend/web"` atau bisa menggunakan **Web Service** Seperti Nginx / Apache
- Untuk menjalankan REST API gunakan perintah `php yii serve --docroot="api/web"` atau bisa menggunakan **Web Service** Seperti Nginx / Apache
- Import Collection yang berada pada folder **web**/`Inventory API.postman_collection.json`

## Mobile

1. Aktifkan terlebih dulu **Mode Developer**
2. Untuk Windows buka Pengaturan: Klik tombol `Start`, lalu ketik **"Developer Settings"**
3. **Aktifkan Developer Mode**: Cari pilihan **Developer Mode** (Mode Pengembang) dan geser tombolnya ke arah **On** (Aktif)
4. Masuk ke folder **mobile** (Root Mobile) dan Open Project (VSCode) didalam folder tersebut
5. Install _extention_ **Flutter** pada VSCode jika belum ada
6. Buka _terminal_ dan jalankan perintah `flutter pub get`
7. Arahkan Flutter SDK, jika menggunakan Windows jalankan perintah `flutter config --android-sdk "C:\Users\[YOUR PC NAME]\AppData\Local\Android\Sdk"`
8. Masuk ke folder `lib` > `config`, dan buka file `constants.dart`
9. Ubah bagian di **line 4** `static const String baseUrl = '[YOUR HOST]';`
10. Pada menu VSCode pilih `Run` > `Start Debuggin` atau bisa tekan tombol `F5`
11. Jika Debug tidak berjalan, gunakan perintah `flutter doctor` lalu `flutter run -d chrome`
12. Login menggunakan username `X0001` dan password `147852` untuk mencoba

## NOTES

- Saya menggunakan **Laragon** dan memberi _virtual host_ ke frontend/web dan api/web agar lebih mudah dalam development web
