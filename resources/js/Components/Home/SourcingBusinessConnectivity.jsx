import React from "react";

export default function SourcingBusinessConnectivity({ isEn }) {
    return (
        <div className="group relative overflow-hidden rounded-[30px] border border-white/10 bg-white/5 p-7 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-yellow-500/30 hover:bg-white/[0.07]">
            <div className="flex items-start justify-between">
                <div className="flex h-12 w-12 items-center justify-center rounded-2xl bg-yellow-500/10 text-2xl">
                    🤝
                </div>

                <span className="rounded-full border border-emerald-400/20 bg-emerald-400/10 px-3 py-1 text-[9px] font-black uppercase tracking-widest text-emerald-400">
                    {isEn ? "Connected" : "Terhubung"}
                </span>
            </div>

            <h3 className="mt-6 text-xl font-black uppercase text-white">
                {isEn
                    ? "Sourcing / Business Connectivity"
                    : "Sourcing / Konektivitas Bisnis"}
            </h3>

            <p className="mt-4 text-sm leading-7 text-slate-400">
                {isEn
                    ? "Connect buyers with relevant suppliers, technologies, products, and industrial solutions through structured discovery, sourcing, RFQ, matching, and business opportunities."
                    : "Menghubungkan buyer dengan supplier, teknologi, produk, dan solusi industri yang relevan melalui discovery, sourcing, RFQ, matching, dan peluang bisnis yang terstruktur."}
            </p>

            <div className="mt-5 border-t border-white/10 pt-5 text-xs leading-6 text-slate-500">
                {isEn
                    ? "Buyers • Supplier Discovery • Sourcing • RFQ • Matching • Business Opportunities"
                    : "Buyer • Pencarian Supplier • Sourcing • RFQ • Matching • Peluang Bisnis"}
            </div>
        </div>
    );
}
