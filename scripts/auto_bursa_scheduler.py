import time
import subprocess
import os
import sys
from datetime import datetime

# Mengatur interval pengecekan (3600 detik = 1 jam)
CHECK_INTERVAL = 3600 

def run_bursa_sync():
    now = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    print(f"[{now}] 🔄 Memulai sinkronisasi otomatis bursa harian...")
    
    # Menentukan jalur absolut ke skrip cotton_price.py Anda
    script_path = os.path.join(os.path.dirname(__file__), 'cotton_price.py')
    
    try:
        # Jalankan skrip cotton_price.py menggunakan interpreter Python aktif
        result = subprocess.run([sys.executable, script_path], capture_output=True, text=True)
        
        if result.returncode == 0:
            print(f"[{now}] ✅ Output Sistem:\n{result.stdout.strip()}")
        else:
            print(f"[{now}] ❌ Gagal Eksekusi:\n{result.stderr.strip()}")
            
    except Exception as e:
        print(f"[{now}] ❌ Terjadi Error System: {str(e)}")

if __name__ == "__main__":
    print("🚀 Engine Auto-Bursa Digestex V2 Aktif di Background...")
    print(f"⏰ Sinkronisasi otomatis berjalan setiap {CHECK_INTERVAL / 60} menit.")
    print("------------------------------------------------------------")
    
    # Jalankan pertama kali saat komputer dinyalakan
    run_bursa_sync()
    
    # Loop abadi untuk background scheduler
    while True:
        time.sleep(CHECK_INTERVAL)
        run_bursa_sync()
