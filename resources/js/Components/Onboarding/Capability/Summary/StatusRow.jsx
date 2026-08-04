/*
|--------------------------------------------------------------------------
| DIGESTEX Status Row™
|--------------------------------------------------------------------------
|
| Displays the completion or readiness status of an item.
|
| Example:
|
| ✓ Export Ready
| ✓ OEM Manufacturing
| ○ Sampling Service
| ✕ Sustainability
|
|--------------------------------------------------------------------------
*/

import { CheckCircle2, Circle, XCircle } from "lucide-react";

export default function StatusRow({
    label,
    status = false,
    trueLabel = "Completed",
    falseLabel = "Pending",
}) {
    const Icon = status ? CheckCircle2 : Circle;

    return (
        <div className="flex items-center justify-between border-b border-slate-100 py-3 last:border-b-0">
            <div className="flex items-center gap-3">
                <Icon
                    className={`h-5 w-5 ${
                        status ? "text-emerald-500" : "text-slate-300"
                    }`}
                />

                <span className="text-sm font-medium text-slate-700">
                    {label}
                </span>
            </div>

            <span
                className={`text-sm font-semibold ${
                    status ? "text-emerald-600" : "text-slate-400"
                }`}
            >
                {status ? trueLabel : falseLabel}
            </span>
        </div>
    );
}
