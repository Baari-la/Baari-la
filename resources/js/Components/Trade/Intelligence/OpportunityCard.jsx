import { TrendingUp, ArrowUpRight } from "lucide-react";

const defaultOpportunities = [
    "Growing export demand in ASEAN markets.",
    "Increasing demand for polyester-based products.",
    "Expansion opportunities in the Middle East.",
];

export default function OpportunityCard({
    title = "Business Opportunities",

    opportunities = defaultOpportunities,
}) {
    return (
        <div className="rounded-3xl border border-emerald-200 bg-white shadow-sm">
            {/* Header */}

            <div className="border-b border-emerald-100 bg-emerald-50 px-6 py-5">
                <div className="flex items-center gap-3">
                    <div className="rounded-xl bg-emerald-100 p-3">
                        <TrendingUp size={22} className="text-emerald-600" />
                    </div>

                    <div>
                        <h3 className="text-xl font-bold text-slate-900">
                            {title}
                        </h3>

                        <p className="mt-1 text-sm text-slate-600">
                            Executive opportunities identified from trade
                            intelligence.
                        </p>
                    </div>
                </div>
            </div>

            {/* Content */}

            <div className="space-y-4 p-6">
                {opportunities.length === 0 ? (
                    <p className="text-slate-500">No opportunity identified.</p>
                ) : (
                    opportunities.map((item, index) => (
                        <div
                            key={index}
                            className="flex items-start gap-3 rounded-xl border border-slate-100 p-4 hover:bg-slate-50"
                        >
                            <ArrowUpRight
                                size={18}
                                className="mt-1 text-emerald-600"
                            />

                            <p className="leading-7 text-slate-700">{item}</p>
                        </div>
                    ))
                )}
            </div>
        </div>
    );
}
