import WebsiteLayout from "@/Layouts/WebsiteLayout";
import { Link } from "@inertiajs/react";

export default function About() {
    const timeline = [
        {
            year: "2002",
            title: "Industry Association Engagement",
            description:
                "Beginning active involvement within Indonesia's textile industry ecosystem.",
        },

        {
            year: "2005 - 2010",
            title: "Government & International Media",
            description:
                "Supporting industry development through government initiatives and contributions to international textile publications.",
        },

        {
            year: "2011",
            title: "DigTex Industry Magazine",
            description:
                "Launching industry publications dedicated to Indonesia's textile and apparel sector.",
        },

        {
            year: "2011 - 2017",
            title: "Industry Directory & Publications",
            description:
                "Publishing company directories, market information, and industry communications.",
        },

        {
            year: "2020",
            title: "Digital News Platform",
            description:
                "Expanding into digital media and online industry news.",
        },

        {
            year: "2026",
            title: "Industry Ecosystem Platform",
            description:
                "Building a platform connecting industry stakeholders through information, intelligence, solutions, and collaboration.",
        },
    ];

    const pillars = [
        "Manufacturers",
        "Raw Materials",
        "Technology Solutions",
        "Industrial Machinery",
        "Testing & Certification",
        "Logistics & Supply Chain",
        "Trade Finance",
        "Research & Education",
    ];

    const solutions = [
        {
            title: "Industry Directory",
            desc: "Verified company profiles and business discovery.",
        },
        {
            title: "Industry Solutions",
            desc: "Technology, certification, logistics, finance, and industrial services.",
        },
        {
            title: "Market Intelligence",
            desc: "Import-export data, trade intelligence, and market trends.",
        },
        {
            title: "News & Events",
            desc: "Industry news, exhibitions, regulations, and developments.",
        },
        {
            title: "Industry Exchange",
            desc: "Connecting sourcing, opportunities, capacity, and collaboration.",
        },
        {
            title: "Ecosystem Partners",
            desc: "Organizations supporting industry growth and innovation.",
        },
    ];

    return (
        <WebsiteLayout>
            {/* HERO */}

            {/* HERO */}

            <section className="py-32">
                <div className="max-w-6xl mx-auto px-6 text-center">
                    <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                        ABOUT DIGTEX
                    </span>

                    <h1
                        className="
            text-5xl
            md:text-7xl
            font-black
            text-white
            mt-6
            uppercase
            leading-tight
        "
                    >
                        Textile Industry
                        <br />
                        Ecosystem
                    </h1>

                    <p
                        className="
            mt-8
            text-white
            text-xl
            md:text-2xl
            font-bold
            max-w-4xl
            mx-auto
        "
                    >
                        Connecting Industry, Solutions, Markets, and
                        Opportunities.
                    </p>

                    <p
                        className="
            mt-6
            text-gray-400
            max-w-3xl
            mx-auto
            leading-relaxed
        "
                    >
                        Built upon decades of industry experience, publications,
                        market intelligence, and ecosystem development across
                        the textile value chain.
                    </p>
                </div>
            </section>
            <section className="py-24 border-t border-white/5">
                <div className="max-w-6xl mx-auto px-6">
                    <div className="text-center mb-16">
                        <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                            OUR JOURNEY
                        </span>

                        <h2 className="text-4xl md:text-5xl font-black text-white mt-4 uppercase">
                            More Than Two Decades Of Industry Experience
                        </h2>
                    </div>

                    <div className="space-y-8">
                        {timeline.map((item) => (
                            <div
                                key={item.year}
                                className="
                        rounded-[32px]
                        border border-white/10
                        bg-white/5
                        p-8
                    "
                            >
                                <div className="text-yellow-500 font-black text-xl">
                                    {item.year}
                                </div>

                                <h3 className="text-white text-2xl font-black mt-2">
                                    {item.title}
                                </h3>

                                <p className="text-gray-400 mt-4">
                                    {item.description}
                                </p>
                            </div>
                        ))}
                    </div>
                </div>
            </section>
            {/* OUR JOURNEY */}

            {/* BUILT FROM INDUSTRY EXPERIENCE */}

            <section className="py-24 border-t border-white/5">
                <div className="max-w-5xl mx-auto px-6 text-center">
                    <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                        BUILT FROM INDUSTRY EXPERIENCE
                    </span>

                    <h2 className="text-4xl md:text-5xl font-black text-white mt-4 uppercase">
                        More Than A Platform
                    </h2>

                    <p className="mt-8 text-gray-400 text-lg leading-relaxed">
                        DigTex was not created from a business plan.
                    </p>

                    <p className="mt-6 text-gray-400 text-lg leading-relaxed">
                        It was built from decades of industry experience,
                        relationships, publications, market intelligence, and
                        ecosystem development within Indonesia's textile
                        industry.
                    </p>

                    <p className="mt-6 text-gray-400 text-lg leading-relaxed">
                        Today, DigTex is evolving into a platform connecting
                        manufacturers, suppliers, technology providers,
                        certification organizations, logistics companies,
                        financial institutions, research centers, and industry
                        associations.
                    </p>
                </div>
            </section>

            {/* ECOSYSTEM VISION */}

            <section className="py-24 border-t border-white/5">
                <div className="max-w-7xl mx-auto px-6">
                    <div className="text-center mb-16">
                        <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                            ECOSYSTEM VISION
                        </span>

                        <h2 className="text-4xl font-black text-white mt-4 uppercase">
                            Connecting The Textile Value Chain
                        </h2>
                    </div>

                    <div className="grid md:grid-cols-4 gap-6">
                        {pillars.map((pillar) => (
                            <div
                                key={pillar}
                                className="rounded-[24px] border border-white/10 bg-white/5 p-6 text-center"
                            >
                                <h3 className="text-white font-black">
                                    {pillar}
                                </h3>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            <section className="py-24 border-t border-white/5">
                <div className="max-w-5xl mx-auto px-6 text-center">
                    <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                        OUR MISSION
                    </span>

                    <h2 className="text-4xl md:text-5xl font-black text-white mt-4 uppercase">
                        Connecting Industry, Solutions, Markets, and
                        Opportunities
                    </h2>

                    <p className="mt-8 text-gray-400 text-lg leading-relaxed">
                        DigTex exists to support collaboration, knowledge
                        sharing, business development, and sustainable growth
                        throughout the textile ecosystem.
                    </p>
                </div>
            </section>
            {/* DIGTEX TODAY */}

            <section className="py-24 border-t border-white/5">
                <div className="max-w-7xl mx-auto px-6">
                    <div className="text-center mb-16">
                        <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                            DIGTEX TODAY
                        </span>

                        <h2 className="text-4xl font-black text-white mt-4 uppercase">
                            Supporting The Industry Through Digital Solutions
                        </h2>
                    </div>

                    <div className="grid md:grid-cols-3 gap-6">
                        {solutions.map((item) => (
                            <div
                                key={item.title}
                                className="rounded-[32px] border border-white/10 bg-white/5 p-8"
                            >
                                <h3 className="text-white text-xl font-black">
                                    {item.title}
                                </h3>

                                <p className="text-gray-400 mt-4">
                                    {item.desc}
                                </p>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* CTA */}

            <section className="py-24 border-t border-white/5">
                <div className="max-w-4xl mx-auto px-6 text-center">
                    <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                        FOUNDING ECOSYSTEM PARTNER PROGRAM
                    </span>

                    <h2 className="text-4xl md:text-5xl font-black text-white mt-4 uppercase">
                        Join The Ecosystem
                    </h2>

                    <p className="mt-6 text-gray-400">
                        Help shape a connected textile ecosystem by supporting
                        industry collaboration, innovation, market intelligence,
                        and knowledge sharing.
                    </p>

                    <Link
                        href={route("ecosystem-partner.index")}
                        className="
                inline-flex
                items-center
                justify-center
                mt-10
                px-8
                py-4
                rounded-full
                bg-yellow-500
                text-black
                font-black
                uppercase
                text-xs
                tracking-widest
            "
                    >
                        Become A Founding Ecosystem Partner
                    </Link>
                </div>
            </section>
        </WebsiteLayout>
    );
}
