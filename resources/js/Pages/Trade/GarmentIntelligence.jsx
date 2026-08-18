import React, { useMemo } from "react";
import { Head, usePage } from "@inertiajs/react";
import {
    ArrowDownRight,
    ArrowUpRight,
    BarChart3,
    Globe2,
    PackageSearch,
    RefreshCw,
    TrendingDown,
    TrendingUp,
} from "lucide-react";

export default function GarmentIntelligence({ garment = {}, page = {} }) {
    const { props } = usePage();

    /*
    |--------------------------------------------------------------------------
    | Locale
    |--------------------------------------------------------------------------
    */

    const isEn =
        props?.locale === "en" ||
        props?.language === "en" ||
        props?.isEn === true;

    /*
    |--------------------------------------------------------------------------
    | Safe Snapshot Defaults
    |--------------------------------------------------------------------------
    */

    const meta = garment?.meta ?? {};

    const periodLabel = isEn
        ? (meta?.period_label_en ?? "2025 vs 2026")
        : (meta?.period_label_id ?? "2025 vs 2026");

    const displayPeriodLabel = isEn
        ? (meta?.display_period_label_en ?? "")
        : (meta?.display_period_label_id ?? "");

    const comparisonPeriodLabel = isEn
        ? (meta?.comparison_period_label_en ?? "")
        : (meta?.comparison_period_label_id ?? "");

    const overview = garment?.overview ?? {
        import: {
            current: 0,
            previous: 0,
            growth_percent: 0,
        },
        export: {
            current: 0,
            previous: 0,
            growth_percent: 0,
        },
    };

    const bySubsector = garment?.by_subsector ?? [];

    const topImportProducts = garment?.top_import_products ?? [];

    const topExportProducts = garment?.top_export_products ?? [];

    const topImportOrigins = garment?.top_import_origins ?? [];

    const topExportDestinations = garment?.top_export_destinations ?? [];

    const importMarketShare = garment?.import_market_share ?? [];

    const exportMarketShare = garment?.export_market_share ?? [];

    const monthlyTrend = garment?.monthly_trend ?? [];

    const hs8Products = garment?.hs8_products ?? [];

    /*
    |--------------------------------------------------------------------------
    | Labels
    |--------------------------------------------------------------------------
    */

    const labels = {
        title: "Garment Intelligence",

        subtitle: isEn
            ? "Garment Trade Intelligence"
            : "Trade Intelligence Garmen",

        period: periodLabel,

        displayPeriod: displayPeriodLabel,

        comparisonPeriod: comparisonPeriodLabel,

        import: isEn ? "Import" : "Impor",

        export: isEn ? "Export" : "Ekspor",

        previousPeriod: isEn ? "Previous Period" : "Periode Pembanding",

        growth: isEn ? "Growth" : "Pertumbuhan",

        marketStructure: isEn ? "Market Structure" : "Struktur Pasar",

        importIntelligence: isEn ? "Import Intelligence" : "Intelligence Impor",

        exportIntelligence: isEn
            ? "Export Intelligence"
            : "Intelligence Ekspor",

        topProducts: isEn ? "Top Products" : "Produk Teratas",

        topOrigins: isEn ? "Top Import Origins" : "Negara Asal Impor Teratas",

        topDestinations: isEn
            ? "Top Export Destinations"
            : "Negara Tujuan Ekspor Teratas",

        monthlyTrend: isEn ? "Monthly Trade Trend" : "Tren Perdagangan Bulanan",

        hs8: isEn ? "HS-8 Product Intelligence" : "Product Intelligence HS-8",

        records: "Records",

        validated: isEn ? "Validated Snapshot" : "Snapshot Tervalidasi",

        source: isEn
            ? "Official Government Data"
            : "Data Resmi Instansi Pemerintah",

        leadingOrigin: isEn ? "Leading origin" : "Asal utama",

        leadingDestination: isEn ? "Leading destination" : "Tujuan utama",

        buffer: isEn ? "Buffer" : "Buffer",
    };

    /*
    |--------------------------------------------------------------------------
    | Formatters
    |--------------------------------------------------------------------------
    */

    const formatNumber = (value) => {
        const number = Number(value ?? 0);

        return new Intl.NumberFormat(isEn ? "en-US" : "id-ID", {
            maximumFractionDigits: 0,
        }).format(number);
    };

    const formatDecimal = (value) => {
        const number = Number(value ?? 0);

        return new Intl.NumberFormat(isEn ? "en-US" : "id-ID", {
            maximumFractionDigits: 2,
        }).format(number);
    };

    const formatCurrency = (value) => {
        const number = Number(value ?? 0);

        return new Intl.NumberFormat("en-US", {
            style: "currency",
            currency: "USD",
            maximumFractionDigits: 0,
        }).format(number);
    };

    const formatPercent = (value) => {
        const number = Number(value ?? 0);

        return `${number > 0 ? "+" : ""}${formatDecimal(number)}%`;
    };

    /*
    |--------------------------------------------------------------------------
    | Growth Helpers
    |--------------------------------------------------------------------------
    */

    const growthClass = (value) => {
        const number = Number(value ?? 0);

        if (number > 0) {
            return "text-emerald-600";
        }

        if (number < 0) {
            return "text-rose-600";
        }

        return "text-slate-500";
    };

    const growthIcon = (value) => {
        const number = Number(value ?? 0);

        if (number > 0) {
            return <ArrowUpRight className="h-4 w-4" />;
        }

        if (number < 0) {
            return <ArrowDownRight className="h-4 w-4" />;
        }

        return <span className="text-sm font-bold">—</span>;
    };

    /*
    |--------------------------------------------------------------------------
    | Month Label
    |--------------------------------------------------------------------------
    */

    const monthLabel = (period) => {
        if (!period) {
            return "—";
        }

        const parts = String(period).split("-");

        if (parts.length < 2) {
            return period;
        }

        const month = Number(parts[1]);

        const monthsId = [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "Mei",
            "Jun",
            "Jul",
            "Agu",
            "Sep",
            "Okt",
            "Nov",
            "Des",
        ];

        const monthsEn = [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec",
        ];

        return isEn
            ? (monthsEn[month - 1] ?? period)
            : (monthsId[month - 1] ?? period);
    };

    /*
    |--------------------------------------------------------------------------
    | Derived Values
    |--------------------------------------------------------------------------
    */

    const importGrowth = Number(overview?.import?.growth_percent ?? 0);

    const exportGrowth = Number(overview?.export?.growth_percent ?? 0);

    const highestImportShare = useMemo(
        () => importMarketShare?.[0]?.market_share_percent ?? 0,
        [importMarketShare],
    );

    const highestExportShare = useMemo(
        () => exportMarketShare?.[0]?.market_share_percent ?? 0,
        [exportMarketShare],
    );

    /*
    |--------------------------------------------------------------------------
    | Page
    |--------------------------------------------------------------------------
    */

    return (
        <>
            <Head title={labels.title} />

            <div className="min-h-screen bg-slate-50">
                {/* =========================================================
                    HERO
                ========================================================= */}

                <section className="border-b border-slate-200 bg-white">
                    <div className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                        <div className="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                            <div>
                                <div className="mb-3 flex items-center gap-3">
                                    <span className="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-[10px] font-black uppercase tracking-[0.18em] text-indigo-700">
                                        GARMENT
                                    </span>

                                    <span className="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-[10px] font-black uppercase tracking-[0.18em] text-emerald-700">
                                        {labels.validated}
                                    </span>
                                </div>

                                <h1 className="text-3xl font-black tracking-tight text-slate-900 md:text-4xl">
                                    {labels.title}
                                </h1>

                                <p className="mt-2 text-sm font-medium text-slate-500">
                                    {labels.subtitle}
                                </p>
                            </div>

                            <div className="text-left lg:text-right">
                                <div className="text-xs font-black uppercase tracking-[0.16em] text-slate-700">
                                    {labels.period}
                                </div>

                                <div className="mt-1 text-sm font-semibold text-slate-700">
                                    {labels.displayPeriod}
                                </div>

                                <div className="mt-1 text-xs text-slate-400">
                                    {labels.comparisonPeriod}
                                </div>

                                <div className="mt-3 text-sm font-semibold text-slate-700">
                                    {labels.records}:{" "}
                                    {formatNumber(meta?.record_count)}
                                </div>

                                <div className="mt-1 text-[11px] text-slate-400">
                                    {labels.buffer}:{" "}
                                    {meta?.buffer_period ?? "—"}
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {/* =========================================================
                    CONTENT
                ========================================================= */}

                <main className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                    {/* =====================================================
                        EXECUTIVE OVERVIEW
                    ===================================================== */}

                    <section className="mb-8">
                        <div className="mb-4">
                            <h2 className="text-lg font-black text-slate-900">
                                Executive Overview
                            </h2>

                            <p className="mt-1 text-sm text-slate-500">
                                {isEn
                                    ? "Current Garment trade position and year-on-year movement."
                                    : "Posisi perdagangan Garment saat ini dan pergerakan year-on-year."}
                            </p>
                        </div>

                        <div className="grid gap-5 md:grid-cols-2">
                            {/* IMPORT */}

                            <div className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                                <div className="flex items-start justify-between">
                                    <div>
                                        <div className="text-xs font-black uppercase tracking-[0.16em] text-slate-400">
                                            {labels.import} —{" "}
                                            {meta?.current_year ?? 2026}
                                        </div>

                                        <div className="mt-2 text-3xl font-black text-slate-900">
                                            {formatCurrency(
                                                overview?.import?.current,
                                            )}
                                        </div>
                                    </div>

                                    <div
                                        className={`flex items-center gap-1 ${growthClass(
                                            importGrowth,
                                        )}`}
                                    >
                                        {growthIcon(importGrowth)}

                                        <span className="text-sm font-black">
                                            {formatPercent(importGrowth)}
                                        </span>
                                    </div>
                                </div>

                                <div className="mt-5 grid grid-cols-2 gap-4 border-t border-slate-100 pt-4">
                                    <div>
                                        <div className="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                            {labels.previousPeriod} —{" "}
                                            {meta?.comparison_year ?? 2025}
                                        </div>

                                        <div className="mt-1 text-sm font-bold text-slate-700">
                                            {formatCurrency(
                                                overview?.import?.previous,
                                            )}
                                        </div>
                                    </div>

                                    <div>
                                        <div className="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                            {labels.growth}
                                        </div>

                                        <div
                                            className={`mt-1 flex items-center gap-1 text-sm font-black ${growthClass(
                                                importGrowth,
                                            )}`}
                                        >
                                            {importGrowth > 0 ? (
                                                <TrendingUp className="h-4 w-4" />
                                            ) : importGrowth < 0 ? (
                                                <TrendingDown className="h-4 w-4" />
                                            ) : null}

                                            {formatPercent(importGrowth)}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* EXPORT */}

                            <div className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                                <div className="flex items-start justify-between">
                                    <div>
                                        <div className="text-xs font-black uppercase tracking-[0.16em] text-slate-400">
                                            {labels.export} —{" "}
                                            {meta?.current_year ?? 2026}
                                        </div>

                                        <div className="mt-2 text-3xl font-black text-slate-900">
                                            {formatCurrency(
                                                overview?.export?.current,
                                            )}
                                        </div>
                                    </div>

                                    <div
                                        className={`flex items-center gap-1 ${growthClass(
                                            exportGrowth,
                                        )}`}
                                    >
                                        {growthIcon(exportGrowth)}

                                        <span className="text-sm font-black">
                                            {formatPercent(exportGrowth)}
                                        </span>
                                    </div>
                                </div>

                                <div className="mt-5 grid grid-cols-2 gap-4 border-t border-slate-100 pt-4">
                                    <div>
                                        <div className="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                            {labels.previousPeriod} —{" "}
                                            {meta?.comparison_year ?? 2025}
                                        </div>

                                        <div className="mt-1 text-sm font-bold text-slate-700">
                                            {formatCurrency(
                                                overview?.export?.previous,
                                            )}
                                        </div>
                                    </div>

                                    <div>
                                        <div className="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                            {labels.growth}
                                        </div>

                                        <div
                                            className={`mt-1 flex items-center gap-1 text-sm font-black ${growthClass(
                                                exportGrowth,
                                            )}`}
                                        >
                                            {exportGrowth > 0 ? (
                                                <TrendingUp className="h-4 w-4" />
                                            ) : exportGrowth < 0 ? (
                                                <TrendingDown className="h-4 w-4" />
                                            ) : null}

                                            {formatPercent(exportGrowth)}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {/* =====================================================
                        MARKET STRUCTURE
                    ===================================================== */}

                    <section className="mb-8">
                        <div className="mb-4 flex items-center gap-3">
                            <BarChart3 className="h-5 w-5 text-indigo-600" />

                            <div>
                                <h2 className="text-lg font-black text-slate-900">
                                    {labels.marketStructure}
                                </h2>

                                <p className="text-sm text-slate-500">
                                    {isEn
                                        ? "Garment market by product segment."
                                        : "Struktur pasar Garment berdasarkan segmen produk."}
                                </p>
                            </div>
                        </div>

                        <div className="grid gap-4 md:grid-cols-2">
                            {bySubsector.map((item) => (
                                <div
                                    key={item.subsector}
                                    className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"
                                >
                                    <div className="text-xs font-black uppercase tracking-[0.16em] text-slate-400">
                                        {isEn ? item.label_en : item.label_id}
                                    </div>

                                    <div className="mt-3 text-2xl font-black text-slate-900">
                                        {formatCurrency(item.import_value)}
                                    </div>

                                    <div className="mt-1 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                                        {labels.import} —{" "}
                                        {meta?.current_year ?? 2026}
                                    </div>

                                    <div className="mt-5 grid grid-cols-2 gap-4 border-t border-slate-100 pt-4">
                                        <div>
                                            <div className="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                                {labels.export}
                                            </div>

                                            <div className="mt-1 text-base font-black text-slate-700">
                                                {formatCurrency(
                                                    item.export_value,
                                                )}
                                            </div>
                                        </div>

                                        <div>
                                            <div className="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                                {labels.growth}
                                            </div>

                                            <div
                                                className={`mt-1 flex items-center gap-1 text-sm font-black ${growthClass(
                                                    item.import_growth_percent,
                                                )}`}
                                            >
                                                {growthIcon(
                                                    item.import_growth_percent,
                                                )}

                                                {formatPercent(
                                                    item.import_growth_percent,
                                                )}
                                            </div>
                                        </div>
                                    </div>

                                    <div className="mt-4 grid grid-cols-2 gap-4 border-t border-slate-100 pt-4">
                                        <div>
                                            <div className="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                                {labels.previousPeriod}
                                            </div>

                                            <div className="mt-1 text-sm font-bold text-slate-600">
                                                {formatCurrency(
                                                    item.import_previous_value,
                                                )}
                                            </div>
                                        </div>

                                        <div>
                                            <div className="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                                {isEn
                                                    ? "Export Growth"
                                                    : "Pertumbuhan Ekspor"}
                                            </div>

                                            <div
                                                className={`mt-1 flex items-center gap-1 text-sm font-black ${growthClass(
                                                    item.export_growth_percent,
                                                )}`}
                                            >
                                                {growthIcon(
                                                    item.export_growth_percent,
                                                )}

                                                {formatPercent(
                                                    item.export_growth_percent,
                                                )}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </section>

                    {/* =====================================================
                        IMPORT + EXPORT INTELLIGENCE
                    ===================================================== */}

                    <section className="mb-8 grid gap-6 lg:grid-cols-2">
                        {/* IMPORT */}

                        <div className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                            <div className="mb-5 flex items-center gap-3">
                                <Globe2 className="h-5 w-5 text-indigo-600" />

                                <div>
                                    <h2 className="text-lg font-black text-slate-900">
                                        {labels.importIntelligence}
                                    </h2>

                                    <p className="text-sm text-slate-500">
                                        {labels.topOrigins} —{" "}
                                        {meta?.current_year ?? 2026}
                                    </p>
                                </div>
                            </div>

                            <div className="space-y-4">
                                {topImportOrigins
                                    .slice(0, 10)
                                    .map((item, index) => {
                                        const marketShare =
                                            importMarketShare?.find(
                                                (share) =>
                                                    share.country ===
                                                    item.country,
                                            )?.market_share_percent ?? 0;

                                        return (
                                            <div
                                                key={`${item.country}-${index}`}
                                            >
                                                <div className="flex items-center justify-between gap-3">
                                                    <div className="flex min-w-0 items-center gap-3">
                                                        <span className="w-5 shrink-0 text-xs font-black text-slate-400">
                                                            {index + 1}
                                                        </span>

                                                        <span className="truncate text-sm font-bold text-slate-700">
                                                            {item.country}
                                                        </span>
                                                    </div>

                                                    <span className="shrink-0 text-sm font-black text-slate-900">
                                                        {formatCurrency(
                                                            item.value,
                                                        )}
                                                    </span>
                                                </div>

                                                <div className="mt-2 h-1.5 overflow-hidden rounded-full bg-slate-100">
                                                    <div
                                                        className="h-full rounded-full bg-indigo-500"
                                                        style={{
                                                            width: `${Math.max(
                                                                4,
                                                                Number(
                                                                    marketShare,
                                                                ),
                                                            )}%`,
                                                        }}
                                                    />
                                                </div>
                                            </div>
                                        );
                                    })}
                            </div>

                            {importMarketShare.length > 0 && (
                                <div className="mt-5 border-t border-slate-100 pt-4 text-xs text-slate-500">
                                    {labels.leadingOrigin}:{" "}
                                    <span className="font-black text-slate-700">
                                        {importMarketShare?.[0]?.country}
                                    </span>{" "}
                                    ({formatDecimal(highestImportShare)}
                                    %)
                                </div>
                            )}
                        </div>

                        {/* EXPORT */}

                        <div className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                            <div className="mb-5 flex items-center gap-3">
                                <Globe2 className="h-5 w-5 text-emerald-600" />

                                <div>
                                    <h2 className="text-lg font-black text-slate-900">
                                        {labels.exportIntelligence}
                                    </h2>

                                    <p className="text-sm text-slate-500">
                                        {labels.topDestinations} —{" "}
                                        {meta?.current_year ?? 2026}
                                    </p>
                                </div>
                            </div>

                            <div className="space-y-4">
                                {topExportDestinations
                                    .slice(0, 10)
                                    .map((item, index) => {
                                        const marketShare =
                                            exportMarketShare?.find(
                                                (share) =>
                                                    share.country ===
                                                    item.country,
                                            )?.market_share_percent ?? 0;

                                        return (
                                            <div
                                                key={`${item.country}-${index}`}
                                            >
                                                <div className="flex items-center justify-between gap-3">
                                                    <div className="flex min-w-0 items-center gap-3">
                                                        <span className="w-5 shrink-0 text-xs font-black text-slate-400">
                                                            {index + 1}
                                                        </span>

                                                        <span className="truncate text-sm font-bold text-slate-700">
                                                            {item.country}
                                                        </span>
                                                    </div>

                                                    <span className="shrink-0 text-sm font-black text-slate-900">
                                                        {formatCurrency(
                                                            item.value,
                                                        )}
                                                    </span>
                                                </div>

                                                <div className="mt-2 h-1.5 overflow-hidden rounded-full bg-slate-100">
                                                    <div
                                                        className="h-full rounded-full bg-emerald-500"
                                                        style={{
                                                            width: `${Math.max(
                                                                4,
                                                                Number(
                                                                    marketShare,
                                                                ),
                                                            )}%`,
                                                        }}
                                                    />
                                                </div>
                                            </div>
                                        );
                                    })}
                            </div>

                            {exportMarketShare.length > 0 && (
                                <div className="mt-5 border-t border-slate-100 pt-4 text-xs text-slate-500">
                                    {labels.leadingDestination}:{" "}
                                    <span className="font-black text-slate-700">
                                        {exportMarketShare?.[0]?.country}
                                    </span>{" "}
                                    ({formatDecimal(highestExportShare)}
                                    %)
                                </div>
                            )}
                        </div>
                    </section>

                    {/* =====================================================
                        TOP PRODUCTS
                    ===================================================== */}

                    <section className="mb-8 grid gap-6 lg:grid-cols-2">
                        {/* IMPORT PRODUCTS */}

                        <div className="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                            <div className="border-b border-slate-100 px-6 py-5">
                                <div className="flex items-center gap-3">
                                    <PackageSearch className="h-5 w-5 text-indigo-600" />

                                    <div>
                                        <h2 className="text-lg font-black text-slate-900">
                                            {labels.import}
                                        </h2>

                                        <p className="text-sm text-slate-500">
                                            {labels.topProducts} —{" "}
                                            {meta?.current_year ?? 2026}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div className="divide-y divide-slate-100">
                                {topImportProducts.map((item, index) => (
                                    <div
                                        key={`${item.hs4}-${index}`}
                                        className="flex items-center justify-between gap-4 px-6 py-4"
                                    >
                                        <div className="min-w-0">
                                            <div className="text-xs font-black text-indigo-600">
                                                HS {item.hs4}
                                            </div>

                                            <div className="mt-1 text-sm font-bold text-slate-700">
                                                {item.description}
                                            </div>

                                            <div className="mt-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                                {isEn
                                                    ? item.label_en
                                                    : item.label_id}
                                            </div>
                                        </div>

                                        <div className="shrink-0 text-right">
                                            <div className="text-sm font-black text-slate-900">
                                                {formatCurrency(item.value)}
                                            </div>

                                            <div className="mt-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                                {formatNumber(item.volume)}
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* EXPORT PRODUCTS */}

                        <div className="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                            <div className="border-b border-slate-100 px-6 py-5">
                                <div className="flex items-center gap-3">
                                    <PackageSearch className="h-5 w-5 text-emerald-600" />

                                    <div>
                                        <h2 className="text-lg font-black text-slate-900">
                                            {labels.export}
                                        </h2>

                                        <p className="text-sm text-slate-500">
                                            {labels.topProducts} —{" "}
                                            {meta?.current_year ?? 2026}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div className="divide-y divide-slate-100">
                                {topExportProducts.map((item, index) => (
                                    <div
                                        key={`${item.hs4}-${index}`}
                                        className="flex items-center justify-between gap-4 px-6 py-4"
                                    >
                                        <div className="min-w-0">
                                            <div className="text-xs font-black text-emerald-600">
                                                HS {item.hs4}
                                            </div>

                                            <div className="mt-1 text-sm font-bold text-slate-700">
                                                {item.description}
                                            </div>

                                            <div className="mt-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                                {isEn
                                                    ? item.label_en
                                                    : item.label_id}
                                            </div>
                                        </div>

                                        <div className="shrink-0 text-right">
                                            <div className="text-sm font-black text-slate-900">
                                                {formatCurrency(item.value)}
                                            </div>

                                            <div className="mt-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                                {formatNumber(item.volume)}
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </section>

                    {/* =====================================================
                        MONTHLY TRADE TREND
                    ===================================================== */}

                    <section className="mb-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div className="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <h2 className="text-lg font-black text-slate-900">
                                    {labels.monthlyTrend}
                                </h2>

                                <p className="mt-1 text-sm text-slate-500">
                                    {labels.comparisonPeriod}
                                </p>
                            </div>

                            <div className="flex items-center gap-4 text-xs font-bold text-slate-500">
                                <div className="flex items-center gap-2">
                                    <span className="h-2.5 w-2.5 rounded-full bg-slate-400" />
                                    {meta?.comparison_year ?? 2025}
                                </div>

                                <div className="flex items-center gap-2">
                                    <span className="h-2.5 w-2.5 rounded-full bg-indigo-500" />
                                    {meta?.current_year ?? 2026}
                                </div>
                            </div>
                        </div>

                        <div className="overflow-x-auto">
                            <table className="min-w-[720px] w-full">
                                <thead>
                                    <tr className="border-b border-slate-200">
                                        <th className="w-[90px] px-3 py-3 text-left text-[10px] font-black uppercase tracking-[0.16em] text-slate-400">
                                            {isEn ? "Month" : "Bulan"}
                                        </th>

                                        <th className="px-3 py-3 text-right text-[10px] font-black uppercase tracking-[0.16em] text-slate-400">
                                            {labels.import}{" "}
                                            {meta?.comparison_year ?? 2025}
                                        </th>

                                        <th className="px-3 py-3 text-right text-[10px] font-black uppercase tracking-[0.16em] text-slate-400">
                                            {labels.import}{" "}
                                            {meta?.current_year ?? 2026}
                                        </th>

                                        <th className="px-3 py-3 text-right text-[10px] font-black uppercase tracking-[0.16em] text-slate-400">
                                            {labels.export}{" "}
                                            {meta?.comparison_year ?? 2025}
                                        </th>

                                        <th className="px-3 py-3 text-right text-[10px] font-black uppercase tracking-[0.16em] text-slate-400">
                                            {labels.export}{" "}
                                            {meta?.current_year ?? 2026}
                                        </th>
                                    </tr>
                                </thead>

                                <tbody className="divide-y divide-slate-100">
                                    {(() => {
                                        const grouped = {};

                                        monthlyTrend.forEach((item) => {
                                            const [year, month] = String(
                                                item.period,
                                            ).split("-");

                                            if (!year || !month) {
                                                return;
                                            }

                                            if (!grouped[month]) {
                                                grouped[month] = {};
                                            }

                                            grouped[month][year] = item;
                                        });

                                        const throughMonth = Number(
                                            meta?.through_month ?? 5,
                                        );

                                        const monthOrder = Array.from(
                                            {
                                                length: Math.min(
                                                    Math.max(throughMonth, 0),
                                                    12,
                                                ),
                                            },
                                            (_, index) =>
                                                String(index + 1).padStart(
                                                    2,
                                                    "0",
                                                ),
                                        );

                                        return monthOrder.map((month) => {
                                            const comparisonYear = String(
                                                meta?.comparison_year ?? 2025,
                                            );

                                            const currentYear = String(
                                                meta?.current_year ?? 2026,
                                            );

                                            const itemComparison = grouped[
                                                month
                                            ]?.[comparisonYear] ?? {
                                                import: 0,
                                                export: 0,
                                            };

                                            const itemCurrent = grouped[
                                                month
                                            ]?.[currentYear] ?? {
                                                import: 0,
                                                export: 0,
                                            };

                                            const monthName = monthLabel(
                                                `${currentYear}-${month}`,
                                            );

                                            const importComparison = Number(
                                                itemComparison.import ?? 0,
                                            );

                                            const importCurrent = Number(
                                                itemCurrent.import ?? 0,
                                            );

                                            const exportComparison = Number(
                                                itemComparison.export ?? 0,
                                            );

                                            const exportCurrent = Number(
                                                itemCurrent.export ?? 0,
                                            );

                                            return (
                                                <tr
                                                    key={month}
                                                    className="transition-colors hover:bg-slate-50"
                                                >
                                                    <td className="px-3 py-4 text-sm font-black text-slate-800">
                                                        {monthName}
                                                    </td>

                                                    <td className="px-3 py-4 text-right">
                                                        <div className="text-sm font-bold text-slate-500">
                                                            {formatCurrency(
                                                                importComparison,
                                                            )}
                                                        </div>

                                                        <div className="mt-1 h-1.5 overflow-hidden rounded-full bg-slate-100">
                                                            <div
                                                                className="h-full rounded-full bg-slate-400"
                                                                style={{
                                                                    width: `${Math.min(
                                                                        100,
                                                                        Math.max(
                                                                            2,
                                                                            (importComparison /
                                                                                Math.max(
                                                                                    importComparison,
                                                                                    importCurrent,
                                                                                    1,
                                                                                )) *
                                                                                100,
                                                                        ),
                                                                    )}%`,
                                                                }}
                                                            />
                                                        </div>
                                                    </td>

                                                    <td className="px-3 py-4 text-right">
                                                        <div className="text-sm font-black text-indigo-600">
                                                            {formatCurrency(
                                                                importCurrent,
                                                            )}
                                                        </div>

                                                        <div className="mt-1 h-1.5 overflow-hidden rounded-full bg-slate-100">
                                                            <div
                                                                className="h-full rounded-full bg-indigo-500"
                                                                style={{
                                                                    width: `${Math.min(
                                                                        100,
                                                                        Math.max(
                                                                            2,
                                                                            (importCurrent /
                                                                                Math.max(
                                                                                    importComparison,
                                                                                    importCurrent,
                                                                                    1,
                                                                                )) *
                                                                                100,
                                                                        ),
                                                                    )}%`,
                                                                }}
                                                            />
                                                        </div>
                                                    </td>

                                                    <td className="px-3 py-4 text-right">
                                                        <div className="text-sm font-bold text-slate-500">
                                                            {formatCurrency(
                                                                exportComparison,
                                                            )}
                                                        </div>

                                                        <div className="mt-1 h-1.5 overflow-hidden rounded-full bg-slate-100">
                                                            <div
                                                                className="h-full rounded-full bg-slate-400"
                                                                style={{
                                                                    width: `${Math.min(
                                                                        100,
                                                                        Math.max(
                                                                            2,
                                                                            (exportComparison /
                                                                                Math.max(
                                                                                    exportComparison,
                                                                                    exportCurrent,
                                                                                    1,
                                                                                )) *
                                                                                100,
                                                                        ),
                                                                    )}%`,
                                                                }}
                                                            />
                                                        </div>
                                                    </td>

                                                    <td className="px-3 py-4 text-right">
                                                        <div className="text-sm font-black text-emerald-600">
                                                            {formatCurrency(
                                                                exportCurrent,
                                                            )}
                                                        </div>

                                                        <div className="mt-1 h-1.5 overflow-hidden rounded-full bg-slate-100">
                                                            <div
                                                                className="h-full rounded-full bg-emerald-500"
                                                                style={{
                                                                    width: `${Math.min(
                                                                        100,
                                                                        Math.max(
                                                                            2,
                                                                            (exportCurrent /
                                                                                Math.max(
                                                                                    exportComparison,
                                                                                    exportCurrent,
                                                                                    1,
                                                                                )) *
                                                                                100,
                                                                        ),
                                                                    )}%`,
                                                                }}
                                                            />
                                                        </div>
                                                    </td>
                                                </tr>
                                            );
                                        });
                                    })()}
                                </tbody>
                            </table>
                        </div>
                    </section>

                    {/* =====================================================
                        MARKET SIGNAL
                    ===================================================== */}

                    <section className="mb-8">
                        <div className="mb-4">
                            <h2 className="text-lg font-black text-slate-900">
                                {isEn ? "Market Signal" : "Sinyal Pasar"}
                            </h2>

                            <p className="mt-1 text-sm text-slate-500">
                                {isEn
                                    ? "Key movements from the current Garment trade snapshot."
                                    : "Pergerakan utama dari snapshot perdagangan Garment saat ini."}
                            </p>
                        </div>

                        <div className="grid gap-5 md:grid-cols-2 lg:grid-cols-4">
                            <div className="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                                <div className="text-[10px] font-black uppercase tracking-[0.16em] text-slate-400">
                                    {labels.import}
                                </div>

                                <div
                                    className={`mt-2 flex items-center gap-2 text-xl font-black ${growthClass(
                                        importGrowth,
                                    )}`}
                                >
                                    {growthIcon(importGrowth)}

                                    {formatPercent(importGrowth)}
                                </div>

                                <div className="mt-1 text-xs text-slate-400">
                                    {isEn
                                        ? `Import market movement — ${
                                              meta?.current_year ?? 2026
                                          } vs ${meta?.comparison_year ?? 2025}`
                                        : `Pergerakan pasar impor — ${
                                              meta?.current_year ?? 2026
                                          } vs ${
                                              meta?.comparison_year ?? 2025
                                          }`}
                                </div>
                            </div>

                            <div className="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                                <div className="text-[10px] font-black uppercase tracking-[0.16em] text-slate-400">
                                    {labels.export}
                                </div>

                                <div
                                    className={`mt-2 flex items-center gap-2 text-xl font-black ${growthClass(
                                        exportGrowth,
                                    )}`}
                                >
                                    {growthIcon(exportGrowth)}

                                    {formatPercent(exportGrowth)}
                                </div>

                                <div className="mt-1 text-xs text-slate-400">
                                    {isEn
                                        ? `Export market movement — ${
                                              meta?.current_year ?? 2026
                                          } vs ${meta?.comparison_year ?? 2025}`
                                        : `Pergerakan pasar ekspor — ${
                                              meta?.current_year ?? 2026
                                          } vs ${
                                              meta?.comparison_year ?? 2025
                                          }`}
                                </div>
                            </div>

                            <div className="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                                <div className="text-[10px] font-black uppercase tracking-[0.16em] text-slate-400">
                                    {labels.leadingOrigin}
                                </div>

                                <div className="mt-2 text-base font-black text-slate-900">
                                    {topImportOrigins?.[0]?.country ?? "—"}
                                </div>

                                <div className="mt-1 text-xs text-slate-400">
                                    {isEn
                                        ? "Largest Garment import origin"
                                        : "Negara asal impor Garment terbesar"}
                                </div>
                            </div>

                            <div className="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                                <div className="text-[10px] font-black uppercase tracking-[0.16em] text-slate-400">
                                    {labels.leadingDestination}
                                </div>

                                <div className="mt-2 text-base font-black text-slate-900">
                                    {topExportDestinations?.[0]?.country ?? "—"}
                                </div>

                                <div className="mt-1 text-xs text-slate-400">
                                    {isEn
                                        ? "Largest Garment export destination"
                                        : "Negara tujuan ekspor Garment terbesar"}
                                </div>
                            </div>
                        </div>
                    </section>

                    {/* =====================================================
                        HS-8 PRODUCT INTELLIGENCE
                    ===================================================== */}

                    <section className="mb-8 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div className="border-b border-slate-100 px-6 py-5">
                            <div className="flex items-center gap-3">
                                <BarChart3 className="h-5 w-5 text-indigo-600" />

                                <div>
                                    <h2 className="text-lg font-black text-slate-900">
                                        {labels.hs8}
                                    </h2>

                                    <p className="text-sm text-slate-500">
                                        {isEn
                                            ? "Detailed Garment products by HS-8."
                                            : "Detail produk Garment pada tingkat HS-8."}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div className="overflow-x-auto">
                            <table className="min-w-[980px] w-full text-left">
                                <thead className="bg-slate-50">
                                    <tr>
                                        <th className="px-6 py-3 text-[10px] font-black uppercase tracking-wider text-slate-400">
                                            HS-8
                                        </th>

                                        <th className="px-6 py-3 text-[10px] font-black uppercase tracking-wider text-slate-400">
                                            {isEn ? "Product" : "Produk"}
                                        </th>

                                        <th className="px-6 py-3 text-[10px] font-black uppercase tracking-wider text-slate-400">
                                            {isEn ? "Segment" : "Segmen"}
                                        </th>

                                        <th className="px-6 py-3 text-[10px] font-black uppercase tracking-wider text-slate-400">
                                            {isEn ? "Chapter" : "Bab"}
                                        </th>

                                        <th className="px-6 py-3 text-[10px] font-black uppercase tracking-wider text-slate-400">
                                            {isEn ? "Flow" : "Arus"}
                                        </th>

                                        <th className="px-6 py-3 text-right text-[10px] font-black uppercase tracking-wider text-slate-400">
                                            {isEn ? "Value" : "Nilai"}
                                        </th>

                                        <th className="px-6 py-3 text-right text-[10px] font-black uppercase tracking-wider text-slate-400">
                                            Volume
                                        </th>
                                    </tr>
                                </thead>

                                <tbody className="divide-y divide-slate-100">
                                    {hs8Products.map((item) => (
                                        <tr
                                            key={`${item.hs_code}-${item.flow}`}
                                            className="transition hover:bg-slate-50"
                                        >
                                            <td className="whitespace-nowrap px-6 py-4 text-xs font-black text-indigo-600">
                                                {item.hs_code}
                                            </td>

                                            <td className="min-w-[360px] px-6 py-4">
                                                <div className="text-sm font-bold text-slate-700">
                                                    {item.description}
                                                </div>

                                                <div className="mt-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                                    {isEn
                                                        ? item.label_en
                                                        : item.label_id}
                                                </div>
                                            </td>

                                            <td className="whitespace-nowrap px-6 py-4">
                                                <span className="text-xs font-bold text-slate-600">
                                                    {isEn
                                                        ? item.label_en
                                                        : item.label_id}
                                                </span>
                                            </td>

                                            <td className="whitespace-nowrap px-6 py-4">
                                                <span
                                                    className={`inline-flex rounded-full px-2.5 py-1 text-[10px] font-black uppercase tracking-wider ${
                                                        item.chapter === "61"
                                                            ? "bg-indigo-50 text-indigo-700"
                                                            : "bg-amber-50 text-amber-700"
                                                    }`}
                                                >
                                                    {item.chapter}
                                                </span>
                                            </td>

                                            <td className="whitespace-nowrap px-6 py-4">
                                                <span
                                                    className={`inline-flex rounded-full px-2.5 py-1 text-[10px] font-black uppercase tracking-wider ${
                                                        item.flow === "import"
                                                            ? "bg-indigo-50 text-indigo-700"
                                                            : "bg-emerald-50 text-emerald-700"
                                                    }`}
                                                >
                                                    {item.flow}
                                                </span>
                                            </td>

                                            <td className="whitespace-nowrap px-6 py-4 text-right text-sm font-black text-slate-900">
                                                {formatCurrency(item.value)}
                                            </td>

                                            <td className="whitespace-nowrap px-6 py-4 text-right text-sm font-semibold text-slate-600">
                                                {formatNumber(item.volume)}
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </section>

                    {/* =====================================================
                        FOOTER
                    ===================================================== */}

                    <div className="flex flex-col gap-3 border-t border-slate-200 pt-6 text-xs text-slate-400 md:flex-row md:items-center md:justify-between">
                        <div>{labels.source}</div>

                        <div className="flex flex-wrap items-center gap-4">
                            <span>{labels.displayPeriod}</span>

                            {meta?.generated_at && (
                                <div className="flex items-center gap-2">
                                    <RefreshCw className="h-3.5 w-3.5" />

                                    <span>
                                        {new Date(
                                            meta.generated_at,
                                        ).toLocaleString(
                                            isEn ? "en-US" : "id-ID",
                                        )}
                                    </span>
                                </div>
                            )}
                        </div>
                    </div>
                </main>
            </div>
        </>
    );
}
