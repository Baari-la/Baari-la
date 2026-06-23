import WebsiteLayout from "@/Layouts/WebsiteLayout";
import { Link } from "@inertiajs/react";

export default function Index({ featured, articles, partners }) {
    // const partners = [
    //     {
    //         name: "TESTEX",
    //         category: "Testing & Certification",
    //         icon: "fa-shield-halved",
    //     },
    //     {
    //         name: "Coats",
    //         category: "Thread & Material Innovation",
    //         icon: "fa-link",
    //     },
    //     {
    //         name: "Epson",
    //         category: "Digital Textile Printing",
    //         icon: "fa-print",
    //     },
    //     {
    //         name: "Centric Software",
    //         category: "Digital Transformation",
    //         icon: "fa-microchip",
    //     },
    //     {
    //         name: "SGS",
    //         category: "Testing & Compliance",
    //         icon: "fa-flask",
    //     },
    //     {
    //         name: "Bureau Veritas",
    //         category: "Inspection & Certification",
    //         icon: "fa-certificate",
    //     },
    // ];

    return (
        <WebsiteLayout>
            <section className="bg-white">
                {/* HERO */}
                <div className="max-w-7xl mx-auto px-6 py-24">
                    {/* HERO */}
                    <div className="text-center">
                        <span className="text-yellow-600 text-xs font-black uppercase tracking-[0.4em]">
                            PARTNER INSIGHTS
                        </span>

                        <h1 className="mt-4 text-5xl md:text-6xl font-black text-[#0a192f]">
                            Knowledge & Thought Leadership
                        </h1>

                        <p className="max-w-4xl mx-auto mt-6 text-slate-600 text-lg leading-relaxed">
                            Explore technology innovation, sustainability
                            initiatives, compliance updates, manufacturing
                            excellence, and strategic industry insights
                            contributed by ecosystem partners supporting textile
                            and apparel manufacturers worldwide.
                        </p>

                        <p className="max-w-3xl mx-auto mt-4 text-slate-500">
                            Discover how testing, certification, digital
                            transformation, machinery innovation, advanced
                            materials, and industry solutions help manufacturers
                            improve competitiveness, efficiency, and long-term
                            growth.
                        </p>
                    </div>

                    {/* STATS */}
                    <div className="grid grid-cols-2 md:grid-cols-4 gap-6 mt-16">
                        <div className="bg-slate-50 rounded-3xl p-6 text-center border border-slate-100">
                            <div className="text-3xl font-black text-[#0a192f]">
                                {partners?.length || 0}
                            </div>

                            <div className="text-xs uppercase tracking-widest text-slate-500 mt-2">
                                Ecosystem Partners
                            </div>
                        </div>

                        <div className="bg-slate-50 rounded-3xl p-6 text-center border border-slate-100">
                            <div className="text-3xl font-black text-[#0a192f]">
                                {articles?.total || 0}
                            </div>

                            <div className="text-xs uppercase tracking-widest text-slate-500 mt-2">
                                Partner Insights
                            </div>
                        </div>

                        <div className="bg-slate-50 rounded-3xl p-6 text-center border border-slate-100">
                            <div className="text-3xl font-black text-[#0a192f]">
                                6
                            </div>

                            <div className="text-xs uppercase tracking-widest text-slate-500 mt-2">
                                Solution Areas
                            </div>
                        </div>

                        <div className="bg-slate-50 rounded-3xl p-6 text-center border border-slate-100">
                            <div className="text-3xl font-black text-[#0a192f]">
                                Global
                            </div>

                            <div className="text-xs uppercase tracking-widest text-slate-500 mt-2">
                                Industry Reach
                            </div>
                        </div>
                    </div>

                    {/* SUBTEXT */}
                    <div className="text-center mt-10">
                        <p className="text-sm text-slate-500">
                            Powered by real industry expertise, technology
                            innovation, compliance leadership, and strategic
                            solutions from Digestex ecosystem partners.
                        </p>
                    </div>
                </div>

                {/* FEATURED PARTNER INSIGHT */}

                {featured && (
                    <div className="max-w-7xl mx-auto px-6 pb-24">
                        <div className="mb-8">
                            <span className="text-yellow-600 text-xs font-black uppercase tracking-[0.4em]">
                                FEATURED PARTNER INSIGHT
                            </span>
                        </div>

                        <div className="grid lg:grid-cols-2 gap-10 items-center bg-slate-50 rounded-[32px] overflow-hidden border border-slate-200">
                            <div>
                                <img
                                    src={
                                        featured.image
                                            ? `/storage/${featured.image}`
                                            : "/images/news-placeholder.jpg"
                                    }
                                    alt={featured.title}
                                    className="w-full h-[420px] object-cover"
                                />
                            </div>

                            <div className="p-10">
                                <div className="flex flex-wrap items-center gap-2 mb-4">
                                    <span className="inline-flex px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-black uppercase">
                                        Partner Insight
                                    </span>

                                    {featured.partner_name && (
                                        <span className="inline-flex px-3 py-1 rounded-full bg-slate-200 text-slate-700 text-xs font-black uppercase">
                                            Presented by {featured.partner_name}
                                        </span>
                                    )}
                                </div>

                                <h2 className="text-4xl font-black text-[#0a192f] leading-tight">
                                    {featured.title}
                                </h2>

                                <p className="mt-6 text-slate-600 leading-relaxed">
                                    {featured.summary ||
                                        featured.content
                                            ?.replace(/<[^>]*>/g, "")
                                            .substring(0, 250)}
                                    ...
                                </p>

                                <div className="mt-6 text-sm text-slate-500">
                                    {new Date(
                                        featured.created_at,
                                    ).toLocaleDateString("en-US", {
                                        day: "numeric",
                                        month: "long",
                                        year: "numeric",
                                    })}
                                </div>

                                <Link
                                    href={route("news.show", featured.slug)}
                                    className="inline-flex mt-8 px-6 py-3 bg-[#0a192f] text-yellow-500 rounded-full font-black uppercase tracking-wider"
                                >
                                    Read Insight
                                </Link>
                            </div>
                        </div>
                    </div>
                )}
                {/* PARTNER DIRECTORY */}
                <div className="max-w-7xl mx-auto px-6 pb-24">
                    <div className="mb-12 text-center">
                        <h2 className="text-3xl font-black text-[#0a192f]">
                            Ecosystem Solution Partners
                        </h2>

                        <p className="text-slate-600 mt-3">
                            Organizations contributing technology, innovation,
                            compliance, sustainability, and business solutions
                            to support manufacturers across the textile value
                            chain.
                        </p>
                    </div>

                    <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {partners.map((partner) => (
                            <div
                                key={partner.partner_name}
                                className="
                    bg-white
                    border
                    border-slate-200
                    rounded-3xl
                    p-8
                    hover:shadow-xl
                    transition-all
                "
                            >
                                <div className="text-[10px] uppercase font-black tracking-widest text-yellow-600 mb-3">
                                    Ecosystem Partner
                                </div>

                                <h3 className="text-xl font-black text-[#0a192f]">
                                    {partner.partner_name}
                                </h3>

                                <p className="mt-3 text-slate-600">
                                    {partner.total_articles} Insights Published
                                </p>

                                <div className="mt-6 text-xs font-black uppercase tracking-widest text-yellow-600">
                                    <Link
                                        href={route(
                                            "partner-insights.show",
                                            partner.partner_name,
                                        )}
                                        className="mt-6 inline-flex text-xs font-black uppercase tracking-widest text-yellow-600"
                                    >
                                        View Insights →
                                    </Link>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>

                {/* LATEST PARTNER INSIGHTS */}

                <div className="max-w-7xl mx-auto px-6 pb-24">
                    <div className="text-center mb-12">
                        <span className="text-yellow-600 text-xs font-black uppercase tracking-[0.4em]">
                            LATEST INSIGHTS
                        </span>

                        <h2 className="mt-4 text-4xl font-black text-[#0a192f]">
                            Latest Partner Insights
                        </h2>

                        <p className="mt-4 text-slate-600 max-w-3xl mx-auto">
                            Explore the latest technology updates, compliance
                            guidance, sustainability initiatives, innovation
                            trends, and strategic industry knowledge shared by
                            ecosystem partners.
                        </p>
                    </div>

                    <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {articles?.data?.map((item) => (
                            <Link
                                key={item.id}
                                href={route("news.show", item.slug)}
                                className="group"
                            >
                                <div className="bg-white border border-slate-200 rounded-3xl overflow-hidden hover:shadow-xl transition-all duration-300">
                                    <img
                                        src={
                                            item.image
                                                ? `/storage/${item.image}`
                                                : "/images/news-placeholder.jpg"
                                        }
                                        alt={item.title}
                                        className="w-full h-52 object-cover group-hover:scale-105 transition-transform duration-300"
                                    />

                                    <div className="p-6">
                                        <div className="flex items-center justify-between mb-3">
                                            <span className="text-[10px] uppercase font-black text-yellow-600">
                                                Partner Insight
                                            </span>

                                            {item.partner_name && (
                                                <span className="text-[10px] font-black uppercase px-2 py-1 rounded-full bg-blue-50 text-blue-700">
                                                    {item.partner_name}
                                                </span>
                                            )}
                                        </div>

                                        <h3 className="font-black text-[#0a192f] text-lg line-clamp-3">
                                            {item.title}
                                        </h3>

                                        <p className="mt-3 text-sm text-slate-600 line-clamp-3">
                                            {item.summary ||
                                                item.content
                                                    ?.replace(/<[^>]*>/g, "")
                                                    .substring(0, 140)}
                                            ...
                                        </p>

                                        <div className="mt-4 text-xs text-slate-400">
                                            {new Date(
                                                item.created_at,
                                            ).toLocaleDateString("en-US", {
                                                day: "numeric",
                                                month: "short",
                                                year: "numeric",
                                            })}
                                        </div>
                                    </div>
                                </div>
                            </Link>
                        ))}
                    </div>
                </div>
                {/* FEATURED SECTION */}
                <div className="bg-slate-50 py-24">
                    <div className="max-w-7xl mx-auto px-6 text-center">
                        <span className="text-yellow-600 text-xs font-black uppercase tracking-[0.4em]">
                            FEATURED INSIGHT
                        </span>

                        <h2 className="mt-4 text-4xl font-black text-[#0a192f]">
                            Partner Knowledge Center
                        </h2>

                        <p className="max-w-3xl mx-auto mt-6 text-slate-600">
                            Discover expert insights, case studies, technology
                            updates, regulatory guidance, and innovation
                            strategies from ecosystem partners.
                        </p>

                        <div className="mt-10 inline-flex px-6 py-3 rounded-full bg-yellow-100 text-yellow-700 font-black uppercase text-sm">
                            Partner Insights Coming Soon
                        </div>
                    </div>

                    <div className="mt-12 text-center">
                        <button className="px-6 py-3 rounded-full border border-slate-300 text-slate-700 font-bold">
                            Load More Insights
                        </button>
                    </div>
                </div>

                {/* CTA */}
                <div className="bg-[#07111f] py-24">
                    <div className="max-w-4xl mx-auto px-6 text-center">
                        <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                            FOUNDING ECOSYSTEM PARTNER
                        </span>

                        <h2 className="mt-4 text-4xl md:text-5xl font-black text-white">
                            Share Your Expertise With The Industry
                        </h2>

                        <p className="mt-6 text-gray-400">
                            Publish insights, case studies, technology updates,
                            compliance guidance, and thought leadership content
                            to reach manufacturers, exporters, brands, and
                            industry stakeholders across the textile ecosystem.
                        </p>

                        <Link
                            href={route("pricing.index")}
                            className="inline-flex mt-10 px-8 py-4 rounded-full bg-yellow-500 text-[#0a192f] font-black uppercase tracking-widest"
                        >
                            Become A Partner
                        </Link>
                    </div>
                </div>
            </section>
        </WebsiteLayout>
    );
}
