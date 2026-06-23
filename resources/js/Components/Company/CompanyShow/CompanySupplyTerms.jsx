export default function CompanySupplyTerms({ company }) {
    const hasMoqs = company?.moqs?.length > 0;
    const hasLeadTimes = company?.leadTimes?.length > 0;

    if (!hasMoqs && !hasLeadTimes) {
        return null;
    }

    const lowestMoq = hasMoqs
        ? Math.min(
              ...company.moqs.map((item) => Number(item.minimum_quantity || 0)),
          )
        : null;

    const fastestLeadTime = hasLeadTimes
        ? Math.min(...company.leadTimes.map((item) => Number(item.days || 0)))
        : null;

    const totalTerms =
        (company.moqs?.length || 0) + (company.leadTimes?.length || 0);

    return (
        <section
            className="
            relative
            overflow-hidden
            rounded-[40px]
            border
            border-white/10
            bg-white/5
            p-10
            mb-8
        "
        >
            {/* BACKGROUND EFFECT */}
            <div
                className="
                absolute
                bottom-0
                right-0
                h-72
                w-72
                rounded-full
                bg-orange-500/10
                blur-3xl
            "
            />

            <div className="relative z-10">
                {/* HEADER */}
                <div className="mb-10">
                    <div
                        className="
                        text-xs
                        font-black
                        uppercase
                        tracking-[0.35em]
                        text-orange-400
                        mb-3
                    "
                    >
                        Supply & Delivery Terms
                    </div>

                    <h2 className="text-3xl font-black text-white">
                        Commercial Requirements
                    </h2>

                    <p className="text-gray-400 mt-3 max-w-3xl">
                        Minimum order quantities, production lead times, and
                        fulfillment capability for sourcing decisions.
                    </p>
                </div>

                {/* SUMMARY */}
                <div className="grid md:grid-cols-3 gap-5 mb-10">
                    <div
                        className="
                        rounded-3xl
                        border
                        border-white/10
                        bg-white/5
                        p-6
                    "
                    >
                        <div
                            className="
                            text-[10px]
                            uppercase
                            tracking-[0.3em]
                            text-gray-500
                            font-black
                            mb-3
                        "
                        >
                            Lowest MOQ
                        </div>

                        <div
                            className="
                            text-4xl
                            font-black
                            text-orange-400
                        "
                        >
                            {lowestMoq ? lowestMoq.toLocaleString() : "-"}
                        </div>
                    </div>

                    <div
                        className="
                        rounded-3xl
                        border
                        border-white/10
                        bg-white/5
                        p-6
                    "
                    >
                        <div
                            className="
                            text-[10px]
                            uppercase
                            tracking-[0.3em]
                            text-gray-500
                            font-black
                            mb-3
                        "
                        >
                            Fastest Lead Time
                        </div>

                        <div
                            className="
                            text-4xl
                            font-black
                            text-yellow-400
                        "
                        >
                            {fastestLeadTime || "-"}
                        </div>

                        {fastestLeadTime && (
                            <div
                                className="
                                text-xs
                                uppercase
                                tracking-widest
                                text-gray-500
                            "
                            >
                                Days
                            </div>
                        )}
                    </div>

                    <div
                        className="
                        rounded-3xl
                        border
                        border-white/10
                        bg-white/5
                        p-6
                    "
                    >
                        <div
                            className="
                            text-[10px]
                            uppercase
                            tracking-[0.3em]
                            text-gray-500
                            font-black
                            mb-3
                        "
                        >
                            Available Terms
                        </div>

                        <div
                            className="
                            text-4xl
                            font-black
                            text-white
                        "
                        >
                            {totalTerms}
                        </div>
                    </div>
                </div>

                {/* MOQ */}
                {hasMoqs && (
                    <div className="mb-10">
                        <div
                            className="
                            text-sm
                            font-black
                            uppercase
                            tracking-widest
                            text-white
                            mb-5
                        "
                        >
                            Minimum Order Quantity
                        </div>

                        <div className="grid lg:grid-cols-2 gap-6">
                            {company.moqs.map((moq) => (
                                <div
                                    key={moq.id}
                                    className="
                                    rounded-[32px]
                                    border
                                    border-white/10
                                    bg-gradient-to-br
                                    from-white/5
                                    to-white/[0.02]
                                    p-7
                                "
                                >
                                    <div className="flex justify-between items-start gap-4">
                                        <div>
                                            <h3
                                                className="
                                                text-xl
                                                font-black
                                                text-white
                                            "
                                            >
                                                {moq.product_name ||
                                                    "General Product"}
                                            </h3>

                                            {moq.notes && (
                                                <p
                                                    className="
                                                    text-sm
                                                    text-gray-400
                                                    mt-3
                                                "
                                                >
                                                    {moq.notes}
                                                </p>
                                            )}
                                        </div>

                                        <div className="text-right">
                                            <div
                                                className="
                                                text-4xl
                                                font-black
                                                text-orange-400
                                            "
                                            >
                                                {Number(
                                                    moq.minimum_quantity || 0,
                                                ).toLocaleString()}
                                            </div>

                                            <div
                                                className="
                                                text-[10px]
                                                uppercase
                                                tracking-widest
                                                text-gray-500
                                                font-bold
                                            "
                                            >
                                                {moq.unit || "-"}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                )}

                {/* LEAD TIMES */}
                {hasLeadTimes && (
                    <div>
                        <div
                            className="
                            text-sm
                            font-black
                            uppercase
                            tracking-widest
                            text-white
                            mb-5
                        "
                        >
                            Production Lead Times
                        </div>

                        <div className="grid lg:grid-cols-2 gap-6">
                            {company.leadTimes.map((leadTime) => (
                                <div
                                    key={leadTime.id}
                                    className="
                                    rounded-[32px]
                                    border
                                    border-white/10
                                    bg-gradient-to-br
                                    from-white/5
                                    to-white/[0.02]
                                    p-7
                                "
                                >
                                    <div className="flex justify-between items-center">
                                        <div>
                                            <span
                                                className="
                                                inline-flex
                                                px-3
                                                py-2
                                                rounded-full
                                                bg-blue-500/10
                                                text-blue-400
                                                text-[10px]
                                                uppercase
                                                tracking-widest
                                                font-black
                                                mb-4
                                            "
                                            >
                                                {leadTime.lead_time_type ||
                                                    "Standard"}
                                            </span>

                                            {leadTime.notes && (
                                                <p
                                                    className="
                                                    text-sm
                                                    text-gray-400
                                                "
                                                >
                                                    {leadTime.notes}
                                                </p>
                                            )}
                                        </div>

                                        <div className="text-right">
                                            <div
                                                className="
                                                text-5xl
                                                font-black
                                                text-yellow-400
                                            "
                                            >
                                                {leadTime.days || 0}
                                            </div>

                                            <div
                                                className="
                                                text-[10px]
                                                uppercase
                                                tracking-widest
                                                text-gray-500
                                                font-bold
                                            "
                                            >
                                                Days
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                )}
            </div>
        </section>
    );
}
