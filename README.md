# symfony-adminlte-bootstrap

## Cara Install di Windows OS

    1. Copy folder ini ke direktori server (www/htdocs)
    2. Ubah konfigurasi database pada file app/config/parameters.yml
    3. Buka Command Prompt dan pindah ke direktori ini
    4. Buat database dengan menjalankan perintah berikut pada Command Prompt:
      `php bin/console doctrine:database:create`
      Catatan: Jika php belum terdaftar di environment, gunakan *fullpath* seperti contoh berikut:
      `C:\xampp\php\php.exe bin/console doctrine:database:create`
    5. Import schema database dengna menjalankan perintah selanjutnya pada Command Prompt:
      `php bin/console doctrine:schema:create`
      Catatan: Jika php belum terdaftar di environment, gunakan *fullpath* seperti contoh berikut:
      `C:\xampp\php\php.exe bin/console doctrine:schema:create`
    6. Buat user dengan menjalankan perintah selanjutnya pada Command Prompt:
      `php bin/console user:create admin admin`
      Catatan: Jika php belum terdaftar di environment, gunakan *fullpath* seperti contoh berikut:
      `C:\xampp\php\php.exe bin/console user:create admin admin`
    7. Akses direktori ini dengan browser. Anda bisa masuk menggunakan akun admin dan password admin.
        Contoh URL: http://localhost/{nama-folder-ini}/web
    8. Ok sip.


