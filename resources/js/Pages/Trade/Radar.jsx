import PublicNavbar from "@/Components/Navbar/PublicNavbar";
import { Head, usePage } from "@inertiajs/react";
import { useState } from "react";
import {
    ArrowDownRight,
    ArrowUpRight,
    Globe2,
    PackageSearch,
    TrendingDown,
    TrendingUp,
} from "lucide-react";

/*
|--------------------------------------------------------------------------
| Formatting
|--------------------------------------------------------------------------
*/

function formatValue(value) {
    const number = Number(value || 0);

    if (!Number.isFinite(number)) {
        return "$0";
    }

    if (number >= 1_000_000_000) {
        return `$${(number / 1_000_000_000).toFixed(2)}B`;
    }

    if (number >= 1_000_000) {
        return `$${(number / 1_000_000).toFixed(1)}M`;
    }

    if (number >= 1_000) {
        return `$${(number / 1_000).toFixed(1)}K`;
    }

    return `$${number.toLocaleString()}`;
}

function formatPercent(value) {
    if (value === null || value === undefined) {
        return "N/A";
    }

    const number = Number(value);

    if (!Number.isFinite(number)) {
        return "N/A";
    }

    return `${number > 0 ? "+" : ""}${number.toFixed(2)}%`;
}

/*
|--------------------------------------------------------------------------
| Reusable UI
|--------------------------------------------------------------------------
*/

function GrowthBadge({ value }) {
    if (value === null || value === undefined) {
        return (
            <span className="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-black text-gray-500">
                N/A
            </span>
        );
    }

    const positive = Number(value) >= 0;

    return (
        <span
            className={`inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-black ${
                positive
                    ? "bg-emerald-50 text-emerald-700"
                    : "bg-red-50 text-red-700"
            }`}
        >
            {positive ? (
                <ArrowUpRight size={13} />
            ) : (
                <ArrowDownRight size={13} />
            )}

            {formatPercent(value)}
        </span>
    );
}

function SectionTitle({ eyebrow, title, description }) {
    return (
        <div className="mb-6">
            <span className="text-[10px] font-black uppercase tracking-[0.2em] text-orange-500">
                {eyebrow}
            </span>

            <h2 className="mt-1 text-xl font-black uppercase tracking-tight text-[#0a192f]">
                {title}
            </h2>

            {description && (
                <p className="mt-1 max-w-2xl text-sm leading-6 text-gray-500">
                    {description}
                </p>
            )}
        </div>
    );
}

/*
|--------------------------------------------------------------------------
| Market Ranking
|--------------------------------------------------------------------------
*/

function RankingCard({ title, items = [], isEn }) {
    return (
        <div className="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
            <div className="mb-5 flex items-center gap-3">
                <div className="rounded-2xl bg-[#0a192f] p-3 text-white">
                    <Globe2 size={18} />
                </div>

                <h3 className="text-sm font-black uppercase tracking-widest text-[#0a192f]">
                    {title}
                </h3>
            </div>

            <div className="space-y-4">
                {items.length === 0 ? (
                    <div className="rounded-2xl bg-gray-50 p-5 text-center text-sm text-gray-400">
                        {isEn
                            ? "No snapshot data available."
                            : "Belum ada data snapshot."}
                    </div>
                ) : (
                    items.map((item, index) => {
                        const label =
                            item.country_name_en ||
                            item.country_name_id ||
                            "Unknown";

                        const value = Number(item.trade_value || 0);

                        const share = Number(item.share || 0);

                        return (
                            <div
                                key={`${item.country_id || label}-${index}`}
                                className="flex items-center justify-between gap-4 border-b border-gray-100 pb-4 last:border-0 last:pb-0"
                            >
                                <div className="min-w-0">
                                    <div className="flex items-center gap-3">
                                        <span className="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gray-100 text-xs font-black text-[#0a192f]">
                                            {index + 1}
                                        </span>

                                        <div className="min-w-0">
                                            <p className="truncate text-sm font-black text-[#0a192f]">
                                                {label}
                                            </p>

                                            {item.iso3 && (
                                                <p className="text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                                    {item.iso3}
                                                </p>
                                            )}
                                        </div>
                                    </div>
                                </div>

                                <div className="shrink-0 text-right">
                                    <p className="text-sm font-black text-[#0a192f]">
                                        {formatValue(value)}
                                    </p>

                                    {share > 0 && (
                                        <p className="text-[10px] font-bold text-gray-400">
                                            {share.toFixed(2)}
                                            {"% "}
                                            {isEn ? "share" : "pangsa"}
                                        </p>
                                    )}
                                </div>
                            </div>
                        );
                    })
                )}
            </div>
        </div>
    );
}

/*
|--------------------------------------------------------------------------
| Product Ranking
|--------------------------------------------------------------------------
*/

function ProductCard({ title, items = [], isEn }) {
    return (
        <div className="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
            <div className="mb-5 flex items-center gap-3">
                <div className="rounded-2xl bg-orange-500 p-3 text-white">
                    <PackageSearch size={18} />
                </div>

                <h3 className="text-sm font-black uppercase tracking-widest text-[#0a192f]">
                    {title}
                </h3>
            </div>

            <div className="space-y-4">
                {items.length === 0 ? (
                    <div className="rounded-2xl bg-gray-50 p-5 text-center text-sm text-gray-400">
                        {isEn
                            ? "No product snapshot available."
                            : "Belum ada snapshot produk."}
                    </div>
                ) : (
                    items.map((item, index) => (
                        <div
                            key={`${item.hs_code || "hs"}-${index}`}
                            className="border-b border-gray-100 pb-4 last:border-0 last:pb-0"
                        >
                            <div className="flex items-start justify-between gap-4">
                                <div className="min-w-0">
                                    <div className="mb-1 flex items-center gap-2">
                                        <span className="rounded-md bg-gray-100 px-2 py-1 text-[10px] font-black text-[#0a192f]">
                                            HS {item.hs_code || "-"}
                                        </span>
                                    </div>

                                    <p className="text-sm font-bold leading-5 text-[#0a192f]">
                                        {item.product ||
                                            (isEn
                                                ? "Product description unavailable"
                                                : "Deskripsi produk belum tersedia")}
                                    </p>
                                </div>

                                <div className="shrink-0 text-right">
                                    <p className="text-sm font-black text-[#0a192f]">
                                        {formatValue(item.trade_value)}
                                    </p>

                                    <p className="text-[10px] font-bold text-gray-400">
                                        {Number(item.share || 0).toFixed(2)}%{" "}
                                        {isEn ? "share" : "pangsa"}
                                    </p>
                                </div>
                            </div>
                        </div>
                    ))
                )}
            </div>
        </div>
    );
}

/*
|--------------------------------------------------------------------------
| Sector Selector
|--------------------------------------------------------------------------
*/

function SectorSelector({ activeSector, setActiveSector, isEn }) {
    const sectors = [
        {
            key: "all",
            en: "All Textile",
            id: "Seluruh Tekstil",
            enabled: true,
        },
        {
            key: "garment",
            en: "Garment",
            id: "Garmen",
            enabled: true,
        },
        {
            key: "fiber",
            en: "Fiber",
            id: "Serat",
            enabled: false,
        },
        {
            key: "yarn",
            en: "Yarn",
            id: "Benang",
            enabled: false,
        },
        {
            key: "fabric",
            en: "Fabric",
            id: "Kain",
            enabled: false,
        },
    ];

    return (
        <section className="border-b border-gray-100 bg-white">
            <div className="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
                <div className="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <span className="text-[10px] font-black uppercase tracking-[0.2em] text-orange-500">
                            {isEn
                                ? "Textile Sector Intelligence"
                                : "Intelijen Sektor Tekstil"}
                        </span>

                        <p className="mt-1 text-sm font-bold text-[#0a192f]">
                            {isEn
                                ? "Select a sector to explore trade performance."
                                : "Pilih sektor untuk melihat kinerja perdagangan."}
                        </p>
                    </div>

                    <div className="flex flex-wrap gap-2">
                        {sectors.map((sector) => {
                            const active = activeSector === sector.key;

                            return (
                                <button
                                    key={sector.key}
                                    type="button"
                                    disabled={!sector.enabled}
                                    onClick={() =>
                                        sector.enabled &&
                                        setActiveSector(sector.key)
                                    }
                                    className={`
                                        rounded-xl
                                        px-5
                                        py-2.5
                                        text-xs
                                        font-black
                                        uppercase
                                        tracking-widest
                                        transition
                                        ${
                                            active
                                                ? sector.key === "garment"
                                                    ? "bg-orange-500 text-white shadow-md"
                                                    : "bg-[#0a192f] text-white shadow-md"
                                                : sector.enabled
                                                  ? "bg-gray-100 text-gray-600 hover:bg-gray-200"
                                                  : "cursor-not-allowed bg-gray-50 text-gray-300"
                                        }
                                    `}
                                >
                                    {isEn ? sector.en : sector.id}
                                </button>
                            );
                        })}
                    </div>
                </div>
            </div>
        </section>
    );
}

/*
|--------------------------------------------------------------------------
| Page
|--------------------------------------------------------------------------
*/

export default function Radar({
    launchIntelligence = {},
    sectorIntelligence = {},
}) {
    const { props } = usePage();

    const isEn = props.locale === "en";

    const [activeSector, setActiveSector] = useState("all");

    const snapshot =
        activeSector === "garment"
            ? sectorIntelligence?.garment || {}
            : launchIntelligence || {};

    const isGarment = activeSector === "garment";

    const period = snapshot.period || {};

    const summary = snapshot.summary || {};

    const exportSummary = summary.export || {};

    const importSummary = summary.import || {};

    const exportDestinations = isGarment
        ? snapshot.export_destinations || []
        : snapshot.topExportDestinations || [];

    const importOrigins = isGarment
        ? snapshot.import_origins || []
        : snapshot.topImportOrigins || [];

    const exportProducts = isGarment
        ? snapshot.export_products || []
        : snapshot.topExportProducts || [];

    const importProducts = isGarment
        ? snapshot.import_products || []
        : snapshot.topImportProducts || [];

    const exportValue = Number(
        exportSummary.value_2026 || exportSummary.current_value || 0,
    );

    const importValue = Number(
        importSummary.value_2026 || importSummary.current_value || 0,
    );

    const exportGrowth = exportSummary.growth;

    const importGrowth = importSummary.growth;

    const importVolumeGrowth = importSummary.volume_growth;

    const topExport = exportDestinations[0] || null;

    const topImport = importOrigins[0] || null;

    const periodLabel = period.label || "H1 2026 vs H1 2025";

    const copy = {
        tradeTitle: isEn
            ? "Indonesia Trade Intelligence"
            : "Intelijen Perdagangan Indonesia",

        garmentTitle: isEn
            ? "Indonesia Garment Intelligence"
            : "Intelijen Garmen Indonesia",

        tradeBadge: isEn
            ? "Digestex Trade Intelligence"
            : "Digestex Intelijen Perdagangan",

        garmentBadge: isEn ? "Garment Intelligence" : "Intelijen Garmen",

        tradeDescription: isEn
            ? "Executive-level trade intelligence powered by official government data processed through the Digestex Intelligence Data Layer."
            : "Intelijen perdagangan tingkat eksekutif yang menggunakan data resmi instansi pemerintah dan diproses melalui Digestex Intelligence Data Layer.",

        garmentDescription: isEn
            ? "Executive garment trade intelligence covering apparel products classified under HS Chapters 61 and 62."
            : "Intelijen perdagangan garmen tingkat eksekutif yang mencakup produk apparel dalam HS Chapter 61 dan 62.",

        officialData: isEn
            ? "Official Government Data"
            : "Data Resmi Instansi Pemerintah",

        textileDataset: isEn
            ? "Official Government Data"
            : "Data Resmi Instansi Pemerintah",

        executiveOverview: isEn ? "Executive Overview" : "Ringkasan Eksekutif",

        tradePerformance: isEn ? "Trade Performance" : "Kinerja Perdagangan",

        tradePerformanceDescription: isEn
            ? isGarment
                ? "High-level movement in Indonesia's garment trade during H1 2026."
                : "High-level movement in Indonesia's trade during H1 2026."
            : isGarment
              ? "Pergerakan utama perdagangan garmen Indonesia selama H1 2026."
              : "Pergerakan utama perdagangan Indonesia selama H1 2026.",

        exportValue: isEn ? "Export Value" : "Nilai Ekspor",

        importValue: isEn ? "Import Value" : "Nilai Impor",

        importVolume: isEn ? "Import Volume" : "Volume Impor",

        analysisPeriod: isEn ? "Analysis Period" : "Periode Analisis",

        januaryJune: isEn ? "January – June" : "Januari – Juni",

        marketIntelligence: isEn
            ? isGarment
                ? "Garment Market Intelligence"
                : "Market Intelligence"
            : isGarment
              ? "Intelijen Pasar Garmen"
              : "Intelijen Pasar",

        majorMarkets: isEn ? "Major Markets" : "Pasar Utama",

        marketDescription: isEn
            ? isGarment
                ? "Leading garment export destinations and import origins during H1 2026."
                : "Leading export destinations and import origins during H1 2026."
            : isGarment
              ? "Tujuan ekspor dan asal impor garmen utama selama H1 2026."
              : "Tujuan ekspor dan asal impor utama selama H1 2026.",

        exportDestinations: isEn
            ? "Major Export Destinations"
            : "Tujuan Ekspor Utama",

        importOrigins: isEn ? "Major Import Origins" : "Asal Impor Utama",

        leadingExport: isEn ? "Leading Export Market" : "Pasar Ekspor Utama",

        leadingImport: isEn ? "Leading Import Origin" : "Asal Impor Utama",

        exportValuePeriod: isEn
            ? "H1 2026 export value"
            : "Nilai ekspor H1 2026",

        importValuePeriod: isEn
            ? "H1 2026 import value"
            : "Nilai impor H1 2026",

        productIntelligence: isEn
            ? isGarment
                ? "Garment Product Intelligence"
                : "Product Intelligence"
            : isGarment
              ? "Intelijen Produk Garmen"
              : "Intelijen Produk",

        majorProducts: isEn
            ? isGarment
                ? "Major Garment Products"
                : "Major Products"
            : isGarment
              ? "Produk Garmen Utama"
              : "Produk Utama",

        productsDescription: isEn
            ? isGarment
                ? "Leading apparel products classified under HS Chapters 61 and 62."
                : "Leading HS-level products by trade value in H1 2026."
            : isGarment
              ? "Produk apparel utama berdasarkan HS Chapter 61 dan 62."
              : "Produk HS utama berdasarkan nilai perdagangan H1 2026.",

        exportProducts: isEn ? "Major Export Products" : "Produk Ekspor Utama",

        importProducts: isEn ? "Major Import Products" : "Produk Impor Utama",

        signal: isEn ? "2026 Signal" : "Sinyal 2026",

        whatChanged: isEn
            ? "What Changed in H1 2026?"
            : "Apa yang Berubah pada H1 2026?",

        signalDescription: isEn
            ? "Executive signals from the year-on-year comparison."
            : "Sinyal eksekutif dari perbandingan tahun ke tahun.",

        exportMomentum: isEn ? "Export Momentum" : "Momentum Ekspor",

        importMomentum: isEn ? "Import Momentum" : "Momentum Impor",

        physicalDemand: isEn ? "Physical Demand" : "Permintaan Fisik",

        exportMovement: isEn
            ? "Export value movement versus H1 2025."
            : "Pergerakan nilai ekspor dibandingkan H1 2025.",

        importMovement: isEn
            ? "Import value movement versus H1 2025."
            : "Pergerakan nilai impor dibandingkan H1 2025.",

        physicalDemandText: isEn
            ? "Import volume provides an additional demand signal."
            : "Volume impor memberikan sinyal tambahan terhadap permintaan.",

        sourceNote: isEn
            ? "Digestex Trade Intelligence uses official government data processed through the Digestex Intelligence Data Layer and delivers a cached executive snapshot for fast access."
            : "Digestex Trade Intelligence menggunakan data resmi instansi pemerintah yang diproses melalui Digestex Intelligence Data Layer dan menyajikan snapshot eksekutif yang telah di-cache untuk akses cepat.",
    };

    return (
        <>
            <Head
                title={
                    isGarment
                        ? "Digestex Garment Intelligence"
                        : "Digestex Trade Intelligence"
                }
            />

            <PublicNavbar />

            <main className="min-h-screen bg-gray-50">
                {/* HERO */}
                <section className="relative overflow-hidden bg-[#0a192f] text-white">
                    <div className="absolute inset-0 bg-gradient-to-br from-[#0a192f] via-[#0d2344] to-[#112f55]" />

                    <div className="absolute -right-24 -top-24 h-80 w-80 rounded-full bg-orange-500/10 blur-3xl" />

                    <div className="absolute -bottom-24 left-1/3 h-80 w-80 rounded-full bg-blue-400/10 blur-3xl" />

                    <div className="relative mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
                        <div className="max-w-4xl">
                            <span className="inline-flex rounded-full bg-orange-500 px-4 py-2 text-[10px] font-black uppercase tracking-[0.2em] text-white shadow-lg">
                                {isGarment
                                    ? copy.garmentBadge
                                    : copy.tradeBadge}
                            </span>

                            <h1 className="mt-5 text-3xl font-black uppercase tracking-tight sm:text-5xl lg:text-6xl">
                                {isGarment ? (
                                    <>
                                        Indonesia Garment
                                        <br />
                                        Intelligence
                                    </>
                                ) : (
                                    <>
                                        Indonesia Trade
                                        <br />
                                        Intelligence
                                    </>
                                )}
                            </h1>

                            <p className="mt-5 max-w-3xl text-sm leading-7 text-blue-100 sm:text-base">
                                {isGarment
                                    ? copy.garmentDescription
                                    : copy.tradeDescription}
                            </p>

                            <div className="mt-7 flex flex-wrap gap-3">
                                <div className="rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-xs font-black uppercase tracking-widest text-orange-300 backdrop-blur">
                                    {periodLabel}
                                </div>

                                <div className="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-xs font-bold uppercase tracking-widest text-blue-100">
                                    {isGarment
                                        ? "Garment • HS 61–62"
                                        : copy.officialData}
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {/* SECTOR SELECTOR */}
                <SectorSelector
                    activeSector={activeSector}
                    setActiveSector={setActiveSector}
                    isEn={isEn}
                />

                {/* CONTENT */}
                <div className="mx-auto max-w-7xl space-y-10 px-4 py-10 sm:px-6 lg:px-8 lg:py-12">
                    {/* EXECUTIVE OVERVIEW */}
                    <section>
                        <SectionTitle
                            eyebrow={copy.executiveOverview}
                            title={copy.tradePerformance}
                            description={copy.tradePerformanceDescription}
                        />

                        <div className="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">
                            <div className="rounded-3xl bg-white p-6 shadow-sm">
                                <div className="mb-4 flex items-center justify-between">
                                    <span className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                        {copy.exportValue}
                                    </span>

                                    <TrendingUp
                                        size={18}
                                        className="text-emerald-600"
                                    />
                                </div>

                                <p className="text-3xl font-black text-[#0a192f]">
                                    {formatValue(exportValue)}
                                </p>

                                <div className="mt-3">
                                    <GrowthBadge value={exportGrowth} />
                                </div>

                                <p className="mt-2 text-xs text-gray-400">
                                    {periodLabel}
                                </p>
                            </div>

                            <div className="rounded-3xl bg-white p-6 shadow-sm">
                                <div className="mb-4 flex items-center justify-between">
                                    <span className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                        {copy.importValue}
                                    </span>

                                    <TrendingUp
                                        size={18}
                                        className="text-emerald-600"
                                    />
                                </div>

                                <p className="text-3xl font-black text-[#0a192f]">
                                    {formatValue(importValue)}
                                </p>

                                <div className="mt-3">
                                    <GrowthBadge value={importGrowth} />
                                </div>

                                <p className="mt-2 text-xs text-gray-400">
                                    {periodLabel}
                                </p>
                            </div>

                            <div className="rounded-3xl bg-white p-6 shadow-sm">
                                <div className="mb-4 flex items-center justify-between">
                                    <span className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                        {copy.importVolume}
                                    </span>

                                    {Number(importVolumeGrowth || 0) >= 0 ? (
                                        <TrendingUp
                                            size={18}
                                            className="text-orange-500"
                                        />
                                    ) : (
                                        <TrendingDown
                                            size={18}
                                            className="text-red-500"
                                        />
                                    )}
                                </div>

                                <p className="text-3xl font-black text-[#0a192f]">
                                    {formatPercent(importVolumeGrowth)}
                                </p>

                                <p className="mt-3 text-xs text-gray-500">
                                    {isEn
                                        ? "H1 volume growth"
                                        : "Pertumbuhan volume H1"}
                                </p>

                                <p className="mt-2 text-xs text-gray-400">
                                    {isEn
                                        ? "Physical demand signal"
                                        : "Sinyal permintaan fisik"}
                                </p>
                            </div>

                            <div className="rounded-3xl bg-white p-6 shadow-sm">
                                <div className="mb-4">
                                    <span className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                        {copy.analysisPeriod}
                                    </span>
                                </div>

                                <p className="text-2xl font-black text-[#0a192f]">
                                    H1 2026
                                </p>

                                <p className="mt-2 text-xs font-bold uppercase tracking-widest text-gray-400">
                                    {copy.januaryJune}
                                </p>
                            </div>
                        </div>
                    </section>

                    {/* MARKET INTELLIGENCE */}
                    <section>
                        <SectionTitle
                            eyebrow={copy.marketIntelligence}
                            title={copy.majorMarkets}
                            description={copy.marketDescription}
                        />

                        <div className="grid grid-cols-1 gap-6 lg:grid-cols-2">
                            <RankingCard
                                title={copy.exportDestinations}
                                items={exportDestinations}
                                isEn={isEn}
                            />

                            <RankingCard
                                title={copy.importOrigins}
                                items={importOrigins}
                                isEn={isEn}
                            />
                        </div>
                    </section>

                    {/* LEADING MARKETS */}
                    <section className="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <div className="rounded-3xl bg-[#0a192f] p-7 text-white shadow-lg">
                            <span className="text-[10px] font-black uppercase tracking-[0.2em] text-orange-400">
                                {copy.leadingExport}
                            </span>

                            <h3 className="mt-3 text-2xl font-black uppercase">
                                {topExport?.country_name_en ||
                                    topExport?.country_name_id ||
                                    "Data unavailable"}
                            </h3>

                            <div className="mt-4 flex items-end justify-between">
                                <div>
                                    <p className="text-3xl font-black">
                                        {formatValue(topExport?.trade_value)}
                                    </p>

                                    <p className="mt-1 text-xs text-blue-200">
                                        {copy.exportValuePeriod}
                                    </p>
                                </div>

                                <Globe2 size={38} className="text-orange-400" />
                            </div>
                        </div>

                        <div className="rounded-3xl bg-white p-7 shadow-sm">
                            <span className="text-[10px] font-black uppercase tracking-[0.2em] text-orange-500">
                                {copy.leadingImport}
                            </span>

                            <h3 className="mt-3 text-2xl font-black uppercase text-[#0a192f]">
                                {topImport?.country_name_en ||
                                    topImport?.country_name_id ||
                                    "Data unavailable"}
                            </h3>

                            <div className="mt-4 flex items-end justify-between">
                                <div>
                                    <p className="text-3xl font-black text-[#0a192f]">
                                        {formatValue(topImport?.trade_value)}
                                    </p>

                                    <p className="mt-1 text-xs text-gray-400">
                                        {copy.importValuePeriod}
                                    </p>
                                </div>

                                <Globe2 size={38} className="text-[#0a192f]" />
                            </div>
                        </div>
                    </section>

                    {/* PRODUCT INTELLIGENCE */}
                    <section>
                        <SectionTitle
                            eyebrow={copy.productIntelligence}
                            title={copy.majorProducts}
                            description={copy.productsDescription}
                        />

                        <div className="grid grid-cols-1 gap-6 lg:grid-cols-2">
                            <ProductCard
                                title={copy.exportProducts}
                                items={exportProducts}
                                isEn={isEn}
                            />

                            <ProductCard
                                title={copy.importProducts}
                                items={importProducts}
                                isEn={isEn}
                            />
                        </div>
                    </section>

                    {/* WHAT CHANGED */}
                    <section>
                        <SectionTitle
                            eyebrow={copy.signal}
                            title={copy.whatChanged}
                            description={copy.signalDescription}
                        />

                        <div className="grid grid-cols-1 gap-5 md:grid-cols-3">
                            <div className="rounded-3xl bg-white p-6 shadow-sm">
                                <div className="mb-4 flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                                    <TrendingUp size={20} />
                                </div>

                                <h3 className="text-sm font-black uppercase tracking-widest text-[#0a192f]">
                                    {copy.exportMomentum}
                                </h3>

                                <p className="mt-3 text-3xl font-black text-[#0a192f]">
                                    {formatPercent(exportGrowth)}
                                </p>

                                <p className="mt-2 text-sm leading-6 text-gray-500">
                                    {copy.exportMovement}
                                </p>
                            </div>

                            <div className="rounded-3xl bg-white p-6 shadow-sm">
                                <div className="mb-4 flex h-11 w-11 items-center justify-center rounded-2xl bg-orange-50 text-orange-600">
                                    <TrendingUp size={20} />
                                </div>

                                <h3 className="text-sm font-black uppercase tracking-widest text-[#0a192f]">
                                    {copy.importMomentum}
                                </h3>

                                <p className="mt-3 text-3xl font-black text-[#0a192f]">
                                    {formatPercent(importGrowth)}
                                </p>

                                <p className="mt-2 text-sm leading-6 text-gray-500">
                                    {copy.importMovement}
                                </p>
                            </div>

                            <div className="rounded-3xl bg-white p-6 shadow-sm">
                                <div className="mb-4 flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 text-blue-700">
                                    {Number(importVolumeGrowth || 0) >= 0 ? (
                                        <TrendingUp size={20} />
                                    ) : (
                                        <TrendingDown size={20} />
                                    )}
                                </div>

                                <h3 className="text-sm font-black uppercase tracking-widest text-[#0a192f]">
                                    {copy.physicalDemand}
                                </h3>

                                <p className="mt-3 text-3xl font-black text-[#0a192f]">
                                    {formatPercent(importVolumeGrowth)}
                                </p>

                                <p className="mt-2 text-sm leading-6 text-gray-500">
                                    {copy.physicalDemandText}
                                </p>
                            </div>
                        </div>
                    </section>

                    {/* DATA NOTE */}
                    <section className="rounded-3xl border border-orange-100 bg-orange-50 p-6">
                        <p className="text-xs font-bold leading-6 text-orange-900">
                            {copy.sourceNote}
                        </p>
                    </section>
                </div>
            </main>
        </>
    );
}
