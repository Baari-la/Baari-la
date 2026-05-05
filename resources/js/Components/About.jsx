export default function About() {
    const { translations } = usePage().props;
    const t = (key) => translations[key] || key;

    return (
        <div className="bg-[#0a192f] min-h-screen pt-32 pb-20 px-6">

{/* Digital Charter */}

<section className="mt-20 p-10 bg-gradient-to-b from-white/5 to-transparent rounded-[40px] border border-white/10 shadow-2xl">
    <div className="text-center mb-12">
        <h3 className="text-yellow-500 text-[11px] font-black uppercase tracking-[0.5em] mb-4">
            {t('Charter_Title')}
        </h3>
        <div className="h-px w-20 bg-yellow-500 mx-auto"></div>
    </div>

    <div className="grid grid-cols-1 md:grid-cols-3 gap-12">
        {[1, 2, 3].map((i) => (
            <div key={i} className="space-y-4">
                <h4 className="text-white text-sm font-black uppercase tracking-widest italic">
                    {t(`Charter_Point_${i}_Title`)}
                </h4>
                <p className="text-gray-500 text-[11px] leading-relaxed font-medium">
                    {t(`Charter_Point_${i}_Body`)}
                </p>
            </div>
        ))}
    </div>
</section>
{/* Batas Digital Charter */}

            <div className="max-w-4xl mx-auto">
                {/* Judul Halaman */}
                <h4 className="text-yellow-500 text-[11px] font-black uppercase tracking-[0.5em] mb-4">
                    {t('About_Title')}
                </h4>
                
                {/* Sejarah & Evolusi */}
                <h1 className="text-white text-4xl md:text-6xl font-black italic tracking-tighter leading-tight mb-12">
                    {t('About_History_Title')}
                </h1>

                <div className="grid grid-cols-1 md:grid-cols-12 gap-12 border-t border-white/10 pt-12">
                    <div className="md:col-span-8">
                        <p className="text-gray-400 text-lg leading-relaxed mb-10 font-medium">
                            {t('About_History_Body')}
                        </p>
                        
                        <div className="space-y-6">
                            <h5 className="text-white text-sm font-black uppercase tracking-widest italic underline decoration-yellow-500 decoration-2">
                                {t('About_Values_Title')}
                            </h5>
                            <ul className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <li className="text-white text-[10px] font-black uppercase tracking-widest p-4 bg-white/5 rounded-2xl border border-white/10">
                                    {t('About_Values_1')}
                                </li>
                                <li className="text-white text-[10px] font-black uppercase tracking-widest p-4 bg-white/5 rounded-2xl border border-white/10">
                                    {t('About_Values_2')}
                                </li>
                                <li className="text-white text-[10px] font-black uppercase tracking-widest p-4 bg-white/5 rounded-2xl border border-white/10">
                                    {t('About_Values_3')}
                                </li>
                            </ul>
                        </div>
                    </div>

                    {/* Sidebar Visual: API Jakarta Logo */}
                    <div className="md:col-span-4 flex flex-col items-center justify-center p-8 bg-white/5 rounded-[40px] border border-white/10 grayscale opacity-60 hover:grayscale-0 hover:opacity-100 transition-all">
                         <img src="/images/logo_api_jakarta.png" className="h-20 mb-4" />
                         <p className="text-center text-gray-500 text-[9px] font-bold uppercase tracking-widest">Official Custodian of National Intelligence</p>
                    </div>
                </div>
            </div>
        </div>
    );
}
