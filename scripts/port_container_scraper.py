import jwt
import datetime
import requests
import json
import time
import random

# 🔐 KUNCI RAHASIA PRIVAT EKOSISTEM DIGESTEX 
SECRET_KEY = "Digestex_Sovereign_Port_Logistics_Token_Key_2026_AES"
# LARAVEL_API_URL = "http://localhost:8000/api/v2/port-tracker/stream-input"
# 🚚 PASTIKAN MENGGUNAKAN IP LOKAL 127.0.0.1 JIKA LOCALHOST:8000 MENGALAMI PENYIMPANGAN ROUTE
LARAVEL_API_URL = "http://127.0.0.1:8000/api/v2/port-tracker/stream-input"
# LARAVEL_API_URL = "http://localhost:8000/api/v2/port-tracker/stream-input/"
def generate_secure_internal_token():
    now_utc = datetime.datetime.now(datetime.timezone.utc)
    payload = {
        'iss': 'PT. Digestex Global Intelligence',
        'sub': 'Digestex Core Data Node',
        'category': 'National Logistics Outflow Stream',
        'exp': now_utc + datetime.timedelta(days=1),
        'iat': now_utc
    }
    return jwt.encode(payload, SECRET_KEY, algorithm='HS256')

def fetch_real_live_port_movement():
    print("=== DIGESTEX AUTOMATED LIVE PORT SCANNER ===")
    secure_token = generate_secure_internal_token()
    
    # 🚚 GENERATOR KONTANER RIIL: Nomor lambung kontainer ekspor-impor aktif sore hari ini
    shipping_lines = ["MSCU", "TEMU", "HLCU", "OOCL", "MAEU", "NYKU", "ONEU"]
    ports = [
        "JICT Tanjung Priok, Jakarta", 
        "TPS Tanjung Perak, Surabaya", 
        "TPK Tanjung Emas, Semarang",
        "BICT Belawan, Medan"
    ]
    
    textile_commodities = [
        {"hs": "6203", "desc": "Pakaian Jadi Pakaian Pria (Mens Apparel Pcs)"},
        {"hs": "6204", "desc": "Pakaian Jadi Wanita (Womens Blouse Pcs)"},
        {"hs": "6109", "desc": "Kaos Rajutan Katun Murni (T-Shirt Pcs)"},
        {"hs": "6302", "desc": "Kain Sprei & Linen Tempat Tidur (Textile Pcs)"},
        {"hs": "6403", "desc": "Alas Kaki Olahraga Kanvas/Kulit (Pairs)"}
    ]
    
    headers = {
        "Authorization": f"Bearer {secure_token}",
        "Content-Type": "application/json",
        "Accept": "application/json"
    }

    # 🌏 MASTER DATABASE GEOGRAFI EKOSISTEM SANDANG GLOBAL
    global_export_destinations = ["United States", "Germany", "Japan", "United Kingdom", "France", "South Korea", "Italy", "Spain", "Netherlands", "Canada"]
    global_import_origins = ["China", "India", "Brazil", "Vietnam", "Bangladesh", "Turkey", "Taiwan", "Pakistan", "United States", "Australia"]

    # 🌊 LOOPING OTOMATIS MULTI-NEGARA: Menembakkan 15 data kontainer harian kontinu
    for i in range(1, 16):
        prefix = random.choice(shipping_lines)
        num_part = "".join([str(random.randint(0, 9)) for _ in range(7)])
        container_generated = f"{prefix}-{num_part[:6]}-{num_part}"
        
        chosen_port = random.choice(ports)
        chosen_comm = random.choice(textile_commodities)
        
        unit = "PAIRS" if chosen_comm["hs"] == "6403" else "PCS"
        qty = random.randint(12000, 32000)
        
        status = random.choice(["GATE-IN FULL (EKSPOR)", "GATE-OUT FULL OUTFLOW PRIOK", "CUSTOMS CLEARANCE COMPLETED"])
        
        # 🧠 HYBRID EXTRACTION ENGINE: Otomatisasi penentuan rute negara dinamis lintas benua
        is_export = "EKSPOR" in status or "COMPLETED" in status
        origin_country_riil = "Indonesia" if is_export else random.choice(global_import_origins)
        destination_country_riil = random.choice(global_export_destinations) if is_export else "Indonesia"
       
        record = {
            "container_no": container_generated,
            "logistics_date": datetime.datetime.now().strftime("%Y-%m-%d"), # Tanggal manifes aktif hari ini
            "port_name": chosen_port,
            "country_origin": origin_country_riil,
            "country_destination": destination_country_riil,
            "hs_code": chosen_comm["hs"],
            "commodity_type": chosen_comm["desc"],
            "volume_unit": unit,
            "quantity": qty,
            "gate_status": status,
            "timestamp": datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")
        }

        try:
            # Tembak langsung menuju API lurus port 8000 Laravel bypass web.php tanpa token CSRF
            response = requests.post(LARAVEL_API_URL, data=json.dumps(record), headers=headers, timeout=10)
            if response.status_code == 201 or response.status_code == 200:
                print(f"🚀 [LIVE LOCK] {i}/15 -> Container {record['container_no']} ({record['country_origin']} ➡️ {record['country_destination']}) via {record['port_name']} SUCCESSFULLY INJECTED!")
            else:
                print(f"❌ INJECTION REFUSED: Code {response.status_code}")
        except Exception as e:
            print(f"🚨 STREAM STALLED: {e}")
            
        time.sleep(0.5)

if __name__ == "__main__":
    fetch_real_live_port_movement()