import React from "react";
import {
    BarChart,
    Bar,
    XAxis,
    YAxis,
    CartesianGrid,
    Tooltip,
    ResponsiveContainer,
    Cell,
} from "recharts";

const CustomTooltip = ({ active, payload }) => {
    if (active && payload && payload.length) {
        return (
            <div className="bg-[#050c1b] border border-yellow-500/30 p-4 rounded-2xl shadow-2xl backdrop-blur-md">
                <p className="text-yellow-500 text-[10px] font-black uppercase tracking-widest mb-1">
                    {payload[0].payload.name}
                </p>
                <p className="text-white text-lg font-black italic">
                    {payload[0].value.toLocaleString()}{" "}
                    <span className="text-[10px] text-gray-500">Units</span>
                </p>
                <div className="mt-2 pt-2 border-t border-white/5">
                    <p className="text-emerald-400 text-[8px] font-bold uppercase tracking-tighter">
                        Market Share:{" "}
                        {((payload[0].value / 5000) * 100).toFixed(1)}%
                    </p>
                </div>
            </div>
        );
    }
    return null;
};

export default function TopMarketChart({ data }) {
    return (
        <div className="bg-white/5 border border-white/10 p-8 rounded-[40px] shadow-2xl relative overflow-hidden group">
            <div className="flex justify-between items-center mb-8">
                <div>
                    <h3 className="text-white text-sm font-black uppercase italic tracking-tighter">
                        Top{" "}
                        <span className="text-yellow-500">
                            Market Destinations
                        </span>
                    </h3>
                    <p className="text-gray-500 text-[9px] font-bold uppercase italic mt-1">
                        Real-time Trade Intelligence
                    </p>
                </div>
                <i className="fas fa-chart-bar text-yellow-500/20 text-2xl group-hover:rotate-12 transition-transform"></i>
            </div>

            <div className="h-[300px] w-full">
                <ResponsiveContainer width="100%" height="100%">
                    <BarChart
                        data={data}
                        layout="vertical"
                        margin={{ left: 20, right: 30 }}
                    >
                        <XAxis type="number" hide />
                        <YAxis
                            dataKey="name"
                            type="category"
                            tick={{
                                fill: "#94a3b8",
                                fontSize: 10,
                                fontWeight: "900",
                            }}
                            width={80}
                        />
                        <Tooltip
                            content={<CustomTooltip />}
                            cursor={{ fill: "rgba(255,255,255,0.05)" }}
                        />
                        <Bar
                            dataKey="value"
                            radius={[0, 10, 10, 0]}
                            barSize={20}
                        >
                            {data.map((entry, index) => (
                                <Cell
                                    key={`cell-${index}`}
                                    fill={index === 0 ? "#eab308" : "#3b82f6"}
                                    fillOpacity={0.8}
                                />
                            ))}
                        </Bar>
                    </BarChart>
                </ResponsiveContainer>
            </div>

            {/* CTA PREMIUM UNTUK NON-PREMIUM */}
            <div className="mt-6 pt-6 border-t border-white/5">
                <p className="text-[8px] text-gray-500 font-bold uppercase text-center tracking-widest">
                    Want deeper 8-digit HS Code analysis?{" "}
                    <span className="text-yellow-500 cursor-pointer hover:underline">
                        Upgrade to Premium
                    </span>
                </p>
            </div>
        </div>
    );
}
