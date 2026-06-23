export default function CompanyMachines({ company }) {
    if (!company?.machines?.length) {
        return null;
    }

    const totalMachines = company.machines.reduce(
        (sum, machine) => sum + Number(machine.quantity || 0),
        0,
    );

    const categories = [
        ...new Set(
            company.machines
                .map((machine) => machine.machine_category)
                .filter(Boolean),
        ),
    ];

    const countries = [
        ...new Set(
            company.machines
                .map((machine) => machine.country_origin)
                .filter(Boolean),
        ),
    ];

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
            <div
                className="
                absolute
                top-0
                right-0
                h-72
                w-72
                rounded-full
                bg-cyan-500/10
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
                        text-cyan-400
                        mb-3
                    "
                    >
                        Production Technology
                    </div>

                    <h2 className="text-3xl font-black text-white">
                        Machinery & Equipment
                    </h2>

                    <p className="text-gray-400 mt-3 max-w-3xl">
                        Manufacturing equipment, automation capability, and
                        production technology infrastructure.
                    </p>
                </div>

                {/* SUMMARY */}
                <div className="grid md:grid-cols-3 gap-5 mb-10">
                    <div className="rounded-3xl border border-white/10 bg-white/5 p-6">
                        <div className="text-[10px] uppercase tracking-[0.3em] text-gray-500 font-black mb-3">
                            Total Machines
                        </div>

                        <div className="text-4xl font-black text-cyan-400">
                            {totalMachines}
                        </div>
                    </div>

                    <div className="rounded-3xl border border-white/10 bg-white/5 p-6">
                        <div className="text-[10px] uppercase tracking-[0.3em] text-gray-500 font-black mb-3">
                            Categories
                        </div>

                        <div className="text-4xl font-black text-white">
                            {categories.length}
                        </div>
                    </div>

                    <div className="rounded-3xl border border-white/10 bg-white/5 p-6">
                        <div className="text-[10px] uppercase tracking-[0.3em] text-gray-500 font-black mb-3">
                            Technology Origin
                        </div>

                        <div className="text-lg font-bold text-white">
                            {countries.slice(0, 3).join(", ") || "-"}
                        </div>
                    </div>
                </div>

                {/* MACHINE CARDS */}
                <div className="grid lg:grid-cols-2 gap-6">
                    {company.machines.map((machine) => (
                        <div
                            key={machine.id}
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
                            <div className="flex justify-between items-start mb-5">
                                <div>
                                    <div className="text-xs uppercase tracking-widest text-cyan-400 font-black mb-2">
                                        {machine.machine_category}
                                    </div>

                                    <h3 className="text-2xl font-black text-white">
                                        {machine.machine_brand}
                                    </h3>

                                    <div className="text-gray-400 mt-1">
                                        {machine.machine_model}
                                    </div>
                                </div>

                                <div className="text-right">
                                    <div className="text-4xl font-black text-cyan-400">
                                        {machine.quantity || 0}
                                    </div>

                                    <div className="text-[10px] uppercase tracking-widest text-gray-500">
                                        Units
                                    </div>
                                </div>
                            </div>

                            <div className="flex flex-wrap gap-3 mb-6">
                                {machine.country_origin && (
                                    <span className="px-3 py-2 rounded-full bg-blue-500/10 text-blue-400 text-[10px] font-black uppercase">
                                        {machine.country_origin}
                                    </span>
                                )}

                                {machine.automation_level && (
                                    <span className="px-3 py-2 rounded-full bg-emerald-500/10 text-emerald-400 text-[10px] font-black uppercase">
                                        {machine.automation_level}
                                    </span>
                                )}

                                {machine.machine_condition && (
                                    <span className="px-3 py-2 rounded-full bg-yellow-500/10 text-yellow-400 text-[10px] font-black uppercase">
                                        {machine.machine_condition}
                                    </span>
                                )}
                            </div>

                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <div className="text-[10px] uppercase text-gray-500">
                                        Installed
                                    </div>

                                    <div className="font-bold text-white">
                                        {machine.year_installed || "-"}
                                    </div>
                                </div>

                                <div>
                                    <div className="text-[10px] uppercase text-gray-500">
                                        Capacity
                                    </div>

                                    <div className="font-bold text-white">
                                        {machine.production_capacity || "-"}{" "}
                                        {machine.capacity_unit || ""}
                                    </div>
                                </div>
                            </div>

                            {machine.notes && (
                                <div className="mt-6 pt-5 border-t border-white/10">
                                    <p className="text-sm text-gray-300 leading-relaxed">
                                        {machine.notes}
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
