import { AlertTriangle, ShieldAlert } from "lucide-react";

const defaultRisks = [
    "Volatility in global raw material prices.",
    "Increasing competition from regional exporters.",
    "Potential slowdown in key export markets.",
];

export default function RiskCard({
    title = "Industry Risks",

    risks = defaultRisks,
}) {
    return (
        <div className="rounded-3xl border border-red-200 bg-white shadow-sm">
            {/* Header */}

            <div className="border-b border-red-100 bg-red-50 px-6 py-5">
                <div className="flex items-center gap-3">
                    <div className="rounded-xl bg-red-100 p-3">
                        <ShieldAlert size={22} className="text-red-600" />
                    </div>

                    <div>
                        <h3 className="text-xl font-bold text-slate-900">
                            {title}
                        </h3>

                        <p className="mt-1 text-sm text-slate-600">
                            Potential risks identified from the latest trade
                            intelligence.
                        </p>
                    </div>
                </div>
            </div>

            {/* Content */}

            <div className="space-y-4 p-6">
                {risks.length === 0 ? (
                    <p className="text-slate-500">
                        No significant risks identified.
                    </p>
                ) : (
                    risks.map((item, index) => (
                        <div
                            key={index}
                            className="flex items-start gap-3 rounded-xl border border-slate-100 p-4 transition hover:bg-slate-50"
                        >
                            <AlertTriangle
                                size={18}
                                className="mt-1 text-red-600"
                            />

                            <p className="leading-7 text-slate-700">{item}</p>
                        </div>
                    ))
                )}
            </div>
        </div>
    );
}
