/*
|--------------------------------------------------------------------------
| DIGESTEX Summary Row™
|--------------------------------------------------------------------------
|
| Reusable row component used throughout the onboarding
| summary sidebar.
|
| Supports:
|
| • Icon
| • Label
| • Value
| • Optional Badge
| • Empty State
|
|--------------------------------------------------------------------------
*/

import { ChevronRight } from "lucide-react";

export default function SummaryRow({
    icon: Icon,
    label,
    value,
    badge = null,
    emptyText = "-",
    showArrow = false,
}) {
    const displayValue =
        value === undefined || value === null || value === ""
            ? emptyText
            : value;

    return (
        <div className="flex items-center justify-between border-b border-slate-100 py-3 last:border-b-0">
            {/* Left */}

            <div className="flex items-center gap-3 min-w-0">
                {Icon && (
                    <div className="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100">
                        <Icon className="h-4 w-4 text-slate-500" />
                    </div>
                )}

                <div className="min-w-0">
                    <div className="text-sm font-medium text-slate-600">
                        {label}
                    </div>
                </div>
            </div>

            {/* Right */}

            <div className="flex items-center gap-2">
                {badge}

                <span className="max-w-[180px] truncate text-right text-sm font-semibold text-slate-800">
                    {displayValue}
                </span>

                {showArrow && (
                    <ChevronRight className="h-4 w-4 text-slate-400" />
                )}
            </div>
        </div>
    );
}
