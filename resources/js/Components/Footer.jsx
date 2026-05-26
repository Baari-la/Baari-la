import { Link, usePage } from "@inertiajs/react";

export default function Footer() {
    const { translations } = usePage().props;
    const t = (key) => translations[key] || key;

    return (
        <footer className="relative overflow-hidden border-t border-white/5 bg-gradient-to-b from-[#050c1b] via-[#07111f] to-black pt-24 pb-10">
            {/* Ambient Glow */}
            <div className="absolute left-10 top-10 h-40 w-40 rounded-full bg-blue-500/10 blur-[100px]" />
            <div className="absolute right-10 bottom-10 h-48 w-48 rounded-full bg-yellow-500/10 blur-[120px]" />

            <div className="relative z-10 max-w-7xl mx-auto px-6 lg:px-8">
                {/* Main Grid */}
                <div className="grid grid-cols-1 md:grid-cols-3 gap-14 mb-20">
                    {/* COLUMN 1 — BRAND */}
                    <div className="space-y-6">
                        <Link
                            href="/"
                            className="inline-block font-black text-3xl tracking-tighter italic text-white uppercase"
                        >
                            Digestex
                            <span className="text-yellow-500">MEDIA</span>
                        </Link>

                        <p className="text-gray-400 text-[11px] leading-relaxed uppercase tracking-[0.18em] font-bold max-w-sm">
                            {t("Footer_Vision")}
                        </p>

                        <p className="text-gray-500 text-[10px] leading-relaxed max-w-md">
                            {t("Footer_Independent_Platform")}
                        </p>
                    </div>

                    {/* COLUMN 2 — INTELLIGENCE */}
                    <div className="space-y-6">
                        <h4 className="text-[10px] font-black uppercase text-yellow-500 tracking-[0.35em]">
                            {t("Footer_Intelligence")}
                        </h4>

                        <ul className="space-y-3">
                            <li className="text-gray-400 text-[11px] font-bold uppercase tracking-[0.18em]">
                                {t("Footer_Market_Intelligence")}
                            </li>
                            <li className="text-gray-400 text-[11px] font-bold uppercase tracking-[0.18em]">
                                {t("Footer_Supply_Chain")}
                            </li>
                            <li className="text-gray-400 text-[11px] font-bold uppercase tracking-[0.18em]">
                                {t("Footer_Trade_Visibility")}
                            </li>
                            <li className="text-gray-400 text-[11px] font-bold uppercase tracking-[0.18em]">
                                {t("Footer_Risk_Monitoring")}
                            </li>
                        </ul>
                    </div>

                    {/* COLUMN 3 — PLATFORM */}
                    <div className="space-y-6">
                        <h4 className="text-[10px] font-black uppercase text-white tracking-[0.35em]">
                            {t("Footer_Platform")}
                        </h4>

                        <ul className="space-y-4">
                            <li>
                                <Link
                                    href={route("partnership")}
                                    className="text-gray-400 hover:text-yellow-500 text-[11px] font-black uppercase tracking-[0.18em] transition-colors"
                                >
                                    {t("Footer_Link_Partnership")}
                                </Link>
                            </li>

                            <li>
                                <Link
                                    href={route("companies.index")}
                                    className="text-gray-400 hover:text-yellow-500 text-[11px] font-black uppercase tracking-[0.18em] transition-colors"
                                >
                                    {t("Footer_Link_Directory")}
                                </Link>
                            </li>

                            {/* <li>
                                <Link
                                    href={route("about")}
                                    className="text-gray-400 hover:text-yellow-500 text-[11px] font-black uppercase tracking-[0.18em] transition-colors"
                                >
                                    {t("Footer_Link_About")}
                                </Link>
                            </li>

                            <li>
                                <Link
                                    href={route("contact")}
                                    className="text-gray-400 hover:text-yellow-500 text-[11px] font-black uppercase tracking-[0.18em] transition-colors"
                                >
                                    {t("Footer_Link_Contact")}
                                </Link>
                            </li> */}
                        </ul>
                    </div>
                </div>

                {/* Divider */}
                <div className="border-t border-white/5 pt-10">
                    <div className="flex flex-col lg:flex-row justify-between items-center gap-6 text-center lg:text-left">
                        {/* Copyright */}
                        <p className="text-gray-600 text-[9px] font-black uppercase tracking-[0.28em]">
                            © 2026 DIGESTEXMEDIA ·{" "}
                            {t("Footer_Trusted_Intelligence")}
                        </p>

                        {/* Platform Indicators */}
                        <div className="flex flex-wrap justify-center gap-6 text-gray-600 text-[9px] font-black uppercase tracking-[0.22em]">
                            <span>Independent Platform</span>
                            <span>Global Visibility</span>
                            <span>Secure Infrastructure</span>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    );
}
