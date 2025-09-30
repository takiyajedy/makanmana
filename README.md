# 🍽️ MakanMana – Food Recommendation App

Projek ini adalah aplikasi web berasaskan Laravel + MySQL untuk cadangan restoran/kedai makan berdekatan pengguna.  
User boleh login, lihat senarai restoran, menu, peta lokasi, dan tekan butang "Choose for me" untuk pilih restoran rawak dalam radius tertentu.

---

## 🚀 Features
- Login & register (Laravel Breeze)
- Senarai restoran + menu
- Admin boleh tambah/edit/padam restoran & menu
- Peta interaktif (Leaflet) untuk tunjuk restoran berhampiran
- Butang **"Choose for me"** cadangkan restoran rawak
- Data awal dengan **database seeder**

---

## 🛠️ Teknologi Digunakan
- [Laravel 10](https://laravel.com/)
- [MySQL](https://www.mysql.com/)
- [Bootstrap 5](https://getbootstrap.com/)
- [Leaflet.js](https://leafletjs.com/) – untuk peta
- [Composer](https://getcomposer.org/)
- [Node.js & NPM](https://nodejs.org/)
- 
---

## ⚙️ Cara Setup Projek

### 1. Clone repository
```bash
git clone git@gitlab.com:username/makanmana.git
cd makanmana

composer install
npm install && npm run dev

---
##  Setup environment
Copy .env.example → .env

---

bash
Copy code
cp .env.example .env
Edit .env dan masukkan setting DB:

makefile
Copy code
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=makanmana
DB_USERNAME=root
DB_PASSWORD=
Kalau guna Google Maps API (optional):

ini
Copy code
GOOGLE_MAPS_API_KEY=your_key_here
4. Generate app key
bash
Copy code
php artisan key:generate
5. Run migration & seeder
bash
Copy code
php artisan migrate --seed
Seeder akan create:

1 user login (admin)

Beberapa restoran & menu dummy

6. Jalankan projek
bash
Copy code
php artisan serve
Akses di browser:

cpp
Copy code
http://127.0.0.1:8000
