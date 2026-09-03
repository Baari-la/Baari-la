import React from "react";
import { Link } from "@inertiajs/react";

export default function FinalEcosystemCTA({ isEn }) {
    return (
        <section className="relative overflow-hidden border-t border-white/5 py-28 lg:py-36">
            <div className="pointer-events-none absolute inset-0">
                <div className="absolute left-1/2 top-1/2 h-[500px] w-[700px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-yellow-500/10 blur-[140px]" />
            </div>

            <div className="relative mx-auto max-w-5xl px-6 text-center">
                <span className="text-xs font-black uppercase tracking-[0.4em] text-yellow-500">
                    {isEn
                        ? "JOIN THE DIGESTEX ECOSYSTEM"
                        : "BERGABUNG DENGAN EKOSISTEM DIGESTEX"}
                </span>

                <h2 className="mt-6 text-4xl font-black uppercase leading-tight tracking-tight text-white sm:text-5xl lg:text-6xl">
                    {isEn
                        ? "Build The Future Of The Connected Textile Industry"
                        : "Membangun Masa Depan Industri Tekstil Yang Terhubung"}
                </h2>

                <p className="mx-auto mt-8 max-w-3xl text-lg leading-8 text-slate-300 sm:text-xl">
                    {isEn
                        ? "DIGESTEX brings together industry capabilities, technologies, solutions, intelligence, business connectivity, and strategic partners within one connected textile industry ecosystem."
                        : "DIGESTEX mempertemukan kapabilitas industri, teknologi, solusi, intelligence, konektivitas bisnis, dan strategic partner dalam satu ekosistem industri tekstil yang terhubung."}
                </p>

                <p className="mx-auto mt-6 max-w-3xl text-base leading-7 text-slate-400">
                    {isEn
                        ? "Whether you are a manufacturer, supplier, technology provider, buyer, testing and certification organization, industry association, strategic partner, or ecosystem contributor, there is a place for you within the DIGESTEX ecosystem."
                        : "Baik sebagai manufacturer, supplier, technology provider, buyer, organisasi testing dan certification, asosiasi industri, strategic partner, maupun kontributor ekosistem, terdapat ruang bagi Anda di dalam ekosistem DIGESTEX."}
                </p>

                <div className="mt-10 flex flex-wrap justify-center gap-4">
                    <Link
                        href={route("ecosystem-partner.index")}
                        className="inline-flex items-center justify-center rounded-full bg-yellow-500 px-9 py-4 text-xs font-black uppercase tracking-[0.2em] text-[#0a192f] shadow-xl transition-all duration-300 hover:bg-yellow-400"
                    >
                        {isEn
                            ? "Join The DIGESTEX Ecosystem"
                            : "Bergabung Dengan Ekosistem DIGESTEX"}
                    </Link>
                </div>

                <p className="mt-6 text-xs uppercase tracking-[0.25em] text-slate-500">
                    {isEn
                        ? "Built In Indonesia. Connected To The Global Textile Industry."
                        : "Dibangun Di Indonesia. Terhubung Dengan Industri Tekstil Global."}
                </p>
            </div>
        </section>
    );
}
