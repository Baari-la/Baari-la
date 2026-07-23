import { TrendingUp, TrendingDown } from "lucide-react";

export default function ExecutiveExportMonitor({
    top_exports = [],
    top_imports = [],
    executive_insight = "",
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
                <h2 className="text-xl font-bold">Executive Export Monitor</h2>

                <p className="mt-1 text-sm text-slate-500">
                    Top export and import HS codes.
                </p>
            </div>

            <div className="grid gap-5 p-6 lg:grid-cols-2">
                <MonitorCard
                    title="TOP EXPORT HS"
                    icon={<TrendingUp size={18} />}
                    items={top_exports}
                />

                <MonitorCard
                    title="TOP IMPORT HS"
                    icon={<TrendingDown size={18} />}
                    items={top_imports}
                />
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
                    {executive_insight}
                </p>
            </div>
        </div>
    );
}

function MonitorCard({ title, icon, items = [] }) {
    const money = (value) => `US$ ${(value / 1_000_000).toFixed(1)} M`;

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

                <h3 className="font-bold">{title}</h3>
            </div>

            <div className="mt-4 space-y-3">
                {items.length === 0 ? (
                    <p className="text-sm text-slate-400">No data available.</p>
                ) : (
                    items.map((item, index) => (
                        <div
                            key={item.hs_code}
                            className="
                                    flex
                                    items-center
                                    justify-between
                                    rounded-xl
                                    bg-slate-50
                                    px-4
                                    py-3
                                "
                        >
                            <div>
                                <p
                                    className="
                                            font-semibold
                                        "
                                >
                                    #{index + 1} · HS {item.hs_code}
                                </p>

                                <p
                                    className="
                                            mt-1
                                            text-xs
                                            text-slate-500
                                        "
                                >
                                    {money(item.trade_value)}
                                </p>
                            </div>
                        </div>
                    ))
                )}
            </div>
        </div>
    );
}
