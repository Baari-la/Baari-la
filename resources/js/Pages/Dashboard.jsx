import { AlertCircle, Download } from "lucide-react";
import TopMarketChart from "@/Components/TopMarketChart";
import StockTicker from "@/Components/StockTicker";
import CottonCurrencyTrendChart from "@/Components/CottonCurrencyTrendChart";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, usePage } from "@inertiajs/react";
import html2canvas from "html2canvas";
import React from "react";

import {
    XAxis,
    YAxis,
    CartesianGrid,
    Tooltip,
    ResponsiveContainer,
    AreaChart,
    Area,
    BarChart,
    Bar,
} from "recharts";

const data = [
    { month: "Jan", price: 68.5 },
    { month: "Feb", price: 70.2 },
    { month: "Mar", price: 72.1 },
    { month: "Apr", price: 71.31 },
];

const labels = {
    performance_title: {
        id: "Tren Kinerja 5 Tahun",
        en: "5-Year Performance Trend",
    },
    comparison_title: {
        id: "Perbandingan Tahunan",
        en: "Year-on-Year Comparison",
    },
    surplus_msg: {
        id: "Industri dalam kondisi SURPLUS. Pertahankan efisiensi hulu.",
        en: "Industry is in SURPLUS. Maintain upstream efficiency.",
    },
    deficit_msg: {
        id: "Peringatan: Defisit pada sektor kain terdeteksi.",
        en: "Warning: Fabric sector deficit detected.",
    },
};

export default function Dashboard({
    marketHistory = [], // Memberikan proteksi array kosong bawaan agar aman dari crash
    topDestinations = {},
    cottonTrend,
    usd_idr,
    cottonPrice,
    exportValue,
    memberStatus,
}) {
    // AMBIL SEMUA DATA DARI usePage
    const { auth, locale } = usePage().props;
    const isEn = locale === "en" || auth?.user?.locale === "en";

    // 🕵️ MATRIKS EKSTRAKSI OTOMATIS BARIS TERAKHIR TABEL MARKET_HISTORIES (ID 46)
    const latestHistoryRecord =
        marketHistory && marketHistory.length > 0
            ? marketHistory[marketHistory.length - 1]
            : null;

    // Menentukan nilai live rate murni: Jika usd_idr bawaan kosong, ambil dari record database terbaru
    const liveExchangeRate =
        usd_idr && parseFloat(usd_idr) > 0
            ? parseFloat(usd_idr)
            : latestHistoryRecord && latestHistoryRecord.usd_idr
              ? parseFloat(latestHistoryRecord.usd_idr)
              : 17600;

    const liveCottonPrice =
        cottonPrice && parseFloat(cottonPrice) > 0
            ? cottonPrice
            : latestHistoryRecord && latestHistoryRecord.cotton_price
              ? latestHistoryRecord.cotton_price
              : "81.51";

    // FUNGSI EKSPOR CAPTURE GAMBAR HTML2CANVAS
    const exportAsImage = async (elementId, fileName) => {
        const element = document.getElementById(elementId);
        const canvas = await html2canvas(element, {
            backgroundColor: "#f9fafb",
        });
        const image = canvas.toDataURL("image/png", 1.0);
        const downloadLink = document.createElement("a");
        downloadLink.href = image;
        downloadLink.download = fileName;
        downloadLink.click();
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-black uppercase tracking-wider font-sans text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-cyan-400 to-indigo-400 drop-shadow-[0_2px_10px_rgba(96,165,250,0.3)]">
                    Industrial Intelligence Dashboard
                </h2>
            }
        >
            <Head title="Dashboard" />

            <div className="py-12 bg-gray-50">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    {/* --- SECTION: UPDATE DATA PERUSAHAAN --- */}
                    <div className="mb-10 bg-gradient-to-r from-[#0f172a] to-[#1e293b] p-8 rounded-[40px] border border-white/10 shadow-2xl flex flex-col md:flex-row justify-between items-center gap-6">
                        <div className="flex items-center gap-6">
                            <div className="bg-yellow-500/20 w-16 h-16 rounded-3xl flex items-center justify-center border border-yellow-500/30">
                                <i className="fas fa-building text-yellow-500 text-2xl"></i>
                            </div>
                            <div>
                                <h2 className="text-xl font-black text-white uppercase italic tracking-tighter">
                                    {isEn
                                        ? "Corporate Data Integrity"
                                        : "Integritas Data Perusahaan"}
                                </h2>
                                <p className="text-gray-400 text-xs mt-1 max-w-md">
                                    {isEn
                                        ? "Keep your industrial profile updated to ensure Big Data accuracy for the national textile ecosystem."
                                        : "Pastikan profil industri Anda mutakhir untuk akurasi Big Data ekosistem pertekstilan nasional."}
                                </p>
                            </div>
                        </div>
                        {auth.user.company_id && (
                            <Link
                                href={route(
                                    "companies.edit",
                                    auth.user.company_id,
                                )}
                                className="group bg-yellow-500 text-[#0a192f] px-10 py-4 rounded-full font-black text-[11px] uppercase tracking-widest hover:bg-yellow-400 transition-all shadow-xl shadow-yellow-500/10 flex items-center gap-3 whitespace-nowrap"
                            >
                                <i className="fas fa-sync-alt group-hover:rotate-180 transition-transform duration-500"></i>
                                {isEn
                                    ? "Update Corporate Profile"
                                    : "Update Profil Perusahaan"}
                            </Link>
                        )}

                        {/* OPSIONAL: TAMPILKAN PESAN JIKA TIDAK ADA COMPANY ID */}
                        {!auth.user.company_id && (
                            <p className="text-amber-500 text-[10px] font-bold italic">
                                *Data profil belum terhubung. Hubungi Admin API.
                            </p>
                        )}
                    </div>

                    {/* --- WELCOME BANNER --- */}
                    <div className="bg-gradient-to-r from-blue-700 to-indigo-900 rounded-3xl p-8 mb-8 text-white shadow-2xl relative overflow-hidden">
                        <div className="relative z-10">
                            <h2 className="text-3xl font-black italic tracking-tighter mb-2">
                                WELCOME BACK, {auth.user.name.toUpperCase()}!
                            </h2>
                            <p className="text-blue-100 opacity-80 font-medium">
                                Intelligence system is active. Your encrypted
                                data stream is ready.
                            </p>
                        </div>
                        <div className="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-20 -mt-20 blur-3xl"></div>
                    </div>

                    <div className="mb-8">
                        <StockTicker topStocks={[]} />
                    </div>

                    {/* --- STATS CARDS GRID --- */}
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        {/* 1. COTTON PRICE */}
                        <div className="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-yellow-500 text-gray-900 group hover:shadow-md transition-all">
                            <div className="flex justify-between items-start">
                                <p className="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                    Cotton Price
                                </p>
                                <span
                                    className={`text-[10px] font-bold px-2 py-0.5 rounded-full ${parseFloat(cottonTrend) >= 0 ? "bg-green-100 text-green-700" : "bg-red-100 text-red-700"}`}
                                >
                                    {cottonTrend}
                                </span>
                            </div>
                            <h3 className="text-2xl font-black mt-1">
                                {cottonPrice}{" "}
                                <span className="text-sm font-normal text-gray-400">
                                    USD/lb
                                </span>
                            </h3>
                        </div>

                        {/* 2. EXCHANGE RATE (DATA LIVE DARIPADA PYTHON) */}
                        <div className="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-emerald-500 text-gray-900 group hover:shadow-md transition-all">
                            <p className="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                Kurs USD / IDR
                            </p>
                            <h3 className="text-2xl font-black mt-1">
                                Rp {parseFloat(usd_idr).toLocaleString("id-ID")}
                            </h3>
                            <p className="text-[9px] text-gray-400 font-bold italic mt-1 uppercase">
                                Bursa Live Data
                            </p>
                        </div>

                        {/* 3. NATIONAL EXPORT */}
                        <div className="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-blue-600 text-gray-900 group hover:shadow-md transition-all">
                            <p className="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                National Export
                            </p>
                            <h3 className="text-2xl font-black mt-1">
                                ${exportValue}{" "}
                                <span className="text-sm font-normal text-gray-400">
                                    B
                                </span>
                            </h3>
                        </div>

                        {/* 4. USER STATUS */}
                        <div className="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-indigo-500 text-gray-900 group hover:shadow-md transition-all">
                            <p className="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                Account Status
                            </p>
                            <div className="flex items-center gap-2 mt-1">
                                <h3
                                    className={`text-lg font-black uppercase italic ${memberStatus.includes("Premium") ? "text-indigo-600" : "text-gray-600"}`}
                                >
                                    {memberStatus}
                                </h3>
                                {memberStatus.includes("Premium") && (
                                    <span className="animate-pulse text-yellow-500">
                                        ⭐
                                    </span>
                                )}
                            </div>
                        </div>
                    </div>

                    {/* --- LINK CEPAT DEEP INTELLIGENCE --- */}
                    <Link
                        href={route("intelligence.center")}
                        className="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 mb-8"
                    >
                        <span className="mr-2">🚀</span> Buka Deep Intelligence
                        Center
                    </Link>

                    {/* --- BILINGUAL WARNING INDUSTRIAL INSIGHT --- */}
                    <div className="bg-amber-50 border-l-4 border-amber-400 p-4 my-6 rounded-r-xl">
                        <div className="flex items-center">
                            <AlertCircle
                                className="text-amber-600 mr-3"
                                size={24}
                            />
                            <div>
                                <h4 className="text-sm font-bold text-amber-800 uppercase tracking-wider">
                                    Industrial Insight /{" "}
                                    <span className="italic">
                                        Wawasan Industri
                                    </span>
                                </h4>
                                <p className="text-sm text-amber-700">
                                    Export value to USA remains dominant, but
                                    keep an eye on China's premium growth.
                                </p>
                                <p className="text-xs text-amber-600 italic mt-1">
                                    Nilai ekspor ke AS tetap dominan, namun
                                    perhatikan pertumbuhan premium di Cina.
                                </p>
                            </div>
                        </div>
                    </div>

                    {/* --- AREA YANG AKAN DI-CAPTURE (id="capture-area") --- */}
                    <div id="capture-area" className="p-4 rounded-3xl">
                        {/* 🕵️ SUNTIKAN LOGIKA PEMBERSIH DATA: KONVERSI TEKS DB MENJADI ANGKA MURNI RECHARTS */}
                        {(() => {
                            const cleanedMarketHistory =
                                marketHistory && marketHistory.length > 0
                                    ? marketHistory.map((item) => ({
                                          ...item,
                                          // Memaksa nilai teks DB dari Python menjadi angka matematika murni
                                          cotton_price: item.cotton_price
                                              ? parseFloat(item.cotton_price)
                                              : 0,
                                          usd_idr: item.usd_idr
                                              ? parseFloat(item.usd_idr)
                                              : 0,
                                          // Format penamaan tanggal agar grafik sumbu X terlihat rapi
                                          date: item.date
                                              ? item.date
                                                    .split("-")
                                                    .slice(1)
                                                    .reverse()
                                                    .join("/")
                                              : "",
                                      }))
                                    : [];

                            return (
                                <div className="bg-white rounded-[40px] shadow-sm overflow-hidden border border-gray-100 mb-8">
                                    {/* PILOT DATA SEKARANG DIALIRKAN VIA cleanedMarketHistory */}
                                    <CottonCurrencyTrendChart
                                        data={cleanedMarketHistory}
                                        usd_idr={usd_idr}
                                        cottonPrice={cottonPrice}
                                        isEn={isEn}
                                    />
                                </div>
                            );
                        })()}
                        {/* GRAFIK TUJUAN EKSPOR MACO WIDE SCREEN */}
                        <div className="mx-auto max-w-none px-4 sm:px-6 lg:px-10">
                            <TopMarketChart
                                data={topDestinations}
                                isEn={isEn}
                            />
                        </div>
                    </div>

                    {/* --- TOMBOL AKSES BURSA INDEPENDEN --- */}
                    <div className="mb-10 mt-8">
                        <Link
                            href={route("inventory.create")}
                            className="flex items-center justify-between p-8 bg-yellow-500 rounded-[35px] group hover:scale-[1.02] transition-all shadow-2xl shadow-yellow-500/20"
                        >
                            <div className="flex items-center gap-6">
                                <div className="bg-[#0a192f] text-white p-4 rounded-2xl shadow-lg">
                                    <i className="fas fa-plus text-xl"></i>
                                </div>
                                <div>
                                    <h4 className="text-[#0a192f] font-black text-xl uppercase leading-tight">
                                        Post to Bursa Bahan
                                    </h4>
                                    <p className="text-[#0a192f]/60 text-xs font-bold uppercase tracking-widest mt-1">
                                        Upload sisa produksi / inventori Anda
                                    </p>
                                </div>
                            </div>
                            <div className="hidden md:block text-[#0a192f]/40 group-hover:translate-x-2 transition-transform">
                                <i className="fas fa-chevron-right text-2xl"></i>
                            </div>
                        </Link>
                    </div>

                    {/* --- TOMBOL AKSI CAPTURE MEDIA LOKAL --- */}
                    <div className="mt-8 flex justify-end">
                        <button
                            onClick={() =>
                                exportAsImage(
                                    "capture-area",
                                    "Digestex-Market-Intelligence",
                                )
                            }
                            className="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-full font-bold shadow-xl transition-all flex items-center gap-2 cursor-pointer"
                        >
                            <i className="fab fa-whatsapp text-lg"></i> CAPTURE
                            & SHARE TO WHATSAPP
                        </button>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
