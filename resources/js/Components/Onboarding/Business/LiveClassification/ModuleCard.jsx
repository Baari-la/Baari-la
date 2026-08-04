/*
|--------------------------------------------------------------------------
| DIGESTEX Module Card™
|--------------------------------------------------------------------------
|
| Displays the modules that will be activated based on
| the selected Capability Framework™.
|
|--------------------------------------------------------------------------
*/

import { LayoutGrid, CheckCircle2, Circle } from "lucide-react";

export default function ModuleCard({ modules = [] }) {
    const ready = modules.length > 0;

    return (
        <div className="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            {/* Header */}

            <div className="flex items-center gap-3">
                <div className="rounded-xl bg-emerald-100 p-2">
                    <LayoutGrid className="h-5 w-5 text-emerald-600" />
                </div>

                <div>
                    <div className="text-sm font-semibold text-slate-500">
                        Activated Modules
                    </div>

                    <div className="text-lg font-black">DIGESTEX Modules™</div>
                </div>
            </div>

            {/* Empty */}

            {!ready && (
                <div className="mt-6 rounded-xl bg-slate-50 p-5 text-center">
                    <Circle className="mx-auto h-8 w-8 text-slate-400" />

                    <p className="mt-3 text-sm leading-6 text-slate-500">
                        Modules will appear automatically after your business
                        has been classified.
                    </p>
                </div>
            )}

            {/* Modules */}

            {ready && (
                <div className="mt-6 space-y-3">
                    {modules.map((module) => (
                        <div
                            key={module}
                            className="flex items-center justify-between rounded-xl border border-slate-200 p-3 transition hover:border-indigo-300 hover:bg-indigo-50"
                        >
                            <div className="flex items-center gap-3">
                                <CheckCircle2 className="h-5 w-5 text-emerald-600" />

                                <span className="font-medium">
                                    {format(module)}
                                </span>
                            </div>

                            <span className="rounded-full bg-emerald-100 px-2 py-1 text-xs font-bold text-emerald-700">
                                Ready
                            </span>
                        </div>
                    ))}
                </div>
            )}

            {/* Footer */}

            <div className="mt-6 rounded-xl bg-indigo-50 p-4">
                <div className="font-semibold text-indigo-700">
                    Module Intelligence™
                </div>

                <p className="mt-2 text-sm leading-6 text-slate-600">
                    {ready
                        ? `${modules.length} module${modules.length > 1 ? "s are" : " is"} ready to support your onboarding process. Step 3 will display these modules automatically.`
                        : "The appropriate modules will be activated after your business activities have been analyzed."}
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
                    {ready ? "Modules Ready" : "Waiting"}
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
