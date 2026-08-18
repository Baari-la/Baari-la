import { useState } from "react";
import { usePage } from "@inertiajs/react";
import TopDestinationGrid from "@/Components/Trade/Countries/TopDestinationGrid";

export default function ExecutiveIntelligenceTabs({
    fiberCountries = [],
    yarnCountries = [],
    fabricCountries = [],
    apparelCountries = [],
}) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const [activeTab, setActiveTab] = useState("fiber");

    const sectors = [
        {
            key: "fiber",
            title: isEn ? "Fiber" : "Serat",
            icon: "🌾",
        },
        {
            key: "yarn",
            title: isEn ? "Yarn" : "Benang",
            icon: "🧵",
        },
        {
            key: "fabric",
            title: isEn ? "Fabric" : "Kain",
            icon: "🧶",
        },
        {
            key: "apparel",
            title: isEn ? "Apparel" : "Apparel",
            icon: "👔",
        },
    ];

    const sectorData = {
        fiber: {
            title: isEn
                ? "Top Export Destination Countries for Fiber"
                : "Negara Tujuan Ekspor Utama untuk Serat",

            subtitle: isEn
                ? "January–June 2026 compared with January–June 2025"
                : "Januari–Juni 2026 dibandingkan dengan Januari–Juni 2025",

            footerText: isEn
                ? "Explore Full Fiber Intelligence in Tier A"
                : "Lihat Full Fiber Intelligence di Tier A",

            countries: fiberCountries,
        },

        yarn: {
            title: isEn
                ? "Top Export Destination Countries for Yarn"
                : "Negara Tujuan Ekspor Utama untuk Benang",

            subtitle: isEn
                ? "January–June 2026 compared with January–June 2025"
                : "Januari–Juni 2026 dibandingkan dengan Januari–Juni 2025",

            footerText: isEn
                ? "Explore Full Yarn Intelligence in Tier A"
                : "Lihat Full Yarn Intelligence di Tier A",

            countries: yarnCountries,
        },

        fabric: {
            title: isEn
                ? "Top Export Destination Countries for Fabric"
                : "Negara Tujuan Ekspor Utama untuk Kain",

            subtitle: isEn
                ? "January–June 2026 compared with January–June 2025"
                : "Januari–Juni 2026 dibandingkan dengan Januari–Juni 2025",

            footerText: isEn
                ? "Explore Full Fabric Intelligence in Tier A"
                : "Lihat Full Fabric Intelligence di Tier A",

            countries: fabricCountries,
        },

        apparel: {
            title: isEn
                ? "Top Export Destination Countries for Apparel"
                : "Negara Tujuan Ekspor Utama untuk Apparel",

            subtitle: isEn
                ? "January–June 2026 compared with January–June 2025"
                : "Januari–Juni 2026 dibandingkan dengan Januari–Juni 2025",

            footerText: isEn
                ? "Explore Full Apparel Intelligence with PCS Analysis in Tier A"
                : "Lihat Full Apparel Intelligence dengan Analisis PCS di Tier A",

            countries: apparelCountries,
        },
    };

    const activeSector = sectorData[activeTab];

    return (
        <section className="py-16">
            <div className="max-w-7xl mx-auto px-6">
                {/* HEADER */}
                <div className="mb-10">
                    <div className="text-xs font-black tracking-[0.4em] text-yellow-500">
                        {isEn
                            ? "DIGESTEX EXECUTIVE INTELLIGENCE"
                            : "DIGESTEX EXECUTIVE INTELLIGENCE"}
                    </div>

                    <h2 className="mt-4 text-4xl font-black text-white">
                        {isEn
                            ? "Global Textile Executive Intelligence"
                            : "Global Textile Executive Intelligence"}
                    </h2>

                    <p className="mt-4 max-w-3xl text-gray-400 leading-relaxed">
                        {isEn
                            ? "Explore a public preview of textile export and import intelligence across the global value chain, with deeper trade analysis available through Tier A."
                            : "Jelajahi preview publik export dan import intelligence di seluruh rantai nilai industri tekstil, dengan analisis perdagangan yang lebih mendalam tersedia melalui Tier A."}
                    </p>

                    {/* DATA PERIOD */}
                    <div className="mt-5 inline-flex rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs font-bold text-slate-400">
                        {isEn
                            ? "Data through June 2026"
                            : "Data sampai Juni 2026"}
                    </div>
                </div>

                {/* TABS */}
                <div className="mb-8 flex flex-wrap gap-3">
                    {sectors.map((sector) => (
                        <button
                            key={sector.key}
                            type="button"
                            onClick={() => setActiveTab(sector.key)}
                            className={`
                                rounded-2xl
                                px-5
                                py-3
                                font-bold
                                transition-all
                                ${
                                    activeTab === sector.key
                                        ? "bg-yellow-500 text-black shadow-lg"
                                        : "bg-slate-800 text-white hover:bg-slate-700"
                                }
                            `}
                        >
                            {sector.icon} {sector.title}
                        </button>
                    ))}
                </div>

                {/* ACTIVE INTELLIGENCE */}
                <TopDestinationGrid
                    title={activeSector.title}
                    subtitle={activeSector.subtitle}
                    footerText={activeSector.footerText}
                    countries={activeSector.countries}
                />

                {/* TIER A TEASER */}
                <div className="mt-8 rounded-3xl border border-yellow-500/20 bg-gradient-to-r from-yellow-500/10 to-transparent p-6">
                    <div className="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
                        <div className="max-w-4xl">
                            <div className="text-xs font-black uppercase tracking-[0.25em] text-yellow-500">
                                {isEn
                                    ? "TIER A TRADE INTELLIGENCE"
                                    : "TIER A TRADE INTELLIGENCE"}
                            </div>

                            <p className="mt-2 text-sm leading-relaxed text-slate-300">
                                {isEn
                                    ? activeTab === "apparel"
                                        ? "Tier A provides deeper Apparel Intelligence including HS 8-digit analysis, export value, KG, PCS, destination markets, growth, and detailed market signals."
                                        : "Tier A provides deeper trade intelligence including HS 8-digit analysis, export value, KG, destination markets, growth, and detailed market signals."
                                    : activeTab === "apparel"
                                      ? "Tier A menyediakan Apparel Intelligence yang lebih mendalam mencakup analisis HS 8 digit, nilai ekspor, KG, PCS, negara tujuan, pertumbuhan, dan market signals."
                                      : "Tier A menyediakan trade intelligence yang lebih mendalam mencakup analisis HS 8 digit, nilai ekspor, KG, negara tujuan, pertumbuhan, dan market signals."}
                            </p>
                        </div>

                        <div className="shrink-0">
                            <span className="inline-flex rounded-xl bg-yellow-500 px-5 py-3 text-xs font-black uppercase tracking-wider text-black">
                                {isEn ? "TIER A" : "TIER A"}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}
