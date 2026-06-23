import { Link } from "@inertiajs/react";

export default function CompanyHero({
    company,
    auth,
    isEn,
    companyRoleLabel,
    trustLevel,
    trustScore,
    companyAge,
}) {
    const featuredImage =
        company.images?.find((img) => img.is_featured)?.image_url ||
        company.images?.[0]?.image_url ||
        "/images/company-placeholder.jpg";

    return (
        <section
            className="
                relative
                overflow-hidden
                rounded-[32px]
                border
                border-white/10
                bg-gradient-to-br
                from-[#0a192f]
                via-[#112240]
                to-[#0f2747]
                p-8
                md:p-12
                shadow-2xl
                mb-10
            "
        >
            {/* BACKGROUND EFFECT */}
            <div className="absolute inset-0 opacity-10">
                <div className="absolute top-0 right-0 w-96 h-96 bg-blue-400 rounded-full blur-3xl" />
                <div className="absolute bottom-0 left-0 w-80 h-80 bg-cyan-400 rounded-full blur-3xl" />
            </div>

            <div className="relative z-10">
                {/* TOP BAR */}
                <div className="flex flex-wrap justify-between items-start gap-4 mb-8">
                    <div className="flex flex-wrap items-center gap-3">
                        {company.membership_type === "gold_member" && (
                            <div
                                className="
                                    flex items-center gap-2
                                    px-5 py-2
                                    rounded-full
                                    bg-yellow-500
                                    text-[#0a192f]
                                    text-[10px]
                                    font-black
                                    uppercase
                                    tracking-widest
                                "
                            >
                                <i className="fas fa-crown"></i>
                                Gold Member
                            </div>
                        )}

                        {companyRoleLabel && (
                            <div
                                className="
                                    px-4 py-2
                                    rounded-full
                                    bg-white/10
                                    border border-white/10
                                    text-[10px]
                                    font-black
                                    uppercase
                                    tracking-widest
                                    text-white
                                "
                            >
                                {companyRoleLabel}
                            </div>
                        )}

                        {company.status_verifikasi === "verified" && (
                            <div
                                className="
                                    px-4 py-2
                                    rounded-full
                                    bg-emerald-500/20
                                    border border-emerald-400/30
                                    text-emerald-300
                                    text-[10px]
                                    font-black
                                    uppercase
                                    tracking-[0.25em]
                                "
                            >
                                Verified Supplier
                            </div>
                        )}
                    </div>

                    {auth?.user &&
                        (auth.user.role === "admin" ||
                            auth.user.company_id === company.id) && (
                            <Link
                                href={route("companies.edit", company.id)}
                                className="
                                    flex items-center gap-2
                                    px-6 py-3
                                    rounded-2xl
                                    bg-white/10
                                    hover:bg-white/20
                                    border border-white/10
                                    text-white
                                    text-[10px]
                                    font-black
                                    uppercase
                                    tracking-widest
                                    transition-all
                                "
                            >
                                <i className="fas fa-pen"></i>
                                {isEn ? "Update Profile" : "Update Profil"}
                            </Link>
                        )}
                </div>

                {/* MAIN CONTENT */}
                <div className="grid lg:grid-cols-3 gap-10 items-center">
                    {/* LEFT SIDE */}
                    <div className="lg:col-span-2">
                        {/* COMPANY NAME */}
                        <h1
                            className="
                                text-4xl
                                md:text-6xl
                                xl:text-7xl
                                font-black
                                uppercase
                                italic
                                tracking-tight
                                leading-none
                                text-white
                                mb-5
                            "
                        >
                            {company.nama_perusahaan}
                        </h1>

                        {/* COMPANY DESCRIPTION */}
                        <p
                            className="
                                text-white/80
                                text-base
                                md:text-lg
                                leading-relaxed
                                max-w-3xl
                            "
                        >
                            {company.produk ||
                                "Part of the global textile industry ecosystem connecting sourcing, manufacturing, innovation, and market intelligence."}
                        </p>

                        {/* TRUST SCORE */}
                        <div
                            className="
                                mt-8
                                inline-flex
                                items-center
                                gap-6
                                rounded-2xl
                                bg-white/5
                                border border-white/10
                                px-6 py-4
                            "
                        >
                            <div>
                                <div className="text-xs uppercase tracking-widest text-gray-400">
                                    Trust Score
                                </div>

                                <div className="text-4xl font-black text-white">
                                    {trustScore?.score || 0}
                                </div>
                            </div>

                            <div>
                                <span
                                    className={`
                                        px-4 py-2
                                        rounded-full
                                        text-[10px]
                                        font-black
                                        uppercase
                                        tracking-widest
                                        ${trustLevel?.color || ""}
                                    `}
                                >
                                    {trustLevel?.label || "Standard Supplier"}
                                </span>
                            </div>
                        </div>

                        {/* COMPANY FACTS */}
                        <div
                            className="
                                grid
                                grid-cols-2
                                md:grid-cols-4
                                gap-6
                                mt-10
                            "
                        >
                            <div>
                                <div className="text-xs uppercase text-gray-400 mb-1">
                                    Established
                                </div>

                                <div className="font-bold text-white">
                                    {companyAge || "-"}
                                </div>
                            </div>

                            <div>
                                <div className="text-xs uppercase text-gray-400 mb-1">
                                    Employees
                                </div>

                                <div className="font-bold text-white">
                                    {company.tenaga_kerja || "-"}
                                </div>
                            </div>

                            <div>
                                <div className="text-xs uppercase text-gray-400 mb-1">
                                    Location
                                </div>

                                <div className="font-bold text-white">
                                    {company.city || "-"}
                                </div>
                            </div>

                            <div>
                                <div className="text-xs uppercase text-gray-400 mb-1">
                                    Export Markets
                                </div>

                                <div className="font-bold text-white">
                                    {company.pasar_ekspor || "-"}
                                </div>
                            </div>
                        </div>

                        {/* ACTION BUTTONS */}
                        <div className="flex flex-wrap gap-4 mt-10">
                            <button
                                className="
                                    px-7 py-3
                                    rounded-xl
                                    bg-yellow-500
                                    text-[#0a192f]
                                    font-black
                                    uppercase
                                    text-xs
                                    tracking-widest
                                "
                            >
                                Send RFQ
                            </button>

                            <button
                                className="
                                    px-7 py-3
                                    rounded-xl
                                    border
                                    border-white/20
                                    bg-white/5
                                    hover:bg-white/10
                                    text-white
                                    font-black
                                    uppercase
                                    text-xs
                                    tracking-widest
                                    transition-all
                                "
                            >
                                Contact Supplier
                            </button>
                        </div>
                    </div>

                    {/* RIGHT SIDE IMAGE */}
                    <div className="hidden lg:block">
                        <img
                            src={featuredImage}
                            alt={company.nama_perusahaan}
                            className="
                                w-full
                                h-[360px]
                                object-cover
                                rounded-3xl
                                border
                                border-white/10
                                shadow-2xl
                            "
                        />
                    </div>
                </div>
            </div>
        </section>
    );
}
