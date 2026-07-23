import { Factory, BadgeCheck, Globe, Star } from "lucide-react";

export default function SupplierMatching({ suppliers = [] }) {
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
                    <Factory size={24} />

                    <div>
                        <h2 className="text-xl font-bold">Supplier Matching</h2>

                        <p className="text-sm text-slate-500">
                            AI-powered supplier recommendations.
                        </p>
                    </div>
                </div>
            </div>

            <div className="p-6">
                <div className="space-y-4">
                    {suppliers.length === 0 ? (
                        <div className="py-10 text-center">
                            <Factory
                                size={40}
                                className="
                                    mx-auto
                                    text-slate-400
                                "
                            />

                            <p className="mt-3 text-slate-500">
                                No suppliers found.
                            </p>
                        </div>
                    ) : (
                        suppliers.map((supplier, index) => (
                            <div
                                key={supplier.company_id}
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
                                            #{index + 1} {supplier.name}
                                        </p>

                                        <p
                                            className="
                                                    mt-1
                                                    text-sm
                                                    text-slate-500
                                                "
                                        >
                                            {supplier.segment}
                                        </p>
                                    </div>

                                    <span
                                        className="
                                                rounded-full
                                                bg-violet-100
                                                px-3
                                                py-1
                                                text-sm
                                                font-bold
                                                text-violet-700
                                            "
                                    >
                                        {supplier.score}%
                                    </span>
                                </div>

                                {/* Stats */}

                                <div className="mt-4 grid gap-4 lg:grid-cols-3">
                                    <InfoBox
                                        icon={<Globe size={16} />}
                                        title="Country"
                                        value={
                                            supplier.country?.country_name_en ??
                                            supplier.country ??
                                            "-"
                                        }
                                    />

                                    <InfoBox
                                        icon={<Star size={16} />}
                                        title="Membership"
                                        value={supplier.membership ?? "-"}
                                    />

                                    <InfoBox
                                        icon={<BadgeCheck size={16} />}
                                        title="Certifications"
                                        value={
                                            supplier.certifications?.length ?? 0
                                        }
                                    />
                                </div>

                                {/* Certifications */}

                                <div className="mt-5">
                                    <p className="text-sm font-semibold">
                                        Certifications
                                    </p>

                                    <div className="mt-2 flex flex-wrap gap-2">
                                        {supplier.certifications
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
                                        Export Markets
                                    </p>

                                    <div className="mt-2 flex flex-wrap gap-2">
                                        {supplier.markets
                                            ?.filter(Boolean)
                                            .map((market, index) => (
                                                <span
                                                    key={`${index}-${typeof market === "string" ? market : market.id}`}
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
                                                    {typeof market === "string"
                                                        ? market
                                                        : `${market.flag_emoji ?? "🌎"} ${market.country_name_en}`}
                                                </span>
                                            ))}
                                    </div>
                                </div>
                            </div>
                        ))
                    )}
                </div>
            </div>
        </div>
    );
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

            <p className="mt-2 text-sm text-slate-700">{value ?? "-"}</p>
        </div>
    );
}
