import {
    Activity,
    Building2,
    CircleDollarSign,
    Globe2,
    Landmark,
    TrendingDown,
    TrendingUp,
    Scale,
} from "lucide-react";

/*
|--------------------------------------------------------------------------
| Format USD Trade Value
|--------------------------------------------------------------------------
|
| Database menyimpan nilai perdagangan dalam USD penuh:
|
| 11,975,228,376.685 -> $11.98B
|  9,166,801,046.636 -> $9.17B
|
*/

function formatUSDCompact(value, showSign = false) {
    const number = Number(value);

    if (!Number.isFinite(number)) {
        return "-";
    }

    const sign = showSign && number > 0 ? "+" : "";

    const absolute = Math.abs(number);

    if (absolute >= 1_000_000_000) {
        return `${sign}$${(number / 1_000_000_000).toFixed(2)}B`;
    }

    if (absolute >= 1_000_000) {
        return `${sign}$${(number / 1_000_000).toFixed(2)}M`;
    }

    if (absolute >= 1_000) {
        return `${sign}$${(number / 1_000).toFixed(2)}K`;
    }

    return `${sign}$${number.toLocaleString("en-US", {
        maximumFractionDigits: 2,
    })}`;
}

export default function StatsCards({
    cottonPrice,
    cottonTrend,
    liveExchangeRate,
    exportValue,
    importValue,
    tradeBalance,
    tradeYear = 2025,
    totalCompanies = 0,
}) {
    const cottonChange = Number.parseFloat(cottonTrend);

    const hasCottonTrend = Number.isFinite(cottonChange);

    const balance = Number(tradeBalance);

    const positiveBalance = Number.isFinite(balance) && balance >= 0;

    const cards = [
        {
            key: "cotton",
            eyebrow: "Commodity",
            title: "Cotton Price",
            value:
                cottonPrice !== null && cottonPrice !== undefined
                    ? Number(cottonPrice).toLocaleString("en-US", {
                          minimumFractionDigits: 2,
                          maximumFractionDigits: 2,
                      })
                    : "-",
            suffix: "USD/lb",
            icon: CircleDollarSign,
            iconBg: "bg-amber-50",
            iconColor: "text-amber-600",
            description: "Global cotton benchmark",

            indicator: hasCottonTrend
                ? {
                      value: cottonTrend,
                      positive: cottonChange >= 0,
                  }
                : null,
        },

        {
            key: "currency",
            eyebrow: "Currency",
            title: "USD / IDR",
            value:
                liveExchangeRate !== null && liveExchangeRate !== undefined
                    ? `Rp ${Number(liveExchangeRate).toLocaleString("id-ID")}`
                    : "-",
            icon: Landmark,
            iconBg: "bg-emerald-50",
            iconColor: "text-emerald-600",
            description: "Reference exchange rate",
        },

        {
            key: "export",
            eyebrow: `Trade ${tradeYear}`,
            title: "National Export",
            value: formatUSDCompact(exportValue),
            icon: TrendingUp,
            iconBg: "bg-blue-50",
            iconColor: "text-blue-600",
            description: "Textile & apparel exports",
        },

        {
            key: "import",
            eyebrow: `Trade ${tradeYear}`,
            title: "National Import",
            value: formatUSDCompact(importValue),
            icon: TrendingDown,
            iconBg: "bg-violet-50",
            iconColor: "text-violet-600",
            description: "Textile & apparel imports",
        },

        {
            key: "balance",
            eyebrow: `Trade ${tradeYear}`,
            title: "Trade Balance",
            value: formatUSDCompact(tradeBalance, true),
            icon: Scale,
            iconBg: positiveBalance ? "bg-emerald-50" : "bg-red-50",
            iconColor: positiveBalance ? "text-emerald-600" : "text-red-600",
            description: positiveBalance
                ? "National trade surplus"
                : "National trade deficit",
            indicator: {
                value: positiveBalance ? "Surplus" : "Deficit",
                positive: positiveBalance,
            },
        },

        {
            key: "companies",
            eyebrow: "Industry Network",
            title: "Companies Tracked",
            value: Number(totalCompanies || 0).toLocaleString("en-US"),
            icon: Building2,
            iconBg: "bg-indigo-50",
            iconColor: "text-indigo-600",
            description: "Companies in DIGESTEX directory",
        },
    ];

    return (
        <section className="mb-8">
            {/* Header */}

            <div
                className="
                    mb-5
                    flex
                    flex-col
                    gap-3
                    sm:flex-row
                    sm:items-end
                    sm:justify-between
                "
            >
                <div>
                    <div
                        className="
                            flex
                            items-center
                            gap-2
                            text-[11px]
                            font-black
                            uppercase
                            tracking-[0.2em]
                            text-blue-600
                        "
                    >
                        <Activity className="h-4 w-4" />
                        Market Pulse
                    </div>

                    <h2
                        className="
                            mt-2
                            text-2xl
                            font-black
                            tracking-tight
                            text-slate-900
                        "
                    >
                        Textile Market Snapshot
                    </h2>

                    <p
                        className="
                            mt-1
                            max-w-2xl
                            text-sm
                            text-slate-500
                        "
                    >
                        Key commodity, currency, trade, and industry indicators
                        in one executive view.
                    </p>
                </div>

                <div
                    className="
                        inline-flex
                        items-center
                        gap-2
                        text-[10px]
                        font-bold
                        uppercase
                        tracking-[0.16em]
                        text-slate-400
                    "
                >
                    <span
                        className="
                            h-2
                            w-2
                            rounded-full
                            bg-emerald-500
                        "
                    />
                    Intelligence Feed
                </div>
            </div>

            {/* Market Pulse Grid */}

            <div
                className="
                    grid
                    grid-cols-1
                    gap-4
                    md:grid-cols-2
                    xl:grid-cols-3
                "
            >
                {cards.map((card) => {
                    const Icon = card.icon;

                    return (
                        <article
                            key={card.key}
                            className="
                                group
                                relative
                                overflow-hidden
                                rounded-[28px]
                                border
                                border-slate-200
                                bg-white
                                p-6
                                shadow-sm
                                transition
                                duration-300
                                hover:-translate-y-0.5
                                hover:border-slate-300
                                hover:shadow-lg
                            "
                        >
                            <div
                                className="
                                    flex
                                    items-start
                                    justify-between
                                    gap-4
                                "
                            >
                                <div>
                                    <p
                                        className="
                                            text-[9px]
                                            font-black
                                            uppercase
                                            tracking-[0.2em]
                                            text-slate-400
                                        "
                                    >
                                        {card.eyebrow}
                                    </p>

                                    <h3
                                        className="
                                            mt-1
                                            text-sm
                                            font-black
                                            text-slate-700
                                        "
                                    >
                                        {card.title}
                                    </h3>
                                </div>

                                <div
                                    className={`
                                        flex
                                        h-11
                                        w-11
                                        shrink-0
                                        items-center
                                        justify-center
                                        rounded-2xl
                                        ${card.iconBg}
                                    `}
                                >
                                    <Icon
                                        className={`
                                            h-5
                                            w-5
                                            ${card.iconColor}
                                        `}
                                    />
                                </div>
                            </div>

                            {/* Value */}

                            <div
                                className="
                                    mt-7
                                    flex
                                    flex-wrap
                                    items-end
                                    gap-2
                                "
                            >
                                <span
                                    className="
                                        text-3xl
                                        font-black
                                        tracking-tight
                                        text-slate-950
                                    "
                                >
                                    {card.value}
                                </span>

                                {card.suffix && (
                                    <span
                                        className="
                                            pb-1
                                            text-xs
                                            font-bold
                                            text-slate-400
                                        "
                                    >
                                        {card.suffix}
                                    </span>
                                )}
                            </div>

                            {/* Footer */}

                            <div
                                className="
                                    mt-5
                                    flex
                                    min-h-8
                                    items-center
                                    justify-between
                                    gap-3
                                    border-t
                                    border-slate-100
                                    pt-4
                                "
                            >
                                <p
                                    className="
                                        text-[10px]
                                        font-semibold
                                        uppercase
                                        tracking-wide
                                        text-slate-400
                                    "
                                >
                                    {card.description}
                                </p>

                                {card.indicator && (
                                    <TrendBadge
                                        value={card.indicator.value}
                                        positive={card.indicator.positive}
                                    />
                                )}
                            </div>
                        </article>
                    );
                })}
            </div>
        </section>
    );
}

/*
|--------------------------------------------------------------------------
| Trend Badge
|--------------------------------------------------------------------------
*/

function TrendBadge({ value, positive }) {
    const Icon = positive ? TrendingUp : TrendingDown;

    return (
        <span
            className={`
                inline-flex
                shrink-0
                items-center
                gap-1
                rounded-full
                px-2.5
                py-1
                text-[10px]
                font-black
                ${
                    positive
                        ? "bg-emerald-50 text-emerald-700"
                        : "bg-red-50 text-red-700"
                }
            `}
        >
            <Icon className="h-3 w-3" />

            {value}
        </span>
    );
}
