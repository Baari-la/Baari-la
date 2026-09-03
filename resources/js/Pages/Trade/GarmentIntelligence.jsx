import React, { useMemo, useState } from "react";
import { Head, router, usePage } from "@inertiajs/react";
import PublicNavbar from "@/Components/Navbar/PublicNavbar";
import GarmentPeriodSelector from "@/Pages/Trade/Garment/GarmentPeriodSelector";
import {
    ArrowDownRight,
    ArrowUpRight,
    BarChart3,
    Globe2,
    PackageSearch,
    TrendingDown,
    TrendingUp,
} from "lucide-react";

export default function GarmentIntelligence({
    garment = {},
    page = {},
    periodSelection = {},
    availablePeriods = {},
}) {
    const { props } = usePage();

    const isEn =
        props?.locale === "en" ||
        props?.language === "en" ||
        props?.isEn === true;

    const meta = garment?.meta ?? {};
    const overview = garment?.overview ?? {};

    const topImportOrigins = garment?.top_import_origins ?? [];
    const topExportDestinations = garment?.top_export_destinations ?? [];

    const monthlyTrend = garment?.monthly_trend ?? [];

    const selectedYear = Number(
        periodSelection?.year ??
            meta?.current_year ??
            availablePeriods?.latest?.year ??
            2026,
    );

    const selectedMonth = Number(
        periodSelection?.month ??
            meta?.through_month ??
            availablePeriods?.latest?.month ??
            6,
    );

    const selectedCompareYear = Number(
        periodSelection?.compare_year ??
            meta?.comparison_year ??
            selectedYear - 1,
    );

    const selectedCompareMonth = Number(
        periodSelection?.compare_month ??
            meta?.comparison_through_month ??
            selectedMonth,
    );

    const selectedMode = periodSelection?.mode ?? meta?.mode ?? "ytd";

    const [periodYear, setPeriodYear] = useState(selectedYear);
    const [periodMonth, setPeriodMonth] = useState(selectedMonth);
    const [compareYear, setCompareYear] = useState(selectedCompareYear);
    const [compareMonth, setCompareMonth] = useState(selectedCompareMonth);
    const [periodMode, setPeriodMode] = useState(selectedMode);

    const [isApplyingPeriod, setIsApplyingPeriod] = useState(false);
    const [activeView, setActiveView] = useState("overview");

    const periodLabel = isEn
        ? (meta?.period_label_en ?? "2026 vs 2025")
        : (meta?.period_label_id ?? "2026 vs 2025");

    const displayPeriodLabel = isEn
        ? (meta?.display_period_label_en ?? "")
        : (meta?.display_period_label_id ?? "");

    const comparisonPeriodLabel = isEn
        ? (meta?.comparison_period_label_en ?? "")
        : (meta?.comparison_period_label_id ?? "");

    /*
    |--------------------------------------------------------------------------
    | Labels
    |--------------------------------------------------------------------------
    */

    const labels = {
        eyebrow: isEn
            ? "Industry & Trade Intelligence"
            : "Industry & Trade Intelligence",

        title: isEn
            ? "Garment Trade Intelligence"
            : "Garment Trade Intelligence",

        description: isEn
            ? "Understanding global garment trade flows, markets and physical trade movements."
            : "Memahami arus perdagangan garment global, pasar dan pergerakan volume fisik.",

        overview: isEn ? "Overview" : "Ringkasan",

        current: isEn ? "Current" : "Current",

        singlePeriod: isEn ? "Single Period" : "Single Period",

        tradeValue: isEn ? "Trade Value" : "Nilai Perdagangan",

        import: isEn ? "Import" : "Impor",

        export: isEn ? "Export" : "Ekspor",

        physicalVolume: isEn
            ? "Physical Trade Volume"
            : "Volume Perdagangan Fisik",

        destinations: isEn
            ? "Global Destination Markets"
            : "Pasar Tujuan Global",

        origins: isEn ? "Countries of Origin" : "Negara Asal",

        keyInsights: isEn ? "Key Trade Insights" : "Insight Perdagangan Utama",

        source: isEn
            ? "Official Government Data"
            : "Data Resmi Instansi Pemerintah",

        validated: isEn ? "Validated" : "Tervalidasi",

        ytd: isEn ? "Year to Date" : "Year to Date",

        records: isEn ? "Records" : "Records",
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

    const formatCompact = (value, unit = "") => {
        const number = Number(value ?? 0);

        if (Math.abs(number) >= 1_000_000_000) {
            return `${formatDecimal(number / 1_000_000_000)} B${unit ? ` ${unit}` : ""}`;
        }

        if (Math.abs(number) >= 1_000_000) {
            return `${formatDecimal(number / 1_000_000)} M${unit ? ` ${unit}` : ""}`;
        }

        if (Math.abs(number) >= 1_000) {
            return `${formatDecimal(number / 1_000)} K${unit ? ` ${unit}` : ""}`;
        }

        return `${formatNumber(number)}${unit ? ` ${unit}` : ""}`;
    };

    const formatPercent = (value) => {
        const number = Number(value ?? 0);

        return `${number > 0 ? "+" : ""}${formatDecimal(number)}%`;
    };

    /*
    |--------------------------------------------------------------------------
    | Overview Metrics
    |--------------------------------------------------------------------------
    */

    const importValue = Number(overview?.import?.current ?? 0);

    const exportValue = Number(overview?.export?.current ?? 0);

    const importGrowth = Number(overview?.import?.growth_percent ?? 0);

    const exportGrowth = Number(overview?.export?.growth_percent ?? 0);

    const importVolume = Number(overview?.import?.physical_volume_kg ?? 0);

    const exportVolume = Number(overview?.export?.physical_volume_kg ?? 0);

    const importVolumeGrowth = Number(
        overview?.import?.physical_volume_growth_percent ?? 0,
    );

    const exportVolumeGrowth = Number(
        overview?.export?.physical_volume_growth_percent ?? 0,
    );

    /*
    |--------------------------------------------------------------------------
    | Chart Helpers
    |--------------------------------------------------------------------------
    */

    const destinationChart = useMemo(() => {
        return topExportDestinations.slice(0, 8).map((item) => ({
            name: item?.country_name ?? item?.country ?? item?.name ?? "—",

            value: Number(
                item?.trade_value ?? item?.value ?? item?.current ?? 0,
            ),

            country_code: item?.country_code ?? null,
            iso3: item?.iso3 ?? null,
            country_name_en: item?.country_name_en ?? null,
            country_name_id: item?.country_name_id ?? null,
            flag_emoji: item?.flag_emoji ?? null,
        }));
    }, [topExportDestinations]);

    const originChart = useMemo(() => {
        return topImportOrigins.slice(0, 8).map((item) => ({
            name: item?.country_name ?? item?.country ?? item?.name ?? "—",

            value: Number(
                item?.trade_value ?? item?.value ?? item?.current ?? 0,
            ),

            country_code: item?.country_code ?? null,
            iso3: item?.iso3 ?? null,
            country_name_en: item?.country_name_en ?? null,
            country_name_id: item?.country_name_id ?? null,
            flag_emoji: item?.flag_emoji ?? null,
        }));
    }, [topImportOrigins]);

    const destinationMax = Math.max(
        ...destinationChart.map((item) => item.value),
        1,
    );

    const originMax = Math.max(...originChart.map((item) => item.value), 1);
    /*
    |--------------------------------------------------------------------------
    | Period Selection
    |--------------------------------------------------------------------------
    */

    const applyPeriodSelection = () => {
        if (isApplyingPeriod) {
            return;
        }

        const finalMonth = periodMode === "full_year" ? 12 : periodMonth;

        const finalCompareMonth =
            periodMode === "full_year" ? 12 : compareMonth;

        setIsApplyingPeriod(true);

        router.get(
            route("trade.garment.intelligence"),
            {
                year: periodYear,
                month: finalMonth,
                compare_year: compareYear,
                compare_month: finalCompareMonth,
                mode: periodMode,
            },
            {
                preserveState: false,
                preserveScroll: true,
                replace: true,

                onFinish: () => {
                    setIsApplyingPeriod(false);
                },
            },
        );
    };

    /*
    |--------------------------------------------------------------------------
    | Growth Badge
    |--------------------------------------------------------------------------
    */

    const GrowthBadge = ({ value }) => {
        const numericValue = Number(value ?? 0);

        const positive = numericValue >= 0;

        return (
            <span
                className={[
                    "inline-flex items-center gap-1",
                    "rounded-full px-2.5 py-1",
                    "text-xs font-black",
                    positive
                        ? "bg-emerald-400/10 text-emerald-300"
                        : "bg-rose-400/10 text-rose-300",
                ].join(" ")}
            >
                {positive ? (
                    <TrendingUp className="h-3.5 w-3.5" />
                ) : (
                    <TrendingDown className="h-3.5 w-3.5" />
                )}

                {formatPercent(numericValue)}
            </span>
        );
    };

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    return (
        <>
            <Head title={labels.title} />

            <PublicNavbar />

            <main className="min-h-screen bg-[#071a33] text-white">
                {/* =========================================================
                    HERO
                ========================================================= */}

                <section className="relative overflow-hidden border-b border-white/10">
                    <div className="pointer-events-none absolute inset-0">
                        <div className="absolute left-1/2 top-0 h-[500px] w-[900px] -translate-x-1/2 rounded-full bg-blue-500/10 blur-[150px]" />

                        <div className="absolute bottom-0 right-0 h-[400px] w-[500px] rounded-full bg-emerald-400/5 blur-[140px]" />
                    </div>

                    <div className="relative mx-auto max-w-7xl px-6 py-16 lg:px-8 lg:py-20">
                        <div className="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                            <div className="max-w-4xl">
                                <div className="mb-5 flex flex-wrap items-center gap-3">
                                    <span className="text-[10px] font-black uppercase tracking-[0.35em] text-yellow-400">
                                        {labels.eyebrow}
                                    </span>

                                    <span className="rounded-full border border-emerald-400/20 bg-emerald-400/10 px-3 py-1 text-[10px] font-black uppercase tracking-[0.18em] text-emerald-300">
                                        {labels.validated}
                                    </span>
                                </div>

                                <h1 className="text-4xl font-black uppercase leading-[1.05] tracking-tight sm:text-5xl lg:text-6xl">
                                    {labels.title}
                                </h1>

                                <p className="mt-6 max-w-3xl text-base leading-8 text-slate-400 sm:text-lg">
                                    {labels.description}
                                </p>
                            </div>

                            <div className="shrink-0">
                                <div className="rounded-full border border-white/10 bg-white/5 p-1 backdrop-blur-xl">
                                    <div className="flex items-center">
                                        <button
                                            type="button"
                                            onClick={() =>
                                                setActiveView("overview")
                                            }
                                            className={
                                                activeView === "overview"
                                                    ? "rounded-full bg-yellow-400 px-5 py-2.5 text-[10px] font-black uppercase tracking-[0.18em] text-slate-950"
                                                    : "rounded-full px-5 py-2.5 text-[10px] font-black uppercase tracking-[0.18em] text-slate-400 transition hover:text-white"
                                            }
                                        >
                                            {labels.overview}
                                        </button>

                                        <button
                                            type="button"
                                            onClick={() =>
                                                setActiveView("current")
                                            }
                                            className={
                                                activeView === "current"
                                                    ? "rounded-full bg-yellow-400 px-5 py-2.5 text-[10px] font-black uppercase tracking-[0.18em] text-slate-950"
                                                    : "rounded-full px-5 py-2.5 text-[10px] font-black uppercase tracking-[0.18em] text-slate-400 transition hover:text-white"
                                            }
                                        >
                                            {labels.current}
                                        </button>

                                        <button
                                            type="button"
                                            onClick={() =>
                                                setActiveView("single-period")
                                            }
                                            className={
                                                activeView === "single-period"
                                                    ? "rounded-full bg-yellow-400 px-5 py-2.5 text-[10px] font-black uppercase tracking-[0.18em] text-slate-950"
                                                    : "rounded-full px-5 py-2.5 text-[10px] font-black uppercase tracking-[0.18em] text-slate-400 transition hover:text-white"
                                            }
                                        >
                                            {labels.singlePeriod}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {activeView === "overview" && (
                    <>
                        {/* =========================================================
                    PERIOD CONTEXT
                ========================================================= */}

                        <section className="border-b border-white/10 bg-[#081d38]">
                            <div className="mx-auto max-w-7xl px-6 py-6 lg:px-8">
                                <div className="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                                    <div>
                                        <div className="text-[10px] font-black uppercase tracking-[0.3em] text-yellow-400">
                                            {labels.period}
                                        </div>

                                        <div className="mt-2 text-xl font-black text-white">
                                            {displayPeriodLabel || periodLabel}
                                        </div>

                                        <div className="mt-1 text-xs text-slate-500">
                                            {comparisonPeriodLabel}
                                        </div>
                                    </div>

                                    <div className="flex flex-wrap gap-3 text-xs text-slate-400">
                                        <span className="rounded-full border border-white/10 bg-white/5 px-4 py-2">
                                            {labels.ytd}
                                        </span>

                                        <span className="rounded-full border border-white/10 bg-white/5 px-4 py-2">
                                            {labels.records}:{" "}
                                            {formatNumber(meta?.record_count)}
                                        </span>

                                        <span className="rounded-full border border-white/10 bg-white/5 px-4 py-2">
                                            {labels.source}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </section>

                        {/* =========================================================
                    PERIOD SELECTOR
                ========================================================= */}

                        <section className="bg-[#071a33]">
                            <div className="mx-auto max-w-7xl px-6 pt-10 lg:px-8">
                                <div className="rounded-[28px] border border-white/10 bg-white/[0.035] p-6 backdrop-blur-xl">
                                    <GarmentPeriodSelector
                                        availablePeriods={availablePeriods}
                                        year={periodYear}
                                        month={periodMonth}
                                        compareYear={compareYear}
                                        compareMonth={compareMonth}
                                        mode={periodMode}
                                        setYear={setPeriodYear}
                                        setMonth={setPeriodMonth}
                                        setCompareYear={setCompareYear}
                                        setCompareMonth={setCompareMonth}
                                        setMode={setPeriodMode}
                                        onApply={applyPeriodSelection}
                                        isApplying={isApplyingPeriod}
                                    />
                                </div>
                            </div>
                        </section>

                        {/* =========================================================
                    EXECUTIVE TRADE VALUE
                ========================================================= */}

                        <section className="bg-[#071a33]">
                            <div className="mx-auto max-w-7xl px-6 py-10 lg:px-8">
                                <div className="mb-6">
                                    <div className="text-[10px] font-black uppercase tracking-[0.35em] text-yellow-400">
                                        {labels.tradeValue}
                                    </div>

                                    <h2 className="mt-2 text-2xl font-black uppercase tracking-tight sm:text-3xl">
                                        Executive Trade Position
                                    </h2>
                                </div>

                                <div className="grid gap-6 md:grid-cols-2">
                                    {/* IMPORT */}

                                    <div className="group rounded-[30px] border border-white/10 bg-white/[0.045] p-8 transition-all duration-300 hover:-translate-y-1 hover:border-yellow-400/30 hover:bg-white/[0.065]">
                                        <div className="flex items-start justify-between">
                                            <div>
                                                <div className="text-xs font-black uppercase tracking-[0.25em] text-slate-400">
                                                    {labels.import}
                                                </div>

                                                <div className="mt-4 text-4xl font-black tracking-tight text-white sm:text-5xl">
                                                    {formatCurrency(
                                                        importValue,
                                                    )}
                                                </div>
                                            </div>

                                            <div className="flex h-12 w-12 items-center justify-center rounded-2xl bg-yellow-400/10 text-yellow-400">
                                                <ArrowDownRight className="h-6 w-6" />
                                            </div>
                                        </div>

                                        <div className="mt-6 flex items-center justify-between">
                                            <span className="text-xs font-bold uppercase tracking-wider text-slate-500">
                                                YoY
                                            </span>

                                            <GrowthBadge value={importGrowth} />
                                        </div>
                                    </div>

                                    {/* EXPORT */}

                                    <div className="group rounded-[30px] border border-white/10 bg-white/[0.045] p-8 transition-all duration-300 hover:-translate-y-1 hover:border-yellow-400/30 hover:bg-white/[0.065]">
                                        <div className="flex items-start justify-between">
                                            <div>
                                                <div className="text-xs font-black uppercase tracking-[0.25em] text-slate-400">
                                                    {labels.export}
                                                </div>

                                                <div className="mt-4 text-4xl font-black tracking-tight text-white sm:text-5xl">
                                                    {formatCurrency(
                                                        exportValue,
                                                    )}
                                                </div>
                                            </div>

                                            <div className="flex h-12 w-12 items-center justify-center rounded-2xl bg-yellow-400/10 text-yellow-400">
                                                <ArrowUpRight className="h-6 w-6" />
                                            </div>
                                        </div>

                                        <div className="mt-6 flex items-center justify-between">
                                            <span className="text-xs font-bold uppercase tracking-wider text-slate-500">
                                                YoY
                                            </span>

                                            <GrowthBadge value={exportGrowth} />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        {/* =========================================================
                    PHYSICAL TRADE VOLUME
                ========================================================= */}

                        <section className="border-y border-white/10 bg-[#081d38]">
                            <div className="mx-auto max-w-7xl px-6 py-12 lg:px-8">
                                <div className="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                                    <div>
                                        <div className="text-[10px] font-black uppercase tracking-[0.35em] text-yellow-400">
                                            {labels.physicalVolume}
                                        </div>

                                        <h2 className="mt-2 text-2xl font-black uppercase tracking-tight sm:text-3xl">
                                            Physical Trade Volume
                                        </h2>
                                    </div>

                                    <div className="text-xs text-slate-500">
                                        KG
                                    </div>
                                </div>

                                <div className="grid gap-6 lg:grid-cols-2">
                                    {/* IMPORT VOLUME */}

                                    <div className="rounded-[30px] border border-white/10 bg-white/[0.035] p-7">
                                        <div className="flex items-center justify-between">
                                            <span className="text-xs font-black uppercase tracking-[0.2em] text-slate-400">
                                                {labels.import}
                                            </span>

                                            <GrowthBadge
                                                value={importVolumeGrowth}
                                            />
                                        </div>

                                        <div className="mt-5 text-4xl font-black text-white">
                                            {formatCompact(importVolume, "KG")}
                                        </div>
                                    </div>

                                    {/* EXPORT VOLUME */}

                                    <div className="rounded-[30px] border border-white/10 bg-white/[0.035] p-7">
                                        <div className="flex items-center justify-between">
                                            <span className="text-xs font-black uppercase tracking-[0.2em] text-slate-400">
                                                {labels.export}
                                            </span>

                                            <GrowthBadge
                                                value={exportVolumeGrowth}
                                            />
                                        </div>

                                        <div className="mt-5 text-4xl font-black text-white">
                                            {formatCompact(exportVolume, "KG")}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        {/* =========================================================
    DESTINATIONS + ORIGINS
========================================================= */}

                        <section className="bg-[#071a33]">
                            <div className="mx-auto max-w-7xl px-6 py-14 lg:px-8">
                                <div className="grid gap-6 lg:grid-cols-2">
                                    {/* =========================================================
                DESTINATIONS
            ========================================================= */}

                                    <div className="rounded-[30px] border border-white/10 bg-white/[0.045] p-7">
                                        <div className="flex items-center gap-3">
                                            <div className="flex h-11 w-11 items-center justify-center rounded-2xl bg-yellow-400/10 text-yellow-400">
                                                <Globe2 className="h-5 w-5" />
                                            </div>

                                            <div>
                                                <div className="text-[10px] font-black uppercase tracking-[0.3em] text-yellow-400">
                                                    Export
                                                </div>

                                                <h3 className="mt-1 text-xl font-black uppercase">
                                                    {labels.destinations}
                                                </h3>
                                            </div>
                                        </div>

                                        <div className="mt-8 space-y-5">
                                            {destinationChart.length > 0 ? (
                                                destinationChart.map(
                                                    (item, index) => {
                                                        const width = Math.max(
                                                            4,
                                                            (Number(
                                                                item.value ?? 0,
                                                            ) /
                                                                destinationMax) *
                                                                100,
                                                        );

                                                        const countryName = isEn
                                                            ? (item.country_name_en ??
                                                              item.name ??
                                                              item.country ??
                                                              "—")
                                                            : (item.country_name_id ??
                                                              item.name ??
                                                              item.country ??
                                                              "—");

                                                        return (
                                                            <div
                                                                key={`${item.country_code ?? item.iso3 ?? item.name ?? item.country}-${index}`}
                                                            >
                                                                <div className="mb-2 flex items-center justify-between gap-4">
                                                                    <div className="flex min-w-0 items-center gap-2">
                                                                        <span className="w-5 shrink-0 text-xs font-black text-slate-500">
                                                                            {index +
                                                                                1}
                                                                        </span>

                                                                        <span className="shrink-0 text-base leading-none">
                                                                            {item.flag_emoji?.trim() ||
                                                                                "🌐"}
                                                                        </span>

                                                                        <span className="truncate text-sm font-bold text-slate-300">
                                                                            {
                                                                                countryName
                                                                            }
                                                                        </span>
                                                                    </div>

                                                                    <span className="shrink-0 text-xs font-black text-slate-500">
                                                                        {formatCompact(
                                                                            item.value,
                                                                            "USD",
                                                                        )}
                                                                    </span>
                                                                </div>

                                                                <div className="h-2 overflow-hidden rounded-full bg-white/5">
                                                                    <div
                                                                        className="h-full rounded-full bg-yellow-400 transition-all"
                                                                        style={{
                                                                            width: `${width}%`,
                                                                        }}
                                                                    />
                                                                </div>
                                                            </div>
                                                        );
                                                    },
                                                )
                                            ) : (
                                                <div className="py-10 text-center text-sm text-slate-500">
                                                    {isEn
                                                        ? "No destination data"
                                                        : "Tidak ada data negara tujuan"}
                                                </div>
                                            )}
                                        </div>
                                    </div>

                                    {/* =========================================================
                ORIGINS
            ========================================================= */}

                                    <div className="rounded-[30px] border border-white/10 bg-white/[0.045] p-7">
                                        <div className="flex items-center gap-3">
                                            <div className="flex h-11 w-11 items-center justify-center rounded-2xl bg-yellow-400/10 text-yellow-400">
                                                <Globe2 className="h-5 w-5" />
                                            </div>

                                            <div>
                                                <div className="text-[10px] font-black uppercase tracking-[0.3em] text-yellow-400">
                                                    Import
                                                </div>

                                                <h3 className="mt-1 text-xl font-black uppercase">
                                                    {labels.origins}
                                                </h3>
                                            </div>
                                        </div>

                                        <div className="mt-8 space-y-5">
                                            {originChart.length > 0 ? (
                                                originChart.map(
                                                    (item, index) => {
                                                        const width = Math.max(
                                                            4,
                                                            (Number(
                                                                item.value ?? 0,
                                                            ) /
                                                                originMax) *
                                                                100,
                                                        );

                                                        const countryName = isEn
                                                            ? (item.country_name_en ??
                                                              item.name ??
                                                              item.country ??
                                                              "—")
                                                            : (item.country_name_id ??
                                                              item.name ??
                                                              item.country ??
                                                              "—");

                                                        return (
                                                            <div
                                                                key={`${item.country_code ?? item.iso3 ?? item.name ?? item.country}-${index}`}
                                                            >
                                                                <div className="mb-2 flex items-center justify-between gap-4">
                                                                    <div className="flex min-w-0 items-center gap-2">
                                                                        <span className="w-5 shrink-0 text-xs font-black text-slate-500">
                                                                            {index +
                                                                                1}
                                                                        </span>

                                                                        <span className="shrink-0 text-base leading-none">
                                                                            {item.flag_emoji?.trim() ||
                                                                                "🌐"}
                                                                        </span>

                                                                        <span className="truncate text-sm font-bold text-slate-300">
                                                                            {
                                                                                countryName
                                                                            }
                                                                        </span>
                                                                    </div>

                                                                    <span className="shrink-0 text-xs font-black text-slate-500">
                                                                        {formatCompact(
                                                                            item.value,
                                                                            "USD",
                                                                        )}
                                                                    </span>
                                                                </div>

                                                                <div className="h-2 overflow-hidden rounded-full bg-white/5">
                                                                    <div
                                                                        className="h-full rounded-full bg-yellow-400 transition-all"
                                                                        style={{
                                                                            width: `${width}%`,
                                                                        }}
                                                                    />
                                                                </div>
                                                            </div>
                                                        );
                                                    },
                                                )
                                            ) : (
                                                <div className="py-10 text-center text-sm text-slate-500">
                                                    {isEn
                                                        ? "No origin data"
                                                        : "Tidak ada data negara asal"}
                                                </div>
                                            )}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        {/* =========================================================
                    MONTHLY TREND
                ========================================================= */}

                        {monthlyTrend.length > 0 && (
                            <section className="border-y border-white/10 bg-[#081d38]">
                                <div className="mx-auto max-w-7xl px-6 py-14 lg:px-8">
                                    <div className="mb-8 flex items-center gap-3">
                                        <div className="flex h-11 w-11 items-center justify-center rounded-2xl bg-yellow-400/10 text-yellow-400">
                                            <BarChart3 className="h-5 w-5" />
                                        </div>

                                        <div>
                                            <div className="text-[10px] font-black uppercase tracking-[0.3em] text-yellow-400">
                                                Trade Movement
                                            </div>

                                            <h2 className="mt-1 text-2xl font-black uppercase tracking-tight">
                                                Monthly Trade Trend
                                            </h2>
                                        </div>
                                    </div>

                                    <div className="overflow-x-auto rounded-[30px] border border-white/10 bg-white/[0.035] p-6">
                                        <div className="flex min-w-[720px] items-end gap-4">
                                            {monthlyTrend.map((item, index) => {
                                                const importValue = Number(
                                                    item?.import ??
                                                        item?.import_value ??
                                                        0,
                                                );

                                                const exportValue = Number(
                                                    item?.export ??
                                                        item?.export_value ??
                                                        0,
                                                );

                                                const maxValue = Math.max(
                                                    ...monthlyTrend.map(
                                                        (trend) =>
                                                            Math.max(
                                                                Number(
                                                                    trend?.import ??
                                                                        trend?.import_value ??
                                                                        0,
                                                                ),
                                                                Number(
                                                                    trend?.export ??
                                                                        trend?.export_value ??
                                                                        0,
                                                                ),
                                                            ),
                                                    ),
                                                    1,
                                                );

                                                const importHeight = Math.max(
                                                    4,
                                                    (importValue / maxValue) *
                                                        180,
                                                );

                                                const exportHeight = Math.max(
                                                    4,
                                                    (exportValue / maxValue) *
                                                        180,
                                                );

                                                return (
                                                    <div
                                                        key={index}
                                                        className="flex min-w-[64px] flex-1 flex-col items-center"
                                                    >
                                                        <div className="flex h-[190px] items-end gap-1">
                                                            <div
                                                                className="w-5 rounded-t-md bg-yellow-400/70"
                                                                style={{
                                                                    height: `${importHeight}px`,
                                                                }}
                                                                title={`Import ${formatCurrency(importValue)}`}
                                                            />

                                                            <div
                                                                className="w-5 rounded-t-md bg-white/30"
                                                                style={{
                                                                    height: `${exportHeight}px`,
                                                                }}
                                                                title={`Export ${formatCurrency(exportValue)}`}
                                                            />
                                                        </div>

                                                        <div className="mt-3 text-[10px] font-black uppercase tracking-wider text-slate-500">
                                                            {item?.period ??
                                                                item?.month ??
                                                                index + 1}
                                                        </div>
                                                    </div>
                                                );
                                            })}
                                        </div>
                                    </div>
                                </div>
                            </section>
                        )}

                        {/* =========================================================
                    KEY INSIGHTS
                ========================================================= */}

                        <section className="bg-[#071a33]">
                            <div className="mx-auto max-w-7xl px-6 py-14 lg:px-8">
                                <div className="mb-8">
                                    <div className="text-[10px] font-black uppercase tracking-[0.35em] text-yellow-400">
                                        Intelligence
                                    </div>

                                    <h2 className="mt-2 text-2xl font-black uppercase tracking-tight sm:text-3xl">
                                        {labels.keyInsights}
                                    </h2>
                                </div>

                                <div className="grid gap-6 md:grid-cols-3">
                                    <div className="rounded-[28px] border border-white/10 bg-white/[0.04] p-7">
                                        <div className="text-[10px] font-black uppercase tracking-[0.25em] text-slate-500">
                                            Trade Value
                                        </div>

                                        <div className="mt-4 text-2xl font-black text-white">
                                            {formatCurrency(exportValue)}
                                        </div>

                                        <p className="mt-3 text-sm leading-7 text-slate-400">
                                            {isEn
                                                ? "Export trade value and its year-on-year movement provide the primary view of current garment trade performance."
                                                : "Nilai ekspor dan pergerakan year-on-year memberikan gambaran utama mengenai kinerja perdagangan garment saat ini."}
                                        </p>
                                    </div>

                                    <div className="rounded-[28px] border border-white/10 bg-white/[0.04] p-7">
                                        <div className="text-[10px] font-black uppercase tracking-[0.25em] text-slate-500">
                                            Physical Volume
                                        </div>

                                        <div className="mt-4 text-2xl font-black text-white">
                                            {formatCompact(exportVolume, "KG")}
                                        </div>

                                        <p className="mt-3 text-sm leading-7 text-slate-400">
                                            {isEn
                                                ? "Physical trade volume provides a direct view of the scale of garment trade beyond monetary value."
                                                : "Volume perdagangan fisik memberikan gambaran langsung mengenai skala perdagangan garment di luar nilai moneter."}
                                        </p>
                                    </div>

                                    <div className="rounded-[28px] border border-white/10 bg-white/[0.04] p-7">
                                        <div className="text-[10px] font-black uppercase tracking-[0.25em] text-slate-500">
                                            Market Reach
                                        </div>

                                        <div className="mt-4 text-2xl font-black text-white">
                                            {destinationChart.length}
                                        </div>

                                        <p className="mt-3 text-sm leading-7 text-slate-400">
                                            {isEn
                                                ? "Leading destination markets reveal where garment trade demand is concentrated."
                                                : "Pasar tujuan utama menunjukkan konsentrasi permintaan perdagangan garment."}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </>
                )}

                {/* =========================================================
                    FOOTER
                ========================================================= */}

                <section className="border-t border-white/10 bg-[#06162b]">
                    <div className="mx-auto max-w-4xl px-6 py-16 text-center lg:py-20">
                        <PackageSearch className="mx-auto h-8 w-8 text-yellow-400" />

                        <div className="mt-5 text-[10px] font-black uppercase tracking-[0.35em] text-yellow-400">
                            DIGESTEX Trade Intelligence
                        </div>

                        <h2 className="mt-4 text-3xl font-black uppercase leading-tight sm:text-4xl">
                            From Trade Data To Business Intelligence
                        </h2>

                        <p className="mx-auto mt-5 max-w-2xl text-sm leading-7 text-slate-400">
                            {isEn
                                ? "Explore the current trade position or analyze a specific trade period."
                                : "Jelajahi posisi perdagangan saat ini atau analisis periode perdagangan tertentu."}
                        </p>
                    </div>
                </section>
            </main>
        </>
    );
}
