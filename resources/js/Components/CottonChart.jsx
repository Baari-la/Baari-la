import React from "react";
import {
    AreaChart,
    Area,
    XAxis,
    YAxis,
    CartesianGrid,
    Tooltip,
    ResponsiveContainer,
} from "recharts";

export default function CottonChart({ data }) {
    // Data dummy jika robot Python belum menyuplai data history
    const defaultData = [
        { date: "14 Apr", price: 81.2 },
        { date: "15 Apr", price: 82.5 },
        { date: "16 Apr", price: 81.9 },
        { date: "17 Apr", price: 83.4 },
        { date: "18 Apr", price: 84.1 },
        { date: "19 Apr", price: 83.8 },
    ];

    const chartData = data || defaultData;

    return (
        <div className="w-full h-[350px] bg-[#050c1b]/50 backdrop-blur-xl border border-white/10 rounded-[40px] p-8 shadow-2xl relative overflow-hidden group">
            {/* EFEK GLOW DI BACKGROUND */}
            <div className="absolute -top-24 -right-24 w-48 h-48 bg-yellow-500/10 blur-[80px] rounded-full"></div>

            <div className="flex justify-between items-start mb-8 relative z-10">
                <div>
                    <h4 className="text-[10px] font-black uppercase text-yellow-500 tracking-[0.4em] mb-1">
                        ICE Cotton Intelligence
                    </h4>
                    <p className="text-white text-xl font-black italic uppercase tracking-tighter">
                        Market Trend{" "}
                        <span className="text-gray-500 text-xs">
                            / USD Per Lbs
                        </span>
                    </p>
                </div>
                <div className="text-right">
                    <span className="text-[8px] font-black text-green-500 uppercase bg-green-500/10 px-3 py-1 rounded-full animate-pulse">
                        ● Live Market Feed
                    </span>
                </div>
            </div>

            <div className="h-[200px] w-full relative z-10">
                <ResponsiveContainer width="100%" height="100%">
                    <AreaChart data={chartData}>
                        <defs>
                            <linearGradient
                                id="colorPrice"
                                x1="0"
                                y1="0"
                                x2="0"
                                y2="1"
                            >
                                <stop
                                    offset="5%"
                                    stopColor="#eab308"
                                    stopOpacity={0.4}
                                />
                                <stop
                                    offset="95%"
                                    stopColor="#eab308"
                                    stopOpacity={0}
                                />
                            </linearGradient>
                        </defs>
                        <CartesianGrid
                            strokeDasharray="3 3"
                            stroke="#ffffff05"
                            vertical={false}
                        />
                        <XAxis
                            dataKey="date"
                            axisLine={false}
                            tickLine={false}
                            tick={{
                                fill: "#4b5563",
                                fontSize: 9,
                                fontWeight: "bold",
                            }}
                            dy={10}
                        />
                        <YAxis hide domain={["dataMin - 1", "dataMax + 1"]} />
                        <Tooltip
                            contentStyle={{
                                backgroundColor: "#0a192f",
                                border: "1px solid #ffffff10",
                                borderRadius: "20px",
                                boxShadow: "0 20px 40px rgba(0,0,0,0.4)",
                            }}
                            itemStyle={{
                                color: "#eab308",
                                fontSize: "12px",
                                fontWeight: "900",
                            }}
                        />
                        {/* GARIS GLOW (DUPLIKAT AREA UNTUK EFEK CAHAYA) */}
                        <Area
                            type="monotone"
                            dataKey="price"
                            stroke="#eab308"
                            strokeWidth={4}
                            fillOpacity={1}
                            fill="url(#colorPrice)"
                            animationDuration={2000}
                        />
                    </AreaChart>
                </ResponsiveContainer>
            </div>

            <p className="text-gray-600 text-[8px] font-bold uppercase tracking-[0.2em] mt-6 italic">
                * Data synchronized via Python Industrial Crawler from ICE
                Futures Exchange
            </p>
        </div>
    );
}
