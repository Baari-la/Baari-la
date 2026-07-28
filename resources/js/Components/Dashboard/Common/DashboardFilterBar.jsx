import { Calendar, Globe, Search, Filter } from "lucide-react";

export default function DashboardFilterBar({
    filters,
    setFilters,

    years = [],

    countries = [],

    hsCodes = [],
}) {
    const handleChange = (field, value) => {
        setFilters((prev) => ({
            ...prev,

            [field]: value,
        }));
    };

    return (
        <div className="rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div className="flex items-center gap-2 border-b border-slate-100 px-6 py-4">
                <Filter size={18} className="text-blue-600" />

                <div>
                    <h2 className="font-bold text-slate-900">
                        Dashboard Filter
                    </h2>

                    <p className="text-sm text-slate-500">
                        EN : Filter dashboard information
                        <br />
                        ID : Filter informasi dashboard
                    </p>
                </div>
            </div>

            <div className="grid gap-4 p-6 md:grid-cols-2 xl:grid-cols-5">
                {/* Year */}

                <div>
                    <label className="mb-2 block text-xs font-bold uppercase text-slate-500">
                        Year
                    </label>

                    <div className="relative">
                        <Calendar
                            size={16}
                            className="absolute left-3 top-3 text-slate-400"
                        />

                        <select
                            value={filters.year}
                            onChange={(e) =>
                                handleChange("year", e.target.value)
                            }
                            className="
        w-full
        rounded-xl
        border
        border-slate-200
        bg-white
        py-2
        pl-10
        pr-3
        font-semibold
        text-slate-800
        focus:border-blue-500
        focus:outline-none"
                        >
                            {years.map((year) => (
                                <option key={year} value={year}>
                                    {year}
                                </option>
                            ))}
                        </select>
                    </div>
                </div>

                {/* Trade Flow */}

                <div>
                    <label className="mb-2 block text-xs font-bold uppercase text-slate-500">
                        Trade Flow
                    </label>

                    <select
                        value={filters.tradeFlow}
                        onChange={(e) =>
                            handleChange("tradeFlow", e.target.value)
                        }
                        className="w-full rounded-xl border border-slate-200 px-3 py-2 focus:border-blue-500 focus:outline-none"
                    >
                        <option value="all">All</option>

                        <option value="export">Export</option>

                        <option value="import">Import</option>
                    </select>
                </div>

                {/* Country */}

                <div>
                    <label className="mb-2 block text-xs font-bold uppercase text-slate-500">
                        Country
                    </label>

                    <div className="relative">
                        <Globe
                            size={16}
                            className="absolute left-3 top-3 text-slate-400"
                        />

                        <select
                            value={filters.country}
                            onChange={(e) =>
                                handleChange("country", e.target.value)
                            }
                            className="w-full rounded-xl border border-slate-200 py-2 pl-10 pr-3 focus:border-blue-500 focus:outline-none"
                        >
                            <option value="">All Countries</option>

                            {countries.map((country) => (
                                <option key={country.code} value={country.code}>
                                    {country.name}
                                </option>
                            ))}
                        </select>
                    </div>
                </div>

                {/* HS Code */}

                <div>
                    <label className="mb-2 block text-xs font-bold uppercase text-slate-500">
                        HS Code
                    </label>

                    <select
                        value={filters.hsCode}
                        onChange={(e) => handleChange("hsCode", e.target.value)}
                        className="w-full rounded-xl border border-slate-200 px-3 py-2 focus:border-blue-500 focus:outline-none"
                    >
                        <option value="">All HS Code</option>

                        {hsCodes.map((hs) => (
                            <option key={hs.code} value={hs.code}>
                                {hs.code}
                                {hs.description ? ` — ${hs.description}` : ""}
                            </option>
                        ))}
                    </select>
                </div>

                {/* Search */}

                <div>
                    <label className="mb-2 block text-xs font-bold uppercase text-slate-500">
                        Search
                    </label>

                    <div className="relative">
                        <Search
                            size={16}
                            className="absolute left-3 top-3 text-slate-400"
                        />

                        <input
                            type="text"
                            placeholder="Keyword..."
                            value={filters.keyword}
                            onChange={(e) =>
                                handleChange("keyword", e.target.value)
                            }
                            className="w-full rounded-xl border border-slate-200 py-2 pl-10 pr-3 focus:border-blue-500 focus:outline-none"
                        />
                    </div>
                </div>
            </div>
        </div>
    );
}
