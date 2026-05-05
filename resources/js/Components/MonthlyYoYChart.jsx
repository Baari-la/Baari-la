import React, { useState } from "react";
import {
    BarChart,
    Bar,
    XAxis,
    YAxis,
    CartesianGrid,
    Tooltip,
    ResponsiveContainer,
    Cell,
    LabelList,
} from "recharts";

const MonthlyYoYChart = ({ data = [] }) => {
    const [viewMode, setViewMode] = useState("value");

    if (!data || data.length < 2) return null;

    // Hitung Persentase Pertumbuhan Otomatis (YoY)
    const currentVal = viewMode === "value" ? data[1].value : data[1].volume;
    const prevVal = viewMode === "value" ? data[0].value : data[0].volume;
    const growth = (((currentVal - prevVal) / prevVal) * 100).toFixed(1);
    const isRisk = growth < 0;

    // Fungsi Narasi Risiko Otomatis
    const renderRiskNote = () => {
        // Simulasi deteksi otomatis (Bapak bisa sesuaikan dengan data riil nantinya)
        const riskSectorEn = "Downstream (Garment)";
        const riskSectorId = "Hilir (Garmen)";
        const riskRegionEn = "European Union";
        const riskRegionId = "Uni Eropa";

        if (isRisk) {
            return (
                <div className="mt-6 p-5 bg-white/95 border-l-[6px] border-red-600 rounded-r-2xl shadow-[0_10px_30px_rgba(220,38,38,0.3)]">
                    <div className="flex items-start">
                        {/* Icon Box lebih solid agar terlihat berwibawa */}
                        <div className="bg-red-600 p-2 rounded-xl mr-4 shadow-lg animate-pulse">
                            <span className="text-white text-sm">⚠️</span>
                        </div>
                        <div>
                            <div className="flex items-center gap-2 mb-2">
                                {/* Teks Header dibuat gelap agar kontras dengan BG putih */}
                                <p className="text-red-700 text-[11px] font-black uppercase tracking-widest">
                                    ERM ALERT SYSTEM
                                </p>
                                <span className="text-red-300">|</span>
                                <p className="text-slate-500 text-[10px] font-bold italic uppercase">
                                    Sistem Peringatan Dini
                                </p>
                            </div>

                            {/* English Section: Teks dibuat Hitam/Gelap agar sangat mudah dibaca */}
                            <p className="text-slate-900 text-[12px] leading-relaxed font-extrabold mb-1">
                                A decrease of{" "}
                                <span className="text-red-600">
                                    {Math.abs(growth)}%
                                </span>{" "}
                                detected. Primary risk source identified in{" "}
                                <span className="underline decoration-red-600/50">
                                    {riskSectorEn}
                                </span>
                                , specifically impacting the{" "}
                                <span className="text-red-600">
                                    {riskRegionEn}
                                </span>{" "}
                                market.
                            </p>

                            {/* Indonesian Section: Teks dibuat lebih lembut tapi tetap kontras */}
                            <p className="text-slate-600 text-[11px] italic leading-relaxed border-t border-slate-200 pt-2 mt-2 font-medium">
                                Penurunan {Math.abs(growth)}% terdeteksi. Sumber
                                risiko utama teridentifikasi pada sektor{" "}
                                <span className="font-bold text-red-700">
                                    {riskSectorId}
                                </span>
                                , khususnya berdampak pada pasar{" "}
                                <span className="font-bold text-red-700">
                                    {riskRegionId}
                                </span>{" "}
                                pada periode Jan-Feb 2026.
                            </p>

                            {/* TOMBOL SHARE ALERT (WhatsApp Ready) */}
                            <button
                                onClick={() => {
                                    const text = `⚠️ ERM ALERT: Penurunan ${Math.abs(growth)}% terdeteksi di sektor ${riskSectorId} pasar ${riskRegionId}.`;
                                    navigator.clipboard.writeText(text);
                                    alert(
                                        "Peringatan disalin ke Clipboard! Siap ditempel di WhatsApp.",
                                    );
                                }}
                                className="mt-3 flex items-center gap-1.5 text-[9px] font-black text-red-600 hover:text-red-800 transition-colors uppercase tracking-tighter"
                            >
                                <span>📲</span> Copy & Share Alert
                            </button>
                        </div>
                    </div>
                </div>
            );
        }

        return (
            <div className="mt-6 p-5 bg-emerald-950/40 border-l-4 border-emerald-500 rounded-r-2xl shadow-xl">
                {/* Tampilan Hijau (Sama dengan sebelumnya) */}
                <div className="flex items-start">
                    <div className="bg-emerald-500 p-1.5 rounded-lg mr-4">
                        <span className="text-white text-xs">✅</span>
                    </div>
                    <div>
                        <div className="flex items-center gap-2 mb-2">
                            <p className="text-emerald-500 text-[11px] font-black uppercase tracking-widest">
                                STABILITY ASSURANCE
                            </p>
                        </div>
                        <p className="text-emerald-500 text-[11px] leading-relaxed font-bold">
                            Global trade performance remains optimal. Market
                            demand across all regions is within the positive
                            growth threshold.
                        </p>
                    </div>
                </div>
            </div>
        );
    };

    return (
        <div className="bg-white/5 border border-white/10 p-6 rounded-3xl shadow-2xl backdrop-blur-sm">
            <div className="flex justify-between items-start mb-8">
                <div>
                    <h3 className="text-white font-black uppercase italic tracking-widest text-lg">
                        YoY Growth{" "}
                        <span className="text-blue-500">Analysis</span>
                    </h3>
                    <div
                        className={`mt-2 inline-flex items-center px-3 py-1 rounded-full border ${isRisk ? "bg-red-500/10 border-red-500/50 text-red-500" : "bg-emerald-500/10 border-emerald-500/50 text-emerald-500"}`}
                    >
                        <span
                            className={`w-2 h-2 rounded-full mr-2 animate-pulse ${isRisk ? "bg-red-500" : "bg-emerald-500"}`}
                        ></span>
                        <span className="text-[10px] font-black uppercase tracking-tighter">
                            {isRisk
                                ? `AT RISK: ${growth}%`
                                : `STABLE GROWTH: +${growth}%`}
                        </span>
                    </div>
                </div>

                <div className="flex bg-black/40 p-1 rounded-xl border border-white/10">
                    <button
                        onClick={() => setViewMode("value")}
                        className={`px-4 py-1.5 rounded-lg text-[10px] font-black transition-all ${viewMode === "value" ? "bg-blue-600 text-white shadow-lg" : "text-slate-500 hover:text-white"}`}
                    >
                        VALUE (USD)
                    </button>
                    <button
                        onClick={() => setViewMode("volume")}
                        className={`px-4 py-1.5 rounded-lg text-[10px] font-black transition-all ${viewMode === "volume" ? "bg-emerald-600 text-white shadow-lg" : "text-slate-500 hover:text-white"}`}
                    >
                        VOLUME (KG)
                    </button>
                </div>
            </div>

            <div className="h-[300px] w-full">
                <ResponsiveContainer width="100%" height="100%">
                    <BarChart
                        data={data}
                        margin={{ top: 20, right: 30, left: 20, bottom: 5 }}
                    >
                        <CartesianGrid
                            strokeDasharray="3 3"
                            stroke="#ffffff10"
                            vertical={false}
                        />
                        <XAxis
                            dataKey="period"
                            stroke="#94a3b8"
                            fontSize={11}
                            fontWeight="bold"
                            axisLine={false}
                            tickLine={false}
                        />
                        <YAxis
                            stroke="#94a3b8"
                            fontSize={11}
                            axisLine={false}
                            tickLine={false}
                            tickFormatter={(val) =>
                                viewMode === "value" ? `$${val}M` : `${val}M`
                            }
                        />
                        <Tooltip
                            contentStyle={{
                                backgroundColor: "#0f172a",
                                border: "1px solid #334155",
                                borderRadius: "12px",
                            }}
                            itemStyle={{ fontSize: "12px", fontWeight: "bold" }}
                        />
                        <Bar
                            dataKey={viewMode === "value" ? "value" : "volume"}
                            radius={[10, 10, 0, 0]}
                            barSize={80}
                        >
                            {data.map((entry, index) => (
                                <Cell
                                    key={`cell-${index}`}
                                    fill={
                                        index === 0
                                            ? "rgba(255, 255, 255, 0.3)"
                                            : viewMode === "value"
                                              ? "#3b82f6"
                                              : "#10b981"
                                    }
                                />
                            ))}
                            <LabelList
                                dataKey={
                                    viewMode === "value" ? "value" : "volume"
                                }
                                position="top"
                                style={{
                                    fill: "#ffffff",
                                    fontSize: "12px",
                                    fontWeight: "900",
                                }}
                                formatter={(val) =>
                                    viewMode === "value"
                                        ? `$${val}M`
                                        : `${val}M`
                                }
                            />
                        </Bar>
                    </BarChart>
                </ResponsiveContainer>
            </div>

            <div className="mt-4 flex items-center justify-center gap-6">
                <div className="flex items-center gap-2">
                    <div className="w-3 h-3 rounded bg-white/30 border border-white/50"></div>
                    <span className="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">
                        Jan-Feb 2025
                    </span>
                </div>
                <div className="flex items-center gap-2">
                    <div
                        className={`w-3 h-3 rounded ${viewMode === "value" ? "bg-blue-600" : "bg-emerald-600"}`}
                    ></div>
                    <span className="text-[10px] text-slate-400 font-bold uppercase tracking-tighter text-nowrap">
                        Jan-Feb 2026
                    </span>
                </div>
            </div>

            {/* PANGGIL FUNGSI NARASI DI SINI */}
            {renderRiskNote()}
        </div>
    );
};

export default MonthlyYoYChart;
