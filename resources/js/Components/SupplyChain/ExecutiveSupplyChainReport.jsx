import { FileText, Factory, ShoppingBag, Network } from "lucide-react";

export default function ExecutiveSupplyChainReport({
    title = "Build My Supply Chain™ Report",
    generated_at = null,
    product = null,
    sector = null,
    executive_summary = "",
    metrics = {},
    ai_insight = "",
}) {
    return (
        <div
            className="
                overflow-hidden
                rounded-3xl
                border
                border-slate-200
                bg-white
                shadow-sm
            "
        >
            {/* Header */}

            <div
                className="
                    bg-gradient-to-r
                    from-slate-900
                    to-slate-700
                    px-6
                    py-6
                    text-white
                "
            >
                <div className="flex items-center gap-3">
                    <FileText size={28} />

                    <div>
                        <h2 className="text-2xl font-bold">{title}</h2>

                        <p className="mt-1 text-sm text-slate-300">
                            Generated: {generated_at ?? "-"}
                        </p>
                    </div>
                </div>
            </div>

            {/* Product Info */}

            <div className="grid gap-4 p-6 lg:grid-cols-2">
                <InfoCard title="Product" value={product} />

                <InfoCard title="Sector" value={sector} />
            </div>
            {/* Executive Summary */}

            <div className="px-6">
                <div
                    className="
                        rounded-2xl
                        bg-slate-50
                        p-5
                    "
                >
                    <p
                        className="
                            text-sm
                            font-bold
                            uppercase
                            tracking-wider
                            text-slate-600
                        "
                    >
                        Executive Summary
                    </p>

                    <p
                        className="
                            mt-3
                            text-sm
                            leading-7
                            text-slate-700
                        "
                    >
                        {executive_summary}
                    </p>
                </div>
            </div>

            {/* Metrics */}

            <div className="grid gap-4 p-6 lg:grid-cols-4">
                <MetricCard
                    icon={<Factory size={18} />}
                    title="Suppliers"
                    value={metrics.total_suppliers}
                />

                <MetricCard
                    icon={<ShoppingBag size={18} />}
                    title="Buyers"
                    value={metrics.total_buyers}
                />

                <MetricCard
                    icon={<Network size={18} />}
                    title="Nodes"
                    value={metrics.total_nodes}
                />

                <MetricCard
                    icon={<Network size={18} />}
                    title="Edges"
                    value={metrics.total_edges}
                />
            </div>
            {/* AI Insight */}

            <div
                className="
                    border-t
                    bg-violet-50
                    px-6
                    py-5
                "
            >
                <p
                    className="
                        text-sm
                        font-bold
                        uppercase
                        tracking-wider
                        text-violet-700
                    "
                >
                    DIGESTEX AI INSIGHT
                </p>

                <p
                    className="
                        mt-2
                        text-sm
                        leading-7
                        text-slate-700
                    "
                >
                    {ai_insight}
                </p>
            </div>
        </div>
    );
}

function InfoCard({ title, value }) {
    return (
        <div
            className="
                rounded-2xl
                border
                border-slate-200
                p-5
            "
        >
            <p
                className="
                    text-xs
                    font-bold
                    uppercase
                    text-slate-500
                "
            >
                {title}
            </p>

            <p
                className="
                    mt-2
                    text-xl
                    font-bold
                "
            >
                {value ?? "-"}
            </p>
        </div>
    );
}

function MetricCard({ icon, title, value }) {
    return (
        <div
            className="
                rounded-2xl
                border
                border-slate-200
                p-5
            "
        >
            <div className="flex items-center gap-2">
                {icon}

                <p className="text-sm font-semibold">{title}</p>
            </div>

            <p
                className="
                    mt-4
                    text-3xl
                    font-bold
                    text-slate-900
                "
            >
                {value ?? 0}
            </p>
        </div>
    );
}
