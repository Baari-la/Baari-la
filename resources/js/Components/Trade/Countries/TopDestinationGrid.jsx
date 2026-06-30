import { Globe, TrendingUp, TrendingDown, Minus } from "lucide-react";

const defaultCountries = [
    {
        rank: 1,
        country: "United States",
        exportValue: "US$ 0",
        growth: 0,
    },
    {
        rank: 2,
        country: "Japan",
        exportValue: "US$ 0",
        growth: 0,
    },
    {
        rank: 3,
        country: "China",
        exportValue: "US$ 0",
        growth: 0,
    },
    {
        rank: 4,
        country: "Vietnam",
        exportValue: "US$ 0",
        growth: 0,
    },
    {
        rank: 5,
        country: "Germany",
        exportValue: "US$ 0",
        growth: 0,
    },
];

function Trend({ value }) {
    if (value > 0) {
        return (
            <div className="flex items-center gap-1 text-emerald-600 font-semibold">
                <TrendingUp size={16} />
                {value}%
            </div>
        );
    }

    if (value < 0) {
        return (
            <div className="flex items-center gap-1 text-red-600 font-semibold">
                <TrendingDown size={16} />
                {Math.abs(value)}%
            </div>
        );
    }

    return (
        <div className="flex items-center gap-1 text-slate-500 font-semibold">
            <Minus size={16} />
            Stable
        </div>
    );
}

export default function TopDestinationGrid({ countries = defaultCountries }) {
    return (
        <div className="rounded-3xl border border-slate-200 bg-white shadow-sm">
            {/* Header */}

            <div className="border-b border-slate-100 px-6 py-5">
                <div className="flex items-center gap-3">
                    <Globe className="text-blue-600" size={24} />

                    <div>
                        <h3 className="text-xl font-bold text-slate-900">
                            Top Export Destination Countries
                        </h3>

                        <p className="mt-1 text-sm text-slate-500">
                            January – April 2026 compared with January – April
                            2025
                        </p>
                    </div>
                </div>
            </div>

            {/* Grid */}

            <div className="grid gap-5 p-6 md:grid-cols-2 xl:grid-cols-3">
                {countries.map((country) => (
                    <div
                        key={country.rank}
                        className="rounded-2xl border border-slate-200 p-5 transition hover:border-blue-300 hover:shadow-md"
                    >
                        <div className="flex items-center justify-between">
                            <span className="rounded-full bg-blue-600 px-3 py-1 text-xs font-bold text-white">
                                🥇 TOP {country.rank}
                            </span>

                            <span className="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">
                                {/* {country.share}% */}
                            </span>
                        </div>

                        <h4 className="mt-5 text-lg font-bold text-slate-900">
                            {country.country}
                        </h4>

                        <p className="mt-3 text-sm text-slate-500">
                            Export Value
                        </p>

                        <p className="mt-1 text-2xl font-bold text-blue-700">
                            US${" "}
                            {Number(
                                country.export_million ?? 0,
                            ).toLocaleString()}{" "}
                            M
                        </p>
                        <p className="mt-2 text-sm text-slate-500">
                            Market Share
                        </p>

                        <p className="text-lg font-semibold text-emerald-600">
                            {country.share}%
                        </p>
                    </div>
                ))}
            </div>
        </div>
    );
}
