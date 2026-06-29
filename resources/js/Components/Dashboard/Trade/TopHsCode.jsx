import { Package } from "lucide-react";

export default function TopHSCodes({ data = {} }) {
    const exportHS = data.export ?? [];
    const importHS = data.import ?? [];

    const formatValue = (value) => {
        if (!value) return "$0";

        if (value >= 1000000000) return `$${(value / 1000000000).toFixed(2)}B`;

        if (value >= 1000000) return `$${(value / 1000000).toFixed(2)}M`;

        return `$${Number(value).toLocaleString()}`;
    };

    return (
        <div className="rounded-3xl border border-slate-200 bg-white shadow-sm">
            {/* Header */}

            <div className="border-b border-slate-100 px-6 py-5">
                <div className="flex items-center gap-2">
                    <Package size={18} className="text-blue-600" />

                    <h2 className="text-lg font-bold text-slate-900">
                        Top HS Codes
                    </h2>
                </div>

                <p className="mt-1 text-sm text-slate-500">
                    EN : Highest export and import commodities
                    <br />
                    ID : Komoditas ekspor dan impor terbesar berdasarkan HS Code
                </p>
            </div>

            {/* Content */}

            <div className="grid gap-8 p-6 lg:grid-cols-2">
                {/* Export */}

                <div>
                    <h3 className="mb-4 text-sm font-bold uppercase tracking-wider text-blue-700">
                        Top Export HS
                    </h3>

                    <div className="space-y-3">
                        {exportHS.map((item, index) => (
                            <div
                                key={index}
                                className="rounded-xl border border-slate-100 p-4 transition hover:bg-slate-50"
                            >
                                <div className="flex items-center justify-between">
                                    <div className="font-bold text-blue-700">
                                        {item.hs_code}
                                    </div>

                                    <div className="text-sm font-bold text-slate-700">
                                        {formatValue(item.trade_value)}
                                    </div>
                                </div>

                                <div className="mt-2 text-sm leading-5 text-slate-600">
                                    {item.hs_description}
                                </div>
                            </div>
                        ))}
                    </div>
                </div>

                {/* Import */}

                <div>
                    <h3 className="mb-4 text-sm font-bold uppercase tracking-wider text-orange-700">
                        Top Import HS
                    </h3>

                    <div className="space-y-3">
                        {importHS.map((item, index) => (
                            <div
                                key={index}
                                className="rounded-xl border border-slate-100 p-4 transition hover:bg-slate-50"
                            >
                                <div className="flex items-center justify-between">
                                    <div className="font-bold text-orange-700">
                                        {item.hs_code}
                                    </div>

                                    <div className="text-sm font-bold text-slate-700">
                                        {formatValue(item.trade_value)}
                                    </div>
                                </div>

                                <div className="mt-2 text-sm leading-5 text-slate-600">
                                    {item.hs_description}
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </div>
    );
}
