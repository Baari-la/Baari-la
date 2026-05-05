export default function VisionMission() {
    return (
        <section className="container mx-auto px-6 py-24 border-t border-white/5 bg-gradient-to-b from-[#0a192f] to-black">
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                {/* LEFT SIDE: VISION (Dominant Text) */}
                <div className="space-y-8">
                    <div className="inline-block px-4 py-1 rounded-full bg-yellow-500/10 border border-yellow-500/20 shadow-sm">
                        <span className="text-[10px] font-black tracking-[0.2em] text-yellow-500 uppercase">
                            Our Vision / Visi Kami
                        </span>
                    </div>

                    <div className="space-y-6">
                        <h2 className="text-4xl md:text-5xl font-black leading-[0.95] text-white tracking-tighter uppercase italic">
                            Menjadi pusat intelijen data pertekstilan nasional
                            yang transparan dan terpercaya.
                        </h2>
                        <p className="text-xl md:text-2xl text-gray-500 font-light italic leading-relaxed border-l-4 border-yellow-500 pl-6">
                            "To become the most transparent and trusted national
                            textile data intelligence hub."
                        </p>
                    </div>
                </div>

                {/* RIGHT SIDE: MISSION (Modern List) */}
                <div className="bg-white/5 p-10 rounded-[50px] border border-white/10 backdrop-blur-xl shadow-2xl relative overflow-hidden group">
                    <div className="absolute top-0 right-0 p-8 opacity-5">
                        <i className="fas fa-bullseye text-[150px] text-white"></i>
                    </div>

                    <h3 className="text-[10px] font-black tracking-[0.4em] text-gray-500 uppercase mb-10">
                        Our Mission / Misi Kami
                    </h3>

                    <div className="space-y-10 relative z-10">
                        {/* Mission 01 */}
                        <div className="flex gap-6 group">
                            <div className="text-yellow-500 font-black text-3xl tracking-tighter opacity-50 group-hover:opacity-100 transition-opacity">
                                01
                            </div>
                            <div>
                                <p className="text-lg font-black text-white uppercase tracking-tight mb-1">
                                    Digitalisasi Rantai Pasok
                                </p>
                                <p className="text-xs text-gray-500 font-medium tracking-wide leading-relaxed">
                                    Digitalizing the textile supply chain for
                                    global competitiveness.
                                </p>
                            </div>
                        </div>

                        {/* Mission 02 */}
                        <div className="flex gap-6 border-t border-white/5 pt-8 group">
                            <div className="text-yellow-500 font-black text-3xl tracking-tighter opacity-50 group-hover:opacity-100 transition-opacity">
                                02
                            </div>
                            <div>
                                <p className="text-lg font-black text-white uppercase tracking-tight mb-1">
                                    Transparansi Data Industri
                                </p>
                                <p className="text-xs text-gray-500 font-medium tracking-wide leading-relaxed">
                                    Ensuring industrial data transparency for
                                    strategic decision making.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}
