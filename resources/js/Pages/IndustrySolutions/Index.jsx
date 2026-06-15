import WebsiteLayout from "@/Layouts/WebsiteLayout";
import { Link } from "@inertiajs/react";
export default function Index({ partners }) {
    const categories = [
        {
            title: "Testing & Certification",
            description:
                "Quality assurance, laboratory testing, certification, and compliance solutions.",
            icon: "fa-shield-check",
        },
        {
            title: "Industrial Machinery",
            description:
                "Knitting, weaving, dyeing, finishing, and textile manufacturing technologies.",
            icon: "fa-industry",
        },
        {
            title: "Technology Solutions",
            description:
                "ERP, PLM, AI, Industry 4.0, and digital transformation solutions.",
            icon: "fa-microchip",
        },
        {
            title: "Raw Materials",
            description:
                "Fiber, yarn, fabrics, chemicals, and textile materials.",
            icon: "fa-boxes-stacked",
        },
        {
            title: "Logistics & Supply Chain",
            description:
                "Domestic and international logistics, warehousing, and trade support.",
            icon: "fa-truck",
        },
        {
            title: "Trade Finance",
            description:
                "Financing solutions supporting industrial growth and export activities.",
            icon: "fa-building-columns",
        },
        {
            title: "Exhibitions & Events",
            description:
                "Trade fairs, business matching, networking, and industry events.",
            icon: "fa-calendar-days",
        },
        {
            title: "Research & Education",
            description:
                "Universities, research institutions, training centers, and workforce development.",
            icon: "fa-graduation-cap",
        },
    ];
    return (
        <WebsiteLayout>
            {/* HERO */}

            <section className="py-20 text-center">
                <div className="max-w-5xl mx-auto px-6">
                    <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                        INDUSTRY SOLUTIONS
                    </span>

                    <h1 className="text-5xl md:text-7xl font-black text-white mt-6 uppercase">
                        Solutions Supporting The Textile Ecosystem
                    </h1>

                    <p className="mt-8 text-gray-400 max-w-3xl mx-auto">
                        Connecting technology, certification, machinery,
                        logistics, finance, research, and innovation across the
                        textile value chain.
                    </p>
                </div>
            </section>
            <section className="py-15">
                <div className="text-center mb-14">
                    <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                        SOLUTION CATEGORIES
                    </span>

                    <h2 className="text-4xl font-black text-white mt-4 uppercase">
                        Explore Industry Solutions
                    </h2>

                    <p className="max-w-3xl mx-auto mt-6 text-gray-400">
                        Discover solution providers supporting every stage of
                        the textile value chain.
                    </p>
                </div>
                <div className="max-w-7xl mx-auto px-6">
                    <div className="grid md:grid-cols-4 gap-6">
                        {categories.map((category) => (
                            <div
                                key={category.title}
                                className="
                        rounded-[32px]
                        border border-white/10
                        bg-white/5
                        backdrop-blur-xl
                        p-8
                    "
                            >
                                <div className="text-yellow-500 text-4xl mb-5">
                                    <i className={`fas ${category.icon}`} />
                                </div>

                                <h3 className="text-xl font-black text-white mb-4">
                                    {category.title}
                                </h3>

                                <p className="text-gray-400 text-sm leading-relaxed">
                                    {category.description}
                                </p>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            <section className="py-14 border-t border-white/5">
                <div className="max-w-7xl mx-auto px-6">
                    <div className="text-center mb-14">
                        <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                            ECOSYSTEM PARTNERS
                        </span>

                        <h2 className="text-4xl font-black text-white mt-4 uppercase">
                            Featured Solution Providers
                        </h2>
                    </div>

                    <div className="grid md:grid-cols-3 gap-6">
                        {partners.map((partner) => (
                            <div
                                key={partner.id}
                                className="
                rounded-[32px]
                border border-white/10
                bg-white/5
                backdrop-blur-xl
                p-8
            "
                            >
                                <h3 className="text-2xl font-black text-white">
                                    {partner.company_name}
                                </h3>

                                <div className="text-yellow-500 mt-3 font-semibold">
                                    {partner.category_label}
                                </div>

                                <p className="mt-5 text-gray-400">
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
            <section className="py-4 border-t border-white/5">
                <div className="max-w-5xl mx-auto px-6 text-center">
                    <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                        JOIN THE ECOSYSTEM
                    </span>

                    <h2 className="text-4xl md:text-5xl font-black text-white mt-4 uppercase">
                        Become An Ecosystem Partner
                    </h2>

                    <p className="max-w-3xl mx-auto mt-6 text-gray-400">
                        Showcase your technology, services, expertise, and
                        innovations to Indonesia's textile industry ecosystem.
                    </p>

                    <Link
                        href={route("ecosystem-partner.index")}
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
