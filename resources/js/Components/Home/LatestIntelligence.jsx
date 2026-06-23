import { Link } from "@inertiajs/react";

export default function LatestIntelligence({
    latestIntelligence = [],
    marketIntelligence,
    tradePolicy,
    sustainability,
    technology,
    industryNews,
    intelligenceStats,
}) {
    const featured = latestIntelligence?.[0];

    return (
        <section className="py-20 bg-white">
            <div className="max-w-7xl mx-auto px-6">
                {/* Header */}
                <div className="text-center mb-12">
                    <div className="text-xs uppercase tracking-[0.3em] text-yellow-600 font-black">
                        INTELLIGENCE CENTER
                    </div>

                    <h2 className="text-4xl md:text-5xl font-black text-[#0a192f] mt-3">
                        Global Market & Trade Insights
                    </h2>

                    <p className="mt-4 text-slate-600 max-w-3xl mx-auto">
                        Real-time market insights, trade intelligence, policy
                        developments, sustainability trends, and technology
                        innovations across the global textile industry.
                    </p>
                </div>

                {/* Statistics */}
                <div className="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div className="bg-slate-50 rounded-2xl p-4 text-center">
                        <div className="text-2xl font-black text-[#0a192f]">
                            {intelligenceStats?.reports?.toLocaleString() || 0}
                        </div>

                        <div className="text-xs text-slate-500 uppercase">
                            Reports
                        </div>
                    </div>

                    <div className="bg-slate-50 rounded-2xl p-4 text-center">
                        <div className="text-2xl font-black text-[#0a192f]">
                            {intelligenceStats?.desks || 0}
                        </div>

                        <div className="text-xs text-slate-500 uppercase">
                            Intelligence Desks
                        </div>
                    </div>

                    <div className="bg-slate-50 rounded-2xl p-4 text-center">
                        <div className="text-2xl font-black text-[#0a192f]">
                            {intelligenceStats?.companies?.toLocaleString() ||
                                0}
                        </div>

                        <div className="text-xs text-slate-500 uppercase">
                            Companies
                        </div>
                    </div>

                    <div className="bg-slate-50 rounded-2xl p-4 text-center">
                        <div className="text-2xl font-black text-[#0a192f]">
                            {intelligenceStats?.markets?.toLocaleString() || 0}
                        </div>

                        <div className="text-xs text-slate-500 uppercase">
                            Markets
                        </div>
                    </div>
                </div>

                <div className="text-center mb-10">
                    <p className="text-sm text-slate-500">
                        Powered by real industry data, trade intelligence, and
                        verified business networks.
                    </p>
                </div>

                {/* Intelligence Desks */}
                <div className="grid grid-cols-2 md:grid-cols-5 gap-4 mb-14">
                    {[
                        {
                            name: "Market Intelligence",
                            count: marketIntelligence?.length || 0,
                            icon: "📈",
                        },
                        {
                            name: "Trade & Policy",
                            count: tradePolicy?.length || 0,
                            icon: "🌍",
                        },
                        {
                            name: "Sustainability",
                            count: sustainability?.length || 0,
                            icon: "♻",
                        },
                        {
                            name: "Technology",
                            count: technology?.length || 0,
                            icon: "⚙",
                        },
                        {
                            name: "Industry News",
                            count: industryNews?.length || 0,
                            icon: "📰",
                        },
                    ].map((item) => (
                        <div
                            key={item.name}
                            className="bg-slate-50 border border-slate-200 rounded-2xl p-4 text-center"
                        >
                            <div className="text-2xl mb-2">{item.icon}</div>

                            <div className="text-xs font-black text-[#0a192f]">
                                {item.name}
                            </div>

                            <div className="text-[10px] text-slate-500 mt-1">
                                {item.count} Reports
                            </div>
                        </div>
                    ))}
                </div>

                {/* Featured Intelligence Report */}
                {featured && (
                    <div className="grid lg:grid-cols-2 gap-10 mb-16 items-center">
                        <div>
                            <img
                                src={
                                    featured.image
                                        ? `/storage/${featured.image}`
                                        : "/images/news-placeholder.jpg"
                                }
                                alt={featured.title}
                                className="w-full h-[500px] object-cover rounded-3xl"
                            />
                        </div>

                        <div>
                            <div className="text-xs uppercase tracking-[0.3em] text-yellow-600 font-black mb-3">
                                Featured Intelligence Report
                            </div>

                            <div className="flex flex-wrap items-center gap-2">
                                <span className="inline-flex px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-black uppercase">
                                    {featured.partner_name
                                        ? "Partner Insight"
                                        : featured.category}
                                </span>

                                {featured.partner_name && (
                                    <span className="inline-flex px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-black uppercase">
                                        Presented by {featured.partner_name}
                                    </span>
                                )}
                            </div>
                            <h3 className="mt-4 text-4xl lg:text-5xl font-black text-[#0a192f] leading-tight">
                                {featured.title}
                            </h3>

                            <p className="mt-4 text-slate-500 text-sm">
                                {new Date(
                                    featured.created_at,
                                ).toLocaleDateString("en-US", {
                                    day: "numeric",
                                    month: "long",
                                    year: "numeric",
                                })}
                            </p>

                            <div className="mt-6">
                                <div className="text-xs uppercase tracking-widest text-slate-400 font-black mb-2">
                                    Executive Summary
                                </div>

                                <p className="text-slate-600 leading-relaxed">
                                    {featured.summary ||
                                        featured.content
                                            ?.replace(/<[^>]*>/g, "")
                                            .substring(0, 220)}
                                </p>
                            </div>

                            <Link
                                href={route("news.show", featured.slug)}
                                className="inline-flex mt-8 px-8 py-4 bg-[#0a192f] text-yellow-500 rounded-full font-black uppercase tracking-widest hover:opacity-90"
                            >
                                Read Intelligence Report →
                            </Link>
                        </div>
                    </div>
                )}

                {/* Intelligence Grid */}
                <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                    {latestIntelligence.slice(1).map((item) => (
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
                                    className="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300"
                                />
                                <div className="p-5">
                                    <div className="flex items-start justify-between gap-2 mb-3">
                                        <div className="text-[10px] uppercase font-black text-yellow-600">
                                            {item.partner_name
                                                ? "Partner Insight"
                                                : item.category}
                                        </div>

                                        {item.partner_name ? (
                                            <div className="px-2 py-1 rounded-full bg-blue-50 text-blue-700 text-[10px] font-black uppercase whitespace-nowrap">
                                                {item.partner_name}
                                            </div>
                                        ) : (
                                            <div className="text-[10px] text-slate-400">
                                                Intelligence Brief
                                            </div>
                                        )}
                                    </div>

                                    <h4 className="font-bold text-[#0a192f] line-clamp-3">
                                        {item.title}
                                    </h4>

                                    <p className="mt-3 text-sm text-slate-600 line-clamp-3">
                                        {item.summary ||
                                            item.content
                                                ?.replace(/<[^>]*>/g, "")
                                                .substring(0, 120)}
                                    </p>

                                    <p className="text-xs text-slate-400 mt-4">
                                        {new Date(
                                            item.created_at,
                                        ).toLocaleDateString("en-US", {
                                            day: "numeric",
                                            month: "short",
                                            year: "numeric",
                                        })}
                                    </p>
                                </div>
                            </div>
                        </Link>
                    ))}
                </div>

                {/* CTA */}
                <div className="mt-16 text-center">
                    <Link
                        href="/news"
                        className="inline-flex items-center gap-2 px-8 py-4 rounded-full bg-[#0a192f] text-yellow-500 font-black uppercase tracking-widest hover:opacity-90"
                    >
                        View Intelligence Center
                    </Link>
                </div>
            </div>
        </section>
    );
}
