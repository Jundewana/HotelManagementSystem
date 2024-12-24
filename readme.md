# Botble CMS

Botble CMS adalah sebuah framework PHP yang dibangun di atas Laravel untuk memudahkan pembuatan dan pengelolaan konten website.

## Persyaratan

- PHP >= 7.3
- Composer
- MySQL
- Node.js (untuk pengembangan frontend)

## Instalasi

1. Clone repository ini:

    ```sh
    git clone https://github.com/Jundewana/HotelManagementSystem
    cd repository
    ```

2. Salin file [.env.example](http://_vscodecontentref_/1) menjadi [.env](http://_vscodecontentref_/2) dan sesuaikan konfigurasi database Anda:

    ```sh
    cp .env.example .env
    ```

3. Instal dependensi PHP menggunakan Composer:

    ```sh
    composer install
    ```

4. Instal dependensi frontend menggunakan npm:

    ```sh
    npm install
    ```

5. Generate key aplikasi:

    ```sh
    php artisan key:generate
    ```

6. Migrasi dan seed database:

    ```sh
    php artisan migrate --seed
    ```

7. Jalankan server pengembangan:

    ```sh
    php artisan serve
    ```

8. Buka browser dan akses `http://localhost:8000`.

## Struktur Direktori

- [app](http://_vscodecontentref_/3) - Berisi kode aplikasi utama.
- [bootstrap](http://_vscodecontentref_/4) - Berisi file bootstrap dan cache.
- [config](http://_vscodecontentref_/5) - Berisi file konfigurasi.
- [database](http://_vscodecontentref_/6) - Berisi migrasi, seeder, dan file database lainnya.
- [public](http://_vscodecontentref_/7) - Berisi file yang dapat diakses publik seperti `index.php`.
- [resources](http://_vscodecontentref_/8) - Berisi view, asset, dan file bahasa.
- [routes](http://_vscodecontentref_/9) - Berisi file rute.
- [storage](http://_vscodecontentref_/10) - Berisi file yang dihasilkan oleh aplikasi seperti log dan cache.
- [tests](http://_vscodecontentref_/11) - Berisi file pengujian.
- [vendor](http://_vscodecontentref_/12) - Berisi dependensi Composer.

## Konfigurasi

Sesuaikan file [.env](http://_vscodecontentref_/13) untuk mengatur konfigurasi aplikasi, seperti database, cache, dan lainnya.

## Migrasi

Untuk menjalankan migrasi database, gunakan perintah berikut:

```sh
php artisan migrate