import { CalendarRange } from "lucide-react";

export default function ReportPeriod({
    country = "Indonesia",

    period = "January – April",

    compare = "2025 vs 2026",
}) {
    return (
        <div className="rounded-2xl border border-blue-100 bg-blue-50 p-6">
            <div className="flex items-center gap-3">
                <CalendarRange className="text-blue-600" />

                <div>
                    <p className="text-xs font-bold uppercase tracking-[0.25em] text-blue-700">
                        Reporting Period
                    </p>

                    <h2 className="mt-1 text-2xl font-bold text-slate-900">
                        {country}
                    </h2>
                </div>
            </div>

            <div className="mt-6 grid gap-6 md:grid-cols-2">
                <div>
                    <p className="text-xs uppercase tracking-widest text-slate-500">
                        Period
                    </p>

                    <p className="mt-2 text-xl font-bold">{period}</p>
                </div>

                <div>
                    <p className="text-xs uppercase tracking-widest text-slate-500">
                        Comparison
                    </p>

                    <p className="mt-2 text-xl font-bold">{compare}</p>
                </div>
            </div>
        </div>
    );
}
