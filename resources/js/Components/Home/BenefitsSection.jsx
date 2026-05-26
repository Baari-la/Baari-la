import React from "react";

export default function BenefitsSection({ isEn }) {
    const content = {
        heading: isEn ? "Integrated " : "Ekosistem ",
        highlight: isEn ? "Intelligence" : "Intelijen",
        subtitle: isEn
            ? "Built for local markets, SMEs, manufacturers, institutions, and global supply chain stakeholders."
            : "Dibangun untuk pasar lokal, IKM, produsen, institusi, serta pemangku kepentingan rantai pasok regional dan global.",

        tiers: [
            {
                level: isEn ? "MARKET INTELLIGENCE" : "INTELIJEN PASAR",

                title: isEn
                    ? "Domestic & SME Ecosystem"
                    : "Ekosistem Domestik & IKM",

                features: isEn
                    ? [
                          "Local Market Trends",
                          "SME / IKM Visibility",
                          "Verified Industry Directory",
                          "Trade & Regulatory Updates",
                      ]
                    : [
                          "Tren Pasar Lokal",
                          "Visibilitas IKM",
                          "Direktori Industri Terverifikasi",
                          "Pembaruan Regulasi & Perdagangan",
                      ],

                note: isEn
                    ? "Supporting local industry, SMEs, mills, suppliers, traders, and ecosystem participants."
                    : "Mendukung industri lokal, IKM, pabrik, pemasok, trader, dan pelaku ekosistem.",

                cta: isEn
                    ? "Explore Market Intelligence"
                    : "Jelajahi Intelijen Pasar",

                color: "text-white",
                bg: "bg-white/5",
                border: "border-white/10",
                icon: "fa-store",
            },

            {
                level: isEn
                    ? "OPERATIONAL INTELLIGENCE"
                    : "INTELIJEN OPERASIONAL",

                title: isEn ? "Industrial Visibility" : "Visibilitas Industri",

                features: isEn
                    ? [
                          "Supply Chain Monitoring",
                          "Manufacturing Intelligence",
                          "Sourcing & Procurement Insights",
                          "Export / Import Movement Radar",
                      ]
                    : [
                          "Pemantauan Rantai Pasok",
                          "Intelijen Manufaktur",
                          "Insight Sourcing & Procurement",
                          "Radar Pergerakan Ekspor / Impor",
                      ],

                note: isEn
                    ? "Designed for manufacturers, suppliers, sourcing teams, exporters, and operational stakeholders."
                    : "Dirancang untuk produsen, pemasok, tim sourcing, eksportir, dan pemangku kepentingan operasional.",

                cta: isEn
                    ? "Access Operational Insights"
                    : "Akses Insight Operasional",

                color: "text-blue-400",
                bg: "bg-blue-500/5",
                border: "border-blue-500/20",
                icon: "fa-industry",
            },

            {
                level: isEn ? "STRATEGIC INTELLIGENCE" : "INTELIJEN STRATEGIS",

                title: isEn
                    ? "Regional & Global Advantage"
                    : "Keunggulan Regional & Global",

                features: isEn
                    ? [
                          "Regional Trade Radar",
                          "Risk & Opportunity Monitoring",
                          "Cross-Border Supply Chain Intelligence",
                          "Executive Strategic Briefings",
                      ]
                    : [
                          "Radar Perdagangan Regional",
                          "Pemantauan Risiko & Peluang",
                          "Intelijen Rantai Pasok Lintas Negara",
                          "Briefing Strategis Eksekutif",
                      ],

                note: isEn
                    ? "Built for PMA, multinational brands, institutions, investors, and strategic ecosystem partners."
                    : "Dibangun untuk PMA, brand multinasional, institusi, investor, dan mitra strategis ekosistem.",

                privilege: isEn
                    ? "Verified ecosystem connectivity and selected strategic privileges through industry association networks."
                    : "Konektivitas ekosistem terverifikasi dan privilese strategis tertentu melalui jaringan asosiasi industri.",

                cta: isEn
                    ? "Unlock Strategic Intelligence"
                    : "Buka Intelijen Strategis",

                color: "text-yellow-500",
                bg: "bg-yellow-500/5",
                border: "border-yellow-500/25",
                icon: "fa-globe-asia",
            },
        ],
    };

    return (
        <section className="relative overflow-hidden border-t border-white/5 bg-gradient-to-b from-[#07111f] via-[#081522] to-black py-28">
            {/* Ambient Glow */}
            <div className="absolute top-20 left-10 h-56 w-56 rounded-full bg-yellow-500/10 blur-[120px]" />
            <div className="absolute bottom-20 right-10 h-72 w-72 rounded-full bg-blue-500/10 blur-[140px]" />

            <div className="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
                {/* Heading */}
                <div className="text-center mb-20 max-w-5xl mx-auto">
                    <h2 className="text-4xl md:text-6xl font-black text-white uppercase tracking-tight italic mb-5">
                        {content.heading}
                        <span className="text-yellow-500">
                            {content.highlight}
                        </span>
                    </h2>

                    <p className="text-xs md:text-sm text-gray-400 uppercase tracking-[0.25em] font-bold leading-relaxed">
                        {content.subtitle}
                    </p>
                </div>

                {/* Cards */}
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {content.tiers.map((tier, idx) => (
                        <div
                            key={idx}
                            className={`
                                relative overflow-hidden rounded-[40px]
                                border ${tier.border}
                                ${tier.bg}
                                backdrop-blur-xl
                                p-8 md:p-10
                                shadow-[0_20px_80px_rgba(0,0,0,0.35)]
                                transition-all duration-500
                                hover:scale-[1.02]
                                hover:-translate-y-1
                                group
                            `}
                        >
                            {/* Decorative Icon */}
                            <i
                                className={`
                                    fas ${tier.icon}
                                    absolute -right-8 -bottom-8
                                    text-[150px]
                                    text-white/5
                                    -rotate-12
                                `}
                            />

                            {/* Level */}
                            <span
                                className={`${tier.color} text-[10px] font-black uppercase tracking-[0.35em] mb-3 block`}
                            >
                                {tier.level}
                            </span>

                            {/* Title */}
                            <h3 className="text-2xl md:text-3xl font-black text-white uppercase tracking-tight mb-8 leading-tight">
                                {tier.title}
                            </h3>

                            {/* Features */}
                            <ul className="space-y-5 relative z-10">
                                {tier.features.map((feature, index) => (
                                    <li
                                        key={index}
                                        className="flex gap-4 items-start text-gray-300 group-hover:text-white transition-colors"
                                    >
                                        <div
                                            className={`
                                                mt-1 w-6 h-6 rounded-full flex items-center justify-center shrink-0
                                                ${
                                                    idx === 2
                                                        ? "bg-yellow-500"
                                                        : idx === 1
                                                          ? "bg-blue-500"
                                                          : "bg-white/10"
                                                }
                                            `}
                                        >
                                            <i
                                                className={`
                                                    fas fa-check text-[10px]
                                                    ${
                                                        idx === 0
                                                            ? "text-white"
                                                            : "text-[#07111f]"
                                                    }
                                                `}
                                            />
                                        </div>

                                        <span className="text-sm md:text-[14px] font-bold uppercase tracking-wide leading-relaxed">
                                            {feature}
                                        </span>
                                    </li>
                                ))}
                            </ul>

                            {/* Footer */}
                            <div className="mt-10 pt-8 border-t border-white/5 relative z-10">
                                <p className="text-[11px] text-gray-400 leading-relaxed mb-4 min-h-[70px]">
                                    {tier.note}
                                </p>

                                {tier.privilege && (
                                    <p className="text-[10px] text-yellow-500/80 italic leading-relaxed mb-6">
                                        {tier.privilege}
                                    </p>
                                )}

                                <button
                                    className={`
                                        w-full py-4 rounded-2xl
                                        font-black uppercase
                                        tracking-[0.22em]
                                        text-[11px]
                                        transition-all duration-300
                                        ${
                                            idx === 2
                                                ? "bg-yellow-500 text-[#07111f] hover:shadow-[0_0_35px_rgba(234,179,8,0.35)]"
                                                : idx === 1
                                                  ? "bg-blue-500 text-white hover:shadow-[0_0_30px_rgba(59,130,246,0.25)]"
                                                  : "bg-white/10 text-white hover:bg-white/15"
                                        }
                                    `}
                                >
                                    {tier.cta}
                                </button>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </section>
    );
}
