export default function CompanyIdentity({ company }) {
    return (
        <div
            className="
             bg-white/5
             border
             border-white/10
             rounded-[40px]
             p-10
             mb-8
         "
        >
            {" "}
            <h2
                className="
                 text-yellow-500
                 text-xs
                 font-black
                 uppercase
                 tracking-[0.4em]
                 mb-8
             "
            >
                Company Identity{" "}
            </h2>
            ```
            <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <div
                        className="
                        text-[10px]
                        uppercase
                        tracking-widest
                        text-gray-500
                        mb-2
                    "
                    >
                        Headquarters
                    </div>

                    <div className="text-lg font-bold text-white">
                        {company.country_name || "-"}
                    </div>
                </div>

                <div>
                    <div
                        className="
                        text-[10px]
                        uppercase
                        tracking-widest
                        text-gray-500
                        mb-2
                    "
                    >
                        City
                    </div>

                    <div className="text-lg font-bold text-white">
                        {company.city || "-"}
                    </div>
                </div>

                <div>
                    <div
                        className="
                        text-[10px]
                        uppercase
                        tracking-widest
                        text-gray-500
                        mb-2
                    "
                    >
                        Established
                    </div>

                    <div className="text-lg font-bold text-white">
                        {company.tahun_berdiri || "-"}
                    </div>
                </div>

                <div>
                    <div
                        className="
                        text-[10px]
                        uppercase
                        tracking-widest
                        text-gray-500
                        mb-2
                    "
                    >
                        Workforce
                    </div>

                    <div className="text-lg font-bold text-white">
                        {company.tenaga_kerja || "-"}
                    </div>
                </div>
            </div>
            {(company.company_role || company.company_type) && (
                <div
                    className="
                    mt-8
                    pt-8
                    border-t
                    border-white/10
                "
                >
                    <div className="flex flex-wrap gap-3">
                        {company.company_role && (
                            <span
                                className="
                                px-4
                                py-2
                                rounded-full
                                bg-blue-500/10
                                text-blue-400
                                text-xs
                                uppercase
                                font-bold
                            "
                            >
                                {company.company_role}
                            </span>
                        )}

                        {company.company_type && (
                            <span
                                className="
                                px-4
                                py-2
                                rounded-full
                                bg-emerald-500/10
                                text-emerald-400
                                text-xs
                                uppercase
                                font-bold
                            "
                            >
                                {company.company_type}
                            </span>
                        )}
                    </div>
                </div>
            )}
            {company.claimed_by_user_id && (
                <div
                    className="
                    mt-8
                    inline-flex
                    items-center
                    gap-2
                    px-4
                    py-2
                    rounded-full
                    bg-green-500/10
                    text-green-400
                    text-xs
                    font-bold
                "
                >
                    ✓ Verified Company Owner
                </div>
            )}
        </div>
    );
}
