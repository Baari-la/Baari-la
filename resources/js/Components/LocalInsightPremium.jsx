import { motion } from "framer-motion";
import { Link } from "@inertiajs/react";

{
    /* RESOURCES/JS/COMPONENTS/LOCALINSIGHTPREMIUM.JSX */
}
export default function LocalInsightPremium({ isEn, isPremium, sectorData }) {
    return (
        <div className="mt-12 space-y-8 animate-in fade-in duration-1000">
            {/* HEADER DENGAN ICON RADAR */}
            <div className="flex items-center gap-4">
                <div className="p-3 bg-emerald-500/10 rounded-2xl border border-emerald-500/20">
                    <i className="fas fa-satellite-dish text-emerald-500 animate-pulse"></i>
                </div>
                <div>
                    <h4 className="text-white text-xs font-black uppercase italic tracking-widest">
                        Domestic Industry Intelligence
                    </h4>
                    <p className="text-gray-500 text-[9px] font-bold uppercase tracking-widest">
                        National Supply Chain & Market Analysis
                    </p>
                </div>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                {/* 1. PELUANG SUBSTITUSI IMPOR (DATA DRIVEN) */}
                <div className="bg-[#050c1b] border border-blue-500/20 p-8 rounded-[40px] relative overflow-hidden group">
                    <h5 className="text-blue-400 text-[9px] font-black uppercase mb-4 tracking-[0.2em]">
                        Import Substitution
                    </h5>
                    <p className="text-white text-sm font-bold italic mb-4 leading-tight">
                        {isEn
                            ? "Supply Gap Detected in HS-5208 (Cotton Fabrics)"
                            : "Celah Pasokan Terdeteksi: HS-5208 (Kain Katun)"}
                    </p>
                    <p className="text-gray-500 text-[10px] leading-relaxed mb-6 italic">
                        {isEn
                            ? "Current domestic demand is met by 40% imports. High opportunity for local mills to capture this segment."
                            : "40% kebutuhan domestik saat ini dipenuhi impor. Peluang besar bagi pabrik lokal untuk mengisi segmen ini."}
                    </p>
                    <div className="flex items-center gap-2 text-blue-500 text-[8px] font-black uppercase group-hover:gap-4 transition-all">
                        {isEn
                            ? "View Detailed HS-Code Analysis"
                            : "Lihat Detail Analisis HS-Code"}{" "}
                        <i className="fas fa-arrow-right"></i>
                    </div>
                </div>

                {/* 2. RANTAI PASOK NASIONAL (B2B MATCHMAKING) */}
                <div className="bg-[#050c1b] border border-emerald-500/20 p-8 rounded-[40px] relative overflow-hidden">
                    <h5 className="text-emerald-500 text-[9px] font-black uppercase mb-4 tracking-[0.2em]">
                        Supply Chain Radar
                    </h5>
                    <p className="text-white text-sm font-bold italic mb-4 leading-tight">
                        {isEn
                            ? "Verified Local Yarn Suppliers Active"
                            : "Pemasok Benang Lokal Terverifikasi"}
                    </p>
                    <p className="text-gray-500 text-[10px] leading-relaxed mb-6 italic">
                        {isEn
                            ? "15+ spinning mills in West Java are now verified for high-tenacity yarn production."
                            : "15+ pabrik pemintalan di Jawa Barat telah terverifikasi untuk produksi benang berkekuatan tinggi."}
                    </p>
                    <div className="flex items-center gap-2 text-emerald-500 text-[8px] font-black uppercase">
                        <i className="fas fa-map-marker-alt"></i>{" "}
                        {isEn
                            ? "Find Nearby Partners"
                            : "Temukan Mitra Terdekat"}
                    </div>
                </div>

                {/* 3. TREN PERMINTAAN LOKAL (PREMIUM ONLY) */}
                <div className="bg-white/5 border border-white/10 p-8 rounded-[40px] relative overflow-hidden group">
                    {!isPremium && (
                        <div className="absolute inset-0 bg-[#0a192f]/60 backdrop-blur-md z-20 flex flex-col items-center justify-center p-6 text-center">
                            <i className="fas fa-lock text-yellow-500 mb-3"></i>
                            <span className="text-[8px] font-black text-white uppercase tracking-widest">
                                Premium Insights Locked
                            </span>
                        </div>
                    )}
                    <h5 className="text-gray-500 text-[9px] font-black uppercase mb-4 tracking-[0.2em]">
                        Market Sentiment
                    </h5>
                    <p className="text-white text-sm font-bold italic mb-4 leading-tight">
                        {isEn
                            ? "Demand Spike: Sustainable Fibers"
                            : "Lonjakan Permintaan: Serat Berkelanjutan"}
                    </p>
                    <p className="text-gray-500 text-[10px] leading-relaxed italic">
                        Tren lokal bergeser ke arah produk ramah lingkungan.
                        Produsen garmen domestik mulai mencari kain
                        bersertifikasi hijau.
                    </p>
                </div>
            </div>
            {/* Fitur Harga */}

            {/* PRICE INTELLIGENCE RADAR (PREMIUM VALUE) */}
            <div className="mt-8 bg-gradient-to-br from-[#0d1d36] to-transparent border border-white/5 rounded-[40px] p-8 relative overflow-hidden group">
                <div className="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
                    <div className="flex items-center gap-6">
                        <div className="h-14 w-14 bg-yellow-500/10 rounded-2xl flex items-center justify-center border border-yellow-500/20">
                            <i className="fas fa-search-dollar text-yellow-500 text-xl"></i>
                        </div>
                        <div>
                            <h4 className="text-white text-xs font-black uppercase italic tracking-widest mb-1">
                                Raw Material Price Intelligence
                            </h4>
                            <p className="text-gray-500 text-[9px] font-bold uppercase italic leading-tight max-w-sm">
                                {isEn
                                    ? "Real-time comparison: Domestic vs Imported raw material benchmarks."
                                    : "Perbandingan real-time: Benchmark harga bahan baku Domestik vs Impor."}
                            </p>
                        </div>
                    </div>

                    {/* DATA PRICE COMPARISON (LOCKED FOR NON-PREMIUM) */}
                    <div className="flex gap-6 relative">
                        {!isPremium && (
                            <div className="absolute inset-0 bg-[#0d1d36]/80 backdrop-blur-sm z-20 flex items-center justify-center rounded-2xl border border-white/5">
                                <span className="text-[7px] font-black text-yellow-500 uppercase tracking-[0.3em]">
                                    Unlock Price Radar
                                </span>
                            </div>
                        )}
                        <div className="text-center">
                            <p className="text-[7px] text-gray-600 font-black uppercase mb-1">
                                Local Cotton
                            </p>
                            <p className="text-emerald-400 text-xs font-black italic">
                                Rp 42.500
                                <span className="text-[8px] ml-1">/Kg</span>
                            </p>
                        </div>
                        <div className="h-8 w-px bg-white/5"></div>
                        <div className="text-center">
                            <p className="text-[7px] text-gray-600 font-black uppercase mb-1">
                                Imported (Avg)
                            </p>
                            <p className="text-red-400 text-xs font-black italic">
                                Rp 48.200
                                <span className="text-[8px] ml-1">/Kg</span>
                            </p>
                        </div>
                    </div>
                </div>

                {/* TOOLTIP DINAMIS */}
                <div className="mt-6 pt-6 border-t border-white/5">
                    <p className="text-[9px] text-gray-400 font-medium italic leading-relaxed">
                        <i className="fas fa-lightbulb text-yellow-500 mr-2"></i>
                        {isEn
                            ? "Current trend: Local raw materials are 12% more cost-effective this week due to import logistics surges."
                            : "Tren minggu ini: Bahan baku lokal 12% lebih hemat biaya karena lonjakan biaya logistik impor."}
                    </p>
                </div>
            </div>
            {/* SUPPLIER DIRECT ACCESS (THE DIGITAL STOREFRONT) */}
            <div className="mt-6 flex flex-wrap gap-4">
                {/* TOMBOL KONTAK PEMASOK LOKAL */}
                <div className="flex-1 min-w-[280px] bg-white/5 border border-white/10 p-6 rounded-[35px] flex items-center justify-between group hover:border-emerald-500/30 transition-all">
                    <div className="flex items-center gap-4">
                        <div className="h-10 w-10 bg-emerald-500/20 rounded-xl flex items-center justify-center text-emerald-500">
                            <i className="fab fa-whatsapp text-lg"></i>
                        </div>
                        <div>
                            <p className="text-[7px] text-gray-500 font-black uppercase tracking-widest mb-0.5">
                                Verified Supplier
                            </p>
                            <h5 className="text-white text-[11px] font-black uppercase italic">
                                Pabrik Benang Jabar
                            </h5>
                        </div>
                    </div>

                    {/* ACTION BUTTON */}
                    <a
                        href={`https://wa.me, saya tertarik dengan stok benang lokal dari Radar Digestex.`}
                        target="_blank"
                        className="bg-emerald-600 hover:bg-emerald-500 text-white px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all shadow-lg shadow-emerald-600/20"
                    >
                        Order Now
                    </a>
                </div>

                {/* PROMO SPACE UNTUK SUBSCRIPTION */}
                {!isPremium && (
                    <div className="flex-1 min-w-[280px] bg-gradient-to-r from-blue-600/20 to-transparent border border-blue-500/20 p-6 rounded-[35px] flex items-center justify-between">
                        <p className="text-gray-300 text-[10px] font-bold italic leading-tight max-w-[180px]">
                            {isEn
                                ? "Get 50+ verified local supplier contacts."
                                : "Akses 50+ kontak pemasok lokal terverifikasi."}
                        </p>
                        <Link
                            href="#"
                            className="text-blue-400 text-[9px] font-black uppercase tracking-widest border-b border-blue-400 pb-0.5"
                        >
                            Unlock All
                        </Link>
                    </div>
                )}
            </div>
        </div>
    );
}
