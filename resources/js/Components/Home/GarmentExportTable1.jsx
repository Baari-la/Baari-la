import * as XLSX from "xlsx";

export default function GarmentExportTable({
    topProducts = [],
    totalGarment = { kg_2025: 0 },
    isEn,
}) {
    const exportToExcel = () => {
        const excelData = topProducts.map((item) => ({
            "HS Code 8-Digit": item.hs_code_clean,
            Description: item.uraian_hs,
            "Volume 2024 (KG)": Number(item.vol_2024),
            "Volume 2025 (KG)": Number(item.vol_2025),
            "Growth (%)": Number(item.growth || 0).toFixed(2) + "%",
        }));

        const worksheet = XLSX.utils.json_to_sheet(excelData);
        const workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(
            workbook,
            worksheet,
            "Top 15 Export Garments",
        );
        XLSX.writeFile(workbook, `Digestex_V2_Top_15_Garment_Export_2025.xlsx`);
    };

    return (
        <div className="space-y-10">
            {/* HEADER & TOMBOL EXPORT */}
            <div className="bg-white/10 border border-white/20 rounded-[32px] overflow-hidden backdrop-blur-2xl shadow-[0_20px_50px_rgba(0,0,0,0.5)]">
                <div className="p-8 border-b border-white/20 bg-gradient-to-r from-blue-600/20 to-transparent">
                    <div className="flex items-center gap-4">
                        <div className="w-2 h-10 bg-yellow-500 rounded-full shadow-[0_0_15px_rgba(234,179,8,0.5)]"></div>
                        <div>
                            <h3 className="text-white text-2xl font-black uppercase italic tracking-tighter leading-none">
                                Top 15{" "}
                                <span className="text-yellow-500">
                                    Export Commodities
                                </span>
                            </h3>
                            <p className="text-blue-400 text-[10px] font-black uppercase tracking-[0.3em] mt-2">
                                Real-Time 8-Digit HS Code Analytics (2025)
                            </p>
                        </div>
                    </div>
                </div>

                <button
                    onClick={exportToExcel}
                    className="flex items-center gap-3 bg-emerald-600 hover:bg-emerald-500 text-white px-6 py-3 rounded-2xl font-black uppercase text-[10px] tracking-widest transition-all shadow-lg active:scale-95"
                >
                    <i className="fas fa-file-excel text-lg"></i>
                    {isEn ? "Export to Excel" : "Unduh Data Excel"}
                </button>
            </div>

            {/* TABEL TOP 15 */}
            <div className="bg-white/5 border border-white/10 rounded-[32px] overflow-hidden backdrop-blur-xl shadow-2xl">
                <div className="p-8 border-b border-white/10">
                    <h3 className="text-white font-black uppercase italic text-xl">
                        Top 15{" "}
                        <span className="text-yellow-500">Commodities</span>
                    </h3>
                </div>

                <div className="overflow-x-auto">
                    <table className="w-full text-left">
                        <thead className="bg-white/10">
                            <tr>
                                <th className="px-8 py-5 text-[11px] font-black uppercase text-white tracking-widest border-b border-white/10">
                                    HS Code
                                </th>
                                <th className="px-6 py-5 text-[11px] font-black uppercase text-white tracking-widest border-b border-white/10 italic">
                                    Description
                                </th>
                                <th className="px-6 py-5 text-[11px] font-black uppercase text-yellow-500 tracking-widest border-b border-white/10 text-right">
                                    Vol 2025 (KG)
                                </th>
                                <th className="px-6 py-5 text-[11px] font-black uppercase text-white tracking-widest border-b border-white/10 text-right">
                                    Trend
                                </th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-white/10">
                            {topProducts.map((item, idx) => (
                                <tr
                                    key={idx}
                                    className="hover:bg-white/10 transition-all group"
                                >
                                    <td className="px-8 py-6 text-yellow-500 font-black text-sm tracking-tighter">
                                        {item.hs_code_clean}
                                    </td>
                                    <td className="px-6 py-6 text-white text-xs font-bold uppercase leading-tight max-w-sm">
                                        <span className="opacity-90 group-hover:opacity-100 transition-opacity">
                                            {item.uraian_hs}
                                        </span>
                                    </td>
                                    <td className="px-6 py-6 text-right text-white font-black text-sm tabular-nums">
                                        {Number(item.vol_2025).toLocaleString()}
                                    </td>
                                    <td
                                        className={`px-6 py-6 text-right font-black text-xs ${item.growth > 0 ? "text-emerald-400" : "text-red-500"}`}
                                    >
                                        <div
                                            className={`inline-block px-3 py-1 rounded-full ${item.growth > 0 ? "bg-emerald-500/10" : "bg-red-500/10"}`}
                                        >
                                            {item.growth > 0 ? "▲" : "▼"}{" "}
                                            {Math.abs(item.growth || 0).toFixed(
                                                1,
                                            )}
                                            %
                                        </div>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>

            {/* SUMMARY CARDS */}
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="bg-gradient-to-br from-blue-600 to-blue-700 p-8 rounded-[32px] shadow-xl">
                    <p className="text-blue-200 font-black uppercase text-[10px] tracking-[0.2em] mb-2">
                        Total Volume (KG)
                    </p>
                    <h4 className="text-white text-4xl font-black italic">
                        {Number(totalGarment.kg_2025 || 0).toLocaleString()}
                    </h4>
                </div>
                <div className="bg-gradient-to-br from-emerald-600 to-emerald-700 p-8 rounded-[32px] shadow-xl">
                    <p className="text-emerald-200 font-black uppercase text-[10px] tracking-[0.2em] mb-2">
                        Est. Production Units
                    </p>
                    <h4 className="text-white text-4xl font-black italic">
                        ± 1.53B{" "}
                        <span className="text-sm font-normal not-italic opacity-60 ml-2">
                            PCS
                        </span>
                    </h4>
                </div>
            </div>
        </div>
    );
}
