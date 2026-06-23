export default function CompanyLeadership({ company, companyAge }) {
    if (!company?.pimpinan && !company?.pimpinan_2) {
        return null;
    }
    const leaders = [
        {
            name: company.pimpinan,
            photo: company.photo_pimpinan,
            position: company.pimpinan_position || "President Director",
            color: "yellow",
        },
        {
            name: company.pimpinan_2,
            photo: company.photo_pimpinan_2,
            position: company.pimpinan_2_position || "Operations Director",
            color: "blue",
        },
    ].filter((leader) => leader.name);

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
                right-0
                h-72
                w-72
                rounded-full
                bg-purple-500/10
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
                        text-purple-400
                        mb-3
                    "
                    >
                        Executive Leadership
                    </div>

                    <h2 className="text-3xl font-black text-white">
                        Leadership Team
                    </h2>

                    <p className="text-gray-400 mt-3 max-w-3xl">
                        Management team driving business growth, manufacturing
                        excellence, and global market development.
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
                            Executives
                        </div>

                        <div className="text-4xl font-black text-purple-400">
                            {leaders.length}
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
                            Company Age
                        </div>

                        <div className="text-4xl font-black text-white">
                            {companyAge || "-"}
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
                            Global Markets
                        </div>

                        <div className="text-4xl font-black text-cyan-400">
                            {company.markets?.length || 0}
                        </div>
                    </div>
                </div>

                {/* LEADERS */}
                <div className="grid md:grid-cols-2 gap-6">
                    {leaders.map((leader, index) => (
                        <div
                            key={index}
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
                            <div className="flex items-center gap-6">
                                <div
                                    className="
                                    w-28
                                    h-28
                                    rounded-3xl
                                    overflow-hidden
                                    border
                                    border-white/10
                                    flex-shrink-0
                                "
                                >
                                    <img
                                        src={
                                            leader.photo ||
                                            "/images/avatar-placeholder.jpg"
                                        }
                                        alt={leader.name}
                                        className="
                                        w-full
                                        h-full
                                        object-cover
                                    "
                                    />
                                </div>

                                <div>
                                    <div
                                        className="
                                        text-[10px]
                                        uppercase
                                        tracking-[0.3em]
                                        text-purple-400
                                        font-black
                                        mb-3
                                    "
                                    >
                                        {leader.position}
                                    </div>

                                    <h3
                                        className="
                                        text-2xl
                                        font-black
                                        text-white
                                    "
                                    >
                                        {leader.name}
                                    </h3>

                                    <div
                                        className="
                                        mt-4
                                        inline-flex
                                        px-3
                                        py-2
                                        rounded-full
                                        bg-emerald-500/10
                                        text-emerald-400
                                        text-[10px]
                                        uppercase
                                        tracking-widest
                                        font-black
                                    "
                                    >
                                        Verified Company Representative
                                    </div>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </section>
    );
}
