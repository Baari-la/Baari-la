import WebsiteLayout from "@/Layouts/WebsiteLayout";
import React, { useState, useEffect } from "react";
import { Head, router, Link, usePage } from "@inertiajs/react";
import MarketTicker from "@/Components/MarketTicker";
import DigitalDirectoryVisibilityBanner from "@/Components/Program/DigitalDirectoryVisibilityBanner";
import UpcomingPreview from "@/Components/Home/UpcomingPreview";
import GlobalEcosystemHero from "@/Components/Home/GlobalEcosystemHero";
import EcosystemPositioning from "@/Components/Home/EcosystemPositioning";
import IndustryTradeIntelligence from "@/Components/Home/IndustryTradeIntelligence";
import TechnologySolutions from "@/Components/Home/TechnologySolutions";
import SourcingBusinessConnectivity from "@/Components/Home/SourcingBusinessConnectivity";
import StrategicEcosystemPartners from "@/Components/Home/StrategicEcosystemPartners";
import FinalEcosystemCTA from "@/Components/Home/FinalEcosystemCTA";

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

        latestIntelligence,
        marketIntelligence,
        tradePolicy,
        sustainability,
        technology,
        industryNews,
        partnerInsights,
        intelligenceStats,

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
    useEffect(() => {}, [props.fiberIntelligence]);

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
                <MarketTicker
                    cotton={currentCotton}
                    exchangeRate={currentExchange}
                />

                <main className="flex-1 overflow-hidden relative">
                    <GlobalEcosystemHero isEn={isEn} />

                    {/* =====================================================
    ECOSYSTEM POSITIONING
===================================================== */}

                    <EcosystemPositioning isEn={isEn} />

                    {/* <Navbar auth={auth} /> */}
                    <DigitalDirectoryVisibilityBanner
                        participatingCompanies={0}
                        verifiedCompanies={0}
                        goldMembers={0}
                    />

                    <div className="px-11">
                        <UpcomingPreview />
                    </div>
                    <IndustryTradeIntelligence
                        isEn={isEn}
                        marketHistory={marketHistory}
                        fiberIntelligence={fiberIntelligence}
                        isLoggedIn={props.isLoggedIn}
                    />

                    <TechnologySolutions isEn={isEn} />
                    <SourcingBusinessConnectivity isEn={isEn} />

                    <StrategicEcosystemPartners isEn={isEn} />

                    <FinalEcosystemCTA isEn={isEn} />
                </main>
            </div>
        </WebsiteLayout>
    );
}
