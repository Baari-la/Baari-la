import React from "react";

export default function StrategicEcosystemPartners({ isEn }) {
    return (
        <div className="group relative overflow-hidden rounded-[30px] border border-white/10 bg-white/5 p-7 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-yellow-500/30 hover:bg-white/[0.07]">
            <div className="flex items-start justify-between">
                <div className="flex h-12 w-12 items-center justify-center rounded-2xl bg-yellow-500/10 text-2xl">
                    🌐
                </div>

                <span className="rounded-full border border-amber-400/20 bg-amber-400/10 px-3 py-1 text-[9px] font-black uppercase tracking-widest text-amber-300">
                    {isEn ? "Ecosystem" : "Ekosistem"}
                </span>
            </div>

            <h3 className="mt-6 text-xl font-black uppercase text-white">
                {isEn
                    ? "Strategic & Ecosystem Partners"
                    : "Strategic & Ecosystem Partners"}
            </h3>

            <p className="mt-4 text-sm leading-7 text-slate-400">
                {isEn
                    ? "Connect with solution providers, strategic partners, testing and certification organizations, technology companies, industry associations, and supporting organizations across the textile ecosystem."
                    : "Terhubung dengan penyedia solusi, strategic partner, organisasi testing dan certification, perusahaan teknologi, asosiasi industri, serta organisasi pendukung di seluruh ekosistem industri tekstil."}
            </p>

            <div className="mt-5 border-t border-white/10 pt-5 text-xs leading-6 text-slate-500">
                {isEn
                    ? "Solution Partners • Strategic Partners • Testing & Certification • Technology Partners • Industry Associations • Supporting Organizations"
                    : "Solution Partner • Strategic Partner • Testing & Certification • Technology Partner • Asosiasi Industri • Organisasi Pendukung"}
            </div>
        </div>
    );
}
