
const { translations } = usePage().props;
const t = (key) => translations[key] || key;

return (
<section className="relative min-h-[80vh] flex items-center justify-center overflow-hidden bg-[#0a192f] py-20 px-6">
    {/* Efek Digital Grid di Background */}
    <div className="absolute inset-0 opacity-10 bg-[url('/images/grid-pattern.png')]"></div>
    
    <div className="relative z-10 max-w-5xl text-center">
        {/* Label Status Global */}
        <div className="inline-flex items-center gap-2 bg-yellow-500/10 border border-yellow-500/30 px-4 py-2 rounded-full mb-8">
            <span className="relative flex h-2 w-2">
                <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                <span className="relative inline-flex rounded-full h-2 w-2 bg-yellow-500"></span>
            </span>
            <span className="text-[10px] font-black text-yellow-500 uppercase tracking-[0.3em]">
                {t('Hero_Status')}
            </span>
        </div>

        <h1 className="text-white text-5xl md:text-7xl font-black italic tracking-tighter leading-none mb-8">
            UNLOCKING THE DNA OF <br />
            <span className="text-transparent bg-clip-text bg-gradient-to-r from-yellow-500 to-yellow-200">
                GLOBAL TEXTILE
            </span> INTELLIGENCE.
        </h1>

        <p className="text-gray-400 text-lg md:text-xl font-medium max-w-3xl mx-auto mb-12 leading-relaxed">
            Navigate the Indonesian textile ecosystem with 8-digit precision. From raw fiber to finished garments, Digestex V2 provides the world’s most granular data radar to empower your global supply chain.
        </p>

        <div className="flex flex-col md:flex-row items-center justify-center gap-4">
            <button className="bg-yellow-500 text-black px-10 py-4 rounded-2xl font-black uppercase text-[12px] tracking-widest shadow-2xl hover:bg-yellow-400 transition-all">
                Explore 8-Digit Radar
            </button>
            <button className="bg-white/5 border border-white/20 text-white px-10 py-4 rounded-2xl font-black uppercase text-[12px] tracking-widest hover:bg-white/10 transition-all">
                {/* Description */}
        <p className="text-gray-400 text-lg md:text-xl font-medium max-w-3xl mx-auto mb-12">
            {t('Hero_Description')}
        </p>
            </button>
        </div>
    </div>
</section>
);
