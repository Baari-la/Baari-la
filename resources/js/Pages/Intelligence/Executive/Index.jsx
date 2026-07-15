import WebsiteLayout from "@/Layouts/WebsiteLayout";
import KPIGrid from "@/Components/Intelligence/Executive/KPIGrid";
import IntelligenceSummary from "@/Components/Intelligence/Executive/IntelligenceSummary";
import EarlyWarningCard from "@/Components/Intelligence/Executive/EarlyWarningCard";

export default function Index({
    stats = {},
    intelligence = {},
    warnings = [],
}) {
    return (
        <WebsiteLayout title="Executive Dashboard">
            <div className="space-y-6">
                {/* =======================================================
                    HERO
                ======================================================= */}

                <div className="rounded-2xl bg-white shadow-sm p-8">
                    <div className="flex items-center justify-between">
                        <div>
                            <h1 className="text-3xl font-bold text-slate-900">
                                DIGESTEX GLOBAL TEXTILE INTELLIGENCE
                            </h1>

                            <p className="mt-2 text-slate-600">
                                Executive dashboard for market, company, supply
                                chain and knowledge intelligence.
                            </p>
                        </div>

                        <div className="text-right">
                            <div className="text-sm text-slate-500">
                                Knowledge Graph
                            </div>

                            <div className="text-2xl font-bold">PASSED</div>
                        </div>
                    </div>
                </div>

                {/* =======================================================
                    KPI GRID
                ======================================================= */}

                <KPIGrid stats={stats} />

                {/* =======================================================
                    INTELLIGENCE SUMMARY
                ======================================================= */}

                <IntelligenceSummary intelligence={intelligence} />

                {/* =======================================================
                    TWO COLUMNS
                ======================================================= */}

                <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <EarlyWarningCard warnings={warnings} />

                    <div className="rounded-2xl bg-white p-6 shadow-sm">
                        <h2 className="text-lg font-semibold mb-4">
                            AI Recommendations
                        </h2>

                        <ul className="space-y-3">
                            <li>
                                • Export demand for ASEAN market remains stable.
                            </li>

                            <li>
                                • Increase visibility for companies without
                                certifications.
                            </li>

                            <li>
                                • Expand buyer discovery for garment exporters.
                            </li>

                            <li>
                                • Monitor import surge in selected HS Codes.
                            </li>
                        </ul>
                    </div>
                </div>

                {/* =======================================================
                    KNOWLEDGE GRAPH HEALTH
                ======================================================= */}

                <div className="rounded-2xl bg-white p-6 shadow-sm">
                    <h2 className="text-lg font-semibold mb-4">
                        Knowledge Graph Health
                    </h2>

                    <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <p className="text-sm text-slate-500">Nodes</p>

                            <p className="text-2xl font-bold">
                                {stats.nodes ?? 16}
                            </p>
                        </div>

                        <div>
                            <p className="text-sm text-slate-500">Edges</p>

                            <p className="text-2xl font-bold">
                                {stats.edges ?? 18}
                            </p>
                        </div>

                        <div>
                            <p className="text-sm text-slate-500">Warnings</p>

                            <p className="text-2xl font-bold">
                                {stats.warnings ?? 4}
                            </p>
                        </div>

                        <div>
                            <p className="text-sm text-slate-500">Validation</p>

                            <p className="text-2xl font-bold text-green-600">
                                PASSED
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </WebsiteLayout>
    );
}
