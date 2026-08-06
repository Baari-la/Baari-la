/*
|--------------------------------------------------------------------------
| DIGESTEX Industry Summary Card™
|--------------------------------------------------------------------------
|
| Generic Summary Card.
|
| Every Industry Blueprint may provide:
|
| • Title
| • Description
| • Summary Items
| • Intelligence
| • Progress
|
|--------------------------------------------------------------------------
*/

import { CheckCircle2, Circle, Gauge, Building2 } from "lucide-react";

export default function IndustrySummaryCard({ blueprint, business, data }) {
    /*
    |--------------------------------------------------------------------------
    | Blueprint
    |--------------------------------------------------------------------------
    */

    const title = blueprint?.title ?? "Industry Blueprint™";

    const description = blueprint?.description ?? "";

    const summary = blueprint?.summary ?? [];

    const intelligence = blueprint?.intelligence ?? [];

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    return (
        <div className="space-y-6">
            {/* ==========================================================
                Blueprint
            ========================================================== */}

            <div className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div className="flex items-center gap-3">
                    <div className="rounded-xl bg-indigo-100 p-3">
                        <Building2 className="h-5 w-5 text-indigo-600" />
                    </div>

                    <div>
                        <h3 className="font-bold">{title}</h3>

                        <p className="mt-1 text-sm text-slate-500">
                            {description}
                        </p>
                    </div>
                </div>
            </div>

            {/* ==========================================================
                Summary
            ========================================================== */}

            <div className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div className="mb-4 flex items-center gap-2">
                    <Gauge className="h-5 w-5 text-indigo-600" />

                    <h3 className="font-bold">Industry Summary™</h3>
                </div>

                <div className="space-y-3">
                    {summary.map((item) => (
                        <SummaryRow
                            key={item.key}
                            label={item.label}
                            value={data[item.key] ?? "-"}
                        />
                    ))}
                </div>
            </div>

            {/* ==========================================================
                Intelligence
            ========================================================== */}

            <div className="rounded-3xl border border-indigo-100 bg-indigo-50 p-6">
                <h3 className="font-bold text-indigo-700">
                    DIGESTEX Intelligence™
                </h3>

                <div className="mt-4 space-y-3">
                    {intelligence.map((module) => (
                        <div key={module} className="flex items-center gap-3">
                            <CheckCircle2 className="h-4 w-4 text-indigo-600" />

                            <span className="text-sm">{module}</span>
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
}

/*
|--------------------------------------------------------------------------
| Summary Row
|--------------------------------------------------------------------------
*/

function SummaryRow({ label, value }) {
    return (
        <div className="flex items-center justify-between border-b border-slate-100 pb-2">
            <span className="text-sm text-slate-500">{label}</span>

            <span className="font-semibold">
                {value || <Circle className="h-4 w-4 text-slate-300" />}
            </span>
        </div>
    );
}
