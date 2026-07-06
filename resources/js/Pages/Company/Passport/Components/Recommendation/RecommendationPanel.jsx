import {
    CheckCircle2,
    AlertTriangle,
    TrendingUp,
    ArrowRight,
    ShieldCheck,
    Factory,
    Globe,
} from "lucide-react";

export default function RecommendationPanel({ passport }) {
    /*
    |--------------------------------------------------------------------------
    | Temporary Recommendation
    |--------------------------------------------------------------------------
    | Sprint 1
    |
    | Future:
    | Recommendation will come from Executive AI.
    |
    */

    const recommendations = [
        {
            title: "Upload Factory Photos",

            priority: "High",
        },

        {
            title: "Add Production Capacity",

            priority: "High",
        },

        {
            title: "Complete Lead Time",

            priority: "Medium",
        },

        {
            title: "Add GRS Certificate",

            priority: "High",
        },

        {
            title: "Complete Export Markets",

            priority: "Medium",
        },
    ];

    return (
        <div className="space-y-6">
            {/* ==========================================================
                Executive Recommendation
            ========================================================== */}

            <div className="rounded-2xl border bg-white shadow-sm">
                <div className="border-b px-6 py-5">
                    <div className="flex items-center gap-3">
                        <TrendingUp className="h-6 w-6 text-blue-600" />

                        <div>
                            <h2 className="text-2xl font-bold">
                                Executive Recommendation
                            </h2>

                            <p className="mt-1 text-sm text-slate-500">
                                Business recommendations generated from Digital
                                Company Passport.
                            </p>
                        </div>
                    </div>
                </div>

                <div className="grid gap-6 p-6 lg:grid-cols-3">
                    {/* Priority */}

                    <div className="rounded-xl bg-emerald-50 p-6">
                        <div className="flex items-center gap-3">
                            <CheckCircle2 className="h-6 w-6 text-emerald-600" />

                            <h3 className="font-bold">Current Status</h3>
                        </div>

                        <div className="mt-5">
                            <span className="rounded-full bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">
                                Export Ready
                            </span>
                        </div>

                        <p className="mt-5 text-sm leading-6 text-slate-600">
                            The company has established a solid digital profile
                            and is ready to improve its competitiveness by
                            completing the remaining business information.
                        </p>
                    </div>

                    {/* Strength */}

                    <div className="rounded-xl bg-blue-50 p-6">
                        <div className="flex items-center gap-3">
                            <ShieldCheck className="h-6 w-6 text-blue-600" />

                            <h3 className="font-bold">Strengths</h3>
                        </div>

                        <ul className="mt-5 space-y-3 text-sm">
                            <li className="flex gap-2">
                                <ArrowRight className="mt-0.5 h-4 w-4 text-blue-600" />
                                Manufacturing Capability
                            </li>

                            <li className="flex gap-2">
                                <ArrowRight className="mt-0.5 h-4 w-4 text-blue-600" />
                                Business Profile
                            </li>

                            <li className="flex gap-2">
                                <ArrowRight className="mt-0.5 h-4 w-4 text-blue-600" />
                                Export Experience
                            </li>

                            <li className="flex gap-2">
                                <ArrowRight className="mt-0.5 h-4 w-4 text-blue-600" />
                                Global Visibility
                            </li>
                        </ul>
                    </div>

                    {/* Improvement */}

                    <div className="rounded-xl bg-amber-50 p-6">
                        <div className="flex items-center gap-3">
                            <AlertTriangle className="h-6 w-6 text-amber-600" />

                            <h3 className="font-bold">Improvement Focus</h3>
                        </div>

                        <ul className="mt-5 space-y-3 text-sm">
                            <li className="flex gap-2">
                                <ArrowRight className="mt-0.5 h-4 w-4 text-amber-600" />
                                Complete Compliance Profile
                            </li>

                            <li className="flex gap-2">
                                <ArrowRight className="mt-0.5 h-4 w-4 text-amber-600" />
                                Add Factory Verification
                            </li>

                            <li className="flex gap-2">
                                <ArrowRight className="mt-0.5 h-4 w-4 text-amber-600" />
                                Improve Supply Chain Data
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {/* ==========================================================
                Next Recommended Actions
            ========================================================== */}

            <div className="rounded-2xl border bg-white shadow-sm">
                <div className="border-b px-6 py-5">
                    <div className="flex items-center gap-3">
                        <Factory className="h-6 w-6 text-indigo-600" />

                        <div>
                            <h2 className="text-2xl font-bold">
                                Next Recommended Actions
                            </h2>

                            <p className="mt-1 text-sm text-slate-500">
                                Recommended improvements to increase your
                                Digital Company Passport quality.
                            </p>
                        </div>
                    </div>
                </div>

                <div className="divide-y">
                    {recommendations.map((item) => (
                        <div
                            key={item.title}
                            className="flex items-center justify-between px-6 py-5"
                        >
                            <div className="flex items-center gap-4">
                                <CheckCircle2 className="h-5 w-5 text-emerald-600" />

                                <span>{item.title}</span>
                            </div>

                            <span
                                className={
                                    item.priority === "High"
                                        ? "rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700"
                                        : "rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700"
                                }
                            >
                                {item.priority}
                            </span>
                        </div>
                    ))}
                </div>
            </div>

            {/* ==========================================================
                Future Executive AI
            ========================================================== */}

            <div className="rounded-2xl border bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 p-8 text-white shadow-sm">
                <div className="flex items-center gap-3">
                    <Globe className="h-7 w-7 text-cyan-400" />

                    <div>
                        <h2 className="text-2xl font-bold">
                            Executive AI Insight
                        </h2>

                        <p className="mt-2 text-slate-300">
                            Available in the next intelligence phase.
                        </p>
                    </div>
                </div>

                <div className="mt-8 rounded-xl border border-white/10 bg-white/5 p-6">
                    <p className="leading-8 text-slate-200">
                        Executive AI will analyze your company profile,
                        manufacturing capability, compliance, sustainability,
                        export experience, supply chain readiness, buyer
                        matching, business opportunities, and market
                        intelligence to generate executive-level
                        recommendations.
                    </p>
                </div>
            </div>
        </div>
    );
}
