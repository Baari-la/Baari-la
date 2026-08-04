/*
|--------------------------------------------------------------------------
| DIGESTEX Industry Type Card™
|--------------------------------------------------------------------------
|
| Displays the resolved Industry Type.
|
|--------------------------------------------------------------------------
*/

import { Network } from "lucide-react";

export default function IndustryTypeCard({ industryType = "general" }) {
    const isReady = industryType !== "general";

    return (
        <div className="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            {/* Header */}

            <div className="flex items-center gap-3">
                <div className="rounded-xl bg-sky-100 p-2">
                    <Network className="h-5 w-5 text-sky-600" />
                </div>

                <div>
                    <div className="text-sm font-semibold text-slate-500">
                        Industry Type
                    </div>

                    <div className="text-lg font-black text-slate-900">
                        {format(industryType)}
                    </div>
                </div>
            </div>

            {/* Description */}

            <div className="mt-5 rounded-xl bg-slate-50 p-4">
                <p className="text-sm leading-6 text-slate-600">
                    {description(industryType)}
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

function description(industryType) {
    switch (industryType) {
        case "textile_manufacturer":
            return "DIGESTEX identifies your company as a textile manufacturing business operating within the industrial value chain.";

        case "quality_services":
            return "Your company operates as a quality infrastructure provider supporting testing, inspection, certification, and compliance.";

        case "textile_supporting":
            return "Your company provides supporting products or services such as machinery, chemicals, accessories, engineering, or industrial solutions.";

        case "commercial_services":
            return "Your company focuses on commercial activities including trading, sourcing, buying office operations, or brand management.";

        default:
            return "The industry type will be determined automatically after your business activities are selected.";
    }
}
