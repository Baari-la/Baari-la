export default function PartnerSponsorship({ isEn }) {
    // Kita batasi hanya 1 Platinum (Utama) dan 2 Slot Cadangan (Gold)
    const featuredPartners = [
        {
            name: "PARTNER",
            logo: "/images/",
            isMain: true,
        },
        { name: "AVAILABLE SLOT", tier: "Gold Partner", isMain: false },
        { name: "AVAILABLE SLOT", tier: "Gold Partner", isMain: false },
    ];

    return (
        <div className="mt-16 py-12 border-t border-white/10">
            <div className="text-center mb-12">
                <h4 className="text-yellow-500 text-[10px] font-black uppercase tracking-[0.5em] mb-3">
                    {isEn ? "Strategic Partnership" : "Kemitraan Strategis"}
                </h4>
                <h3 className="text-2xl font-black uppercase italic text-white">
                    Digital{" "}
                    <span className="text-yellow-500">Ecosystem Partners</span>
                </h3>
            </div>

            <div className="flex flex-col md:flex-row items-center justify-center gap-8 max-w-5xl mx-auto px-6">
                {featuredPartners.map((p, i) =>
                    p.isMain ? (
                        /* SLOT UTAMA: LEBIH BESAR & KONTRAS */
                        <div key={i} className="relative group">
                            <div className="absolute inset-0 bg-yellow-500/10 blur-3xl rounded-full opacity-50 group-hover:opacity-100 transition-opacity"></div>
                            <div className="relative bg-white p-8 rounded-[40px] border-2 border-yellow-500 shadow-2xl w-[300px] h-[140px] flex items-center justify-center transition-transform hover:scale-105">
                                <img
                                    src={p.logo}
                                    className="h-12 object-contain"
                                    alt="PARTNER"
                                />
                            </div>
                            <p className="text-center mt-4 text-[9px] font-black text-yellow-500 uppercase tracking-widest">
                                Official Tech Partner
                            </p>
                        </div>
                    ) : (
                        /* SLOT TERBATAS: LEBIH KECIL & MINIMALIS */
                        <div
                            key={i}
                            className="flex flex-col items-center justify-center p-6 rounded-[32px] border-2 border-dashed border-white/10 bg-white/5 w-[220px] h-[120px] hover:border-white/30 transition-all group cursor-pointer"
                        >
                            <span className="text-xl mb-2 opacity-30 group-hover:opacity-100 group-hover:scale-125 transition-all italic">
                                💎
                            </span>
                            <span className="text-[10px] font-black text-gray-500 uppercase tracking-widest group-hover:text-white">
                                {p.tier}
                            </span>
                            <span className="text-[8px] text-gray-600 mt-1 uppercase font-bold tracking-tighter">
                                {isEn ? "Available" : "Tersedia"}
                            </span>
                        </div>
                    ),
                )}
            </div>

            <div className="mt-12 text-center">
                <p className="text-[10px] text-gray-500 font-bold uppercase tracking-[0.3em] italic">
                    {isEn
                        ? "Limited strategic slots available for Q2 2026."
                        : "Slot strategis terbatas tersedia untuk Kuartal 2 - 2026."}
                </p>
            </div>
        </div>
    );
}
