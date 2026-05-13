import React, { useState } from "react";
import {
    BarChart,
    Bar,
    XAxis,
    YAxis,
    Tooltip,
    ResponsiveContainer,
    Cell,
    LabelList,
} from "recharts";

const CustomTooltip = ({ active, payload }) => {
    if (active && payload && payload.length) {
        return (
            <div className="bg-[#050c1b] border border-white/10 p-3 rounded-xl shadow-2xl backdrop-blur-md">
                <p className="text-white text-[10px] font-bold mb-1">
                    {payload[0].payload.name}
                </p>
                <p className="text-yellow-500 text-sm font-black">
                    ${(payload[0].value / 1000000).toFixed(2)}M USD
                </p>
            </div>
        );
    }
    return null;
};
const calculateTotal = (dataArray) => {
    return dataArray.reduce(
        (acc, curr) => acc + parseFloat(curr.value || 0),
        0,
    );
};

export default function TopMarketChart({ data = {}, isEn = false }) {
    const [activeTab, setActiveTab] = useState("Garment");

    const categories = [
        { id: "Garment", label: isEn ? "Garment" : "Garmen", color: "#3b82f6" },
        { id: "Yarn", label: isEn ? "Yarn" : "Benang", color: "#eab308" },
        { id: "Fabric", label: isEn ? "Fabric" : "Kain", color: "#10b981" },
        { id: "Fiber", label: isEn ? "Fiber" : "Serat Alam", color: "#f59e0b" },
        {
            id: "Synthetic",
            label: isEn ? "Synthetic" : "Sintetik",
            color: "#8b5cf6",
        },
        { id: "Various", label: isEn ? "Misc" : "Berbagai", color: "#ec4899" },
    ];

    const currentData = data[activeTab] || { export: [], import: [] };

    // Hitung total untuk Ekspor dan Impor
    const totalExport = calculateTotal(currentData.export || []);
    const totalImport = calculateTotal(currentData.import || []);

    return (
        <div className="w-full bg-white/5 border border-white/10 p-4 md:p-10 rounded-[40px] shadow-2xl">
            {/* Header & Kategori - Dibuat Responsive */}
            <div className="flex flex-col xl:flex-row justify-between items-start xl:items-center mb-10 gap-6">
                <div className="border-l-4 border-yellow-500 pl-4">
                    <h3 className="text-white text-xl font-black uppercase italic tracking-tighter">
                        Market{" "}
                        <span className="text-yellow-500">
                            Radar Intelligence
                        </span>
                    </h3>
                    <p className="text-gray-500 text-[10px] font-bold uppercase tracking-widest mt-1">
                        Global Supply Chain Flow 2025
                    </p>
                </div>

                {/* Scrollable Tabs untuk HP */}
                <div className="flex bg-white/5 p-1 rounded-2xl overflow-x-auto w-full xl:w-auto scrollbar-hide">
                    {categories.map((cat) => (
                        <button
                            key={cat.id}
                            onClick={() => setActiveTab(cat.id)}
                            className={`px-5 py-3 rounded-xl text-[9px] font-black uppercase transition-all whitespace-nowrap ${
                                activeTab === cat.id
                                    ? "bg-yellow-500 text-[#0a192f] shadow-lg shadow-yellow-500/20"
                                    : "text-gray-500 hover:text-white"
                            }`}
                        >
                            {cat.label}
                        </button>
                    ))}
                </div>
            </div>

            {/* GRID SISTEM: PC Berdampingan (2 Kolom), HP Turun ke Bawah (1 Kolom) */}
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-12">
                {/* GRAFIK EKSPOR */}
                <div className="bg-white/5 p-6 rounded-[30px] border border-white/5">
                    <h4 className="text-blue-400 text-xs font-black uppercase mb-8 flex items-center gap-3">
                        <span className="w-3 h-3 rounded-full bg-blue-400 animate-pulse"></span>
                        {isEn
                            ? "Major Export Destinations"
                            : "Tujuan Ekspor Utama"}
                    </h4>
                    <div className="h-[350px] w-full">
                        <ResponsiveContainer width="100%" height="100%">
                            <BarChart
                                data={currentData.export || []}
                                layout="vertical"
                                /* PERBAIKAN 1: Perlebar margin kanan dari 50 ke 100 agar teks (15.5%) tidak terpotong */
                                margin={{ right: 100, left: 10 }}
                            >
                                <XAxis type="number" hide />
                                <YAxis dataKey="name" type="category" hide />
                                <Tooltip
                                    content={<CustomTooltip />}
                                    cursor={{ fill: "rgba(255,255,255,0.03)" }}
                                />
                                <Bar
                                    dataKey="value"
                                    radius={[0, 15, 15, 0]}
                                    barSize={25}
                                >
                                    <LabelList
                                        dataKey="name"
                                        position="insideLeft"
                                        fill="#fff"
                                        fontSize={11}
                                        fontWeight="900"
                                        offset={15}
                                    />
                                    <LabelList
                                        dataKey="value"
                                        content={(props) => {
                                            /* PERBAIKAN 2: Gunakan 'width' untuk menentukan ujung batang */
                                            const { x, y, width, value } =
                                                props;
                                            const percentage =
                                                totalExport > 0
                                                    ? (
                                                          (value /
                                                              totalExport) *
                                                          100
                                                      ).toFixed(1)
                                                    : 0;

                                            return (
                                                <text
                                                    /* x + width memastikan teks mulai SETELAH batang berakhir */
                                                    x={x + width + 10}
                                                    y={y + 17}
                                                    fill="#94a3b8"
                                                    fontSize={10}
                                                    fontWeight="bold"
                                                    textAnchor="start" /* Teks memanjang ke kanan */
                                                >
                                                    $
                                                    {(value / 1000000).toFixed(
                                                        1,
                                                    )}
                                                    M ({percentage}%)
                                                </text>
                                            );
                                        }}
                                    />
                                    {(currentData.export || []).map(
                                        (entry, index) => (
                                            <Cell
                                                key={index}
                                                fill={
                                                    categories.find(
                                                        (c) =>
                                                            c.id === activeTab,
                                                    ).color
                                                }
                                                fillOpacity={1 - index * 0.1}
                                            />
                                        ),
                                    )}
                                </Bar>
                            </BarChart>
                        </ResponsiveContainer>
                    </div>
                </div>

                {/* GRAFIK IMPOR */}
                <div className="bg-white/5 p-6 rounded-[30px] border border-white/5">
                    <h4 className="text-rose-400 text-xs font-black uppercase mb-8 flex items-center gap-3">
                        <span className="w-3 h-3 rounded-full bg-rose-400 animate-pulse"></span>
                        {isEn ? "Primary Import Origins" : "Asal Utama Impor"}
                    </h4>
                    <div className="h-[350px] w-full">
                        <ResponsiveContainer width="100%" height="100%">
                            <BarChart
                                data={currentData.import || []}
                                layout="vertical"
                                /* PERBAIKAN 1: Perlebar margin kanan agar label persentase tidak terpotong */
                                margin={{ right: 100, left: 10 }}
                            >
                                <XAxis type="number" hide />
                                <YAxis dataKey="name" type="category" hide />
                                <Tooltip
                                    content={<CustomTooltip />}
                                    cursor={{ fill: "rgba(255,255,255,0.03)" }}
                                />
                                <Bar
                                    dataKey="value"
                                    fill="#f43f5e"
                                    radius={[0, 15, 15, 0]}
                                    barSize={25}
                                >
                                    <LabelList
                                        dataKey="name"
                                        position="insideLeft"
                                        fill="#fff"
                                        fontSize={11}
                                        fontWeight="900"
                                        offset={15}
                                    />
                                    <LabelList
                                        dataKey="value"
                                        content={(props) => {
                                            /* PERBAIKAN 2: Gunakan width agar teks berada di ujung luar batang */
                                            const { x, y, width, value } =
                                                props;
                                            const percentage =
                                                totalImport > 0
                                                    ? (
                                                          (value /
                                                              totalImport) *
                                                          100
                                                      ).toFixed(1)
                                                    : 0;
                                            return (
                                                <text
                                                    x={x + width + 10}
                                                    y={y + 17}
                                                    fill="#94a3b8"
                                                    fontSize={10}
                                                    fontWeight="bold"
                                                    textAnchor="start"
                                                >
                                                    $
                                                    {(value / 1000000).toFixed(
                                                        1,
                                                    )}
                                                    M ({percentage}%)
                                                </text>
                                            );
                                        }}
                                    />
                                    {(currentData.import || []).map(
                                        (entry, index) => (
                                            <Cell
                                                key={index}
                                                fill="#f43f5e"
                                                fillOpacity={1 - index * 0.1}
                                            />
                                        ),
                                    )}
                                </Bar>
                            </BarChart>
                        </ResponsiveContainer>
                    </div>
                </div>
            </div>
        </div>
    );
}
