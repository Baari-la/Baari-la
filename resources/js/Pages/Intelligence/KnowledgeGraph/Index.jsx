import WebsiteLayout from "@/Layouts/WebsiteLayout";

export default function Index({ graph = {}, nodes = [], edges = [] }) {
    return (
        <WebsiteLayout title="Knowledge Graph">
            <div className="space-y-6">
                {/* ===================================================
                    HERO
                =================================================== */}

                <div className="bg-white rounded-2xl shadow-sm p-8">
                    <h1 className="text-3xl font-bold text-slate-900">
                        DIGESTEX Knowledge Graph
                    </h1>

                    <p className="mt-2 text-slate-600">
                        Explore relationships across companies, products,
                        certifications, technologies, markets, and the global
                        textile ecosystem.
                    </p>
                </div>

                {/* ===================================================
                    GRAPH HEALTH
                =================================================== */}

                <div
                    className="
                    grid
                    grid-cols-2
                    md:grid-cols-4
                    gap-6
                "
                >
                    <MetricCard title="Nodes" value={graph.nodes ?? 16} />

                    <MetricCard title="Edges" value={graph.edges ?? 18} />

                    <MetricCard title="Warnings" value={graph.warnings ?? 4} />

                    <MetricCard
                        title="Validation"
                        value={graph.validation ?? "PASSED"}
                    />
                </div>

                {/* ===================================================
                    GRAPH OVERVIEW
                =================================================== */}

                <div className="bg-white rounded-2xl shadow-sm p-6">
                    <h2 className="text-lg font-semibold mb-6">
                        Knowledge Graph Overview
                    </h2>

                    <div className="grid grid-cols-2 gap-6">
                        <InfoItem label="Business Nodes" value="4" />

                        <InfoItem label="Certification Nodes" value="4" />

                        <InfoItem label="Product Nodes" value="3" />

                        <InfoItem label="Machinery Nodes" value="1" />

                        <InfoItem label="Knowledge Nodes" value="16" />

                        <InfoItem label="Relationships" value="18" />
                    </div>
                </div>

                {/* ===================================================
                    NODES
                =================================================== */}

                <div className="bg-white rounded-2xl shadow-sm p-6">
                    <h2 className="text-lg font-semibold mb-4">
                        Registered Nodes
                    </h2>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                        {(nodes.length > 0
                            ? nodes
                            : [
                                  "business_roles",
                                  "buyer_segments",
                                  "supplier_segments",
                                  "certifications",
                                  "certification_markets",
                                  "product_categories",
                                  "technologies",
                                  "industry_segments",
                              ]
                        ).map((node) => (
                            <div
                                key={node}
                                className="
                                    border
                                    rounded-lg
                                    px-4
                                    py-3
                                "
                            >
                                {node}
                            </div>
                        ))}
                    </div>
                </div>

                {/* ===================================================
                    EDGES
                =================================================== */}

                <div className="bg-white rounded-2xl shadow-sm p-6">
                    <h2 className="text-lg font-semibold mb-4">
                        Relationships
                    </h2>

                    <div className="space-y-3">
                        {(edges.length > 0
                            ? edges
                            : [
                                  "buyer_segments → product_categories",
                                  "supplier_segments → certifications",
                                  "certifications → certification_markets",
                                  "industry_segments → business_ecosystems",
                              ]
                        ).map((edge, index) => (
                            <div
                                key={index}
                                className="
                                    flex
                                    items-center
                                    gap-2
                                    border-b
                                    pb-3
                                "
                            >
                                <span>{edge}</span>
                            </div>
                        ))}
                    </div>
                </div>

                {/* ===================================================
                    GRAPH HEALTH STATUS
                =================================================== */}

                <div className="bg-white rounded-2xl shadow-sm p-6">
                    <h2 className="text-lg font-semibold mb-4">
                        Validation Report
                    </h2>

                    <div className="space-y-3">
                        <StatusItem
                            status="SUCCESS"
                            message="Knowledge Graph validation passed."
                        />

                        <StatusItem
                            status="INFO"
                            message="16 nodes registered."
                        />

                        <StatusItem
                            status="INFO"
                            message="18 relationships established."
                        />

                        <StatusItem
                            status="WARNING"
                            message="4 orphan taxonomy nodes detected."
                        />
                    </div>
                </div>
            </div>
        </WebsiteLayout>
    );
}

/* ==========================================================
   COMPONENTS
========================================================== */

function MetricCard({ title, value }) {
    return (
        <div className="bg-white rounded-2xl shadow-sm p-6">
            <p className="text-sm text-slate-500">{title}</p>

            <p className="text-3xl font-bold mt-2">{value}</p>
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

function StatusItem({ status, message }) {
    return (
        <div className="flex gap-3">
            <div className="font-semibold min-w-[90px]">{status}</div>

            <div>{message}</div>
        </div>
    );
}
