import React, { useState, useEffect } from "react";
// import { useState } from "react";
import { Head, router, Link, usePage } from "@inertiajs/react";
import Navbar from "@/Components/Navbar";
import Footer from "@/Components/Footer";
import AdsBanner from "@/Components/AdsBanner";
import AdsBannerTestex from "@/Components/AdsBannerTestex";
import MarketChart from "@/Components/Home/MarketChart";
import NewsSection from "@/Components/Home/NewsSection";
import PartnerLogo from "@/Components/Home/PartnerLogo";
import VissionMission from "@/Components/Home/VissionMission";
import LocalSolutions from "@/Components/Home/LocalSolutions";
import EventSpotlight from "@/Components/EventSpotlight";
import BenefitsSection from "@/Components/Home/BenefitsSection";
import PricingSection from "@/Components/Home/PricingSection";
import TechPartnerHub from "@/Components/TechPartnerHub";
import IndustrialAnalyticsChart from "@/Components/IndustrialAnalyticsChart";
import GarmentExportTable from "@/Components/Home/GarmentExportTable";
import PartnerSponsorship from "@/Components/PartnerSponsorship";
import SponsorSlider from "@/Components/SponsorSlider";
import StockTicker from "@/Components/StockTicker";
import PremiumCountdown from "@/Components/PremiumCountdown";
import { motion, AnimatePresence } from "framer-motion";
import confetti from "canvas-confetti";
import InaugurationPopup from "@/Components/InaugurationPopup";



export default function Home({
    marketHistory = [],
    latestMarket,
    latestNews = [],
    cottonPrice,
    topProducts,
    topStocks,
    totalGarment,
    garmentTrade,
    industrialData,
   }) {
    
    const { props } = usePage();
    const auth = props.auth;
    const isEn = props.locale === "en" || auth?.locale === "en";

    const currentCotton = latestMarket?.cotton_price || cottonPrice || "71.31";
    const [keyword, setKeyword] = useState("");

    // REDIRECT KE DIREKTORI DENGAN PARAMETER PENCARIAN
    const handleSearch = (e) => {
        e.preventDefault();
        // Arahkan ke rute companies.index yang baru saja kita rapikan filternya
        router.get(route("companies.index"), { search: keyword });
    };

    useEffect(() => {
        // 1. Check if user is now logged in
        // 2. Check if there is a saved URL in sessionStorage
        const intendedUrl = sessionStorage.getItem('intended_url');

        if (auth.user && intendedUrl) {
            // Remove it immediately so it doesn't trigger again
            sessionStorage.removeItem('intended_url');

            // Small delay (300ms) makes the transition feel smoother 
            // after the page loads
            const timeout = setTimeout(() => {
                router.get(intendedUrl);
            }, 300);

            return () => clearTimeout(timeout);
        }
    }, [auth.user]); // Trigger whenever auth state changes

    return (
        <div className="bg-[#0a192f] min-h-screen text-white font-sans">
            <Head
                title={
                    isEn
                        ? "Digestex V2 - Intelligence Console"
                        : "Digestex V2 - Konsol Intelijen"
                }
            />

            {/* 1. TICKER - STICKY TOP */}
            <div className="bg-yellow-500 py-1 overflow-hidden sticky top-0 z-[60]">
                <marquee className="font-bold text-[#0a192f] text-[10px] uppercase">
                    {isEn
                        ? "Live Market Intelligence"
                        : "Intelijen Pasar Langsung"}
                    : NY/ICE Cotton {currentCotton} USD/LB |
                    {isEn ? " National Export Value" : " Nilai Ekspor Nasional"}{" "}
                    : $11.9 Billion
                </marquee>
            </div>

            <div className="flex flex-col lg:flex-row">
                {/* 2. SIDEBAR - FIXED ON LEFT */}
                <aside className="hidden lg:block w-72 h-screen sticky top-8 bg-[#0a192f] border-r border-white/5 p-6 space-y-10 overflow-y-auto">
                    <div className="mb-10">
                        <img
                            src="/images/logo_api_digestex2.png"
                            className="h-12 w-auto mb-4"
                            alt="Logo"
                        />
                        <span className="text-[10px] font-black text-yellow-500 uppercase tracking-widest block">
                            {isEn
                                ? "Premium Intelligence"
                                : "Intelijen Premium"}
                        </span>
                    </div>

                    <nav className="space-y-8">
                        <div>
                            <p className="text-[9px] font-black text-gray-500 uppercase tracking-[0.3em] mb-4">
                                {isEn ? "Main Console" : "Konsol Utama"}
                            </p>
                            <ul className="space-y-4">
                                <Link
                                    href={route("home")}
                                    className="flex items-center gap-3 text-sm font-bold text-white hover:text-yellow-500 transition group"
                                >
                                    <i className="fas fa-chart-line w-5 text-yellow-500/50 group-hover:text-yellow-500"></i>
                                    {isEn ? "Market Radar" : "Radar Pasar"}
                                </Link>
                                <Link
                                    href={route("companies.index")}
                                    className="flex items-center gap-3 text-sm font-bold text-gray-400 hover:text-white transition group"
                                >
                                    <i className="fas fa-industry w-5 text-gray-500 group-hover:text-yellow-500"></i>
                                    {isEn
                                        ? "Industrial Directory"
                                        : "Direktori Industri"}
                                </Link>
                                <li className="flex items-center gap-3 text-sm font-bold text-gray-400 cursor-not-allowed opacity-50">
                                    <i className="fas fa-shopping-cart w-5"></i>
                                    {isEn ? "Material Bursa" : "Bursa Bahan"}
                                </li>
                            </ul>
                        </div>
                    </nav>

                    <div className="pt-10 border-t border-white/5">
                        <div className="p-4 rounded-3xl bg-white/5 italic text-[10px] text-gray-400 leading-relaxed border border-white/5">
                            {isEn
                                ? '"Supporting Indonesia\'s Textile Digital Transformation with Centric PLM."'
                                : '"Mendukung Transformasi Digital Tekstil Indonesia bersama Centric PLM."'}
                        </div>
                    </div>
                </aside>

                {/* 3. MAIN CONTENT AREA */}
                <main className="flex-1 overflow-hidden relative">
                    <Navbar auth={auth} />
                    <InaugurationPopup isEn={isEn} />
                    <StockTicker topStocks={topStocks} />
                    {/* Tes tampilan tabel */}
                    <div className="mt-20">
                        <PremiumCountdown isEn={isEn} />
                        <GarmentExportTable
                            topProducts={topProducts}
                            totalGarment={totalGarment}
                            garmentTrade={garmentTrade}
                            auth={auth}
                            isEn={isEn}
                        />
                    </div>
                    <PartnerSponsorship isEn={isEn} />
                    <SponsorSlider />
                    {/* DASHBOARD WIDGETS */}
                    <MarketChart
                        data={marketHistory}
                        cottonPrice={currentCotton}
                    />
                    {/* <IndustrialAnalyticsChart data={industrialData} /> */}
                    {/* Tombol untuk grafik lain */}
                    <div className="mt-8 text-center">
                        <Link
                            href={route("intelligence.center")}
                            className="bg-blue-600/20 text-blue-400 border border-blue-600/30 px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-600 hover:text-white transition-all"
                        >
                            Explore Full Industrial Analytics →
                        </Link>
                    </div>

                    {/* HERO & UNIVERSAL SEARCH */}
                    <div className="px-6 pt-24 pb-16 text-center relative overflow-hidden">
                        {/* Efek Cahaya di belakang Search */}
                        <div className="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full bg-blue-600/10 blur-[120px] rounded-full pointer-events-none"></div>

                        <h1 className="text-5xl md:text-8xl font-black mb-8 leading-none uppercase tracking-tighter italic relative z-10">
                            {isEn ? "Textile Industry " : "Big Data Industri "}
                            <span className="text-yellow-500">
                                {isEn ? "Big Data" : "Pertekstilan"}
                            </span>
                        </h1>
                        <p className="text-gray-400 text-xs font-bold uppercase tracking-[0.4em] mb-10 -mt-4 opacity-70">
                            {isEn
                                ? "National Digital Intelligence Hub"
                                : "Pusat Intelijen Digital Nasional"}
                        </p>

                        <form
                            onSubmit={handleSearch}
                            className="max-w-3xl mx-auto mb-16 relative z-10 px-4"
                        >
                            <div className="bg-white/5 backdrop-blur-xl border border-white/10 p-2 rounded-[30px] shadow-2xl flex items-center group focus-within:border-yellow-500/50 transition-all">
                                <div className="pl-6 text-yellow-500/50 group-focus-within:text-yellow-500 transition-colors">
                                    <i className="fas fa-search"></i>
                                </div>
                                <input
                                    type="text"
                                    value={keyword}
                                    onChange={(e) => setKeyword(e.target.value)}
                                    className="block w-full px-6 py-5 bg-transparent border-none text-white text-lg outline-none focus:ring-0 placeholder:text-gray-600"
                                    placeholder={
                                        isEn
                                            ? "Search 1,982+ industries & news..."
                                            : "Cari 1.982+ industri & berita..."
                                    }
                                />
                                <button className="bg-yellow-500 text-[#0a192f] font-black px-10 py-4 rounded-[22px] text-[10px] uppercase tracking-widest shadow-xl hover:bg-yellow-400 transition-all active:scale-95">
                                    {isEn ? "EXPLORE" : "JELAJAHI"}
                                </button>
                            </div>
                            <div className="mt-4 flex justify-center gap-6 opacity-40">
                                <span className="text-[9px] font-black uppercase tracking-widest text-yellow-500">
                                    #Epson
                                </span>
                                <span className="text-[9px] font-black uppercase tracking-widest text-yellow-500">
                                    #US-Export
                                </span>
                                <span className="text-[9px] font-black uppercase tracking-widest text-yellow-500">
                                    #Zipper
                                </span>
                            </div>
                        </form>
                    </div>

                    <div className="px-6 py-8 space-y-6">
                        {/* <AdsBanner /> */}
                        {/* <AdsBannerTestex /> */}
                    </div>

                    <PartnerLogo />

                    {/* ALAT TEMPUR EPSON */}
                    {/* <TechPartnerHub isEn={isEn} /> */}

                    <NewsSection latestNews={latestNews} isEn={isEn} />
                    <EventSpotlight />
                    <LocalSolutions />
                    <VissionMission />
                    <BenefitsSection isEn={isEn} />
                    {/* <PricingSection /> */}
                    <Footer />
                </main>
            </div>
        </div>
    );
}
