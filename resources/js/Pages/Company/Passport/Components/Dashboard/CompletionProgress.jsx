import { CheckCircle2, Circle } from "lucide-react";

export default function CompletionProgress({ passport }) {
    const capability =
        passport.passport?.capability?.completeness?.percentage ?? 0;

    const compliance =
        passport.passport?.compliance?.completeness?.percentage ?? 0;

    const market = passport.passport?.market?.completeness?.percentage ?? 0;

    const supplyChain =
        passport.passport?.supply_chain?.completeness?.percentage ?? 0;

    const readiness =
        passport.passport?.readiness?.completeness?.percentage ?? 0;

    const sections = [
        {
            name: "Capability",
            value: capability,
        },

        {
            name: "Compliance",
            value: compliance,
        },

        {
            name: "Market",
            value: market,
        },

        {
            name: "Supply Chain",
            value: supplyChain,
        },

        {
            name: "Business Readiness",
            value: readiness,
        },
    ];

    return (
        <div className="rounded-2xl border bg-white p-8 shadow-sm">
            <h2 className="text-2xl font-bold">Company Profile Completion</h2>

            <p className="mt-2 text-sm text-slate-500">
                Complete company information to improve intelligence quality and
                matching opportunities.
            </p>

            <div className="mt-8 space-y-6">
                {sections.map((section) => (
                    <div key={section.name}>
                        <div className="mb-2 flex items-center justify-between">
                            <div className="flex items-center gap-2">
                                {section.value >= 100 ? (
                                    <CheckCircle2 className="h-5 w-5 text-emerald-600" />
                                ) : (
                                    <Circle className="h-5 w-5 text-slate-400" />
                                )}

                                <span className="font-medium">
                                    {section.name}
                                </span>
                            </div>

                            <span className="font-semibold">
                                {section.value}%
                            </span>
                        </div>

                        <div className="h-3 overflow-hidden rounded-full bg-slate-200">
                            <div
                                className="h-full rounded-full bg-emerald-600 transition-all"
                                style={{
                                    width: `${section.value}%`,
                                }}
                            />
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}
