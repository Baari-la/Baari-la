import { usePage } from "@inertiajs/react";
import { Globe, TrendingUp, Trophy, Building2, MapPinned } from "lucide-react";

const regions = [
    {
        key: "asean",
        title: "ASEAN",
    },
    {
        key: "asia",
        title: "Asia",
    },
    {
        key: "europe",
        title: "Europe",
    },
    {
        key: "america",
        title: "America",
    },
    {
        key: "middle_east",
        title: "Middle East",
    },
    {
        key: "africa",
        title: "Africa",
    },
];

export default function GlobalTextileRadar({
    dataPeriod = "January-April 2026",
    radar = {},
}) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

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
            {/* Header */}

            <div className="border-b border-slate-100 px-6 py-5">
                <div className="flex items-center gap-3">
                    <Globe size={28} className="text-blue-600" />

                    <div>
                        <h2 className="text-2xl font-bold text-slate-900">
                            {isEn
                                ? "Global Textile Radar"
                                : "Radar Tekstil Global"}
                        </h2>

                        <p className="mt-1 text-sm text-slate-500">
                            {isEn ? "Reporting Period" : "Periode Pelaporan"}:{" "}
                            {dataPeriod}
                        </p>
                    </div>
                </div>
            </div>

            {/* Regions */}

            <div className="grid gap-5 p-6 lg:grid-cols-2">
                {regions.map((region) => {
                    const item = radar[region.key] ?? {};

                    return (
                        <div
                            key={region.key}
                            className="
                                rounded-2xl
                                border
                                border-slate-200
                                p-5
                                hover:shadow-md
                                transition
                            "
                        >
                            {/* Title */}

                            <h3
                                className="
                                    text-xl
                                    font-bold
                                    text-slate-900
                                "
                            >
                                {region.title}
                            </h3>

                            <p className="mt-1 text-sm text-slate-500">
                                {item.total_countries ?? 0}{" "}
                                {isEn
                                    ? "Countries Analyzed"
                                    : "Negara Dianalisis"}
                            </p>

                            {/* Executive Headline */}

                            <div
                                className="
                                    mt-5
                                    rounded-2xl
                                    bg-slate-50
                                    p-4
                                "
                            >
                                <p
                                    className="
                                        text-xs
                                        font-bold
                                        uppercase
                                        tracking-wider
                                        text-slate-500
                                    "
                                >
                                    {isEn
                                        ? "Executive Insight"
                                        : "Insight Eksekutif"}
                                </p>

                                <p
                                    className="
                                        mt-2
                                        text-sm
                                        leading-7
                                        text-slate-700
                                    "
                                >
                                    {item.executive_headline ?? "-"}
                                </p>
                            </div>

                            {/* Summary */}

                            <div className="mt-5 grid gap-4 md:grid-cols-3">
                                <SummaryCard
                                    icon={Trophy}
                                    title={
                                        isEn
                                            ? "Largest Market"
                                            : "Pasar Terbesar"
                                    }
                                    value={
                                        item?.regional_summary?.largest_market
                                    }
                                />

                                <SummaryCard
                                    icon={Building2}
                                    title={
                                        isEn
                                            ? "Largest Supplier"
                                            : "Pemasok Terbesar"
                                    }
                                    value={
                                        item?.regional_summary?.largest_supplier
                                    }
                                />

                                <SummaryCard
                                    icon={TrendingUp}
                                    title={
                                        isEn
                                            ? "Fastest Growing"
                                            : "Pertumbuhan Tertinggi"
                                    }
                                    value={
                                        item?.regional_summary?.fastest_growing
                                    }
                                />
                            </div>

                            {/* Top Scores */}

                            <div className="mt-6">
                                <h4
                                    className="
                                        flex
                                        items-center
                                        gap-2
                                        text-sm
                                        font-bold
                                        uppercase
                                        tracking-wide
                                        text-slate-500
                                    "
                                >
                                    <MapPinned size={16} />

                                    {isEn ? "Top Scores" : "Skor Tertinggi"}
                                </h4>

                                <div className="mt-3 space-y-2">
                                    {(item.top_scores ?? []).map(
                                        (score, index) => (
                                            <div
                                                key={score.country}
                                                className="
                                                    flex
                                                    items-center
                                                    justify-between
                                                    rounded-xl
                                                    bg-slate-50
                                                    px-4
                                                    py-3
                                                "
                                            >
                                                <div>
                                                    <p
                                                        className="
                                                            font-semibold
                                                            text-slate-900
                                                        "
                                                    >
                                                        {index + 1}.{" "}
                                                        {score.country}
                                                    </p>

                                                    <p
                                                        className="
                                                            text-xs
                                                            text-slate-500
                                                        "
                                                    >
                                                        Grade {score.grade}
                                                    </p>
                                                </div>

                                                <span
                                                    className="
                                                        rounded-full
                                                        bg-blue-100
                                                        px-3
                                                        py-1
                                                        text-sm
                                                        font-bold
                                                        text-blue-700
                                                    "
                                                >
                                                    {score.score}
                                                </span>
                                            </div>
                                        ),
                                    )}
                                </div>
                            </div>
                        </div>
                    );
                })}
            </div>
        </div>
    );
}

/*
|--------------------------------------------------------------------------
| Summary Card
|--------------------------------------------------------------------------
*/

function SummaryCard({ icon: Icon, title, value }) {
    return (
        <div
            className="
                rounded-2xl
                border
                border-slate-200
                p-4
            "
        >
            <div className="flex items-center gap-2">
                <Icon size={18} className="text-blue-600" />

                <p className="text-xs text-slate-500">{title}</p>
            </div>

            <p
                className="
                    mt-3
                    text-lg
                    font-bold
                    text-slate-900
                "
            >
                {value ?? "-"}
            </p>
        </div>
    );
}
