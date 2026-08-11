import WebsiteLayout from "@/Layouts/WebsiteLayout";
import { Link } from "@inertiajs/react";

export default function Show({ category, partners = [] }) {
    return (
        <WebsiteLayout>
            {/* =====================================================
                HERO
            ===================================================== */}

            <section className="py-14 text-center">
                <div className="mx-auto max-w-5xl px-6">
                    <div className="mb-6 text-5xl text-yellow-500">
                        <i className={`fas ${category.icon}`} />
                    </div>

                    <span
                        className="
                            text-xs
                            font-black
                            uppercase
                            tracking-[0.4em]
                            text-yellow-500
                        "
                    >
                        INDUSTRY SOLUTIONS
                    </span>

                    <h1
                        className="
                            mt-6
                            text-5xl
                            font-black
                            uppercase
                            text-white
                            md:text-7xl
                        "
                    >
                        {category.title}
                    </h1>

                    <p
                        className="
                            mx-auto
                            mt-8
                            max-w-3xl
                            text-gray-400
                        "
                    >
                        {category.description}
                    </p>

                    {partners.length === 0 && (
                        <div className="py-16 text-center">
                            <h3 className="text-2xl font-black text-white">
                                Be The First Ecosystem Partner
                            </h3>

                            <p className="mt-4 text-gray-400">
                                This category is currently open for ecosystem
                                partners.
                            </p>
                        </div>
                    )}
                </div>
            </section>

            {/* =====================================================
                ECOSYSTEM PARTNERS
            ===================================================== */}

            {partners.length > 0 && (
                <section className="border-t border-white/5 py-10">
                    <div className="mx-auto max-w-7xl px-6">
                        <div className="mb-10 text-center">
                            <span
                                className="
                                    text-xs
                                    font-black
                                    uppercase
                                    tracking-[0.4em]
                                    text-yellow-500
                                "
                            >
                                ECOSYSTEM PARTNERS
                            </span>

                            <h2
                                className="
                                    mt-4
                                    text-4xl
                                    font-black
                                    uppercase
                                    text-white
                                "
                            >
                                Strategic Solution Partners
                            </h2>

                            <p
                                className="
                                    mx-auto
                                    mt-4
                                    max-w-2xl
                                    text-sm
                                    leading-6
                                    text-gray-400
                                "
                            >
                                Discover verified solution providers
                                contributing technology, expertise and
                                innovation to the textile industry ecosystem.
                            </p>
                        </div>

                        <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                            {partners.map((partner) => (
                                <article
                                    key={partner.id}
                                    className="
                                        group
                                        relative
                                        overflow-hidden
                                        rounded-[32px]
                                        border
                                        border-white/10
                                        bg-white/5
                                        p-7
                                        backdrop-blur-xl
                                        transition-all
                                        duration-300
                                        hover:-translate-y-1
                                        hover:border-yellow-500/30
                                        hover:bg-white/[0.07]
                                    "
                                >
                                    {/* Featured Badge */}

                                    {partner.is_featured && (
                                        <div
                                            className="
                                                absolute
                                                right-5
                                                top-5
                                                rounded-full
                                                border
                                                border-yellow-400/30
                                                bg-yellow-400/10
                                                px-3
                                                py-1.5
                                                text-[10px]
                                                font-black
                                                uppercase
                                                tracking-wider
                                                text-yellow-400
                                            "
                                        >
                                            Featured
                                        </div>
                                    )}

                                    {/* Logo */}

                                    <div
                                        className="
                                            flex
                                            h-20
                                            w-20
                                            items-center
                                            justify-center
                                            overflow-hidden
                                            rounded-2xl
                                            border
                                            border-white/10
                                            bg-white
                                        "
                                    >
                                        {partner.logo_url ? (
                                            <img
                                                src={partner.logo_url}
                                                alt={partner.company_name}
                                                className="
                                                    max-h-full
                                                    max-w-full
                                                    object-contain
                                                    p-2
                                                "
                                            />
                                        ) : (
                                            <div
                                                className="
                                                    text-2xl
                                                    font-black
                                                    text-slate-800
                                                "
                                            >
                                                {partner.company_name
                                                    ?.charAt(0)
                                                    ?.toUpperCase()}
                                            </div>
                                        )}
                                    </div>

                                    {/* Company */}

                                    <div className="mt-6">
                                        <div
                                            className="
                                                flex
                                                items-start
                                                justify-between
                                                gap-3
                                            "
                                        >
                                            <div>
                                                <h3
                                                    className="
                                                        text-2xl
                                                        font-black
                                                        text-white
                                                    "
                                                >
                                                    {partner.company_name}
                                                </h3>

                                                <div
                                                    className="
                                                        mt-2
                                                        text-sm
                                                        font-semibold
                                                        text-yellow-500
                                                    "
                                                >
                                                    {partner.category_label}
                                                </div>
                                            </div>
                                        </div>

                                        {/* Partner Level */}

                                        <div
                                            className="
                                                mt-4
                                                inline-flex
                                                items-center
                                                gap-2
                                                rounded-full
                                                border
                                                border-yellow-500/20
                                                bg-yellow-500/10
                                                px-4
                                                py-2
                                                text-xs
                                                font-black
                                                uppercase
                                                text-yellow-400
                                            "
                                        >
                                            <i className="fas fa-star text-[10px]" />

                                            {partner.partner_level_label}
                                        </div>

                                        {/* Verified */}

                                        <div
                                            className="
                                                mt-3
                                                inline-flex
                                                items-center
                                                gap-2
                                                rounded-full
                                                border
                                                border-emerald-400/20
                                                bg-emerald-400/10
                                                px-3
                                                py-1.5
                                                text-[10px]
                                                font-black
                                                uppercase
                                                tracking-wider
                                                text-emerald-400
                                            "
                                        >
                                            <i className="fas fa-check-circle" />
                                            DIGESTEX VERIFIED
                                        </div>

                                        {/* Description */}

                                        <p
                                            className="
                                                mt-5
                                                min-h-[84px]
                                                text-sm
                                                leading-6
                                                text-gray-400
                                            "
                                        >
                                            {partner.short_description}
                                        </p>

                                        {/* Actions */}

                                        <div
                                            className="
                                                mt-6
                                                flex
                                                flex-col
                                                gap-3
                                            "
                                        >
                                            <Link
                                                href={route(
                                                    "industry-partners.show",
                                                    partner.slug,
                                                )}
                                                className="
                                                    inline-flex
                                                    items-center
                                                    justify-center
                                                    gap-2
                                                    rounded-xl
                                                    bg-yellow-500
                                                    px-5
                                                    py-3
                                                    text-xs
                                                    font-black
                                                    uppercase
                                                    tracking-wider
                                                    text-black
                                                    transition
                                                    hover:bg-yellow-400
                                                "
                                            >
                                                View Partner Profile
                                                <i className="fas fa-arrow-right" />
                                            </Link>

                                            {partner.website_url && (
                                                <a
                                                    href={partner.website_url}
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    className="
                                                        inline-flex
                                                        items-center
                                                        justify-center
                                                        gap-2
                                                        rounded-xl
                                                        border
                                                        border-white/10
                                                        bg-white/5
                                                        px-5
                                                        py-3
                                                        text-xs
                                                        font-black
                                                        uppercase
                                                        tracking-wider
                                                        text-gray-300
                                                        transition
                                                        hover:bg-white/10
                                                        hover:text-white
                                                    "
                                                >
                                                    Visit Website
                                                    <i className="fas fa-external-link-alt" />
                                                </a>
                                            )}
                                        </div>
                                    </div>
                                </article>
                            ))}
                        </div>
                    </div>
                </section>
            )}

            {/* =====================================================
                JOIN ECOSYSTEM
            ===================================================== */}

            <section className="border-t border-white/5 py-24">
                <div className="mx-auto max-w-5xl px-6 text-center">
                    <span
                        className="
                            text-xs
                            font-black
                            uppercase
                            tracking-[0.4em]
                            text-yellow-500
                        "
                    >
                        JOIN THE ECOSYSTEM
                    </span>

                    <h2
                        className="
                            mt-4
                            text-4xl
                            font-black
                            uppercase
                            text-white
                            md:text-5xl
                        "
                    >
                        {category.cta_title}
                    </h2>

                    <p
                        className="
                            mx-auto
                            mt-6
                            max-w-3xl
                            text-gray-400
                        "
                    >
                        Showcase your expertise, services, technologies, and
                        innovations to Indonesia's textile industry ecosystem.
                    </p>

                    <Link
                        href={route("ecosystem-partner.index")}
                        className="
                            relative
                            z-[9999]
                            mt-8
                            inline-flex
                            items-center
                            justify-center
                            rounded-full
                            bg-yellow-500
                            px-8
                            py-4
                            text-xs
                            font-black
                            uppercase
                            tracking-widest
                            text-black
                            transition
                            hover:bg-yellow-400
                        "
                    >
                        Join The Ecosystem
                    </Link>
                </div>
            </section>
        </WebsiteLayout>
    );
}
