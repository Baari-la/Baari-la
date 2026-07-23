import { usePage } from "@inertiajs/react";
import {
    Globe,
    TrendingUp,
    TrendingDown,
    Minus,
    ArrowRight,
} from "lucide-react";

const defaultCountries = [
    {
        rank: 1,
        country_name_en: "United States",
        country_name_id: "Amerika Serikat",
        export_million: 0,
        share: 0,
        growth: 0,
        flag: "🇺🇸",
    },
];

function Trend({ value, isEn }) {
    if (value === null || value === undefined) {
        return (
            <div className="flex items-center gap-1 font-semibold text-slate-400">
                <Minus size={16} />
                {isEn ? "Not Available" : "Belum Tersedia"}
            </div>
        );
    }

    if (value > 0) {
        return (
            <div className="flex items-center gap-1 font-semibold text-emerald-600">
                <TrendingUp size={16} />
                {value}%
            </div>
        );
    }

    if (value < 0) {
        return (
            <div className="flex items-center gap-1 font-semibold text-red-600">
                <TrendingDown size={16} />
                {Math.abs(value)}%
            </div>
        );
    }

    return (
        <div className="flex items-center gap-1 font-semibold text-slate-500">
            <Minus size={16} />
            {isEn ? "Stable" : "Stabil"}
        </div>
    );
}

export default function TopDestinationGrid({
    title,
    subtitle,
    footerText,
    countries = defaultCountries,
}) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const formatVolume = (value) => {
        return `${((value ?? 0) / 1000000).toFixed(1)} M KG`;
    };

    const formatUSD = (value) => {
        return Number(value ?? 0).toLocaleString();
    };

    const getBadges = (country) => {
        const badges = [];

        if (country.trade_balance > 0) {
            badges.push({
                label: "NET EXPORTER",
                className: "bg-emerald-100 text-emerald-700",
            });
        }

        if ((country.growth ?? 0) >= 20) {
            badges.push({
                label: "HIGH GROWTH",
                className: "bg-yellow-100 text-yellow-700",
            });
        }

        if ((country.share ?? 0) >= 10) {
            badges.push({
                label: "STRATEGIC MARKET",
                className: "bg-blue-100 text-blue-700",
            });
        }

        return badges;
    };

    return (
        <div className="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            {/* Header */}

            <div className="border-b border-slate-100 px-6 py-5">
                <div className="flex items-start gap-3">
                    <Globe className="mt-1 text-blue-600" size={24} />

                    <div>
                        <h3 className="text-xl font-bold text-slate-900">
                            {title}
                        </h3>

                        <p className="mt-1 text-sm text-slate-500">
                            {subtitle}
                        </p>

                        <p className="mt-1 text-xs text-slate-400">
                            {isEn
                                ? "Updated from DIGESTEX Trade Intelligence"
                                : "Diperbarui dari DIGESTEX Trade Intelligence"}
                        </p>
                    </div>
                </div>
            </div>

            {/* Countries */}

            <div className="grid gap-5 p-6 md:grid-cols-2 xl:grid-cols-3">
                {countries.map((country) => {
                    const badges = getBadges(country);

                    return (
                        <div
                            key={country.country_code}
                            className="
                            rounded-2xl
                            border
                            border-slate-200
                            p-5
                            transition-all
                            hover:-translate-y-1
                            hover:border-blue-300
                            hover:shadow-lg
                        "
                        >
                            <div className="flex items-center justify-between">
                                <span
                                    className="
                                    rounded-full
                                    bg-blue-600
                                    px-3
                                    py-1
                                    text-xs
                                    font-bold
                                    text-white
                                "
                                >
                                    TOP {country.rank}
                                </span>

                                <span
                                    className="
                                    rounded-full
                                    bg-emerald-100
                                    px-3
                                    py-1
                                    text-xs
                                    font-bold
                                    text-emerald-700
                                "
                                >
                                    {country.share ?? 0}%
                                </span>
                            </div>

                            <h4 className="mt-5 text-lg font-bold text-slate-900">
                                {country.flag}{" "}
                                {isEn
                                    ? country.country_name_en
                                    : country.country_name_id}
                            </h4>

                            <p className="mt-4 text-sm text-slate-500">
                                {isEn ? "Export Value" : "Nilai Ekspor"}
                            </p>

                            <p className="mt-1 text-2xl font-bold text-blue-700">
                                US${" "}
                                {Number(
                                    country.export_million ?? 0,
                                ).toLocaleString()}{" "}
                                M
                            </p>
                            <p className="mt-4 text-sm text-slate-500">
                                {isEn ? "Export Volume" : "Volume Ekspor"}
                            </p>

                            <p className="font-semibold text-slate-800">
                                {formatVolume(country.export_volume)}
                            </p>

                            <p className="mt-4 text-sm text-slate-500">
                                {isEn ? "Import Value" : "Nilai Impor"}
                            </p>
                            <p className="font-semibold text-red-600">
                                US$ {formatUSD(country.import_value / 1000000)}{" "}
                                M
                            </p>

                            <p className="mt-4 text-sm text-slate-500">
                                {isEn ? "Import Volume" : "Volume Impor"}
                            </p>

                            <p className="font-semibold text-slate-800">
                                {formatVolume(country.import_volume)}
                            </p>

                            <p className="mt-4 text-sm text-slate-500">
                                {isEn ? "Market Share" : "Pangsa Pasar"}
                            </p>

                            <p className="text-lg font-semibold text-emerald-600">
                                {country.share ?? 0}%
                            </p>

                            <p className="mt-4 text-sm text-slate-500">
                                {isEn ? "Value Growth" : "Pertumbuhan Nilai"}
                            </p>

                            <div className="mt-1">
                                <Trend value={country.growth} isEn={isEn} />
                            </div>

                            <p className="mt-4 text-sm text-slate-500">
                                {isEn ? "Volume Growth" : "Pertumbuhan Volume"}
                            </p>

                            <Trend value={country.growth_volume} isEn={isEn} />
                            <p className="mt-4 text-sm text-slate-500">
                                {isEn ? "Trade Balance" : "Neraca Perdagangan"}
                            </p>

                            <p
                                className={
                                    country.trade_balance >= 0
                                        ? "text-lg font-bold text-emerald-600"
                                        : "text-lg font-bold text-red-600"
                                }
                            >
                                {country.trade_balance >= 0 ? "+" : ""}
                                US${" "}
                                {Number(
                                    country.trade_balance_million ?? 0,
                                ).toLocaleString()}{" "}
                                M
                            </p>
                            <div className="mt-5 flex flex-wrap gap-2">
                                {badges.map((badge) => (
                                    <span
                                        key={badge.label}
                                        className={`
                rounded-full
                px-2
                py-1
                text-xs
                font-bold
                ${badge.className}
            `}
                                    >
                                        {badge.label}
                                    </span>
                                ))}
                            </div>
                        </div>
                    );
                })}
            </div>
            {/* Footer */}

            <div className="border-t border-slate-100 bg-slate-50 px-6 py-4">
                <button
                    className="
                        flex
                        items-center
                        gap-2
                        text-sm
                        font-semibold
                        text-blue-600
                        hover:text-blue-700
                    "
                >
                    {footerText}

                    <ArrowRight size={16} />
                </button>
            </div>
        </div>
    );
}
