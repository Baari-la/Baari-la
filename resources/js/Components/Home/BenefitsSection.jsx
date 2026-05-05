import React from "react";

export default function BenefitsSection({ isEn }) {
    const benefits = [
        {
            level: "STANDARD MEMBER",
            title: isEn ? "Industry Resilience" : "Resiliensi Industri",
            features: isEn
                ? [
                      "Full Intelligence Access",
                      "Official Regulatory PDF",
                      "Verified Member Directory",
                      "National Network Hub",
                  ]
                : [
                      "Akses Intelijen Penuh",
                      "PDF Regulasi Resmi",
                      "Direktori Anggota Terverifikasi",
                      "Pusat Jaringan Nasional",
                  ],
            note: isEn
                ? "Simply register as an official member of API Jakarta"
                : "Cukup daftar menjadi anggota resmi API Jakarta",
            color: "text-white",
            bg: "bg-white/5",
            border: "border-white/10",
        },
        {
            level: "PREMIUM PARTNER",
            title: isEn ? "Strategic Dominance" : "Dominasi Strategis",
            features: isEn
                ? [
                      "AI Matchmaking Priority",
                      "Real-time Market Radar",
                      "Global Brand Exposure",
                      "Executive Briefings",
                  ]
                : [
                      "Prioritas AI Matchmaking",
                      "Radar Pasar Real-time",
                      "Eksposur Brand Global",
                      "Executive Briefings",
                  ],
            color: "text-yellow-500",
            bg: "bg-yellow-500/5",
            border: "border-yellow-500/30",
        },
    ];

    return (
        <section className="py-24 bg-[#0a192f]">
            <div className="max-w-7xl mx-auto px-6 lg:px-8">
                <div className="text-center mb-16">
                    <h2 className="text-4xl md:text-5xl font-black text-white uppercase tracking-tighter italic mb-4">
                        {isEn ? "Membership " : "Keunggulan "}
                        <span className="text-yellow-500">
                            {isEn ? "Advantage" : "Keanggotaan"}
                        </span>
                    </h2>
                    <p className="text-gray-400 text-sm uppercase tracking-widest font-bold">
                        {isEn
                            ? "Choose your level of intelligence"
                            : "Pilih tingkat intelijen Anda"}
                    </p>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {benefits.map((tier, idx) => (
                        <div
                            key={idx}
                            className={`${tier.bg} ${tier.border} border rounded-[50px] p-12 transition-all duration-500 hover:scale-[1.02] relative overflow-hidden group`}
                        >
                            {/* Decorative Background Icon */}
                            <i
                                className={`fas ${idx === 1 ? "fa-crown" : "fa-users"} absolute -right-10 -bottom-10 text-white/5 text-[200px] -rotate-12`}
                            ></i>

                            <span
                                className={`${tier.color} text-[10px] font-black uppercase tracking-[0.4em] mb-2 block`}
                            >
                                {tier.level}
                            </span>
                            <h3 className="text-3xl font-black text-white uppercase mb-10 tracking-tight">
                                {tier.title}
                            </h3>

                            <ul className="space-y-6 relative z-10">
                                {tier.features.map((feature, fIdx) => (
                                    <li
                                        key={fIdx}
                                        className="flex items-center gap-4 text-gray-300 group-hover:text-white transition-colors"
                                    >
                                        <div
                                            className={`w-6 h-6 rounded-full flex items-center justify-center ${idx === 1 ? "bg-yellow-500" : "bg-white/10"}`}
                                        >
                                            <i
                                                className={`fas fa-check text-[10px] ${idx === 1 ? "text-[#0a192f]" : "text-white"}`}
                                            ></i>
                                        </div>
                                        <span className="text-sm font-bold uppercase tracking-wide">
                                            {feature}
                                        </span>
                                    </li>
                                ))}
                            </ul>

                            <div className="mt-12 pt-8 border-t border-white/5 relative z-10">
                                {tier.note && (
                                    <p className="text-[10px] text-yellow-500/80 font-black uppercase tracking-widest mb-4 italic text-center">
                                        {tier.note}
                                    </p>
                                )}

                                <button
                                    className={`w-full py-4 rounded-2xl font-black uppercase tracking-widest text-[10px] transition-all
        ${
            idx === 1
                ? "bg-yellow-500 text-[#0a192f] hover:shadow-[0_0_30px_rgba(234,179,8,0.3)]"
                : "bg-white/10 text-white hover:bg-white/20"
        }`}
                                >
                                    {idx === 1
                                        ? isEn
                                            ? "Upgrade to Premium"
                                            : "Tingkatkan ke Premium"
                                        : isEn
                                          ? "Register Membership"
                                          : "Daftar Keanggotaan"}
                                </button>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </section>
    );
}
