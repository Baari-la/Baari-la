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
                : "Negara Tujuan Ekspor Utama Untuk Serat",

            subtitle: isEn
                ? "January–April 2026 compared with January–April 2025"
                : "Januari–April 2026 dibanding Januari–April 2025",

            footerText: isEn
                ? "View Full Fiber Intelligence"
                : "Lihat Intelligence Serat",

            countries: fiberCountries,
        },

        yarn: {
            title: isEn
                ? "Top Export Destination Countries for Yarn"
                : "Negara Tujuan Ekspor Utama Untuk Benang",

            subtitle: isEn
                ? "January–April 2026 compared with January–April 2025"
                : "Januari–April 2026 dibanding Januari–April 2025",

            footerText: isEn
                ? "View Full Yarn Intelligence"
                : "Lihat Intelligence Benang",

            countries: yarnCountries,
        },

        fabric: {
            title: isEn
                ? "Top Export Destination Countries for Fabric"
                : "Negara Tujuan Ekspor Utama Untuk Kain",

            subtitle: isEn
                ? "January–April 2026 compared with January–April 2025"
                : "Januari–April 2026 dibanding Januari–April 2025",

            footerText: isEn
                ? "View Full Fabric Intelligence"
                : "Lihat Intelligence Kain",

            countries: fabricCountries,
        },

        apparel: {
            title: isEn
                ? "Top Export Destination Countries for Apparel"
                : "Negara Tujuan Ekspor Utama Untuk Apparel",

            subtitle: isEn
                ? "January–April 2026 compared with January–April 2025"
                : "Januari–April 2026 dibanding Januari–April 2025",

            footerText: isEn
                ? "View Full Apparel Intelligence"
                : "Lihat Intelligence Apparel",

            countries: apparelCountries,
        },
    };

    return (
        <section className="py-16">
            <div className="max-w-7xl mx-auto px-6">
                {/* Header */}

                <div className="mb-10">
                    <div className="text-xs font-black tracking-[0.4em] text-yellow-500">
                        DIGESTEX EXECUTIVE INTELLIGENCE
                    </div>

                    <h2 className="mt-4 text-4xl font-black">
                        {isEn
                            ? "Global Textile Executive Intelligence"
                            : "Global Textile Executive Intelligence"}
                    </h2>

                    <p className="mt-4 max-w-3xl text-gray-400">
                        {isEn
                            ? "Explore export intelligence across the global textile value chain."
                            : "Eksplorasi intelligence ekspor di seluruh rantai nilai industri tekstil global."}
                    </p>
                </div>

                {/* Tabs */}

                <div className="mb-8 flex flex-wrap gap-3">
                    {sectors.map((sector) => (
                        <button
                            key={sector.key}
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

                {/* Grid */}

                <TopDestinationGrid
                    title={sectorData[activeTab].title}
                    subtitle={sectorData[activeTab].subtitle}
                    footerText={sectorData[activeTab].footerText}
                    countries={sectorData[activeTab].countries}
                />
            </div>
        </section>
    );
}
