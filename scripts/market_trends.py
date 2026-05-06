import yfinance as yf
import mysql.connector
from datetime import datetime

def sync_trends():
    try:
        print("🔄 Menarik riwayat bursa 1 bulan...")
        cotton = yf.Ticker("CT=F").history(period="1mo")
        forex = yf.Ticker("IDR=X").history(period="1mo")

        db = mysql.connector.connect(
            host="127.0.0.1", user="root", password="", database="digestex_v2"
        )
        cursor = db.cursor()

        for index, row in cotton.iterrows():
            date_str = index.strftime('%Y-%m-%d')
            c_price = round(row['Close'], 2)
            try:
                f_price = round(forex.loc[index]['Close'], 2)
            except:
                f_price = 0

            sql = """INSERT INTO market_histories (date, cotton_price, usd_idr, created_at, updated_at) 
                     VALUES (%s, %s, %s, %s, %s)
                     ON DUPLICATE KEY UPDATE cotton_price = VALUES(cotton_price), usd_idr = VALUES(usd_idr)"""
            cursor.execute(sql, (date_str, c_price, f_price, datetime.now(), datetime.now()))

        db.commit()
        db.close()
        print("✅ Riwayat 30 hari berhasil disinkronkan ke Database.")
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    sync_trends()
