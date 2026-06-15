import WebsiteLayout from "@/Layouts/WebsiteLayout";
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
import IndustryDirectorySnapshot from "@/Components/Home/IndustryDirectorySnapshot";
import SourcingHubPreview from "@/Components/Home/SourcingHubPreview";
import FeaturedPartnerBanner from "@/Components/Home/FeaturedPartnerBanner";
import IndustrySolutionsSection from "@/Components/Home/IndustrySolutionsSection";
import SponsoredInsightSection from "@/Components/Home/SponsoredInsightSection";
export default function Home(props) {
    // 1. Ambil data trade mentah
    const garmentTrade = props.garmentTrade || { export_pcs: 0, import_pcs: 0 };
    const totalGarment = garmentTrade?.export_pcs || 0;

    // 2. Satukan semua destructuring agar tidak tumpang tindih
    const {
        auth,
        directoryStats,
        featuredPartner,
        industrySolutions = [],
        marketHistory = [],
        fiberIntelligence = [], // <--- Pastikan ini satu grup di sini
        currentCotton = "0.00",
        currentExchange = "0",
        topProducts = [],
        topStocks = [],
        latestNews = [],
        latestRegulations = [],
        isLoggedIn,
        locale,
    } = props;

    // 4. States & Localization
    const [keyword, setKeyword] = useState("");
    const isEn = locale === "en" || auth?.user?.locale === "en";
    const memberStatus = auth?.user?.member_status || "Free";

    // Fungsi pengaman klik unduh dokumen di halaman depan
    const handleDownload = (fileUrl, tier, title) => {
        if (tier === "Premium" && !memberStatus.includes("Premium")) {
            alert(
                isEn
                    ? `❌ Premium Tier Required for: ${title}`
                    : `❌ Akun Premium Diperlukan untuk: ${title}`,
            );
            return;
        }
        window.open(`/storage/${fileUrl}`, "_blank");
    };

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
        <WebsiteLayout>
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
                    {/* <Navbar auth={auth} /> */}
                    <InaugurationPopup isEn={isEn} />
                    <StockTicker topStocks={topStocks} />

                    {/* --- SECTION: HERO & SEARCH --- */}
                    <div className="px-6 pt-20 pb-16 text-center relative overflow-hidden">
                        <div className="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full bg-blue-600/10 blur-[120px] rounded-full pointer-events-none"></div>
                        <h1 className="text-6xl md:text-8xl font-black mb-6 leading-none uppercase tracking-tighter italic relative z-10">
                            {isEn ? "Textile Industry " : "Ekosistem Industri "}
                            <span className="text-yellow-500 leading-none block md:inline">
                                {isEn ? "Ecosystem" : "Tekstil Terintegrasi"}
                            </span>
                        </h1>
                        <div className="max-w-4xl mx-auto mb-12 relative z-10">
                            <p className=" text-white text-lg md:text-2xl font-bold leading-relaxed tracking-tight">
                                {isEn
                                    ? "Connecting Industry, Sourcing Hub, and Market Intelligence across the textile supply chain."
                                    : "Menghubungkan Industri, Solusi, Pasar, dan Peluang."}
                            </p>
                            <p
                                className="
            mt-4
            text-gray-400
            text-sm
            md:text-base
            leading-relaxed
            max-w-3xl
            mx-auto
        "
                            >
                                {isEn
                                    ? "Built to support collaboration, innovation, and sustainable growth across the textile value chain."
                                    : "Dibangun untuk mendukung kolaborasi, inovasi, dan pertumbuhan berkelanjutan di seluruh rantai nilai industri tekstil."}
                            </p>
                        </div>
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
                        {/* Hero KPI Banner */}

                        <div className="relative z-10 max-w-5xl mx-auto px-4">
                            <div className="grid grid-cols-2 md:grid-cols-4 gap-4 mt-10">
                                <div className="rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl p-5 text-center">
                                    <div className="text-yellow-500 text-2xl mb-3">
                                        <i className="fas fa-building" />
                                    </div>
                                    <div className="text-3xl font-black text-yellow-500">
                                        {Number(
                                            directoryStats?.companies ?? 0,
                                        ).toLocaleString()}
                                        +
                                    </div>

                                    <div className="flex items-center justify-center gap-2 text-xs uppercase tracking-widest text-gray-400 mt-2">
                                        <span>
                                            {isEn ? "Companies" : "Perusahaan"}
                                        </span>
                                    </div>
                                </div>

                                <div className="rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl p-5 text-center">
                                    <div className="text-yellow-500 text-2xl mb-3">
                                        <i className="fas fa-box" />
                                    </div>

                                    <div className="text-3xl font-black text-yellow-500">
                                        {Number(
                                            directoryStats?.products ?? 0,
                                        ).toLocaleString()}
                                        +
                                    </div>

                                    <div className="text-xs uppercase tracking-widest text-gray-400 mt-2">
                                        {isEn ? "Products" : "Produk"}
                                    </div>
                                </div>

                                <div className="rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl p-5 text-center">
                                    <div className="text-yellow-500 text-2xl mb-3">
                                        <i className="fas fa-earth-asia" />
                                    </div>

                                    <div className="text-3xl font-black text-yellow-500">
                                        {Number(
                                            directoryStats?.markets ?? 0,
                                        ).toLocaleString()}
                                        +
                                    </div>

                                    <div className="text-xs uppercase tracking-widest text-gray-400 mt-2">
                                        {isEn ? "Markets" : "Pasar"}
                                    </div>
                                </div>

                                <div className="rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl p-5 text-center">
                                    <div className="text-yellow-500 text-2xl mb-3">
                                        <i className="fas fa-ship" />
                                    </div>

                                    <div className="text-3xl font-black text-yellow-500">
                                        {Number(
                                            directoryStats?.exportCompanies ??
                                                0,
                                        ).toLocaleString()}
                                        +
                                    </div>

                                    <div className="text-xs uppercase tracking-widest text-gray-400 mt-2">
                                        {isEn ? "Exporters" : "Eksportir"}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <FeaturedPartnerBanner isEn={isEn} />

                    <IndustryDirectorySnapshot
                        isEn={isEn}
                        stats={directoryStats}
                    />
                    <IndustrySolutionsSection isEn={isEn} />

                    <SourcingHubPreview />
                    <section className="pt-24">
                        <div className="max-w-7xl mx-auto px-6 text-center mb-16">
                            <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                                MARKET INTELLIGENCE
                            </span>

                            <h2 className="text-4xl md:text-6xl font-black text-white mt-4 uppercase">
                                {isEn
                                    ? "Insights For Better Decisions"
                                    : "Wawasan Untuk Keputusan Yang Lebih Baik"}
                            </h2>

                            <p className="max-w-3xl mx-auto mt-6 text-gray-400">
                                {isEn
                                    ? "Trade analytics, sourcing intelligence, market trends, and strategic insights supporting manufacturers, suppliers, buyers, and industry stakeholders."
                                    : "Analitik perdagangan, intelijen sourcing, tren pasar, dan wawasan strategis untuk mendukung produsen, pemasok, pembeli, dan pemangku kepentingan industri."}
                            </p>
                            <div className="grid md:grid-cols-3 gap-8 max-w-7xl mx-auto px-6 mt-16">
                                {/* Trade Intelligence */}

                                <div className="rounded-[32px]border border-white/10 bg-white/5 backdrop-blur-xl p-8 hover:border-yellow-500/30 transition-all duration-500">
                                    <i className="fas fa-chart-line text-yellow-500 text-4xl mb-6" />

                                    <h3 className="text-2xl font-black text-white mb-4">
                                        Trade Intelligence
                                    </h3>

                                    <ul className="space-y-3 text-gray-400 text-sm">
                                        <li>Import & Export Analytics</li>

                                        <li>Trade Flow Monitoring</li>

                                        <li>Market Access Insights</li>
                                    </ul>
                                </div>

                                {/* Supply Chain Insights */}

                                <div
                                    className=" rounded-[32px]  border border-white/10 bg-white/5 backdrop-blur-xl
        p-8 hover:border-yellow-500/30 transition-all duration-500"
                                >
                                    <i className="fas fa-industry text-yellow-500 text-4xl mb-6" />

                                    <h3 className="text-2xl font-black text-white mb-4">
                                        Sourcing Intelligence
                                    </h3>

                                    <ul className="space-y-3 text-gray-400 text-sm">
                                        <li>Raw Material Trends</li>

                                        <li>MOQ Intelligence</li>

                                        <li>Supplier Discovery</li>
                                    </ul>
                                </div>

                                {/* Market Trends */}

                                <div className="rounded-[32px]border border-white/10 bg-white/5 backdrop-blur-xl p-8 hover:border-yellow-500/30 transition-all duration-500">
                                    <i className="fas fa-globe-asia text-yellow-500 text-4xl mb-6" />

                                    <h3 className="text-2xl font-black text-white mb-4">
                                        Strategic Intelligence
                                    </h3>

                                    <ul className="space-y-3 text-gray-400 text-sm">
                                        <li>Price Monitoring</li>

                                        <li>Industry Updates</li>

                                        <li>Executive Briefings</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </section>
                    {/* Batas Sourcing Hub dan Market Intelligecnce*/}

                    {/* <MarketIntelligenceSection isEn={isEn} /> */}

                    <SponsoredInsightSection isEn={isEn} />

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
                    <FiberComparisonChart
                        data={fiberIntelligence}
                        isEn={isEn}
                        isLoggedIn={props.isLoggedIn} // <--- Kirim status login di sini
                    />
                    {/* --- SECTION: TOP PRODUCTS & PAYWALL --- */}
                    <div className="px-6 mb-24">
                        <div className="max-w-7xl mx-auto relative">
                            <div
                                className={`transition-all duration-700 ${!auth.user ? "blur-sm grayscale-[0.5]" : ""}`}
                            >
                                {/* Perbandingan Impor Kapas dan Sintetis */}
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

                    <section className="py-24 border-t border-white/5">
                        <div className="max-w-7xl mx-auto px-6">
                            <div className="text-center mb-16">
                                <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                                    PARTNERS & ECOSYSTEM
                                </span>

                                <h2 className="text-4xl md:text-6xl font-black text-white mt-4 uppercase">
                                    Strategic Partners
                                </h2>

                                <p className="max-w-3xl mx-auto mt-6 text-gray-400">
                                    Collaborating with industry associations,
                                    institutions, technology providers, and
                                    ecosystem stakeholders.
                                </p>
                            </div>

                            <div className="grid md:grid-cols-4 gap-6 mb-20">
                                <div className="rounded-3xl border border-white/10 bg-white/5 p-8 text-center">
                                    <i className="fas fa-handshake text-yellow-500 text-4xl mb-5" />

                                    <h3 className="text-white font-black uppercase mb-3">
                                        Industry Associations
                                    </h3>

                                    <p className="text-gray-400 text-sm">
                                        Textile, garment, footwear, and industry
                                        organizations.
                                    </p>
                                </div>

                                <div className="rounded-3xl border border-white/10 bg-white/5 p-8 text-center">
                                    <i className="fas fa-graduation-cap text-yellow-500 text-4xl mb-5" />

                                    <h3 className="text-white font-black uppercase mb-3">
                                        Research & Education
                                    </h3>

                                    <p className="text-gray-400 text-sm">
                                        Universities, research centers, and
                                        training institutions.
                                    </p>
                                </div>

                                <div className="rounded-3xl border border-white/10 bg-white/5 p-8 text-center">
                                    <i className="fas fa-microchip text-yellow-500 text-4xl mb-5" />

                                    <h3 className="text-white font-black uppercase mb-3">
                                        Technology Partners
                                    </h3>

                                    <p className="text-gray-400 text-sm">
                                        Digital platforms, software providers,
                                        and innovation partners.
                                    </p>
                                </div>

                                <div className="rounded-3xl border border-white/10 bg-white/5 p-8 text-center">
                                    <i className="fas fa-globe-asia text-yellow-500 text-4xl mb-5" />

                                    <h3 className="text-white font-black uppercase mb-3">
                                        Supporting Organizations
                                    </h3>

                                    <p className="text-gray-400 text-sm">
                                        Government agencies, NGOs, development
                                        partners, and institutions.
                                    </p>
                                </div>
                            </div>
                            {/* <PartnerSponsorship />
                            <SponsorSlider />
                            <PartnerLogo /> */}
                        </div>
                    </section>
                    <NewsSection latestNews={latestNews} isEn={isEn} />
                    {/* <EventSpotlight /> */}
                    <LocalSolutions
                        materials={props.regulations || []}
                        inventoryItems={props.inventoryItems || []}
                        partnershipItems={props.partnershipItems || []}
                        isLoggedIn={props.isLoggedIn}
                        memberStatus={props.auth?.user?.member_status || "Free"}
                        isEn={isEn}
                        /* PERBAIKAN UTAMA: Teruskan objek auth ke dalam komponen */
                        auth={props.auth}
                    />
                </main>
            </div>
        </WebsiteLayout>
    );
}
