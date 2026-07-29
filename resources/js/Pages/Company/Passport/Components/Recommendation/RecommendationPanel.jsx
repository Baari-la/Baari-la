import {
    AlertTriangle,
    ArrowRight,
    CheckCircle2,
    CircleAlert,
    Target,
    TrendingUp,
} from "lucide-react";

export default function RecommendationPanel({ passport, isEn = true }) {
    /*
    |--------------------------------------------------------------------------
    | Readiness Intelligence
    |--------------------------------------------------------------------------
    */

    const readiness = passport?.passport?.readiness ?? {};

    const summary = readiness?.summary ?? {};

    const missingIntelligence = Array.isArray(readiness?.missing_intelligence)
        ? readiness.missing_intelligence
        : [];

    /*
    |--------------------------------------------------------------------------
    | Language
    |--------------------------------------------------------------------------
    */

    const language = isEn ? "en" : "id";

    /*
    |--------------------------------------------------------------------------
    | Next Best Actions
    |--------------------------------------------------------------------------
    |
    | Backend remains the SSOT.
    |
    | React only:
    | - displays the action
    | - sorts by potential score gain
    | - handles presentation
    |
    */

    const actions = [...missingIntelligence].sort(
        (a, b) =>
            Number(b?.potential_gain ?? 0) - Number(a?.potential_gain ?? 0),
    );

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    const formatNumber = (value) => {
        const number = Number(value ?? 0);

        if (Number.isInteger(number)) {
            return number.toFixed(0);
        }

        return number.toFixed(2);
    };

    const getLocalizedValue = (value, fallback = "") => {
        if (!value) {
            return fallback;
        }

        if (typeof value === "string") {
            return value;
        }

        return value?.[language] ?? value?.en ?? value?.id ?? fallback;
    };

    const getPriorityLabel = (priority) => {
        const labels = {
            critical: {
                en: "Critical",
                id: "Kritis",
            },

            high: {
                en: "High Priority",
                id: "Prioritas Tinggi",
            },

            medium: {
                en: "Medium Priority",
                id: "Prioritas Menengah",
            },

            low: {
                en: "Low Priority",
                id: "Prioritas Rendah",
            },
        };

        return labels?.[priority]?.[language] ?? priority ?? "-";
    };

    const getPriorityClass = (priority) => {
        if (priority === "critical") {
            return "border-red-200 bg-red-50 text-red-700";
        }

        if (priority === "high") {
            return "border-orange-200 bg-orange-50 text-orange-700";
        }

        if (priority === "medium") {
            return "border-amber-200 bg-amber-50 text-amber-700";
        }

        return "border-slate-200 bg-slate-50 text-slate-600";
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

    /*
    |--------------------------------------------------------------------------
    | Summary
    |--------------------------------------------------------------------------
    */

    const overallScore = Number(summary?.overall_score ?? 0);

    const level = summary?.level ?? "Developing";

    const completed = summary?.completed_dimensions ?? 0;

    const partial = summary?.partial_dimensions ?? 0;

    const missing = summary?.missing_dimensions ?? 0;

    return (
        <section className="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            {/* =========================================================
                HEADER
            ========================================================= */}

            <div className="border-b border-slate-100 px-6 py-6 md:px-8">
                <div className="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                    <div className="flex items-start gap-4">
                        <div className="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-900 text-white">
                            <Target className="h-5 w-5" />
                        </div>

                        <div>
                            <p className="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                                {isEn
                                    ? "Intelligence Recommendations"
                                    : "Rekomendasi Intelligence"}
                            </p>

                            <h2 className="mt-1 text-2xl font-bold text-slate-900">
                                {isEn
                                    ? "Next Best Actions"
                                    : "Tindakan Prioritas Berikutnya"}
                            </h2>

                            <p className="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                                {isEn
                                    ? "Prioritized actions based on missing intelligence and their potential impact on your Intelligence Readiness Score."
                                    : "Tindakan yang diprioritaskan berdasarkan intelligence yang belum lengkap dan potensi dampaknya terhadap Intelligence Readiness Score."}
                            </p>
                        </div>
                    </div>

                    <div className="flex shrink-0 items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <TrendingUp className="h-5 w-5 text-blue-600" />

                        <div>
                            <p className="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                {isEn
                                    ? "Current Readiness"
                                    : "Kesiapan Saat Ini"}
                            </p>

                            <div className="mt-1 flex items-center gap-2">
                                <span className="text-xl font-black text-slate-900">
                                    {formatNumber(overallScore)}%
                                </span>

                                <span className="rounded-full bg-blue-50 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-blue-700">
                                    {level}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {/* SUMMARY */}

                <div className="mt-6 flex flex-wrap gap-2">
                    <span className="rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700">
                        {completed} {isEn ? "Complete" : "Lengkap"}
                    </span>

                    <span className="rounded-full bg-amber-50 px-3 py-1.5 text-xs font-bold text-amber-700">
                        {partial} {isEn ? "Partial" : "Sebagian"}
                    </span>

                    <span className="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-600">
                        {missing} {isEn ? "Missing" : "Belum Lengkap"}
                    </span>
                </div>
            </div>

            {/* =========================================================
                ACTIONS
            ========================================================= */}

            <div className="p-6 md:p-8">
                {actions.length === 0 ? (
                    <div className="flex items-start gap-4 rounded-2xl border border-emerald-100 bg-emerald-50 p-6">
                        <CheckCircle2 className="mt-0.5 h-6 w-6 shrink-0 text-emerald-600" />

                        <div>
                            <h3 className="font-bold text-emerald-900">
                                {isEn
                                    ? "Intelligence profile complete"
                                    : "Profil intelligence lengkap"}
                            </h3>

                            <p className="mt-1 text-sm leading-6 text-emerald-700">
                                {isEn
                                    ? "All intelligence dimensions are currently complete."
                                    : "Seluruh dimensi intelligence saat ini telah lengkap."}
                            </p>
                        </div>
                    </div>
                ) : (
                    <div className="space-y-4">
                        {actions.map((item, index) => {
                            const label = getLocalizedValue(
                                item?.label,
                                item?.dimension ?? "Intelligence",
                            );

                            const action = getLocalizedValue(
                                item?.action,
                                label,
                            );

                            const completion = Number(item?.completion ?? 0);

                            const potentialGain = Number(
                                item?.potential_gain ?? 0,
                            );

                            const priority = item?.priority ?? "low";

                            const status = item?.status ?? "missing";

                            return (
                                <article
                                    key={item?.dimension ?? index}
                                    className="group rounded-2xl border border-slate-200 bg-white p-5 transition hover:border-slate-300 hover:shadow-sm md:p-6"
                                >
                                    <div className="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                                        {/* LEFT */}

                                        <div className="flex min-w-0 gap-4">
                                            <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-xs font-black text-slate-600">
                                                {String(index + 1).padStart(
                                                    2,
                                                    "0",
                                                )}
                                            </div>

                                            <div className="min-w-0">
                                                <div className="flex flex-wrap items-center gap-2">
                                                    <h3 className="font-bold text-slate-900">
                                                        {label}
                                                    </h3>

                                                    <span
                                                        className={`rounded-full border px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider ${getPriorityClass(
                                                            priority,
                                                        )}`}
                                                    >
                                                        {getPriorityLabel(
                                                            priority,
                                                        )}
                                                    </span>
                                                </div>

                                                <div className="mt-2 flex items-start gap-2">
                                                    <ArrowRight className="mt-0.5 h-4 w-4 shrink-0 text-blue-600" />

                                                    <p className="text-sm font-medium leading-5 text-slate-700">
                                                        {action}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        {/* SCORE IMPACT */}

                                        <div className="flex shrink-0 items-center gap-5 lg:pl-6">
                                            <div className="text-right">
                                                <p className="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                                    {isEn
                                                        ? "Potential Gain"
                                                        : "Potensi Kenaikan"}
                                                </p>

                                                <p className="mt-1 text-xl font-black tabular-nums text-emerald-600">
                                                    +
                                                    {formatNumber(
                                                        potentialGain,
                                                    )}{" "}
                                                    pts
                                                </p>
                                            </div>

                                            <div className="hidden h-10 w-px bg-slate-200 sm:block" />

                                            <CircleAlert className="hidden h-5 w-5 text-slate-400 sm:block" />
                                        </div>
                                    </div>

                                    {/* PROGRESS */}

                                    <div className="mt-5 border-t border-slate-100 pt-4">
                                        <div className="mb-2 flex items-center justify-between">
                                            <span className="text-xs font-medium text-slate-500">
                                                {isEn
                                                    ? "Dimension completion"
                                                    : "Kelengkapan dimensi"}
                                            </span>

                                            <span className="text-xs font-bold tabular-nums text-slate-700">
                                                {formatNumber(completion)}%
                                            </span>
                                        </div>

                                        <div className="h-2 overflow-hidden rounded-full bg-slate-100">
                                            <div
                                                className={`h-full rounded-full transition-all duration-500 ${getProgressClass(
                                                    status,
                                                )}`}
                                                style={{
                                                    width: `${Math.min(
                                                        100,
                                                        Math.max(0, completion),
                                                    )}%`,
                                                }}
                                            />
                                        </div>
                                    </div>
                                </article>
                            );
                        })}
                    </div>
                )}
            </div>

            {/* =========================================================
                FOOTER NOTE
            ========================================================= */}

            {actions.length > 0 && (
                <div className="border-t border-slate-100 bg-slate-50 px-6 py-4 md:px-8">
                    <div className="flex items-start gap-3">
                        <AlertTriangle className="mt-0.5 h-4 w-4 shrink-0 text-amber-500" />

                        <p className="text-xs leading-5 text-slate-500">
                            {isEn
                                ? "Potential gain represents the maximum contribution of each intelligence dimension to the overall readiness score when the dimension becomes complete."
                                : "Potensi kenaikan menunjukkan kontribusi maksimum setiap dimensi intelligence terhadap skor kesiapan keseluruhan apabila dimensi tersebut menjadi lengkap."}
                        </p>
                    </div>
                </div>
            )}
        </section>
    );
}
