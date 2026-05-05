import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, usePage, Link } from "@inertiajs/react";
import GarmentsCalculator from "@/Components/GarmentsCalculator";

export default function GarmentCalculatorPage({ auth }) {
    // TAMBAHKAN BARIS INI UNTUK MENDEFINISIKAN isEn
    const { props } = usePage();
    const isEn = props.locale === "en" || auth?.locale === "en";
    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Premium Industrial Calculator" />

            <div className="py-12 bg-[#0a192f] min-h-screen">
                <div className="max-w-5xl mx-auto px-6">
                    <div className="mb-10">
                        <h1 className="text-4xl font-black text-white uppercase italic mb-2">
                            Industrial{" "}
                            <span className="text-blue-500">Toolbox</span>
                        </h1>
                        <p className="text-slate-400 font-bold uppercase text-[10px] tracking-[0.3em]">
                            Professional PLM & Logistics Simulation
                        </p>
                    </div>

                    {/* Memanggil Komponen Kalkulator */}
                    <GarmentsCalculator />

                    {/* --- BAGIAN PANDUAN BILINGUAL --- */}
                    <div className="mt-12 bg-blue-600/5 border border-blue-500/20 p-8 rounded-3xl">
                        <h4 className="text-white font-bold mb-4 uppercase text-sm italic tracking-widest">
                            {isEn ? "User Guide / " : ""}
                            <span className="text-blue-400">
                                Panduan Penggunaan:
                            </span>
                        </h4>
                        <ul className="text-slate-400 text-xs space-y-4 list-disc pl-5 font-medium">
                            <li>
                                <span className="text-white font-bold">
                                    {isEn
                                        ? "Select Category"
                                        : "Pilih Kategori"}
                                </span>
                                :
                                {isEn
                                    ? " Choose a product category to adjust raw material consumption coefficients."
                                    : " Pilih kategori produk untuk menyesuaikan koefisien konsumsi bahan baku."}
                            </li>
                            <li>
                                <span className="text-white font-bold">
                                    {isEn ? "Set Target" : "Tentukan Target"}
                                </span>
                                :
                                {isEn
                                    ? " Input production target (pieces) according to your Purchase Order (PO)."
                                    : " Masukkan target produksi (pieces) sesuai dengan Purchase Order (PO)."}
                            </li>
                            <li>
                                <span className="text-white font-bold">
                                    {isEn
                                        ? "Automated Calculation"
                                        : "Kalkulasi Otomatis"}
                                </span>
                                :
                                {isEn
                                    ? " The system will automatically calculate fabric estimates and 20ft container requirements."
                                    : " Sistem akan otomatis menghitung estimasi kain dan kebutuhan kontainer 20ft."}
                            </li>
                            <li>
                                <span className="text-white font-bold">
                                    {isEn ? "Export Report" : "Ekspor Laporan"}
                                </span>
                                :
                                {isEn
                                    ? " Use the 'Download PDF' button to get an official production plan report."
                                    : " Gunakan tombol 'Download PDF' untuk mendapatkan laporan resmi rencana produksi."}
                            </li>
                        </ul>
                    </div>
                </div>
                {/* --- TOMBOL KEMBALI (EXECUTIVE DESIGN) --- */}
                <div className="mt-16 flex justify-center pb-10">
                    <Link
                        href={route("home")}
                        className="group relative flex items-center gap-4 bg-white/5 hover:bg-white/10 border border-white/10 px-8 py-4 rounded-2xl transition-all duration-500 shadow-[0_0_20px_rgba(59,130,246,0.1)] hover:shadow-[0_0_30px_rgba(59,130,246,0.3)]"
                    >
                        {/* Ikon Panah dengan Circle Blue Glow */}
                        <div className="w-12 h-12 rounded-xl bg-blue-600 flex items-center justify-center shadow-[0_0_15px_rgba(37,99,235,0.5)] group-hover:scale-110 transition-transform duration-500">
                            <span className="text-white text-xl group-hover:-translate-x-1 transition-transform">
                                ←
                            </span>
                        </div>

                        <div className="flex flex-col items-start">
                            <span className="text-[10px] font-black uppercase tracking-[0.3em] text-blue-400 group-hover:text-blue-300 transition-colors">
                                {isEn ? "RETURN TO" : "KEMBALI KE"}
                            </span>
                            <span className="text-sm font-black uppercase italic text-white tracking-tight">
                                Intelligence{" "}
                                <span className="text-blue-500">Center</span>
                            </span>
                        </div>

                        {/* Efek Garis Cahaya di Bawah */}
                        <div className="absolute bottom-0 left-1/2 -translate-x-1/2 w-0 h-[2px] bg-blue-500 group-hover:w-1/2 transition-all duration-500"></div>
                    </Link>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
