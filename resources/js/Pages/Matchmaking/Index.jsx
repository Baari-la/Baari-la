import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, usePage, router } from "@inertiajs/react";
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
            `wa.me{whatsappNumber}?text=Halo%20${encodeURIComponent(partnerName)},%20kami%20tertarik%20untuk%20menjajaki%20B2B%20Partnership%20melalui%20sistem%20Matchmaking%20Digestex%20V2.`,
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
                    {/* --- HEADER SECTION --- */}
                    <div className="mb-12">
                        <span className="text-yellow-500 text-[10px] font-black uppercase tracking-[0.4em] mb-4 block">
                            {isEn
                                ? "Advanced B2B Synergy Ecosystem"
                                : "Ekosistem Sinergi B2B Tingkat Lanjut"}
                        </span>
                        <h1 className="text-5xl font-black uppercase tracking-tighter italic">
                            Business{" "}
                            <span className="text-yellow-500">Matchmaking</span>
                        </h1>
                        <p className="text-gray-400 mt-4 max-w-2xl leading-relaxed text-sm">
                            {isEn
                                ? "Intelligent gateway connecting local textile manufacturers with global technology, premium machinery suppliers, and top-tier raw material vendors."
                                : "Gerbang cerdas menghubungkan manufaktur tekstil lokal dengan teknologi global, penyuplai mesin/sparepart premium, dan vendor bahan baku utama."}
                        </p>
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
                            {partners.map((partner) => (
                                <div
                                    key={partner.id}
                                    className="group bg-white/5 border border-white/10 p-10 rounded-[50px] hover:border-yellow-500/40 transition-all duration-500 flex flex-col justify-between"
                                >
                                    <div>
                                        <div className="flex justify-between items-start mb-6">
                                            <div className="p-4 bg-white rounded-2xl border border-white/10 w-24 h-16 flex items-center justify-center overflow-hidden">
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
                                            <span className="bg-emerald-500/20 text-emerald-400 border border-emerald-500/20 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-wider font-mono">
                                                {partner.match_percentage}%
                                                Match
                                            </span>
                                        </div>
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
                                        <p className="text-xs text-gray-400 mb-8 leading-relaxed font-medium">
                                            {partner.description}
                                        </p>
                                    </div>
                                    <button
                                        onClick={() =>
                                            handleConnect(
                                                partner.whatsapp_contact,
                                                partner.name,
                                            )
                                        }
                                        className="bg-white/10 text-white w-full py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest group-hover:bg-yellow-500 group-hover:text-[#0a192f] transition-all duration-300 hover:scale-[1.02] cursor-pointer"
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
