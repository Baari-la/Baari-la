# 🚀 Digestex V2 - Market Intelligence System

Sistem intelijen pasar tekstil otomatis yang menghubungkan data bursa global (NY/ICE) ke Dashboard Laravel 12.

## 🏠 Sinkronisasi Rumah-Kantor

Setiap kali berpindah workstation, lakukan langkah berikut:

### 1. Di Workstation Asal (Sebelum Pulang)

- **Git Push**: `git add .` -> `git commit -m "update"` -> `git push origin main`
- **Database**: Jika ada data manual baru, lakukan Export SQL ke Flashdisk.

### 2. Di Workstation Tujuan (Saat Tiba)

- **Git Pull**: `git pull origin main`
- **Install Library**: `npm install`
- **Migrate**: `php artisan migrate`

---

## 🐍 Menjalankan Python (Update Data Bursa)

Karena jalur Python bisa berbeda, gunakan perintah ini:

**PC Rumah:**

```bash
& "C:\Users\user\AppData\Local\Programs\Python\Python313\python.exe" scripts/cotton_price.py
```

**PC Kantor:**

```bash
python scripts/cotton_price.py
```

_(Gunakan `pip install yfinance mysql-connector-python pandas` jika library belum ada)_

---

## 🛠️ Perbaikan Umum (Troubleshooting)

- **Layar Putih/Error Rute**: Jalankan `php artisan route:clear` dan `php artisan view:clear`.
- **Garis Kuning di VS Code**: Tekan `Ctrl + Shift + P` -> `Python: Select Interpreter` -> Pilih Python 3.13.
- **Apache XAMPP Error**: Jalankan XAMPP sebagai Administrator atau gunakan `php artisan serve`.
