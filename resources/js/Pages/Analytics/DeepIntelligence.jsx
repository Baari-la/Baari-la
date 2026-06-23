import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import IndustrialAnalyticsChart from "@/Components/IndustrialAnalyticsChart";
import MonthlyYoYChart from "@/Components/MonthlyYoYChart";
import TopMarketChart from "@/Components/TopMarketChart";
import { Link, usePage } from "@inertiajs/react"; // Tambahkan Link & usePage
import { useState, useEffect } from "react"; // Tambahkan useState & useEffect
import { motion, AnimatePresence } from "framer-motion"; // Untuk animasi sukses
import LocalInsightPremium from "@/Components/LocalInsightPremium";
import WelcomeMessage from "@/Components/WelcomeMessage";

export default function DeepIntelligence({
    auth,
    industrialData,
    regions,
    topCountries,
    riskHeatmap = [],
    comparisonData,
    flash = {},
    company,
}) {
    const [showSuccess, setShowSuccess] = useState(false);
    const isEn = auth?.user?.locale === "en";

    useEffect(() => {
        if (flash?.message) {
            setShowSuccess(true);
        }
    }, [flash]);

    return (
        <AuthenticatedLayout user={auth.user}>
            <div className="py-12 bg-[#0a192f] min-h-screen">
                <div className="max-w-7xl mx-auto px-6">
                    <WelcomeMessage isEn={isEn} />
                    {/* BARIS 1: TOMBOL UPDATE DATA (ACTION AREA) */}
                    {auth.user?.company_id && (
                        <div className="mb-10 bg-gradient-to-r from-yellow-500/10 to-transparent border border-yellow-500/20 p-8 rounded-[40px] flex flex-col md:flex-row justify-between items-center gap-6 backdrop-blur-md">
                            <div className="flex items-center gap-5">
                                <div className="bg-yellow-500/20 p-4 rounded-2xl">
                                    <i className="fas fa-industry text-yellow-500 text-2xl"></i>
                                </div>
                                <div>
                                    <h4 className="text-white text-xs font-black uppercase tracking-[0.2em] mb-1">
                                        Corporate Intelligence Update
                                    </h4>
                                    <p className="text-gray-400 text-[10px] font-bold uppercase italic leading-tight">
                                        {isEn
                                            ? "Validate your manufacturing profile to strengthen national textile mapping."
                                            : "Validasi profil manufaktur Anda untuk memperkuat pemetaan industri pertekstilan nasional."}
                                    </p>
                                </div>
                            </div>
                            <Link
                                href={route(
                                    "companies.edit",
                                    auth.user.company_id,
                                )}
                                className="bg-yellow-500 text-[#0a192f] px-10 py-4 rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-yellow-400 transition-all shadow-xl flex items-center gap-3"
                            >
                                <i className="fas fa-edit"></i>{" "}
                                {isEn
                                    ? "Update My Company Profile"
                                    : "Mutakhirkan Profil Perusahaan"}
                            </Link>
                        </div>
                    )}

                    {/* BARIS 2: LIVE INDUSTRY TICKER */}
                    <div className="mb-12 bg-white/5 border-y border-white/10 py-4 overflow-hidden flex gap-10 whitespace-nowrap">
                        <div className="flex gap-10 animate-marquee uppercase text-[9px] font-black tracking-widest text-emerald-400">
                            <span>• Global Cotton Price: -1.2%</span>
                            <span>• EU Textile Regulation Update 2026</span>
                            <span>• New Tech: AI-Powered Dyeing Process</span>
                            <span>• Indonesia Export Target: $15B</span>
                        </div>
                    </div>

                    {/* BARIS 3: MARKET STATUS CARDS (DUAL MARKET STRATEGY) */}
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                        {/* Market Mastery Card */}
                        <div className="bg-gradient-to-br from-emerald-600/20 to-[#0d1d36] p-10 rounded-[50px] border border-emerald-500/20 relative overflow-hidden group">
                            <div className="relative z-10">
                                <h4 className="text-emerald-400 text-[10px] font-black uppercase tracking-[0.4em] mb-4">
                                    {company?.pasar_ekspor
                                        ? "Global Trade Dominance"
                                        : "Domestic Market Mastery"}
                                </h4>
                                <h3 className="text-white text-3xl font-black italic uppercase leading-none mb-6">
                                    Market{" "}
                                    <span className="text-emerald-500">
                                        Stability
                                    </span>
                                </h3>
                                <span className="text-5xl font-black text-white italic">
                                    {company?.pasar_ekspor
                                        ? "Export Ready"
                                        : "Local Hero"}
                                </span>
                            </div>
                            <i
                                className={`fas ${company?.pasar_ekspor ? "fa-globe-americas" : "fa-map-marked-alt"} absolute -right-10 -bottom-10 text-[180px] opacity-5 text-white`}
                            ></i>
                        </div>

                        {/* Supply Chain Card */}
                        <div className="bg-white/5 p-10 rounded-[50px] border border-white/10 flex flex-col justify-between">
                            <div>
                                <h4 className="text-gray-500 text-[10px] font-black uppercase tracking-[0.4em] mb-4 italic">
                                    Supply Chain Integrity
                                </h4>
                                <div className="flex items-center gap-4">
                                    <div className="h-12 w-12 bg-blue-500/20 rounded-2xl flex items-center justify-center border border-blue-500/30">
                                        <i className="fas fa-truck-loading text-blue-500"></i>
                                    </div>
                                    <p className="text-white font-bold text-sm uppercase italic">
                                        {company?.pasar_ekspor
                                            ? "Optimal Global Supply Link"
                                            : "Penyokong Utama Rantai Pasok Nasional"}
                                    </p>
                                </div>
                            </div>
                            <p className="text-[10px] text-gray-500 font-medium leading-relaxed mt-6">
                                Sistem Digestex memantau integritas 8-digit Anda
                                untuk efisiensi pasar{" "}
                                {company?.pasar_ekspor ? "Global" : "Domestik"}.
                            </p>
                        </div>
                    </div>

                    {/* BARIS 4: CORE ANALYTICS (GRAFIK UTAMA) */}
                    <div className="mb-12 min-h-[400px]">
                        <IndustrialAnalyticsChart
                            data={industrialData}
                            regions={regions}
                        />
                    </div>

                    {/* 2. ERM RISK HEATMAP (Radar Risiko) */}
                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-12">
                        {riskHeatmap && riskHeatmap.length > 0 ? (
                            riskHeatmap.map((risk, index) => (
                                <div
                                    key={index}
                                    className="bg-white/5 border border-white/10 p-5 rounded-3xl backdrop-blur-xl"
                                >
                                    <div className="flex justify-between items-center mb-3">
                                        <span className="text-[9px] font-black text-slate-500 uppercase tracking-widest">
                                            Risk Status
                                        </span>
                                        <div
                                            className={`w-2.5 h-2.5 rounded-full animate-pulse ${risk.color}`}
                                        ></div>
                                    </div>
                                    <h4 className="text-white font-black text-xs uppercase truncate">
                                        {risk.name}
                                    </h4>
                                    <p
                                        className={`text-[10px] font-extrabold mt-1 uppercase ${risk.status === "AT RISK" ? "text-red-500" : "text-emerald-500"}`}
                                    >
                                        {risk.status}
                                    </p>
                                </div>
                            ))
                        ) : (
                            <div className="text-slate-500 text-xs font-bold uppercase animate-pulse">
                                Initializing ERM Radar...
                            </div>
                        )}
                    </div>

                    {/* BARIS 5: SECONDARY CHARTS */}
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                        <TopMarketChart data={topCountries} />
                        <MonthlyYoYChart data={comparisonData} />
                    </div>

                    {/* BARIS 6: PREMIUM INSIGHTS (LOCAL UMKM FOCUS) */}
                    <LocalInsightPremium
                        isEn={isEn}
                        isPremium={auth.user.role === "premium"}
                    />

                    {/* 3. CHARTS AREA (Grafik Utama) */}
                    {/* <div className="min-h-[400px] w-full mb-12">
                        <IndustrialAnalyticsChart
                            data={industrialData}
                            regions={regions}
                        />
                    </div> */}

                    {/* 4. SECONDARY ANALYSIS (Top Market & Comparison) */}
                    {/* <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                        <TopMarketChart data={topCountries} />
                        <MonthlyYoYChart data={comparisonData} />
                    </div> */}

                    {/* 5. LOCAL INSIGHT (Premium Value untuk UMKM/Lokal) */}
                </div>{" "}
                {/* Penutup max-w-7xl */}
            </div>{" "}
            {/* Penutup py-12 */}
            {/* MODAL SUKSES (AnimatePresence tetap di sini) */}
            {/* MODAL SUKSES (CUSTOM INTELLIGENCE MODAL) */}
            <AnimatePresence>
                {showSuccess && (
                    <div className="fixed inset-0 z-50 flex items-center justify-center p-6 bg-[#0a192f]/90 backdrop-blur-2xl">
                        <motion.div
                            initial={{ opacity: 0, scale: 0.9 }}
                            animate={{ opacity: 1, scale: 1 }}
                            exit={{ opacity: 0, scale: 0.9 }}
                            className="max-w-md w-full bg-[#0d1d36] border border-yellow-500/20 rounded-[50px] p-12 text-center relative overflow-hidden"
                        >
                            <div className="relative mb-8 flex justify-center">
                                <div className="h-20 w-20 bg-yellow-500 rounded-full flex items-center justify-center shadow-[0_0_30px_rgba(234,179,8,0.4)]">
                                    <i className="fas fa-check text-3xl text-[#0a192f]"></i>
                                </div>
                                <div className="absolute inset-0 h-20 w-20 border-4 border-yellow-500/30 rounded-full animate-ping mx-auto"></div>
                            </div>
                            <h3 className="text-xl font-black italic uppercase text-white mb-4 tracking-tighter">
                                {isEn ? "Data Transmitted" : "Data Terkirim"}
                            </h3>
                            <p className="text-gray-400 text-[11px] leading-relaxed mb-10 font-medium italic">
                                {isEn
                                    ? "Your industrial update has been queued for professional audit. High integrity data ensures better global visibility."
                                    : "Pembaruan industri Anda telah masuk antrean audit profesional. Integritas data yang tinggi menjamin visibilitas global yang lebih baik."}
                            </p>
                            <button
                                onClick={() => setShowSuccess(false)}
                                className="w-full bg-white text-black py-4 rounded-2xl font-black uppercase text-[10px] tracking-[0.3em] hover:bg-yellow-500 transition-all"
                            >
                                {isEn
                                    ? "Proceed to Dashboard"
                                    : "Masuk ke Dashboard"}
                            </button>
                            <div className="absolute bottom-0 left-0 h-1 bg-yellow-500 w-full">
                                <motion.div
                                    initial={{ width: "100%" }}
                                    animate={{ width: "0%" }}
                                    transition={{ duration: 4 }}
                                    className="h-full bg-[#0a192f]/50"
                                />
                            </div>
                        </motion.div>
                    </div>
                )}
            </AnimatePresence>
        </AuthenticatedLayout>
    );
}
