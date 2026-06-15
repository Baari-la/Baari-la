import WebsiteLayout from "@/Layouts/WebsiteLayout";

export default function Index() {
    const categories = [
        "Testing & Certification",
        "Technology Solutions",
        "Industrial Machinery",
        "Raw Materials",
        "Logistics & Supply Chain",
        "Trade Finance",
        "Exhibitions & Events",
        "Research & Education",
    ];

    const benefits = [
        {
            icon: "fa-bullhorn",
            title: "Industry Visibility",
            description:
                "Increase visibility across Indonesia's textile industry ecosystem.",
        },
        {
            icon: "fa-lightbulb",
            title: "Thought Leadership",
            description:
                "Share expertise, innovation, and industry knowledge with industry stakeholders.",
        },
        {
            icon: "fa-handshake",
            title: "Business Opportunities",
            description:
                "Connect with buyers, suppliers, manufacturers, and strategic partners.",
        },
        {
            icon: "fa-seedling",
            title: "Ecosystem Growth",
            description:
                "Support the development and digital transformation of Indonesia's textile industry.",
        },
    ];

    const programs = [
        {
            name: "Bronze Partner",
            features: [
                "Company Profile",
                "Website Link",
                "Partner Directory Listing",
            ],
        },
        {
            name: "Silver Partner",
            features: [
                "Featured Listing",
                "Enhanced Visibility",
                "Partner Insight Publication",
            ],
        },
        {
            name: "Gold Partner",
            features: [
                "Homepage Visibility",
                "Featured Placement",
                "Thought Leadership Content",
            ],
        },
        {
            name: "Platinum Partner",
            features: [
                "Strategic Ecosystem Partner",
                "Industry Campaign Support",
                "Premium Visibility",
            ],
        },
    ];

    return (
        <WebsiteLayout>
            {/* HERO */}

            <section className="py-28 text-center">
                <div className="max-w-5xl mx-auto px-6">
                    <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                        FOUNDING ECOSYSTEM PARTNER PROGRAM
                    </span>

                    <h1 className="text-5xl md:text-7xl font-black text-white mt-6 uppercase">
                        Building The Future Of The Textile Industry Ecosystem
                    </h1>

                    <p className="max-w-3xl mx-auto mt-8 text-gray-400 text-lg">
                        Join a collaborative ecosystem connecting manufacturers,
                        solution providers, suppliers, technology companies,
                        institutions, and industry stakeholders.
                    </p>
                </div>
            </section>

            {/* WHY PARTNER */}

            <section className="py-24 border-t border-white/5">
                <div className="max-w-7xl mx-auto px-6">
                    <div className="text-center mb-14">
                        <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                            Why Join The Ecosystem
                        </span>

                        <h2 className="text-4xl font-black text-white mt-4 uppercase">
                            Grow With The Ecosystem
                        </h2>
                    </div>

                    <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                        {benefits.map((item) => (
                            <div
                                key={item.title}
                                className="
                                    rounded-[32px]
                                    border border-white/10
                                    bg-white/5
                                    backdrop-blur-xl
                                    p-8
                                    hover:border-yellow-500/30
                                    transition-all
                                "
                            >
                                <i
                                    className={`fas ${item.icon} text-yellow-500 text-4xl mb-6`}
                                />

                                <h3 className="text-white font-black text-xl mb-4">
                                    {item.title}
                                </h3>

                                <p className="text-gray-400 text-sm leading-relaxed">
                                    {item.description}
                                </p>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* PARTNER CATEGORIES */}

            <section className="py-24 border-t border-white/5">
                <div className="max-w-7xl mx-auto px-6">
                    <div className="text-center mb-14">
                        <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                            PARTNER CATEGORIES
                        </span>

                        <h2 className="text-4xl font-black text-white mt-4 uppercase">
                            Ecosystem Opportunities
                        </h2>
                    </div>

                    <div className="grid md:grid-cols-4 gap-4">
                        {categories.map((category) => (
                            <div
                                key={category}
                                className="
                                    rounded-2xl
                                    border border-white/10
                                    bg-white/5
                                    p-5
                                    text-center
                                "
                            >
                                <span className="text-white font-bold">
                                    {category}
                                </span>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* VISIBILITY */}

            <section className="py-24 border-t border-white/5">
                <div className="max-w-7xl mx-auto px-6">
                    <div className="text-center mb-14">
                        <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                            VISIBILITY OPPORTUNITIES
                        </span>

                        <h2 className="text-4xl font-black text-white mt-4 uppercase">
                            Partner Exposure Across DigTex
                        </h2>
                    </div>

                    <div className="grid md:grid-cols-2 gap-6">
                        <div className="rounded-[32px] border border-white/10 bg-white/5 p-8">
                            <h3 className="text-white font-black text-2xl mb-4">
                                Industry Solutions Directory
                            </h3>

                            <p className="text-gray-400">
                                Showcase your company, services, expertise, and
                                solutions through dedicated category landing
                                pages.
                            </p>
                        </div>

                        <div className="rounded-[32px] border border-white/10 bg-white/5 p-8">
                            <h3 className="text-white font-black text-2xl mb-4">
                                Partner Insights
                            </h3>

                            <p className="text-gray-400">
                                Publish compliance updates, technology trends,
                                industry knowledge, and thought leadership
                                content.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {/* PARTNER PROGRAMS */}

            {/* FOUNDING PARTNER BENEFITS */}

            <section className="py-24 border-t border-white/5">
                <div className="max-w-7xl mx-auto px-6">
                    <div className="text-center mb-14">
                        <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                            FOUNDING PARTNER BENEFITS
                        </span>

                        <h2 className="text-4xl font-black text-white mt-4 uppercase">
                            Why Join Early
                        </h2>

                        <p className="max-w-3xl mx-auto mt-6 text-gray-400">
                            Become part of the ecosystem from the beginning and
                            help shape the future of industry collaboration,
                            knowledge sharing, and digital transformation across
                            the textile value chain.
                        </p>
                    </div>

                    <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div className="rounded-[32px] border border-white/10 bg-white/5 p-8">
                            <h3 className="text-white font-black text-xl mb-4">
                                Industry Visibility
                            </h3>

                            <p className="text-gray-400">
                                Increase visibility through industry
                                directories, solution categories, and ecosystem
                                partner pages.
                            </p>
                        </div>

                        <div className="rounded-[32px] border border-white/10 bg-white/5 p-8">
                            <h3 className="text-white font-black text-xl mb-4">
                                Thought Leadership
                            </h3>

                            <p className="text-gray-400">
                                Share expertise, innovation, compliance updates,
                                research, and industry knowledge with
                                stakeholders.
                            </p>
                        </div>

                        <div className="rounded-[32px] border border-white/10 bg-white/5 p-8">
                            <h3 className="text-white font-black text-xl mb-4">
                                Strategic Collaboration
                            </h3>

                            <p className="text-gray-400">
                                Connect with manufacturers, exporters,
                                suppliers, institutions, and solution providers
                                across the ecosystem.
                            </p>
                        </div>

                        <div className="rounded-[32px] border border-white/10 bg-white/5 p-8">
                            <h3 className="text-white font-black text-xl mb-4">
                                Market Intelligence Exposure
                            </h3>

                            <p className="text-gray-400">
                                Participate in industry discussions, market
                                insights, and ecosystem knowledge initiatives.
                            </p>
                        </div>

                        <div className="rounded-[32px] border border-white/10 bg-white/5 p-8">
                            <h3 className="text-white font-black text-xl mb-4">
                                Early Ecosystem Recognition
                            </h3>

                            <p className="text-gray-400">
                                Be recognized as one of the organizations
                                supporting the development of the DigTex
                                ecosystem from its early stage.
                            </p>
                        </div>

                        <div className="rounded-[32px] border border-white/10 bg-white/5 p-8">
                            <h3 className="text-white font-black text-xl mb-4">
                                Long-Term Partnership Opportunities
                            </h3>

                            <p className="text-gray-400">
                                Explore future collaboration opportunities
                                across industry solutions, sourcing, exchange,
                                intelligence, and ecosystem initiatives.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {/* FINAL CTA */}

            <section className="py-24 border-t border-white/5">
                <div className="max-w-5xl mx-auto px-6 text-center">
                    <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                        READY TO JOIN?
                    </span>

                    <h2 className="text-4xl md:text-6xl font-black text-white mt-4 uppercase">
                        Become Part Of Indonesia's Textile Ecosystem
                    </h2>

                    <p className="max-w-3xl mx-auto mt-6 text-gray-400">
                        Join DigTex and help shape the future of Indonesia's
                        textile industry through collaboration, innovation, and
                        ecosystem growth.
                    </p>
                    <section className="py-24 border-t border-white/5">
                        <div className="max-w-6xl mx-auto px-6 text-center">
                            <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                                FOUNDING PARTNERS
                            </span>

                            <h2 className="text-4xl font-black text-white mt-4 uppercase">
                                Industry Leaders Joining Soon
                            </h2>

                            <p className="max-w-3xl mx-auto mt-6 text-gray-400">
                                DigTex is currently engaging with technology
                                providers, testing & certification
                                organizations, machinery suppliers, financial
                                institutions, logistics providers, exhibition
                                organizers, and research institutions across the
                                textile ecosystem.
                            </p>

                            <div
                                className="
            mt-12
            rounded-[40px]
            border border-dashed border-yellow-500/20
            bg-white/5
            backdrop-blur-xl
            p-12
        "
                            >
                                <span
                                    className="
                text-yellow-500
                text-lg
                font-black
                uppercase
                tracking-[0.4em]
            "
                                >
                                    Coming Soon
                                </span>
                            </div>
                        </div>
                    </section>
                    <a
                        href="mailto:partnership@digtex.id"
                        className="
                            inline-flex
                            items-center
                            justify-center
                            mt-10
                            px-10
                            py-5
                            bg-yellow-500
                            text-black
                            rounded-full
                            font-black
                            uppercase
                            text-xs
                            tracking-widest
                        "
                    >
                        Become An Ecosystem Partner
                    </a>
                </div>
            </section>
        </WebsiteLayout>
    );
}
