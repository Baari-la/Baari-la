/*
|--------------------------------------------------------------------------
| DIGESTEX Value Chain Card™
|--------------------------------------------------------------------------
|
| Displays the company's position within the
| Global Textile Value Chain™.
|
|--------------------------------------------------------------------------
*/

import { Workflow } from "lucide-react";

export default function ValueChainCard({ valueChain = "general" }) {
    const isReady = valueChain !== "general";

    return (
        <div className="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            {/* Header */}

            <div className="flex items-center gap-3">
                <div className="rounded-xl bg-violet-100 p-2">
                    <Workflow className="h-5 w-5 text-violet-600" />
                </div>

                <div>
                    <div className="text-sm font-semibold text-slate-500">
                        Value Chain Position
                    </div>

                    <div className="text-lg font-black text-slate-900">
                        {format(valueChain)}
                    </div>
                </div>
            </div>

            {/* Description */}

            <div className="mt-5 rounded-xl bg-slate-50 p-4">
                <p className="text-sm leading-6 text-slate-600">
                    {description(valueChain)}
                </p>
            </div>

            {/* Timeline */}

            <div className="mt-6">
                <div className="mb-2 flex justify-between text-xs font-semibold text-slate-400">
                    <span>Upstream</span>

                    <span>Midstream</span>

                    <span>Downstream</span>
                </div>

                <div className="relative h-2 rounded-full bg-slate-200">
                    <div
                        className={`absolute top-0 h-2 rounded-full transition-all duration-500 ${barColor(
                            valueChain,
                        )}`}
                        style={{
                            width: `${progress(valueChain)}%`,
                        }}
                    />
                </div>
            </div>

            {/* Status */}

            <div className="mt-6 flex items-center justify-between border-t pt-4">
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

function description(valueChain) {
    switch (valueChain) {
        case "upstream":
            return "Your company primarily supplies raw materials or intermediate inputs used in textile manufacturing, such as fibers and yarns.";

        case "midstream":
            return "Your company transforms materials into fabrics or performs textile processing such as weaving, knitting, dyeing, finishing, or printing.";

        case "downstream":
            return "Your company produces finished products, manages brands, or supplies goods directly to domestic and international buyers.";

        case "supporting":
            return "Your company provides supporting products or services that enable textile manufacturing, including machinery, chemicals, testing, certification, and industrial solutions.";

        case "commercial":
            return "Your company connects buyers and suppliers through trading, sourcing, buying office, import-export, or commercial representation.";

        default:
            return "The value chain position will be determined automatically based on the business activities you select.";
    }
}

function progress(valueChain) {
    switch (valueChain) {
        case "upstream":
            return 20;

        case "midstream":
            return 55;

        case "downstream":
            return 90;

        case "supporting":
            return 50;

        case "commercial":
            return 100;

        default:
            return 0;
    }
}

function barColor(valueChain) {
    switch (valueChain) {
        case "upstream":
            return "bg-blue-500";

        case "midstream":
            return "bg-violet-500";

        case "downstream":
            return "bg-emerald-500";

        case "supporting":
            return "bg-amber-500";

        case "commercial":
            return "bg-indigo-500";

        default:
            return "bg-slate-300";
    }
}
