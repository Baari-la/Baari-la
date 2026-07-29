import { CheckCircle2, Circle, AlertCircle, Building2 } from "lucide-react";

export default function CompletionProgress({ passport, isEn = true }) {
    /*
    |--------------------------------------------------------------------------
    | Readiness Intelligence
    |--------------------------------------------------------------------------
    */

    const readiness = passport?.passport?.readiness ?? {};

    const score = readiness?.score ?? {};
    const summary = readiness?.summary ?? {};
    const dimensions = score?.dimensions ?? {};

    const overall = Number(score?.overall ?? summary?.overall_score ?? 0);

    const level = score?.level ?? summary?.level ?? "Developing";

    /*
    |--------------------------------------------------------------------------
    | Dimension Labels
    |--------------------------------------------------------------------------
    */

    const dimensionLabels = {
        "01_identity": {
            en: "Identity",
            id: "Identitas",
        },

        "02_facilities": {
            en: "Facilities",
            id: "Fasilitas",
        },

        "03_products": {
            en: "Products",
            id: "Produk",
        },

        "04_capacity": {
            en: "Capacity",
            id: "Kapasitas",
        },

        "05_machinery": {
            en: "Machinery",
            id: "Mesin",
        },

        "06_commercial": {
            en: "Commercial",
            id: "Komersial",
        },

        "07_markets": {
            en: "Markets",
            id: "Pasar",
        },

        "08_compliance": {
            en: "Compliance",
            id: "Kepatuhan",
        },

        "09_contacts": {
            en: "Contacts",
            id: "Kontak",
        },

        "10_media": {
            en: "Media",
            id: "Media",
        },
    };

    /*
    |--------------------------------------------------------------------------
    | Ordered Intelligence Dimensions
    |--------------------------------------------------------------------------
    */

    const sections = Object.entries(dimensionLabels).map(([key, label]) => {
        const dimension = dimensions?.[key] ?? {};

        return {
            key,
            number: key.substring(0, 2),
            name: isEn ? label.en : label.id,
            value: Number(dimension?.completion ?? 0),
            status: dimension?.status ?? "missing",
        };
    });

    /*
    |--------------------------------------------------------------------------
    | Summary
    |--------------------------------------------------------------------------
    */

    const completed = summary?.completed_dimensions ?? 0;

    const partial = summary?.partial_dimensions ?? 0;

    const missing = summary?.missing_dimensions ?? 0;

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    const formatPercentage = (value) => {
        const number = Number(value ?? 0);

        if (Number.isInteger(number)) {
            return number.toFixed(0);
        }

        return number.toFixed(2);
    };

    const getStatusIcon = (status) => {
        if (status === "complete") {
            return <CheckCircle2 className="h-5 w-5 text-emerald-500" />;
        }

        if (status === "partial") {
            return <AlertCircle className="h-5 w-5 text-amber-500" />;
        }

        return <Circle className="h-5 w-5 text-slate-400" />;
    };

    const getProgressClass = (status) => {
        if (status === "complete") {
            return "bg-emerald-500";
        }

        if (status === "partial") {
            return "bg-amber-500";
        }

        return "bg-slate-400";
    };

    return (
        <div className="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            {/* =========================================================
                HEADER
            ========================================================= */}

            <div className="border-b border-slate-100 p-8">
                <div className="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <div className="mb-3 flex items-center gap-3">
                            <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-900 text-white">
                                <Building2 className="h-5 w-5" />
                            </div>

                            <div>
                                <p className="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                                    {isEn
                                        ? "Intelligence Profile"
                                        : "Profil Intelligence"}
                                </p>

                                <h2 className="text-2xl font-bold text-slate-900">
                                    {isEn
                                        ? "Company Intelligence Data"
                                        : "Data Intelligence Perusahaan"}
                                </h2>
                            </div>
                        </div>

                        <p className="max-w-2xl text-sm leading-6 text-slate-500">
                            {isEn
                                ? "Complete each intelligence dimension to improve company visibility, data quality, and business matching opportunities."
                                : "Lengkapi setiap dimensi intelligence untuk meningkatkan visibilitas perusahaan, kualitas data, dan peluang business matching."}
                        </p>
                    </div>

                    {/* OVERALL READINESS */}

                    <div className="min-w-[220px] rounded-2xl border border-slate-200 bg-slate-50 px-6 py-5">
                        <p className="text-xs font-bold uppercase tracking-widest text-slate-400">
                            {isEn
                                ? "Intelligence Readiness"
                                : "Kesiapan Intelligence"}
                        </p>

                        <div className="mt-2 flex items-end gap-2">
                            <span className="text-4xl font-black tracking-tight text-slate-900">
                                {formatPercentage(overall)}
                            </span>

                            <span className="mb-1 text-lg font-bold text-slate-400">
                                %
                            </span>
                        </div>

                        <div className="mt-3 inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-bold uppercase tracking-wider text-blue-700">
                            {level}
                        </div>
                    </div>
                </div>

                {/* SUMMARY */}

                <div className="mt-7 flex flex-wrap gap-3">
                    <div className="rounded-full bg-emerald-50 px-4 py-2 text-xs font-bold text-emerald-700">
                        {completed} {isEn ? "Complete" : "Lengkap"}
                    </div>

                    <div className="rounded-full bg-amber-50 px-4 py-2 text-xs font-bold text-amber-700">
                        {partial} {isEn ? "Partial" : "Sebagian"}
                    </div>

                    <div className="rounded-full bg-slate-100 px-4 py-2 text-xs font-bold text-slate-600">
                        {missing} {isEn ? "Missing" : "Belum Lengkap"}
                    </div>
                </div>
            </div>

            {/* =========================================================
                10 INTELLIGENCE DIMENSIONS
            ========================================================= */}

            <div className="p-8">
                <div className="grid grid-cols-1 gap-x-10 gap-y-7 lg:grid-cols-2">
                    {sections.map((section) => (
                        <div key={section.key}>
                            <div className="mb-3 flex items-center justify-between gap-4">
                                <div className="flex min-w-0 items-center gap-3">
                                    <div className="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-xs font-black text-slate-500">
                                        {section.number}
                                    </div>

                                    {getStatusIcon(section.status)}

                                    <span className="truncate font-semibold text-slate-800">
                                        {section.name}
                                    </span>
                                </div>

                                <span className="shrink-0 text-sm font-black tabular-nums text-slate-900">
                                    {formatPercentage(section.value)}%
                                </span>
                            </div>

                            <div className="h-2.5 overflow-hidden rounded-full bg-slate-100">
                                <div
                                    className={`h-full rounded-full transition-all duration-500 ${getProgressClass(
                                        section.status,
                                    )}`}
                                    style={{
                                        width: `${Math.min(
                                            100,
                                            Math.max(0, section.value),
                                        )}%`,
                                    }}
                                />
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
}
