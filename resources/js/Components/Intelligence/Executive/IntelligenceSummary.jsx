import { Activity, Building2, Globe2, Network } from "lucide-react";

export default function IntelligenceSummary({ intelligence = {} }) {
    const items = [
        {
            label: "Company Intelligence",
            description:
                "Company capability, readiness, compliance and business profile intelligence.",
            value: intelligence.company ?? intelligence.companies ?? 0,
            icon: Building2,
        },
        {
            label: "Market Intelligence",
            description:
                "Trade flows, market opportunities and global textile market signals.",
            value: intelligence.market ?? intelligence.markets ?? 0,
            icon: Globe2,
        },
        {
            label: "Supply Chain Intelligence",
            description:
                "Supplier connectivity, sourcing capability and supply chain visibility.",
            value: intelligence.supply_chain ?? intelligence.supplyChain ?? 0,
            icon: Network,
        },
        {
            label: "Intelligence Signals",
            description:
                "Strategic signals generated across the DIGESTEX intelligence ecosystem.",
            value: intelligence.signals ?? intelligence.alerts ?? 0,
            icon: Activity,
        },
    ];

    return (
        <section className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div>
                <p className="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                    Intelligence Overview
                </p>

                <h2 className="mt-1 text-xl font-bold text-slate-900">
                    Executive Intelligence Summary
                </h2>

                <p className="mt-2 text-sm text-slate-500">
                    Consolidated view of intelligence across company, market,
                    and supply chain dimensions.
                </p>
            </div>

            <div className="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                {items.map((item) => {
                    const Icon = item.icon;

                    return (
                        <div
                            key={item.label}
                            className="rounded-2xl border border-slate-100 bg-slate-50 p-5"
                        >
                            <div className="flex items-start justify-between gap-4">
                                <div className="min-w-0">
                                    <p className="text-sm font-bold text-slate-900">
                                        {item.label}
                                    </p>

                                    <p className="mt-2 text-xs leading-5 text-slate-500">
                                        {item.description}
                                    </p>
                                </div>

                                <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white shadow-sm">
                                    <Icon className="h-5 w-5 text-slate-600" />
                                </div>
                            </div>

                            <div className="mt-5">
                                <span className="text-2xl font-black tabular-nums text-slate-900">
                                    {item.value}
                                </span>
                            </div>
                        </div>
                    );
                })}
            </div>
        </section>
    );
}
