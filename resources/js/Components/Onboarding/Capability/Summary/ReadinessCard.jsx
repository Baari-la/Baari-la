/*
|--------------------------------------------------------------------------
| DIGESTEX Readiness Card™
|--------------------------------------------------------------------------
|
| Generic readiness indicator used throughout DIGESTEX.
|
| Examples:
|
| • Buyer Readiness™
| • ESG Readiness™
| • Manufacturing Readiness™
| • Export Readiness™
| • Digital Readiness™
|
|--------------------------------------------------------------------------
*/

import {
    BadgeCheck,
    CircleCheckBig,
    CircleDashed,
    TriangleAlert,
} from "lucide-react";

const LEVELS = {
    excellent: {
        label: "Excellent",
        icon: CircleCheckBig,
        color: "emerald",
        border: "border-emerald-200",
        background: "bg-emerald-50",
        title: "text-emerald-700",
        value: "text-emerald-600",
    },

    good: {
        label: "Good",
        icon: BadgeCheck,
        color: "blue",
        border: "border-blue-200",
        background: "bg-blue-50",
        title: "text-blue-700",
        value: "text-blue-600",
    },

    fair: {
        label: "Fair",
        icon: TriangleAlert,
        color: "amber",
        border: "border-amber-200",
        background: "bg-amber-50",
        title: "text-amber-700",
        value: "text-amber-600",
    },

    starting: {
        label: "Getting Started",
        icon: CircleDashed,
        color: "slate",
        border: "border-slate-200",
        background: "bg-slate-50",
        title: "text-slate-700",
        value: "text-slate-600",
    },
};

export default function ReadinessCard({
    title,
    level = "starting",
    description = "",
}) {
    const config = LEVELS[level] ?? LEVELS.starting;

    const Icon = config.icon;

    return (
        <div
            className={`rounded-3xl border p-7 ${config.border} ${config.background}`}
        >
            <div className="flex items-center gap-3">
                <Icon className={`h-6 w-6 ${config.value}`} />

                <h3 className={`text-lg font-black ${config.title}`}>
                    {title}
                </h3>
            </div>

            <div className={`mt-6 text-3xl font-black ${config.value}`}>
                {config.label}
            </div>

            {description && (
                <p className="mt-4 text-sm leading-6 text-slate-600">
                    {description}
                </p>
            )}
        </div>
    );
}
