import {
    TrendingUp,
    TrendingDown,
    Globe,
    Package,
    DollarSign,
    Activity,
} from "lucide-react";

const defaultItems = [
    {
        title: "Export Growth",
        value: "Loading...",
        icon: TrendingUp,
        color: "emerald",
    },
    {
        title: "Import Growth",
        value: "Loading...",
        icon: TrendingDown,
        color: "amber",
    },
    {
        title: "Trade Balance",
        value: "Loading...",
        icon: DollarSign,
        color: "blue",
    },
    {
        title: "Top Export Market",
        value: "Loading...",
        icon: Globe,
        color: "indigo",
    },
    {
        title: "Top Product",
        value: "Loading...",
        icon: Package,
        color: "cyan",
    },
    {
        title: "Market Status",
        value: "Monitoring",
        icon: Activity,
        color: "rose",
    },
];

const colors = {
    emerald: {
        bg: "bg-emerald-50",
        icon: "text-emerald-600",
    },
    amber: {
        bg: "bg-amber-50",
        icon: "text-amber-600",
    },
    blue: {
        bg: "bg-blue-50",
        icon: "text-blue-600",
    },
    indigo: {
        bg: "bg-indigo-50",
        icon: "text-indigo-600",
    },
    cyan: {
        bg: "bg-cyan-50",
        icon: "text-cyan-600",
    },
    rose: {
        bg: "bg-rose-50",
        icon: "text-rose-600",
    },
};

export default function TodaysIndustrySnapshot({ snapshot = defaultItems }) {
    return (
        <section className="py-14">
            <div className="mx-auto max-w-7xl px-6">
                <div className="mb-8">
                    <p className="text-sm font-semibold uppercase tracking-[0.30em] text-blue-600">
                        Today's Industry Snapshot
                    </p>

                    <h2 className="mt-2 text-4xl font-black text-slate-900">
                        Indonesia Textile Industry Today
                    </h2>

                    <p className="mt-3 max-w-3xl text-lg text-slate-600">
                        A quick overview of Indonesia's latest textile industry
                        indicators generated from official trade statistics and
                        Digestex Intelligence.
                    </p>
                </div>

                <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
                    {snapshot.map((item, index) => {
                        const Icon = item.icon;

                        const color = colors[item.color] || colors.blue;

                        return (
                            <div
                                key={index}
                                className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg"
                            >
                                <div
                                    className={`inline-flex rounded-2xl ${color.bg} p-3`}
                                >
                                    <Icon size={22} className={color.icon} />
                                </div>

                                <p className="mt-5 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    {item.title}
                                </p>

                                <h3 className="mt-2 text-xl font-bold text-slate-900">
                                    {item.value}
                                </h3>
                            </div>
                        );
                    })}
                </div>
            </div>
        </section>
    );
}
