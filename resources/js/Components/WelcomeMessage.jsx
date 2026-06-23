import React from "react";

export default function WelcomeMessage({ isEn }) {
    return (
        <div className="mb-12 relative overflow-hidden rounded-[50px] bg-gradient-to-br from-[#0d1d36] via-[#102C57] to-[#0a192f] border border-cyan-500/20 p-10 lg:p-14 shadow-2xl">
            {/* Background Grid */}
            <div
                className="absolute inset-0 opacity-[0.04] pointer-events-none"
                style={{
                    backgroundImage:
                        "radial-gradient(circle, #ffffff 1px, transparent 1px)",
                    backgroundSize: "30px 30px",
                }}
            />

            {/* Glow Effects */}
            <div className="absolute -top-20 -right-20 w-72 h-72 bg-cyan-500/10 rounded-full blur-3xl"></div>
            <div className="absolute -bottom-20 -left-20 w-72 h-72 bg-amber-500/10 rounded-full blur-3xl"></div>

            <div className="relative z-10">
                {/* TOP BADGES */}
                <div className="flex flex-wrap items-center gap-4 mb-8">
                    <span className="inline-flex items-center gap-2 bg-cyan-500/10 border border-cyan-400/20 text-cyan-300 text-[10px] font-black px-4 py-2 rounded-full uppercase tracking-[0.25em]">
                        <span className="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                        Digestex Intelligence Network
                    </span>

                    <div className="hidden md:block h-px w-16 bg-white/10"></div>

                    <span className="text-slate-400 text-[10px] font-black uppercase tracking-[0.25em]">
                        Global Textile Ecosystem Platform
                    </span>
                </div>

                {/* MAIN TITLE */}
                <h1 className="text-white text-4xl lg:text-6xl font-black leading-tight tracking-tight max-w-5xl">
                    {isEn
                        ? "Global Textile Intelligence & Trade Ecosystem"
                        : "Ekosistem Intelijen & Perdagangan Tekstil Global"}
                </h1>

                {/* SUBTITLE */}
                <div className="mt-4 text-cyan-300 font-bold uppercase tracking-[0.25em] text-xs">
                    {isEn
                        ? "Trade Analytics • Market Intelligence • Industry Connectivity"
                        : "Analitik Perdagangan • Market Intelligence • Konektivitas Industri"}
                </div>

                {/* DESCRIPTION */}
                <p className="mt-8 text-slate-300 text-sm lg:text-lg leading-relaxed max-w-5xl">
                    {isEn
                        ? "Digestex connects manufacturers, traders, brands, suppliers, and industry stakeholders through integrated market intelligence, trade analytics, sourcing networks, logistics visibility, and industrial collaboration tools across local, regional, and global textile ecosystems."
                        : "Digestex menghubungkan manufaktur, trader, brand, pemasok, dan pelaku industri melalui market intelligence terintegrasi, analitik perdagangan, jaringan sourcing, visibilitas logistik, serta alat kolaborasi industri dalam ekosistem tekstil lokal, regional, dan global."}
                </p>

                {/* CAPABILITY CHIPS */}
                <div className="flex flex-wrap gap-3 mt-8">
                    <span className="px-3 py-2 rounded-full bg-white/5 border border-white/10 text-[11px] font-semibold text-slate-300">
                        🌎 Trade Analytics
                    </span>

                    <span className="px-3 py-2 rounded-full bg-white/5 border border-white/10 text-[11px] font-semibold text-slate-300">
                        🏭 Industry Directory
                    </span>

                    <span className="px-3 py-2 rounded-full bg-white/5 border border-white/10 text-[11px] font-semibold text-slate-300">
                        🤝 MOQ Matching Network
                    </span>

                    <span className="px-3 py-2 rounded-full bg-white/5 border border-white/10 text-[11px] font-semibold text-slate-300">
                        📦 RFQ Marketplace
                    </span>

                    <span className="px-3 py-2 rounded-full bg-white/5 border border-white/10 text-[11px] font-semibold text-slate-300">
                        🚢 Logistics Intelligence
                    </span>

                    <span className="px-3 py-2 rounded-full bg-white/5 border border-white/10 text-[11px] font-semibold text-slate-300">
                        📜 Regulation Center
                    </span>
                </div>

                {/* FOOTER STATUS */}
                <div className="mt-10 flex flex-wrap items-center gap-6">
                    <div className="flex -space-x-3">
                        <div className="h-12 w-12 bg-cyan-500 rounded-full border-4 border-[#0a192f] flex items-center justify-center shadow-xl text-white font-black text-[10px]">
                            DX
                        </div>

                        <div className="h-12 w-12 bg-amber-500 rounded-full border-4 border-[#0a192f] flex items-center justify-center shadow-xl text-[#0a192f] font-black text-[8px]">
                            AI
                        </div>

                        <div className="h-12 w-12 bg-white rounded-full border-4 border-[#0a192f] flex items-center justify-center shadow-xl text-[#0a192f] font-black text-[8px]">
                            B2B
                        </div>
                    </div>

                    <div className="text-[10px] font-black uppercase tracking-widest text-emerald-400 animate-pulse">
                        ● Global Intelligence Network Active
                    </div>
                </div>
            </div>
        </div>
    );
}
