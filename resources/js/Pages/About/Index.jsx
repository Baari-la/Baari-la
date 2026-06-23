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
            year: "2004 - 2011",
            title: "International Textile Media & Global Industry Network",
            description:
                "Contributing to international textile publications while building relationships across manufacturers, suppliers, buyers, and textile stakeholders worldwide.",
        },

        {
            year: "2006 - 2011",
            title: "Government & Industry Engagement",
            description:
                "Supporting industry development initiatives while gaining insights into industrial policy, trade, and regulatory frameworks.",
        },

        {
            year: "2011 - Present",
            title: "Industry Publications & Ecosystem Development",
            description:
                "Developing industry publications, directories, business networking initiatives, seminars, and market connectivity programs.",
        },

        {
            year: "2022 - 2025",
            title: "Textile Industry Directories",
            description:
                "Publishing industry directories connecting textile manufacturers, suppliers, and stakeholders across Indonesia.",
        },

        {
            year: "2026",
            title: "Global Textile Industry Ecosystem",
            description:
                "Transforming decades of experience, networks, and industry engagement into a digital ecosystem connecting visibility, intelligence, collaboration, and business opportunities.",
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
            title: "Directory & Verification",
            desc: "Verified company profiles that improve visibility and business discovery across local and global markets.",
            icon: "🏭",
        },
        {
            title: "RFQ & Business Matching",
            desc: "Connecting buyers and suppliers through sourcing opportunities and quotation requests.",
            icon: "🤝",
        },
        {
            title: "MOQ Matching Network",
            desc: "Helping companies collaborate and access materials that require larger minimum order quantities.",
            icon: "📦",
        },
        {
            title: "Market Intelligence",
            desc: "Trade analytics, market trends, pricing insights, and industry intelligence.",
            icon: "📊",
        },
        {
            title: "Industry Solutions Hub",
            desc: "Connecting technology providers, machinery suppliers, logistics companies, and industry service providers.",
            icon: "⚙️",
        },
        {
            title: "Ecosystem Partner Network",
            desc: "Building strategic collaboration between industry stakeholders and founding ecosystem partners.",
            icon: "🌎",
        },
    ];

    return (
        <WebsiteLayout>
            {/* HERO */}

            {/* HERO */}

            <section className="py-24">
                <div className="max-w-6xl mx-auto px-6 text-center">
                    <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                        ABOUT DIGESTEX
                    </span>

                    <h1
                        className="
                text-5xl
                md:text-7xl
                font-black
                text-white
                mt-6
                leading-[0.95]
                uppercase
            "
                    >
                        Global Textile
                        <br />
                        Industry Ecosystem
                    </h1>

                    <p
                        className="
                mt-8
                text-white
                text-xl
                md:text-2xl
                font-bold
                max-w-5xl
                mx-auto
                leading-relaxed
            "
                    >
                        Built From Industry Experience. Powered By Industry
                        Needs.
                    </p>

                    <p
                        className="
                mt-8
                text-slate-400
                text-lg
                max-w-4xl
                mx-auto
                leading-relaxed
            "
                    >
                        Digestex is built upon more than two decades of
                        engagement with global business networks, industry
                        associations, government and policy stakeholders,
                        manufacturers, suppliers, technology providers, and
                        international buyers across the textile value chain.
                    </p>
                    <p
                        className="
                mt-6
                text-slate-400
                text-lg
                max-w-4xl
                mx-auto
                leading-relaxed
            "
                    >
                        This experience has been developed through industry
                        organizations, business matching initiatives, industry
                        directories, international collaboration programs, and
                        contributions to international textile industry
                        publications.
                    </p>

                    <p
                        className="
                mt-6
                text-slate-400
                text-lg
                max-w-4xl
                mx-auto
                leading-relaxed
            "
                    >
                        Digestex evolved from real industry needs and continues
                        to grow through practical solutions that strengthen
                        visibility, connectivity, intelligence, collaboration,
                        and business opportunities throughout the global textile
                        value chain.
                    </p>

                    <div className="flex flex-wrap justify-center gap-3 mt-12">
                        <span className="px-4 py-2 rounded-full bg-white/5 border border-white/10 text-xs text-slate-300">
                            20+ Years Industry Experience
                        </span>

                        <span className="px-4 py-2 rounded-full bg-white/5 border border-white/10 text-xs text-slate-300">
                            Global Industry Network
                        </span>

                        <span className="px-4 py-2 rounded-full bg-white/5 border border-white/10 text-xs text-slate-300">
                            Business Matching
                        </span>

                        <span className="px-4 py-2 rounded-full bg-white/5 border border-white/10 text-xs text-slate-300">
                            Market Intelligence
                        </span>
                    </div>
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

                    <div className="grid md:grid-cols-2 gap-6">
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
                        Transforming Experience Into Industry Value
                    </h2>

                    <p className="mt-8 text-gray-400 text-lg leading-relaxed">
                        Digestex brings together decades of industry experience,
                        business connectivity, market understanding, and
                        stakeholder engagement into a practical ecosystem
                        designed to support the textile industry.
                    </p>

                    <p className="mt-6 text-gray-400 text-lg leading-relaxed">
                        Over the years, interactions with manufacturers,
                        suppliers, technology providers, industry organizations,
                        policymakers, and international buyers have provided
                        valuable insights into the challenges and opportunities
                        facing the textile sector.
                    </p>

                    <p className="mt-6 text-gray-400 text-lg leading-relaxed">
                        Today, these insights are being translated into
                        practical solutions that enhance visibility, strengthen
                        connectivity, support collaboration, improve market
                        intelligence, and create new business opportunities
                        across the global textile value chain.
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
                <div className="max-w-7xl mx-auto px-6">
                    <div className="text-center max-w-4xl mx-auto mb-20">
                        <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                            WHY DIGESTEX EXISTS
                        </span>

                        <h2 className="text-4xl md:text-6xl font-black text-white mt-6 uppercase leading-tight">
                            Built Around
                            <br />
                            Real Industry Needs
                        </h2>

                        <p className="mt-8 text-gray-400 text-lg leading-relaxed">
                            Digestex was not built based on market assumptions.
                            It evolved from real needs expressed by
                            manufacturers, international buyers, technology
                            providers, and industry stakeholders seeking
                            stronger visibility, connectivity, intelligence, and
                            collaboration.
                        </p>
                    </div>

                    <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                        {/* Visibility */}
                        <div className="rounded-[32px] border border-white/10 bg-white/5 p-8 hover:bg-white/[0.07] transition-all">
                            <div className="text-4xl mb-5">🌍</div>

                            <h3 className="text-white text-xl font-black">
                                Visibility
                            </h3>

                            <p className="text-gray-400 mt-4 leading-relaxed">
                                Manufacturers need greater visibility to buyers,
                                sourcing offices, investors, and business
                                partners across local and international markets.
                            </p>
                        </div>

                        {/* Connectivity */}
                        <div className="rounded-[32px] border border-white/10 bg-white/5 p-8 hover:bg-white/[0.07] transition-all">
                            <div className="text-4xl mb-5">🤝</div>

                            <h3 className="text-white text-xl font-black">
                                Connectivity
                            </h3>

                            <p className="text-gray-400 mt-4 leading-relaxed">
                                International buyers, suppliers, and industry
                                stakeholders need efficient access to trusted
                                manufacturing partners and business
                                opportunities.
                            </p>
                        </div>

                        {/* Intelligence */}
                        <div className="rounded-[32px] border border-white/10 bg-white/5 p-8 hover:bg-white/[0.07] transition-all">
                            <div className="text-4xl mb-5">📊</div>

                            <h3 className="text-white text-xl font-black">
                                Intelligence
                            </h3>

                            <p className="text-gray-400 mt-4 leading-relaxed">
                                Industry participants require better access to
                                market intelligence, trade analytics,
                                regulations, and strategic insights for decision
                                making.
                            </p>
                        </div>

                        {/* Collaboration */}
                        <div className="rounded-[32px] border border-white/10 bg-white/5 p-8 hover:bg-white/[0.07] transition-all">
                            <div className="text-4xl mb-5">🚀</div>

                            <h3 className="text-white text-xl font-black">
                                Collaboration
                            </h3>

                            <p className="text-gray-400 mt-4 leading-relaxed">
                                Technology providers, solution partners,
                                associations, and industry organizations need a
                                platform that encourages collaboration and
                                ecosystem growth.
                            </p>
                        </div>
                    </div>

                    <div className="mt-16 max-w-5xl mx-auto text-center">
                        <p className="text-xl text-slate-300 leading-relaxed">
                            Digestex exists to connect these needs through one
                            integrated ecosystem that supports business
                            visibility, market intelligence, industry
                            collaboration, and global opportunities throughout
                            the textile value chain.
                        </p>
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
                        Digestex exists to support collaboration, knowledge
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
                            DIGESTEX TODAY
                        </span>

                        <h2 className="text-4xl font-black text-white mt-4 uppercase">
                            Building The Global Textile Industry Ecosystem
                        </h2>
                        <p className="max-w-4xl mx-auto text-lg text-slate-400 leading-relaxed mt-8">
                            Digestex is transforming decades of industry
                            experience, relationships, and business connectivity
                            into a digital ecosystem that supports visibility,
                            sourcing, collaboration, market intelligence, and
                            business growth throughout the global textile value
                            chain.
                        </p>
                    </div>

                    <div className="grid md:grid-cols-3 gap-6">
                        {solutions.map((item) => (
                            <div
                                key={item.title}
                                className="
group
rounded-[32px]
border
border-white/10
bg-gradient-to-b
from-white/5
to-white/[0.02]
p-8
hover:border-yellow-500/30
hover:bg-white/[0.07]
transition-all
duration-300
"
                            >
                                <div className="text-4xl mb-5">{item.icon}</div>
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

            <section className="py-32 border-t border-white/5">
                <div className="max-w-5xl mx-auto px-6 text-center">
                    <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                        FOUNDING ECOSYSTEM PARTNER PROGRAM
                    </span>
                    <h2 className="text-4xl md:text-6xl font-black text-white mt-6 uppercase leading-tight">
                        Become A Founding Partner
                        <br />
                        In Building The Future Of
                        <br />
                        Textile Industry Connectivity
                    </h2>
                    <p className="mt-8 text-slate-400 text-lg leading-relaxed max-w-4xl mx-auto">
                        Digestex is inviting selected organizations, technology
                        providers, manufacturers, suppliers, logistics
                        companies, industry service providers, and strategic
                        stakeholders to participate in the development of a
                        connected textile ecosystem that strengthens visibility,
                        collaboration, intelligence, and business opportunities
                        across the global textile value chain.
                    </p>
                    <p className="mt-10 text-slate-300 text-lg max-w-3xl mx-auto">
                        Join a growing network of industry leaders helping shape
                        the future of textile connectivity, intelligence, and
                        collaboration.
                    </p>
                    <div className="flex flex-wrap justify-center gap-3 mt-10">
                        <span className="px-4 py-2 rounded-full bg-white/5 border border-white/10 text-xs font-semibold text-slate-300">
                            🌍 Global Visibility
                        </span>

                        <span className="px-4 py-2 rounded-full bg-white/5 border border-white/10 text-xs font-semibold text-slate-300">
                            🤝 Strategic Collaboration
                        </span>

                        <span className="px-4 py-2 rounded-full bg-white/5 border border-white/10 text-xs font-semibold text-slate-300">
                            📊 Market Intelligence
                        </span>

                        <span className="px-4 py-2 rounded-full bg-white/5 border border-white/10 text-xs font-semibold text-slate-300">
                            🚀 Business Opportunities
                        </span>
                    </div>
                    <div className="mt-16 grid md:grid-cols-3 gap-6 text-left">
                        <div className="rounded-[28px] border border-white/10 bg-white/5 p-6">
                            <h3 className="text-white font-black text-lg">
                                Industry Visibility
                            </h3>

                            <p className="text-slate-400 mt-3 text-sm leading-relaxed">
                                Strengthen brand presence and connect with
                                manufacturers, buyers, suppliers, and
                                decision-makers throughout the textile industry.
                            </p>
                        </div>

                        <div className="rounded-[28px] border border-white/10 bg-white/5 p-6">
                            <h3 className="text-white font-black text-lg">
                                Ecosystem Participation
                            </h3>

                            <p className="text-slate-400 mt-3 text-sm leading-relaxed">
                                Participate in the development of industry
                                initiatives, business matching programs, market
                                intelligence, and future ecosystem solutions.
                            </p>
                        </div>

                        <div className="rounded-[28px] border border-white/10 bg-white/5 p-6">
                            <h3 className="text-white font-black text-lg">
                                Strategic Positioning
                            </h3>

                            <p className="text-slate-400 mt-3 text-sm leading-relaxed">
                                Be recognized as an early supporter of a growing
                                ecosystem designed to strengthen textile
                                industry connectivity on a national and global
                                scale.
                            </p>
                        </div>
                    </div>
                    <div className="mt-16">
                        <Link
                            href={route("ecosystem-partner.index")}
                            className="
                inline-flex
                items-center
                justify-center
                px-10
                py-5
                rounded-full
                bg-yellow-500
                hover:bg-yellow-400
                text-black
                font-black
                uppercase
                text-xs
                tracking-[0.25em]
                transition-all
                duration-300
                shadow-xl
            "
                        >
                            Explore Founding Partner Opportunities
                        </Link>

                        <p className="mt-6 text-xs uppercase tracking-[0.3em] text-slate-500">
                            Limited Founding Partner Opportunities Available
                        </p>
                    </div>
                </div>
            </section>
        </WebsiteLayout>
    );
}
