import { AlertCircle, Download } from "lucide-react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, usePage } from "@inertiajs/react";
import html2canvas from "html2canvas";
import {
    XAxis,
    YAxis,
    CartesianGrid,
    Tooltip,
    ResponsiveContainer,
    AreaChart,
    Area,
    BarChart,
    Bar,
} from "recharts";

const data = [
    { month: "Jan", price: 68.5 },
    { month: "Feb", price: 70.2 },
    { month: "Mar", price: 72.1 },
    { month: "Apr", price: 71.31 },
];

const labels = {
    performance_title: {
        id: "Tren Kinerja 5 Tahun",
        en: "5-Year Performance Trend",
    },
    comparison_title: {
        id: "Perbandingan Tahunan",
        en: "Year-on-Year Comparison",
    },
    surplus_msg: {
        id: "Industri dalam kondisi SURPLUS. Pertahankan efisiensi hulu.",
        en: "Industry is in SURPLUS. Maintain upstream efficiency.",
    },
    deficit_msg: {
        id: "Peringatan: Defisit pada sektor kain terdeteksi.",
        en: "Warning: Fabric sector deficit detected.",
    },
};

export default function Dashboard({cottonPrice, exportValue, memberStatus }) {
    // FUNGSI CAPTURE LAPORAN
    const { auth } = usePage().props;
    const exportAsImage = async (elementId, fileName) => {
        const element = document.getElementById(elementId);
        const canvas = await html2canvas(element, {
            backgroundColor: "#f9fafb",
        });
        const image = canvas.toDataURL("image/png", 1.0);
        const downloadLink = document.createElement("a");
        downloadLink.href = image;
        downloadLink.download = fileName;
        downloadLink.click();
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-bold leading-tight text-gray-800">
                    Industrial Intelligence Dashboard
                </h2>
            }
        >
            <Head title="Dashboard" />


            <div className="py-12 bg-gray-50">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                   
 
            {/* Selamat datang */}
<div className="bg-gradient-to-r from-blue-700 to-indigo-900 rounded-3xl p-8 mb-8 text-white shadow-2xl relative overflow-hidden">
    <div className="relative z-10">
        <h2 className="text-3xl font-black italic tracking-tighter mb-2">
            WELCOME BACK, {auth.user.name.toUpperCase()}!
        </h2>
        <p className="text-blue-100 opacity-80 font-medium">
            Intelligence system is active. Your encrypted data stream is ready.
        </p>
    </div>
    {/* Dekorasi Abstract di background */}
    <div className="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-20 -mt-20 blur-3xl"></div>
</div>

            {/* Batas Selamat Datang */}                  
                   
                   
                    {/* STATS CARDS */}
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div className="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-yellow-500 text-gray-900">
                            <p className="text-xs font-bold text-gray-400 uppercase">
                                Cotton Price
                            </p>
                            <h3 className="text-2xl font-black">
                                {cottonPrice}{" "}
                                <span className="text-sm font-normal">
                                    USD/lb
                                </span>
                            </h3>
                        </div>
                        <div className="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-blue-600 text-gray-900">
                            <p className="text-xs font-bold text-gray-400 uppercase">
                                National Export
                            </p>
                            <h3 className="text-2xl font-black">
                                ${exportValue} B
                            </h3>
                        </div>
                        <div className="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-green-500 text-gray-900">
                            <p className="text-xs font-bold text-gray-400 uppercase">
                                Status
                            </p>
                            <h3 className="text-lg font-bold text-green-600">
                                {memberStatus}
                            </h3>
                        </div>
                    </div>
                    {/* Link Cepat menuju Intelligence */}
                    <Link
                        href={route("intelligence.center")} // Sesuaikan dengan nama route Bapak
                        className="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 mb-8"
                    >
                        <span className="mr-2">🚀</span> Buka Deep Intelligence
                        Center
                    </Link>

                    {/* --- SIMPAN KODE BILINGUAL WARNING DI SINI --- */}
                    <div className="bg-amber-50 border-l-4 border-amber-400 p-4 my-6 rounded-r-xl">
                        <div className="flex items-center">
                            <AlertCircle
                                className="text-amber-600 mr-3"
                                size={24}
                            />
                            <div>
                                <h4 className="text-sm font-bold text-amber-800 uppercase tracking-wider">
                                    Industrial Insight /{" "}
                                    <span className="italic">
                                        Wawasan Industri
                                    </span>
                                </h4>
                                <p className="text-sm text-amber-700">
                                    Export value to USA remains dominant, but
                                    keep an eye on China's premium growth.
                                </p>
                                <p className="text-xs text-amber-600 italic mt-1">
                                    Nilai ekspor ke AS tetap dominan, namun
                                    perhatikan pertumbuhan premium di Cina.
                                </p>
                            </div>
                        </div>
                    </div>
                    {/* ------------------------------------------- */}

                    {/* AREA YANG AKAN DI-CAPTURE (id="capture-area") */}
                    <div id="capture-area" className="p-4 rounded-3xl">
                        <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            {/* GRAFIK HARGA KAPAS */}
                            <div className="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                                <h4 className="font-bold text-gray-800 text-lg mb-6 italic">
                                    Cotton Price Analysis
                                </h4>
                                <div className="h-64 w-full">
                                    <ResponsiveContainer
                                        width="100%"
                                        height="100%"
                                    >
                                        <AreaChart data={data}>
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
                                                        stopColor="#ebb308"
                                                        stopOpacity={0.2}
                                                    />
                                                    <stop
                                                        offset="95%"
                                                        stopColor="#ebb308"
                                                        stopOpacity={0}
                                                    />
                                                </linearGradient>
                                            </defs>
                                            <Tooltip
                                                contentStyle={{
                                                    borderRadius: "12px",
                                                    border: "none",
                                                }}
                                            />
                                            <Area
                                                type="monotone"
                                                dataKey="price"
                                                stroke="#ebb308"
                                                strokeWidth={3}
                                                fill="url(#colorPrice)"
                                            />
                                        </AreaChart>
                                    </ResponsiveContainer>
                                </div>
                            </div>

                            {/* GRAFIK TUJUAN EKSPOR */}
                            <div className="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 text-gray-900">
                                <h4 className="font-bold text-gray-800 text-lg mb-6 italic">
                                    Top 5 Export Destinations
                                </h4>
                                <div className="h-64 w-full">
                                    <ResponsiveContainer
                                        width="100%"
                                        height="100%"
                                    >
                                        <BarChart
                                            data={[
                                                { name: "USA", value: 4.2 },
                                                { name: "Japan", value: 2.1 },
                                                { name: "China", value: 1.8 },
                                                { name: "Germany", value: 1.5 },
                                                { name: "India", value: 1.2 },
                                            ]}
                                        >
                                            <XAxis
                                                dataKey="name"
                                                tick={{ fontSize: 11 }}
                                                axisLine={false}
                                                tickLine={false}
                                            />
                                            <Tooltip
                                                cursor={{ fill: "#f9fafb" }}
                                                contentStyle={{
                                                    borderRadius: "12px",
                                                    border: "none",
                                                }}
                                            />
                                            <Bar
                                                dataKey="value"
                                                fill="#2563eb"
                                                radius={[5, 5, 0, 0]}
                                                barSize={30}
                                            />
                                        </BarChart>
                                    </ResponsiveContainer>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Tambahan tombol form bursa */}
                    {/* TOMBOL AKSES BURSA (Tambahan untuk Kantor) */}
                    <div className="mb-10">
                        <Link
                            href={route("inventory.create")}
                            className="flex items-center justify-between p-8 bg-yellow-500 rounded-[35px] group hover:scale-[1.02] transition-all shadow-2xl shadow-yellow-500/20"
                        >
                            <div className="flex items-center gap-6">
                                <div className="bg-[#0a192f] text-white p-4 rounded-2xl shadow-lg">
                                    <i className="fas fa-plus text-xl"></i>
                                </div>
                                <div>
                                    <h4 className="text-[#0a192f] font-black text-xl uppercase leading-tight">
                                        Post to Bursa Bahan
                                    </h4>
                                    <p className="text-[#0a192f]/60 text-xs font-bold uppercase tracking-widest mt-1">
                                        Upload sisa produksi / inventori Anda
                                    </p>
                                </div>
                            </div>
                            <div className="hidden md:block text-[#0a192f]/40 group-hover:translate-x-2 transition-transform">
                                <i className="fas fa-chevron-right text-2xl"></i>
                            </div>
                        </Link>
                    </div>

                    {/* TOMBOL AKSI */}
                    <div className="mt-8 flex justify-end">
                        <button
                            onClick={() =>
                                exportAsImage(
                                    "capture-area",
                                    "Digestex-Market-Intelligence",
                                )
                            }
                            className="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-full font-bold shadow-xl transition-all flex items-center"
                        >
                            CAPTURE & SHARE TO WHATSAPP
                        </button>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
