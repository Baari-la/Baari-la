import { Package, TrendingUp, TrendingDown, Minus } from "lucide-react";

const defaultProducts = [
    {
        rank: 1,
        hsCode: "5205",
        product: "Cotton Yarn",
        exportValue: "US$ 0",
        growth: 0,
    },
    {
        rank: 2,
        hsCode: "5402",
        product: "Polyester Yarn",
        exportValue: "US$ 0",
        growth: 0,
    },
    {
        rank: 3,
        hsCode: "6006",
        product: "Knitted Fabric",
        exportValue: "US$ 0",
        growth: 0,
    },
    {
        rank: 4,
        hsCode: "6109",
        product: "T-Shirts",
        exportValue: "US$ 0",
        growth: 0,
    },
    {
        rank: 5,
        hsCode: "6203",
        product: "Men's Garments",
        exportValue: "US$ 0",
        growth: 0,
    },
];

function GrowthIndicator({ value }) {
    if (value > 0) {
        return (
            <div className="flex items-center justify-end gap-1 font-semibold text-emerald-600">
                <TrendingUp size={15} />
                {value}%
            </div>
        );
    }

    if (value < 0) {
        return (
            <div className="flex items-center justify-end gap-1 font-semibold text-red-600">
                <TrendingDown size={15} />
                {Math.abs(value)}%
            </div>
        );
    }

    return (
        <div className="flex items-center justify-end gap-1 font-semibold text-slate-500">
            <Minus size={15} />
            Stable
        </div>
    );
}

export default function ProductPerformanceTable({
    title = "Top Product Performance",

    products = defaultProducts,
}) {
    return (
        <div className="rounded-3xl border border-slate-200 bg-white shadow-sm">
            {/* Header */}

            <div className="border-b border-slate-100 px-6 py-5">
                <div className="flex items-center gap-3">
                    <Package size={24} className="text-indigo-600" />

                    <div>
                        <h3 className="text-xl font-bold text-slate-900">
                            {title}
                        </h3>

                        <p className="mt-1 text-sm text-slate-500">
                            Best performing textile products based on export
                            value
                        </p>
                    </div>
                </div>
            </div>

            {/* Table */}

            <div className="overflow-x-auto">
                <table className="min-w-full">
                    <thead className="bg-slate-50">
                        <tr>
                            <th className="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                #
                            </th>

                            <th className="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Product
                            </th>

                            <th className="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                HS Code
                            </th>

                            <th className="px-5 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500">
                                Export
                            </th>

                            <th className="w-24 px-5 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">
                                Share
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        {products.map((item) => (
                            <tr
                                key={item.rank}
                                className="border-t border-slate-100 hover:bg-slate-50"
                            >
                                <td className="px-5 py-4">
                                    <span className="rounded-full bg-indigo-600 px-3 py-1 text-xs font-bold text-white">
                                        {item.rank}
                                    </span>
                                </td>

                                <td className="px-5 py-4">
                                    <div>
                                        <p className="font-semibold text-slate-900">
                                            {item.product.length > 70
                                                ? item.product.substring(
                                                      0,
                                                      70,
                                                  ) + "..."
                                                : item.product}
                                        </p>
                                    </div>
                                </td>

                                <td className="px-5 py-4">
                                    <span className="rounded bg-slate-100 px-2 py-1 font-mono text-sm text-slate-900">
                                        {item.hs_code}
                                    </span>
                                </td>

                                <td className="px-5 py-4 text-right font-semibold text-slate-900">
                                    US${" "}
                                    {Number(
                                        item.export_million ?? 0,
                                    ).toLocaleString()}{" "}
                                    M
                                </td>

                                <td className="w-24 px-5 py-4 text-center">
                                    <span className="font-semibold text-emerald-600">
                                        {item.share}%
                                    </span>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </div>
    );
}
