import { Link } from "@inertiajs/react";

export default function FeaturedPartnerBanner({ partner, isEn = false }) {
    return (
        <section className="py-12">
            <div className="max-w-7xl mx-auto px-6">
                <div
                    className="
                        relative
                        overflow-hidden
                        rounded-[36px]
                        border border-yellow-500/20
                        bg-gradient-to-r
                        from-yellow-500/10
                        via-[#07111f]
                        to-blue-500/10
                        p-10 md:p-14
                        backdrop-blur-xl
                    "
                >
                    {/* Glow */}
                    <div className="absolute -top-10 -right-10 w-40 h-40 bg-yellow-500/10 blur-[80px] rounded-full" />
                    <div className="absolute -bottom-10 -left-10 w-40 h-40 bg-blue-500/10 blur-[80px] rounded-full" />

                    <div className="relative z-10">
                        <span
                            className="
        text-yellow-500
        text-xs
        font-black
        uppercase
        tracking-[0.4em]
    "
                        >
                            {isEn
                                ? "FEATURED ECOSYSTEM PARTNER"
                                : "MITRA EKOSISTEM UNGGULAN"}
                        </span>
                        {partner ? (
                            <>
                                <h2 className="text-3xl md:text-5xl font-black text-white mt-4">
                                    {partner.company_name}
                                </h2>

                                <p className="max-w-3xl mt-6 text-gray-300 leading-relaxed">
                                    {partner.short_description}
                                </p>

                                <div className="flex flex-wrap gap-4 mt-8">
                                    <a
                                        href={partner.website_url}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="
                                            px-8
                                            py-4
                                            bg-yellow-500
                                            text-black
                                            rounded-full
                                            font-black
                                            uppercase
                                            text-xs
                                            tracking-widest
                                            hover:bg-yellow-400
                                            transition
                                        "
                                    >
                                        Learn More
                                    </a>

                                    <span
                                        className="
                                            px-6
                                            py-4
                                            rounded-full
                                            border border-white/10
                                            text-gray-300
                                            text-xs
                                            uppercase
                                            tracking-widest
                                        "
                                    >
                                        {partner.partner_level}
                                    </span>
                                </div>
                            </>
                        ) : (
                            <>
                                <h2 className="text-3xl md:text-5xl font-black text-white mt-4">
                                    {isEn
                                        ? "Your Company Here"
                                        : "Perusahaan Anda di Sini"}
                                </h2>

                                <p className="max-w-3xl mt-6 text-gray-300 leading-relaxed">
                                    Showcase your products, services, solutions,
                                    and innovations to Indonesia's textile
                                    industry ecosystem through DigesTex.
                                </p>

                                <div className="mt-8">
                                    <Link
                                        href={route("join.us")}
                                        className="
                                            inline-flex
                                            items-center
                                            px-8
                                            py-4
                                            bg-yellow-500
                                            text-black
                                            rounded-full
                                            font-black
                                            uppercase
                                            text-xs
                                            tracking-widest
                                            hover:bg-yellow-400
                                            transition
                                        "
                                    >
                                        {isEn
                                            ? "Join the Ecosystem"
                                            : "Gabung ke Ekosistem"}
                                    </Link>
                                </div>
                            </>
                        )}
                    </div>
                </div>
            </div>
        </section>
    );
}
