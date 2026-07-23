import { ShoppingBag, Globe, BadgeCheck, Star } from "lucide-react";

export default function BuyerDiscovery({ buyers = [] }) {
    return (
        <div
            className="
                overflow-hidden
                rounded-3xl
                border
                border-slate-200
                bg-white
                shadow-sm
            "
        >
            {/* Header */}

            <div className="border-b px-6 py-5">
                <div className="flex items-center gap-3">
                    <ShoppingBag size={24} />

                    <div>
                        <h2 className="text-xl font-bold">Buyer Discovery</h2>

                        <p className="text-sm text-slate-500">
                            Discover potential buyers and brands.
                        </p>
                    </div>
                </div>
            </div>

            <div className="p-6">
                <div className="space-y-4">
                    {buyers.length === 0 ? (
                        <div className="py-10 text-center">
                            <ShoppingBag
                                size={40}
                                className="
                                    mx-auto
                                    text-slate-400
                                "
                            />

                            <p className="mt-3 text-slate-500">
                                No buyers found.
                            </p>
                        </div>
                    ) : (
                        buyers.map((buyer, index) => (
                            <div
                                key={buyer.company_id}
                                className="
                                        rounded-2xl
                                        border
                                        border-slate-200
                                        p-5
                                    "
                            >
                                <div className="flex items-start justify-between">
                                    <div>
                                        <p
                                            className="
                                                    text-lg
                                                    font-bold
                                                    text-slate-900
                                                "
                                        >
                                            #{index + 1} {buyer.name}
                                        </p>

                                        <p
                                            className="
                                                    mt-1
                                                    text-sm
                                                    text-slate-500
                                                "
                                        >
                                            {buyer.business_role ??
                                                "Potential Buyer"}
                                        </p>
                                    </div>

                                    <span
                                        className="
                                                rounded-full
                                                bg-emerald-100
                                                px-3
                                                py-1
                                                text-sm
                                                font-bold
                                                text-emerald-700
                                            "
                                    >
                                        {buyer.score}%
                                    </span>
                                </div>

                                {/* Stats */}

                                <div className="mt-4 grid gap-4 lg:grid-cols-3">
                                    <InfoBox
                                        icon={<Globe size={16} />}
                                        title="Country"
                                        value={
                                            buyer.country?.country_name_en ??
                                            buyer.country ??
                                            "-"
                                        }
                                    />

                                    <InfoBox
                                        icon={<Star size={16} />}
                                        title="Role"
                                        value={
                                            buyer.business_role ??
                                            "Potential Buyer"
                                        }
                                    />

                                    <InfoBox
                                        icon={<BadgeCheck size={16} />}
                                        title="Certifications"
                                        value={
                                            buyer.certifications?.length ?? 0
                                        }
                                    />
                                </div>

                                {/* Certifications */}

                                <div className="mt-5">
                                    <p className="text-sm font-semibold">
                                        Certifications
                                    </p>

                                    <div className="mt-2 flex flex-wrap gap-2">
                                        {buyer.certifications
                                            ?.filter(Boolean)
                                            .map((cert, index) => (
                                                <span
                                                    key={`${index}-${typeof cert === "string" ? cert : cert.id}`}
                                                    className="
                                                                rounded-full
                                                                bg-emerald-100
                                                                px-3
                                                                py-1
                                                                text-xs
                                                                font-medium
                                                                text-emerald-700
                                                            "
                                                >
                                                    {typeof cert === "string"
                                                        ? cert
                                                        : (cert.certification_name ??
                                                          cert.name)}
                                                </span>
                                            ))}
                                    </div>
                                </div>

                                {/* Markets */}

                                <div className="mt-5">
                                    <p className="text-sm font-semibold">
                                        Markets
                                    </p>

                                    <div className="mt-2 flex flex-wrap gap-2">
                                        {buyer.markets
                                            ?.filter(Boolean)
                                            .map((market, index) => (
                                                <span
                                                    key={`${marketLabel(
                                                        market,
                                                    )}-${index}`}
                                                    className="
                rounded-full
                bg-blue-100
                px-3
                py-1
                text-xs
                font-medium
                text-blue-700
            "
                                                >
                                                    {marketFlag(
                                                        marketLabel(market),
                                                    )}{" "}
                                                    {marketLabel(market)}
                                                </span>
                                            ))}
                                    </div>
                                </div>

                                {/* Actions */}

                                <div className="mt-5 flex gap-3">
                                    <button
                                        className="
                                                rounded-xl
                                                bg-slate-900
                                                px-4
                                                py-2
                                                text-sm
                                                font-medium
                                                text-white
                                            "
                                    >
                                        View Buyer
                                    </button>

                                    <button
                                        className="
                                                rounded-xl
                                                border
                                                border-slate-300
                                                px-4
                                                py-2
                                                text-sm
                                                font-medium
                                            "
                                    >
                                        Add to Pipeline
                                    </button>
                                </div>
                            </div>
                        ))
                    )}
                </div>
            </div>
        </div>
    );
}

function marketFlag(market) {
    return (
        {
            Japan: "🇯🇵",

            ASEAN: "🌏",

            Europe: "🇪🇺",

            France: "🇫🇷",

            Sweden: "🇸🇪",

            "United States": "🇺🇸",

            "United Kingdom": "🇬🇧",

            Germany: "🇩🇪",

            Italy: "🇮🇹",

            China: "🇨🇳",

            Indonesia: "🇮🇩",

            Global: "🌎",

            Asia: "🌏",
        }[market] ?? "🌎"
    );
}

function marketLabel(market) {
    if (typeof market === "string") {
        return market;
    }

    return market?.country_name_en ?? "Global";
}

function InfoBox({ icon, title, value }) {
    return (
        <div
            className="
                rounded-xl
                bg-slate-50
                p-4
            "
        >
            <div className="flex items-center gap-2">
                {icon}

                <p className="text-xs font-bold uppercase">{title}</p>
            </div>

            <p className="mt-2 text-sm text-slate-700">
                {typeof value === "object"
                    ? (value?.country_name_en ?? value?.name ?? "-")
                    : (value ?? "-")}
            </p>
        </div>
    );
}
