import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, usePage, router, Link } from "@inertiajs/react";
import React, { useState } from "react";

export default function Index({ partners = [], filters = {} }) {
    const { auth, locale } = usePage().props;
    const isEn = locale === "en" || auth?.user?.locale === "en";
    const memberStatus = auth?.user?.member_status || "Free";

    // State Filter Dinamis
    const [category, setCategory] = useState(filters.category || "");
    const [region, setRegion] = useState(filters.region || "");

    const handleFilterSubmit = (e) => {
        e.preventDefault();
        router.get(
            route("matchmaking.index"),
            { category, region },
            { preserveState: true },
        );
    };

    // PROTEKSI AKSES PREMIUM KONEKSI KEMITRAAN B2B
    const handleConnect = (whatsappNumber, partnerName) => {
        if (!memberStatus.includes("Premium")) {
            alert(
                isEn
                    ? `🔒 Premium Partnership Gate\nRequesting connection with "${partnerName}" is restricted for Free accounts. Please upgrade to Premium.`
                    : `🔒 Gerbang Kemitraan Terkunci\nPengajuan koneksi bisnis dengan "${partnerName}" eksklusif untuk Anggota Premium API. Silakan hubungi admin.`,
            );
            return;
        }
        window.open(
            `https://wa.me{whatsappNumber}?text=Halo%20${encodeURIComponent(partnerName)},%20kami%20tertarik%20untuk%20menjajaki%20B2B%20Partnership%20melalui%20sistem%20Matchmaking%20Digestex%20V2.`,
            "_blank",
        );
    };

    return (
        <AuthenticatedLayout>
            <Head
                title={
                    isEn
                        ? "Advanced B2B Matchmaking"
                        : "Pusat Perjodohan Bisnis Multi-Sektor"
                }
            />

            <div className="py-12 bg-[#0a192f] min-h-screen text-white">
                <div className="max-w-7xl mx-auto px-6 lg:px-8">
                    {/* --- HEADER SECTION DENGAN SINKRONISASI TOMBOL DAFTAR VENDOR --- */}
                    <div className="mb-12 flex flex-col md:flex-row justify-between items-start md:items-end gap-6 border-b border-white/5 pb-6">
                        <div>
                            <span className="text-yellow-500 text-[10px] font-black uppercase tracking-[0.4em] mb-4 block">
                                {isEn
                                    ? "Advanced B2B Synergy Ecosystem"
                                    : "Ekosistem Sinergi B2B Tingkat Lanjut"}
                            </span>
                            <h1 className="text-5xl font-black uppercase tracking-tighter italic">
                                Business{" "}
                                <span className="text-yellow-500">
                                    Matchmaking
                                </span>
                            </h1>
                            <p className="text-gray-400 mt-4 max-w-2xl leading-relaxed text-sm">
                                {isEn
                                    ? "Intelligent gateway connecting local textile manufacturers with global technology, premium machinery suppliers, and top-tier raw material vendors."
                                    : "Gerbang cerdas menghubungkan manufaktur tekstil lokal dengan teknologi global, penyuplai mesin/sparepart premium, dan vendor bahan baku utama."}
                            </p>
                        </div>

                        {/* Tombol Akses Pintas Menuju Formulir Input Baru */}
                        <div className="w-full md:w-auto">
                            <Link
                                href={route("matchmaking.create")}
                                className="w-full md:w-auto bg-gradient-to-r from-amber-500 to-yellow-500 text-[#0a192f] font-black px-6 py-4 rounded-xl uppercase text-[9px] tracking-widest hover:from-amber-400 hover:to-yellow-400 transition-all shadow-lg shadow-yellow-500/10 hover:scale-105 duration-300 text-center block whitespace-nowrap"
                            >
                                <i className="fas fa-plus-circle mr-2"></i>
                                {isEn
                                    ? "List Partnership / Machinery"
                                    : "Daftarkan Kemitraan / Mesin"}
                            </Link>
                        </div>
                    </div>

                    {/* --- INTERACTIVE DYNAMIC FILTERS --- */}
                    <form
                        onSubmit={handleFilterSubmit}
                        className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12 bg-white/5 p-8 rounded-[40px] border border-white/10 backdrop-blur-md"
                    >
                        <div className="space-y-2">
                            <label className="text-[10px] font-black uppercase tracking-widest text-gray-500">
                                {isEn
                                    ? "Partnership Sector"
                                    : "Sektor Kemitraan"}
                            </label>
                            <select
                                value={category}
                                onChange={(e) => setCategory(e.target.value)}
                                className="w-full bg-[#0a192f] border border-white/10 rounded-xl text-xs font-bold p-3 text-white focus:border-yellow-500 focus:outline-none"
                            >
                                <option value="">
                                    {isEn ? "All Sectors" : "Semua Sektor"}
                                </option>
                                <option value="Technology">
                                    {isEn
                                        ? "Technology (PLM/ERP)"
                                        : "Teknologi (PLM/ERP)"}
                                </option>
                                <option value="Machinery">
                                    {isEn
                                        ? "Machinery & Spareparts"
                                        : "Mesin Tekstil & Sparepart"}
                                </option>
                                <option value="Raw Material">
                                    {isEn
                                        ? "Raw Material Supplies"
                                        : "Bahan Baku & Serat"}
                                </option>
                            </select>
                        </div>
                        <div className="space-y-2">
                            <label className="text-[10px] font-black uppercase tracking-widest text-gray-500">
                                {isEn
                                    ? "Geographic Region"
                                    : "Wilayah Operasional"}
                            </label>
                            <select
                                value={region}
                                onChange={(e) => setRegion(e.target.value)}
                                className="w-full bg-[#0a192f] border border-white/10 rounded-xl text-xs font-bold p-3 text-white focus:border-yellow-500 focus:outline-none"
                            >
                                <option value="">
                                    {isEn ? "All Regions" : "Semua Wilayah"}
                                </option>
                                <option value="West Java">
                                    {isEn ? "West Java" : "Jawa Barat"}
                                </option>
                                <option value="Central Java">
                                    {isEn ? "Central Java" : "Jawa Tengah"}
                                </option>
                                <option value="Global">
                                    {isEn
                                        ? "Global Network"
                                        : "Jaringan Global"}
                                </option>
                            </select>
                        </div>
                        <div className="md:col-span-2 flex items-end">
                            <button
                                type="submit"
                                className="w-full bg-yellow-500 text-[#0a192f] font-black py-3.5 rounded-xl uppercase text-[10px] tracking-widest hover:scale-105 transition-all shadow-lg shadow-yellow-500/10 cursor-pointer"
                            >
                                {isEn
                                    ? "Analyze & Find My Partner"
                                    : "Analisis & Cari Mitra Saya"}
                            </button>
                        </div>
                    </form>

                    {/* --- DYNAMIC MATCH RESULTS GRID --- */}
                    {partners.length === 0 ? (
                        <div className="text-center py-20 bg-white/5 border border-white/10 rounded-[40px]">
                            <p className="text-gray-500 text-xs uppercase font-black tracking-widest">
                                {isEn
                                    ? "No partnerships match the selected criteria"
                                    : "Belum ada mitra yang cocok dengan kriteria"}
                            </p>
                        </div>
                    ) : (
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                            {/* --- POTONGAN PERBAIKAN CARD MATCHING DENGAN SPEK LENGKAP VENDOR --- */}
                            {partners.map((partner) => (
                                <div
                                    key={partner.id}
                                    className="group bg-white/5 border border-white/10 p-10 rounded-[50px] hover:border-yellow-500/40 transition-all duration-500 flex flex-col justify-between"
                                >
                                    <div>
                                        {/* Bagian Atas: Logo & % Match */}
                                        <div className="flex justify-between items-start mb-6">
                                            <div className="p-4 bg-white rounded-2xl border border-white/10 w-24 h-16 flex items-center justify-center overflow-hidden shadow-inner">
                                                {partner.logo_path ? (
                                                    <img
                                                        src={partner.logo_path}
                                                        className="h-8 w-auto object-contain"
                                                        alt={partner.name}
                                                    />
                                                ) : (
                                                    <span className="text-[#0a192f] font-black text-xs uppercase font-mono">
                                                        {partner.category.substring(
                                                            0,
                                                            3,
                                                        )}
                                                    </span>
                                                )}
                                            </div>
                                            <span className="bg-emerald-500/20 text-emerald-400 border border-emerald-500/20 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-wider font-mono shadow-md">
                                                {partner.match_percentage}%
                                                Match
                                            </span>
                                        </div>

                                        {/* Judul & Deskripsi Utama */}
                                        <span className="text-[8px] text-gray-500 font-mono uppercase tracking-[0.2em]">
                                            {partner.category} •{" "}
                                            {partner.region}
                                        </span>
                                        <h3 className="text-2xl font-black uppercase mb-2 tracking-tight group-hover:text-yellow-500 transition-colors mt-1">
                                            {partner.name}
                                        </h3>
                                        <p className="text-[11px] text-yellow-500/80 uppercase font-bold tracking-wider mb-3">
                                            {partner.tagline}
                                        </p>
                                        <p className="text-xs text-gray-400 mb-6 leading-relaxed font-medium">
                                            {partner.description}
                                        </p>

                                        {/* --- DETIL PANEL VALIDASI TEKNIS B2B (KOLOM BARU) --- */}
                                        <div className="mt-6 pt-6 border-t border-white/5 grid grid-cols-1 sm:grid-cols-2 gap-4 text-[11px] mb-8 bg-black/20 p-5 rounded-3xl border border-white/5">
                                            <div className="space-y-1">
                                                <p className="text-gray-500 text-[8px] font-black uppercase tracking-widest">
                                                    {isEn
                                                        ? "Minimum Order (MOQ)"
                                                        : "Batas Minimum Order (MOQ)"}
                                                </p>
                                                <p className="text-gray-300 font-bold">
                                                    <i className="fas fa-shopping-bag text-yellow-500/30 mr-1.5"></i>
                                                    {partner.moq_info ||
                                                        (isEn
                                                            ? "Contact for details"
                                                            : "Nego langsung")}
                                                </p>
                                            </div>
                                            <div className="space-y-1">
                                                <p className="text-gray-500 text-[8px] font-black uppercase tracking-widest">
                                                    {isEn
                                                        ? "Supply Capacity"
                                                        : "Kapasitas Pasokan Maksimal"}
                                                </p>
                                                <p className="text-gray-300 font-bold">
                                                    <i className="fas fa-chart-line text-emerald-500/30 mr-1.5"></i>
                                                    {partner.capacity_info ||
                                                        (isEn
                                                            ? "High Capacity Grade"
                                                            : "Skala Industri Besar")}
                                                </p>
                                            </div>
                                            <div className="space-y-1 sm:col-span-2 pt-2 border-t border-white/5">
                                                <p className="text-gray-500 text-[8px] font-black uppercase tracking-widest">
                                                    {isEn
                                                        ? "Track Record Portfolio"
                                                        : "Rekam Jejak Klien Utama"}
                                                </p>
                                                <p className="text-gray-300 font-bold leading-normal">
                                                    <i className="fas fa-check-circle text-blue-500/30 mr-1.5"></i>
                                                    {partner.clients_portfolio ||
                                                        "Mitra Resmi Jaringan API"}
                                                </p>
                                            </div>
                                            <div className="space-y-1 sm:col-span-2 pt-2 border-t border-white/5">
                                                <p className="text-gray-500 text-[8px] font-black uppercase tracking-widest">
                                                    {isEn
                                                        ? "After-Sales & Warranty Support"
                                                        : "Jaminan Purnajual & Garansi"}
                                                </p>
                                                <p className="text-yellow-500/90 font-black italic">
                                                    <i className="fas fa-shield-alt text-amber-500/30 mr-1.5"></i>
                                                    {partner.after_sales_sla ||
                                                        "SLA Terjamin Korporasi"}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    {/* Tombol Kontak WA */}
                                    <button
                                        onClick={() =>
                                            handleConnect(
                                                partner.whatsapp_contact,
                                                partner.name,
                                            )
                                        }
                                        className="bg-white/10 text-white w-full py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest group-hover:bg-yellow-500 group-hover:text-[#0a192f] transition-all duration-300 hover:scale-[1.02] cursor-pointer shadow-lg"
                                    >
                                        {isEn
                                            ? "Request Partnership Connection"
                                            : "Ajukan Koneksi Kemitraan"}
                                    </button>
                                </div>
                            ))}
                        </div>
                    )}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
