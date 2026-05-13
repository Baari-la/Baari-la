import React, { useState, useEffect } from "react";
import { Head, router, Link, usePage } from "@inertiajs/react";
import Navbar from "@/Components/Navbar";
import Footer from "@/Components/Footer";
import NewsSection from "@/Components/Home/NewsSection";
import PartnerLogo from "@/Components/Home/PartnerLogo";
import VissionMission from "@/Components/Home/VissionMission";
import LocalSolutions from "@/Components/Home/LocalSolutions";
import EventSpotlight from "@/Components/EventSpotlight";
import BenefitsSection from "@/Components/Home/BenefitsSection";
import PartnerSponsorship from "@/Components/PartnerSponsorship";
import SponsorSlider from "@/Components/SponsorSlider";
import StockTicker from "@/Components/StockTicker";
import InaugurationPopup from "@/Components/InaugurationPopup";
import GarmentExportTable from "@/Components/Home/GarmentExportTable";
import CottonCurrencyTrendChart from "@/Components/CottonCurrencyTrendChart";
import FiberComparisonChart from "@/Components/FiberComparisonChart";

export default function Home(props) {
    // 1. Ambil data trade mentah
    const garmentTrade = props.garmentTrade || { export_pcs: 0, import_pcs: 0 };
    const totalGarment = garmentTrade?.export_pcs || 0;

    // 2. Satukan semua destructuring agar tidak tumpang tindih
    const {
        auth,
        marketHistory = [],
        fiberIntelligence = [], // <--- Pastikan ini satu grup di sini
        currentCotton = "0.00",
        currentExchange = "0",
        topProducts = [],
        topStocks = [],
        latestNews = [],
        locale,
    } = props;

    // 4. States & Localization
    const [keyword, setKeyword] = useState("");
    const isEn = locale === "en" || auth?.user?.locale === "en";

    // Di dalam Home.jsx
    useEffect(() => {
        console.log("Cek Data Serat:", props.fiberIntelligence);
    }, [props.fiberIntelligence]);

    // 5. Hooks
    useEffect(() => {
        const intendedUrl = sessionStorage.getItem("intended_url");
        if (auth.user && intendedUrl) {
            sessionStorage.removeItem("intended_url");
            const timeout = setTimeout(() => {
                router.get(intendedUrl);
            }, 300);
            return () => clearTimeout(timeout);
        }
    }, [auth.user]);

    // 6. Functions
    const handleSearch = (e) => {
        e.preventDefault();
        router.get(route("companies.index"), { search: keyword });
    };

    return (
        <div className="bg-[#0a192f] min-h-screen text-white font-sans selection:bg-yellow-500 selection:text-[#0a192f]">
            <Head
                title={
                    isEn
                        ? "Digestex V2 - Intelligence Console"
                        : "Digestex V2 - Konsol Intelijen"
                }
            />

            {/* --- 1. TICKER - STICKY TOP --- */}
            <div className="bg-[#0a192f] border-b border-white/5 py-2 overflow-hidden sticky top-0 z-[60] backdrop-blur-md">
                <div className="flex animate-marquee whitespace-nowrap">
                    <div className="flex items-center">
                        <span className="flex items-center gap-2 font-black text-yellow-500 text-[10px] uppercase mx-8">
                            <span className="h-1.5 w-1.5 rounded-full bg-yellow-500 animate-ping"></span>
                            {isEn
                                ? "LIVE MARKET INTELLIGENCE"
                                : "INTELIJEN PASAR LANGSUNG"}
                        </span>
                        <span className="font-bold text-white text-[10px] uppercase mx-8">
                            NY/ICE COTTON:{" "}
                            <span className="text-yellow-500">
                                ${currentCotton}
                            </span>{" "}
                            USD/LB
                        </span>
                        <span className="font-bold text-white text-[10px] uppercase mx-8 border-l border-white/10 pl-8">
                            {isEn ? "EXCHANGE RATE" : "KURS USD/IDR"}:
                            <span className="text-emerald-400 ml-2">
                                Rp{" "}
                                {parseFloat(currentExchange).toLocaleString(
                                    "id-ID",
                                )}
                            </span>
                        </span>
                    </div>
                    {/* Duplikat untuk Loop */}
                    <div className="flex items-center">
                        <span className="flex items-center gap-2 font-black text-yellow-500 text-[10px] uppercase mx-8">
                            <span className="h-1.5 w-1.5 rounded-full bg-yellow-500 animate-ping"></span>
                            {isEn
                                ? "LIVE MARKET INTELLIGENCE"
                                : "INTELIJEN PASAR LANGSUNG"}
                        </span>
                        <span className="font-bold text-white text-[10px] uppercase mx-8">
                            NY/ICE COTTON:{" "}
                            <span className="text-yellow-500">
                                ${currentCotton}
                            </span>{" "}
                            USD/LB
                        </span>
                    </div>
                </div>
                <style
                    dangerouslySetInnerHTML={{
                        __html: `@keyframes marquee-home { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } } .animate-marquee { display: flex; animation: marquee-home 30s linear infinite; min-width: 200%; }`,
                    }}
                />
            </div>

            <main className="flex-1 overflow-hidden relative">
                <Navbar auth={auth} />
                <InaugurationPopup isEn={isEn} />
                <StockTicker topStocks={topStocks} />

                <div
                    className="mt-20 block w-full"
                    style={{ minHeight: "350px" }}
                >
                    <div className="max-w-7xl mx-auto">
                        <CottonCurrencyTrendChart
                            data={marketHistory}
                            isEn={isEn}
                        />
                    </div>
                </div>
                {/* Perbandingan Impor Kapas dan Sintetis */}
                <FiberComparisonChart
                    data={fiberIntelligence}
                    isEn={isEn}
                    isLoggedIn={props.isLoggedIn} // <--- Kirim status login di sini
                />

                {/* --- SECTION: HERO & SEARCH --- */}
                <div className="px-6 pt-20 pb-16 text-center relative overflow-hidden">
                    <div className="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full bg-blue-600/10 blur-[120px] rounded-full pointer-events-none"></div>
                    <h1 className="text-6xl md:text-8xl font-black mb-6 leading-none uppercase tracking-tighter italic relative z-10">
                        {isEn ? "Textile Industry " : "Big Data Industri "}
                        <span className="text-yellow-500 leading-none block md:inline">
                            {isEn ? "Big Data" : "Pertekstilan"}
                        </span>
                    </h1>
                    <p className="text-gray-400 text-xs font-bold uppercase tracking-[0.4em] mb-12 opacity-70">
                        {isEn
                            ? "National Digital Intelligence Hub"
                            : "Pusat Intelijen Digital Nasional"}
                    </p>

                    <form
                        onSubmit={handleSearch}
                        className="max-w-3xl mx-auto mb-10 relative z-10 px-4"
                    >
                        <div className="bg-white/5 backdrop-blur-xl border border-white/10 p-2 rounded-[30px] shadow-2xl flex items-center group focus-within:border-yellow-500/50 transition-all">
                            <div className="pl-6 text-yellow-500/50 group-focus-within:text-yellow-500">
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
                            <button className="bg-yellow-500 text-[#0a192f] font-black px-10 py-4 rounded-[22px] text-[10px] uppercase tracking-widest shadow-xl hover:bg-yellow-400 transition-all">
                                {isEn ? "EXPLORE" : "JELAJAHI"}
                            </button>
                        </div>
                    </form>
                </div>

                {/* --- SECTION: TOP PRODUCTS & PAYWALL --- */}
                <div className="px-6 mb-24">
                    <div className="max-w-7xl mx-auto relative">
                        <div
                            className={`transition-all duration-700 ${!auth.user ? "blur-sm grayscale-[0.5]" : ""}`}
                        >
                            <GarmentExportTable
                                topProducts={topProducts}
                                totalGarment={totalGarment}
                                garmentTrade={garmentTrade}
                                auth={auth}
                                isEn={isEn}
                            />
                        </div>

                        {/* Paywall Overlay */}
                        {!auth.user && (
                            <div className="absolute inset-0 bg-gradient-to-t from-[#0a192f] via-[#0a192f]/80 to-transparent flex flex-col items-center justify-end pb-20 p-6 text-center z-20">
                                <div className="bg-[#0f172a]/90 backdrop-blur-2xl p-10 rounded-[40px] border border-white/10 shadow-2xl max-w-lg">
                                    <div className="bg-yellow-500 w-16 h-16 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-yellow-500/20">
                                        <i className="fas fa-lock text-[#0a192f] text-2xl"></i>
                                    </div>
                                    <h4 className="text-2xl font-black text-white uppercase italic tracking-tighter">
                                        {isEn
                                            ? "Unlock Industrial Insight"
                                            : "Buka Wawasan Industri"}
                                    </h4>
                                    <p className="text-gray-400 text-sm my-6 leading-relaxed">
                                        {isEn
                                            ? "Access 1,200+ HS Codes, complete 30-day historical market trends, and advanced industrial calculators."
                                            : "Akses 1.200+ HS Code, tren pasar historis 30 hari lengkap, dan kalkulator industri canggih."}
                                    </p>
                                    <Link
                                        href={route("login")}
                                        className="inline-block bg-yellow-500 text-[#0a192f] px-12 py-4 rounded-full font-black text-[10px] uppercase tracking-widest hover:bg-yellow-400 transition-all shadow-xl"
                                    >
                                        {isEn
                                            ? "Get Premium Access"
                                            : "Dapatkan Akses Premium"}
                                    </Link>
                                </div>
                            </div>
                        )}
                    </div>
                </div>

                <PartnerSponsorship isEn={isEn} />
                <SponsorSlider />
                <PartnerLogo />
                <NewsSection latestNews={latestNews} isEn={isEn} />
                <EventSpotlight />
                <LocalSolutions />
                <VissionMission />
                <BenefitsSection isEn={isEn} />

                <Footer />
            </main>
        </div>
    );
}
