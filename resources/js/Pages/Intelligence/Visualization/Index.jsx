import WebsiteLayout from "@/Layouts/WebsiteLayout";

export default function Index({ visualizations = {} }) {
    return (
        <WebsiteLayout title="Visualization Lab">
            <div className="space-y-6">
                {/* ===================================================
                    HERO
                =================================================== */}

                <div className="bg-white rounded-2xl shadow-sm p-8">
                    <h1 className="text-3xl font-bold text-slate-900">
                        Visualization Lab
                    </h1>

                    <p className="mt-2 text-slate-600">
                        Interactive visualization workspace for Knowledge
                        Graphs, Trade Intelligence, Company Networks, and Supply
                        Chain Mapping.
                    </p>
                </div>

                {/* ===================================================
                    LAB STATUS
                =================================================== */}

                <div
                    className="
                    grid
                    grid-cols-2
                    md:grid-cols-4
                    gap-6
                "
                >
                    <MetricCard
                        title="Charts"
                        value={visualizations.charts ?? 12}
                    />

                    <MetricCard
                        title="Graphs"
                        value={visualizations.graphs ?? 5}
                    />

                    <MetricCard title="Maps" value={visualizations.maps ?? 3} />

                    <MetricCard title="Status" value="ACTIVE" />
                </div>

                {/* ===================================================
                    VISUALIZATION MODULES
                =================================================== */}

                <div
                    className="
                    grid
                    grid-cols-1
                    lg:grid-cols-2
                    gap-6
                "
                >
                    <VisualizationCard
                        title="Knowledge Graph"
                        description="
                        Visualize nodes, edges, and
                        relationships across the textile
                        ecosystem."
                    />

                    <VisualizationCard
                        title="Trade Intelligence"
                        description="
                        Explore export and import trends
                        by country, HS Code, and region."
                    />

                    <VisualizationCard
                        title="Company Network"
                        description="
                        Discover connections between
                        companies, buyers, suppliers,
                        and partners."
                    />

                    <VisualizationCard
                        title="Supply Chain Builder"
                        description="
                        Build and analyze upstream to
                        downstream supply chains."
                    />

                    <VisualizationCard
                        title="Market Intelligence"
                        description="
                        Visualize global demand, trade
                        flows, and market opportunities."
                    />

                    <VisualizationCard
                        title="Executive AI"
                        description="
                        Present AI-generated insights
                        through interactive dashboards."
                    />
                </div>

                {/* ===================================================
                    ROADMAP
                =================================================== */}

                <div className="bg-white rounded-2xl shadow-sm p-6">
                    <h2 className="text-lg font-semibold mb-6">
                        Visualization Roadmap
                    </h2>

                    <div className="space-y-4">
                        <RoadmapItem
                            title="Phase 1"
                            value="Recharts Dashboard"
                        />

                        <RoadmapItem
                            title="Phase 2"
                            value="React Flow Integration"
                        />

                        <RoadmapItem title="Phase 3" value="Trade Heatmaps" />

                        <RoadmapItem
                            title="Phase 4"
                            value="Supply Chain Explorer"
                        />

                        <RoadmapItem
                            title="Phase 5"
                            value="3D Knowledge Graph"
                        />
                    </div>
                </div>

                {/* ===================================================
                    TECHNOLOGY STACK
                =================================================== */}

                <div className="bg-white rounded-2xl shadow-sm p-6">
                    <h2 className="text-lg font-semibold mb-6">
                        Visualization Stack
                    </h2>

                    <div className="grid grid-cols-2 gap-6">
                        <InfoItem label="Frontend" value="React + Inertia" />

                        <InfoItem label="Charts" value="Recharts" />

                        <InfoItem label="Graph Engine" value="React Flow" />

                        <InfoItem label="Maps" value="Leaflet" />

                        <InfoItem label="Backend" value="Laravel 12" />

                        <InfoItem
                            label="Knowledge Engine"
                            value="Master Data V2"
                        />
                    </div>
                </div>

                {/* ===================================================
                    FUTURE MODULES
                =================================================== */}

                <div className="bg-white rounded-2xl shadow-sm p-6">
                    <h2 className="text-lg font-semibold mb-4">
                        Future Modules
                    </h2>

                    <ul className="space-y-3">
                        <li>• Global Textile Trade Heatmap</li>

                        <li>• Buyer Discovery Network</li>

                        <li>• Build My Supply Chain™</li>

                        <li>• Executive Intelligence Center</li>

                        <li>• Company Intelligence Explorer</li>

                        <li>• Real-Time Knowledge Graph</li>
                    </ul>
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

function VisualizationCard({ title, description }) {
    return (
        <div className="bg-white rounded-2xl shadow-sm p-6">
            <h2 className="text-lg font-semibold mb-3">{title}</h2>

            <p className="text-slate-600">{description}</p>
        </div>
    );
}

function RoadmapItem({ title, value }) {
    return (
        <div className="flex justify-between">
            <span className="font-medium">{title}</span>

            <span className="text-slate-600">{value}</span>
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
