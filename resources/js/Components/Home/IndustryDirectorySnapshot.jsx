import { Link } from "@inertiajs/react";

export default function IndustryDirectorySnapshot({ isEn, stats }) {
    const directoryItems = [
        {
            icon: "fa-building",
            value: stats?.companies ?? 0,
            label: isEn ? "Verified Companies" : "Perusahaan",
        },

        {
            icon: "fa-box",
            value: stats?.products ?? 0,
            label: isEn ? "Products & Services" : "Produk & Layanan",
        },

        {
            icon: "fa-earth-asia",
            value: stats?.markets ?? 0,
            label: isEn ? "Export Markets" : "Pasar Ekspor",
        },

        {
            icon: "fa-ship",
            value: stats?.exportCompanies ?? 0,
            label: isEn ? "Export Companies" : "Perusahaan Ekspor",
        },
    ];

    return (
        <section className="py-28 bg-[#07111f] border-y border-white/5">
            <div className="max-w-7xl mx-auto px-6">
                <div className="text-center mb-16">
                    <span className="text-yellow-500 text-xs font-black tracking-[0.4em] uppercase">
                        Industry Directory
                    </span>

                    <h2 className="text-5xl font-black text-white mt-4 uppercase">
                        {isEn
                            ? "Verified Industry Network"
                            : "Jaringan Industri Terverifikasi"}
                    </h2>
                    <p className="max-w-3xl mx-auto mt-6 text-gray-400">
                        {isEn
                            ? `Explore ${Number(stats?.companies ?? 0).toLocaleString()} companies, ${Number(stats?.products ?? 0).toLocaleString()} products and services, and ${Number(stats?.markets ?? 0).toLocaleString()} export market records across Indonesia's textile ecosystem.`
                            : `Jelajahi ${Number(stats?.companies ?? 0).toLocaleString()} perusahaan, ${Number(stats?.products ?? 0).toLocaleString()} produk dan layanan, serta ${Number(stats?.markets ?? 0).toLocaleString()} data pasar ekspor dalam ekosistem industri tekstil Indonesia.`}
                    </p>
                </div>

                <div className="grid md:grid-cols-4 gap-6">
                    {directoryItems.map((item) => (
                        <div
                            key={item.label}
                            className="rounded-3xl bg-white/5 border border-white/10 p-10 text-center"
                        >
                            <i
                                className={`
        fas ${item.icon}
        text-yellow-500
        text-3xl
        mb-6
    `}
                            />
                            <div className="text-5xl font-black text-yellow-500 mb-3">
                                {Number(item.value).toLocaleString()}+
                            </div>

                            <div className="text-gray-300 uppercase text-xs tracking-widest">
                                {item.label}
                            </div>
                        </div>
                    ))}
                </div>

                <div className="flex flex-wrap justify-center gap-4 mt-12">
                    <Link
                        href={route("companies.index")}
                        className="inline-flex items-center bg-yellow-500 text-[#07111f] px-8 py-4 rounded-full font-black uppercase tracking-widest text-xs"
                    >
                        {isEn ? "Explore Directory" : "Jelajahi Direktori"}
                    </Link>
                    <Link
                        href={route("pricing.index")}
                        className="border border-white/20 px-8 py-4 rounded-full text-white uppercase text-xs tracking-widest"
                    >
                        {isEn ? "Join Network" : "Gabung Jaringan"}
                    </Link>
                </div>
            </div>
        </section>
    );
}
