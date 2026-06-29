import {
    ArrowUpRight,
    ArrowDownRight,
    Globe,
    Boxes,
    Database,
    Scale,
} from "lucide-react";

export default function SummaryCards({ summary = {} }) {
    const exportValue = Number(summary.exportValue ?? 0);
    const importValue = Number(summary.importValue ?? 0);

    const tradeBalance = exportValue - importValue;

    const formatCurrency = (value) => {
        return new Intl.NumberFormat("en-US", {
            notation: "compact",
            maximumFractionDigits: 2,
        }).format(value);
    };

    const cards = [
        {
            title: "Export",
            value: `$ ${formatCurrency(exportValue)}`,
            icon: ArrowUpRight,
            color: "text-green-600",
            bg: "bg-green-50",
        },

        {
            title: "Import",
            value: `$ ${formatCurrency(importValue)}`,
            icon: ArrowDownRight,
            color: "text-red-600",
            bg: "bg-red-50",
        },

        {
            title: "Trade Balance",
            value: `$ ${formatCurrency(tradeBalance)}`,
            icon: Scale,
            color: tradeBalance >= 0 ? "text-blue-600" : "text-orange-600",
            bg: tradeBalance >= 0 ? "bg-blue-50" : "bg-orange-50",
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
            value: Number(summary.records ?? 0).toLocaleString(),
            icon: Database,
            color: "text-slate-700",
            bg: "bg-slate-100",
        },
    ];

    return (
        <div className="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            {cards.map((card) => {
                const Icon = card.icon;

                return (
                    <div
                        key={card.title}
                        className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:shadow-md"
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
