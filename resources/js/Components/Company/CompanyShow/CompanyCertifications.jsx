export default function CompanyCertifications({ company }) {
    if (!company?.certifications?.length) {
        return null;
    }

    const verifiedCount = company.certifications.filter(
        (cert) => cert.is_verified,
    ).length;

    const formatDate = (date) => {
        if (!date) return "-";

        return new Date(date).toLocaleDateString("en-US", {
            year: "numeric",
            month: "short",
            day: "numeric",
        });
    };

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
                bg-emerald-500/10
                blur-3xl
            "
            />

            <div className="relative z-10">
                {/* HEADER */}
                <div className="flex flex-wrap items-center justify-between gap-4 mb-10">
                    <div>
                        <div
                            className="
                            text-xs
                            font-black
                            uppercase
                            tracking-[0.35em]
                            text-emerald-400
                            mb-3
                        "
                        >
                            Certifications & Compliance
                        </div>

                        <h2 className="text-3xl font-black text-white">
                            International Standards
                        </h2>

                        <p className="text-gray-400 mt-3">
                            Quality, sustainability, compliance, and
                            internationally recognized certifications.
                        </p>
                    </div>

                    {verifiedCount > 0 && (
                        <div
                            className="
                            inline-flex
                            items-center
                            gap-3
                            px-5
                            py-3
                            rounded-2xl
                            border
                            border-emerald-500/20
                            bg-emerald-500/10
                        "
                        >
                            <i className="fas fa-shield-check text-emerald-400" />

                            <span
                                className="
                                text-xs
                                uppercase
                                tracking-widest
                                font-black
                                text-emerald-300
                            "
                            >
                                {verifiedCount} Verified Certifications
                            </span>
                        </div>
                    )}
                </div>

                {/* CERTIFICATION GRID */}
                <div className="grid md:grid-cols-2 xl:grid-cols-3 gap-6">
                    {company.certifications.map((cert) => {
                        const certName =
                            cert.certification_name?.toUpperCase() || "";

                        const isExpired =
                            cert.valid_until &&
                            new Date(cert.valid_until) < new Date();

                        let badgeColor = "from-slate-700/40 to-slate-900/40";

                        let icon = "fas fa-award";

                        if (certName.includes("OEKO")) {
                            badgeColor = "from-green-500/20 to-emerald-900/20";
                            icon = "fas fa-leaf";
                        }

                        if (certName.includes("GRS")) {
                            badgeColor = "from-cyan-500/20 to-blue-900/20";
                            icon = "fas fa-recycle";
                        }

                        if (certName.includes("ISO")) {
                            badgeColor = "from-yellow-500/20 to-orange-900/20";
                            icon = "fas fa-globe";
                        }

                        if (certName.includes("HIGG")) {
                            badgeColor = "from-purple-500/20 to-fuchsia-900/20";
                            icon = "fas fa-chart-line";
                        }

                        if (certName.includes("BSCI")) {
                            badgeColor = "from-pink-500/20 to-rose-900/20";
                            icon = "fas fa-users";
                        }

                        return (
                            <div
                                key={cert.id}
                                className={`
                                relative
                                overflow-hidden
                                rounded-[32px]
                                border
                                border-white/10
                                bg-gradient-to-br
                                ${badgeColor}
                                p-7
                                transition-all
                                duration-300
                                hover:scale-[1.02]
                            `}
                            >
                                {/* TOP */}
                                <div className="flex justify-between items-start mb-6">
                                    <div
                                        className="
                                        h-16
                                        w-16
                                        rounded-2xl
                                        bg-white/10
                                        border
                                        border-white/10
                                        flex
                                        items-center
                                        justify-center
                                    "
                                    >
                                        {cert.logo_url ? (
                                            <img
                                                src={`/storage/${cert.logo_url}`}
                                                alt={cert.certification_name}
                                                className="
                                                h-10
                                                object-contain
                                            "
                                            />
                                        ) : (
                                            <i
                                                className={`${icon} text-xl text-white`}
                                            />
                                        )}
                                    </div>

                                    <div className="flex flex-col items-end gap-2">
                                        {cert.category && (
                                            <span
                                                className="
                                                px-3
                                                py-1
                                                rounded-full
                                                bg-white/10
                                                text-[9px]
                                                uppercase
                                                tracking-widest
                                                text-gray-300
                                                font-bold
                                            "
                                            >
                                                {cert.category.replaceAll(
                                                    "_",
                                                    " ",
                                                )}
                                            </span>
                                        )}

                                        {cert.is_verified && (
                                            <span
                                                className="
                                                px-3
                                                py-1
                                                rounded-full
                                                bg-emerald-500/20
                                                text-[9px]
                                                uppercase
                                                tracking-widest
                                                text-emerald-300
                                                font-black
                                            "
                                            >
                                                Verified
                                            </span>
                                        )}
                                    </div>
                                </div>

                                {/* NAME */}
                                <h3
                                    className="
                                    text-xl
                                    font-black
                                    uppercase
                                    italic
                                    text-white
                                    mb-3
                                "
                                >
                                    {cert.certification_name}
                                </h3>

                                {/* ISSUER */}
                                {cert.issuer && (
                                    <p
                                        className="
                                        text-xs
                                        uppercase
                                        tracking-widest
                                        text-gray-400
                                        mb-4
                                    "
                                    >
                                        Issued by{" "}
                                        <span className="text-white">
                                            {cert.issuer}
                                        </span>
                                    </p>
                                )}

                                {/* DESCRIPTION */}
                                {cert.description && (
                                    <p
                                        className="
                                        text-sm
                                        text-gray-300
                                        leading-relaxed
                                        mb-5
                                        line-clamp-3
                                    "
                                    >
                                        {cert.description}
                                    </p>
                                )}

                                {/* CERTIFICATE NUMBER */}
                                {cert.certificate_number && (
                                    <div className="mb-5">
                                        <div
                                            className="
                                            text-[9px]
                                            uppercase
                                            tracking-[0.3em]
                                            text-gray-500
                                            font-black
                                            mb-2
                                        "
                                        >
                                            Certificate Number
                                        </div>

                                        <div
                                            className="
                                            rounded-2xl
                                            border
                                            border-white/10
                                            bg-black/20
                                            px-4
                                            py-3
                                            font-mono
                                            text-xs
                                            text-white
                                            break-all
                                        "
                                        >
                                            {cert.certificate_number}
                                        </div>
                                    </div>
                                )}

                                {/* FOOTER */}
                                <div
                                    className="
                                    flex
                                    items-center
                                    justify-between
                                    pt-5
                                    border-t
                                    border-white/10
                                "
                                >
                                    <div>
                                        <div
                                            className="
                                            text-[9px]
                                            uppercase
                                            tracking-[0.3em]
                                            text-gray-500
                                            font-black
                                            mb-1
                                        "
                                        >
                                            Valid Until
                                        </div>

                                        <div
                                            className="
                                            text-sm
                                            font-bold
                                            text-white
                                        "
                                        >
                                            {formatDate(cert.valid_until)}
                                        </div>
                                    </div>

                                    <div
                                        className={`
                                        px-3
                                        py-2
                                        rounded-xl
                                        text-[10px]
                                        font-black
                                        uppercase
                                        tracking-widest
                                        ${
                                            isExpired
                                                ? "bg-red-500/20 text-red-300"
                                                : "bg-emerald-500/20 text-emerald-300"
                                        }
                                    `}
                                    >
                                        {isExpired ? "Expired" : "Active"}
                                    </div>
                                </div>
                            </div>
                        );
                    })}
                </div>
            </div>
        </section>
    );
}
