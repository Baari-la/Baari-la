import React, { useState } from "react"; // Tambahkan useState
import {
    Bar,
    XAxis,
    YAxis,
    CartesianGrid,
    Tooltip,
    Legend,
    ResponsiveContainer,
    Line,
    ComposedChart,
    LabelList,
} from "recharts";

export default function IndustrialAnalyticsChart({ data }) {
    // 1. STATE UNTUK SWITCH VIEW (Value vs Volume)
    const [viewMode, setViewMode] = useState("value"); // default ke USD

    const defaultData = [
        {
            year: "2021",
            hulu: 0.85,
            antara: 3.45,
            hilir: 8.61,
            total: 12.91,
            vol_hulu: 150,
            vol_antara: 500,
            vol_hilir: 900,
        },
        {
            year: "2022",
            hulu: 0.92,
            antara: 3.82,
            hilir: 9.1,
            total: 13.84,
            vol_hulu: 160,
            vol_antara: 520,
            vol_hilir: 950,
        },
        {
            year: "2023",
            hulu: 0.78,
            antara: 3.12,
            hilir: 7.7,
            total: 11.6,
            vol_hulu: 140,
            vol_antara: 480,
            vol_hilir: 800,
        },
        {
            year: "2024",
            hulu: 0.82,
            antara: 3.25,
            hilir: 8.08,
            total: 12.15,
            vol_hulu: 145,
            vol_antara: 490,
            vol_hilir: 850,
        },
        {
            year: "2025",
            hulu: 0.8,
            antara: 3.18,
            hilir: 8.0,
            total: 11.98,
            vol_hulu: 142,
            vol_antara: 485,
            vol_hilir: 840,
        },
    ];

    const chartData = data && data.length > 0 ? data : defaultData;

    return (
        <div className="w-full bg-[#050c1b]/50 backdrop-blur-xl border border-white/10 rounded-[50px] p-8 md:p-12 shadow-2xl relative overflow-hidden group">
            <div className="flex flex-col md:flex-row justify-between items-start mb-12 relative z-10 gap-6">
                <div>
                    <span className="bg-blue-600 text-white text-[9px] font-black px-4 py-1 rounded-full uppercase tracking-[0.3em]">
                        Deep Analytics Mode
                    </span>
                    <h3 className="text-3xl md:text-5xl font-black text-white mt-4 tracking-tighter uppercase leading-none italic">
                        Sectoral{" "}
                        <span className="text-blue-500">Performance</span>
                    </h3>

                    {/* 2. TOMBOL SWITCHER ELEGAN */}
                    <div className="flex mt-6 bg-white/5 p-1 rounded-2xl border border-white/10 w-fit">
                        <button
                            onClick={() => setViewMode("value")}
                            className={`px-6 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all ${viewMode === "value" ? "bg-blue-600 text-white shadow-lg shadow-blue-600/20" : "text-gray-500 hover:text-white"}`}
                        >
                            Value ($ Billion)
                        </button>
                        <button
                            onClick={() => setViewMode("volume")}
                            className={`px-6 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all ${viewMode === "volume" ? "bg-green-600 text-white shadow-lg shadow-green-600/20" : "text-gray-500 hover:text-white"}`}
                        >
                            Volume (Million Kg)
                        </button>
                    </div>
                </div>

                <div className="bg-white/5 border border-white/10 p-4 rounded-3xl text-right">
                    <p className="text-gray-500 text-[8px] font-black uppercase tracking-widest mb-1 text-nowrap">
                        Current Metric
                    </p>
                    <p className="text-white font-black text-xs italic">
                        {viewMode === "value"
                            ? "Currency: USD"
                            : "Quantity: Kilograms"}
                    </p>
                </div>
            </div>

            <div className="h-[450px] w-full relative z-10">
                <ResponsiveContainer width="100%" height="100%">
                    <ComposedChart
                        data={chartData}
                        margin={{ top: 20, right: 20, bottom: 20, left: 20 }}
                    >
                        <CartesianGrid
                            strokeDasharray="3 3"
                            vertical={false}
                            stroke="rgba(255,255,255,0.05)"
                        />
                        <XAxis
                            dataKey="year"
                            stroke="#ffffff"
                            fontSize={12}
                            fontWeight="900"
                            axisLine={true}
                            tickLine={true}
                            dy={15}
                        />
                        <YAxis
                            stroke="#ffffff"
                            fontSize={11}
                            fontWeight="900"
                            axisLine={true}
                            tickLine={true}
                            tickFormatter={(val) =>
                                viewMode === "value" ? `$${val}B` : `${val}M`
                            }
                        />
                        <Tooltip
                            contentStyle={{
                                backgroundColor: "#0a192f",
                                border: "1px solid rgba(255,255,255,0.1)",
                                borderRadius: "25px",
                            }}
                            itemStyle={{
                                fontSize: "12px",
                                fontWeight: "900",
                                textTransform: "uppercase",
                            }}
                        />
                        <Legend
                            verticalAlign="top"
                            align="right"
                            wrapperStyle={{
                                paddingBottom: "40px",
                                fontSize: "10px",
                                fontWeight: "bold",
                            }}
                        />

                        {/* 3. LOGIKA BAR DINAMIS BERDASARKAN VIEWMODE DENGAN LABEL ANGKA */}
                        <Bar
                            dataKey={viewMode === "value" ? "hulu" : "vol_hulu"}
                            name={
                                viewMode === "value"
                                    ? "Serat ($)"
                                    : "Serat (Kg)"
                            }
                            fill="#60a5fa"
                            radius={[10, 10, 0, 0]}
                        >
                            <LabelList
                                dataKey={
                                    viewMode === "value" ? "hulu" : "vol_hulu"
                                }
                                position="top"
                                style={{
                                    fill: "#ffffff",
                                    fontSize: "10px",
                                    fontWeight: "bold",
                                }}
                                formatter={(val) =>
                                    viewMode === "value" ? `$${val}` : `${val}M`
                                }
                            />
                        </Bar>

                        <Bar
                            dataKey={
                                viewMode === "value" ? "antara" : "vol_antara"
                            }
                            name={
                                viewMode === "value"
                                    ? "Antara ($)"
                                    : "Antara (Kg)"
                            }
                            fill="#eab308"
                            radius={[10, 10, 0, 0]}
                        >
                            <LabelList
                                dataKey={
                                    viewMode === "value"
                                        ? "antara"
                                        : "vol_antara"
                                }
                                position="top"
                                style={{
                                    fill: "#ffffff",
                                    fontSize: "10px",
                                    fontWeight: "bold",
                                }}
                                formatter={(val) =>
                                    viewMode === "value" ? `$${val}` : `${val}M`
                                }
                            />
                        </Bar>

                        <Bar
                            dataKey={
                                viewMode === "value" ? "hilir" : "vol_hilir"
                            }
                            name={
                                viewMode === "value"
                                    ? "Hilir ($)"
                                    : "Hilir (Kg)"
                            }
                            fill="#ef4444"
                            radius={[10, 10, 0, 0]}
                        >
                            <LabelList
                                dataKey={
                                    viewMode === "value"
                                        ? "Garmen"
                                        : "vol_hilir"
                                }
                                position="top"
                                style={{
                                    fill: "#ffffff",
                                    fontSize: "10px",
                                    fontWeight: "bold",
                                }}
                                formatter={(val) =>
                                    viewMode === "value" ? `$${val}` : `${val}M`
                                }
                            />
                        </Bar>

                        {/* LINE HANYA MUNCUL DI MODE VALUE AGAR TIDAK RANCU */}
                        {viewMode === "value" && (
                            <Line
                                type="monotone"
                                dataKey="total"
                                name="Total Export"
                                stroke="#ffffff"
                                strokeWidth={4}
                                dot={{ r: 6, fill: "#ffffff" }}
                            />
                        )}
                    </ComposedChart>
                </ResponsiveContainer>
            </div>

            <div className="mt-8 pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-4">
                <p className="text-gray-600 text-[8px] font-black uppercase tracking-[0.2em] italic">
                    *{" "}
                    {viewMode === "value"
                        ? "Units in Billion USD."
                        : "Units in Million Kilograms."}{" "}
                    Analytics derived from trade_analytics_annual.
                </p>
                {/* Legend indicator tetap ada di bawah */}
            </div>
        </div>
    );
}
