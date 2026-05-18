import React from "react";

export default function EcosystemManifesto({ isEn = false }) {
    // Data 4 Pilar Manufaktur Utama Digestex Global
    const sectors = [
        {
            icon: "fas fa-tshirt",
            titleId: "Apparel & Pakaian Jadi",
            titleEn: "Apparel & Garments",
            descId: "Integrasi lini maklon jahit, CMT, garmen medis, dan rantai pasok industri pakaian jadi skala ekspor global.",
            descEn: "Integration of sewing lines, CMT, medical garments, and global export-scale apparel supply chains.",
        },
        {
            icon: "fas fa-scroll",
            titleId: "Tekstil & Bahan Hulu",
            titleEn: "Textiles & Upstream Materials",
            descId: "Automasi pemantauan harga kapas dunia, bursa benang, kain rajut, tenun, hingga optimalisasi kapasitas mesin kosong.",
            descEn: "Automated global cotton tracking, yarn trade, knitting, weaving, and idle machinery optimization.",
        },
        {
            icon: "fas fa-shoe-prints",
            titleId: "Alas Kaki & Sepatu",
            titleEn: "Footwear & Footwear Assembly",
            descId: "Terminal perjodohan unit perakitan sol, kulit sintetis, komponen keras, dan manufaktur sepatu internasional.",
            descEn: "Matchmaking terminal for sole assembly units, synthetic leather, hardware components, and global footwear manufacturing.",
        },
        {
            icon: "fas fa-briefcase",
            titleId: "Tas, Sarung Tangan & Kulit",
            titleEn: "Bags, Gloves & Leather",
            descId: "Ekosistem industri tas, koper, sarung tangan kulit proteksi tinggi, penyamakan kulit (leather tanning), dan penyuplai aksesoris logam terpadu.",
            descEn: "Integrated ecosystem for bags, high-protection leather gloves, leather tanning processes, and heavy-duty hardware suppliers.",
        },
    ];

    return (
        <section className="container mx-auto px-6 py-12 max-w-7xl">
            {/* --- HEADLINE SECTION --- */}
            <div className="mb-10 text-center md:text-left border-l-4 border-amber-500 pl-6 max-w-3xl">
                <span className="text-yellow-500 text-[9px] font-black uppercase tracking-[0.4em] mb-2 block">
                    {isEn
                        ? "Global Industrial Integration Blueprint"
                        : "Cetak Biru Integrasi Industri Global"}
                </span>
                <h2 className="text-3xl font-black uppercase leading-tight text-white tracking-tighter">
                    {isEn
                        ? "The Integrated B2B Super-Hub"
                        : "Super-Hub Digital Terintegrasi"}{" "}
                    <br />
                    <span className="text-yellow-500">
                        {isEn
                            ? "From Upstream to Downstream"
                            : "Dari Hulu Hingga ke Hilir"}
                    </span>
                </h2>
                <p className="text-gray-400 text-xs mt-3 leading-relaxed">
                    {isEn
                        ? "Inspired by the architectural metrics of international trade journals, Digestex Global unifies four pillars of labor-intensive manufacturing into a singular data-driven synergy terminal."
                        : "Terinspirasi dari matriks arsitektur jurnal perdagangan internasional, Digestex Global menyatukan empat pilar manufaktur padat karya ke dalam satu terminal sinergi berbasis data."}
                </p>
            </div>

            {/* --- GRID 4 PILAR UTAMA INDUSTRIAL --- */}
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {sectors.map((sector, index) => (
                    <div
                        key={index}
                        className="bg-white/5 border border-white/10 p-6 rounded-[35px] hover:border-amber-500/30 transition-all duration-500 hover:-translate-y-1.5 group flex flex-col justify-between"
                    >
                        <div>
                            {/* Icon Box */}
                            <div className="w-12 h-12 bg-[#001F3F]/60 rounded-2xl flex items-center justify-center text-yellow-500 text-lg mb-4 border border-white/5 group-hover:bg-amber-500 group-hover:text-[#0a192f] transition-all duration-300 shadow-inner">
                                <i className={sector.icon}></i>
                            </div>
                            {/* Title */}
                            <h3 className="text-base font-black uppercase text-white mb-2 tracking-tight group-hover:text-yellow-500 transition-colors">
                                {isEn ? sector.titleEn : sector.titleId}
                            </h3>
                            {/* Description */}
                            <p className="text-[11px] text-gray-400 leading-relaxed font-medium">
                                {isEn ? sector.descEn : sector.descId}
                            </p>
                        </div>
                    </div>
                ))}
            </div>
        </section>
    );
}
