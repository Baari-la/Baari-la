import {
    ResponsiveContainer,
    AreaChart,
    Area,
    CartesianGrid,
    Tooltip,
    XAxis,
    YAxis,
} from "recharts";

export default function CottonChart({ data = [] }) {
    return (
        <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <div className="mb-8">
                <p className="text-xs font-bold uppercase tracking-[0.25em] text-blue-600">
                    Cotton Market
                </p>

                <h3 className="mt-2 text-2xl font-black text-slate-900">
                    Cotton Price Trend
                </h3>

                <p className="mt-2 text-sm text-slate-500">
                    Daily cotton price and exchange rate movement.
                </p>
            </div>

            <ResponsiveContainer width="100%" height={340}>
                <AreaChart data={data}>
                    <defs>
                        <linearGradient id="cotton" x1="0" y1="0" x2="0" y2="1">
                            <stop
                                offset="5%"
                                stopColor="#2563eb"
                                stopOpacity={0.4}
                            />

                            <stop
                                offset="95%"
                                stopColor="#2563eb"
                                stopOpacity={0}
                            />
                        </linearGradient>
                    </defs>

                    <CartesianGrid strokeDasharray="3 3" />

                    <XAxis dataKey="month" />

                    <YAxis />

                    <Tooltip />

                    <Area
                        type="monotone"
                        dataKey="price"
                        stroke="#2563eb"
                        fill="url(#cotton)"
                        strokeWidth={3}
                    />
                </AreaChart>
            </ResponsiveContainer>
        </div>
    );
}
