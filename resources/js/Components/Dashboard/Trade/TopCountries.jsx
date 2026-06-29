import { Globe } from "lucide-react";

export default function TopCountries({ data = {} }) {
    const exportCountries = data.export ?? [];
    const importCountries = data.import ?? [];

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
                    <Globe size={18} className="text-blue-600" />

                    <h2 className="text-lg font-bold text-slate-900">
                        Top Trading Countries
                    </h2>
                </div>

                <p className="mt-1 text-sm text-slate-500">
                    EN : Leading export and import destinations
                    <br />
                    ID : Negara tujuan ekspor dan asal impor utama
                </p>
            </div>

            {/* Content */}

            <div className="grid gap-8 p-6 lg:grid-cols-2">
                {/* Export */}

                <div>
                    <h3 className="mb-4 text-sm font-bold uppercase tracking-wider text-blue-700">
                        Top Export Countries
                    </h3>

                    <div className="space-y-3">
                        {exportCountries.map((item, index) => (
                            <div
                                key={index}
                                className="flex items-center justify-between rounded-xl border border-slate-100 p-3 hover:bg-slate-50"
                            >
                                <div className="flex items-center gap-3">
                                    <div className="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-sm font-bold text-blue-700">
                                        {index + 1}
                                    </div>

                                    <div>
                                        <div className="font-semibold text-slate-900">
                                            {item.country_name}
                                        </div>
                                    </div>
                                </div>

                                <div className="font-bold text-blue-700">
                                    {formatValue(item.trade_value)}
                                </div>
                            </div>
                        ))}
                    </div>
                </div>

                {/* Import */}

                <div>
                    <h3 className="mb-4 text-sm font-bold uppercase tracking-wider text-orange-700">
                        Top Import Countries
                    </h3>

                    <div className="space-y-3">
                        {importCountries.map((item, index) => (
                            <div
                                key={index}
                                className="flex items-center justify-between rounded-xl border border-slate-100 p-3 hover:bg-slate-50"
                            >
                                <div className="flex items-center gap-3">
                                    <div className="flex h-8 w-8 items-center justify-center rounded-full bg-orange-100 text-sm font-bold text-orange-700">
                                        {index + 1}
                                    </div>

                                    <div>
                                        <div className="font-semibold text-slate-900">
                                            {item.country_name}
                                        </div>
                                    </div>
                                </div>

                                <div className="font-bold text-orange-700">
                                    {formatValue(item.trade_value)}
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </div>
    );
}
