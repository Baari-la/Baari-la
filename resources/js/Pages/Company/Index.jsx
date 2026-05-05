import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, router, Link } from "@inertiajs/react";
import { useState, useEffect, useRef } from "react";
import Pagination from "@/Components/Pagination";

export default function Index({ companies, newsResults, filters, auth }) {
    // 1. STATE MANAGEMENT
    const [search, setSearch] = useState(filters.search || "");
    const [category, setCategory] = useState(filters.category || "");
    const [location, setLocation] = useState(filters.location || "");

    const isEn = auth.locale === "en";

    // 2. LOGIKA AKSES (Premium & API Member)
    const isPremium =
        (auth.user && auth.user.role === "premium") ||
        (auth.user && auth.user.is_api_member);

    // 3. LOGIKA FILTER (Debounce Search)
    const isFirstRender = useRef(true);

    useEffect(() => {
        // Mencegah trigger router.get pada saat pertama kali halaman dimuat
        if (isFirstRender.current) {
            isFirstRender.current = false;
            return;
        }

        const delayDebounceFn = setTimeout(() => {
            router.get(
                route("companies.index"),
                { search, category, location },
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
                    {/* --- BAGIAN 1: HEADER --- */}
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

                    {/* --- BAGIAN 2: SEARCH & FILTER (Terbuka untuk Semua) --- */}
                    <div className="relative z-20 -mt-10 mb-16">
                        <div className="bg-white/5 backdrop-blur-xl border border-white/10 p-2 rounded-[30px] shadow-2xl">
                            <div className="flex flex-col lg:flex-row gap-2">
                                {/* Input Search */}
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

                                {/* Dropdown Filters */}
                                <div className="flex flex-col md:flex-row gap-2 p-1">
                                    <select
                                        value={category}
                                        onChange={(e) =>
                                            setCategory(e.target.value)
                                        }
                                        className="bg-[#0a192f] border border-white/10 text-white text-[10px] font-black uppercase rounded-[20px] px-6 py-4 outline-none focus:ring-2 focus:ring-yellow-500 appearance-none cursor-pointer hover:bg-white/10 transition-all"
                                    >
                                        <option value="">
                                            {isEn
                                                ? "All Categories"
                                                : "Semua Kategori"}
                                        </option>
                                        <option value="Digital Printing">
                                            Digital Printing
                                        </option>
                                        <option value="Garment">Garment</option>
                                        <option value="Spinning">
                                            Spinning
                                        </option>
                                    </select>

                                    <select
                                        value={location}
                                        onChange={(e) =>
                                            setLocation(e.target.value)
                                        }
                                        className="bg-[#0a192f] border border-white/10 text-white text-[10px] font-black uppercase rounded-[20px] px-6 py-4 outline-none focus:ring-2 focus:ring-yellow-500 appearance-none cursor-pointer hover:bg-white/10 transition-all"
                                    >
                                        <option value="">
                                            {isEn
                                                ? "All Locations"
                                                : "Semua Lokasi"}
                                        </option>
                                        <option value="Jakarta">Jakarta</option>
                                        <option value="Bandung">Bandung</option>
                                        <option value="Solo">Solo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* --- BAGIAN 3: RELATED NEWS (TERBUKA UNTUK UMUM) --- */}
                    {newsResults && newsResults.data.length > 0 && search && (
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
                                        className="bg-white/5 p-6 rounded-[30px] border border-white/5 hover:border-yellow-500/30 transition-all group block"
                                    >
                                        <p className="text-[10px] text-gray-500 font-bold uppercase mb-2">
                                            {new Date(
                                                item.created_at,
                                            ).toLocaleDateString()}
                                        </p>
                                        <h3 className="text-white font-black text-sm uppercase leading-tight group-hover:text-yellow-500 transition-colors line-clamp-2">
                                            {isEn
                                                ? item.title_en
                                                : item.title_id}
                                        </h3>
                                        <div className="mt-4 flex items-center text-yellow-500 text-[9px] font-black uppercase tracking-widest">
                                            {isEn ? "Read Intel" : "Baca Intel"}{" "}
                                            <i className="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                                        </div>
                                    </Link>
                                ))}
                            </div>
                        </div>
                    )}

                    {/* --- BAGIAN 4: PROTECTED DATA SECTION --- */}
                    <div className="relative min-h-[400px]">
                        {!isPremium ? (
                            /* TAMPILAN JIKA BUKAN PREMIUM (LOCK) */
                            <div className="absolute inset-0 z-50 flex flex-col items-center justify-center bg-black/70 backdrop-blur-md rounded-[40px] p-10 text-center border border-white/10">
                                <div className="bg-blue-500/20 p-5 rounded-full mb-6 border border-blue-500/30 shadow-[0_0_30px_rgba(59,130,246,0.3)] text-3xl">
                                    🔒
                                </div>
                                <h4 className="text-white font-black uppercase italic text-2xl mb-4 tracking-tighter">
                                    {isEn
                                        ? "RESTRICTED ACCESS"
                                        : "AKSES TERBATAS"}
                                </h4>

                                {/* Deskripsi Bilingual Konsisten */}
                                <p className="text-slate-300 text-xs mb-10 max-w-md leading-relaxed font-medium px-4">
                                    {isEn
                                        ? "This professional tool is reserved for Premium Members. API Jakarta Members are eligible for Special Access."
                                        : "Alat profesional ini khusus untuk Member Premium. Anggota API Jakarta berhak mendapatkan Akses Istimewa."}
                                </p>

                                <div className="flex flex-col sm:flex-row gap-4 w-full max-w-md justify-center px-6">
                                    <Link
                                        href={route("login")}
                                        className="bg-white text-black px-6 py-4 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 transition-all shadow-xl text-center flex-1"
                                    >
                                        {isEn
                                            ? "Login Member API Jakarta"
                                            : "Login Anggota API Jakarta"}
                                    </Link>
                                    <a
                                        href="https://wa.me/628129928939"
                                        target="_blank"
                                        className="bg-yellow-500 text-black px-6 py-4 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-yellow-400 transition-all shadow-[0_0_20px_rgba(234,179,8,0.4)] text-center flex-1"
                                    >
                                        {isEn
                                            ? "Register as Premium"
                                            : "Daftar Premium"}
                                    </a>
                                </div>
                            </div>
                        ) : (
                            /* TAMPILAN JIKA PREMIUM (DAFTAR PERUSAHAAN) */
                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                {companies.data.map((company) => (
                                    <div
                                        key={company.id}
                                        className="bg-white/5 border border-white/10 p-6 rounded-[30px] hover:border-yellow-500/50 transition-all"
                                    >
                                        <h3 className="text-white font-bold text-lg mb-2">
                                            <Link
                                                href={route(
                                                    "companies.show",
                                                    company.id,
                                                )}
                                                className="hover:text-yellow-500 transition-colors"
                                            >
                                                {company.nama_perusahaan}
                                            </Link>
                                        </h3>
                                        <div className="text-gray-400 text-xs space-y-1">
                                            <p>
                                                <i className="fas fa-tag mr-2 text-yellow-500"></i>{" "}
                                                {company.sektor}
                                            </p>
                                            <p>
                                                <i className="fas fa-map-marker-alt mr-2 text-yellow-500"></i>{" "}
                                                {company.wilayah}
                                            </p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        )}
                    </div>

                    {/* --- BAGIAN 5: PAGINATION (Hanya muncul jika premium) --- */}
                    {isPremium && companies.links && (
                        <div className="mt-12">
                            <Pagination links={companies.links} />
                        </div>
                    )}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
