import React from "react";
import { Link, usePage } from "@inertiajs/react";
import {
    ArrowDown,
    ArrowUp,
    BarChart3,
    Globe2,
    Package,
    RefreshCw,
    TrendingDown,
    TrendingUp,
} from "lucide-react";

export default function TierAIntelligence({
    executiveOverview = {},
    sectorSummary = {},
    sectorOverview = {},
    sectors = [],
    exportMonitor = {},
    hsLeaderboard = {},
    countries = [],
    earlyWarning = {},
    globalRadar = {},
    recommendations = [],
    tier = "A",
    sector = "textile",
    dataPeriod = "January-April 2026",
}) {
    const { props } = usePage();

    const locale = props.locale ?? "id";
    const isEn = locale === "en";

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    const formatUSD = (value) => {
        const number = Number(value ?? 0);

        if (number >= 1_000_000_000) {
            return `US$ ${(number / 1_000_000_000).toFixed(2)} B`;
        }

        if (number >= 1_000_000) {
            return `US$ ${(number / 1_000_000).toFixed(1)} M`;
        }

        if (number >= 1_000) {
            return `US$ ${(number / 1_000).toFixed(1)} K`;
        }

        return `US$ ${number.toLocaleString()}`;
    };

    const formatNumber = (value) => Number(value ?? 0).toLocaleString();

    const getValue = (...values) => {
        for (const value of values) {
            if (value !== undefined && value !== null && value !== "") {
                return Number(value) || 0;
            }
        }

        return 0;
    };

    /*
    |--------------------------------------------------------------------------
    | Summary Data
    |--------------------------------------------------------------------------
    */

    const summary = sectorSummary?.summary ?? sectorSummary ?? {};

    const exportValue = getValue(
        summary.export_value,
        summary.exportValue,
        summary.total_export,
        summary.totalExport,
        executiveOverview.exportValue,
    );

    const importValue = getValue(
        summary.import_value,
        summary.importValue,
        summary.total_import,
        summary.totalImport,
        executiveOverview.importValue,
    );

    const tradeBalance = exportValue - importValue;

    const exportGrowth = getValue(
        summary.export_growth,
        summary.exportGrowth,
        executiveOverview.exportGrowth,
    );

    const importGrowth = getValue(
        summary.import_growth,
        summary.importGrowth,
        executiveOverview.importGrowth,
    );

    const totalCountries = getValue(
        executiveOverview.totalCountries,
        summary.totalCountries,
        summary.total_countries,
    );

    const growthMarkets = getValue(
        executiveOverview.growthMarkets,
        summary.growthMarkets,
        summary.growth_markets,
    );

    /*
    |--------------------------------------------------------------------------
    | Sector
    |--------------------------------------------------------------------------
    */

    const currentSector =
        executiveOverview.sector_name ??
        sectorOverview?.sector_name ??
        (isEn ? "All Textile" : "Seluruh Tekstil");

    /*
    |--------------------------------------------------------------------------
    | Navigation
    |--------------------------------------------------------------------------
    */

    const sectorLinks = Array.isArray(sectors) ? sectors : [];

    /*
    |--------------------------------------------------------------------------
    | KPI Card
    |--------------------------------------------------------------------------
    */

    const KPICard = ({
        icon: Icon,
        label,
        value,
        helper,
        trend,
        trendLabel,
    }) => (
        <div className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md">
            <div className="flex items-start justify-between gap-4">
                <div>
                    <p className="text-xs font-black uppercase tracking-[0.18em] text-slate-500">
                        {label}
                    </p>

                    <p className="mt-3 text-2xl font-black text-slate-900 md:text-3xl">
                        {value}
                    </p>

                    {helper && (
                        <p className="mt-2 text-xs text-slate-500">{helper}</p>
                    )}
                </div>

                <div className="rounded-2xl bg-slate-900 p-3 text-white">
                    <Icon size={20} />
                </div>
            </div>

            {trend !== undefined && trend !== null && (
                <div
                    className={`mt-5 flex items-center gap-2 text-xs font-bold ${
                        Number(trend) >= 0 ? "text-emerald-600" : "text-red-600"
                    }`}
                >
                    {Number(trend) >= 0 ? (
                        <TrendingUp size={15} />
                    ) : (
                        <TrendingDown size={15} />
                    )}

                    <span>
                        {Number(trend) >= 0 ? "+" : ""}
                        {Number(trend).toFixed(1)}%
                    </span>

                    {trendLabel && (
                        <span className="font-medium text-slate-400">
                            {trendLabel}
                        </span>
                    )}
                </div>
            )}
        </div>
    );

    /*
    |--------------------------------------------------------------------------
    | Trade Balance
    |--------------------------------------------------------------------------
    */

    const balancePositive = tradeBalance >= 0;

    /*
    |--------------------------------------------------------------------------
    | Country Preview
    |--------------------------------------------------------------------------
    */

    const topCountries = Array.isArray(countries) ? countries.slice(0, 5) : [];

    /*
    |--------------------------------------------------------------------------
    | HS Leaderboard
    |--------------------------------------------------------------------------
    */

    const products = Array.isArray(hsLeaderboard)
        ? hsLeaderboard.slice(0, 5)
        : Array.isArray(hsLeaderboard?.data)
          ? hsLeaderboard.data.slice(0, 5)
          : Array.isArray(hsLeaderboard?.products)
            ? hsLeaderboard.products.slice(0, 5)
            : [];

    return (
        <div className="min-h-screen bg-slate-50">
            {/* =========================================================
                HERO
            ========================================================= */}

            <section className="border-b border-slate-200 bg-slate-950">
                <div className="mx-auto max-w-7xl px-6 py-12 lg:py-16">
                    <div className="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                        <div className="max-w-4xl">
                            <div className="flex flex-wrap items-center gap-3">
                                <span className="inline-flex items-center rounded-full border border-yellow-500/30 bg-yellow-500/10 px-4 py-2 text-[10px] font-black uppercase tracking-[0.25em] text-yellow-400">
                                    DIGESTEX TIER A
                                </span>

                                <span className="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-4 py-2 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-300">
                                    {isEn
                                        ? "Premium Intelligence"
                                        : "Premium Intelligence"}
                                </span>
                            </div>

                            <h1 className="mt-6 text-4xl font-black uppercase leading-[0.95] text-white md:text-6xl">
                                {isEn
                                    ? "Trade Intelligence"
                                    : "Trade Intelligence"}
                            </h1>

                            <p className="mt-5 max-w-3xl text-lg leading-relaxed text-slate-300">
                                {isEn
                                    ? "Executive-level export and import intelligence across the textile value chain."
                                    : "Trade intelligence tingkat eksekutif untuk ekspor dan impor di seluruh rantai nilai industri tekstil."}
                            </p>

                            <div className="mt-6 flex flex-wrap gap-3">
                                <span className="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs font-semibold text-slate-300">
                                    {currentSector}
                                </span>

                                <span className="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs font-semibold text-slate-300">
                                    {isEn
                                        ? `Data through ${dataPeriod}`
                                        : `Data sampai ${dataPeriod}`}
                                </span>

                                <span className="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs font-semibold text-slate-300">
                                    HS 8-Digit
                                </span>
                            </div>
                        </div>

                        <div className="rounded-3xl border border-white/10 bg-white/5 p-5 lg:min-w-[250px]">
                            <div className="flex items-center gap-3">
                                <div className="rounded-2xl bg-yellow-500 p-3 text-black">
                                    <BarChart3 size={22} />
                                </div>

                                <div>
                                    <p className="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500">
                                        {isEn ? "Access Level" : "Level Akses"}
                                    </p>

                                    <p className="mt-1 text-lg font-black text-white">
                                        Tier A
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* =========================================================
                SECTOR NAVIGATION
            ========================================================= */}

            {sectorLinks.length > 0 && (
                <section className="border-b border-slate-200 bg-white">
                    <div className="mx-auto max-w-7xl px-6 py-4">
                        <div className="flex flex-wrap gap-2">
                            {sectorLinks.map((item) => (
                                <Link
                                    key={item.slug}
                                    href={route("executive.tier-a", {
                                        sector: item.slug,
                                    })}
                                    className={`
                                        inline-flex
                                        items-center
                                        gap-2
                                        rounded-xl
                                        px-4
                                        py-2.5
                                        text-xs
                                        font-bold
                                        transition-all
                                        ${
                                            item.slug === sector
                                                ? "bg-yellow-500 text-black shadow-sm"
                                                : "bg-slate-100 text-slate-600 hover:bg-slate-200"
                                        }
                                    `}
                                >
                                    {item.icon && <span>{item.icon}</span>}

                                    {item.title}
                                </Link>
                            ))}
                        </div>
                    </div>
                </section>
            )}

            {/* =========================================================
                MAIN CONTENT
            ========================================================= */}

            <main className="mx-auto max-w-7xl px-6 py-10">
                {/* =====================================================
                    EXECUTIVE KPI
                ===================================================== */}

                <div className="mb-10">
                    <div className="mb-6">
                        <span className="text-xs font-black uppercase tracking-[0.3em] text-yellow-600">
                            {isEn ? "EXECUTIVE OVERVIEW" : "EXECUTIVE OVERVIEW"}
                        </span>

                        <h2 className="mt-2 text-3xl font-black text-slate-900">
                            {isEn
                                ? "Indonesia Textile Trade Performance"
                                : "Kinerja Perdagangan Tekstil Indonesia"}
                        </h2>

                        <p className="mt-2 max-w-3xl text-sm leading-relaxed text-slate-500">
                            {isEn
                                ? "A consolidated view of export, import, trade balance and market coverage."
                                : "Gambaran terkonsolidasi mengenai ekspor, impor, trade balance, dan cakupan pasar."}
                        </p>
                    </div>

                    <div className="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                        <KPICard
                            icon={ArrowUp}
                            label={isEn ? "Export Value" : "Nilai Ekspor"}
                            value={formatUSD(exportValue)}
                            helper={dataPeriod}
                            trend={exportGrowth}
                            trendLabel={isEn ? "YoY" : "YoY"}
                        />

                        <KPICard
                            icon={ArrowDown}
                            label={isEn ? "Import Value" : "Nilai Impor"}
                            value={formatUSD(importValue)}
                            helper={dataPeriod}
                            trend={importGrowth}
                            trendLabel={isEn ? "YoY" : "YoY"}
                        />

                        <KPICard
                            icon={balancePositive ? TrendingUp : TrendingDown}
                            label={isEn ? "Trade Balance" : "Trade Balance"}
                            value={formatUSD(tradeBalance)}
                            helper={
                                balancePositive
                                    ? isEn
                                        ? "Trade surplus"
                                        : "Surplus perdagangan"
                                    : isEn
                                      ? "Trade deficit"
                                      : "Defisit perdagangan"
                            }
                        />

                        <KPICard
                            icon={Globe2}
                            label={isEn ? "Market Coverage" : "Cakupan Pasar"}
                            value={formatNumber(totalCountries)}
                            helper={
                                isEn
                                    ? "Markets identified"
                                    : "Pasar teridentifikasi"
                            }
                            trend={growthMarkets}
                            trendLabel={
                                isEn ? "Growth markets" : "Growth markets"
                            }
                        />
                    </div>
                </div>

                {/* =====================================================
                    DATA STATUS
                ===================================================== */}

                <div className="mb-10 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div className="flex items-start gap-4">
                            <div className="rounded-2xl bg-slate-100 p-3 text-slate-700">
                                <RefreshCw size={20} />
                            </div>

                            <div>
                                <h3 className="font-black text-slate-900">
                                    {isEn
                                        ? "Data Coverage & Update Status"
                                        : "Cakupan & Status Pembaruan Data"}
                                </h3>

                                <p className="mt-1 text-sm leading-relaxed text-slate-500">
                                    {isEn
                                        ? "Trade intelligence is presented according to the latest official data available in the Digestex database."
                                        : "Trade intelligence ditampilkan berdasarkan data resmi terbaru yang tersedia di database Digestex."}
                                </p>
                            </div>
                        </div>

                        <div className="shrink-0 rounded-2xl bg-slate-50 px-5 py-3">
                            <p className="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                                {isEn ? "Current Coverage" : "Cakupan Saat Ini"}
                            </p>

                            <p className="mt-1 font-black text-slate-900">
                                {dataPeriod}
                            </p>
                        </div>
                    </div>
                </div>

                {/* =====================================================
                    TRADE PERFORMANCE PLACEHOLDER
                ===================================================== */}

                <section className="mb-10">
                    <div className="mb-6">
                        <span className="text-xs font-black uppercase tracking-[0.3em] text-yellow-600">
                            {isEn ? "TRADE PERFORMANCE" : "TRADE PERFORMANCE"}
                        </span>

                        <h2 className="mt-2 text-3xl font-black text-slate-900">
                            {isEn
                                ? "Export & Import Intelligence"
                                : "Intelligence Ekspor & Impor"}
                        </h2>

                        <p className="mt-2 max-w-3xl text-sm leading-relaxed text-slate-500">
                            {isEn
                                ? "Detailed trade performance, market movements and product intelligence."
                                : "Detail kinerja perdagangan, pergerakan pasar, dan intelligence produk."}
                        </p>
                    </div>

                    <div className="grid gap-6 lg:grid-cols-2">
                        <div className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <div className="flex items-center gap-3">
                                <div className="rounded-2xl bg-emerald-50 p-3 text-emerald-600">
                                    <ArrowUp size={20} />
                                </div>

                                <div>
                                    <h3 className="font-black text-slate-900">
                                        {isEn
                                            ? "Export Intelligence"
                                            : "Export Intelligence"}
                                    </h3>

                                    <p className="text-xs text-slate-500">
                                        {isEn
                                            ? "Value, volume, markets and growth"
                                            : "Nilai, volume, pasar dan pertumbuhan"}
                                    </p>
                                </div>
                            </div>

                            <div className="mt-6 rounded-2xl bg-slate-50 p-5">
                                <p className="text-xs font-bold uppercase tracking-wider text-slate-400">
                                    {isEn ? "Export Value" : "Nilai Ekspor"}
                                </p>

                                <p className="mt-2 text-3xl font-black text-slate-900">
                                    {formatUSD(exportValue)}
                                </p>
                            </div>
                        </div>

                        <div className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <div className="flex items-center gap-3">
                                <div className="rounded-2xl bg-red-50 p-3 text-red-600">
                                    <ArrowDown size={20} />
                                </div>

                                <div>
                                    <h3 className="font-black text-slate-900">
                                        {isEn
                                            ? "Import Intelligence"
                                            : "Import Intelligence"}
                                    </h3>

                                    <p className="text-xs text-slate-500">
                                        {isEn
                                            ? "Value, volume, origins and growth"
                                            : "Nilai, volume, asal dan pertumbuhan"}
                                    </p>
                                </div>
                            </div>

                            <div className="mt-6 rounded-2xl bg-slate-50 p-5">
                                <p className="text-xs font-bold uppercase tracking-wider text-slate-400">
                                    {isEn ? "Import Value" : "Nilai Impor"}
                                </p>

                                <p className="mt-2 text-3xl font-black text-slate-900">
                                    {formatUSD(importValue)}
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                {/* =====================================================
                    TOP MARKETS
                ===================================================== */}

                <section className="mb-10">
                    <div className="mb-6">
                        <span className="text-xs font-black uppercase tracking-[0.3em] text-yellow-600">
                            {isEn
                                ? "MARKET INTELLIGENCE"
                                : "MARKET INTELLIGENCE"}
                        </span>

                        <h2 className="mt-2 text-3xl font-black text-slate-900">
                            {isEn
                                ? "Top Textile Markets"
                                : "Pasar Utama Tekstil"}
                        </h2>
                    </div>

                    <div className="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                        <div className="overflow-x-auto">
                            <table className="min-w-full">
                                <thead className="bg-slate-900 text-white">
                                    <tr>
                                        <th className="px-6 py-4 text-left text-xs font-black uppercase tracking-wider">
                                            #
                                        </th>

                                        <th className="px-6 py-4 text-left text-xs font-black uppercase tracking-wider">
                                            {isEn ? "Market" : "Pasar"}
                                        </th>

                                        <th className="px-6 py-4 text-right text-xs font-black uppercase tracking-wider">
                                            {isEn ? "Export" : "Ekspor"}
                                        </th>

                                        <th className="px-6 py-4 text-right text-xs font-black uppercase tracking-wider">
                                            {isEn ? "Growth" : "Growth"}
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    {topCountries.length > 0 ? (
                                        topCountries.map((country, index) => (
                                            <tr
                                                key={
                                                    country.country_code ??
                                                    country.country_name ??
                                                    index
                                                }
                                                className="border-t border-slate-100 hover:bg-slate-50"
                                            >
                                                <td className="px-6 py-4">
                                                    <span className="inline-flex h-8 w-8 items-center justify-center rounded-full bg-slate-900 text-xs font-black text-white">
                                                        {index + 1}
                                                    </span>
                                                </td>

                                                <td className="px-6 py-4 font-bold text-slate-900">
                                                    {country.country_name_en ??
                                                        country.country_name ??
                                                        country.name ??
                                                        "—"}
                                                </td>

                                                <td className="px-6 py-4 text-right font-semibold text-slate-900">
                                                    {formatUSD(
                                                        country.export_value ??
                                                            country.exportValue ??
                                                            country.trade_value,
                                                    )}
                                                </td>

                                                <td className="px-6 py-4 text-right">
                                                    <span
                                                        className={
                                                            Number(
                                                                country.growth ??
                                                                    0,
                                                            ) >= 0
                                                                ? "font-bold text-emerald-600"
                                                                : "font-bold text-red-600"
                                                        }
                                                    >
                                                        {Number(
                                                            country.growth ?? 0,
                                                        ) >= 0
                                                            ? "+"
                                                            : ""}
                                                        {Number(
                                                            country.growth ?? 0,
                                                        ).toFixed(1)}
                                                        %
                                                    </span>
                                                </td>
                                            </tr>
                                        ))
                                    ) : (
                                        <tr>
                                            <td
                                                colSpan={4}
                                                className="px-6 py-12 text-center text-sm text-slate-400"
                                            >
                                                {isEn
                                                    ? "Market intelligence data is not available yet."
                                                    : "Data market intelligence belum tersedia."}
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                {/* =====================================================
                    PRODUCT INTELLIGENCE
                ===================================================== */}

                <section className="mb-10">
                    <div className="mb-6">
                        <span className="text-xs font-black uppercase tracking-[0.3em] text-yellow-600">
                            {isEn
                                ? "PRODUCT INTELLIGENCE"
                                : "PRODUCT INTELLIGENCE"}
                        </span>

                        <h2 className="mt-2 text-3xl font-black text-slate-900">
                            {isEn
                                ? "Top Textile Products"
                                : "Produk Tekstil Utama"}
                        </h2>

                        <p className="mt-2 max-w-3xl text-sm text-slate-500">
                            {isEn
                                ? "HS-based product intelligence across the textile value chain."
                                : "Product intelligence berbasis HS di seluruh rantai nilai industri tekstil."}
                        </p>
                    </div>

                    <div className="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                        <div className="overflow-x-auto">
                            <table className="min-w-full">
                                <thead className="bg-slate-900 text-white">
                                    <tr>
                                        <th className="px-6 py-4 text-left text-xs font-black uppercase tracking-wider">
                                            #
                                        </th>

                                        <th className="px-6 py-4 text-left text-xs font-black uppercase tracking-wider">
                                            HS Code
                                        </th>

                                        <th className="px-6 py-4 text-left text-xs font-black uppercase tracking-wider">
                                            {isEn ? "Product" : "Produk"}
                                        </th>

                                        <th className="px-6 py-4 text-right text-xs font-black uppercase tracking-wider">
                                            {isEn
                                                ? "Trade Value"
                                                : "Nilai Perdagangan"}
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    {products.length > 0 ? (
                                        products.map((product, index) => (
                                            <tr
                                                key={
                                                    product.hs_code ??
                                                    product.hsCode ??
                                                    index
                                                }
                                                className="border-t border-slate-100 hover:bg-slate-50"
                                            >
                                                <td className="px-6 py-4">
                                                    <span className="inline-flex h-8 w-8 items-center justify-center rounded-full bg-slate-900 text-xs font-black text-white">
                                                        {index + 1}
                                                    </span>
                                                </td>

                                                <td className="px-6 py-4">
                                                    <span className="rounded-lg bg-slate-100 px-3 py-1 font-mono text-xs font-bold text-slate-700">
                                                        {product.hs_code ??
                                                            product.hsCode ??
                                                            "—"}
                                                    </span>
                                                </td>

                                                <td className="px-6 py-4 font-bold text-slate-900">
                                                    {product.product ??
                                                        product.product_name ??
                                                        product.hs_description ??
                                                        "—"}
                                                </td>

                                                <td className="px-6 py-4 text-right font-semibold text-slate-900">
                                                    {formatUSD(
                                                        product.trade_value ??
                                                            product.tradeValue ??
                                                            product.export_value ??
                                                            product.exportValue,
                                                    )}
                                                </td>
                                            </tr>
                                        ))
                                    ) : (
                                        <tr>
                                            <td
                                                colSpan={4}
                                                className="px-6 py-12 text-center text-sm text-slate-400"
                                            >
                                                {isEn
                                                    ? "Product intelligence data is not available yet."
                                                    : "Data product intelligence belum tersedia."}
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                {/* =====================================================
                    TIER A CAPABILITIES
                ===================================================== */}

                <section className="mb-10 rounded-3xl bg-slate-900 p-8 text-white md:p-10">
                    <div className="max-w-4xl">
                        <span className="text-xs font-black uppercase tracking-[0.3em] text-yellow-400">
                            DIGESTEX TIER A
                        </span>

                        <h2 className="mt-4 text-3xl font-black uppercase leading-tight md:text-4xl">
                            {isEn
                                ? "Deeper Trade Intelligence"
                                : "Trade Intelligence yang Lebih Mendalam"}
                        </h2>

                        <p className="mt-5 leading-relaxed text-slate-300">
                            {isEn
                                ? "Tier A provides deeper access to export and import intelligence, including HS 8-digit analysis, destination and origin markets, trade value, volume, growth, product performance and specialized garment intelligence."
                                : "Tier A memberikan akses lebih mendalam terhadap intelligence ekspor dan impor, termasuk analisis HS 8 digit, pasar tujuan dan asal, nilai perdagangan, volume, pertumbuhan, product performance, serta intelligence khusus garment."}
                        </p>

                        <div className="mt-7 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                            {[
                                isEn
                                    ? "Export Intelligence"
                                    : "Export Intelligence",

                                isEn
                                    ? "Import Intelligence"
                                    : "Import Intelligence",

                                "HS 8-Digit Analysis",

                                isEn
                                    ? "Destination & Origin Markets"
                                    : "Pasar Tujuan & Asal",

                                isEn
                                    ? "Product Performance"
                                    : "Product Performance",

                                isEn
                                    ? "Garment PCS Intelligence"
                                    : "Garment PCS Intelligence",
                            ].map((item) => (
                                <div
                                    key={item}
                                    className="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-slate-200"
                                >
                                    {item}
                                </div>
                            ))}
                        </div>
                    </div>
                </section>
            </main>
        </div>
    );
}
