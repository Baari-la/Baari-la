import React from "react";

export default function VisionMission({ isEn }) {
    const content = {
        heading: isEn ? "Vision & " : "Visi & ",
        highlight: isEn ? "Mission" : "Misi",

        intro: isEn
            ? "Building an independent industry ecosystem that connects market visibility, sourcing intelligence, and strategic decision-making across domestic, regional, and global textile value chains."
            : "Mewujudkan ekosistem industri yang mandiri dengan mengintegrasikan visibilitas pasar, kecerdasan sourcing, dan kebijakan strategis di sepanjang rantai nilai tekstil domestik, regional, maupun global.",

        visionTitle: isEn ? "Vision" : "Visi",

        vision: isEn
            ? "To become the trusted digital ecosystem for the textile, apparel, footwear, and supply chain industries—connecting companies, markets, sourcing opportunities, and strategic intelligence through a unified platform that drives visibility, collaboration, and sustainable growth."
            : "Menyediakan intelijen industri yang terpercaya dan visibilitas terverifikasi bagi perusahaan, produk, kapabilitas, sertifikasi, serta peluang pasar di seluruh ekosistem tekstil.",

        missions: [
            {
                title: isEn
                    ? "Strengthen Market Visibility"
                    : "Memperkuat Visibilitas Pasar",

                text: isEn
                    ? "Provide trusted industry intelligence and verified visibility for companies, products, capabilities, certifications, and market opportunities across the textile ecosystem."
                    : "Menyediakan intelijen industri yang terpercaya dan visibilitas terverifikasi bagi perusahaan, produk, kapabilitas, sertifikasi, serta peluang pasar di seluruh ekosistem tekstil.",

                icon: "fa-chart-line",
                color: "text-blue-400",
                bg: "bg-blue-500/5",
                border: "border-blue-500/20",
            },
            {
                title: isEn
                    ? "Enable Industrial & Supply Chain Advantage"
                    : "Mendorong Keunggulan Industri & Rantai Pasok",

                text: isEn
                    ? "Enable sourcing, procurement, and supply chain collaboration through digital tools, verified networks, and actionable intelligence that improve competitiveness, efficiency, and resilience."
                    : "Mendukung produsen, pemasok, eksportir, dan perusahaan PMA dengan intelijen yang meningkatkan sourcing, operasional, daya saing, dan mitigasi risiko.",

                icon: "fa-industry",
                color: "text-white",
                bg: "bg-white/5",
                border: "border-white/10",
            },
            {
                title: isEn
                    ? "Connect Regional & Global Ecosystems"
                    : "Menghubungkan Ekosistem Regional & Global",

                text: isEn
                    ? "Connect Indonesia’s textile ecosystem with regional and global markets through strategic intelligence, trusted industry networks, trade opportunities, and cross-border collaboration."
                    : "Menghubungkan ekosistem tekstil Indonesia dengan pasar regional dan global melalui intelijen strategis, jaringan industri terpercaya, peluang perdagangan, serta kolaborasi lintas negara.",

                icon: "fa-globe-asia",
                color: "text-yellow-500",
                bg: "bg-yellow-500/5",
                border: "border-yellow-500/20",
            },
        ],
    };

    return (
        <section className="relative overflow-hidden border-t border-white/5 bg-gradient-to-b from-black via-[#07111f] to-[#081522] py-28">
            {/* Ambient Glow */}
            <div className="absolute top-24 left-10 h-56 w-56 rounded-full bg-blue-500/10 blur-[120px]" />
            <div className="absolute bottom-20 right-10 h-72 w-72 rounded-full bg-yellow-500/10 blur-[140px]" />

            <div className="relative z-10 max-w-7xl mx-auto px-6 lg:px-8">
                {/* Heading */}
                <div className="text-center max-w-5xl mx-auto mb-20">
                    <h2 className="text-4xl md:text-6xl font-black text-white uppercase tracking-tight italic mb-5">
                        {content.heading}
                        <span className="text-yellow-500">
                            {content.highlight}
                        </span>
                    </h2>

                    <p className="text-sm md:text-base text-gray-400 leading-relaxed">
                        {content.intro}
                    </p>
                </div>

                {/* Vision */}
                <div className="mb-20 rounded-[40px] border border-yellow-500/15 bg-yellow-500/5 backdrop-blur-xl p-10 md:p-14 shadow-[0_20px_80px_rgba(0,0,0,0.35)]">
                    <span className="text-yellow-500 text-[11px] font-black uppercase tracking-[0.35em] mb-4 block">
                        {content.visionTitle}
                    </span>

                    <h3 className="text-3xl md:text-5xl font-black text-white uppercase leading-tight mb-8">
                        {isEn
                            ? "Trusted Intelligence for a Connected Industry"
                            : "Intelijen Terpercaya untuk Industri yang Terhubung"}
                    </h3>

                    <p className="text-gray-300 text-sm md:text-base leading-relaxed max-w-5xl">
                        {content.vision}
                    </p>
                </div>

                {/* Mission Cards */}
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {content.missions.map((mission, idx) => (
                        <div
                            key={idx}
                            className={`
                                relative overflow-hidden rounded-[36px]
                                ${mission.bg}
                                border ${mission.border}
                                backdrop-blur-xl
                                p-8 md:p-10
                                shadow-[0_20px_70px_rgba(0,0,0,0.35)]
                                transition-all duration-500
                                hover:scale-[1.02]
                                hover:-translate-y-1
                                group
                            `}
                        >
                            {/* Decorative Icon */}
                            <i
                                className={`
                                    fas ${mission.icon}
                                    absolute -right-8 -bottom-8
                                    text-[140px]
                                    text-white/5
                                    -rotate-12
                                `}
                            />

                            {/* Mission Number */}
                            <span
                                className={`${mission.color} text-[10px] font-black uppercase tracking-[0.35em] block mb-4`}
                            >
                                {isEn ? "MISSION" : "MISI"} 0{idx + 1}
                            </span>

                            {/* Title */}
                            <h3 className="text-2xl md:text-3xl font-black text-white uppercase leading-tight mb-6">
                                {mission.title}
                            </h3>

                            {/* Description */}
                            <p className="text-sm md:text-[15px] text-gray-300 leading-relaxed group-hover:text-white transition-colors">
                                {mission.text}
                            </p>
                        </div>
                    ))}
                </div>
            </div>
        </section>
    );
}
