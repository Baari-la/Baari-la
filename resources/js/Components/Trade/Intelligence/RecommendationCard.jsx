import { Lightbulb, CheckCircle2, ArrowRight } from "lucide-react";

const defaultRecommendations = [
    "Strengthen export expansion into high-growth ASEAN markets.",
    "Monitor raw material price volatility and optimize procurement planning.",
    "Diversify export destinations to reduce dependency on traditional markets.",
];

export default function RecommendationCard({
    title = "Executive Recommendations",

    recommendation = defaultRecommendations,
}) {
    const recommendations = Array.isArray(recommendation)
        ? recommendation
        : [recommendation];

    return (
        <div className="rounded-3xl border border-blue-200 bg-gradient-to-br from-blue-50 via-white to-white shadow-sm">
            {/* Header */}

            <div className="border-b border-blue-100 px-8 py-6">
                <div className="flex items-center gap-4">
                    <div className="rounded-2xl bg-blue-100 p-3">
                        <Lightbulb size={24} className="text-blue-600" />
                    </div>

                    <div>
                        <p className="text-xs font-semibold uppercase tracking-[0.25em] text-blue-600">
                            Digestex Intelligence
                        </p>

                        <h2 className="mt-1 text-2xl font-bold text-slate-900">
                            {title}
                        </h2>
                    </div>
                </div>
            </div>

            {/* Recommendations */}

            <div className="space-y-5 p-8">
                {recommendations.map((item, index) => (
                    <div
                        key={index}
                        className="flex items-start gap-4 rounded-2xl border border-slate-200 bg-white p-5 transition hover:shadow-sm"
                    >
                        <CheckCircle2
                            size={22}
                            className="mt-1 text-emerald-600"
                        />

                        <div className="flex-1">
                            <h4 className="font-semibold text-slate-900">
                                Recommendation {index + 1}
                            </h4>

                            <p className="mt-2 leading-7 text-slate-600">
                                {item}
                            </p>
                        </div>
                    </div>
                ))}
            </div>

            {/* Footer */}

            <div className="flex items-center justify-between rounded-b-3xl border-t border-blue-100 bg-slate-50 px-8 py-5">
                <p className="text-sm text-slate-500">
                    Recommendations are generated using official trade
                    statistics processed by the Digestex Intelligence Engine.
                </p>

                <button className="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                    View Full Intelligence
                    <ArrowRight size={18} />
                </button>
            </div>
        </div>
    );
}
