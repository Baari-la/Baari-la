import { Link } from "@inertiajs/react";

export default function AdsBannerTestex() {
    return (
        <div className="container mx-auto px-6 mb-20">
            <div className="relative group overflow-hidden rounded-[40px] border border-white/10 bg-[#051622] shadow-2xl transition-all duration-500 hover:border-blue-500/30">
                {/* Efek Cahaya Biru Lab (Lab Blue Glow) */}
                <div className="absolute -top-24 -left-24 w-80 h-80 bg-blue-500/10 blur-[100px] rounded-full group-hover:bg-blue-500/20 transition-all duration-700"></div>

                <div className="relative z-10 flex flex-col md:flex-row items-center justify-between p-10 md:p-16 gap-10">
                    {/* Sisi Kiri: Otoritas Sertifikasi */}
                    <div className="max-w-2xl space-y-6 text-center md:text-left">
                        <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20">
                            <span className="text-[10px] font-black tracking-widest text-blue-400 uppercase">
                                Certification & Laboratory Partner
                            </span>
                        </div>

                        <h2 className="text-3xl md:text-5xl font-black leading-tight uppercase tracking-tighter">
                            Ensure Global Trust with <br />
                            <span className="text-blue-400 italic">
                                TESTEX Switzerland
                            </span>
                        </h2>

                        <p className="text-gray-400 text-sm md:text-lg font-light leading-relaxed max-w-lg">
                            Official OEKO-TEX® certification and textile testing
                            for Indonesian exporters. Meet the highest global
                            safety standards.
                        </p>
                    </div>

                    {/* Sisi Kanan: Aksi (Contact/Inquiry) */}
                    <div className="flex flex-col items-center gap-4">
                        <button className="bg-blue-600 text-white px-10 py-5 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-blue-500 transition-all duration-300 shadow-xl shadow-blue-500/20">
                            Book Certification
                        </button>
                        <span className="text-[9px] text-gray-500 font-bold uppercase tracking-[0.3em]">
                            Swiss Standard Excellence
                        </span>
                    </div>
                </div>

                {/* Garis Aksen Biru di Bawah */}
                <div className="absolute bottom-0 left-0 w-0 h-[2px] bg-blue-500 group-hover:w-full transition-all duration-700"></div>
            </div>
        </div>
    );
}
