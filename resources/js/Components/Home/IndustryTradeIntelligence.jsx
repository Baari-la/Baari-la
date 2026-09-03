import { Link } from "@inertiajs/react";
import React from "react";

export default function IndustryTradeIntelligence({ isEn }) {
    return (
        <section className="relative overflow-hidden border-t border-white/5 py-24 lg:py-28">
            {/* Background */}
            <div className="pointer-events-none absolute inset-0">
                <div className="absolute left-1/2 top-0 h-[500px] w-[900px] -translate-x-1/2 rounded-full bg-blue-600/10 blur-[140px]" />

                <div className="absolute bottom-0 right-0 h-[400px] w-[500px] rounded-full bg-emerald-500/5 blur-[130px]" />
            </div>

            <div className="relative mx-auto max-w-7xl px-6">
                {/* HEADER */}
                <div className="mx-auto max-w-4xl text-center">
                    <span className="text-xs font-black uppercase tracking-[0.4em] text-yellow-500">
                        {isEn
                            ? "INDUSTRY & TRADE INTELLIGENCE"
                            : "INDUSTRY & TRADE INTELLIGENCE"}
                    </span>

                    <h2 className="mt-5 text-4xl font-black uppercase leading-tight tracking-tight text-white sm:text-5xl lg:text-6xl">
                        {isEn
                            ? "Turning Textile Industry Data Into Business Intelligence"
                            : "Mengubah Data Industri Tekstil Menjadi Business Intelligence"}
                    </h2>

                    <p className="mx-auto mt-7 max-w-4xl text-base leading-8 text-slate-400 sm:text-lg">
                        {isEn
                            ? "DIGESTEX transforms trade data, market signals, industry developments, commodity movements, regulations, and technology trends into actionable intelligence for textile industry decision makers."
                            : "DIGESTEX mentransformasikan data perdagangan, sinyal pasar, perkembangan industri, pergerakan komoditas, regulasi, dan tren teknologi menjadi intelligence yang dapat digunakan oleh decision maker industri tekstil."}
                    </p>
                </div>

                {/* INTELLIGENCE LAYERS */}
                <div className="mt-16 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                    {/* CARD 1 — TRADE INTELLIGENCE */}
                    <Link
                        href={route("intelligence.trade.landing")}
                        className="group relative block overflow-hidden rounded-[30px] border border-white/10 bg-white/5 p-7 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-yellow-500/30 hover:bg-white/[0.07]"
                    >
                        <div className="flex items-start justify-between">
                            <div className="flex h-12 w-12 items-center justify-center rounded-2xl bg-yellow-500/10 text-2xl">
                                📊
                            </div>

                            <span className="rounded-full border border-emerald-400/20 bg-emerald-400/10 px-3 py-1 text-[9px] font-black uppercase tracking-widest text-emerald-400">
                                {isEn ? "Preview" : "Preview"}
                            </span>
                        </div>

                        <h3 className="mt-6 text-xl font-black uppercase text-white">
                            {isEn ? "Trade Intelligence" : "Trade Intelligence"}
                        </h3>

                        <p className="mt-4 text-sm leading-7 text-slate-400">
                            {isEn
                                ? "Global textile trade intelligence covering HS Code trade flows, import and export trends, country markets, product movements, trade origins and destinations, and trade routes."
                                : "Trade intelligence industri tekstil yang mencakup arus perdagangan berdasarkan HS Code, tren impor dan ekspor, pasar negara, pergerakan produk, negara asal dan tujuan perdagangan, serta jalur perdagangan."}
                        </p>

                        <div className="mt-5 border-t border-white/10 pt-5 text-xs leading-6 text-slate-500">
                            {isEn
                                ? "HS Code • Import • Export • Origins • Destinations • Trade Routes"
                                : "HS Code • Impor • Ekspor • Negara Asal • Negara Tujuan • Jalur Perdagangan"}
                        </div>

                        <div className="mt-6 flex items-center gap-2 text-xs font-black uppercase tracking-[0.2em] text-yellow-500 transition-all duration-300 group-hover:text-yellow-400">
                            <span>
                                {isEn
                                    ? "Explore Trade Intelligence"
                                    : "Jelajahi Trade Intelligence"}
                            </span>

                            <span className="transition-transform duration-300 group-hover:translate-x-1">
                                →
                            </span>
                        </div>
                    </Link>
                    {/* lanjutkan di sini */}

                    {/* CARD 2 — MARKET INTELLIGENCE */}
                    <div className="group relative overflow-hidden rounded-[30px] border border-white/10 bg-white/5 p-7 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-yellow-500/30 hover:bg-white/[0.07]">
                        <div className="flex items-start justify-between">
                            <div className="flex h-12 w-12 items-center justify-center rounded-2xl bg-yellow-500/10 text-2xl">
                                🌍
                            </div>

                            <span className="rounded-full border border-emerald-400/20 bg-emerald-400/10 px-3 py-1 text-[9px] font-black uppercase tracking-widest text-emerald-400">
                                {isEn ? "Preview" : "Preview"}
                            </span>
                        </div>

                        <h3 className="mt-6 text-xl font-black uppercase text-white">
                            {isEn
                                ? "Market Intelligence"
                                : "Market Intelligence"}
                        </h3>

                        <p className="mt-4 text-sm leading-7 text-slate-400">
                            {isEn
                                ? "Market intelligence that helps identify demand signals, country opportunities, product movements, market comparisons, emerging trends, and potential textile business opportunities."
                                : "Market intelligence untuk membantu mengidentifikasi sinyal permintaan, peluang negara, pergerakan produk, perbandingan pasar, tren yang berkembang, dan potensi peluang bisnis tekstil."}
                        </p>

                        <div className="mt-5 border-t border-white/10 pt-5 text-xs leading-6 text-slate-500">
                            {isEn
                                ? "Market Trends • Demand Signals • Country Opportunities • Market Analysis"
                                : "Tren Pasar • Sinyal Permintaan • Peluang Negara • Analisis Pasar"}
                        </div>
                    </div>

                    {/* =====================================================
    INDUSTRY INTELLIGENCE
===================================================== */}

                    <div className="group relative overflow-hidden rounded-[30px] border border-white/10 bg-white/5 p-7 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-yellow-500/30 hover:bg-white/[0.07]">
                        <div className="flex items-start justify-between">
                            <div className="flex h-12 w-12 items-center justify-center rounded-2xl bg-yellow-500/10 text-2xl">
                                🏭
                            </div>

                            <span className="rounded-full border border-amber-400/20 bg-amber-400/10 px-3 py-1 text-[9px] font-black uppercase tracking-widest text-amber-300">
                                {isEn ? "Premium" : "Premium"}
                            </span>
                        </div>

                        <h3 className="mt-6 text-xl font-black uppercase text-white">
                            {isEn
                                ? "Industry Intelligence"
                                : "Industry Intelligence"}
                        </h3>

                        <p className="mt-4 text-sm leading-7 text-slate-400">
                            {isEn
                                ? "Industry intelligence covering manufacturing developments, production trends, investment movements, industry players, capacity, supply chains, technologies, and structural changes across the textile industry."
                                : "Industry intelligence yang mencakup perkembangan manufaktur, tren produksi, pergerakan investasi, pelaku industri, kapasitas, supply chain, teknologi, dan perubahan struktural di industri tekstil."}
                        </p>

                        <div className="mt-5 border-t border-white/10 pt-5 text-xs leading-6 text-slate-500">
                            {isEn
                                ? "Manufacturing • Industry Players • Investment • Capacity • Supply Chain"
                                : "Manufaktur • Pelaku Industri • Investasi • Kapasitas • Supply Chain"}
                        </div>
                    </div>
                    {/* =====================================================
    COMMODITY INTELLIGENCE
===================================================== */}

                    <div className="group relative overflow-hidden rounded-[30px] border border-white/10 bg-white/5 p-7 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-yellow-500/30 hover:bg-white/[0.07]">
                        <div className="flex items-start justify-between">
                            <div className="flex h-12 w-12 items-center justify-center rounded-2xl bg-yellow-500/10 text-2xl">
                                📈
                            </div>

                            <span className="rounded-full border border-emerald-400/20 bg-emerald-400/10 px-3 py-1 text-[9px] font-black uppercase tracking-widest text-emerald-400">
                                {isEn ? "Live / Preview" : "Live / Preview"}
                            </span>
                        </div>

                        <h3 className="mt-6 text-xl font-black uppercase text-white">
                            {isEn
                                ? "Commodity Intelligence"
                                : "Commodity Intelligence"}
                        </h3>

                        <p className="mt-4 text-sm leading-7 text-slate-400">
                            {isEn
                                ? "Track key textile commodities, including cotton, fiber, yarn, chemicals, raw materials, price movements, and currency signals that influence textile business decisions."
                                : "Memantau komoditas utama industri tekstil, termasuk kapas, fiber, yarn, chemicals, bahan baku, pergerakan harga, dan sinyal nilai tukar yang memengaruhi keputusan bisnis tekstil."}
                        </p>

                        <div className="mt-5 border-t border-white/10 pt-5 text-xs leading-6 text-slate-500">
                            {isEn
                                ? "Cotton • Fiber • Yarn • Chemicals • Raw Materials • Currency"
                                : "Kapas • Fiber • Yarn • Chemicals • Bahan Baku • Nilai Tukar"}
                        </div>
                    </div>

                    {/* =====================================================
    TRADE POLICY & REGULATION
===================================================== */}

                    <div className="group relative overflow-hidden rounded-[30px] border border-white/10 bg-white/5 p-7 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-yellow-500/30 hover:bg-white/[0.07]">
                        <div className="flex items-start justify-between">
                            <div className="flex h-12 w-12 items-center justify-center rounded-2xl bg-yellow-500/10 text-2xl">
                                📜
                            </div>

                            <span className="rounded-full border border-amber-400/20 bg-amber-400/10 px-3 py-1 text-[9px] font-black uppercase tracking-widest text-amber-300">
                                {isEn ? "Premium" : "Premium"}
                            </span>
                        </div>

                        <h3 className="mt-6 text-xl font-black uppercase text-white">
                            {isEn
                                ? "Trade Policy & Regulation"
                                : "Trade Policy & Regulation"}
                        </h3>

                        <p className="mt-4 text-sm leading-7 text-slate-400">
                            {isEn
                                ? "Trade policies, tariffs, regulations, trade agreements, customs developments, compliance requirements, and market access conditions affecting the textile industry."
                                : "Kebijakan perdagangan, tarif, regulasi, perjanjian perdagangan, perkembangan customs, persyaratan compliance, dan kondisi akses pasar yang memengaruhi industri tekstil."}
                        </p>

                        <div className="mt-5 border-t border-white/10 pt-5 text-xs leading-6 text-slate-500">
                            {isEn
                                ? "Tariffs • Regulations • Trade Agreements • Customs • Compliance"
                                : "Tarif • Regulasi • Trade Agreement • Customs • Compliance"}
                        </div>
                    </div>

                    {/* =====================================================
    TECHNOLOGY & SUSTAINABILITY INTELLIGENCE
===================================================== */}

                    <div className="group relative overflow-hidden rounded-[30px] border border-white/10 bg-white/5 p-7 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-yellow-500/30 hover:bg-white/[0.07]">
                        <div className="flex items-start justify-between">
                            <div className="flex h-12 w-12 items-center justify-center rounded-2xl bg-yellow-500/10 text-2xl">
                                ⚙️
                            </div>

                            <span className="rounded-full border border-amber-400/20 bg-amber-400/10 px-3 py-1 text-[9px] font-black uppercase tracking-widest text-amber-300">
                                {isEn ? "Premium" : "Premium"}
                            </span>
                        </div>

                        <h3 className="mt-6 text-xl font-black uppercase text-white">
                            {isEn
                                ? "Technology & Sustainability Intelligence"
                                : "Technology & Sustainability Intelligence"}
                        </h3>

                        <p className="mt-4 text-sm leading-7 text-slate-400">
                            {isEn
                                ? "Technology adoption, automation, AI, smart manufacturing, sustainability, circular economy, and emerging technologies shaping the future of the textile industry."
                                : "Adopsi teknologi, automation, AI, smart manufacturing, sustainability, circular economy, dan teknologi baru yang membentuk masa depan industri tekstil."}
                        </p>

                        <div className="mt-5 border-t border-white/10 pt-5 text-xs leading-6 text-slate-500">
                            {isEn
                                ? "Technology • Automation • AI • Smart Manufacturing • Sustainability"
                                : "Teknologi • Automation • AI • Smart Manufacturing • Sustainability"}
                        </div>
                    </div>
                </div>

                {/* =================================================
            INTELLIGENCE VALUE STATEMENT
        ================================================= */}

                <div className="mx-auto mt-16 max-w-5xl text-center">
                    <p className="text-lg font-semibold leading-8 text-slate-300 sm:text-xl">
                        {isEn
                            ? "From data and signals to intelligence that helps the textile industry understand markets, identify opportunities, and make better business decisions."
                            : "Dari data dan sinyal menjadi intelligence yang membantu industri tekstil memahami pasar, mengidentifikasi peluang, dan mengambil keputusan bisnis yang lebih baik."}
                    </p>
                    {/* lanjutkan di sini */}
                </div>

                {/* =================================================
            CTA
        ================================================= */}

                <div className="mt-10 flex justify-center">
                    {/* <Link
                                    href={route("intelligence.index")}
                                    className="inline-flex items-center gap-3 rounded-full bg-yellow-500 px-8 py-4 text-xs font-black uppercase tracking-[0.2em] text-slate-950 shadow-xl transition-all duration-300 hover:bg-yellow-400"
                                >
                                    {isEn
                                        ? "Explore Textile Intelligence"
                                        : "Jelajahi Textile Intelligence"}

                                    <span className="text-base">→</span>
                                </Link> */}
                </div>
            </div>
        </section>
    );
}
