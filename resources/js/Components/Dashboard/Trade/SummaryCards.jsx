import {
    ArrowUpRight,
    ArrowDownRight,
    Globe,
    Boxes,
    Database,
    Scale,
} from "lucide-react";

export default function SummaryCards({ summary = {}, tradeFlow = "all" }) {
    /*
    |--------------------------------------------------------------------------
    | Trade Values
    |--------------------------------------------------------------------------
    */

    const exportValue = Number(summary.exportValue ?? 0);
    const importValue = Number(summary.importValue ?? 0);

    const tradeBalance = Number(
        summary.tradeBalance ?? exportValue - importValue,
    );

    /*
    |--------------------------------------------------------------------------
    | Trade Flow Visibility
    |--------------------------------------------------------------------------
    */

    const showExport = tradeFlow === "all" || tradeFlow === "export";

    const showImport = tradeFlow === "all" || tradeFlow === "import";

    const showBalance = tradeFlow === "all";

    /*
    |--------------------------------------------------------------------------
    | Formatter
    |--------------------------------------------------------------------------
    */

    const formatCurrency = (value) => {
        return new Intl.NumberFormat("en-US", {
            notation: "compact",
            maximumFractionDigits: 2,
        }).format(value);
    };

    /*
    |--------------------------------------------------------------------------
    | Cards
    |--------------------------------------------------------------------------
    */

    const cards = [
        {
            title: "Export",

            value: showExport ? `$ ${formatCurrency(exportValue)}` : "—",

            icon: ArrowUpRight,

            color: showExport ? "text-green-600" : "text-slate-400",

            bg: showExport ? "bg-green-50" : "bg-slate-50",
        },

        {
            title: "Import",

            value: showImport ? `$ ${formatCurrency(importValue)}` : "—",

            icon: ArrowDownRight,

            color: showImport ? "text-red-600" : "text-slate-400",

            bg: showImport ? "bg-red-50" : "bg-slate-50",
        },

        {
            title: "Trade Balance",

            value: showBalance
                ? tradeBalance >= 0
                    ? `+$ ${formatCurrency(tradeBalance)}`
                    : `-$ ${formatCurrency(Math.abs(tradeBalance))}`
                : "—",

            icon: Scale,

            color: showBalance
                ? tradeBalance >= 0
                    ? "text-blue-600"
                    : "text-orange-600"
                : "text-slate-400",

            bg: showBalance
                ? tradeBalance >= 0
                    ? "bg-blue-50"
                    : "bg-orange-50"
                : "bg-slate-50",
        },

        {
            title: "Countries",
            value: summary.countries ?? "-",
            icon: Globe,
            color: "text-indigo-600",
            bg: "bg-indigo-50",
        },

        {
            title: "HS Codes",
            value: summary.hsCodes ?? "-",
            icon: Boxes,
            color: "text-purple-600",
            bg: "bg-purple-50",
        },

        {
            title: "Records",
            value: Number(summary.records ?? 0).toLocaleString("en-US"),
            icon: Database,
            color: "text-slate-700",
            bg: "bg-slate-100",
        },
    ];

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    return (
        <div className="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            {cards.map((card) => {
                const Icon = card.icon;

                return (
                    <div
                        key={card.title}
                        className="
                            rounded-2xl
                            border border-slate-200
                            bg-white
                            p-6
                            shadow-sm
                            transition
                            hover:shadow-md
                        "
                    >
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-slate-500">
                                    {card.title}
                                </p>

                                <h3 className="mt-3 text-3xl font-black text-slate-900">
                                    {card.value}
                                </h3>
                            </div>

                            <div className={`rounded-xl p-3 ${card.bg}`}>
                                <Icon className={card.color} size={28} />
                            </div>
                        </div>
                    </div>
                );
            })}
        </div>
    );
}
