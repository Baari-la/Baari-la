export default function CompanyIntelligence({ company, isEn }) {
    const totalProducts = company?.products?.length || 0;

    const totalMachines =
        company?.machines?.reduce(
            (sum, item) => sum + Number(item.quantity || 0),
            0,
        ) || 0;

    const totalCapacities = company?.capacities?.length || 0;

    const totalCertifications = company?.certifications?.length || 0;

    const totalMarkets = company?.markets?.length || 0;

    const workforce = company?.tenaga_kerja || "-";

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
            {/* BACKGROUND */}
            <div
                className="
                    absolute
                    top-0
                    right-0
                    w-80
                    h-80
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
                        Corporate Intelligence
                    </div>

                    <h2 className="text-3xl font-black text-white">
                        Business Intelligence Summary
                    </h2>

                    <p className="text-gray-400 mt-3 max-w-3xl">
                        Operational scale, manufacturing readiness, market
                        reach, and company intelligence overview.
                    </p>
                </div>

                {/* KPI CARDS */}
                <div className="grid md:grid-cols-3 lg:grid-cols-6 gap-5 mb-10">
                    <div
                        className="
                            rounded-3xl
                            border
                            border-white/10
                            bg-white/5
                            p-5
                        "
                    >
                        <div className="text-[10px] uppercase tracking-widest text-gray-500 mb-2">
                            Workforce
                        </div>

                        <div className="text-3xl font-black text-white">
                            {workforce}
                        </div>
                    </div>

                    <div
                        className="
                            rounded-3xl
                            border
                            border-white/10
                            bg-white/5
                            p-5
                        "
                    >
                        <div className="text-[10px] uppercase tracking-widest text-gray-500 mb-2">
                            Markets
                        </div>

                        <div className="text-3xl font-black text-cyan-400">
                            {totalMarkets}
                        </div>
                    </div>

                    <div
                        className="
                            rounded-3xl
                            border
                            border-white/10
                            bg-white/5
                            p-5
                        "
                    >
                        <div className="text-[10px] uppercase tracking-widest text-gray-500 mb-2">
                            Products
                        </div>

                        <div className="text-3xl font-black text-white">
                            {totalProducts}
                        </div>
                    </div>

                    <div
                        className="
                            rounded-3xl
                            border
                            border-white/10
                            bg-white/5
                            p-5
                        "
                    >
                        <div className="text-[10px] uppercase tracking-widest text-gray-500 mb-2">
                            Machines
                        </div>

                        <div className="text-3xl font-black text-yellow-400">
                            {totalMachines}
                        </div>
                    </div>

                    <div
                        className="
                            rounded-3xl
                            border
                            border-white/10
                            bg-white/5
                            p-5
                        "
                    >
                        <div className="text-[10px] uppercase tracking-widest text-gray-500 mb-2">
                            Capacity
                        </div>

                        <div className="text-3xl font-black text-emerald-400">
                            {totalCapacities}
                        </div>
                    </div>

                    <div
                        className="
                            rounded-3xl
                            border
                            border-white/10
                            bg-white/5
                            p-5
                        "
                    >
                        <div className="text-[10px] uppercase tracking-widest text-gray-500 mb-2">
                            Certifications
                        </div>

                        <div className="text-3xl font-black text-purple-400">
                            {totalCertifications}
                        </div>
                    </div>
                </div>

                {/* INTELLIGENCE GRID */}
                <div className="grid lg:grid-cols-2 gap-8">
                    {/* MARKET REACH */}
                    <div
                        className="
                            rounded-[32px]
                            border
                            border-white/10
                            bg-gradient-to-br
                            from-white/5
                            to-white/[0.02]
                            p-8
                        "
                    >
                        <div
                            className="
                                text-[10px]
                                uppercase
                                tracking-[0.3em]
                                text-cyan-400
                                font-black
                                mb-4
                            "
                        >
                            Global Market Reach
                        </div>

                        <div
                            className="
                                text-lg
                                font-bold
                                text-white
                                leading-relaxed
                            "
                        >
                            {company.pasar_ekspor ||
                                (isEn
                                    ? "Export market information not available."
                                    : "Data pasar ekspor belum tersedia.")}
                        </div>
                    </div>

                    {/* CONTACT */}
                    <div
                        className="
                            rounded-[32px]
                            border
                            border-white/10
                            bg-gradient-to-br
                            from-white/5
                            to-white/[0.02]
                            p-8
                        "
                    >
                        <div
                            className="
                                text-[10px]
                                uppercase
                                tracking-[0.3em]
                                text-cyan-400
                                font-black
                                mb-5
                            "
                        >
                            Corporate Contact
                        </div>

                        <div className="space-y-5">
                            <div>
                                <div className="text-[10px] uppercase tracking-widest text-gray-500 mb-2">
                                    Phone
                                </div>

                                <div className="font-semibold text-white">
                                    {company.telepon || "-"}
                                </div>
                            </div>

                            <div>
                                <div className="text-[10px] uppercase tracking-widest text-gray-500 mb-2">
                                    Email
                                </div>

                                <div className="font-semibold text-white break-all">
                                    {company.email_web || "-"}
                                </div>
                            </div>

                            <div>
                                <div className="text-[10px] uppercase tracking-widest text-gray-500 mb-2">
                                    Location
                                </div>

                                <div className="font-semibold text-white">
                                    {company.city || "-"}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* VERIFICATION STATUS */}
                <div
                    className={`
                        mt-8
                        rounded-[32px]
                        border
                        p-6
                        ${
                            company.status_verifikasi === "verified"
                                ? "border-emerald-500/30 bg-emerald-500/10"
                                : "border-white/10 bg-white/5"
                        }
                    `}
                >
                    <div className="flex items-center gap-4">
                        <div
                            className={`
                                w-14
                                h-14
                                rounded-2xl
                                flex
                                items-center
                                justify-center
                                ${
                                    company.status_verifikasi === "verified"
                                        ? "bg-emerald-500"
                                        : "bg-gray-700"
                                }
                            `}
                        >
                            <i
                                className={`fas ${
                                    company.status_verifikasi === "verified"
                                        ? "fa-shield-check"
                                        : "fa-clock"
                                } text-white`}
                            />
                        </div>

                        <div>
                            <div
                                className="
                                    text-xs
                                    uppercase
                                    tracking-[0.3em]
                                    font-black
                                    text-emerald-400
                                "
                            >
                                Verification Status
                            </div>

                            <div className="text-xl font-black text-white mt-1">
                                {company.status_verifikasi === "verified"
                                    ? "Verified Industry Profile"
                                    : "Verification Pending"}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}
