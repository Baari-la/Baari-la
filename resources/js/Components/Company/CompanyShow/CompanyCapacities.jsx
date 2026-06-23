export default function CompanyCapacities({ company }) {
    if (!company?.capacities?.length) {
        return null;
    }

    const totalCapacity = company.capacities.reduce(
        (sum, item) => sum + Number(item.capacity_value || 0),
        0,
    );

    const totalMachines = company.capacities.reduce(
        (sum, item) => sum + Number(item.machine_count || 0),
        0,
    );

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
                top-0
                left-0
                h-72
                w-72
                rounded-full
                bg-yellow-500/10
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
                        text-yellow-500
                        mb-3
                    "
                    >
                        Manufacturing Capabilities
                    </div>

                    <h2 className="text-3xl font-black text-white">
                        Production Capacity
                    </h2>

                    <p className="text-gray-400 mt-3 max-w-3xl">
                        Production scale, operational readiness, manufacturing
                        capability, and capacity overview.
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
                            Production Lines
                        </div>

                        <div className="text-4xl font-black text-white">
                            {company.capacities.length}
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
                            Total Capacity
                        </div>

                        <div className="text-4xl font-black text-emerald-400">
                            {totalCapacity.toLocaleString()}
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
                            Machines
                        </div>

                        <div className="text-4xl font-black text-yellow-400">
                            {totalMachines}
                        </div>
                    </div>
                </div>

                {/* CAPACITY CARDS */}
                <div className="grid lg:grid-cols-2 gap-6">
                    {company.capacities.map((capacity) => (
                        <div
                            key={capacity.id}
                            className="
                            rounded-[32px]
                            border
                            border-white/10
                            bg-gradient-to-br
                            from-white/5
                            to-white/[0.02]
                            p-7
                            transition-all
                            duration-300
                            hover:scale-[1.02]
                        "
                        >
                            {/* TOP */}
                            <div className="flex justify-between items-start gap-4">
                                <div>
                                    <h3
                                        className="
                                        text-2xl
                                        font-black
                                        uppercase
                                        italic
                                        text-white
                                        mb-2
                                    "
                                    >
                                        {capacity.item_name}
                                    </h3>

                                    <p
                                        className="
                                        text-xs
                                        uppercase
                                        tracking-widest
                                        text-gray-400
                                    "
                                    >
                                        {capacity.capacity_type}
                                    </p>
                                </div>

                                <div className="text-right">
                                    <div
                                        className="
                                        text-4xl
                                        font-black
                                        text-emerald-400
                                    "
                                    >
                                        {Number(
                                            capacity.capacity_value || 0,
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
                                        {capacity.capacity_unit}
                                    </div>
                                </div>
                            </div>

                            {/* BADGES */}
                            <div className="flex flex-wrap gap-3 mt-6">
                                {capacity.capacity_category && (
                                    <span
                                        className="
                                        px-3
                                        py-2
                                        rounded-full
                                        bg-blue-500/10
                                        text-blue-400
                                        text-[10px]
                                        uppercase
                                        font-black
                                        tracking-widest
                                    "
                                    >
                                        {capacity.capacity_category}
                                    </span>
                                )}

                                {capacity.machine_count > 0 && (
                                    <span
                                        className="
                                        px-3
                                        py-2
                                        rounded-full
                                        bg-yellow-500/10
                                        text-yellow-400
                                        text-[10px]
                                        uppercase
                                        font-black
                                        tracking-widest
                                    "
                                    >
                                        {capacity.machine_count} Machines
                                    </span>
                                )}

                                {capacity.shift_info && (
                                    <span
                                        className="
                                        px-3
                                        py-2
                                        rounded-full
                                        bg-emerald-500/10
                                        text-emerald-400
                                        text-[10px]
                                        uppercase
                                        font-black
                                        tracking-widest
                                    "
                                    >
                                        {capacity.shift_info}
                                    </span>
                                )}
                            </div>

                            {/* NOTES */}
                            {capacity.notes && (
                                <div
                                    className="
                                    mt-6
                                    pt-5
                                    border-t
                                    border-white/10
                                "
                                >
                                    <p
                                        className="
                                        text-sm
                                        text-gray-300
                                        leading-relaxed
                                    "
                                    >
                                        {capacity.notes}
                                    </p>
                                </div>
                            )}
                        </div>
                    ))}
                </div>
            </div>
        </section>
    );
}
