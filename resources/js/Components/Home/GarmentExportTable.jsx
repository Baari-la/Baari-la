import React, { useState, useEffect } from "react";
import * as XLSX from "xlsx";
import { Link, usePage } from "@inertiajs/react";

export default function GarmentExportTable({
    topProducts = [],
    totalGarment = { kg_2025: 0 },
    garmentTrade = { export_pcs: 0, import_pcs: 0 },
    isEn,
    auth,
}) {
    const { translations } = usePage().props;
    const t = (key) => translations[key] || key;
    const THREAD_PER_PCS_KM = 0.15;

    const isPremium =
        auth?.user?.role === "admin" || auth?.user?.role === "premium";

    // 🌟 KALKULASI TOTAL DI TEMPAT YANG BENAR (Scope Utama Komponen)
    const totalValueUSD = topProducts.reduce((sum, item) => sum + Number(item.val_2025 || 0), 0);
    const totalVolumePcs = topProducts.reduce((sum, item) => sum + Number(item.vol_2025 || 0), 0);

    // --- LOGIKA PAGINATION ---
    const [currentPage, setCurrentPage] = useState(1);
    const itemsPerPage = 15;
    const totalPages = Math.ceil(topProducts.length / itemsPerPage) || 1;

    useEffect(() => {
        setCurrentPage(1);
    }, [topProducts.length]);

    const indexOfLastItem = currentPage * itemsPerPage;
    const indexOfFirstItem = indexOfLastItem - itemsPerPage;
    const currentItems = topProducts.slice(indexOfFirstItem, indexOfLastItem);

    const paginate = (pageNumber) => {
        if (pageNumber >= 1 && pageNumber <= totalPages) {
            setCurrentPage(pageNumber);
            document
                .getElementById("garment-table-top")
                ?.scrollIntoView({ behavior: "smooth" });
        }
    };

    const exportToExcel = () => {
        const excelData = topProducts.map((item) => ({
            "HS Code 8-Digit": item.hs_code_clean,
            Description: item.uraian_hs,
            "Volume 2025 (Pcs)": Number(item.vol_2025),
            "Value 2025 (USD)": Number(item.val_2025),
            "Growth (%)": Number(item.growth || 0).toFixed(2) + "%",
        }));

        const worksheet = XLSX.utils.json_to_sheet(excelData);
        const workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, "Top 15 Export Garments");
        XLSX.writeFile(workbook, `Digestex_V2_Top_15_Garment_Export_2025.xlsx`);
    };

    return (
        <div id="garment-table-top" className="space-y-10">
            {/* --- SECTION 1: TRADE BALANCE (PIECES VIEW) --- */}
            <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div className="bg-gradient-to-br from-emerald-600 to-emerald-900 p-8 rounded-[35px] shadow-xl relative overflow-hidden group">
                    <p className="text-emerald-200 text-[9px] font-black uppercase tracking-widest mb-2">
                        {t("Total Export Units")}
                    </p>
                    <h4 className="text-white text-3xl font-black italic tracking-tighter">
                        {Number(Math.round(garmentTrade.export_pcs || 0)).toLocaleString()}{" "}
                        <span className="text-xs opacity-60 not-italic uppercase font-bold">Pcs</span>
                    </h4>
                    <div className="absolute -right-4 -bottom-4 opacity-10 text-6xl italic font-black text-white group-hover:scale-110 transition-transform">
                        OUT
                    </div>
                </div>

                <div className="bg-white/5 border border-white/10 p-8 rounded-[35px] flex flex-col items-center justify-center text-center backdrop-blur-md">
                    <p className="text-yellow-500 text-[10px] font-black uppercase tracking-widest mb-1">
                        Net Trade Surplus
                    </p>
                    <h4 className="text-white text-2xl font-black tracking-tighter">
                        {Number(Math.round((garmentTrade.export_pcs || 0) - (garmentTrade.import_pcs || 0))).toLocaleString()}{" "}
                        <span className="text-xs font-normal">Pcs</span>
                    </h4>
                    <p className="text-slate-500 text-[8px] font-bold uppercase mt-2 italic">
                        Productivity Gap (Global vs Local)
                    </p>
                </div>

                <div className="bg-gradient-to-br from-red-600 to-red-900 p-8 rounded-[35px] shadow-xl relative overflow-hidden group">
                    <p className="text-red-200 text-[9px] font-black uppercase tracking-widest mb-2">
                        Total Import Units
                    </p>
                    <h4 className="text-white text-3xl font-black italic tracking-tighter">
                        {Number(Math.round(garmentTrade.import_pcs || 0)).toLocaleString()}{" "}
                        <span className="text-xs opacity-60 not-italic uppercase font-bold">Pcs</span>
                    </h4>
                    <div className="absolute -right-4 -bottom-4 opacity-10 text-6xl italic font-black text-white group-hover:scale-110 transition-transform">
                        IN
                    </div>
                </div>

                <div className="bg-gradient-to-br from-blue-900 to-indigo-950 p-6 rounded-[35px] border border-blue-500/30 shadow-2xl relative overflow-hidden group">
                    <div className="absolute top-0 right-0 p-4 opacity-20">
                        <i className="fas fa-spool text-4xl text-blue-400 group-hover:rotate-45 transition-transform"></i>
                    </div>
                    <p className="text-blue-300 text-[9px] font-black uppercase tracking-widest mb-2">
                        {t("Potential Thread Demand")}
                    </p>
                    <h4 className="text-white text-2xl font-black italic tracking-tighter leading-none">
                        {Number(Math.round(garmentTrade.export_pcs * THREAD_PER_PCS_KM)).toLocaleString()}
                        <span className="text-xs opacity-60 not-italic ml-1">{t("Kilometers")}</span>
                    </h4>
                    <p className="text-[8px] text-blue-400/60 mt-3 font-bold uppercase tracking-tighter">
                        {t("Based on exported garment types")}
                    </p>
                </div>
            </div>

            {/* --- SECTION 2: TOP 15 TABLE --- */}
            <div className="bg-white/10 border border-white/20 rounded-[40px] overflow-hidden backdrop-blur-2xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] relative">
                <div className="p-8 border-b border-white/20 bg-gradient-to-r from-blue-600/20 to-transparent flex flex-col md:flex-row justify-between items-center gap-6">
                    <div className="flex items-center gap-4">
                        <div className="w-2 h-12 bg-yellow-500 rounded-full shadow-[0_0_15px_rgba(234,179,8,0.5)]"></div>
                        <div>
                            <h3 className="text-white text-2xl font-black uppercase italic tracking-tighter leading-none">
    Top 15 <span className="text-yellow-500">{isEn ? "Exported Garment Products" : "Produk Garmen yang Diekspor"}</span>
</h3>
                            <div className="flex items-center gap-2 mt-1 px-1">
                                <span className="text-[9px] font-black text-yellow-400 uppercase tracking-[0.3em] drop-shadow-md">
                                    {t("Reuters Standard")}
                                </span>
                                <div className="w-1 h-1 bg-white/40 rounded-full"></div>
                                <span className="text-[9px] font-black text-emerald-400 uppercase tracking-[0.3em]">
                                    Live Intelligence
                                </span>
                            </div>
                        </div>
                    </div>

                    <button
                        onClick={exportToExcel}
                        className="flex items-center gap-3 bg-emerald-600 hover:bg-emerald-500 text-white px-8 py-3 rounded-2xl font-black uppercase text-[10px] tracking-widest transition-all shadow-lg active:scale-95 whitespace-nowrap"
                    >
                        <i className="fas fa-file-excel text-lg"></i>
                        {isEn ? "Export to Excel" : "Unduh Data Excel"}
                    </button>
                </div>

                <div className="overflow-x-auto relative">
                    <table className="w-full text-left">
                        <thead className="bg-white/10">
                            <tr>
                                <th className="px-8 py-5 text-[11px] font-black uppercase text-white tracking-widest border-b border-white/10">HS Code</th>
                                <th className="px-6 py-5 text-[11px] font-black uppercase text-white tracking-widest border-b border-white/10 italic">Description</th>
                                <th className="px-6 py-5 text-[11px] font-black uppercase text-yellow-500 tracking-widest border-b border-white/10 text-right">Vol 2025 (Pcs)</th>
                                <th className="px-6 py-5 text-[11px] font-black uppercase text-emerald-400 tracking-widest border-b border-white/10 text-right">Value 2025 (USD)</th>
                                <th className="px-6 py-5 text-[11px] font-black uppercase text-white tracking-widest border-b border-white/10 text-right">Trend</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-white/10">
                            {currentItems.map((item, idx) => {
                                const globalIndex = (currentPage - 1) * itemsPerPage + idx;
                                const isLocked = !isPremium && globalIndex >= 5;

                                return (
                                    <tr
                                        key={idx}
                                        className={`transition-all ${isLocked ? "blur-md opacity-20 pointer-events-none" : "hover:bg-white/10"}`}
                                    >
                                        <td className="px-8 py-4 text-yellow-500 font-black text-sm tracking-tighter">
                                            {item.hs_code_clean}
                                        </td>
                                        <td className="px-6 py-4 text-white text-[11px] font-bold uppercase leading-tight max-w-sm">
                                            {isLocked ? "HIDDEN PREMIUM DATA" : item.uraian_hs}
                                        </td>
                                        <td className="px-6 py-4 font-bold text-white text-right text-xs">
                                            {Number(item.vol_2025 || 0).toLocaleString('id-ID')}{" "}
                                            <span className="text-[8px] text-gray-500 font-normal uppercase">Pcs</span>
                                        </td>
                                        <td className="px-6 py-4 font-bold text-emerald-400 text-right text-xs">
                                            {isLocked ? "$ *.*" : `$ ${Number(item.val_2025 || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`}
                                        </td>
                                        <td className={`px-6 py-4 text-right font-black text-xs ${(item.growth || 0) > 0 ? "text-emerald-400" : "text-red-400"}`}>
                                            {isLocked ? "▲ *.*%" : `${(item.growth || 0) > 0 ? "▲" : "▼"} ${Math.abs(item.growth || 0).toFixed(1)}%`}
                                        </td>
                                    </tr>
                                );
                            })}
                        </tbody>

                        {/* 🌟 BARIS TOTAL DI FOOTER TABEL */}
                        <tfoot className="bg-white/5 border-t border-white/20">
                            <tr>
                                <td className="px-8 py-4 text-[11px] font-black uppercase text-gray-400 tracking-widest">TOTAL</td>
                                <td className="px-6 py-4 text-[10px] font-bold text-gray-400 italic uppercase">All Garment Commodities</td>
                                <td className="px-6 py-4 font-black text-white text-right text-xs">
                                    {totalVolumePcs.toLocaleString('id-ID')}{" "}
                                    <span className="text-[8px] text-gray-500 font-normal">Pcs</span>
                                </td>
                                <td className="px-6 py-4 font-black text-emerald-400 text-right text-xs">
                                    {`$ ${totalValueUSD.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`}
                                </td>
                                <td className="px-6 py-4"></td>
                            </tr>
                        </tfoot>
                    </table>

                    {/* --- CONTROLS PAGINATION --- */}
                    <div className="p-6 bg-white/5 border-t border-white/10 flex justify-between items-center relative z-40">
                        <div className="flex items-center gap-6">
                            <span className="text-[10px] text-gray-400 font-black uppercase tracking-widest">
                                {t("Showing page")} <span className="text-white">{currentPage}</span> {t("of")} <span className="text-white">{totalPages}</span>
                            </span>
                            <div className="flex gap-1 border-l border-white/10 pl-6">
                                {[...Array(totalPages)].map((_, i) => {
                                    const page = i + 1;
                                    if (page === 1 || page === totalPages || (page >= currentPage - 1 && page <= currentPage + 1)) {
                                        return (
                                            <button
                                                key={i}
                                                onClick={() => paginate(page)}
                                                className={`w-7 h-7 rounded-lg text-[9px] font-black transition-all ${currentPage === page ? "bg-yellow-500 text-black shadow-lg" : "bg-white/5 text-white hover:bg-white/20"}`}
                                            >
                                                {page}
                                            </button>
                                        );
                                    }
                                    if (page === currentPage - 2 || page === currentPage + 2) {
                                        return <span key={i} className="text-gray-600 text-[10px]">...</span>;
                                    }
                                    return null;
                                })}
                            </div>
                        </div>

                        <div className="flex gap-2">
                            <button
                                onClick={() => paginate(currentPage - 1)}
                                disabled={currentPage === 1}
                                className="px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-[10px] text-white font-black uppercase hover:bg-white/20 disabled:opacity-20"
                            >
                                {t("Previous")}
                            </button>
                            <button
                                onClick={() => paginate(currentPage + 1)}
                                disabled={currentPage === totalPages}
                                className="px-4 py-2 bg-yellow-500 text-black rounded-xl text-[10px] font-black uppercase hover:bg-yellow-400 disabled:opacity-20 shadow-lg"
                            >
                                {t("Next")}
                            </button>
                        </div>
                    </div>

                    {/* OVERLAY TOMBOL UPGRADE */}
                    {!isPremium && (
                        <div className="absolute inset-0 z-30 flex flex-col items-center justify-end pb-16 bg-gradient-to-t from-[#0a192f] via-[#0a192f]/40 to-transparent">
                            <div className="bg-black/60 backdrop-blur-md p-8 rounded-[40px] border border-white/10 shadow-2xl flex flex-col items-center max-w-md mx-auto">
                                <div className="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center mb-4 shadow-[0_0_20px_rgba(234,179,8,0.4)]">
                                    <span className="text-xl">💎</span>
                                </div>
                                <p className="text-white text-xs font-black uppercase text-center leading-relaxed mb-6 px-4">
                                    {isEn ? "To access full 8-digit export data and premium analysis, please upgrade your account to Premium." : "Untuk mendapatkan akses ke data ekspor 8-digit lengkap dan analisis premium, silakan tingkatkan akun Anda ke Premium."}
                                </p>
                                <Link
                                    href={route("pricing.index")}
                                    className="bg-yellow-500 text-black px-10 py-4 rounded-2xl font-black uppercase text-[11px] tracking-widest hover:bg-yellow-400 transition-all shadow-xl"
                                >
                                    {isEn ? "Upgrade to Premium" : "Upgrade ke Premium"}
                                </Link>
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
}