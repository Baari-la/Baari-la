import WebsiteLayout from "@/Layouts/WebsiteLayout";

export default function Index({
    company = {},
    scores = {},
    recommendations = [],
}) {
    return (
        <WebsiteLayout title="Company Intelligence">
            <div className="space-y-6">
                {/* ===================================================
                    HERO
                =================================================== */}

                <div className="bg-white rounded-2xl shadow-sm p-8">
                    <h1 className="text-3xl font-bold text-slate-900">
                        Company Intelligence
                    </h1>

                    <p className="mt-2 text-slate-600">
                        AI-powered company intelligence across capability,
                        compliance, market readiness, and supply chain
                        performance.
                    </p>
                </div>

                {/* ===================================================
                    COMPANY PROFILE
                =================================================== */}

                <div className="bg-white rounded-2xl shadow-sm p-6">
                    <div className="flex justify-between">
                        <div>
                            <h2 className="text-2xl font-bold">
                                {company.name ?? "PT DIGESTEX GLOBAL"}
                            </h2>

                            <p className="text-slate-500">
                                {company.sector ?? "Textile Intelligence"}
                            </p>
                        </div>

                        <div className="text-right">
                            <p className="text-sm text-slate-500">
                                Visibility Score
                            </p>

                            <p className="text-3xl font-bold text-indigo-600">
                                {scores.visibility ?? 92}
                            </p>
                        </div>
                    </div>
                </div>

                {/* ===================================================
                    SCORE CARDS
                =================================================== */}

                <div
                    className="
                    grid
                    grid-cols-1
                    md:grid-cols-2
                    lg:grid-cols-3
                    gap-6
                "
                >
                    <ScoreCard
                        title="Capability"
                        score={scores.capability ?? 90}
                    />

                    <ScoreCard
                        title="Compliance"
                        score={scores.compliance ?? 87}
                    />

                    <ScoreCard title="Market" score={scores.market ?? 94} />

                    <ScoreCard
                        title="Supply Chain"
                        score={scores.supply_chain ?? 88}
                    />

                    <ScoreCard
                        title="Readiness"
                        score={scores.readiness ?? 91}
                    />

                    <ScoreCard title="Overall" score={scores.overall ?? 90} />
                </div>

                {/* ===================================================
                    PASSPORT
                =================================================== */}

                <div className="bg-white rounded-2xl shadow-sm p-6">
                    <h2 className="text-lg font-semibold mb-6">
                        Company Passport
                    </h2>

                    <div className="grid grid-cols-2 gap-6">
                        <InfoItem
                            label="Products"
                            value={company.products ?? "12 Products"}
                        />

                        <InfoItem
                            label="Markets"
                            value={company.markets ?? "ASEAN, EU, USA"}
                        />

                        <InfoItem
                            label="Certifications"
                            value={company.certifications ?? "OEKO-TEX®, GRS"}
                        />

                        <InfoItem
                            label="Employees"
                            value={company.employees ?? "1,250"}
                        />

                        <InfoItem
                            label="Production Capacity"
                            value={company.capacity ?? "500 Tons/Month"}
                        />

                        <InfoItem
                            label="Lead Time"
                            value={company.lead_time ?? "30 Days"}
                        />
                    </div>
                </div>

                {/* ===================================================
                    RECOMMENDATIONS
                =================================================== */}

                <div className="bg-white rounded-2xl shadow-sm p-6">
                    <h2 className="text-lg font-semibold mb-4">
                        AI Recommendations
                    </h2>

                    <ul className="space-y-3">
                        {(recommendations.length > 0
                            ? recommendations
                            : [
                                  "Add additional export certifications.",
                                  "Improve supply chain visibility.",
                                  "Expand presence in EU markets.",
                                  "Increase profile completeness.",
                              ]
                        ).map((item, index) => (
                            <li key={index}>• {item}</li>
                        ))}
                    </ul>
                </div>
            </div>
        </WebsiteLayout>
    );
}

/* ==========================================================
   COMPONENTS
========================================================== */

function ScoreCard({ title, score }) {
    return (
        <div className="bg-white rounded-2xl p-6 shadow-sm">
            <p className="text-sm text-slate-500">{title}</p>

            <p className="text-3xl font-bold mt-2">{score}</p>
        </div>
    );
}

function InfoItem({ label, value }) {
    return (
        <div>
            <p className="text-sm text-slate-500">{label}</p>

            <p className="font-semibold">{value}</p>
        </div>
    );
}
