import {
    ResponsiveContainer,
    BarChart,
    Bar,
    CartesianGrid,
    XAxis,
    YAxis,
    Tooltip,
    Legend,
    Cell,
} from "recharts";

import ChartCard from "@/Components/Common/Charts/ChartCard";
import ChartHeader from "@/Components/Common/Charts/ChartHeader";
import EmptyChart from "@/Components/Common/Charts/EmptyChart";

const defaultData = [
    {
        label: "Jan",
        2025: 0,
        2026: 0,
    },
];

export default function ExportImportComparisonChart({
    data = defaultData,

    loading = false,
}) {
    if (loading) {
        return (
            <ChartCard>
                <ChartHeader
                    title="Indonesia Apparel Export Performance"
                    subtitle="HS Chapters 61–63 • January–April 2025 vs January–April 2026"
                />

                <EmptyChart message="Loading trade statistics..." />
            </ChartCard>
        );
    }

    if (!data || data.length === 0) {
        return (
            <ChartCard>
                <ChartHeader
                    title="Export vs Import Comparison"
                    subtitle="Indonesia Textile Industry"
                />

                <EmptyChart message="No trade data available." />
            </ChartCard>
        );
    }

    return (
        <ChartCard>
            <ChartHeader
                title="Export vs Import Comparison"
                subtitle="January–April 2025 vs January–April 2026"
            />

            <div className="h-[420px]">
                {/* <div className="bg-red-200 p-2 mb-2">
                    TEST CHART - {data.length} records
                </div> */}

                <div className="border border-red-500 h-[420px] flex items-center justify-center">
                    INSIDE CHART AREA
                </div>
                {/* <ResponsiveContainer width="100%" height="100%">
                    <BarChart
                        data={data}
                        margin={{
                            top: 20,
                            right: 20,
                            left: 10,
                            bottom: 10,
                        }}
                    >
                        <CartesianGrid strokeDasharray="3 3" vertical={false} />

                        <XAxis
                            dataKey="label"
                            interval={0}
                            tick={{
                                fontSize: 13,
                            }}
                        />

                        <YAxis
                            domain={[0, "dataMax"]}
                            tickFormatter={(value) =>
                                `${(value / 1000000).toFixed(0)}M`
                            }
                            tick={{
                                fontSize: 12,
                            }}
                        />

                        <Tooltip
                            formatter={(value) => [
                                `US$ ${(Number(value) / 1000000).toFixed(1)} Million`,
                            ]}
                        />

                        <Legend />

                        <Bar
                            dataKey="export2025"
                            name="2025"
                            radius={[8, 8, 0, 0]}
                        >
                            {data.map((entry, index) => (
                                <Cell key={index} fill="#2563eb" />
                            ))}
                        </Bar>

                        <Bar
                            dataKey="export2026"
                            name="2026"
                            radius={[8, 8, 0, 0]}
                        >
                            {data.map((entry, index) => (
                                <Cell key={index} fill="#f59e0b" />
                            ))}
                        </Bar>
                    </BarChart>
                </ResponsiveContainer> */}
            </div>
        </ChartCard>
    );
}
