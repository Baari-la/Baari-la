import React, { useState } from "react";
import {
    AreaChart,
    Area,
    XAxis,
    YAxis,
    Tooltip,
    ResponsiveContainer,
    CartesianGrid,
    Legend,
    LabelList,
} from "recharts";

const FiberComparisonChart = ({
    data = [],
    isEn = false,
    isLoggedIn = false,
}) => {
    // Tombol Switch Volume vs Value tetap bisa diklik 100% oleh siapa saja
    const [viewMode, setViewMode] = useState("vol");

    const activeCottonKey = viewMode === "vol" ? "cotton_vol" : "cotton_val";
    const activeSynKey = viewMode === "vol" ? "syn_vol" : "syn_val";

    const formatLabel = (value) => {
        if (!value) return ""; // Menghilangkan label angka 0M di area terlarang agar tidak berantakan
        return `${(value / 1000000).toFixed(1)}M`;
    };

    return (
        <div className="w-full bg-white/5 border border-white/10 p-8 rounded-[40px] shadow-2xl relative overflow-hidden group">
            {/* --- HEADER & NAVIGATION CONTROLS (Selalu Aktif & Berfungsi) --- */}
            <div className="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4 relative z-20">
                <div>
                    <h4 className="text-white text-sm font-black uppercase italic tracking-tighter">
                        {isEn
                            ? "Strategic Fiber Intelligence"
                            : "Intelijen Strategis Serat"}
                    </h4>
                    <p className="text-gray-500 text-[10px] font-bold uppercase tracking-widest mt-1">
                        {viewMode === "vol"
                            ? isEn
                                ? "Import Quantity Analysis (KG)"
                                : "Analisis Volume Pasokan (KG)"
                            : isEn
                              ? "Financial Import Value (USD)"
                              : "Analisis Nilai Transaksi (USD)"}
                    </p>
                </div>

                {/* Switcher Tombol Tetap Bisa Diklik dengan Mulus */}
                <div className="flex bg-white/10 p-1 rounded-2xl border border-white/5 backdrop-blur-md">
                    <button
                        onClick={() => setViewMode("vol")}
                        className={`px-6 py-2 rounded-xl text-[9px] font-black uppercase transition-all duration-300 ${viewMode === "vol" ? "bg-yellow-500 text-[#0a192f] shadow-lg shadow-yellow-500/20" : "text-gray-400 hover:text-white"}`}
                    >
                        Volume (KG)
                    </button>
                    <button
                        onClick={() => setViewMode("val")}
                        className={`px-6 py-2 rounded-xl text-[9px] font-black uppercase transition-all duration-300 ${viewMode === "val" ? "bg-blue-500 text-white shadow-lg" : "text-gray-400 hover:text-white"}`}
                    >
                        Value (USD)
                    </button>
                </div>
            </div>

            {/* --- WORKBENCH AREA GRAFIK RECHARTS --- */}
            <div className="relative w-full h-[400px]">
                {/* Efek Buram dipindahkan ke sini. Jika belum login, grafik 2019-2022 tetap tegak, tapi melandai buram di ujungnya */}
                <div className="w-full h-full">
                    <ResponsiveContainer width="100%" height="100%">
                        <AreaChart
                            data={data}
                            margin={{
                                top: 40,
                                right: 30,
                                left: 10,
                                bottom: 10,
                            }}
                        >
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
                                        stopOpacity={0.25}
                                    />
                                    <stop
                                        offset="95%"
                                        stopColor="#ebb308"
                                        stopOpacity={0}
                                    />
                                </linearGradient>
                                <linearGradient
                                    id="colorSyn"
                                    x1="0"
                                    y1="0"
                                    x2="0"
                                    y2="1"
                                >
                                    <stop
                                        offset="5%"
                                        stopColor="#8b5cf6"
                                        stopOpacity={0.25}
                                    />
                                    <stop
                                        offset="95%"
                                        stopColor="#8b5cf6"
                                        stopOpacity={0}
                                    />
                                </linearGradient>
                            </defs>
                            <CartesianGrid
                                strokeDasharray="3 3"
                                vertical={false}
                                stroke="#ffffff05"
                            />
                            <XAxis
                                dataKey="year"
                                axisLine={{ stroke: "#ffffff10" }}
                                tick={{
                                    fill: "#64748b",
                                    fontSize: 10,
                                    fontWeight: "bold",
                                }}
                            />
                            <YAxis
                                axisLine={{ stroke: "#ffffff10" }}
                                tickFormatter={formatLabel}
                                width={50}
                                domain={["auto", "auto"]}
                            />

                            {/* Kursor cerdas hanya mendeteksi data jika sudah login atau di area aman (2019-2022) */}
                            {/* --- ADVANCED METRIC TOOLTIP (SINKRON DENGAN SWITCH VALUE/VOLUME + AMAN CEK NULL) --- */}
                            <Tooltip
                                content={({ active, payload, label }) => {
                                    // PERBAIKAN 1: Pastikan payload memiliki isi dan indeks ke-0 tersedia sebelum dieksekusi
                                    if (
                                        active &&
                                        payload &&
                                        payload.length > 0 &&
                                        payload[0]?.payload
                                    ) {
                                        // Recharts menyimpan objek baris data di dalam indeks pertama payload
                                        const item = payload[0].payload;

                                        // Cegah pembacaan kursor di tahun terlarang jika pendaftar belum login
                                        if (
                                            !isLoggedIn &&
                                            parseInt(label) > 2022
                                        )
                                            return null;

                                        const isVolumeMode = viewMode === "vol";

                                        return (
                                            <div className="bg-[#050c1b] p-4 rounded-2xl border border-white/10 shadow-2xl backdrop-blur-xl min-w-[200px]">
                                                <p className="text-white font-black text-[10px] mb-3 border-b border-white/5 pb-2 uppercase italic tracking-widest">
                                                    Year {label}
                                                </p>
                                                <div className="space-y-4">
                                                    {/* 1. KURSOR DATA KAPAS */}
                                                    <div className="border-l-2 border-yellow-500 pl-3">
                                                        <p className="text-yellow-500 text-[8px] font-black uppercase tracking-tighter">
                                                            Natural Cotton
                                                        </p>
                                                        <p className="text-white text-sm font-black italic">
                                                            {isVolumeMode
                                                                ? `${((item.cotton_vol || 0) / 1000000).toFixed(1)}M KG`
                                                                : `$${((item.cotton_val || 0) / 1000000).toFixed(1)}M USD`}
                                                        </p>
                                                        <p className="text-gray-500 text-[9px] font-medium mt-0.5">
                                                            {isVolumeMode
                                                                ? `Val: $${((item.cotton_val || 0) / 1000000).toFixed(1)}M USD`
                                                                : `Vol: ${((item.cotton_vol || 0) / 1000000).toFixed(1)}M KG`}
                                                        </p>
                                                    </div>

                                                    {/* 2. KURSOR DATA SERAT SINTETIS */}
                                                    <div className="border-l-2 border-purple-500 pl-3">
                                                        <p className="text-purple-500 text-[8px] font-black uppercase tracking-tighter">
                                                            Synthetic Fiber
                                                        </p>
                                                        <p className="text-white text-sm font-black italic">
                                                            {isVolumeMode
                                                                ? `${((item.syn_vol || 0) / 1000000).toFixed(1)}M KG`
                                                                : `$${((item.syn_val || 0) / 1000000).toFixed(1)}M USD`}
                                                        </p>
                                                        <p className="text-gray-500 text-[9px] font-medium mt-0.5">
                                                            {isVolumeMode
                                                                ? `Val: $${((item.syn_val || 0) / 1000000).toFixed(1)}M USD`
                                                                : `Vol: ${((item.syn_vol || 0) / 1000000).toFixed(1)}M KG`}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        );
                                    }
                                    return null;
                                }}
                            />

                            <Legend
                                verticalAlign="top"
                                align="right"
                                wrapperStyle={{
                                    paddingBottom: "20px",
                                    fontSize: "10px",
                                    fontWeight: "900",
                                }}
                            />

                            <Area
                                type="monotone"
                                dataKey={activeCottonKey}
                                name={isEn ? "Natural Cotton" : "Kapas Alam"}
                                stroke="#ebb308"
                                fill="url(#colorCotton)"
                                strokeWidth={4}
                            >
                                <LabelList
                                    dataKey={activeCottonKey}
                                    position="top"
                                    offset={12}
                                    fill="#ebb308"
                                    fontSize={10}
                                    fontWeight="900"
                                    formatter={formatLabel}
                                />
                            </Area>
                            <Area
                                type="monotone"
                                dataKey={activeSynKey}
                                name={
                                    isEn ? "Synthetic Fiber" : "Serat Sintetis"
                                }
                                stroke="#8b5cf6"
                                fill="url(#colorSyn)"
                                strokeWidth={4}
                            >
                                <LabelList
                                    dataKey={activeSynKey}
                                    position="top"
                                    offset={12}
                                    fill="#8b5cf6"
                                    fontSize={10}
                                    fontWeight="900"
                                    formatter={formatLabel}
                                />
                            </Area>
                        </AreaChart>
                    </ResponsiveContainer>
                </div>

                {/* --- OVERLAY COATING: Hanya Mengunci Sisi Kanan Grafik (2023-2025) --- */}
                {!isLoggedIn && (
                    <div className="absolute top-0 right-0 w-[42%] h-[82%] bg-gradient-to-r from-transparent via-[#0a192f]/80 to-[#0a192f] backdrop-blur-[5px] flex flex-col items-center justify-center p-4 text-center z-30 rounded-r-[30px] border-l border-white/5 animate-fade-in">
                        <div className="bg-yellow-500/10 border border-yellow-500/30 w-12 h-12 rounded-2xl flex items-center justify-center shadow-xl mb-2 shadow-yellow-500/5">
                            <i className="fas fa-lock text-yellow-500 text-base animate-bounce"></i>
                        </div>
                        <h5 className="text-white text-xs font-black uppercase tracking-tight italic">
                            {isEn ? "Forecast Locked" : "Tren Baru Terkunci"}
                        </h5>
                        <p className="text-gray-400 text-[9px] font-medium max-w-[180px] mt-1 mb-4 leading-normal">
                            {isEn
                                ? "Sign in to unlock data updates for 2023 - 2025."
                                : "Masuk untuk melihat rincian pembaruan data tahun 2023 - 2025."}
                        </p>

                        {/* Tombol Masuk Google */}
                        <a
                            href={route("google.login")}
                            className="flex items-center gap-2 bg-white text-gray-900 px-4 py-2.5 rounded-full font-black text-[8px] uppercase tracking-wider hover:bg-gray-100 transition-all shadow-lg border border-white/20 hover:scale-105 duration-300"
                        >
                            <svg className="w-3 h-3" viewBox="0 0 24 24">
                                <path
                                    fill="#EA4335"
                                    d="M12 5.04c1.66 0 3.2.57 4.38 1.69l3.27-3.27C17.67 1.47 14.97 0 12 0 7.35 0 3.33 2.67 1.34 6.57l3.85 2.99c.92-2.77 3.51-4.52 6.81-4.52z"
                                />
                                <path
                                    fill="#4285F4"
                                    d="M23.49 12.27c0-.81-.07-1.59-.2-2.36H12v4.51h6.46c-.29 1.48-1.14 2.74-2.4 3.58l3.76 2.91c2.2-2.03 3.67-5.02 3.67-8.64z"
                                />
                                <path
                                    fill="#FBBC05"
                                    d="M5.19 14.54a7.17 7.17 0 0 1 0-4.08L1.34 7.47a11.94 11.94 0 0 0 0 9.07l3.85-3z"
                                />
                                <path
                                    fill="#34A853"
                                    d="M12 24c3.24 0 5.97-1.07 7.96-2.91l-3.76-2.91c-1.05.7-2.39 1.12-4.2 1.12-3.3 0-5.89-1.75-6.81-4.52L1.34 17.7C3.33 21.33 7.35 24 12 24z"
                                />
                            </svg>
                            {isEn ? "Unlock (Free)" : "Buka Grafik (Gratis)"}
                        </a>
                    </div>
                )}
            </div>
        </div>
    );
};

export default FiberComparisonChart;
