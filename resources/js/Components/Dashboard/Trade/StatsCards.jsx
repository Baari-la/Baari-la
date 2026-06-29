import { DollarSign, Landmark, TrendingUp, Building2 } from "lucide-react";

export default function StatsCards({
    cottonPrice,
    cottonTrend,
    liveExchangeRate,
    exportValue,
    totalCompanies = "12,850",
}) {
    const cards = [
        {
            title: "Cotton Price",
            value: cottonPrice,
            suffix: "USD/lb",
            icon: DollarSign,
            border: "border-amber-500",
            bg: "bg-amber-50",
            iconColor: "text-amber-600",
            badge:
                parseFloat(cottonTrend) >= 0
                    ? "bg-green-100 text-green-700"
                    : "bg-red-100 text-red-700",
            badgeText: cottonTrend,
        },
        {
            title: "USD / IDR",
            value: `Rp ${Number(liveExchangeRate).toLocaleString("id-ID")}`,
            icon: Landmark,
            border: "border-emerald-500",
            bg: "bg-emerald-50",
            iconColor: "text-emerald-600",
            subtitle: "Live Exchange Rate",
        },
        {
            title: "National Export",
            value: `$${exportValue}`,
            suffix: "B",
            icon: TrendingUp,
            border: "border-blue-600",
            bg: "bg-blue-50",
            iconColor: "text-blue-600",
            subtitle: "Textile & Apparel Sector",
        },
        {
            title: "Active Companies",
            value: totalCompanies,
            icon: Building2,
            border: "border-indigo-500",
            bg: "bg-indigo-50",
            iconColor: "text-indigo-600",
            subtitle: "Verified Directory Members",
        },
    ];

    return (
        <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
            {cards.map((card) => {
                const Icon = card.icon;

                return (
                    <div
                        key={card.title}
                        className={`
                            relative overflow-hidden
                            bg-white
                            rounded-3xl
                            border-l-4 ${card.border}
                            shadow-sm
                            hover:shadow-xl
                            hover:-translate-y-1
                            transition-all duration-300
                            p-6
                        `}
                    >
                        {/* Glow Effect */}
                        <div
                            className={`absolute top-0 right-0 w-24 h-24 ${card.bg} rounded-full blur-2xl opacity-50`}
                        />

                        <div className="relative z-10">
                            <div className="flex justify-between items-start">
                                <div>
                                    <p className="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                                        {card.title}
                                    </p>

                                    {card.badgeText && (
                                        <span
                                            className={`inline-flex mt-2 px-2 py-1 rounded-full text-[10px] font-bold ${card.badge}`}
                                        >
                                            {card.badgeText}
                                        </span>
                                    )}
                                </div>

                                <div
                                    className={`w-12 h-12 rounded-2xl ${card.bg} flex items-center justify-center`}
                                >
                                    <Icon
                                        className={`w-6 h-6 ${card.iconColor}`}
                                    />
                                </div>
                            </div>

                            <h3 className="mt-4 text-2xl lg:text-3xl font-black text-slate-900">
                                {card.value}
                                {card.suffix && (
                                    <span className="ml-1 text-sm font-medium text-slate-400">
                                        {card.suffix}
                                    </span>
                                )}
                            </h3>

                            {card.subtitle && (
                                <p className="mt-2 text-[11px] text-slate-500 font-medium uppercase tracking-wide">
                                    {card.subtitle}
                                </p>
                            )}
                        </div>
                    </div>
                );
            })}
        </div>
    );
}
