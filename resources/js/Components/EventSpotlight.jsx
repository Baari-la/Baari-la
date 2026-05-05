export default function EventSpotlight() {
    return (
        <section className="container mx-auto px-6 py-20 border-t border-white/5">
            <div className="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
                <div className="space-y-4">
                    <div className="inline-block px-3 py-1 rounded-md bg-blue-500/10 border border-blue-500/20">
                        <span className="text-[10px] font-black tracking-widest text-blue-400 uppercase">
                            Latest Event / Kegiatan Terbaru
                        </span>
                    </div>
                    <h2 className="text-3xl font-black uppercase text-white leading-tight">
                        Strategic Partnership: <br />
                        <span className="text-yellow-500">
                            API Jakarta x Centric Software
                        </span>
                    </h2>
                </div>
                <p className="text-gray-500 text-sm max-w-md italic border-l border-white/10 pl-6">
                    "Accelerating digital transformation in Indonesia's textile
                    industry through global PLM expertise."
                </p>
            </div>

            {/* FOTO KEGIATAN */}
            <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div className="group relative overflow-hidden rounded-[40px] border border-white/10 shadow-2xl">
                    <img
                        src="/images/events/centric-seminar-1.jpg"
                        alt="Centric Seminar"
                        className="w-full h-[400px] object-cover transition duration-700 group-hover:scale-110"
                    />
                    <div className="absolute inset-0 bg-gradient-to-t from-[#0a192f] via-transparent to-transparent opacity-80"></div>
                    <div className="absolute bottom-8 left-8">
                        <p className="text-yellow-500 font-black text-xs uppercase tracking-widest mb-2">
                            Seminar & Workshop
                        </p>
                        <h4 className="text-xl font-bold text-white uppercase tracking-tight">
                            Implementation of PLM in Garment Industry
                        </h4>
                    </div>
                </div>

                <div className="bg-white/5 p-10 rounded-[40px] border border-white/10 flex flex-col justify-center">
                    <blockquote className="text-2xl font-light italic text-gray-300 leading-relaxed mb-8">
                        "Kolaborasi ini bertujuan untuk membawa efisiensi
                        standar Silicon Valley ke pabrik-pabrik tekstil di
                        Indonesia."
                    </blockquote>
                    <div className="flex items-center gap-4">
                        <div className="h-1 w-12 bg-yellow-500"></div>
                        <span className="text-xs font-black uppercase tracking-widest text-white text-[10px]">
                            API Jakarta Executive Report
                        </span>
                    </div>
                </div>
            </div>
        </section>
    );
}
