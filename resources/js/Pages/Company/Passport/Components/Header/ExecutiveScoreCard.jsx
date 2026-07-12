import { Award, ShieldCheck, TrendingUp } from "lucide-react";

export default function ExecutiveScoreCard({ passport }) {
    const score = passport?.scores?.score ?? {};

    const modules = [
        {
            label: "Capability",
            value: score.capability ?? 0,
        },

        {
            label: "Compliance",
            value: score.compliance ?? 0,
        },

        {
            label: "Market",
            value: score.market ?? 0,
        },

        {
            label: "Supply Chain",
            value: score.supply_chain ?? 0,
        },

        {
            label: "Business Readiness",
            value: score.readiness ?? 0,
        },
    ];

    return (
        <div className="rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 p-8 text-white shadow-xl">
            {/* Header */}

            <div className="flex items-center gap-3">
                <Award className="h-6 w-6 text-amber-400" />

                <div>
                    <div className="text-xs uppercase tracking-[0.25em] text-slate-300">
                        Executive Intelligence Score
                    </div>

                    <div className="text-sm text-slate-400">
                        DIGESTEX Company Intelligence
                    </div>
                </div>
            </div>

            {/* Score */}

            <div className="mt-8 text-center">
                <div className="text-7xl font-black">{score.overall ?? 0}</div>

                <div className="mt-2 text-2xl font-semibold">
                    {score.level ?? "-"}
                </div>

                <div className="mt-1 text-slate-300">
                    Rating {score.rating ?? "-"}
                </div>
            </div>

            {/* Progress */}

            <div className="mt-8">
                <div className="mb-2 flex justify-between text-sm">
                    <span>Overall Performance</span>

                    <span>{score.overall ?? 0}%</span>
                </div>

                <div className="h-3 rounded-full bg-white/10">
                    <div
                        className="h-3 rounded-full bg-emerald-400"
                        style={{
                            width: `${score.overall ?? 0}%`,
                        }}
                    />
                </div>
            </div>

            {/* Breakdown */}

            <div className="mt-8 space-y-4">
                {modules.map((item) => (
                    <ModuleProgress
                        key={item.label}
                        label={item.label}
                        value={item.value}
                    />
                ))}
            </div>

            {/* Footer */}

            <div className="mt-8 rounded-2xl border border-white/10 bg-white/5 p-4">
                <div className="flex items-center gap-2">
                    <ShieldCheck className="h-5 w-5 text-emerald-400" />

                    <span className="font-semibold">
                        Executive Intelligence Certified
                    </span>
                </div>
            </div>
        </div>
    );
}

function ModuleProgress({
    label,

    value,
}) {
    return (
        <div>
            <div className="mb-1 flex justify-between text-sm">
                <span>{label}</span>

                <span>{value}</span>
            </div>

            <div className="h-2 rounded-full bg-white/10">
                <div
                    className="h-2 rounded-full bg-cyan-400"
                    style={{
                        width: `${value}%`,
                    }}
                />
            </div>
        </div>
    );
}
