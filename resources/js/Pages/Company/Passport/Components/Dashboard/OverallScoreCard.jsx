import {
    Award,
    ShieldCheck,
    Factory,
    Globe,
    Truck,
    Briefcase,
    Target,
} from "lucide-react";

export default function OverallScoreCard({ passport }) {
    const scores = passport.passport?.scorecard?.scorecard ?? {};

    const cards = [
        {
            title: "Capability",
            icon: Factory,
            value: scores.capability?.score ?? "--",
        },

        {
            title: "Compliance",
            icon: ShieldCheck,
            value: scores.compliance?.score ?? "--",
        },

        {
            title: "Market",
            icon: Globe,
            value: scores.market?.score ?? "--",
        },

        {
            title: "Supply Chain",
            icon: Truck,
            value: scores.supply_chain?.score ?? "--",
        },

        {
            title: "Readiness",
            icon: Briefcase,
            value: scores.readiness?.score ?? "--",
        },

        {
            title: "Matching",
            icon: Target,
            value: scores.matching?.score ?? "--",
        },
    ];

    return (
        <div className="space-y-6">
            <div className="rounded-2xl border bg-white p-8 shadow-sm">
                <div className="flex items-center justify-between">
                    <div>
                        <h2 className="text-2xl font-bold">
                            Overall Intelligence Score
                        </h2>

                        <p className="mt-2 text-sm text-slate-500">
                            Consolidated score generated from all intelligence
                            engines.
                        </p>
                    </div>

                    <div className="text-center">
                        <Award className="mx-auto mb-3 h-10 w-10 text-amber-500" />

                        <div className="text-6xl font-bold text-slate-900">
                            {scores.overall?.score ?? "--"}
                        </div>
                    </div>
                </div>
            </div>

            <div className="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                {cards.map((card) => {
                    const Icon = card.icon;

                    return (
                        <div
                            key={card.title}
                            className="rounded-xl border bg-white p-6 shadow-sm"
                        >
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-sm text-slate-500">
                                        {card.title}
                                    </p>

                                    <h3 className="mt-2 text-3xl font-bold">
                                        {card.value}
                                    </h3>
                                </div>

                                <Icon className="h-8 w-8 text-slate-500" />
                            </div>
                        </div>
                    );
                })}
            </div>
        </div>
    );
}
