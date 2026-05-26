import React from "react";
import { Link, Head } from "@inertiajs/react";

export default function JoinUs({ auth }) {
    const isEn = auth?.user?.locale === "en";

    const waMessage = encodeURIComponent(
        isEn
            ? "Hello DigestexGlobal, we are interested in registering our company for enterprise access and the industrial intelligence platform."
            : "Halo DigestexGlobal, kami tertarik mendaftarkan perusahaan untuk enterprise access dan industrial intelligence platform.",
    );

    return (
        <div className="bg-[#0a192f] min-h-screen text-white selection:bg-blue-500 selection:text-white">
            <Head>
                <title>
                    {isEn
                        ? "Enterprise Access | DigestexGlobal"
                        : "Akses Enterprise | DigestexGlobal"}
                </title>
                <meta
                    property="og:title"
                    content={
                        isEn
                            ? "Join the Future of Industrial Intelligence"
                            : "Masa Depan Intelijen Industri"
                    }
                />
                <meta
                    property="og:description"
                    content={
                        isEn
                            ? "Connect your company to verified trade visibility, manufacturing intelligence, and enterprise-grade digital access."
                            : "Hubungkan perusahaan Anda ke visibilitas perdagangan terverifikasi, intelijen manufaktur, dan akses digital kelas enterprise."
                    }
                />
                <meta property="og:image" content="/images/digestex-og.png" />
                <meta property="og:type" content="website" />
                <meta name="twitter:card" content="summary_large_image" />
            </Head>

            {/* BACK */}
            <div className="fixed top-8 left-8 z-[100]">
                <Link
                    href={route("home")}
                    className="flex items-center gap-3 group bg-white/5 backdrop-blur-md border border-white/10 px-6 py-3 rounded-2xl hover:bg-blue-600 transition-all duration-500 shadow-xl"
                >
                    <i className="fas fa-arrow-left text-[10px] group-hover:-translate-x-1 transition-transform"></i>
                    <span className="text-[10px] font-black uppercase tracking-[0.2em] italic">
                        {isEn ? "Back to Platform" : "Kembali ke Platform"}
                    </span>
                </Link>
            </div>

            {/* HERO */}
            <div className="relative pt-32 pb-20 px-6 overflow-hidden text-center">
                <div className="max-w-5xl mx-auto relative z-10">
                    <h4 className="text-blue-500 text-[10px] font-black uppercase tracking-[0.5em] mb-6 animate-pulse">
                        {isEn
                            ? "Enterprise Access For Industrial Growth"
                            : "Akses Enterprise Untuk Pertumbuhan Industri"}
                    </h4>

                    <h1 className="text-5xl md:text-7xl font-black uppercase italic tracking-tighter leading-none mb-8">
                        {isEn ? "Build Visibility." : "Bangun Visibilitas."}
                        <br />
                        <span className="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-emerald-400">
                            {isEn
                                ? "Connect Capacity."
                                : "Hubungkan Kapasitas."}
                        </span>
                    </h1>

                    <p className="text-slate-300 text-sm md:text-base font-medium leading-relaxed max-w-3xl mx-auto">
                        {isEn
                            ? "Connect your company to industrial intelligence, production capacity visibility, and verified digital business access."
                            : "Hubungkan perusahaan Anda ke ekosistem intelijen industri, visibilitas kapasitas produksi, dan akses bisnis digital terverifikasi."}
                    </p>
                </div>
            </div>

            {/* VALUE PROPOSITIONS */}
            <div className="max-w-7xl mx-auto px-6 py-20 grid grid-cols-1 md:grid-cols-3 gap-8">
                {[
                    {
                        icon: "fa-chart-line",
                        color: "blue",
                        title: isEn
                            ? "Trade Visibility Network"
                            : "Jaringan Visibilitas Perdagangan",
                        desc: isEn
                            ? "Expand exposure to buyers, suppliers, and industrial markets through verified digital visibility."
                            : "Perluas eksposur ke buyer, pemasok, dan pasar industri melalui visibilitas digital terverifikasi.",
                    },
                    {
                        icon: "fa-link",
                        color: "emerald",
                        title: isEn
                            ? "Enterprise Connectivity"
                            : "Konektivitas Enterprise",
                        desc: isEn
                            ? "Build direct business connections with manufacturers, suppliers, and industrial partners."
                            : "Bangun koneksi bisnis langsung dengan manufaktur, pemasok, dan mitra industri.",
                    },
                    {
                        icon: "fa-industry",
                        color: "yellow",
                        title: isEn
                            ? "Verified Capacity Intelligence"
                            : "Intelijen Kapasitas Terverifikasi",
                        desc: isEn
                            ? "Showcase verified production capacity, operational readiness, and manufacturing visibility."
                            : "Tampilkan kapasitas produksi, kesiapan operasional, dan visibilitas manufaktur terverifikasi.",
                    },
                ].map((item, idx) => (
                    <div
                        key={idx}
                        className="bg-white/5 border border-white/10 p-10 rounded-[40px] hover:border-white/20 transition-all"
                    >
                        <div className="h-14 w-14 bg-white/10 rounded-2xl flex items-center justify-center mb-8 text-white shadow-lg">
                            <i className={`fas ${item.icon} text-xl`}></i>
                        </div>
                        <h3 className="text-lg font-black uppercase italic mb-4">
                            {item.title}
                        </h3>
                        <p className="text-slate-400 text-[12px] uppercase font-bold leading-relaxed tracking-wide">
                            {item.desc}
                        </p>
                    </div>
                ))}
            </div>

            {/* CTA */}
            <div className="max-w-4xl mx-auto px-6 py-24 text-center">
                <div className="bg-gradient-to-br from-blue-600 to-blue-900 p-16 rounded-[60px] shadow-2xl relative overflow-hidden">
                    <h2 className="text-white text-3xl font-black uppercase italic mb-4">
                        {isEn
                            ? "Activate Enterprise Access"
                            : "Aktifkan Akses Enterprise"}
                    </h2>
                    <p className="text-blue-100 text-sm max-w-2xl mx-auto mb-10 font-medium leading-relaxed">
                        {isEn
                            ? "Register your company to unlock industrial visibility, digital connectivity, and premium operational access."
                            : "Daftarkan perusahaan Anda untuk membuka visibilitas industri, konektivitas digital, dan akses operasional premium."}
                    </p>

                    <a
                        href={`https://wa.me/628129928939?text=${waMessage}`}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="inline-block bg-white text-blue-900 px-12 py-5 rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] hover:bg-yellow-500 transition-all shadow-xl"
                    >
                        {isEn
                            ? "Register Company Access"
                            : "Daftarkan Akses Perusahaan"}
                    </a>
                </div>
            </div>

            {/* TICKER */}
            <div className="fixed bottom-0 left-0 w-full z-50">
                <div className="bg-[#0a192f]/80 backdrop-blur-xl border-t border-blue-500/30 py-4 shadow-[0_-20px_50px_rgba(0,0,0,0.5)]">
                    <div className="max-w-7xl mx-auto px-6 flex items-center gap-8">
                        <div className="flex items-center gap-2 bg-blue-600 px-4 py-1.5 rounded-full whitespace-nowrap">
                            <span className="h-1.5 w-1.5 rounded-full bg-white animate-ping"></span>
                            <span className="text-[8px] font-black uppercase tracking-widest">
                                {isEn
                                    ? "Industrial Exchange Preview"
                                    : "Preview Bursa Industri"}
                            </span>
                        </div>

                        <div className="flex-1 overflow-hidden whitespace-nowrap relative">
                            <div className="flex gap-12 animate-marquee-fast">
                                {[1, 2, 3].map((i) => (
                                    <React.Fragment key={i}>
                                        <span className="text-blue-400 text-[10px] font-black uppercase tracking-widest italic">
                                            Yarn Capacity
                                        </span>
                                        <span className="text-white text-[10px] font-black italic">
                                            15,000 KG
                                        </span>
                                        <span className="text-emerald-500 text-[10px] font-black italic">
                                            Verified
                                        </span>

                                        <span className="text-blue-400 text-[10px] font-black uppercase tracking-widest italic">
                                            Fabric Output
                                        </span>
                                        <span className="text-white text-[10px] font-black italic">
                                            8,500 KG
                                        </span>
                                        <span className="text-emerald-500 text-[10px] font-black italic">
                                            Live Visibility
                                        </span>
                                    </React.Fragment>
                                ))}
                            </div>
                        </div>
                    </div>
                </div>

                <style
                    dangerouslySetInnerHTML={{
                        __html: `
                            @keyframes marquee-fast {
                                0% { transform: translateX(0); }
                                100% { transform: translateX(-33.3%); }
                            }
                            .animate-marquee-fast {
                                display: flex;
                                animation: marquee-fast 20s linear infinite;
                            }
                        `,
                    }}
                />
            </div>

            {/* FLOATING WA */}
            <div className="fixed bottom-32 right-8 z-[100] group">
                <div className="absolute bottom-full right-0 mb-4 w-48 opacity-0 group-hover:opacity-100 transition-all duration-500 pointer-events-none">
                    <div className="bg-white text-[#0a192f] text-[10px] font-black uppercase tracking-widest p-4 rounded-2xl shadow-2xl relative">
                        {isEn ? "Enterprise Support" : "Dukungan Enterprise"}
                        <div className="absolute top-full right-6 w-3 h-3 bg-white rotate-45 -translate-y-1.5"></div>
                    </div>
                </div>

                <a
                    href={`https://wa.me/628129928939?text=${waMessage}`}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="h-16 w-16 bg-[#25D366] rounded-full flex items-center justify-center text-white text-2xl shadow-[0_10_40px_rgba(37,211,102,0.4)] hover:scale-110 hover:rotate-12 transition-all duration-300 relative"
                >
                    <i className="fab fa-whatsapp"></i>
                    <span className="absolute -top-1 -right-1 h-4 w-4 bg-red-500 rounded-full border-2 border-[#0a192f] animate-pulse"></span>
                </a>
            </div>
        </div>
    );
}
