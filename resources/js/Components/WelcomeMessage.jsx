import React from "react";

export default function WelcomeMessage({ isEn }) {
    return (
        <div className="mb-12 relative overflow-hidden rounded-[50px] bg-gradient-to-br from-[#0d1d36] to-[#0a192f] border border-blue-500/20 p-12 shadow-2xl animate-in fade-in slide-in-from-top-4 duration-1000">
            {/* Background grid */}
            <div
                className="absolute inset-0 opacity-[0.05] pointer-events-none"
                style={{
                    backgroundImage:
                        "radial-gradient(circle, #ffffff 1px, transparent 1px)",
                    backgroundSize: "30px 30px",
                }}
            ></div>

            <div className="relative z-10">
                {/* Header badge */}
                <div className="flex items-center gap-4 mb-8 flex-wrap">
                    <span className="bg-blue-600 text-white text-[8px] font-black px-4 py-1.5 rounded-full uppercase tracking-[0.3em] shadow-lg">
                        Corporate Intelligence Center
                    </span>

                    <div className="h-px w-20 bg-white/10"></div>

                    <span className="text-blue-300/80 text-[8px] font-black uppercase tracking-[0.3em] italic">
                        Independent Industrial Platform
                    </span>
                </div>

                {/* Main heading */}
                <h1 className="text-white text-3xl md:text-5xl font-black italic uppercase tracking-tighter leading-tight mb-8 max-w-4xl">
                    {isEn
                        ? "Integrated Market & Industrial Intelligence"
                        : "Pusat Intelijen Pasar & Industri Terintegrasi"}
                </h1>

                {/* Main description */}
                <p className="text-gray-400 text-sm md:text-lg leading-relaxed font-medium italic max-w-5xl">
                    {isEn
                        ? "DigestexGlobal delivers integrated industrial visibility, supply intelligence, and market insight to support stronger business decisions across local enterprises, IKM manufacturers, regional trade ecosystems, and global market networks."
                        : "DigestexGlobal menghadirkan visibilitas industri terintegrasi, intelijen rantai pasok, dan wawasan pasar untuk mendukung keputusan bisnis yang lebih kuat bagi perusahaan lokal, manufaktur IKM, ekosistem perdagangan regional, hingga jaringan pasar global."}
                </p>

                {/* Footer indicators */}
                <div className="mt-10 flex items-center gap-6 flex-wrap">
                    <div className="flex -space-x-3">
                        {/* Digestex Node */}
                        <div className="h-12 w-12 bg-blue-600 rounded-full border-4 border-[#0a192f] flex items-center justify-center shadow-xl text-white font-black text-[10px]">
                            DX
                        </div>

                        {/* Intelligence Node */}
                        <div className="h-12 w-12 bg-white rounded-full border-4 border-[#0a192f] flex items-center justify-center shadow-xl text-[#0a192f] font-black text-[8px]">
                            INT
                        </div>
                    </div>

                    <div className="text-[9px] font-black uppercase tracking-widest text-emerald-500 animate-pulse">
                        • Cross-Market Intelligence Active
                    </div>
                </div>
            </div>
        </div>
    );
}
