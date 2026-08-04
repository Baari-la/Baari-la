/*
|--------------------------------------------------------------------------
| DIGESTEX Business Category Card™
|--------------------------------------------------------------------------
|
| Displays the resolved Primary Business Category.
|
|--------------------------------------------------------------------------
*/

import { Factory } from "lucide-react";

export default function BusinessCategoryCard({ category = "general" }) {
    const isReady = category !== "general";

    return (
        <div className="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            {/* Header */}

            <div className="flex items-center gap-3">
                <div className="rounded-xl bg-indigo-100 p-2">
                    <Factory className="h-5 w-5 text-indigo-600" />
                </div>

                <div>
                    <div className="text-sm font-semibold text-slate-500">
                        Primary Business Category
                    </div>

                    <div className="text-lg font-black text-slate-900">
                        {format(category)}
                    </div>
                </div>
            </div>

            {/* Description */}

            <div className="mt-5 rounded-xl bg-slate-50 p-4">
                <p className="text-sm leading-6 text-slate-600">
                    {description(category)}
                </p>
            </div>

            {/* Status */}

            <div className="mt-5 flex items-center justify-between border-t pt-4">
                <span className="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Status
                </span>

                <span
                    className={`rounded-full px-3 py-1 text-xs font-bold ${
                        isReady
                            ? "bg-emerald-100 text-emerald-700"
                            : "bg-slate-100 text-slate-500"
                    }`}
                >
                    {isReady ? "Detected" : "Waiting"}
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

function description(category) {
    switch (category) {
        case "manufacturer":
            return "Your company is classified as a textile manufacturer based on its production activities.";

        case "quality_infrastructure":
            return "Your company provides testing, inspection, certification, or quality assurance services.";

        case "supporting_industry":
            return "Your company supports textile manufacturing through machinery, chemicals, accessories, or industrial services.";

        case "commercial":
            return "Your company focuses on trading, sourcing, buying office, or brand management activities.";

        default:
            return "Select one or more business activities to allow DIGESTEX Decision Engine™ to classify your business automatically.";
    }
}
