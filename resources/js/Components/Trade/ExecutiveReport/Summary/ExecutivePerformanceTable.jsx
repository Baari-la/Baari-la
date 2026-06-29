export default function ExecutivePerformanceTable({ data = [] }) {
    const formatMillion = (value) => `US$ ${(value / 1000000).toFixed(1)} M`;

    const growth = (oldValue, newValue) => {
        if (!oldValue) return 0;
        return ((newValue - oldValue) / oldValue) * 100;
    };

    return (
        <div className="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
            <div className="px-6 py-5 border-b bg-gray-50">
                <h2 className="text-xl font-bold text-gray-900">
                    Executive Performance Summary
                </h2>

                <p className="text-sm text-gray-500 mt-1">
                    Indonesia Apparel & Made-up Textile Export Performance
                </p>
            </div>

            <table className="w-full">
                <thead className="bg-slate-800">
                    <tr>
                        <th className="px-6 py-3 text-left">Month</th>

                        <th className="px-6 py-3 text-right">Jan–Apr 2025</th>

                        <th className="px-6 py-3 text-right">Jan–Apr 2026</th>

                        <th className="px-6 py-3 text-center">Growth</th>
                    </tr>
                </thead>

                <tbody>
                    {data.map((row) => {
                        const pct = growth(row.export2025, row.export2026);

                        return (
                            <tr
                                key={row.month}
                                className="border-t border-gray-200 hover:bg-gray-50"
                            >
                                <td className="px-6 py-4 font-medium text-gray-900">
                                    {row.label}
                                </td>

                                <td className="px-6 py-4 text-right text-gray-900">
                                    {formatMillion(row.export2025)}
                                </td>

                                <td className="px-6 py-4 text-right font-semibold text-gray-900">
                                    {formatMillion(row.export2026)}
                                </td>

                                <td className="px-6 py-4 text-center">
                                    <span
                                        className={
                                            pct >= 0
                                                ? "text-green-600 font-bold"
                                                : "text-red-600 font-bold"
                                        }
                                    >
                                        {pct >= 0 ? "🟢 +" : "🔻 "}
                                        {pct.toFixed(1)}%
                                    </span>
                                </td>
                            </tr>
                        );
                    })}
                </tbody>
            </table>
        </div>
    );
}
