import WebsiteLayout from "@/Layouts/WebsiteLayout";
import { Link } from "@inertiajs/react";

export default function Show({ category, partners }) {
    return (
        <WebsiteLayout>
            {/* HERO */}

            <section className="py-14 text-center">
                <div className="max-w-5xl mx-auto px-6">
                    <div className="text-yellow-500 text-5xl mb-6">
                        <i className={`fas ${category.icon}`} />
                    </div>

                    <span
                        className="
                        text-yellow-500
                        text-xs
                        font-black
                        uppercase
                        tracking-[0.4em]
                    "
                    >
                        INDUSTRY SOLUTIONS
                    </span>

                    <h1
                        className="
                        text-5xl
                        md:text-7xl
                        font-black
                        text-white
                        mt-6
                        uppercase
                    "
                    >
                        {category.title}
                    </h1>

                    <p
                        className="
                        mt-8
                        text-gray-400
                        max-w-3xl
                        mx-auto
                    "
                    >
                        {category.description}
                    </p>

                    {partners.length === 0 && (
                        <div className="text-center py-16">
                            <h3 className="text-2xl font-black text-white">
                                Be The First Ecosystem Partner
                            </h3>

                            <p className="text-gray-400 mt-4">
                                This category is currently open for ecosystem
                                partners.
                            </p>
                        </div>
                    )}
                </div>
            </section>
            <section className="py-10 border-t border-white/5">
                <div className="max-w-7xl mx-auto px-6">
                    <div className="text-center mb-10">
                        <span
                            className="
                text-yellow-500
                text-xs
                font-black
                uppercase
                tracking-[0.4em]
            "
                        >
                            ECOSYSTEM PARTNERS
                        </span>

                        <h2
                            className="
                text-4xl font-black text-white mt-4 uppercase"
                        >
                            Featured Providers
                        </h2>
                    </div>

                    <div className="grid md:grid-cols-3 gap-6">
                        {partners.map((partner) => (
                            <div
                                key={partner.id}
                                className="
                        group
                        rounded-[32px]
                        border border-white/10
                        bg-white/5
                        backdrop-blur-xl
                        p-8
                        hover:border-yellow-500/30
                        hover:-translate-y-1
                        transition-all
                    "
                            >
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
                        text-yellow-500
                        mt-3
                        font-semibold
                    "
                                >
                                    {partner.category_label}
                                </div>

                                <span
                                    className="
                        inline-flex
                        mt-4
                        px-4
                        py-2
                        rounded-full
                        bg-yellow-500/20
                        text-yellow-400
                        text-xs
                        font-black
                        uppercase
                    "
                                >
                                    {partner.partner_level_label}
                                </span>

                                <p
                                    className="
                        mt-5
                        text-gray-400
                    "
                                >
                                    {partner.short_description}
                                </p>

                                <a
                                    href={partner.website_url}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="
                            inline-flex
                            items-center
                            gap-2
                            mt-6
                            text-yellow-500
                            font-black
                            uppercase
                            text-xs
                        "
                                >
                                    Visit Website
                                    <i className="fas fa-arrow-right" />
                                </a>
                            </div>
                        ))}
                    </div>
                </div>
            </section>
            <section className="py-24 border-t border-white/5">
                <div className="max-w-5xl mx-auto px-6 text-center">
                    <span
                        className="
            text-yellow-500
            text-xs
            font-black
            uppercase
            tracking-[0.4em]
        "
                    >
                        JOIN THE ECOSYSTEM
                    </span>

                    <h2
                        className="
            text-4xl
            md:text-5xl
            font-black
            text-white
            mt-4
            uppercase
        "
                    >
                        {category.cta_title}
                    </h2>

                    <p
                        className="
            max-w-3xl
            mx-auto
            mt-6
            text-gray-400
        "
                    >
                        Showcase your expertise, services, technologies, and
                        innovations to Indonesia's textile industry ecosystem.
                    </p>

                    <Link
                        href={route("ecosystem-partner.index")}
                        onClick={() => console.log("clicked")}
                        className="
        relative
        z-[9999]
        inline-flex
        items-center
        justify-center
        mt-8
        bg-yellow-500
        text-black
        px-8
        py-4
        rounded-full
        font-black
        uppercase
        text-xs
        tracking-widest"
                    >
                        Join The Ecosystem
                    </Link>
                </div>
            </section>
        </WebsiteLayout>
    );
}
