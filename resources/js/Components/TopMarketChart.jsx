import React, { useState } from "react";
import {
    BarChart,
    Bar,
    XAxis,
    YAxis,
    Tooltip,
    ResponsiveContainer,
    Cell,
} from "recharts";

const CustomTooltip = ({ active, payload, isEn }) => {
    if (active && payload && payload.length) {
        return (
            <div className="bg-[#050c1b] border border-yellow-500/30 p-4 rounded-2xl shadow-2xl backdrop-blur-md">
                <p className="text-yellow-500 text-[10px] font-black uppercase tracking-widest mb-1">
                    {payload[0].payload.name}
                </p>
                <p className="text-white text-lg font-black italic">
                    ${(payload[0].value / 1000000).toFixed(2)}{" "}
                    <span className="text-[10px] text-gray-500 text-shadow-none">Million USD</span>
                </p>
            </div>
        );
    }
    return null;
};

export default function TopMarketChart({ data = {}, isEn = false }) {
    // 1. Definisikan Kategori sesuai Controller
    const categories = [
        { id: 'Garment', label: isEn ? 'Garment' : 'Garmen', color: '#3b82f6' },
        { id: 'Yarn', label: isEn ? 'Yarn' : 'Benang', color: '#eab308' },
        { id: 'Fabric', label: isEn ? 'Fabric' : 'Kain', color: '#10b981' },
        { id: 'Fiber', label: isEn ? 'Fiber' : 'Serat Alam', color: '#f59e0b' },
        { id: 'Synthetic', label: isEn ? 'Synthetic' : 'Sintetik', color: '#8b5cf6' },
        { id: 'Various', label: isEn ? 'Misc' : 'Berbagai', color: '#ec4899' },
    ];

    const [activeTab, setActiveTab] = useState('Garment');

    return (
        <div className="bg-white/5 border border-white/10 p-8 rounded-[40px] shadow-2xl relative overflow-hidden group">
            {/* Header & Tabs */}
            <div className="flex flex-col xl:flex-row justify-between items-start xl:items-center mb-8 gap-4">
                <div>
                    <h3 className="text-white text-sm font-black uppercase italic tracking-tighter">
                        Top <span className="text-yellow-500">Market Destinations</span>
                    </h3>
                    <p className="text-gray-500 text-[9px] font-bold uppercase italic mt-1">
                        {isEn ? "Global Trade Radar 2025" : "Radar Perdagangan Global 2025"}
                    </p>
                </div>
                
                {/* Switcher Tab */}
                <div className="flex bg-white/5 p-1 rounded-2xl overflow-x-auto max-w-full">
                    {categories.map((cat) => (
                        <button
                            key={cat.id}
                            onClick={() => setActiveTab(cat.id)}
                            className={`px-3 py-2 rounded-xl text-[8px] font-black uppercase transition-all whitespace-nowrap ${
                                activeTab === cat.id 
                                ? "bg-yellow-500 text-[#0a192f] shadow-lg" 
                                : "text-gray-500 hover:text-white"
                            }`}
                        >
                            {cat.label}
                        </button>
                    ))}
                </div>
            </div>

            {/* Chart Area */}
            <div className="h-[300px] w-full">
                <ResponsiveContainer width="100%" height="100%">
                    <BarChart
                        data={data[activeTab] || []}
                        layout="vertical"
                        margin={{ left: 20, right: 30 }}
                    >
                        <XAxis type="number" hide />
                        <YAxis
                            dataKey="name"
                            type="category"
                            tick={{ fill: "#94a3b8", fontSize: 9, fontWeight: "900" }}
                            width={110}
                            axisLine={false}
                            tickLine={false}
                        />
                        <Tooltip
                            content={<CustomTooltip isEn={isEn} />}
                            cursor={{ fill: "rgba(255,255,255,0.02)" }}
                        />
                        <Bar
                            dataKey="value"
                            radius={[0, 10, 10, 0]}
                            barSize={18}
                        >
                            {(data[activeTab] || []).map((entry, index) => (
                                <Cell
                                    key={`cell-${index}`}
                                    fill={categories.find(c => c.id === activeTab).color}
                                    fillOpacity={1 - (index * 0.15)}
                                />
                            ))}
                        </Bar>
                    </BarChart>
                </ResponsiveContainer>
            </div>

            {/* Footer */}
            <div className="mt-6 pt-6 border-t border-white/5 flex justify-between items-center">
                <p className="text-[8px] text-gray-500 font-bold uppercase tracking-widest">
                    {isEn ? "Trade Intelligence Stream" : "Aliran Intelijen Perdagangan"}
                </p>
                <span className="text-yellow-500 text-[8px] font-black italic uppercase cursor-pointer hover:underline">
                    {isEn ? "Deep Analysis" : "Analisis Mendalam"}
                </span>
            </div>
        </div>
    );
}
