import yfinance as yf
import json
import os
import mysql.connector
from datetime import datetime

def update_market_data():
    try:
        print("🔄 Mengambil data bursa harian...")
        
        # 1. AMBIL DATA (Gunakan period 5d untuk memastikan index -2 selalu ada)
        cotton_ticker = yf.Ticker("CT=F")
        forex_ticker = yf.Ticker("IDR=X")
        
        cotton_hist = cotton_ticker.history(period="5d")
        forex_hist = forex_ticker.history(period="5d")
        
        if cotton_hist.empty or forex_hist.empty:
            print("⚠️ Data bursa tidak ditemukan hari ini.")
            return

        # Ambil harga penutupan terbaru
        c_price = round(float(cotton_hist['Close'].iloc[-1]), 2)
        c_prev = float(cotton_hist['Close'].iloc[-2])
        c_change = round(((c_price - c_prev) / c_prev) * 100, 2)

        f_price = round(float(forex_hist['Close'].iloc[-1]), 2)
        f_prev = float(forex_hist['Close'].iloc[-2])
        f_change = round(f_price - f_prev, 2)

        # 2. SIMPAN KE DATABASE (digestex_v2)
        db = mysql.connector.connect(
            host="127.0.0.1", user="root", password="", database="digestex_v2"
        )
        cursor = db.cursor()
        current_date = datetime.now().strftime('%Y-%m-%d')
        
        sql = """INSERT INTO market_histories (date, cotton_price, usd_idr, created_at, updated_at) 
                 VALUES (%s, %s, %s, %s, %s)
                 ON DUPLICATE KEY UPDATE 
                 cotton_price = VALUES(cotton_price), usd_idr = VALUES(usd_idr), updated_at = VALUES(updated_at)"""
        
        cursor.execute(sql, (current_date, c_price, f_price, datetime.now(), datetime.now()))
        db.commit()
        db.close()

        # 3. SIMPAN KE JSON (Untuk Ticker React)
        result = {
            "cotton": {"price": c_price, "percent": c_change, "status": "up" if c_change >= 0 else "down"},
            "kurs": {"price": "{:,.2f}".format(f_price), "change": f_change, "status": "up" if f_change >= 0 else "down"}
        }
        
        output_path = "C:/XAMPP/htdocs/digestex_v2/public/data/market_live.json"
        os.makedirs(os.path.dirname(output_path), exist_ok=True)
        with open(output_path, "w") as f:
            json.dump(result, f, indent=4)
            
        print(f"✅ Berhasil! Kapas: ${c_price} | Kurs: Rp {f_price}")
        
    except Exception as e:
        print(f"❌ Error Detail: {e}")

if __name__ == "__main__":
    update_market_data()
