import React from "react";

export default function GlobalEcosystemHero({ isEn }) {
    return (
        <section className="relative overflow-hidden">
            {/* Background Globe */}
            <img
                src="/images/home_digestex.png"
                alt=""
                className="absolute inset-0 h-full w-full object-cover"
            />

            {/* Overlay */}
            <div className="absolute inset-0 bg-[#020b1a]/40" />

            {/* Content */}
            <div className="relative z-10 mx-auto max-w-7xl px-6 py-20 text-center">
                <span className="text-sm font-black uppercase tracking-[0.35em] text-amber-400">
                    DIGESTEX
                </span>

                <h1 className="mt-5 text-4xl font-black uppercase leading-tight text-white sm:text-5xl md:text-6xl lg:text-7xl">
                    The One-Stop
                    <br />
                    Textile Industry
                    <span className="block text-amber-400">Ecosystem</span>
                </h1>

                <p className="mx-auto mt-7 max-w-3xl text-base leading-7 text-slate-200 sm:text-lg md:text-xl">
                    {isEn
                        ? "Connecting the textile industry from upstream to downstream — together with the technologies, solutions, services, intelligence, and supporting industries that keep the industry moving."
                        : "Menghubungkan industri tekstil dari hulu sampai hilir — bersama teknologi, solusi, layanan, intelligence, dan industri pendukung yang menjaga industri terus bergerak."}
                </p>

                <p className="mt-8 text-sm font-bold text-white sm:text-base">
                    {isEn
                        ? "Built in Indonesia. Connected to the Global Textile Industry."
                        : "Dibangun di Indonesia. Terhubung dengan Industri Tekstil Global."}
                </p>
            </div>
        </section>
    );
}
