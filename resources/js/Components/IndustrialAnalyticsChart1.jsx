import React from "react";
import {
    BarChart,
    Bar,
    XAxis,
    YAxis,
    CartesianGrid,
    Tooltip,
    Legend,
    ResponsiveContainer,
    Line,
    ComposedChart,
} from "recharts";

export default function IndustrialAnalyticsChart({ data }) {
    // Data Fallback jika data dari database belum ter-load (untuk demo)
    const defaultData = [
        { year: "2021", hulu: 0.85, antara: 3.45, hilir: 8.61, total: 12.91 },
        { year: "2022", hulu: 0.92, antara: 3.82, hilir: 9.1, total: 13.84 },
        { year: "2023", hulu: 0.78, antara: 3.12, hilir: 7.7, total: 11.6 },
        { year: "2024", hulu: 0.82, antara: 3.25, hilir: 8.08, total: 12.15 },
        { year: "2025", hulu: 0.8, antara: 3.18, hilir: 8.0, total: 11.98 },
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
                    <p className="text-gray-500 text-[10px] font-bold uppercase tracking-widest mt-2 italic">
                        Integrated Data: HS-Code Mapping & National Trade
                        Analytics
                    </p>
                </div>

                <div className="bg-white/5 border border-white/10 p-4 rounded-3xl text-right">
                    <p className="text-gray-500 text-[8px] font-black uppercase tracking-widest mb-1 text-nowrap">
                        Data Source
                    </p>
                    <p className="text-white font-black text-xs italic">
                        BPS & MST_HSCODE Registry
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
                            tickFormatter={(val) => `$${val}B`}
                        />
                        <Tooltip
                            contentStyle={{
                                backgroundColor: "#0a192f",
                                border: "1px solid rgba(255,255,255,0.1)",
                                borderRadius: "25px",
                                boxShadow: "0 25px 50px rgba(0,0,0,0.5)",
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

                        {/* BARS FOR SECTORS */}
                        <Bar
                            dataKey="hulu"
                            name="Hulu (Fiber)"
                            fill="#60a5fa"
                            radius={[10, 10, 0, 0]}
                        />
                        <Bar
                            dataKey="antara"
                            name="Antara (Yarn/Fabric)"
                            fill="#eab308"
                            radius={[10, 10, 0, 0]}
                        />
                        <Bar
                            dataKey="hilir"
                            name="Hilir (Apparel)"
                            fill="#ef4444"
                            radius={[10, 10, 0, 0]}
                        />

                        {/* LINE FOR TOTAL TREND */}
                        <Line
                            type="monotone"
                            dataKey="total"
                            name="Total Export"
                            stroke="#ffffff"
                            strokeWidth={4}
                            dot={{ r: 6, fill: "#ffffff" }}
                        />
                    </ComposedChart>
                </ResponsiveContainer>
            </div>

            <div className="mt-8 pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-4">
                <p className="text-gray-600 text-[8px] font-black uppercase tracking-[0.2em] italic">
                    * Units in Billion USD. Analytics derived from
                    trade_analytics_annual & mst_hscode.
                </p>
                <div className="flex gap-4">
                    <div className="flex items-center gap-2">
                        <div className="w-2 h-2 rounded-full bg-blue-500 shadow-[0_0_10px_rgba(59,130,246,0.5)]"></div>
                        <span className="text-[8px] font-black text-white uppercase tracking-widest">
                            Hulu
                        </span>
                    </div>
                    <div className="flex items-center gap-2">
                        <div className="w-2 h-2 rounded-full bg-yellow-500 shadow-[0_0_10px_rgba(234,179,8,0.5)]"></div>
                        <span className="text-[8px] font-black text-white uppercase tracking-widest">
                            Antara
                        </span>
                    </div>
                    <div className="flex items-center gap-2">
                        <div className="w-2 h-2 rounded-full bg-red-500 shadow-[0_0_10px_rgba(239,68,68,0.5)]"></div>
                        <span className="text-[8px] font-black text-white uppercase tracking-widest">
                            Hilir
                        </span>
                    </div>
                </div>
            </div>
        </div>
    );
}
