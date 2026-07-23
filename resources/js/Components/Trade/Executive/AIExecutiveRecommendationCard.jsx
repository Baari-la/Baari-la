import { usePage } from "@inertiajs/react";
import { Brain, ArrowRight, Sparkles, CheckCircle2 } from "lucide-react";

export default function AIExecutiveRecommendationCard({
    dataPeriod = "January-April 2026",
    recommendations = [],
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

            <div
                className="
                    border-b
                    border-slate-200
                    bg-gradient-to-r
                    from-violet-900
                    to-indigo-900
                    px-6
                    py-6
                    text-white
                "
            >
                <div className="flex items-center gap-3">
                    <Brain size={28} />

                    <div>
                        <h2 className="text-2xl font-bold">DIGESTEX AI</h2>

                        <p className="mt-1 text-sm text-violet-200">
                            {isEn
                                ? "Executive Recommendations"
                                : "Rekomendasi Eksekutif"}
                        </p>
                    </div>
                </div>

                <p className="mt-4 text-sm text-violet-100">
                    {isEn ? "Reporting Period" : "Periode Pelaporan"}:{" "}
                    {dataPeriod}
                </p>
            </div>

            {/* Hero */}

            <div className="border-b px-6 py-6">
                <div
                    className="
                        rounded-2xl
                        bg-violet-50
                        p-5
                    "
                >
                    <div className="flex items-center gap-2">
                        <Sparkles size={18} className="text-violet-700" />

                        <p
                            className="
                                text-sm
                                font-bold
                                uppercase
                                tracking-wider
                                text-violet-700
                            "
                        >
                            {isEn ? "AI Insight" : "Insight AI"}
                        </p>
                    </div>

                    <p
                        className="
                            mt-3
                            text-lg
                            leading-8
                            text-slate-800
                        "
                    >
                        {isEn
                            ? "DIGESTEX AI continuously evaluates global textile trade patterns and recommends strategic actions to maximize opportunities while mitigating risks."
                            : "DIGESTEX AI secara berkelanjutan menganalisis pola perdagangan tekstil global dan memberikan rekomendasi strategis untuk memaksimalkan peluang serta mengurangi risiko."}
                    </p>
                </div>
            </div>

            {/* Recommendations */}

            <div className="p-6">
                <div className="space-y-4">
                    {recommendations.map((recommendation, index) => (
                        <div
                            key={index}
                            className="
                                    rounded-2xl
                                    border
                                    border-slate-200
                                    p-5
                                    transition
                                    hover:shadow-md
                                "
                        >
                            <div className="flex gap-4">
                                <div
                                    className="
                                            flex
                                            h-10
                                            w-10
                                            items-center
                                            justify-center
                                            rounded-full
                                            bg-violet-100
                                            text-sm
                                            font-bold
                                            text-violet-700
                                        "
                                >
                                    {index + 1}
                                </div>

                                <div className="flex-1">
                                    <div className="flex items-center gap-2">
                                        <CheckCircle2
                                            size={18}
                                            className="text-emerald-600"
                                        />

                                        <p
                                            className="
                                                    font-semibold
                                                    text-slate-900
                                                "
                                        >
                                            {recommendation.title}
                                        </p>
                                    </div>

                                    <p
                                        className="
                                                mt-2
                                                text-sm
                                                leading-7
                                                text-slate-600
                                            "
                                    >
                                        {recommendation.description}
                                    </p>

                                    {recommendation.priority && (
                                        <span
                                            className="
                                                    mt-4
                                                    inline-flex
                                                    rounded-full
                                                    bg-orange-100
                                                    px-3
                                                    py-1
                                                    text-xs
                                                    font-bold
                                                    text-orange-700
                                                "
                                        >
                                            {recommendation.priority}
                                        </span>
                                    )}
                                </div>
                            </div>
                        </div>
                    ))}

                    {recommendations.length === 0 && (
                        <div className="py-10 text-center">
                            <Brain
                                size={42}
                                className="
                                    mx-auto
                                    text-violet-500
                                "
                            />

                            <p className="mt-4 text-slate-600">
                                {isEn
                                    ? "No recommendations available."
                                    : "Belum ada rekomendasi tersedia."}
                            </p>
                        </div>
                    )}
                </div>
            </div>

            {/* Footer */}

            <div
                className="
                    border-t
                    bg-slate-50
                    px-6
                    py-4
                "
            >
                <button
                    className="
                        flex
                        items-center
                        gap-2
                        text-sm
                        font-semibold
                        text-violet-700
                        hover:text-violet-900
                    "
                >
                    {isEn
                        ? "View Full Executive Report"
                        : "Lihat Laporan Eksekutif Lengkap"}

                    <ArrowRight size={16} />
                </button>
            </div>
        </div>
    );
}
