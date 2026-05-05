import { Link } from "@inertiajs/react";

export default function AdsBanner() {
    // Data ini nanti bisa ditarik dari Database agar lebih dinamis
    const adData = {
        partnerName: "Centric Software",
        title: "The World's #1 PLM Solution",
        subtitle:
            "Accelerate Product Innovation for Indonesian Textile Leaders",
        buttonText: "Request Demo",
        imagePath: "/images/partners/centric-banner-bg.jpg", // Opsional jika pakai background image
    };

    return (
        <div className="container mx-auto px-6 mb-20">
            <div className="relative group overflow-hidden rounded-[40px] border border-white/10 bg-[#0c1e35] shadow-2xl transition-all duration-500 hover:border-yellow-500/30">
                {/* Efek Cahaya Dekoratif (Glow Effect) */}
                <div className="absolute -top-24 -right-24 w-80 h-80 bg-yellow-500/10 blur-[100px] rounded-full group-hover:bg-yellow-500/20 transition-all duration-700"></div>
                <div className="absolute -bottom-24 -left-24 w-80 h-80 bg-blue-500/10 blur-[100px] rounded-full"></div>

                <div className="relative z-10 flex flex-col md:flex-row items-center justify-between p-10 md:p-16 gap-10">
                    {/* Sisi Kiri: Teks Strategis */}
                    <div className="max-w-2xl space-y-6 text-center md:text-left">
                        <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-yellow-500/10 border border-yellow-500/20">
                            <span className="relative flex h-2 w-2">
                                <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                                <span className="relative inline-flex rounded-full h-2 w-2 bg-yellow-500"></span>
                            </span>
                            <span className="text-[10px] font-black tracking-widest text-yellow-500 uppercase">
                                Global Technology Partner
                            </span>
                        </div>

                        <h2 className="text-3xl md:text-5xl font-black leading-tight uppercase tracking-tighter">
                            {adData.title} <br />
                            <span className="text-yellow-500 italic font-serif normal-case tracking-normal">
                                by {adData.partnerName}
                            </span>
                        </h2>

                        <p className="text-gray-400 text-sm md:text-lg font-light leading-relaxed max-w-lg">
                            {adData.subtitle}
                        </p>
                    </div>

                    {/* Sisi Kanan: Aksi Utama */}
                    <div className="flex flex-col items-center gap-4">
                        <button className="bg-white text-[#0a192f] px-10 py-5 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-yellow-500 transition-all duration-300 shadow-xl shadow-white/5">
                            {adData.buttonText}
                        </button>
                        <span className="text-[9px] text-gray-500 font-bold uppercase tracking-[0.3em]">
                            Limited Partnership 2026
                        </span>
                    </div>
                </div>

                {/* Garis Aksen Emas di Bawah */}
                <div className="absolute bottom-0 left-0 w-0 h-[2px] bg-yellow-500 group-hover:w-full transition-all duration-700"></div>
            </div>
        </div>
    );
}
