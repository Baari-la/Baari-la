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
                        <div className="absolute top-0 right-0 p-10 opacity-10">
                            <i className="fas fa-industry text-9xl"></i>
                        </div>

                        <div className="relative z-10">
                            <div className="flex items-center gap-4 mb-6">
                                {company.membership_type === "gold_member" && (
                                    <span className="bg-yellow-500 text-[#0a192f] text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-tighter shadow-lg">
                                        Gold Member
                                    </span>
                                )}
                                <span className="text-gray-500 text-[10px] font-bold uppercase tracking-[0.3em]">
                                    {company.sektor}
                                </span>
                            </div>
                            <h1 className="text-4xl md:text-6xl font-black uppercase italic tracking-tighter leading-none mb-4">
                                {company.nama_perusahaan}
                            </h1>
                            <p className="text-gray-400 max-w-2xl italic">
                                {company.alamat_lengkap}
                            </p>
                        </div>
                    </div>

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
