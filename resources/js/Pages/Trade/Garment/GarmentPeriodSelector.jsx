import React, { useEffect, useMemo, useState } from "react";
import axios from "axios";
import { usePage } from "@inertiajs/react";

const MONTHS = [
    { value: 1, en: "Jan", id: "Jan" },
    { value: 2, en: "Feb", id: "Feb" },
    { value: 3, en: "Mar", id: "Mar" },
    { value: 4, en: "Apr", id: "Apr" },
    { value: 5, en: "May", id: "Mei" },
    { value: 6, en: "Jun", id: "Jun" },
    { value: 7, en: "Jul", id: "Jul" },
    { value: 8, en: "Aug", id: "Agu" },
    { value: 9, en: "Sep", id: "Sep" },
    { value: 10, en: "Oct", id: "Okt" },
    { value: 11, en: "Nov", id: "Nov" },
    { value: 12, en: "Dec", id: "Des" },
];

const CURRENT_YEAR = new Date().getFullYear();

function formatNumber(value, maximumFractionDigits = 2) {
    const number = Number(value ?? 0);

    if (!Number.isFinite(number)) {
        return "0";
    }

    return new Intl.NumberFormat("en-US", {
        maximumFractionDigits,
    }).format(number);
}

function formatUSD(value) {
    const number = Number(value ?? 0);

    if (!Number.isFinite(number)) {
        return "USD 0";
    }

    return `USD ${formatNumber(number, 2)}`;
}

function formatCompactNumber(value) {
    const number = Number(value ?? 0);

    if (!Number.isFinite(number)) {
        return "0";
    }

    if (Math.abs(number) >= 1_000_000_000) {
        return `${formatNumber(number / 1_000_000_000, 2)} B`;
    }

    if (Math.abs(number) >= 1_000_000) {
        return `${formatNumber(number / 1_000_000, 2)} M`;
    }

    if (Math.abs(number) >= 1_000) {
        return `${formatNumber(number / 1_000, 2)} K`;
    }

    return formatNumber(number, 2);
}

function periodLabel(year, months, locale) {
    if (!months.length) {
        return `${year}`;
    }

    if (months.length === 12) {
        return locale === "id"
            ? `Januari – Desember ${year}`
            : `January – December ${year}`;
    }

    const selected = MONTHS.filter((month) => months.includes(month.value));

    if (selected.length === 1) {
        const month = selected[0];

        return `${locale === "id" ? month.id : month.en} ${year}`;
    }

    return `${selected
        .map((month) => (locale === "id" ? month.id : month.en))
        .join(", ")} ${year}`;
}

export default function GarmentPeriodSelector({
    endpoint = "/trade/garment/selection",
    initialYear = CURRENT_YEAR,
    initialMonths = [1],
    initialFlow = "export",
    autoLoad = true,
}) {
    const { props } = usePage();

    /*
    |--------------------------------------------------------------------------
    | Locale
    |--------------------------------------------------------------------------
    */

    const locale = props?.locale === "id" ? "id" : "en";

    const isIndonesia = locale === "id";

    /*
    |--------------------------------------------------------------------------
    | Translations
    |--------------------------------------------------------------------------
    */

    const t = {
        title: isIndonesia
            ? "Intelijen Perdagangan Garmen"
            : "Garment Trade Intelligence",

        subtitle: isIndonesia
            ? "Pilih periode dan arus perdagangan."
            : "Select a period and trade flow.",

        year: isIndonesia ? "Tahun" : "Year",

        months: isIndonesia ? "Bulan" : "Months",

        selectAll: isIndonesia ? "Pilih Semua" : "Select All",

        clear: isIndonesia ? "Hapus" : "Clear",

        monthSelected: isIndonesia ? "bulan dipilih" : "months selected",

        monthSingle: isIndonesia ? "bulan dipilih" : "month selected",

        selectMonth: isIndonesia
            ? "Pilih setidaknya satu bulan."
            : "Select at least one month.",

        tradeFlow: isIndonesia ? "Arus Perdagangan" : "Trade Flow",

        export: isIndonesia ? "Ekspor" : "Export",

        import: isIndonesia ? "Impor" : "Import",

        apply: isIndonesia ? "Terapkan Pilihan" : "Apply Selection",

        loading: isIndonesia ? "Memuat..." : "Loading...",

        tradeValue: isIndonesia ? "Nilai Perdagangan" : "Trade Value",

        tradeVolume: isIndonesia ? "Volume Perdagangan" : "Trade Volume",

        derivedPcs: isIndonesia ? "PCS Turunan" : "Derived PCS",

        officialTradeValue: isIndonesia
            ? "Nilai perdagangan resmi"
            : "Official trade value",

        officialTradeVolume: isIndonesia
            ? "Volume perdagangan resmi"
            : "Official trade volume",

        validatedFactor: isIndonesia
            ? "HS-8 dengan faktor konversi tervalidasi"
            : "HS-8 with validated conversion factor",

        hs8Coverage: isIndonesia ? "Cakupan HS-8" : "HS-8 Coverage",

        canonicalHs8: isIndonesia ? "HS-8 Canonical" : "Canonical HS-8",

        withTradeData: isIndonesia
            ? "Dengan Data Perdagangan"
            : "With Trade Data",

        pcsFactor: isIndonesia ? "Faktor PCS" : "PCS Factor",

        withoutFactor: isIndonesia ? "Tanpa Faktor" : "Without Factor",

        derivedPcsTitle: isIndonesia ? "PCS Turunan:" : "Derived PCS:",

        derivedPcsDescription: isIndonesia
            ? "dihitung hanya dari produk HS-8 yang memiliki faktor konversi aktif dan tervalidasi. Nilai perdagangan resmi dan volume KG tidak berubah."
            : "calculated only from HS-8 products with an active, validated conversion factor. Official trade value and KG volume remain unchanged.",

        noPeriod: isIndonesia
            ? "Belum ada periode dipilih"
            : "No period selected",

        unableToLoad: isIndonesia
            ? "Tidak dapat memuat data intelijen perdagangan garmen."
            : "Unable to load garment trade intelligence.",

        invalidResponse: isIndonesia
            ? "Respons dari API Intelijen Perdagangan Garmen tidak valid."
            : "Invalid response from Garment Trade Intelligence API.",
    };

    /*
    |--------------------------------------------------------------------------
    | State
    |--------------------------------------------------------------------------
    */

    const [year, setYear] = useState(Number(initialYear));

    const [selectedMonths, setSelectedMonths] = useState(
        [...initialMonths]
            .map(Number)
            .filter((month) => month >= 1 && month <= 12),
    );

    const [flow, setFlow] = useState(
        initialFlow === "import" ? "import" : "export",
    );

    const [data, setData] = useState(null);

    const [loading, setLoading] = useState(false);

    const [error, setError] = useState(null);

    /*
    |--------------------------------------------------------------------------
    | Available Years
    |--------------------------------------------------------------------------
    */

    const years = useMemo(() => {
        const startYear = 2019;

        return Array.from(
            {
                length: Math.max(1, CURRENT_YEAR - startYear + 1),
            },
            (_, index) => CURRENT_YEAR - index,
        );
    }, []);

    /*
    |--------------------------------------------------------------------------
    | Month Selection
    |--------------------------------------------------------------------------
    */

    const toggleMonth = (month) => {
        setSelectedMonths((current) => {
            if (current.includes(month)) {
                return current
                    .filter((value) => value !== month)
                    .sort((a, b) => a - b);
            }

            return [...current, month].sort((a, b) => a - b);
        });
    };

    const selectAllMonths = () => {
        setSelectedMonths(MONTHS.map((month) => month.value));
    };

    const clearMonths = () => {
        setSelectedMonths([]);
    };

    /*
    |--------------------------------------------------------------------------
    | API Request
    |--------------------------------------------------------------------------
    */

    const fetchData = async () => {
        if (!selectedMonths.length) {
            setError(t.selectMonth);
            setData(null);
            return;
        }

        setLoading(true);
        setError(null);

        try {
            const response = await axios.get(endpoint, {
                params: {
                    year,
                    months: selectedMonths,
                    flow,
                },
            });

            const payload = response?.data;

            if (!payload || payload.success !== true) {
                throw new Error(t.invalidResponse);
            }

            setData(payload.data ?? null);
        } catch (exception) {
            console.error("Garment Trade Selection Error:", exception);

            const message =
                exception?.response?.data?.message ??
                exception?.message ??
                t.unableToLoad;

            setError(message);
            setData(null);
        } finally {
            setLoading(false);
        }
    };

    /*
    |--------------------------------------------------------------------------
    | Initial Load
    |--------------------------------------------------------------------------
    */

    useEffect(() => {
        if (!autoLoad) {
            return;
        }

        fetchData();

        // Initial load only.
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, []);

    /*
    |--------------------------------------------------------------------------
    | Result
    |--------------------------------------------------------------------------
    */

    const result = data ?? {};

    const resultMonths = Array.isArray(result.months)
        ? result.months
        : selectedMonths;

    const resultYear = result.year ?? year;

    const resultFlow = result.flow ?? flow;

    const resultPeriodLabel = periodLabel(resultYear, resultMonths, locale);

    const isExport = resultFlow === "export";

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    return (
        <section className="w-full rounded-2xl border border-slate-200 bg-white shadow-sm">
            {/* Header */}

            <div className="border-b border-slate-200 px-5 py-5 md:px-6">
                <div className="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 className="text-lg font-bold text-slate-900">
                            {t.title}
                        </h2>

                        <p className="text-sm text-slate-500">{t.subtitle}</p>
                    </div>

                    <div
                        className={`inline-flex w-fit rounded-full px-3 py-1 text-xs font-semibold ${
                            flow === "export"
                                ? "bg-blue-50 text-blue-700"
                                : "bg-emerald-50 text-emerald-700"
                        }`}
                    >
                        {flow === "export"
                            ? t.export.toUpperCase()
                            : t.import.toUpperCase()}
                    </div>
                </div>
            </div>

            {/* Selection */}

            <div className="space-y-6 px-5 py-5 md:px-6">
                {/* Year */}

                <div>
                    <label
                        htmlFor="garment-year"
                        className="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        {t.year}
                    </label>

                    <select
                        id="garment-year"
                        value={year}
                        onChange={(event) =>
                            setYear(Number(event.target.value))
                        }
                        className="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200 md:w-48"
                    >
                        {years.map((value) => (
                            <option key={value} value={value}>
                                {value}
                            </option>
                        ))}
                    </select>
                </div>

                {/* Months */}

                <div>
                    <div className="mb-3 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <label className="block text-sm font-semibold text-slate-700">
                            {t.months}
                        </label>

                        <div className="flex gap-2">
                            <button
                                type="button"
                                onClick={selectAllMonths}
                                className="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-600 transition hover:bg-slate-50"
                            >
                                {t.selectAll}
                            </button>

                            <button
                                type="button"
                                onClick={clearMonths}
                                className="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-600 transition hover:bg-slate-50"
                            >
                                {t.clear}
                            </button>
                        </div>
                    </div>

                    <div className="grid grid-cols-3 gap-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-12">
                        {MONTHS.map((month) => {
                            const active = selectedMonths.includes(month.value);

                            return (
                                <button
                                    key={month.value}
                                    type="button"
                                    onClick={() => toggleMonth(month.value)}
                                    aria-pressed={active}
                                    className={`rounded-xl border px-3 py-2.5 text-sm font-semibold transition ${
                                        active
                                            ? "border-slate-800 bg-slate-800 text-white shadow-sm"
                                            : "border-slate-200 bg-white text-slate-600 hover:border-slate-400 hover:bg-slate-50"
                                    }`}
                                >
                                    {isIndonesia ? month.id : month.en}
                                </button>
                            );
                        })}
                    </div>

                    <p className="mt-2 text-xs text-slate-500">
                        {selectedMonths.length
                            ? `${selectedMonths.length} ${
                                  selectedMonths.length > 1
                                      ? t.monthSelected
                                      : t.monthSingle
                              }`
                            : t.selectMonth}
                    </p>
                </div>

                {/* Flow */}

                <div>
                    <span className="mb-3 block text-sm font-semibold text-slate-700">
                        {t.tradeFlow}
                    </span>

                    <div className="flex flex-col gap-2 sm:flex-row">
                        <button
                            type="button"
                            onClick={() => setFlow("export")}
                            aria-pressed={flow === "export"}
                            className={`rounded-xl border px-5 py-3 text-sm font-semibold transition ${
                                flow === "export"
                                    ? "border-blue-700 bg-blue-700 text-white"
                                    : "border-slate-200 bg-white text-slate-600 hover:bg-slate-50"
                            }`}
                        >
                            {t.export}
                        </button>

                        <button
                            type="button"
                            onClick={() => setFlow("import")}
                            aria-pressed={flow === "import"}
                            className={`rounded-xl border px-5 py-3 text-sm font-semibold transition ${
                                flow === "import"
                                    ? "border-emerald-700 bg-emerald-700 text-white"
                                    : "border-slate-200 bg-white text-slate-600 hover:bg-slate-50"
                            }`}
                        >
                            {t.import}
                        </button>
                    </div>
                </div>

                {/* Apply */}

                <div className="flex flex-col gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:items-center sm:justify-between">
                    <div className="text-sm text-slate-500">
                        {selectedMonths.length
                            ? periodLabel(year, selectedMonths, locale)
                            : t.noPeriod}
                    </div>

                    <button
                        type="button"
                        onClick={fetchData}
                        disabled={loading || !selectedMonths.length}
                        className="rounded-xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        {loading ? t.loading : t.apply}
                    </button>
                </div>

                {/* Error */}

                {error && (
                    <div className="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {error}
                    </div>
                )}

                {/* Results */}

                {data && !error && (
                    <div className="space-y-5 border-t border-slate-200 pt-6">
                        <div>
                            <div className="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                {isExport ? t.export : t.import}
                            </div>

                            <h3 className="mt-1 text-xl font-bold text-slate-900">
                                {resultPeriodLabel}
                            </h3>
                        </div>

                        {/* Main Metrics */}

                        <div className="grid grid-cols-1 gap-4 md:grid-cols-3">
                            {/* Value */}

                            <div className="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                                <div className="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    {t.tradeValue}
                                </div>

                                <div className="mt-2 text-2xl font-bold text-slate-900">
                                    {formatUSD(result.trade_value)}
                                </div>

                                <div className="mt-1 text-xs text-slate-500">
                                    {t.officialTradeValue}
                                </div>
                            </div>

                            {/* Volume */}

                            <div className="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                                <div className="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    {t.tradeVolume}
                                </div>

                                <div className="mt-2 text-2xl font-bold text-slate-900">
                                    {formatCompactNumber(result.trade_volume)}{" "}
                                    KG
                                </div>

                                <div className="mt-1 text-xs text-slate-500">
                                    {t.officialTradeVolume}
                                </div>
                            </div>

                            {/* PCS */}

                            <div className="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                                <div className="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    {t.derivedPcs}
                                </div>

                                <div className="mt-2 text-2xl font-bold text-slate-900">
                                    {formatCompactNumber(result.derived_pcs)}{" "}
                                    PCS
                                </div>

                                <div className="mt-1 text-xs text-slate-500">
                                    {t.validatedFactor}
                                </div>
                            </div>
                        </div>

                        {/* HS-8 Coverage */}

                        <div className="rounded-2xl border border-slate-200 bg-white p-5">
                            <div className="mb-4 text-sm font-bold text-slate-800">
                                {t.hs8Coverage}
                            </div>

                            <div className="grid grid-cols-2 gap-4 md:grid-cols-4">
                                <div>
                                    <div className="text-xs text-slate-500">
                                        {t.canonicalHs8}
                                    </div>

                                    <div className="mt-1 text-lg font-bold text-slate-900">
                                        {formatNumber(result.hs8_count, 0)}
                                    </div>
                                </div>

                                <div>
                                    <div className="text-xs text-slate-500">
                                        {t.withTradeData}
                                    </div>

                                    <div className="mt-1 text-lg font-bold text-slate-900">
                                        {formatNumber(
                                            result.aggregated_hs8_count,
                                            0,
                                        )}
                                    </div>
                                </div>

                                <div>
                                    <div className="text-xs text-slate-500">
                                        {t.pcsFactor}
                                    </div>

                                    <div className="mt-1 text-lg font-bold text-slate-900">
                                        {formatNumber(
                                            result.convertible_hs8_count,
                                            0,
                                        )}
                                    </div>
                                </div>

                                <div>
                                    <div className="text-xs text-slate-500">
                                        {t.withoutFactor}
                                    </div>

                                    <div className="mt-1 text-lg font-bold text-slate-900">
                                        {formatNumber(
                                            result.non_convertible_hs8_count,
                                            0,
                                        )}
                                    </div>
                                </div>
                            </div>

                            <div className="mt-4 border-t border-slate-100 pt-4 text-xs leading-5 text-slate-500">
                                <strong className="text-slate-700">
                                    {t.derivedPcsTitle}
                                </strong>{" "}
                                {t.derivedPcsDescription}
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </section>
    );
}
