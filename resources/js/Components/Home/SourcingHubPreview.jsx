import { Link } from "@inertiajs/react";

export default function SourcingHubPreview({ isEn }) {
    const features = [
        {
            title: "RFQ Marketplace",

            description: isEn
                ? "Submit sourcing requests and receive quotations from verified suppliers."
                : "Ajukan kebutuhan sourcing dan terima penawaran dari supplier terverifikasi.",

            icon: "fa-file-signature",
        },

        {
            title: "MOQ Matching",

            description: isEn
                ? "Combine demand from multiple buyers to meet factory minimum order quantities."
                : "Gabungkan kebutuhan pembelian untuk memenuhi minimum order quantity pabrik.",

            icon: "fa-people-group",
        },

        {
            title: "Collective Sourcing",

            description: isEn
                ? "Join sourcing groups and unlock better pricing through collective purchasing power."
                : "Bergabung dalam grup sourcing dan dapatkan harga yang lebih kompetitif melalui pembelian kolektif.",

            icon: "fa-handshake",
        },
    ];

    return (
        <section className="relative py-28 overflow-hidden">
            {/* Background Glow */}

            <div className="absolute top-20 left-10 h-72 w-72 bg-yellow-500/10 blur-[140px] rounded-full" />

            <div className="absolute bottom-10 right-10 h-72 w-72 bg-blue-500/10 blur-[140px] rounded-full" />

            <div className="relative z-10 max-w-7xl mx-auto px-6">
                {/* Heading */}

                <div className="text-center mb-16">
                    <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                        SOURCING HUB
                    </span>

                    <h2 className="text-4xl md:text-6xl font-black text-white mt-4 uppercase">
                        {isEn
                            ? "Smarter Textile Sourcing"
                            : "Sourcing Tekstil Cerdas"}
                    </h2>

                    <p className="max-w-3xl mx-auto mt-6 text-gray-400 leading-relaxed">
                        {isEn
                            ? "Connect buyers and suppliers through collaborative sourcing tools and procurement intelligence."
                            : "Menghubungkan buyer dan supplier melalui tools sourcing kolaboratif dan intelijen pengadaan."}
                    </p>
                </div>

                {/* Features */}

                <div className="grid lg:grid-cols-3 gap-8">
                    {features.map((feature) => (
                        <div
                            key={feature.title}
                            className="
                                    relative
                                    rounded-[32px]
                                    border border-white/10
                                    bg-white/5
                                    backdrop-blur-xl
                                    p-10
                                    hover:border-yellow-500/30
                                    transition-all
                                    duration-500
                                    hover:-translate-y-2
                                "
                        >
                            <i
                                className={`
                                        fas ${feature.icon}
                                        text-yellow-500
                                        text-4xl
                                        mb-6
                                    `}
                            />

                            <h3 className="text-2xl font-black text-white mb-4">
                                {feature.title}
                            </h3>

                            <p className="text-gray-400 leading-relaxed mb-6">
                                {feature.description}
                            </p>

                            <span
                                className="
                                    inline-flex
                                    items-center
                                    px-4
                                    py-2
                                    rounded-full
                                    bg-yellow-500/10
                                    text-yellow-400
                                    text-xs
                                    font-black
                                    uppercase
                                    tracking-wider
                                "
                            >
                                IN DEVELOPMENT
                            </span>
                        </div>
                    ))}
                </div>

                {/* CTA */}

                <div className="text-center mt-16">
                    <Link
                        href={route("sourcing-hub")}
                        className="
                            inline-flex
                            items-center
                            px-8
                            py-4
                            rounded-full
                            bg-yellow-500
                            text-black
                            font-black
                            uppercase
                            tracking-widest
                            text-xs
                        "
                    >
                        {isEn ? "View Roadmap" : "Jelajahi Sourcing Hub"}
                    </Link>
                </div>
            </div>
        </section>
    );
}
