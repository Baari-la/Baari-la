// resources/js/Pages/About/Events.jsx
export default function Events({ eventList }) {
    return (
        <section className="py-20 bg-[#0a192f] text-white">
            <div className="max-w-7xl mx-auto px-6">
                <div className="mb-16">
                    <h2 className="text-4xl font-black italic tracking-tighter uppercase mb-4">
                        {t("Events_Title")}
                    </h2>
                    <p className="text-gray-500 text-sm font-bold uppercase tracking-widest">
                        {t("Events_Subtitle")}
                    </p>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    {/* Contoh Card Acara */}
                    <div className="group bg-white/5 rounded-[40px] border border-white/10 overflow-hidden hover:border-blue-500/50 transition-all">
                        <div className="h-56 bg-gray-800 overflow-hidden relative">
                            <img
                                src="/images/events/centric-summit.jpg"
                                className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                alt="Event"
                            />
                            <span className="absolute top-4 left-4 bg-blue-600 text-[8px] font-black px-3 py-1 rounded-full uppercase">
                                {t("Events_Type_International")}
                            </span>
                        </div>
                        <div className="p-8">
                            <h4 className="text-lg font-black italic mb-4 leading-tight">
                                DIGESTEX WITH CENTRICSOFTWARE SUMMIT
                            </h4>
                            <p className="text-gray-500 text-[10px] font-bold uppercase tracking-widest">
                                Jakarta, 2026 • Strategic Digitalization
                            </p>
                        </div>
                    </div>
                    {/* Bapak bisa memetakan (map) data dari database di sini */}
                </div>
            </div>
        </section>
    );
}
