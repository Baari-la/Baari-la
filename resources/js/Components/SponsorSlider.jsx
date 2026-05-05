import React from "react";

export default function SponsorSlider({ isEn }) {
    // Array logo calon sponsor (Bisa Bapak tambah terus ke bawah)
    const partners = [
        {
            name: "Indorama",
            logo: "/images/partners/indorama.png",
            tier: "Platinum",
        },
        { name: "Sritex", logo: "/images/partners/sritex.png", tier: "Gold" },
        { name: "Lenzing", logo: "/images/partners/lenzing.png", tier: "Gold" },
        {
            name: "Pan Brothers",
            logo: "/images/partners/pan.png",
            tier: "Gold",
        },
        {
            name: "Lucky Print",
            logo: "/images/partners/lucky.png",
            tier: "Silver",
        },
        {
            name: "Kahatex",
            logo: "/images/partners/kahatex.png",
            tier: "Silver",
        },
    ];

    return (
        <div className="mt-16 py-10 border-t border-white/10 relative overflow-hidden">
            <div className="text-center mb-10">
                <h3 className="text-white text-[11px] font-black uppercase tracking-[0.5em] opacity-50 mb-3">
                    {isEn
                        ? "Strategic Industrial Partners"
                        : "Mitra Strategis Industri"}
                </h3>
                <div className="w-20 h-1 bg-yellow-500 mx-auto rounded-full"></div>
            </div>

            {/* ANIMASI SLIDER BERJALAN */}
            <div className="flex overflow-hidden group">
                <div className="flex animate-scroll whitespace-nowrap gap-12 items-center">
                    {/* Render dua kali agar animasi tidak putus (Infinite Loop) */}
                    {[...partners, ...partners].map((partner, index) => (
                        <div
                            key={index}
                            className="flex flex-col items-center justify-center min-w-[150px] group/item"
                        >
                            <div className="h-12 w-32 bg-white/5 border border-white/10 rounded-2xl flex items-center justify-center p-4 transition-all duration-500 grayscale group-hover/item:grayscale-0 group-hover/item:bg-white group-hover/item:scale-110 shadow-lg">
                                {/* Ganti src dengan logo asli nanti */}
                                <span className="text-[10px] font-black text-white group-hover/item:text-black uppercase">
                                    {partner.name}
                                </span>
                            </div>
                            <span className="mt-3 text-[7px] font-black uppercase tracking-widest text-yellow-500 opacity-0 group-hover/item:opacity-100 transition-opacity">
                                {partner.tier} Partner
                            </span>
                        </div>
                    ))}
                </div>
            </div>

            {/* CSS UNTUK ANIMASI (Tambahkan di file CSS utama Bapak) */}
            <style
                dangerouslySetInnerHTML={{
                    __html: `
                @keyframes scroll {
                    0% { transform: translateX(0); }
                    100% { transform: translateX(-50%); }
                }
                .animate-scroll {
                    display: flex;
                    width: max-content;
                    animation: scroll 30s linear infinite;
                }
                .animate-scroll:hover {
                    animation-play-state: paused;
                }
            `,
                }}
            />
        </div>
    );
}
