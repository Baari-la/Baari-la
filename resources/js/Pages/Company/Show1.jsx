import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
// import { Head, Link, usePage } from "@inertiajs/react";
import { Head, Link, router } from "@inertiajs/react";

export default function Show({ auth, company }) {
    const isEn = auth.locale === "en";

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head
                title={`${company.nama_perusahaan} - Industrial Intelligence`}
            />

            <div className="py-12 bg-[#0a192f] min-h-screen text-white">
                <div className="max-w-5xl mx-auto px-6">
                    {/* BREADCRUMB */}
                    <Link
                        href={route("companies.index")}
                        className="text-yellow-500 text-[10px] font-black uppercase tracking-widest mb-8 inline-block hover:text-white transition-all"
                    >
                        ← {isEn ? "Back to Big Data" : "Kembali ke Big Data"}
                    </Link>

                    {/* HEADER PROFILE */}
                    <div className="bg-white/5 border border-white/10 rounded-[50px] p-10 mb-8 relative overflow-hidden">
                        {/* 1. BARIS PALING ATAS: STATUS & PRESTIGE */}
                        <div className="flex justify-between items-start mb-8">
                            <div className="flex items-center gap-3">
                                {company.membership_type === "gold_member" && (
                                    <div className="flex items-center gap-2 bg-yellow-500 text-[#0a192f] text-[9px] font-black px-5 py-2 rounded-full uppercase tracking-tighter shadow-[0_0_20px_rgba(234,179,8,0.3)]">
                                        <i className="fas fa-crown"></i>
                                        Gold Member
                                    </div>
                                )}
                                <span className="bg-blue-500/10 border border-blue-500/30 text-blue-400 text-[8px] font-black px-4 py-2 rounded-full uppercase tracking-[0.3em] backdrop-blur-md shadow-[0_0_15px_rgba(59,130,246,0.1)]">
                                    {company.sektor}
                                </span>
                            </div>

                            {/* TOMBOL UPDATE PINDAH KE POJOK KANAN (TIDAK MENEMPEL) */}
                            {auth.user &&
                                (auth.user.role === "admin" ||
                                    auth.user.company_id === company.id) && (
                                    <Link
                                        href={route(
                                            "companies.edit",
                                            company.id,
                                        )}
                                        className="bg-blue-600 hover:bg-blue-500 text-white px-6 py-2.5 rounded-2xl text-[9px] font-black uppercase tracking-widest transition-all shadow-lg shadow-blue-500/10 flex items-center gap-2"
                                    >
                                        <i className="fas fa-sync-alt"></i>
                                        {isEn
                                            ? "Update Intelligence"
                                            : "Mutakhirkan Data"}
                                    </Link>
                                )}
                        </div>

                        {/* 2. BARIS TENGAH: NAMA PERUSAHAAN */}
                        <div className="relative z-10 mt-4">
                            <h1 className="text-4xl md:text-6xl font-black uppercase italic tracking-tighter leading-none mb-4 text-white">
                                {company.nama_perusahaan}
                            </h1>
                            <div className="flex items-center gap-3 text-gray-400">
                                <i className="fas fa-map-marker-alt text-yellow-500 text-xs"></i>
                                <p className="text-xs font-medium italic tracking-wide">
                                    {company.alamat_lengkap}
                                </p>
                            </div>
                        </div>
                    </div>
                    {/* Tambahan galeri */}

                    {/* Contoh capacity */}
                    {company.capacities?.length > 0 && (
                        <div className="bg-white/5 border border-white/10 rounded-[40px] p-10">
                            <h2 className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em] mb-8">
                                Production Capacity
                            </h2>

                            <div className="space-y-4">
                                {company.capacities.map((capacity) => (
                                    <div
                                        key={capacity.id}
                                        className="border border-white/10 rounded-2xl p-6 bg-white/5"
                                    >
                                        <div className="flex justify-between items-start">
                                            <div>
                                                <h3 className="text-lg font-black text-white uppercase italic">
                                                    {capacity.item_name}
                                                </h3>

                                                <p className="text-gray-400 text-xs uppercase tracking-widest mt-1">
                                                    {capacity.capacity_type}
                                                </p>
                                            </div>

                                            <div className="text-right">
                                                <p className="text-2xl font-black text-emerald-400">
                                                    {Number(
                                                        capacity.capacity_value,
                                                    ).toLocaleString()}
                                                </p>

                                                <p className="text-[10px] text-gray-500 uppercase">
                                                    {capacity.capacity_unit}
                                                </p>
                                            </div>
                                        </div>

                                        <div className="mt-4 flex gap-4 text-[10px] uppercase font-bold">
                                            <span className="text-blue-400">
                                                {capacity.capacity_category}
                                            </span>

                                            {capacity.machine_count && (
                                                <span className="text-yellow-500">
                                                    {capacity.machine_count}{" "}
                                                    Machines
                                                </span>
                                            )}
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}
                    {/* Stock Barang */}
                    {/* FLASH STOCK BADGE - Penanda dari Lantai Bursa */}
                    {company.stock_qty > 0 && (
                        <div className="mb-8 p-6 bg-gradient-to-r from-emerald-600/20 to-transparent border-l-4 border-emerald-500 rounded-r-[30px] animate-in slide-in-from-left duration-700">
                            <div className="flex items-center gap-4">
                                <div className="h-12 w-12 bg-emerald-500 rounded-2xl flex items-center justify-center text-[#0a192f] shadow-lg shadow-emerald-500/20">
                                    <i className="fas fa-bolt text-xl animate-pulse"></i>
                                </div>
                                <div>
                                    <p className="text-emerald-400 text-[9px] font-black uppercase tracking-[0.3em] mb-1">
                                        Flash Stock Available
                                    </p>
                                    <h4 className="text-white text-lg font-black uppercase italic leading-none">
                                        {company.stock_ready_caption}
                                    </h4>
                                    <div className="flex gap-4 mt-2">
                                        <span className="text-gray-400 text-[10px] font-bold italic uppercase">
                                            Current Volume:{" "}
                                            <span className="text-white">
                                                {company.stock_qty.toLocaleString()}{" "}
                                                {company.stock_unit}
                                            </span>
                                        </span>
                                        <div className="h-3 w-px bg-white/10"></div>
                                        <span className="text-yellow-500 text-[10px] font-black italic uppercase">
                                            Market Price: Rp{" "}
                                            {Math.round(
                                                company.price,
                                            ).toLocaleString()}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            {/* SHARE COMPONENT: THE DIGITAL BUSINESS CARD */}
                            <div className="mt-6 flex flex-wrap items-center gap-4 border-t border-white/5 pt-6">
                                <span className="text-[8px] font-black text-gray-500 uppercase tracking-[0.2em]">
                                    Share to Market:
                                </span>

                                <div className="flex gap-2">
                                    {/* SHARE WHATSAPP */}
                                    <a
                                        // href={`https://wa.me{company.telepon.replace(/[^0-9]/g, '')}?text=Halo, saya melihat stok ${company.stock_ready_caption} di Digestex. Apakah masih tersedia?`}
                                        href={`https://wa.me/628129928939/g, '')}?text=Halo, saya melihat stok ${company.stock_ready_caption} di Digestex. Apakah masih tersedia?`}
                                        target="_blank"
                                        rel="noopener noreferrer" // <--- WAJIB: Mencegah browser memblokir tab baru
                                        className="bg-[#25D366] text-white px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-lg flex items-center gap-2"
                                    >
                                        <i className="fab fa-whatsapp text-sm"></i>
                                        Order Now
                                    </a>

                                    {/* SHARE LINK (COPY TO CLIPBOARD) */}
                                    <button
                                        onClick={() => {
                                            navigator.clipboard.writeText(
                                                window.location.href,
                                            );
                                            alert(
                                                "Profile Link Copied to Clipboard! Ready to share.",
                                            );
                                        }}
                                        className="h-10 w-10 bg-blue-500/10 border border-blue-500/20 rounded-xl flex items-center justify-center text-blue-400 hover:bg-blue-500 hover:text-white transition-all shadow-lg"
                                        title="Copy Profile Link"
                                    >
                                        <i className="fas fa-link text-xs"></i>
                                    </button>
                                </div>

                                <div className="flex-1 md:text-right">
                                    <p className="text-[7px] text-gray-600 font-black uppercase tracking-widest italic">
                                        * Boost your global reach by sharing
                                        your verified manufacturing status.
                                    </p>
                                </div>
                            </div>
                        </div>
                    )}

                    {/* SECTION PREMIUM CONTENT (Photo & Gallery) */}
                    {company.membership_type === "gold_member" && (
                        <div className="mt-12 space-y-10 animate-in slide-in-from-bottom duration-700">
                            {/* PHOTO PERUSAHAAN / FACTORY VIEW */}
                            <div className="bg-white/5 border border-white/10 rounded-[50px] overflow-hidden">
                                <img
                                    src={
                                        company.photo_url ||
                                        "/images/factory-placeholder.jpg"
                                    }
                                    className="w-full h-[400px] object-cover opacity-80 hover:opacity-100 transition-all duration-700"
                                    alt="Factory Profile"
                                />
                                <div className="p-8 bg-gradient-to-t from-[#0a192f] to-transparent mt-[-100px] relative z-10">
                                    <h3 className="text-xl font-black italic uppercase italic tracking-tighter">
                                        Factory & Operational Overview
                                    </h3>
                                </div>
                            </div>
                            {/* Photo direktur / ceo */}
                            {/* SECTION BOARD OF DIRECTORS (FACILITATING 2 DIRECTORS) */}
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                                {/* DIREKTUR 1 (UTAMA) */}
                                <div className="flex items-center gap-6 bg-white/5 p-6 rounded-[35px] border border-white/10 group hover:bg-white/10 transition-all">
                                    <div className="w-24 h-24 rounded-2xl overflow-hidden border border-yellow-500/20 flex-shrink-0 shadow-xl">
                                        <img
                                            src={
                                                company.photo_pimpinan ||
                                                "/images/ceo-placeholder.jpg"
                                            }
                                            className="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500"
                                        />
                                    </div>
                                    <div>
                                        <p className="text-yellow-500 text-[7px] font-black uppercase tracking-[0.4em] mb-1">
                                            President Director
                                        </p>
                                        <h3 className="text-sm font-black italic uppercase text-white leading-tight">
                                            {company.pimpinan}
                                        </h3>
                                    </div>
                                </div>

                                {/* DIREKTUR 2 (OPERASIONAL) - HANYA TAMPIL JIKA DATA ADA */}
                                {company.pimpinan_2 && (
                                    <div className="flex items-center gap-6 bg-white/5 p-6 rounded-[35px] border border-white/10 group hover:bg-white/10 transition-all">
                                        <div className="w-24 h-24 rounded-2xl overflow-hidden border border-blue-500/20 flex-shrink-0 shadow-xl">
                                            <img
                                                src={
                                                    company.photo_pimpinan_2 ||
                                                    "/images/coo-placeholder.jpg"
                                                }
                                                className="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500"
                                            />
                                        </div>
                                        <div>
                                            <p className="text-blue-400 text-[7px] font-black uppercase tracking-[0.4em] mb-1">
                                                Operations Director
                                            </p>
                                            <h3 className="text-sm font-black italic uppercase text-white leading-tight">
                                                {company.pimpinan_2}
                                            </h3>
                                        </div>
                                    </div>
                                )}
                            </div>

                            {/* Batas photo direktur */}

                            {/* CATALOG DOWNLOAD & SALES KIT */}
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div className="bg-yellow-500 p-10 rounded-[40px] flex flex-col justify-between items-start group hover:bg-white transition-all duration-500">
                                    <i className="fas fa-file-pdf text-4xl text-[#0a192f] mb-6"></i>
                                    <div>
                                        <h3 className="text-[#0a192f] text-2xl font-black uppercase italic leading-none mb-2">
                                            Download Catalog
                                        </h3>
                                        <p className="text-[#0a192f]/60 text-[10px] font-bold uppercase tracking-widest mb-6">
                                            Sales Kit & Technical Specification
                                            PDF
                                        </p>
                                        <a
                                            href={company.catalog_url}
                                            download
                                            className="bg-[#0a192f] text-white px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:scale-105 transition-all inline-block"
                                        >
                                            Get Sales Kit{" "}
                                            <i className="fas fa-download ml-2"></i>
                                        </a>
                                    </div>
                                </div>

                                {/* PRODUCT SHOWCASE PREVIEW */}
                                <div className="bg-white/5 border border-white/10 p-10 rounded-[40px]">
                                    <h3 className="text-white text-xs font-black uppercase tracking-[0.4em] mb-6">
                                        Top Featured Products
                                    </h3>
                                    <div className="grid grid-cols-3 gap-4">
                                        {/* Contoh looping gambar produk */}
                                        {[1, 2, 3].map((i) => (
                                            <div
                                                key={i}
                                                className="aspect-square bg-white/10 rounded-2xl overflow-hidden border border-white/10 hover:border-yellow-500 transition-all"
                                            >
                                                <img
                                                    src={`/images/product-${i}.jpg`}
                                                    className="w-full h-full object-cover"
                                                />
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            </div>
                        </div>
                    )}

                    {/* Batas tambahan galery dn down load */}

                    {/* DEEP INTELLIGENCE GRID */}
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                        {/* DATA PUBLIK */}
                        <div className="md:col-span-2 space-y-8">
                            <div className="bg-white/5 border border-white/10 rounded-[40px] p-10">
                                <h2 className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em] mb-8">
                                    {isEn
                                        ? "Core Production"
                                        : "Produksi Utama"}
                                </h2>
                                <p className="text-3xl font-light italic leading-relaxed text-gray-300">
                                    "{company.produk || "-"}"
                                </p>
                            </div>

                            {/* LOGIKA PREMIUM LOCK PADA DETAIL */}
                            <div className="bg-white/5 border border-white/10 rounded-[40px] p-10 relative overflow-hidden">
                                {!auth.user.is_premium && (
                                    <div className="absolute inset-0 bg-[#0a192f]/60 backdrop-blur-md z-20 flex flex-col items-center justify-center text-center p-10">
                                        <i className="fas fa-lock text-yellow-500 text-3xl mb-4"></i>
                                        <h3 className="text-xl font-black uppercase italic mb-2">
                                            {isEn
                                                ? "Premium Intelligence Locked"
                                                : "Intelijen Premium Terkunci"}
                                        </h3>
                                        <p className="text-gray-400 text-sm mb-6 max-w-xs">
                                            {isEn
                                                ? "Detailed workforce, CEO, and market data are reserved for premium members."
                                                : "Data tenaga kerja, pimpinan, dan pasar ekspor khusus untuk anggota premium."}
                                        </p>
                                        <button
                                            onClick={() =>
                                                router.post(
                                                    route("premium.request"),
                                                    {
                                                        company_name:
                                                            company.nama_perusahaan,
                                                    },
                                                )
                                            }
                                            className="bg-yellow-500 text-[#0a192f] px-8 py-3 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-white transition-all shadow-2xl"
                                        >
                                            {isEn
                                                ? "Request Access"
                                                : "Ajukan Akses"}
                                        </button>
                                    </div>
                                )}

                                <h2 className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em] mb-8">
                                    {isEn
                                        ? "Operational Intelligence"
                                        : "Intelijen Operasional"}
                                </h2>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-10">
                                    <div>
                                        <label className="text-[10px] font-black text-gray-500 uppercase tracking-widest block mb-2">
                                            {isEn
                                                ? "CEO / Director"
                                                : "Pimpinan"}
                                        </label>
                                        <p className="text-xl font-bold">
                                            {company.pimpinan || "-"}
                                        </p>
                                    </div>
                                    <div>
                                        <label className="text-[10px] font-black text-gray-500 uppercase tracking-widest block mb-2">
                                            {isEn
                                                ? "Workforce"
                                                : "Tenaga Kerja"}
                                        </label>
                                        <p className="text-xl font-bold">
                                            {company.tenaga_kerja || "-"}
                                        </p>
                                    </div>
                                    <div className="md:col-span-2">
                                        <label className="text-[10px] font-black text-gray-500 uppercase tracking-widest block mb-2">
                                            {isEn
                                                ? "Export Markets"
                                                : "Pasar Ekspor"}
                                        </label>
                                        <p className="text-xl font-bold text-blue-400 uppercase italic">
                                            {company.pasar_ekspor || "-"}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {/* Penempatan officil verified bdge */}
                        {/* OFFICIAL VERIFIED BADGE */}
                        <div
                            className={`mb-6 p-6 rounded-[30px] border transition-all duration-700 ${
                                company.status_verifikasi === "verified"
                                    ? "bg-emerald-500/10 border-emerald-500/30 shadow-[0_0_20px_rgba(16,185,129,0.1)]"
                                    : "bg-white/5 border-white/10"
                            }`}
                        >
                            <div className="flex items-center gap-4">
                                <div
                                    className={`h-12 w-12 rounded-2xl flex items-center justify-center shadow-lg ${
                                        company.status_verifikasi === "verified"
                                            ? "bg-emerald-500"
                                            : "bg-gray-700"
                                    }`}
                                >
                                    <i
                                        className={`fas ${company.status_verifikasi === "verified" ? "fa-shield-check text-white" : "fa-clock text-gray-400"} text-xl`}
                                    ></i>
                                </div>
                                <div>
                                    <h4
                                        className={`text-[10px] font-black uppercase tracking-widest ${
                                            company.status_verifikasi ===
                                            "verified"
                                                ? "text-emerald-500"
                                                : "text-gray-500"
                                        }`}
                                    >
                                        {company.status_verifikasi ===
                                        "verified"
                                            ? "8-Digit Verified"
                                            : "Audit Pending"}
                                    </h4>
                                    <p className="text-white text-[9px] font-bold uppercase italic mt-1">
                                        {company.status_verifikasi ===
                                        "verified"
                                            ? "Official Industry Data"
                                            : "Under Verification"}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {/* SIDEBAR KONTAK */}
                        <div className="space-y-6">
                            <div className="bg-white/5 border border-white/10 rounded-[40px] p-8">
                                <h2 className="text-yellow-500 text-[10px] font-black uppercase tracking-[0.4em] mb-6">
                                    {isEn ? "Contact" : "Kontak"}
                                </h2>
                                <div className="space-y-4">
                                    <div className="flex items-center gap-4">
                                        <i className="fas fa-phone text-gray-500 text-sm"></i>
                                        <span className="text-sm font-bold">
                                            {company.telepon || "-"}
                                        </span>
                                    </div>
                                    <div className="flex items-center gap-4">
                                        <i className="fas fa-envelope text-gray-500 text-sm"></i>
                                        <span className="text-sm font-bold truncate">
                                            {company.email_web || "-"}
                                        </span>
                                    </div>
                                    <div className="flex items-center gap-4">
                                        <i className="fas fa-map-marker-alt text-gray-500 text-sm"></i>
                                        <span className="text-sm font-bold">
                                            {company.city}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
