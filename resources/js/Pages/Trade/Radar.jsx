import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import {
    BarChart,
    Bar,
    XAxis,
    YAxis,
    CartesianGrid,
    Tooltip,
    ResponsiveContainer,
    Cell,
    LineChart,
    Line,
    PieChart,
    Pie, // Pastikan PieChart & Pie di-import
} from "recharts";

export default function Radar({
    topTrade,
    countries,
    hscodes,
    yearlyTrends,
    topCountries,
}) {
    // 1. Data untuk Grafik Donat (Top 5 Negara)
    const countryData = topCountries.map((item) => ({
        name: item.nama_negara,
        value: parseFloat(item.total_nilai) / 1000000,
    }));

    // 2. Data untuk Grafik Garis (Tren 5 Tahun)
    const trendData = [
        {
            year: "2021",
            val: parseFloat(yearlyTrends?.["2021"] || 0) / 1000000,
        },
        {
            year: "2022",
            val: parseFloat(yearlyTrends?.["2022"] || 0) / 1000000,
        },
        {
            year: "2023",
            val: parseFloat(yearlyTrends?.["2023"] || 0) / 1000000,
        },
        {
            year: "2024",
            val: parseFloat(yearlyTrends?.["2024"] || 0) / 1000000,
        },
        {
            year: "2025",
            val: parseFloat(yearlyTrends?.["2025"] || 0) / 1000000,
        },
    ];

    // 3. Data untuk Grafik Batang (Top Produk)
    const chartData = topTrade.map((item) => ({
        name: item.kategori_nama,
        value: (parseFloat(item.total_nilai) || 0) / 1000000,
    }));

    const COLORS = ["#0a192f", "#eab308", "#3b82f6", "#10b981", "#f59e0b"];

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-black text-[#0a192f] uppercase tracking-tighter">
                    Trade Intelligence Radar
                </h2>
            }
        >
            <Head title="Trade Radar" />

            <div className="py-12 bg-gray-50 min-h-screen">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                    {/* Header Info */}
                    <div className="bg-[#0a192f] p-8 rounded-[40px] text-white shadow-2xl relative overflow-hidden">
                        <div className="relative z-10">
                            <span className="bg-yellow-500 text-[#0a192f] px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">
                                Global Supply Chain Monitor
                            </span>
                            <h1 className="text-4xl font-black mt-4 tracking-tighter uppercase">
                                Trade Analytics
                            </h1>
                            <p className="text-blue-200 mt-2 font-medium max-w-2xl uppercase text-[10px] tracking-widest">
                                Monitoring Volume & Nilai Strategis Nasional
                                2021-2025.
                            </p>
                        </div>
                    </div>

                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {/* BOX 1: GRAFIK TREN 5 TAHUN */}
                        {/* BOX 1: GRAFIK TREN 5 TAHUN */}
                        <div className="bg-white p-8 rounded-[40px] shadow-sm border border-gray-100">
                            <h3 className="text-xs font-black text-[#0a192f] mb-6 uppercase tracking-widest">
                                Growth Performance (Million USD)
                            </h3>
                            <div className="h-[250px] w-full">
                                <ResponsiveContainer width="100%" height="100%">
                                    <LineChart data={trendData}>
                                        <CartesianGrid
                                            strokeDasharray="3 3"
                                            vertical={false}
                                            stroke="#f0f0f0"
                                        />
                                        <XAxis
                                            dataKey="year"
                                            tick={{
                                                fontSize: 12,
                                                fontWeight: "bold",
                                            }}
                                        />

                                        {/* INPUT BARU DISINI */}
                                        <YAxis
                                            domain={["auto", "auto"]}
                                            tick={{ fontSize: 10 }}
                                            tickFormatter={(value) =>
                                                `${value.toFixed(1)}M`
                                            }
                                        />

                                        <Tooltip
                                            contentStyle={{
                                                borderRadius: "20px",
                                                border: "none",
                                            }}
                                            formatter={(value) => [
                                                `$${value.toFixed(2)} Million`,
                                                "Value",
                                            ]} // Cukup format desimal
                                        />
                                        <Line
                                            type="monotone"
                                            dataKey="val"
                                            stroke="#eab308"
                                            strokeWidth={4}
                                            dot={{ r: 6, fill: "#0a192f" }}
                                        />
                                    </LineChart>
                                </ResponsiveContainer>
                            </div>
                        </div>

                        {/* BOX 2: TOP 5 NEGARA (DONUT CHART) */}
                        <div className="bg-white p-8 rounded-[40px] shadow-sm border border-gray-100">
                            <h3 className="text-xs font-black text-[#0a192f] mb-6 uppercase tracking-widest">
                                Top 5 Export Destinations 2025
                            </h3>
                            <div className="h-[250px] w-full">
                                <ResponsiveContainer width="100%" height="100%">
                                    <PieChart>
                                        <Pie
                                            data={countryData}
                                            innerRadius={60}
                                            outerRadius={80}
                                            paddingAngle={5}
                                            dataKey="value"
                                        >
                                            {countryData.map((entry, index) => (
                                                <Cell
                                                    key={`cell-${index}`}
                                                    fill={
                                                        COLORS[
                                                            index %
                                                                COLORS.length
                                                        ]
                                                    }
                                                />
                                            ))}
                                        </Pie>
                                        <Tooltip />
                                    </PieChart>
                                </ResponsiveContainer>
                            </div>
                        </div>

                        {/* BOX 3: TOP PRODUCTS (FULL WIDTH) */}
                        <div className="lg:col-span-2 bg-white p-8 rounded-[40px] shadow-sm border border-gray-100">
                            <h3 className="text-xs font-black text-[#0a192f] mb-6 uppercase tracking-widest">
                                Top Product Categories 2025
                            </h3>
                            <div className="h-[300px] w-full">
                                <ResponsiveContainer width="100%" height="100%">
                                    <BarChart
                                        data={chartData}
                                        layout="vertical"
                                    >
                                        <XAxis type="number" hide />
                                        <YAxis
                                            dataKey="name"
                                            type="category"
                                            width={120}
                                            tick={{
                                                fontSize: 10,
                                                fontWeight: "bold",
                                            }}
                                        />
                                        <Tooltip />
                                        <Bar
                                            dataKey="value"
                                            fill="#0a192f"
                                            radius={[0, 10, 10, 0]}
                                        />
                                    </BarChart>
                                </ResponsiveContainer>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
