import { Link, usePage } from "@inertiajs/react";

export default function Footer() {
    const { translations } = usePage().props;
    const t = (key) => translations[key] || key;

    return (
        <footer className="bg-[#050c1b] border-t border-white/5 pt-20 pb-10">
            <div className="max-w-7xl mx-auto px-6">
                <div className="grid grid-cols-1 md:grid-cols-3 gap-16 mb-20">
                    {/* COLUMN 1: BRAND & VISION */}
                    <div className="space-y-6">
                        <Link href="/" className="font-black text-2xl tracking-tighter italic text-white uppercase">
                            Digestex<span className="text-yellow-500">GLOBAL</span>
                        </Link>
                        <p className="text-gray-500 text-[10px] leading-relaxed font-bold uppercase tracking-widest italic">
                            {t('Footer_Vision')}
                        </p>
                    </div>

                    {/* COLUMN 2: STRATEGIC ENDORSEMENT (API JAKARTA) */}
                    <div className="space-y-6">
                        <h4 className="text-[10px] font-black uppercase text-yellow-500 tracking-[0.4em]">
                            {t('Footer_Strategic_Endorsement')}
                        </h4>
                        <div className="flex items-start gap-4">
                            <img
                                src="/images/logo_api_jakarta.png"
                                className="h-12 w-auto opacity-80 grayscale hover:grayscale-0 transition-all"
                                alt="API Jakarta"
                            />
                            <div className="border-l border-white/10 pl-4">
                                <p className="text-white text-[10px] font-black uppercase tracking-tighter leading-none mb-1">
                                    API JAKARTA
                                </p>
                                <p className="text-gray-500 text-[9px] leading-tight font-bold italic">
                                    {t('Footer_API_Description')}
                                </p>
                            </div>
                        </div>
                    </div>

                    {/* COLUMN 3: GLOBAL NETWORK */}
                    <div className="space-y-6 text-right md:text-left">
                        <h4 className="text-[10px] font-black uppercase text-white tracking-[0.4em]">
                            {t('Footer_Global_Intelligence')}
                        </h4>
                        <ul className="space-y-3">
                            <li>
                                <Link href={route("partnership")} className="text-gray-500 hover:text-yellow-500 text-[10px] font-black uppercase tracking-widest transition-colors">
                                    {t('Footer_Link_Partnership')}
                                </Link>
                            </li>
                            <li>
                                <Link href={route("companies.index")} className="text-gray-500 hover:text-yellow-500 text-[10px] font-black uppercase tracking-widest transition-colors">
                                    {t('Footer_Link_Directory')}
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>

                {/* BOTTOM BAR: COPYRIGHT & DISCLAIMER */}
                <div className="pt-10 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-4 text-center md:text-left">
                    <p className="text-gray-600 text-[8px] font-black uppercase tracking-[0.3em]">
                        &copy; 2026 Digestex Global. {t('Footer_Independent_Platform')}
                    </p>
                    <div className="flex gap-6 text-gray-600 text-[8px] font-black uppercase tracking-[0.3em]">
                        <span>Security: AES-256</span>
                        <span>Engine: PHP 8.3 Optimized</span>
                    </div>
                </div>
            </div>
        </footer>
    );
}
