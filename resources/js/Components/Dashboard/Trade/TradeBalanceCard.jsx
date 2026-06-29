import { ArrowUpRight, ArrowDownRight, Scale } from "lucide-react";

export default function TradeBalanceCard({ exportValue = 0, importValue = 0 }) {
    const balance = exportValue - importValue;

    const formatValue = (value) => {
        if (!value) return "$0";

        if (Math.abs(value) >= 1000000000)
            return `$${(value / 1000000000).toFixed(2)}B`;

        if (Math.abs(value) >= 1000000)
            return `$${(value / 1000000).toFixed(2)}M`;

        return `$${Number(value).toLocaleString()}`;
    };

    const surplus = balance >= 0;

    return (
        <div className="rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div className="border-b border-slate-100 px-6 py-5">
                <div className="flex items-center gap-2">
                    <Scale size={18} className="text-blue-600" />

                    <h2 className="text-lg font-bold text-slate-900">
                        Trade Balance
                    </h2>
                </div>

                <p className="mt-1 text-sm text-slate-500">
                    EN : National textile trade balance
                    <br />
                    ID : Neraca perdagangan industri tekstil nasional
                </p>
            </div>

            <div className="space-y-5 p-6">
                {/* Export */}

                <div className="flex items-center justify-between">
                    <div>
                        <div className="text-xs uppercase tracking-wider text-slate-500">
                            Export
                        </div>

                        <div className="mt-1 text-xl font-bold text-blue-700">
                            {formatValue(exportValue)}
                        </div>
                    </div>

                    <ArrowUpRight className="text-blue-600" size={28} />
                </div>

                {/* Import */}

                <div className="flex items-center justify-between">
                    <div>
                        <div className="text-xs uppercase tracking-wider text-slate-500">
                            Import
                        </div>

                        <div className="mt-1 text-xl font-bold text-orange-600">
                            {formatValue(importValue)}
                        </div>
                    </div>

                    <ArrowDownRight className="text-orange-600" size={28} />
                </div>

                <hr />

                {/* Balance */}

                <div>
                    <div className="text-xs uppercase tracking-wider text-slate-500">
                        Trade Balance
                    </div>

                    <div
                        className={`mt-2 text-3xl font-black ${
                            surplus ? "text-emerald-600" : "text-red-600"
                        }`}
                    >
                        {surplus ? "+" : "-"}

                        {formatValue(Math.abs(balance))}
                    </div>

                    <div
                        className={`mt-2 inline-flex rounded-full px-3 py-1 text-xs font-bold ${
                            surplus
                                ? "bg-emerald-100 text-emerald-700"
                                : "bg-red-100 text-red-700"
                        }`}
                    >
                        {surplus ? "TRADE SURPLUS" : "TRADE DEFICIT"}
                    </div>
                </div>
            </div>
        </div>
    );
}
