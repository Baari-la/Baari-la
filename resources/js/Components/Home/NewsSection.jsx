import { Link, usePage } from "@inertiajs/react";

export default function NewsSection({ latestNews }) {
    const { locale } = usePage().props;
    const isEn = locale === "en";

    if (!latestNews || latestNews.length === 0) return null;

    return (
        <section className="py-24 border-t border-white/5">
            <div className="max-w-7xl mx-auto px-6">
                {/* Heading */}
                <div className="text-center mb-16">
                    <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                        NEWS & EVENTS
                    </span>

                    <h2 className="text-4xl md:text-6xl font-black text-white mt-4 uppercase">
                        {isEn ? "Industry Updates" : "Kabar Terkini Industri"}
                    </h2>

                    <p className="max-w-3xl mx-auto mt-6 text-gray-400">
                        {isEn
                            ? "Latest news, exhibitions, regulations, trade developments, and industry events shaping the textile ecosystem."
                            : "Berita terbaru, pameran, regulasi, perkembangan perdagangan, dan kegiatan industri yang membentuk ekosistem tekstil."}
                    </p>
                </div>

                {/* News Grid */}
                <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                    {latestNews.map((news) => (
                        <div
                            key={news.id}
                            className="
                                group
                                rounded-[32px]
                                border border-white/10
                                bg-white/5
                                backdrop-blur-xl
                                p-8
                                transition-all
                                duration-500
                                hover:border-yellow-500/30
                                hover:-translate-y-1
                            "
                        >
                            <span className="text-[10px] text-gray-500 font-bold uppercase">
                                {new Date(news.created_at).toLocaleDateString()}
                            </span>

                            <h4
                                className="
                                text-xl
                                font-black
                                text-white
                                mt-4
                                leading-tight
                                uppercase
                                tracking-tighter
                                group-hover:text-yellow-500
                                transition-colors
                            "
                            >
                                {isEn ? news.title_en : news.title_id}
                            </h4>

                            <Link
                                href={route("news.show", news.slug)}
                                className="
                                    inline-flex
                                    items-center
                                    gap-2
                                    mt-6
                                    text-yellow-500
                                    font-black
                                    uppercase
                                    text-xs
                                    tracking-widest
                                    hover:text-yellow-400
                                "
                            >
                                {isEn ? "READ MORE" : "SELENGKAPNYA"}

                                <i className="fas fa-arrow-right" />
                            </Link>
                        </div>
                    ))}
                </div>

                {/* CTA */}
                <div className="text-center mt-14">
                    <Link
                        // href={route("news.index")}
                        className="
                            inline-flex
                            items-center
                            gap-3
                            px-8
                            py-4
                            border border-white/10
                            rounded-full
                            text-white
                            uppercase
                            text-xs
                            font-black
                            tracking-widest
                            hover:border-yellow-500/30
                            transition-all
                        "
                    >
                        {isEn
                            ? "View All News & Events"
                            : "Lihat Semua Berita & Event"}
                    </Link>
                </div>
            </div>
        </section>
    );
}
