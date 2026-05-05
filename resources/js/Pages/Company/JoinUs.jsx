import React from "react";
import { Link, Head } from "@inertiajs/react";

export default function JoinUs({ auth }) {
    const isEn = auth?.user?.locale === "en";
    return (
        <div className="bg-[#0a192f] min-h-screen text-white selection:bg-blue-500 selection:text-white">
            <Head>
                <title>Join the Future of Textile Intelligence</title>
                <meta
                    property="og:title"
                    content="Join the Future of Textile Intelligence"
                />
                <meta
                    property="og:description"
                    content="Hapus perantara, hubungkan pabrik Anda langsung ke buyer internasional. Powered by API Jakarta."
                />
                <meta property="og:image" content="/images/logo-api.png" />
                <meta property="og:type" content="website" />
                <meta name="twitter:card" content="summary_large_image" />
            </Head>
            {/* BACK TO HOME BUTTON */}
            <div className="fixed top-8 left-8 z-[100]">
                <Link
                    href={route("home")}
                    className="flex items-center gap-3 group bg-white/5 backdrop-blur-md border border-white/10 px-6 py-3 rounded-2xl hover:bg-blue-600 transition-all duration-500 shadow-xl"
                >
                    <i className="fas fa-arrow-left text-[10px] group-hover:-translate-x-1 transition-transform"></i>
                    <span className="text-[10px] font-black uppercase tracking-[0.2em] italic">
                        Back to Hub
                    </span>
                </Link>
            </div>

            {/* HERO SECTION */}
            <div className="relative pt-32 pb-20 px-6 overflow-hidden text-center">
                <div className="max-w-5xl mx-auto relative z-10">
                    <h4 className="text-blue-500 text-[10px] font-black uppercase tracking-[0.5em] mb-6 animate-pulse">
                        Exclusive Invitation for Textile Manufacturers & PMA
                    </h4>
                    <h1 className="text-5xl md:text-7xl font-black uppercase italic tracking-tighter leading-none mb-8">
                        Direct Access. <br />
                        <span className="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-emerald-400">
                            No Intermediaries.
                        </span>
                    </h1>

                    {/* Bilingual Description */}
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-8 text-left max-w-4xl mx-auto border-t border-white/10 pt-10">
                        <p className="text-gray-400 text-sm md:text-base font-medium italic leading-relaxed">
                            Hapus perantara, hubungkan pabrik Anda langsung ke
                            buyer internasional. Didukung oleh otoritas API
                            Jakarta dan presisi teknologi DigestexGlobal.
                        </p>
                        <p className="text-blue-300/60 text-sm md:text-base font-medium italic leading-relaxed border-l border-white/10 pl-8">
                            Eliminate middlemen, connect your factory directly
                            to international buyers. Powered by API Jakarta
                            authority and DigestexGlobal precision technology.
                        </p>
                    </div>
                </div>
            </div>

            {/* 3 VALUE PROPOSITIONS */}
            <div className="max-w-7xl mx-auto px-6 py-20 grid grid-cols-1 md:grid-cols-3 gap-8">
                {/* PILAR 1: BURSA */}
                <div className="bg-white/5 border border-white/10 p-10 rounded-[50px] hover:border-blue-500/30 transition-all">
                    <div className="h-14 w-14 bg-blue-600 rounded-2xl flex items-center justify-center mb-8 shadow-lg shadow-blue-600/20 text-white">
                        <i className="fas fa-chart-line text-xl"></i>
                    </div>
                    <h3 className="text-lg font-black uppercase italic mb-4">
                        Stock Ticker Broadcast
                    </h3>
                    <div className="space-y-4">
                        <p className="text-gray-500 text-[11px] italic leading-relaxed uppercase font-bold">
                            Produk ready stock Anda akan disiarkan di bursa
                            digital nasional yang dipantau oleh global buyers.
                        </p>
                        <p className="text-blue-400/50 text-[11px] italic leading-relaxed uppercase font-bold">
                            Your ready stock products will be broadcasted on the
                            national digital exchange monitored by global
                            buyers.
                        </p>
                    </div>
                </div>

                {/* PILAR 2: WHATSAPP */}
                <div className="bg-white/5 border border-white/10 p-10 rounded-[50px] hover:border-emerald-500/30 transition-all scale-105 shadow-2xl">
                    <div className="h-14 w-14 bg-emerald-500 rounded-2xl flex items-center justify-center mb-8 shadow-lg shadow-emerald-500/20 text-white">
                        <i className="fab fa-whatsapp text-2xl"></i>
                    </div>
                    <h3 className="text-lg font-black uppercase italic mb-4 text-emerald-400">
                        Direct B2B Inquiry
                    </h3>
                    <div className="space-y-4">
                        <p className="text-gray-300 text-[11px] italic leading-relaxed uppercase font-bold">
                            Terima permintaan pesanan langsung ke WhatsApp
                            pimpinan tanpa biaya perantara. 0% Komisi.
                        </p>
                        <p className="text-emerald-400/50 text-[11px] italic leading-relaxed uppercase font-bold">
                            Receive direct order inquiries to management's
                            WhatsApp without intermediary fees. 0% Commission.
                        </p>
                    </div>
                </div>

                {/* PILAR 3: VERIFIED */}
                <div className="bg-white/5 border border-white/10 p-10 rounded-[50px] hover:border-yellow-500/30 transition-all">
                    <div className="h-14 w-14 bg-yellow-500 rounded-2xl flex items-center justify-center mb-8 shadow-lg shadow-yellow-500/20 text-[#0a192f]">
                        <i className="fas fa-shield-check text-xl"></i>
                    </div>
                    <h3 className="text-lg font-black uppercase italic mb-4 text-yellow-500">
                        Global Trust Verified
                    </h3>
                    <div className="space-y-4">
                        <p className="text-gray-500 text-[11px] italic leading-relaxed uppercase font-bold">
                            Sertifikasi '8-Digit Verified' membangun kepercayaan
                            instan bagi investor dan buyer global.
                        </p>
                        <p className="text-yellow-500/50 text-[11px] italic leading-relaxed uppercase font-bold">
                            '8-Digit Verified' certification builds instant
                            trust for global investors and buyers.
                        </p>
                    </div>
                </div>
            </div>

            {/* CALL TO ACTION */}
            <div className="max-w-4xl mx-auto px-6 py-24 text-center">
                <div className="bg-gradient-to-br from-blue-600 to-blue-900 p-16 rounded-[60px] shadow-2xl relative overflow-hidden">
                    <h2 className="text-white text-3xl font-black uppercase italic mb-10">
                        Register Your Company <br />
                        <span className="text-blue-200">
                            Daftarkan Perusahaan Anda
                        </span>
                    </h2>
                    <a
                        href="https://wa.me am interested in registering my company to the DigestexGlobal Direct Export program."
                        className="inline-block bg-white text-blue-900 px-12 py-5 rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] hover:bg-yellow-500 transition-all shadow-xl"
                    >
                        Contact Strategic Admin
                    </a>
                </div>
            </div>
            {/* Mini Ticker */}
            {/* Tambahkan ini di bagian paling bawah sebelum penutup </div> di JoinUs.jsx */}
            <div className="fixed bottom-0 left-0 w-full z-50">
                <div className="bg-[#0a192f]/80 backdrop-blur-xl border-t border-blue-500/30 py-4 shadow-[0_-20px_50px_rgba(0,0,0,0.5)]">
                    <div className="max-w-7xl mx-auto px-6 flex items-center gap-8">
                        {/* Label Status */}
                        <div className="flex items-center gap-2 bg-blue-600 px-4 py-1.5 rounded-full whitespace-nowrap">
                            <span className="h-1.5 w-1.5 rounded-full bg-white animate-ping"></span>
                            <span className="text-[8px] font-black uppercase tracking-widest">
                                Live Exchange Preview
                            </span>
                        </div>

                        {/* Ticker Content (Gunakan data topStocks jika dikirim dari Controller) */}
                        <div className="flex-1 overflow-hidden whitespace-nowrap relative">
                            <div className="flex gap-12 animate-marquee-fast">
                                {[1, 2, 3].map((i) => (
                                    <React.Fragment key={i}>
                                        <span className="text-blue-400 text-[10px] font-black uppercase tracking-widest italic flex items-center gap-2">
                                            <i className="fas fa-chart-line text-[8px]"></i>
                                            Polyester DTY 150/48
                                        </span>
                                        <span className="text-white text-[10px] font-black italic">
                                            15,000 KG
                                        </span>
                                        <span className="text-emerald-500 text-[10px] font-black italic">
                                            Rp 18,500
                                        </span>

                                        <span className="text-blue-400 text-[10px] font-black uppercase tracking-widest italic flex items-center gap-2">
                                            <i className="fas fa-chart-line text-[8px]"></i>
                                            Cotton Combed 30s
                                        </span>
                                        <span className="text-white text-[10px] font-black italic">
                                            8,500 KG
                                        </span>
                                        <span className="text-emerald-500 text-[10px] font-black italic">
                                            Rp 42,000
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
            {/* FLOATING WHATSAPP ASSISTANT */}
            <div className="fixed bottom-32 right-8 z-[100] group">
                <div className="absolute bottom-full right-0 mb-4 w-48 opacity-0 group-hover:opacity-100 transition-all duration-500 pointer-events-none">
                    <div className="bg-white text-[#0a192f] text-[10px] font-black uppercase tracking-widest p-4 rounded-2xl shadow-2xl relative">
                        {isEn
                            ? "Direct Access to Admin"
                            : "Akses Langsung ke Admin"}
                        <div className="absolute top-full right-6 w-3 h-3 bg-white rotate-45 -translate-y-1.5"></div>
                    </div>
                </div>

                <a
                    href={`https://wa.me/628129928939?text=Halo Admin DigestexGlobal, saya tertarik untuk mendaftarkan perusahaan kami ke program Direct Export.`}
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
