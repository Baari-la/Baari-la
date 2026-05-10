import React from "react";
import {
    AreaChart,
    Area,
    ResponsiveContainer,
    Tooltip,
    XAxis,
    CartesianGrid,
    YAxis,
    LabelList,
} from "recharts";

const CottonCurrencyTrendChart = ({ data = [], isEn = false }) => {
    const safeData =
        data && data.length > 0
            ? data.map((item) => ({
                  ...item,
                  price: isNaN(parseFloat(item.price))
                      ? 0
                      : parseFloat(item.price),
                  exchange: isNaN(parseFloat(item.exchange))
                      ? 0
                      : parseFloat(item.exchange),
              }))
            : [];

    return (
        <div className="bg-[#0a192f] p-8 rounded-[40px] shadow-2xl border border-white/5 relative overflow-hidden">
            {/* Header */}
            <div className="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 relative z-10">
                <div>
                    <h4 className="text-white font-black text-xl italic tracking-tighter uppercase">
                        {isEn
                            ? "Market & Currency Correlation"
                            : "Korelasi Pasar & Valuta"}
                    </h4>
                    <p className="text-gray-400 text-[10px] font-bold tracking-[0.2em] uppercase mt-1">
                        NY/ICE Cotton Index vs USD/IDR Exchange Rate
                    </p>
                </div>
                <div className="flex gap-4 mt-4 md:mt-0">
                    <div className="flex items-center gap-2 px-3 py-1 bg-yellow-500/10 rounded-full border border-yellow-500/20">
                        <span className="h-1.5 w-1.5 rounded-full bg-yellow-500"></span>
                        <span className="text-[10px] font-black text-yellow-500 uppercase">
                            Cotton
                        </span>
                    </div>
                    <div className="flex items-center gap-2 px-3 py-1 bg-emerald-500/10 rounded-full border border-emerald-500/20">
                        <span className="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span className="text-[10px] font-black text-emerald-400 uppercase">
                            USD/IDR
                        </span>
                    </div>
                </div>
            </div>

            <div className="w-full relative z-10" style={{ height: "400px" }}>
                <ResponsiveContainer width="100%" height="100%">
                    <AreaChart
                        data={safeData}
                        margin={{ top: 40, right: 20, left: -20, bottom: 20 }}
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
                                    stopOpacity={0.3}
                                />
                                <stop
                                    offset="95%"
                                    stopColor="#ebb308"
                                    stopOpacity={0}
                                />
                            </linearGradient>
                            <linearGradient
                                id="colorEx"
                                x1="0"
                                y1="0"
                                x2="0"
                                y2="1"
                            >
                                <stop
                                    offset="5%"
                                    stopColor="#10b981"
                                    stopOpacity={0.2}
                                />
                                <stop
                                    offset="95%"
                                    stopColor="#10b981"
                                    stopOpacity={0}
                                />
                            </linearGradient>
                        </defs>

                        <CartesianGrid
                            strokeDasharray="3 3"
                            vertical={false}
                            stroke="#ffffff10"
                        />
                        {/* SUMBU X - Menampilkan Tanggal */}
                        <XAxis
                            dataKey="month"
                            axisLine={{ stroke: "#ffffff30", strokeWidth: 1 }} // Garis sumbu terlihat
                            tickLine={{ stroke: "#ffffff30" }}
                            tick={{
                                fontSize: 10,
                                fill: "#94a3b8",
                                fontWeight: "bold",
                            }}
                            dy={15}
                        />

                        {/* SUMBU Y KIRI - Untuk Harga Kapas ($) */}
                        <YAxis
                            yAxisId="left"
                            orientation="left"
                            axisLine={{ stroke: "#ebb308", strokeWidth: 1 }} // Warna Kuning sesuai Kapas
                            tickLine={false}
                            tick={{
                                fontSize: 10,
                                fill: "#ebb308",
                                fontWeight: "bold",
                            }}
                            domain={["dataMin - 1", "dataMax + 1"]}
                            dx={-10}
                        />

                        {/* SUMBU Y KANAN - Untuk Kurs (Rp) */}
                        <YAxis
                            yAxisId="right"
                            orientation="right"
                            axisLine={{ stroke: "#10b981", strokeWidth: 1 }} // Warna Hijau sesuai Kurs
                            tickLine={false}
                            tick={{
                                fontSize: 10,
                                fill: "#10b981",
                                fontWeight: "bold",
                            }}
                            domain={["dataMin - 100", "dataMax + 100"]}
                            dx={10}
                        />

                        <Tooltip
                            contentStyle={{
                                backgroundColor: "#0f172a",
                                border: "1px solid #ffffff10",
                                borderRadius: "12px",
                            }}
                            itemStyle={{ fontSize: "10px", fontWeight: "bold" }}
                        />

                        {/* Grafik Kapas (Kuning) */}
                        <Area
                            yAxisId="left"
                            type="monotone"
                            dataKey="price"
                            stroke="#ebb308"
                            strokeWidth={3}
                            fill="url(#colorCotton)"
                        >
                            <LabelList
                                dataKey="price"
                                position="top"
                                content={(props) => (
                                    <text
                                        x={props.x}
                                        y={props.y - 12}
                                        fill="#ebb308"
                                        fontSize={11}
                                        fontWeight="900"
                                        textAnchor="middle"
                                    >
                                        ${props.value}
                                    </text>
                                )}
                            />
                        </Area>

                        {/* Grafik Kurs (Hijau) */}
                        <Area
                            yAxisId="right"
                            type="monotone"
                            dataKey="exchange"
                            stroke="#10b981"
                            strokeWidth={2}
                            strokeDasharray="5 5"
                            fill="url(#colorEx)"
                        >
                            <LabelList
                                dataKey="exchange"
                                position="bottom"
                                content={(props) => (
                                    <text
                                        x={props.x}
                                        y={props.y + 20}
                                        fill="#10b981"
                                        fontSize={9}
                                        fontWeight="bold"
                                        textAnchor="middle"
                                    >
                                        Rp{props.value.toLocaleString("id-ID")}
                                    </text>
                                )}
                            />
                        </Area>
                    </AreaChart>
                </ResponsiveContainer>
            </div>

            <div className="mt-4 flex justify-between items-center opacity-40">
                <p className="text-[8px] text-gray-500 font-bold uppercase tracking-widest italic">
                    Digestex Intelligence Stream v2.0
                </p>
            </div>
        </div>
    );
};

export default CottonCurrencyTrendChart;
