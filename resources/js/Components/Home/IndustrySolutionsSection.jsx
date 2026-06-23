import { Link } from "@inertiajs/react";

export default function IndustrySolutionsSection({ partners = [], isEn }) {
    const solutions = [
        {
            icon: "fa-shield-halved",
            slug: "testing-certification",
            title: isEn ? "Testing & Certification" : "Pengujian & Sertifikasi",

            description: isEn
                ? "Quality assurance, laboratory testing, certification, and compliance solutions."
                : "Jaminan mutu, pengujian laboratorium, sertifikasi, dan kepatuhan industri.",

            leader: null,
        },

        {
            icon: "fa-gears",
            slug: "industrial-machinery",
            title: isEn ? "Industrial Machinery" : "Mesin Industri",

            description: isEn
                ? "Knitting, weaving, dyeing, finishing, and textile manufacturing technologies."
                : "Teknologi rajut, tenun, pencelupan, finishing, dan manufaktur tekstil.",
        },

        {
            icon: "fa-microchip",
            slug: "technology-solutions",
            title: isEn ? "Technology Solutions" : "Solusi Teknologi",

            description: isEn
                ? "ERP, PLM, AI, Industry 4.0, and digital transformation solutions."
                : "ERP, PLM, AI, Industry 4.0, dan transformasi digital industri.",
        },

        {
            icon: "fa-boxes-stacked",
            slug: "raw-materials",
            title: isEn ? "Raw Materials" : "Bahan Baku",

            description: isEn
                ? "Fiber, yarn, fabrics, chemicals, and textile materials."
                : "Serat, benang, kain, bahan kimia, dan material tekstil.",
        },

        {
            icon: "fa-truck",
            slug: "logistics-supply-chain",
            title: isEn
                ? "Logistics & Supply Chain"
                : "Logistik & Rantai Pasok",

            description: isEn
                ? "Domestic and international logistics, warehousing, and trade support."
                : "Logistik domestik dan internasional, pergudangan, dan dukungan perdagangan.",
        },

        {
            icon: "fa-building-columns",
            slug: "trade-finance",
            title: isEn ? "Trade Finance" : "Pembiayaan Perdagangan",

            description: isEn
                ? "Financing solutions supporting industrial growth and export activities."
                : "Solusi pembiayaan yang mendukung pertumbuhan industri dan ekspor.",
        },

        {
            icon: "fa-calendar-days",
            slug: "exhibitions-events",
            title: isEn ? "Exhibitions & Events" : "Pameran & Event",

            description: isEn
                ? "Trade fairs, business matching, networking, and industry events."
                : "Pameran dagang, business matching, networking, dan event industri.",
        },

        {
            icon: "fa-graduation-cap",
            slug: "research-education",
            title: isEn ? "Research & Education" : "Riset & Pendidikan",

            description: isEn
                ? "Universities, research institutions, training centers, and workforce development."
                : "Universitas, lembaga riset, pusat pelatihan, dan pengembangan SDM.",
        },
    ];

    return (
        <section className="py-24 border-y border-white/5 bg-[#07111f]">
            <div className="max-w-7xl mx-auto px-6">
                <div className="text-center mb-16">
                    <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                        TEXTILE INDUSTRY SOLUTIONS
                    </span>

                    <h2 className="text-4xl md:text-6xl font-black text-white mt-4 uppercase">
                        {isEn
                            ? "Connecting industry challenges with technology, certification, machinery, materials, logistics and business solutions."
                            : "Pemimpin Solusi Dalam Ekosistem Tekstil"}
                    </h2>

                    <p className="max-w-4xl mx-auto mt-6 text-gray-400">
                        {isEn
                            ? "Connecting technology providers, certification bodies, raw material suppliers, machinery companies, logistics providers, and financial institutions with the textile industry."
                            : "Menghubungkan penyedia teknologi, lembaga sertifikasi, pemasok bahan baku, perusahaan mesin, logistik, dan institusi keuangan dengan industri tekstil."}
                    </p>
                </div>

                <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                    {solutions.map((item, index) => (
                        <div
                            key={index}
                            className="
                                group
                                rounded-[30px]
                                border border-white/10
                                bg-white/5
                                backdrop-blur-xl
                                p-8
                                transition-all
                                duration-300
                                hover:border-yellow-500/30
                                hover:-translate-y-1
                            "
                        >
                            <i
                                className={`
                                    fas
                                    ${item.icon}
                                    text-yellow-500
                                    text-4xl
                                    mb-6
                                `}
                            />

                            <h3 className="text-white font-black text-xl mb-4">
                                {item.title}
                            </h3>

                            <p className="text-gray-400 text-sm leading-relaxed">
                                {item.description}
                            </p>

                            <div className="mt-6 text-yellow-500 text-xs font-black uppercase tracking-widest">
                                <Link
                                    href={route(
                                        "industry-solutions.show",
                                        item.slug,
                                    )}
                                >
                                    Explore Solutions →
                                </Link>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </section>
    );
}
