import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, usePage, router } from "@inertiajs/react";
import React, { useState } from "react";

/* Ambil props 'materials' dan 'filters' yang dikirim dari RegulationController */
export default function Index({ materials = [], filters = {} }) {
    const { auth, locale } = usePage().props;
    const isEn = locale === "en" || auth?.user?.locale === "en";

    // Ambil status keanggotaan user (Default ke Free jika tidak ada)
    const memberStatus = auth?.user?.member_status || "Free";

    // State untuk fitur pencarian kata kunci
    const [search, setSearch] = useState(filters.search || "");

    const handleSearchSubmit = (e) => {
        e.preventDefault();
        router.get(
            route("regulation.index"),
            { search },
            { preserveState: true },
        );
    };

    // FILTER KLIK UNDUH MATERI (PROTEKSI DATA PREMIUM)
    const handleDownload = (fileUrl, tier, title) => {
        const isPremium = memberStatus.includes("Premium");

        if (tier === "Premium" && !isPremium) {
            alert(
                isEn
                    ? `❌ Premium Tier Required\n"${title}" is exclusive for API Premium Members. Please upgrade your subscription.`
                    : `❌ Memerlukan Akun Premium\n"${title}" eksklusif untuk Anggota Premium API. Silakan hubungi admin untuk upgrade.`,
            );
            return;
        }

        // Jalankan unduhan jika lolos verifikasi hak akses premium
        window.open(`/storage/${fileUrl}`, "_blank");
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-bold leading-tight text-gray-800">
                    {isEn
                        ? "Knowledge & Regulation Hub"
                        : "Pusat Materi & Regulasi"}
                </h2>
            }
        >
            <Head title={isEn ? "Trade Regulations" : "Regulasi Perdagangan"} />

            <div className="py-12 bg-gray-50 min-h-screen">
                <div className="max-w-7xl mx-auto px-6 lg:px-8">
                    {/* --- HEADER TITLE --- */}
                    <div className="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                        <h1 className="text-4xl font-black text-[#0a192f] uppercase tracking-tighter italic">
                            {isEn
                                ? "Trade & Industry Regulations"
                                : "Regulasi Perdagangan & Industri"}
                        </h1>

                        {/* --- BAR PENCARIAN DOKUMEN --- */}
                        <form
                            onSubmit={handleSearchSubmit}
                            className="w-full md:w-80 relative"
                        >
                            <input
                                type="text"
                                placeholder={
                                    isEn
                                        ? "Search regulation or speaker..."
                                        : "Cari regulasi atau pembicara..."
                                }
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                className="w-full bg-white border border-gray-300 px-6 py-2.5 rounded-full text-xs font-medium text-gray-800 placeholder-gray-400 focus:outline-none focus:border-yellow-600 transition-all shadow-sm"
                            />
                            <button
                                type="submit"
                                className="absolute right-4 top-3 text-gray-400 hover:text-yellow-600 transition-colors"
                            >
                                <i className="fas fa-search text-xs"></i>
                            </button>
                        </form>
                    </div>

                    {/* --- TABEL DOKUMEN DINAMIS --- */}
                    <div className="bg-white rounded-[40px] shadow-xl overflow-hidden border border-gray-100">
                        <table className="w-full text-left border-collapse">
                            <thead className="bg-[#0a192f] text-white uppercase text-xs tracking-widest">
                                <tr>
                                    <th className="p-6">
                                        {isEn
                                            ? "Regulation / Event Title"
                                            : "Nama Regulasi / Materi"}
                                    </th>
                                    <th className="p-6">
                                        {isEn
                                            ? "Speaker / Source"
                                            : "Pembicara / Sumber"}
                                    </th>
                                    <th className="p-6">
                                        {isEn ? "Tier" : "Akses"}
                                    </th>
                                    <th className="p-6">
                                        {isEn ? "Date" : "Tanggal"}
                                    </th>
                                    <th className="p-6 text-right">
                                        {isEn ? "Action" : "Aksi"}
                                    </th>
                                </tr>
                            </thead>
                            <tbody className="text-sm">
                                {materials.length === 0 ? (
                                    <tr>
                                        <td
                                            colSpan="5"
                                            className="p-10 text-center text-gray-400 font-bold uppercase tracking-wider"
                                        >
                                            {isEn
                                                ? "No documents available"
                                                : "Belum ada dokumen tersedia"}
                                        </td>
                                    </tr>
                                ) : (
                                    materials.map((item) => (
                                        <tr
                                            key={item.id}
                                            className="border-b border-gray-100 hover:bg-yellow-50/40 transition"
                                        >
                                            <td className="p-6 font-bold text-[#0a192f]">
                                                <div className="flex flex-col">
                                                    <span>{item.title}</span>
                                                    <span className="text-[10px] text-gray-400 uppercase tracking-widest mt-1 font-mono font-normal">
                                                        {item.category}
                                                    </span>
                                                </div>
                                            </td>
                                            <td className="p-6 text-gray-600 font-medium">
                                                {item.speaker}
                                            </td>
                                            <td className="p-6">
                                                <span
                                                    className={`text-[9px] font-black uppercase tracking-wider px-3 py-1 rounded-full ${
                                                        item.access_tier ===
                                                        "Premium"
                                                            ? "bg-amber-100 text-amber-800 border border-amber-300"
                                                            : "bg-emerald-100 text-emerald-800"
                                                    }`}
                                                >
                                                    {item.access_tier}
                                                </span>
                                            </td>
                                            <td className="p-6 text-gray-500 font-medium">
                                                {new Date(
                                                    item.event_date,
                                                ).toLocaleDateString(
                                                    isEn ? "en-US" : "id-ID",
                                                    {
                                                        year: "numeric",
                                                        month: "short",
                                                    },
                                                )}
                                            </td>
                                            <td className="p-6 text-right">
                                                <button
                                                    onClick={() =>
                                                        handleDownload(
                                                            item.file_path,
                                                            item.access_tier,
                                                            item.title,
                                                        )
                                                    }
                                                    className="text-yellow-600 font-black uppercase text-[10px] tracking-widest hover:underline cursor-pointer"
                                                >
                                                    {isEn
                                                        ? "Download PDF"
                                                        : "Unduh PDF"}
                                                </button>
                                            </td>
                                        </tr>
                                    ))
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
