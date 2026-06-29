import { TrendingUp, TrendingDown, Minus } from "lucide-react";

const defaultRadar = [
    {
        title: "Export Performance",
        status: "Positive",
        trend: "up",
    },
    {
        title: "Import Activity",
        status: "Stable",
        trend: "flat",
    },
    {
        title: "Trade Balance",
        status: "Surplus",
        trend: "up",
    },
    {
        title: "Global Demand",
        status: "Growing",
        trend: "up",
    },
    {
        title: "Raw Material",
        status: "Watch",
        trend: "down",
    },
    {
        title: "Logistics",
        status: "Stable",
        trend: "flat",
    },
];

function TrendIcon({ trend }) {
    switch (trend) {
        case "up":
            return <TrendingUp size={18} className="text-emerald-600" />;

        case "down":
            return <TrendingDown size={18} className="text-red-600" />;

        default:
            return <Minus size={18} className="text-amber-500" />;
    }
}

function StatusBadge({ trend, status }) {
    const styles = {
        up: "bg-emerald-100 text-emerald-700",

        down: "bg-red-100 text-red-700",

        flat: "bg-amber-100 text-amber-700",
    };

    return (
        <span
            className={`rounded-full px-3 py-1 text-xs font-semibold ${
                styles[trend] || styles.flat
            }`}
        >
            {status}
        </span>
    );
}

export default function TradeRadar({ radar = defaultRadar }) {
    return (
        <div className="rounded-3xl border border-slate-200 bg-white shadow-sm">
            {/* Header */}

            <div className="border-b border-slate-100 px-6 py-5">
                <h3 className="text-xl font-bold text-slate-900">
                    Trade Radar
                </h3>

                <p className="mt-1 text-sm text-slate-500">
                    Executive market signals generated from the latest trade
                    data.
                </p>
            </div>

            {/* Radar */}

            <div className="space-y-4 p-6">
                {radar.map((item, index) => (
                    <div
                        key={index}
                        className="flex items-center justify-between rounded-xl border border-slate-100 p-4 hover:bg-slate-50"
                    >
                        <div className="flex items-center gap-3">
                            <TrendIcon trend={item.trend} />

                            <span className="font-medium text-slate-800">
                                {item.title}
                            </span>
                        </div>

                        <StatusBadge trend={item.trend} status={item.status} />
                    </div>
                ))}
            </div>
        </div>
    );
}
