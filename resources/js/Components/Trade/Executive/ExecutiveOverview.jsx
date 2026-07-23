import { usePage } from "@inertiajs/react";
import { Globe, TrendingUp, AlertTriangle, Brain } from "lucide-react";

export default function ExecutiveOverview({
    dataPeriod = "January-April 2026",
    totalCountries = 195,
    growthMarkets = 0,
    criticalAlerts = 0,
    executiveHeadline = "",
}) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const cards = [
        {
            icon: Globe,
            title: isEn ? "Markets Analyzed" : "Pasar Dianalisis",
            value: totalCountries,
        },

        {
            icon: TrendingUp,
            title: isEn ? "Growth Markets" : "Pasar Bertumbuh",
            value: growthMarkets,
        },

        {
            icon: AlertTriangle,
            title: isEn ? "Critical Alerts" : "Peringatan Kritis",
            value: criticalAlerts,
        },

        {
            icon: Brain,
            title: isEn ? "AI Intelligence" : "AI Intelligence",
            value: "ACTIVE",
        },
    ];

    return (
        <div
            className="
                overflow-hidden
                rounded-3xl
                border
                border-slate-200
                bg-white
                shadow-sm
            "
        >
            {/* Hero */}

            <div
                className="
                    border-b
                    border-slate-200
                    bg-gradient-to-r
                    from-slate-900
                    to-blue-900
                    px-8
                    py-10
                    text-white
                "
            >
                <p className="text-sm uppercase tracking-widest text-blue-200">
                    DIGESTEX
                </p>

                <h1 className="mt-2 text-4xl font-bold">
                    {isEn ? "Executive Intelligence" : "Executive Intelligence"}
                </h1>

                <p className="mt-3 text-blue-100">
                    {isEn ? "Reporting Period" : "Periode Pelaporan"}:{" "}
                    {dataPeriod}
                </p>

                <div
                    className="
                        mt-6
                        rounded-2xl
                        bg-white/10
                        p-5
                        backdrop-blur
                    "
                >
                    <p className="text-sm font-medium text-blue-100">
                        {isEn ? "Executive Insight" : "Ringkasan Eksekutif"}
                    </p>

                    <p className="mt-2 text-lg leading-8 text-white">
                        {executiveHeadline ||
                            (isEn
                                ? "India remains the fastest growing textile market while China continues to dominate the global supply chain."
                                : "India tetap menjadi pasar tekstil dengan pertumbuhan tercepat sementara Tiongkok masih mendominasi rantai pasok tekstil global.")}
                    </p>
                </div>
            </div>

            {/* Stats */}

            <div className="grid gap-5 p-6 md:grid-cols-2 xl:grid-cols-4">
                {cards.map((card) => {
                    const Icon = card.icon;

                    return (
                        <div
                            key={card.title}
                            className="
                                rounded-2xl
                                border
                                border-slate-200
                                p-5
                                transition
                                hover:-translate-y-1
                                hover:shadow-md
                            "
                        >
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-sm text-slate-500">
                                        {card.title}
                                    </p>

                                    <h2
                                        className="
                                            mt-2
                                            text-3xl
                                            font-bold
                                            text-slate-900
                                        "
                                    >
                                        {card.value}
                                    </h2>
                                </div>

                                <div
                                    className="
                                        rounded-2xl
                                        bg-blue-100
                                        p-3
                                    "
                                >
                                    <Icon size={24} className="text-blue-700" />
                                </div>
                            </div>
                        </div>
                    );
                })}
            </div>
        </div>
    );
}
