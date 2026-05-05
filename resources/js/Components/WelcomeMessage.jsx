import React from "react";

export default function WelcomeMessage({ isEn }) {
    return (
        <div className="mb-12 relative overflow-hidden rounded-[50px] bg-gradient-to-br from-[#0d1d36] to-[#0a192f] border border-blue-500/20 p-12 shadow-2xl animate-in fade-in slide-in-from-top-4 duration-1000">
            {/* Ornamen Grid Halus agar terasa seperti Radar */}
            <div
                className="absolute inset-0 opacity-[0.05] pointer-events-none"
                style={{
                    backgroundImage:
                        "radial-gradient(circle, #ffffff 1px, transparent 1px)",
                    backgroundSize: "30px 30px",
                }}
            ></div>

            <div className="relative z-10">
                <div className="flex items-center gap-4 mb-8">
                    <span className="bg-blue-600 text-white text-[8px] font-black px-4 py-1.5 rounded-full uppercase tracking-[0.3em] shadow-lg">
                        Official Command Center
                    </span>
                    <div className="h-px w-20 bg-white/10"></div>
                    <span className="text-blue-300/80 text-[8px] font-black uppercase tracking-[0.3em] italic">
                        API Jakarta × DigestexGlobal
                    </span>
                </div>

                <h1 className="text-white text-3xl md:text-5xl font-black italic uppercase tracking-tighter leading-tight mb-8 max-w-4xl">
                    {isEn
                        ? "The Future of Textile Intelligence"
                        : "Selamat Datang di Pusat Kendali Industri Pertekstilan"}
                </h1>

                <p className="text-gray-400 text-sm md:text-lg leading-relaxed font-medium italic max-w-5xl">
                    {isEn
                        ? "API Jakarta is now powered by DigestexGlobal's precision technology, bringing 8-digit data transparency directly to your fingertips. Let us lead the textile industry dynamics today and into the future with accurate, integrated data."
                        : "API Jakarta kini diperkuat oleh teknologi presisi DigestexGlobal untuk menghadirkan transparansi data 8-digit ke dalam genggaman Anda. Mari memimpin dinamika industri tekstil masa kini dan kedepan dengan kekuatan data yang akurat dan terintegrasi."}
                </p>

                <div className="mt-10 flex items-center gap-6">
                    <div className="flex -space-x-3">
                        <div className="h-12 w-12 bg-white rounded-full border-4 border-[#0a192f] p-2 shadow-xl">
                            <img
                                src="/images/logo-api.png"
                                className="w-full h-full object-contain"
                                alt="API Jakarta"
                            />
                        </div>
                        <div className="h-12 w-12 bg-blue-600 rounded-full border-4 border-[#0a192f] flex items-center justify-center shadow-xl text-white font-black text-[8px]">
                            DX
                        </div>
                    </div>
                    <div className="text-[9px] font-black uppercase tracking-widest text-emerald-500 animate-pulse">
                        • Strategic Intelligence Active
                    </div>
                </div>
            </div>
        </div>
    );
}
