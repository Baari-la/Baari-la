import { ArrowDown, Network } from "lucide-react";

export default function SupplyChainGraph({
    product = null,
    sector = null,
    nodes = [],
    edges = [],
    total_nodes = 0,
    total_edges = 0,
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

            <div className="border-b px-6 py-5">
                <div className="flex items-center gap-3">
                    <Network size={24} />

                    <div>
                        <h2 className="text-xl font-bold">
                            Supply Chain Graph
                        </h2>

                        <p className="text-sm text-slate-500">
                            Visual representation of the supply chain.
                        </p>
                    </div>
                </div>
            </div>

            {/* Metrics */}

            <div className="grid gap-4 p-6 lg:grid-cols-3">
                <MetricCard title="Product" value={product} />

                <MetricCard title="Nodes" value={total_nodes} />

                <MetricCard title="Edges" value={total_edges} />
            </div>
            {/* Graph */}

            <div className="px-6 pb-6">
                <div
                    className="
                        rounded-2xl
                        border
                        border-slate-200
                        bg-slate-50
                        p-6
                    "
                >
                    <p className="text-sm font-semibold">Supply Chain Flow</p>

                    <div
                        className="
                            mt-6
                            flex
                            flex-col
                            items-center
                            gap-3
                        "
                    >
                        {nodes.map((node, index) => (
                            <div
                                key={node.id}
                                className="
                                        flex
                                        flex-col
                                        items-center
                                    "
                            >
                                <div
                                    className="
                                            min-w-[220px]
                                            rounded-2xl
                                            border
                                            border-slate-200
                                            bg-white
                                            px-6
                                            py-4
                                            text-center
                                            shadow-sm
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
                                        {node.type}
                                    </p>

                                    <p
                                        className="
                                                mt-2
                                                text-base
                                                font-semibold
                                            "
                                    >
                                        {node.label}
                                    </p>
                                </div>

                                {index < nodes.length - 1 && (
                                    <ArrowDown
                                        className="
                                                my-2
                                                text-slate-400
                                            "
                                        size={18}
                                    />
                                )}
                            </div>
                        ))}
                    </div>
                </div>
            </div>
            {/* AI Insight */}

            <div
                className="
                    border-t
                    bg-slate-50
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
                    DIGESTEX AI
                </p>

                <p
                    className="
                        mt-2
                        text-sm
                        leading-7
                        text-slate-600
                    "
                >
                    {ai_insight}
                </p>
            </div>
        </div>
    );
}

function MetricCard({ title, value }) {
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
                    text-2xl
                    font-bold
                    text-slate-900
                "
            >
                {value ?? "-"}
            </p>
        </div>
    );
}
