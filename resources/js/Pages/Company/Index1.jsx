import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, router, Link } from "@inertiajs/react";
import { useState, useEffect } from "react";
import Pagination from "@/Components/Pagination";

export default function Index({ companies, newsResults, filters, auth }) {
    const [search, setSearch] = useState(filters.search || "");
    const [category, setCategory] = useState(filters.category || "");
    const [location, setLocation] = useState(filters.location || "");
    const isEn = auth.locale === "en";

    useEffect(() => {
        const delayDebounceFn = setTimeout(() => {
            router.get(
                route("companies.index"),
                { search: search, category: category, location: location },
                { preserveState: true, replace: true },
            );
        }, 500);
        return () => clearTimeout(delayDebounceFn);
    }, [search, category, location]);

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head
                title={isEn ? "Industrial Directory" : "Direktori Industri"}
            />

            <div className="py-12 bg-[#0a192f] min-h-screen">
                <div className="max-w-7xl mx-auto px-6 lg:px-8">
                    {/* 1. HEADER */}
                    <div className="mb-20">
                        <span className="text-yellow-500 text-[10px] font-black uppercase tracking-[0.4em] mb-2 block">
                            {isEn
                                ? "National Asset Mapping"
                                : "Pemetaan Aset Nasional"}
                        </span>
                        <h1 className="text-5xl md:text-7xl font-black text-white uppercase tracking-tighter italic leading-none">
                            {isEn ? "Textile Industry " : "Big Data "}
                            <span className="text-yellow-500">
                                {isEn ? "Big Data" : "Industri Pertekstilan"}
                            </span>
                        </h1>
                    </div>

                    {/* 2. SEARCH & FILTER SECTION */}
                    <div className="relative z-20 -mt-10 mb-16">
                        <div className="bg-white/5 backdrop-blur-xl border border-white/10 p-2 rounded-[30px] shadow-2xl">
                            <div className="flex flex-col lg:flex-row gap-2">
                                {/* INPUT PENCARIAN */}
                                <div className="relative flex-grow">
                                    <div className="absolute inset-y-0 left-6 flex items-center pointer-events-none">
                                        <i className="fas fa-search text-yellow-500"></i>
                                    </div>
                                    <input
                                        type="text"
                                        value={search}
                                        onChange={(e) =>
                                            setSearch(e.target.value)
                                        }
                                        className="w-full bg-white/5 border-none focus:ring-2 focus:ring-yellow-500/50 text-white placeholder-gray-500 pl-14 pr-6 py-5 rounded-[24px] text-sm font-medium transition-all outline-none"
                                        placeholder={
                                            isEn
                                                ? "Search Company or Product..."
                                                : "Cari Perusahaan atau Produk..."
                                        }
                                    />
                                </div>

                                {/* DROP DOWN FILTERS */}
                                <div className="flex flex-col md:flex-row gap-2 p-1">
                                    <select
                                        value={category}
                                        onChange={(e) =>
                                            setCategory(e.target.value)
                                        }
                                        className="bg-white/5 border border-white/10 text-white text-[10px] font-black uppercase rounded-[20px] px-6 py-4 outline-none focus:ring-2 focus:ring-yellow-500 appearance-none cursor-pointer hover:bg-white/10 transition-all"
                                    >
                                        <option
                                            value=""
                                            className="bg-[#0a192f]"
                                        >
                                            {isEn
                                                ? "All Categories"
                                                : "Semua Kategori"}
                                        </option>
                                        <option
                                            value="Digital Printing"
                                            className="bg-[#0a192f]"
                                        >
                                            Digital Printing
                                        </option>
                                        <option
                                            value="Garment"
                                            className="bg-[#0a192f]"
                                        >
                                            Garment
                                        </option>
                                        <option
                                            value="Spinning"
                                            className="bg-[#0a192f]"
                                        >
                                            Spinning
                                        </option>
                                    </select>

                                    <select
                                        value={location}
                                        onChange={(e) =>
                                            setLocation(e.target.value)
                                        }
                                        className="bg-white/5 border border-white/10 text-white text-[10px] font-black uppercase rounded-[20px] px-6 py-4 outline-none focus:ring-2 focus:ring-yellow-500 appearance-none cursor-pointer hover:bg-white/10 transition-all"
                                    >
                                        <option
                                            value=""
                                            className="bg-[#0a192f]"
                                        >
                                            {isEn
                                                ? "All Locations"
                                                : "Semua Lokasi"}
                                        </option>
                                        <option
                                            value="Jakarta"
                                            className="bg-[#0a192f]"
                                        >
                                            Jakarta
                                        </option>
                                        <option
                                            value="Bandung"
                                            className="bg-[#0a192f]"
                                        >
                                            Bandung
                                        </option>
                                        <option
                                            value="Solo"
                                            className="bg-[#0a192f]"
                                        >
                                            Solo
                                        </option>
                                        <option
                                            value="Bali"
                                            className="bg-[#0a192f]"
                                        >
                                            Bali
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {/* QUICK TAGS */}
                        <div className="mt-4 flex flex-wrap gap-4 px-6">
                            <span className="text-[9px] font-bold text-gray-500 uppercase tracking-widest">
                                {isEn ? "Trending:" : "Populer:"}
                            </span>
                            {[
                                "Zipper",
                                "Digital Printing",
                                "Eco-Textile",
                                "Garment",
                            ].map((tag) => (
                                <button
                                    key={tag}
                                    onClick={() => setSearch(tag)}
                                    className="text-[9px] font-black uppercase tracking-widest text-yellow-500/60 hover:text-yellow-500 transition-colors"
                                >
                                    #{tag}
                                </button>
                            ))}
                        </div>
                    </div>

                    {/* 3. RELATED NEWS */}
                    {newsResults.data.length > 0 && search && (
                        <div className="mb-16 animate-in fade-in slide-in-from-bottom-4 duration-700">
                            <h2 className="text-yellow-500 text-[10px] font-black uppercase tracking-[0.4em] mb-6 border-l-2 border-yellow-500 pl-4">
                                {isEn
                                    ? "Related Intelligence News"
                                    : "Berita Intelijen Terkait"}
                            </h2>
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                                {newsResults.data.map((item) => (
                                    <Link
                                        href={route("news.show", item.slug)}
                                        key={item.id}
                                        className="bg-white/5 p-6 rounded-[30px] border border-white/5 hover:border-yellow-500/30 transition-all group"
                                    >
                                        <p className="text-yellow-500 text-[8px] font-black uppercase mb-2">
                                            News Report
                                        </p>
                                        <h4 className="text-white font-bold text-sm leading-tight group-hover:text-yellow-500 transition-colors">
                                            {isEn
                                                ? item.title_en
                                                : item.title_id}
                                        </h4>
                                    </Link>
                                ))}
                            </div>
                        </div>
                    )}

                    {/* 4. COMPANY LIST */}
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                        {companies.data.map((company) => (
                            <div
                                key={company.id}
                                className="bg-white/5 border border-white/10 p-8 rounded-[40px] hover:border-yellow-500/30 transition-all group relative overflow-hidden"
                            >
                                <div className="flex justify-between items-start mb-6">
                                    <div className="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center">
                                        <i className="fas fa-industry text-yellow-500"></i>
                                    </div>
                                    {company.membership_type ===
                                        "gold_member" && (
                                        <span className="bg-yellow-500 text-[#0a192f] text-[8px] font-black px-3 py-1 rounded-full uppercase tracking-tighter shadow-lg">
                                            Gold Member
                                        </span>
                                    )}
                                </div>
                                <h3 className="text-white font-black uppercase italic text-lg mb-2 tracking-tighter group-hover:text-yellow-500 transition-colors">
                                    {company.name || company.nama_perusahaan}
                                </h3>
                                <p className="text-gray-500 text-[10px] font-bold uppercase tracking-widest mb-6">
                                    {company.city}, {company.province}
                                </p>
                                <Link
                                    href={route(
                                        "companies.show",
                                        company.slug || company.id,
                                    )}
                                    className="inline-block text-yellow-500 text-[9px] font-black uppercase tracking-[0.2em] border-b border-yellow-500/20 pb-1 hover:border-yellow-500 transition-all"
                                >
                                    {isEn
                                        ? "View Intelligence Profile →"
                                        : "Lihat Profil Intelijen →"}
                                </Link>
                            </div>
                        ))}
                    </div>

                    {/* 5. PAGINATION */}
                    <Pagination links={companies.links} />
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
