import { usePage } from "@inertiajs/react";

export default function ExecutivePerformancePiecesTable({
    data = [],
    tierA = false,
}) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const formatPieces = (value) =>
        `${(Number(value || 0) / 1000000).toFixed(1)} M pcs`;

    const growth = (oldValue, newValue) => {
        if (!oldValue) return 0;

        return ((newValue - oldValue) / oldValue) * 100;
    };

    const visibleData = tierA ? data : data.slice(0, 4);

    return (
        <div className="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-lg">
            {/* HEADER */}
            <div className="border-b bg-gray-50 px-6 py-6">
                <div className="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                    <div>
                        <span className="text-xs font-black uppercase tracking-[0.25em] text-slate-500">
                            {isEn
                                ? "GARMENT TRADE INTELLIGENCE"
                                : "GARMENT TRADE INTELLIGENCE"}
                        </span>

                        <h2 className="mt-2 text-2xl font-black text-gray-900">
                            {isEn
                                ? "Executive Apparel Export Volume"
                                : "Volume Ekspor Apparel"}
                        </h2>

                        <p className="mt-1 text-sm text-gray-500">
                            {isEn
                                ? "Export volume presented in pieces (PCS)"
                                : "Volume ekspor disajikan dalam satuan pieces (PCS)"}
                        </p>
                    </div>

                    <div className="shrink-0 rounded-full border border-gray-200 bg-white px-4 py-2 text-xs font-bold text-gray-500">
                        {isEn
                            ? "Data through June 2026"
                            : "Data sampai Juni 2026"}
                    </div>
                </div>
            </div>

            {/* TABLE */}
            <div className="overflow-x-auto">
                <table className="w-full min-w-[700px]">
                    <thead className="bg-slate-800 text-white">
                        <tr>
                            <th className="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">
                                {isEn ? "Month" : "Bulan"}
                            </th>

                            <th className="px-6 py-3 text-right text-xs font-bold uppercase tracking-wider">
                                Jan–Jun 2025
                            </th>

                            <th className="px-6 py-3 text-right text-xs font-bold uppercase tracking-wider">
                                Jan–Jun 2026
                            </th>

                            <th className="px-6 py-3 text-center text-xs font-bold uppercase tracking-wider">
                                {isEn ? "Growth" : "Pertumbuhan"}
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        {visibleData.map((row) => {
                            const pct = growth(row.pieces2025, row.pieces2026);

                            return (
                                <tr
                                    key={row.month}
                                    className="border-t border-gray-200 transition hover:bg-gray-50"
                                >
                                    <td className="px-6 py-4 font-medium text-gray-900">
                                        {row.label}
                                    </td>

                                    <td className="px-6 py-4 text-right text-gray-900">
                                        {formatPieces(row.pieces2025)}
                                    </td>

                                    <td className="px-6 py-4 text-right font-semibold text-gray-900">
                                        {formatPieces(row.pieces2026)}
                                    </td>

                                    <td className="px-6 py-4 text-center">
                                        <span
                                            className={
                                                pct >= 0
                                                    ? "font-bold text-green-600"
                                                    : "font-bold text-red-600"
                                            }
                                        >
                                            {pct >= 0 ? "+" : ""}
                                            {pct.toFixed(1)}%
                                        </span>
                                    </td>
                                </tr>
                            );
                        })}
                    </tbody>
                </table>
            </div>

            {/* TIER A INFORMATION */}
            {!tierA && (
                <div className="border-t border-gray-200 bg-slate-50 px-6 py-6">
                    <div className="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
                        <div className="max-w-3xl">
                            <p className="text-xs font-black uppercase tracking-[0.2em] text-slate-700">
                                {isEn
                                    ? "TIER A GARMENT INTELLIGENCE"
                                    : "TIER A GARMENT INTELLIGENCE"}
                            </p>

                            <p className="mt-2 text-sm leading-relaxed text-gray-600">
                                {isEn
                                    ? "Tier A provides deeper garment trade intelligence, including PCS, KG, trade value, HS 8-digit analysis, destination markets, and growth signals."
                                    : "Tier A menyediakan garment trade intelligence yang lebih mendalam, termasuk PCS, KG, nilai perdagangan, analisis HS 8 digit, pasar tujuan, dan growth signals."}
                            </p>
                        </div>

                        <div className="shrink-0">
                            <span className="inline-flex rounded-xl bg-slate-900 px-5 py-3 text-xs font-black uppercase tracking-wider text-white">
                                {isEn ? "TIER A" : "TIER A"}
                            </span>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}
