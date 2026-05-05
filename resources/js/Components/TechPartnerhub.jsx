import { Link } from "@inertiajs/react";

export default function TechPartnerHub({ isEn }) {
    const supportLogos = [
        { name: "Kemendag", src: "/images/kemendag_logo.png" },
        { name: "Kemenperin", src: "/images/kemenperin_logo.png" },
        { name: "API Jakarta", src: "/images/logo_api_digestex2.png" },
        { name: "US Embassy", src: "/images/us_embassy_logo.png" },
    ];
    return (
        <section className="bg-[#0a192f] py-24 border-t border-white/5 overflow-hidden">
            <div className="max-w-7xl mx-auto px-6">
                {/* SLOT UTAMA: EPSON (The Bintang Utama) */}
                <div className="flex flex-col lg:flex-row items-center gap-16 bg-white rounded-[60px] p-12 lg:p-20 border border-gray-200 shadow-[0_40px_100px_-20px_rgba(0,0,0,0.1)] mb-20">
                    <div className="lg:w-1/2">
                        <div className="flex items-center gap-4 mb-8">
                            <img
                                src="/images/epson_logo.png"
                                className="h-8 w-auto"
                                alt="Epson"
                            />
                            <span className="h-4 w-px bg-gray-300"></span>
                            <span className="text-blue-600 font-black text-[10px] uppercase tracking-widest italic">
                                {isEn
                                    ? "Strategic Technology Leader"
                                    : "Pemimpin Teknologi Strategis"}
                            </span>
                        </div>
                        <h3 className="text-5xl md:text-6xl font-black text-[#0a192f] leading-none tracking-tighter uppercase italic mb-8">
                            Sustainable <br />{" "}
                            <span className="text-blue-600">
                                Digital Precision
                            </span>
                        </h3>
                        <p className="text-gray-500 text-lg font-light leading-relaxed italic mb-10">
                            {isEn
                                ? "Leading the green revolution in Indonesia's textile industry with Epson's world-class digital printing precision."
                                : "Memimpin revolusi hijau di industri tekstil Indonesia dengan presisi cetak digital kelas dunia dari Epson."}
                        </p>
                        <Link
                            href={route("green.tech.hub")}
                            className="bg-[#0a192f] text-white px-10 py-5 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-blue-600 transition-all shadow-xl inline-block"
                        >
                            {isEn
                                ? "Explore Epson Solutions"
                                : "Pelajari Solusi Epson"}
                        </Link>
                    </div>

                    <div className="lg:w-1/2 w-full">
                        <div className="relative rounded-[40px] overflow-hidden shadow-2xl bg-white p-4 border border-gray-100">
                            <img
                                src="/images/epson1.jpeg"
                                className="w-full h-auto rounded-[30px]"
                                alt="Epson Machine"
                            />
                        </div>
                    </div>
                </div>

                {/* LOGO GRID: Mitra Pendukung & Regulator */}
                <div className="pt-10 border-t border-gray-100">
                    <p className="text-center text-[10px] font-black uppercase tracking-[0.4em] text-gray-400 mb-10">
                        {isEn
                            ? "In Collaboration With"
                            : "Berkolaborasi Dengan"}
                    </p>
                    <div className="flex flex-wrap justify-center items-center gap-12 lg:gap-20 opacity-40 hover:opacity-100 transition-opacity grayscale hover:grayscale-0">
                        {supportLogos.map((logo, idx) => (
                            <img
                                key={idx}
                                src={logo.src}
                                alt={logo.name}
                                className="h-10 md:h-12 w-auto object-contain transition-all hover:scale-110"
                            />
                        ))}
                    </div>
                </div>
            </div>
        </section>
    );
}
