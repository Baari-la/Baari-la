import React from "react";
import {
    AreaChart,
    Area,
    ResponsiveContainer,
    Tooltip,
    XAxis,
    CartesianGrid,
    YAxis,
} from "recharts";

const CottonCurrencyTrendChart = ({ data, isEn = false }) => {
    return (
        <div className="bg-[#0a192f] p-8 rounded-[40px] shadow-2xl border border-white/5 relative overflow-hidden group">
            {/* Dekorasi Background */}
            <div className="absolute top-0 right-0 w-64 h-64 bg-yellow-500/10 rounded-full blur-[100px] -mr-32 -mt-32"></div>

            <div className="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 relative z-10">
                <div>
                    <h4 className="text-white font-black text-xl italic tracking-tighter uppercase">
                        {isEn ? "Market Intelligence" : "Intelijen Pasar"}
                    </h4>
                    <p className="text-gray-400 text-[10px] font-bold tracking-[0.2em] uppercase mt-1">
                        NY/ICE Cotton Index - Real-time Performance
                    </p>
                </div>
                <div className="mt-4 md:mt-0 px-4 py-2 bg-white/5 rounded-2xl border border-white/10 backdrop-blur-md">
                    <p className="text-emerald-400 text-[10px] font-black flex items-center gap-2">
                        <span className="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        {isEn ? "LIVE BURSA" : "DATA LIVE"}
                    </p>
                </div>
            </div>

            <div className="h-64 w-full relative z-10">
                <ResponsiveContainer width="100%" height="100%">
                    <AreaChart data={data}>
                        <defs>
                            <linearGradient
                                id="colorCotton"
                                x1="0"
                                y1="0"
                                x2="0"
                                y2="1"
                            >
                                <stop
                                    offset="5%"
                                    stopColor="#ebb308"
                                    stopOpacity={0.4}
                                />
                                <stop
                                    offset="95%"
                                    stopColor="#ebb308"
                                    stopOpacity={0}
                                />
                            </linearGradient>
                        </defs>
                        <CartesianGrid
                            strokeDasharray="3 3"
                            vertical={false}
                            stroke="#ffffff10"
                        />
                        <XAxis
                            dataKey="month"
                            axisLine={false}
                            tickLine={false}
                            tick={{
                                fontSize: 10,
                                fill: "#64748b",
                                fontWeight: "bold",
                            }}
                            dy={10}
                        />
                        {/* YAxis disembunyikan agar tampilan lebih minimalis & bersih */}
                        <YAxis hide domain={["auto", "auto"]} />
                        <Tooltip
                            contentStyle={{
                                backgroundColor: "#0f172a",
                                border: "1px solid #ffffff20",
                                borderRadius: "16px",
                                fontSize: "11px",
                                color: "#fff",
                                boxShadow: "0 20px 25px -5px rgb(0 0 0 / 0.3)",
                            }}
                            itemStyle={{ color: "#ebb308", fontWeight: "bold" }}
                            formatter={(value) => [
                                `$${value}`,
                                isEn ? "Price" : "Harga",
                            ]}
                        />
                        <Area
                            type="monotone"
                            dataKey="price"
                            stroke="#ebb308"
                            strokeWidth={4}
                            fillOpacity={1}
                            fill="url(#colorCotton)"
                            animationDuration={2500}
                        />
                    </AreaChart>
                </ResponsiveContainer>
            </div>

            <div className="mt-6 flex justify-between items-center border-t border-white/5 pt-6">
                <p className="text-[9px] text-gray-500 font-bold tracking-widest uppercase italic">
                    {isEn
                        ? "Source: NY/ICE Global Exchange"
                        : "Sumber: Bursa Global NY/ICE"}
                </p>
                <p className="text-[9px] text-gray-400 font-medium">
                    Updated:{" "}
                    {new Date().toLocaleDateString("id-ID", {
                        day: "numeric",
                        month: "short",
                    })}
                </p>
            </div>
        </div>
    );
};

export default CottonCurrencyTrendChart;
