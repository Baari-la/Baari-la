import React from "react";

export default function VisionMission({ isEn }) {
    const content = {
        heading: isEn ? "Vision & " : "Visi & ",
        highlight: isEn ? "Mission" : "Misi",

        intro: isEn
            ? "Building an independent intelligence ecosystem that strengthens market visibility, industrial competitiveness, and strategic decision-making across domestic, regional, and global textile value chains."
            : "Membangun ekosistem intelijen independen yang memperkuat visibilitas pasar, daya saing industri, serta pengambilan keputusan strategis di seluruh rantai nilai tekstil domestik, regional, dan global.",

        visionTitle: isEn ? "Vision" : "Visi",

        vision: isEn
            ? "To become a trusted independent corporate and market intelligence platform for the textile, garment, footwear, and supply chain ecosystem — connecting local industries, SMEs, manufacturers, institutions, investors, and global stakeholders through structured, actionable, and credible intelligence."
            : "Menjadi platform intelijen korporasi dan pasar independen yang terpercaya bagi ekosistem tekstil, garmen, alas kaki, dan rantai pasok — menghubungkan industri lokal, IKM, manufaktur, institusi, investor, dan pemangku kepentingan global melalui intelijen yang terstruktur, kredibel, dan dapat ditindaklanjuti.",

        missions: [
            {
                title: isEn
                    ? "Strengthen Market Visibility"
                    : "Memperkuat Visibilitas Pasar",

                text: isEn
                    ? "Provide structured intelligence for domestic markets, SMEs, industrial players, and trade stakeholders to improve transparency and faster decision-making."
                    : "Menyediakan intelijen terstruktur bagi pasar domestik, IKM, pelaku industri, dan pemangku kepentingan perdagangan untuk meningkatkan transparansi serta pengambilan keputusan yang lebih cepat.",

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
                    ? "Support manufacturers, suppliers, exporters, and PMA companies with intelligence that improves sourcing, operations, competitiveness, and risk management."
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
                    ? "Bridge Indonesia’s industrial ecosystem with regional and international opportunities through strategic intelligence, verified connectivity, and cross-border market insight."
                    : "Menjembatani ekosistem industri Indonesia dengan peluang regional dan internasional melalui intelijen strategis, konektivitas terverifikasi, serta wawasan pasar lintas negara.",

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
