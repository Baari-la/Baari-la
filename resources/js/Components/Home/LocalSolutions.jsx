import { Link } from "@inertiajs/react";

export default function LocalSolutions() {
    return (
        <section className="container mx-auto px-6 py-12 mb-20">
            <div className="bg-gradient-to-b from-white/10 to-transparent p-10 rounded-[50px] border border-white/10 shadow-2xl">
                <div className="flex flex-col md:flex-row justify-between items-center gap-10 text-center md:text-left">
                    <div className="max-w-sm">
                        <h2 className="text-3xl font-black uppercase leading-none text-white tracking-tighter">
                            Solusi Strategis <br />
                            <span className="text-yellow-500">
                                Untuk Anggota API
                            </span>
                        </h2>
                        <p className="text-gray-500 text-[10px] mt-4 uppercase tracking-[0.3em] font-black">
                            Supporting Local Industry Growth
                        </p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6 w-full md:w-auto">
                        {/* 1. Bursa Bahan */}
                        <Link
                            href={route("inventory.index")}
                            className="bg-[#0a192f] p-8 rounded-[35px] border border-white/5 hover:border-yellow-500/30 transition-all duration-500 group hover:-translate-y-2 text-left block"
                        >
                            <div className="text-yellow-500 mb-4 text-xl">
                                <i className="fas fa-shopping-cart"></i>
                            </div>
                            <h4 className="font-black text-white text-xs uppercase mb-3 tracking-widest">
                                Bursa Bahan
                            </h4>
                            <p className="text-[10px] text-gray-500 leading-relaxed font-medium">
                                Akses bahan baku & sisa produksi antar anggota
                                secara transparan.
                            </p>
                        </Link>

                        {/* 2. Regulasi */}
                        <Link
                            href={route("regulation.index")}
                            className="bg-[#0a192f] p-8 rounded-[35px] border border-white/5 hover:border-yellow-500/30 transition-all duration-500 group hover:-translate-y-2 text-left block"
                        >
                            <div className="text-yellow-500 mb-4 text-xl">
                                <i className="fas fa-gavel"></i>
                            </div>
                            <h4 className="font-black text-white text-xs uppercase mb-3 tracking-widest">
                                Pusat Regulasi
                            </h4>
                            <p className="text-[10px] text-gray-500 leading-relaxed font-medium">
                                Update kebijakan industri & bantuan perizinan
                                NIB/Ekspor terintegrasi.
                            </p>
                        </Link>

                        {/* 3. Matchmaking */}
                        <Link
                            href={route("matchmaking.index")}
                            className="bg-[#0a192f] p-8 rounded-[35px] border border-white/5 hover:border-yellow-500/30 transition-all duration-500 group hover:-translate-y-2 text-left block"
                        >
                            <div className="text-yellow-500 mb-4 text-xl">
                                <i className="fas fa-handshake"></i>
                            </div>
                            <h4 className="font-black text-white text-xs uppercase mb-3 tracking-widest">
                                Matchmaking
                            </h4>
                            <p className="text-[10px] text-gray-500 leading-relaxed font-medium">
                                Hubungkan kapasitas pabrik lokal dengan jaringan
                                brand global.
                            </p>
                        </Link>
                    </div>
                </div>
            </div>
        </section>
    );
}
