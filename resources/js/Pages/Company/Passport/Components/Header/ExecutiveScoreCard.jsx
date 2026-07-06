import {
    Award,
    Factory,
    ShieldCheck,
    Globe2,
    Truck,
    CheckCircle2,
} from "lucide-react";

export default function ExecutiveScoreCard({ scores = {} }) {
    const overall = scores.overall ?? 0;

    const level = getLevel(overall);

    const items = [
        {
            title: "Capability",
            value: scores.capability ?? 0,
            icon: Factory,
        },
        {
            title: "Compliance",
            value: scores.compliance ?? 0,
            icon: ShieldCheck,
        },
        {
            title: "Market",
            value: scores.market ?? 0,
            icon: Globe2,
        },
        {
            title: "Supply Chain",
            value: scores.supply_chain ?? 0,
            icon: Truck,
        },
        {
            title: "Business Readiness",
            value: scores.readiness ?? 0,
            icon: CheckCircle2,
        },
    ];

    return (
        <div className="rounded-2xl border border-white/10 bg-white/10 p-6 backdrop-blur-lg">
            {/* Overall */}

            <div className="text-center">
                <div className="flex justify-center">
                    <Award className="h-10 w-10 text-amber-400" />
                </div>

                <div className="mt-2 text-xs uppercase tracking-[0.25em] text-slate-300">
                    Executive Intelligence Score
                </div>

                <div className="mt-2 text-6xl font-bold text-white">
                    {overall}
                </div>

                <div className="mt-2 inline-flex rounded-full bg-emerald-500/20 px-4 py-1 text-sm font-semibold text-emerald-300">
                    {level}
                </div>
            </div>

            {/* Divider */}

            <div className="my-6 border-t border-white/10" />

            {/* Detail Scores */}

            <div className="space-y-4">
                {items.map((item) => (
                    <ScoreRow key={item.title} {...item} />
                ))}
            </div>
        </div>
    );
}

function ScoreRow({ title, value, icon: Icon }) {
    return (
        <div>
            <div className="mb-2 flex items-center justify-between">
                <div className="flex items-center gap-2">
                    <Icon className="h-4 w-4 text-slate-300" />

                    <span className="text-sm text-slate-200">{title}</span>
                </div>

                <span className="font-semibold text-white">{value}</span>
            </div>

            <div className="h-2 overflow-hidden rounded-full bg-white/10">
                <div
                    className={progressColor(value)}
                    style={{
                        width: `${value}%`,
                    }}
                />
            </div>
        </div>
    );
}

function getLevel(score) {
    if (score >= 95) return "World Class";

    if (score >= 90) return "Excellent";

    if (score >= 80) return "Export Ready";

    if (score >= 70) return "Developing";

    if (score >= 60) return "Emerging";

    return "Needs Improvement";
}

function progressColor(score) {
    if (score >= 95) return "h-full bg-emerald-400";

    if (score >= 90) return "h-full bg-green-400";

    if (score >= 80) return "h-full bg-blue-400";

    if (score >= 70) return "h-full bg-amber-400";

    if (score >= 60) return "h-full bg-orange-400";

    return "h-full bg-red-500";
}
