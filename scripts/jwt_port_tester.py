import jwt
import datetime

# 🔐 KUNCI RAHASIA PRIVAT EKOSISTEM DIGESTEX (Kunci ini tidak boleh bocor)
SECRET_KEY = "Digestex_Sovereign_Port_Logistics_Token_Key_2026_AES"

def generate_port_access_token(pabrik_name, target_kategori):
    """
    Fungsi membuat token otentikasi digital enkripsi untuk pabrik rekanan Digestex
    """
    payload = {
        'iss': 'PT. Digestex Global Intelligence',
        'sub': pabrik_name,
        'category': target_kategori,
        # Mengunci masa aktif token berlaku selama 24 jam penuh
        'exp': datetime.datetime.utcnow() + datetime.timedelta(days=1),
        'iat': datetime.datetime.utcnow()
    }
    
    # Enkripsi data menggunakan algoritma standard keamanan internasional HS256
    encoded_token = jwt.encode(payload, SECRET_KEY, algorithm='HS256')
    return encoded_token

def verify_jict_gate_data(token, raw_incoming_json):
    """
    Fungsi memvalidasi sterilitas token siber sebelum meloloskan data kontainer masuk pelabuhan
    """
    try:
        # Dekripsi token untuk memeriksa masa kedaluwarsa dan hak akses
        decoded_payload = jwt.decode(token, SECRET_KEY, algorithms=['HS256'])
        print(f"✅ VERIFIKASI JWT SUKSES: Akses Diizinkan untuk Subjek: {decoded_payload['sub']}")
        
        # Simulasi pemrosesan data kontainer fisik dari gerbang JICT/NPCT1
        print("🚚 [DATA STREAM JICT DETECTED]:")
        print(f"   -> No Kontainer : {raw_incoming_json.get('container_no')}")
        print(f"   -> Muatan Sektor: {raw_incoming_json.get('hs_sector')}")
        print(f"   -> Status Gerbang: {raw_incoming_json.get('gate_status')}")
        return True
        
    except jwt.ExpiredSignatureError:
        print("🚨 EROR KEAMANAN: Token Digital Sudah Kedaluwarsa (Expired)!")
        return False
    except jwt.InvalidTokenError:
        print("🚨 EROR KEAMANAN: Token Palsu Terdeteksi / Kunci Enkripsi Salah!")
        return False

# ==========================================
# 🧪 EKSEKUSI SIMULASI SIMPUL DATA LOCALHOST
# ==========================================
if __name__ == "__main__":
    print("=== DIGESTEX INTEGRATION ENGINE LOCALHOST TESTER ===")
    
    # 1. Membuat Token Resmi Khusus Untuk PT. Coats Rejo Indonesia
    coats_token = generate_port_access_token("PT. Coats Rejo Indonesia", "Raw Material Anchor")
    print(f"\n🔑 TOKEN EMAS DIGITAL TERBIT:\n{coats_token}\n")
    
    # 2. Simulasi Kiriman Data Fisik Kontainer Masuk Pintu Gerbang Pelabuhan Tanjung Priok
    dummy_jict_feed = {
        "container_no": "TEMU-451298-0",
        "hs_sector": "HS 6203 - Pakaian Jadi Garmen Pcs",
        "gate_status": "GATE-IN FULL OUTFLOW PRIOK"
    }
    
    # 3. Validasi Keamanan & Tampilkan Data
    verify_jict_gate_data(coats_token, dummy_jict_feed)
