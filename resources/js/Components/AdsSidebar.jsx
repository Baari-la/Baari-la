export default function AdsSidebar() {
    return (
        <div className="space-y-6">
            <p className="text-[10px] font-black text-gray-500 uppercase tracking-widest text-center">
                Industrial Solutions Partner
            </p>

            {/* SLOT IKLAN 1: SOLAR ENERGY */}
            <div className="group rounded-2xl border border-white/10 bg-white/5 p-4 hover:border-yellow-500/30 transition">
                <div className="h-24 bg-blue-900/20 rounded-xl mb-4 flex items-center justify-center">
                    <i className="fas fa-solar-panel text-3xl text-blue-500 opacity-30"></i>
                </div>
                <h5 className="text-xs font-bold text-white uppercase mb-1 leading-tight">
                    Solar Power for Industry
                </h5>
                <p className="text-[10px] text-gray-500 leading-tight">
                    Efficiency for PMA & Local Factories.
                </p>
                <a
                    href="#"
                    className="mt-3 block text-center py-2 bg-white/5 text-white text-[9px] font-black rounded-lg uppercase group-hover:bg-yellow-500 group-hover:text-[#0a192f] transition font-sans"
                >
                    Contact Vendor
                </a>
            </div>

            {/* SLOT IKLAN 2: MESIN CHINA (Contoh Kerjasama Baru) */}
            <div className="group rounded-2xl border border-white/10 bg-white/5 p-4 hover:border-yellow-500/30 transition">
                <div className="h-24 bg-gray-800/40 rounded-xl mb-4 flex items-center justify-center">
                    <i className="fas fa-industry text-3xl text-gray-400 opacity-30"></i>
                </div>
                <h5 className="text-xs font-bold text-white uppercase mb-1 leading-tight">
                    High-Speed Weaving Tech
                </h5>
                <p className="text-[10px] text-gray-500 leading-tight">
                    Direct from Global Partners.
                </p>
                <a
                    href="#"
                    className="mt-3 block text-center py-2 bg-white/5 text-white text-[9px] font-black rounded-lg uppercase group-hover:bg-yellow-500 group-hover:text-[#0a192f] transition font-sans"
                >
                    View Catalog
                </a>
            </div>
        </div>
    );
}
