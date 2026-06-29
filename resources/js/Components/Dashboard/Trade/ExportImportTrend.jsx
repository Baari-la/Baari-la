import {
    ResponsiveContainer,
    AreaChart,
    Area,
    XAxis,
    YAxis,
    CartesianGrid,
    Tooltip,
    Legend,
} from "recharts";

import { TrendingUp } from "lucide-react";

export default function ExportImportTrend({ data = [] }) {
    const formatValue = (value) => {
        if (!value) return "$0";

        if (value >= 1000000000) return `$${(value / 1000000000).toFixed(2)}B`;

        if (value >= 1000000) return `$${(value / 1000000).toFixed(2)}M`;

        return `$${Number(value).toLocaleString()}`;
    };

    return (
        <div className="rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div className="flex items-center justify-between border-b border-slate-100 px-6 py-5">
                <div>
                    <div className="flex items-center gap-2">
                        <TrendingUp size={18} className="text-blue-600" />

                        <h2 className="text-lg font-bold text-slate-900">
                            Export vs Import Trend
                        </h2>
                    </div>

                    <p className="mt-1 text-sm text-slate-500">
                        EN : Monthly trade value comparison
                        <br />
                        ID : Perbandingan nilai ekspor dan impor per bulan
                    </p>
                </div>
            </div>

            <div className="h-[420px] p-6">
                <ResponsiveContainer width="100%" height="100%">
                    <AreaChart
                        data={data}
                        margin={{
                            top: 20,
                            right: 20,
                            left: 10,
                            bottom: 10,
                        }}
                    >
                        <CartesianGrid strokeDasharray="3 3" />

                        <XAxis dataKey="month" />

                        <YAxis tickFormatter={formatValue} />

                        <Tooltip formatter={(value) => formatValue(value)} />

                        <Legend />

                        <Area
                            type="monotone"
                            dataKey="export"
                            name="Export"
                            stroke="#2563eb"
                            fill="#93c5fd"
                            fillOpacity={0.45}
                            strokeWidth={3}
                        />

                        <Area
                            type="monotone"
                            dataKey="import"
                            name="Import"
                            stroke="#f97316"
                            fill="#fdba74"
                            fillOpacity={0.45}
                            strokeWidth={3}
                        />
                    </AreaChart>
                </ResponsiveContainer>
            </div>
        </div>
    );
}
