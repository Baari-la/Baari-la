import {
    AreaChart,
    Area,
    XAxis,
    YAxis,
    CartesianGrid,
    Tooltip,
    ResponsiveContainer,
    LabelList,
} from "recharts";

export default function MarketChart({ data }) {
    const isMobile = typeof window !== "undefined" && window.innerWidth < 768;
    const chartData = isMobile ? data.slice(-7) : data;
    if (!chartData || chartData.length === 0) {
        return (
            <div className="container mx-auto px-6 py-12">
                <div className="bg-white/5 h-[400px] rounded-[40px] border border-white/10 flex items-center justify-center">
                    <p className="text-gray-500 font-black uppercase text-[10px] tracking-widest animate-pulse">
                        Synchronizing Market Data...
                    </p>
                </div>
            </div>
        );
    }

    return (
        <div className="container mx-auto px-6 py-12">
            <div className="bg-white/5 p-8 md:p-12 rounded-[50px] border border-white/10 shadow-2xl relative overflow-hidden group">
                <div className="flex justify-between items-start mb-10 relative z-10">
                    <div>
                        <span className="bg-yellow-500 text-[#0a192f] text-[10px] font-black px-4 py-1 rounded-full uppercase tracking-[0.2em]">
                            Live Market Feed
                        </span>
                        <h3 className="text-3xl font-black text-white mt-4 tracking-tighter uppercase leading-none italic">
                            Global Cotton Index{" "}
                            <span className="text-yellow-500">2026</span>
                        </h3>
                    </div>
                    <div className="text-right hidden md:block border-l border-white/10 pl-6">
                        <p className="text-gray-500 text-[9px] font-black uppercase tracking-widest">
                            Exchange Reference
                        </p>
                        <p className="text-white font-black text-lg italic">
                            NY / ICE Futures
                        </p>
                    </div>
                </div>

                <div className="min-h-[450px] w-full mt-8 relative z-10">
                    <ResponsiveContainer width="100%" height={400}>
                        <AreaChart
                            data={chartData}
                            margin={{
                                top: 40,
                                right: 30,
                                left: 15,
                                bottom: 20,
                            }}
                        >
                            <defs>
                                <linearGradient
                                    id="colorPrice"
                                    x1="0"
                                    y1="0"
                                    x2="0"
                                    y2="1"
                                >
                                    <stop
                                        offset="5%"
                                        stopColor="#eab308"
                                        stopOpacity={0.3}
                                    />
                                    <stop
                                        offset="95%"
                                        stopColor="#eab308"
                                        stopOpacity={0}
                                    />
                                </linearGradient>
                            </defs>
                            <CartesianGrid
                                strokeDasharray="3 3"
                                vertical={false}
                                stroke="rgba(255,255,255,0.05)"
                            />
                            <XAxis
                                dataKey="date"
                                hide={false}
                                stroke="#ffffff"
                                fontSize={12}
                                fontWeight="900"
                                axisLine={true}
                                tickLine={true}
                                dy={15}
                                tickFormatter={(str) => {
                                    const date = new Date(str);
                                    return (
                                        date.getDate() +
                                        "/" +
                                        (date.getMonth() + 1)
                                    );
                                }}
                            />
                            <YAxis
                                domain={["dataMin - 1", "dataMax + 1"]}
                                hide={false}
                                stroke="#ffffff"
                                strokeWidth={2}
                                fontSize={12}
                                fontWeight="900"
                                axisLine={true}
                                tickLine={true}
                                tickFormatter={(val) => `$${val}`}
                                dx={-10}
                            />
                            <Tooltip
                                contentStyle={{
                                    backgroundColor: "#0a192f",
                                    border: "1px solid rgba(255,255,255,0.1)",
                                    borderRadius: "20px",
                                    boxShadow: "0 20px 40px rgba(0,0,0,0.5)",
                                }}
                                itemStyle={{
                                    color: "#eab308",
                                    fontWeight: "900",
                                    fontSize: "12px",
                                }}
                            />
                            <Area
                                type="monotone"
                                dataKey="cotton_price"
                                stroke="#eab308"
                                strokeWidth={5}
                                fillOpacity={1}
                                fill="url(#colorPrice)"
                                animationDuration={2000}
                            >
                                <LabelList
                                    dataKey="cotton_price"
                                    position="top"
                                    offset={15}
                                    content={(props) => {
                                        const { x, y, value } = props;
                                        return (
                                            <text
                                                x={x}
                                                y={y - 12}
                                                fill="#fbbf24"
                                                fontSize={12}
                                                fontWeight="900"
                                                textAnchor="middle"
                                                className="italic font-sans"
                                            >
                                                ${value}
                                            </text>
                                        );
                                    }}
                                />
                            </Area>
                        </AreaChart>
                    </ResponsiveContainer>
                </div>
            </div>
        </div>
    );
}
