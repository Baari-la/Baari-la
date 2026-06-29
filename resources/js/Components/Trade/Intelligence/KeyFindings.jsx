import {
    TrendingUp,
    TrendingDown,
    Globe,
    Package,
    ArrowRight,
    AlertTriangle,
} from "lucide-react";

const defaultFindings = [
    {
        icon: TrendingUp,
        color: "emerald",
        title: "Export Growth",
        value: "Loading...",
    },
    {
        icon: TrendingDown,
        color: "amber",
        title: "Import Growth",
        value: "Loading...",
    },
    {
        icon: Globe,
        color: "blue",
        title: "Top Export Market",
        value: "Loading...",
    },
    {
        icon: Package,
        color: "indigo",
        title: "Top Product",
        value: "Loading...",
    },
    {
        icon: ArrowRight,
        color: "cyan",
        title: "Fastest Growing Market",
        value: "Loading...",
    },
    {
        icon: AlertTriangle,
        color: "red",
        title: "Industry Risk",
        value: "Loading...",
    },
];

const colorClasses = {
    emerald: {
        bg: "bg-emerald-50",
        icon: "text-emerald-600",
        border: "border-emerald-200",
    },
    amber: {
        bg: "bg-amber-50",
        icon: "text-amber-600",
        border: "border-amber-200",
    },
    blue: {
        bg: "bg-blue-50",
        icon: "text-blue-600",
        border: "border-blue-200",
    },
    indigo: {
        bg: "bg-indigo-50",
        icon: "text-indigo-600",
        border: "border-indigo-200",
    },
    cyan: {
        bg: "bg-cyan-50",
        icon: "text-cyan-600",
        border: "border-cyan-200",
    },
    red: {
        bg: "bg-red-50",
        icon: "text-red-600",
        border: "border-red-200",
    },
};

export default function KeyFindings({ findings = defaultFindings }) {
    return (
        <section className="rounded-3xl border border-slate-200 bg-white shadow-sm">
            {/* Header */}
            <div className="border-b border-slate-100 px-8 py-6">
                <h2 className="text-2xl font-bold text-slate-900">
                    Key Findings
                </h2>

                <p className="mt-2 text-sm text-slate-500">
                    Highlights generated from the latest Indonesia textile trade
                    performance.
                </p>
            </div>

            {/* Cards */}
            <div className="grid gap-5 p-8 md:grid-cols-2 xl:grid-cols-3">
                {findings.map((item, index) => {
                    const Icon = item.icon;

                    const colors =
                        colorClasses[item.color] ?? colorClasses.blue;

                    return (
                        <div
                            key={index}
                            className={`rounded-2xl border ${colors.border} ${colors.bg} p-5 transition hover:shadow-md`}
                        >
                            <div className="flex items-center gap-4">
                                <div className="rounded-xl bg-white p-3 shadow-sm">
                                    <Icon size={22} className={colors.icon} />
                                </div>

                                <div className="flex-1">
                                    <p className="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        {item.title}
                                    </p>

                                    <p className="mt-1 text-lg font-bold text-slate-900">
                                        {item.value}
                                    </p>
                                </div>
                            </div>
                        </div>
                    );
                })}
            </div>
        </section>
    );
}
