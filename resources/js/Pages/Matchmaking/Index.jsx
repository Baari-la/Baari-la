import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, usePage } from "@inertiajs/react";

export default function Index() {
    const { auth } = usePage().props;
    const isEn = auth.locale === "en";

    return (
        <AuthenticatedLayout>
            <Head title={isEn ? "AI Matchmaking Hub" : "Pusat Perjodohan Bisnis"} />
            
            <div className="py-12 bg-[#0a192f] min-h-screen text-white">
                <div className="max-w-7xl mx-auto px-6 lg:px-8">
                    {/* HEADER SECTION */}
                    <div className="mb-12">
                        <span className="text-yellow-500 text-[10px] font-black uppercase tracking-[0.4em] mb-4 block">
                            {isEn ? "Advanced B2B Synergy" : "Sinergi B2B Tingkat Lanjut"}
                        </span>
                        <h1 className="text-5xl font-black uppercase tracking-tighter italic">
                            Business <span className="text-yellow-500">Matchmaking</span>
                        </h1>
                        <p className="text-gray-400 mt-4 max-w-2xl italic">
                            {isEn 
                                ? "Intelligent connection system between global technology providers and local textile manufacturers." 
                                : "Sistem koneksi cerdas antara penyedia teknologi global dan manufaktur tekstil lokal."}
                        </p>
                    </div>

                    {/* INTERACTIVE FILTERS */}
                    <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12 bg-white/5 p-8 rounded-[40px] border border-white/10">
                        <div className="space-y-2">
                            <label className="text-[10px] font-black uppercase tracking-widest text-gray-500">Category</label>
                            <select className="w-full bg-[#0a192f] border-white/10 rounded-xl text-xs font-bold p-3">
                                <option>{isEn ? "Technology (PLM/ERP)" : "Teknologi (PLM/ERP)"}</option>
                                <option>{isEn ? "Raw Material" : "Bahan Baku"}</option>
                            </select>
                        </div>
                        <div className="space-y-2">
                            <label className="text-[10px] font-black uppercase tracking-widest text-gray-500">Region</label>
                            <select className="w-full bg-[#0a192f] border-white/10 rounded-xl text-xs font-bold p-3">
                                <option>West Java</option>
                                <option>Central Java</option>
                            </select>
                        </div>
                        <div className="md:col-span-2 flex items-end">
                            <button className="w-full bg-yellow-500 text-[#0a192f] font-black py-3 rounded-xl uppercase text-[10px] tracking-widest hover:scale-105 transition">
                                {isEn ? "Find My Partner" : "Cari Mitra Saya"}
                            </button>
                        </div>
                    </div>

                    {/* MATCH RESULTS (CONTOH UNTUK DEMO) */}
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {/* CARD 1: CENTRIC SOFTWARE */}
                        <div className="group bg-white/5 border border-white/10 p-10 rounded-[50px] hover:border-yellow-500/50 transition">
                            <div className="flex justify-between items-start mb-6">
                                <div className="p-4 bg-white rounded-2xl">
                                    <img src="/images/partners/centric-logo.png" className="h-6 w-auto" alt="Centric" />
                                </div>
                                <span className="bg-green-500/20 text-green-500 px-4 py-1 rounded-full text-[9px] font-black uppercase">98% Match</span>
                            </div>
                            <h3 className="text-2xl font-black uppercase mb-2">Centric Software</h3>
                            <p className="text-xs text-gray-400 mb-8 uppercase tracking-widest leading-relaxed">
                                {isEn ? "Specialist in PLM & Digital Transformation for Apparel Industry." : "Spesialis PLM & Transformasi Digital untuk Industri Pakaian."}
                            </p>
                            <button className="bg-white/10 text-white w-full py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest group-hover:bg-yellow-500 group-hover:text-[#0a192f] transition">
                                {isEn ? "Request Connection" : "Ajukan Koneksi"}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}