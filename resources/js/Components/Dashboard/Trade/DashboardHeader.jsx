import { Calendar, Database, Globe, RefreshCw } from "lucide-react";

export default function DashboardHeader({
    summary = {},
    filters = {},
    onFilterChange = () => {},
}) {
    return (
        <div className="mb-8 rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <div className="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                {/* ================= LEFT ================= */}
                <div className="max-w-4xl">
                    {/* Level 1 */}
                    <p className="text-xs font-bold uppercase tracking-[0.30em] text-blue-600">
                        DIGESTEX GLOBAL TEXTILE INTELLIGENCE ECOSYSTEM
                    </p>

                    {/* Level 2 */}
                    <h1 className="mt-2 text-4xl font-black tracking-tight text-slate-900">
                        Industrial Intelligence Center
                    </h1>

                    {/* Level 3 */}
                    <p className="mt-4 text-sm leading-7 text-slate-600">
                        <span className="font-semibold text-slate-800">
                            EN :
                        </span>{" "}
                        Integrated trade statistics, market intelligence,
                        company intelligence, supply chain analytics, and
                        AI-driven insights for the global textile industry.
                    </p>

                    <p className="mt-2 text-sm leading-7 text-slate-500 italic">
                        <span className="font-semibold text-slate-700 not-italic">
                            ID :
                        </span>{" "}
                        Pusat intelijen industri yang mengintegrasikan statistik
                        perdagangan, market intelligence, company intelligence,
                        analisis rantai pasok, serta wawasan berbasis AI untuk
                        industri tekstil global.
                    </p>
                </div>

                {/* ================= RIGHT ================= */}
                <div className="grid grid-cols-2 gap-4 lg:w-[440px]">
                    <div className="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div className="flex items-center gap-2 text-xs font-semibold uppercase text-slate-500">
                            <Database size={15} />
                            Records
                        </div>

                        <div className="mt-2 text-2xl font-black text-slate-900">
                            {summary.records?.toLocaleString() ?? "-"}
                        </div>
                    </div>

                    <div className="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div className="flex items-center gap-2 text-xs font-semibold uppercase text-slate-500">
                            <Calendar size={15} />
                            Last Update
                        </div>

                        <div className="mt-2 text-sm font-bold text-slate-900">
                            {summary.lastUpdate ?? "-"}
                        </div>
                    </div>

                    <div className="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div className="flex items-center gap-2 text-xs font-semibold uppercase text-slate-500">
                            <Globe size={15} />
                            Data Source
                        </div>

                        <div className="mt-2 text-sm font-bold text-slate-900">
                            {summary.source ?? "Kemendag RI"}
                        </div>
                    </div>

                    <div className="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div className="flex items-center gap-2 text-xs font-semibold uppercase text-slate-500">
                            <RefreshCw size={15} />
                            Coverage
                        </div>

                        <div className="mt-2 text-sm font-bold text-slate-900">
                            {summary.coverage ?? "2025 – 2026"}
                        </div>
                    </div>
                </div>
            </div>

            {/* FILTER BAR */}

            <div className="mt-8 flex flex-wrap items-center gap-4 border-t border-slate-200 pt-6">
                <select
                    className="rounded-xl border border-slate-300 px-4 py-2 text-sm"
                    value={filters.trade_flow ?? ""}
                    onChange={(e) =>
                        onFilterChange("trade_flow", e.target.value)
                    }
                >
                    <option value="">All Trade Flow</option>
                    <option value="export">Export</option>
                    <option value="import">Import</option>
                </select>

                <select
                    className="rounded-xl border border-slate-300 px-4 py-2 text-sm"
                    value={filters.year ?? ""}
                    onChange={(e) => onFilterChange("year", e.target.value)}
                >
                    <option value="">All Years</option>
                    <option value="2025">2025</option>
                    <option value="2026">2026</option>
                </select>

                <button className="rounded-xl bg-blue-600 px-5 py-2 font-semibold text-white transition hover:bg-blue-700">
                    Apply Filter
                </button>
            </div>
        </div>
    );
}
