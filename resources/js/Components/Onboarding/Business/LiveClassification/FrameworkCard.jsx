/*
|--------------------------------------------------------------------------
| DIGESTEX Framework Card™
|--------------------------------------------------------------------------
|
| Displays the Capability Framework™ and Manufacturing
| Framework™ selected by the DIGESTEX Decision Engine.
|
|--------------------------------------------------------------------------
*/

import { Boxes, Factory, ArrowRight } from "lucide-react";

export default function FrameworkCard({
    capabilityProfile = "general",
    manufacturingProfile = "general",
}) {
    const ready = capabilityProfile !== "general";

    return (
        <div className="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            {/* Header */}

            <div className="flex items-center gap-3">
                <div className="rounded-xl bg-indigo-100 p-2">
                    <Boxes className="h-5 w-5 text-indigo-600" />
                </div>

                <div>
                    <div className="text-sm font-semibold text-slate-500">
                        Framework™
                    </div>

                    <div className="text-lg font-black">
                        DIGESTEX Decision Engine
                    </div>
                </div>
            </div>

            {/* Capability */}

            <div className="mt-6 rounded-xl border border-slate-200 p-4">
                <div className="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Capability Framework
                </div>

                <div className="mt-2 flex items-center gap-2">
                    <Boxes className="h-5 w-5 text-indigo-600" />

                    <span className="font-semibold">
                        {format(capabilityProfile)}
                    </span>
                </div>
            </div>

            {/* Arrow */}

            <div className="flex justify-center py-3">
                <ArrowRight className="h-5 w-5 text-slate-400" />
            </div>

            {/* Manufacturing */}

            <div className="rounded-xl border border-slate-200 p-4">
                <div className="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Manufacturing Framework
                </div>

                <div className="mt-2 flex items-center gap-2">
                    <Factory className="h-5 w-5 text-emerald-600" />

                    <span className="font-semibold">
                        {format(manufacturingProfile)}
                    </span>
                </div>
            </div>

            {/* Intelligence */}

            <div className="mt-6 rounded-xl bg-indigo-50 p-4">
                <div className="font-semibold text-indigo-700">
                    Decision Engine™
                </div>

                <p className="mt-2 text-sm leading-6 text-slate-600">
                    {ready
                        ? "Your business framework has been identified automatically. Step 3 will display the most relevant capability profile for your company."
                        : "Select one or more business activities to allow DIGESTEX to determine the appropriate capability framework."}
                </p>
            </div>

            {/* Status */}

            <div className="mt-6 flex items-center justify-between border-t pt-4">
                <span className="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Status
                </span>

                <span
                    className={`rounded-full px-3 py-1 text-xs font-bold ${
                        ready
                            ? "bg-emerald-100 text-emerald-700"
                            : "bg-slate-100 text-slate-500"
                    }`}
                >
                    {ready ? "Framework Ready" : "Waiting"}
                </span>
            </div>
        </div>
    );
}

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function format(value) {
    if (!value) {
        return "-";
    }

    return value
        .replaceAll("_", " ")
        .replace(/\b\w/g, (char) => char.toUpperCase());
}
