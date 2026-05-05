import { Link, usePage } from "@inertiajs/react";

export default function NewsSection({ latestNews }) {
    const { locale } = usePage().props;
    const isEn = locale === "en";
    if (!latestNews || latestNews.length === 0) return null;
    return (
        <section className="container mx-auto px-6 py-16">
            <h3 className="text-2xl font-black text-yellow-500 mb-8 uppercase tracking-widest">
                <span className="text-white"></span>{" "}
                {isEn
                    ? "Latest Industry Intelligence"
                    : "Intelijen Industri Terbaru"}
            </h3>
            <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                {latestNews.map((news) => (
                    <div
                        key={news.id}
                        className="bg-white/5 rounded-3xl p-6 border border-white/10 hover:border-yellow-500/30 transition group"
                    >
                        <span className="text-[10px] text-gray-500 font-bold uppercase">
                            {new Date(news.created_at).toLocaleDateString()}
                        </span>
                        <h4 className="text-xl font-black text-white mt-4 group-hover:text-yellow-500 transition leading-tight uppercase tracking-tighter">
                            {isEn ? news.title_en : news.title_id}
                        </h4>
                        <Link
                            href={route("news.show", news.slug)}
                            className="..."
                        >
                            {isEn ? "READ ANALYSIS →" : "BACA ANALISIS →"}
                        </Link>
                    </div>
                ))}
            </div>
        </section>
    );
}
