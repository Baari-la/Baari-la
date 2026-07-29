import { Building2, Globe2, Network, TriangleAlert } from "lucide-react";

export default function KPIGrid({ stats = {} }) {
    const items = [
        {
            label: "Companies",
            value: stats.companies ?? 0,
            icon: Building2,
        },
        {
            label: "Markets",
            value: stats.markets ?? 0,
            icon: Globe2,
        },
        {
            label: "Knowledge Nodes",
            value: stats.nodes ?? 0,
            icon: Network,
        },
        {
            label: "Warnings",
            value: stats.warnings ?? 0,
            icon: TriangleAlert,
        },
    ];

    return (
        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            {items.map((item) => {
                const Icon = item.icon;

                return (
                    <div
                        key={item.label}
                        className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"
                    >
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-xs font-bold uppercase tracking-wider text-slate-400">
                                    {item.label}
                                </p>

                                <p className="mt-2 text-3xl font-black text-slate-900">
                                    {item.value}
                                </p>
                            </div>

                            <div className="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100">
                                <Icon className="h-5 w-5 text-slate-600" />
                            </div>
                        </div>
                    </div>
                );
            })}
        </div>
    );
}
