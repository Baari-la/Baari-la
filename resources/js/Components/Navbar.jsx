import { Link, usePage, router } from "@inertiajs/react";
import { useState } from "react";
import { Menu, X } from "lucide-react";

export default function PublicNavbar() {
    const { props } = usePage();

    const isEn = props.locale === "en";

    const [isOpen, setIsOpen] = useState(false);

    const toggleLanguage = (lang) => {
        router.post(
            route("language.switch", {
                locale: lang,
            }),
            {},
            {
                preserveScroll: true,
                onSuccess: () => setIsOpen(false),
            },
        );
    };

    const navLinkStyle =
        "relative text-[10px] font-black uppercase tracking-widest text-gray-200 hover:text-amber-400 transition-all duration-300 group py-2";

    const underlineStyle =
        "absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-amber-500 to-yellow-400 transition-all duration-300 group-hover:w-full";

    return (
        <nav className="bg-[#0b1329]/90 backdrop-blur-xl border-b border-white/5 sticky top-0 z-50 shadow-2xl">
            <div className="max-w-7xl mx-auto px-6">
                <div className="flex justify-between h-20 items-center">
                    {/* LOGO */}

                    <Link
                        href={route("home")}
                        className="flex items-center gap-3 shrink-0"
                    >
                        <img
                            src="/images/logoWeb.png"
                            className="h-10 w-auto rounded-xl"
                            alt="DigTex"
                        />

                        <div>
                            <div className="font-black text-sm uppercase text-white tracking-tight">
                                DIGTEX
                            </div>

                            <div className="text-[8px] text-amber-500 font-bold uppercase tracking-widest">
                                Textile Industry Ecosystem
                            </div>
                        </div>
                    </Link>

                    {/* DESKTOP MENU */}

                    <div className="hidden lg:flex items-center gap-6">
                        <Link href={route("home")} className={navLinkStyle}>
                            {isEn ? "Home" : "Beranda"}

                            <span className={underlineStyle} />
                        </Link>

                        <Link
                            href={route("companies.index")}
                            className={navLinkStyle}
                        >
                            {isEn ? "Industry Directory" : "Direktori Industri"}

                            <span className={underlineStyle} />
                        </Link>

                        <Link
                            href={route("sourcing-hub")}
                            className={navLinkStyle}
                        >
                            {isEn ? "Sourcing Hub" : "Sourcing Hub"}

                            <span className="ml-2 text-[7px] bg-amber-500/20 text-amber-400 px-1.5 py-0.5 rounded">
                                {isEn ? "COMING SOON" : "SEGERA HADIR"}
                            </span>

                            <span className={underlineStyle} />
                        </Link>

                        <Link
                            href={route("market-intelligence")}
                            className={navLinkStyle}
                        >
                            {isEn ? "Market Intelligence" : "Intelijen Pasar"}

                            <span className="ml-2 text-[7px] bg-amber-500/20 text-amber-400 px-1.5 py-0.5 rounded">
                                {isEn ? "COMING SOON" : "SEGERA HADIR"}
                            </span>

                            <span className={underlineStyle} />
                        </Link>

                        <Link
                            href={route("tools.calculator")}
                            className={navLinkStyle}
                        >
                            {isEn ? "Tools" : "Alat"}

                            <span className={underlineStyle} />
                        </Link>

                        <Link
                            href={route("pricing.index")}
                            className={navLinkStyle}
                        >
                            {isEn ? "Membership" : "Keanggotaan"}

                            <span className={underlineStyle} />
                        </Link>

                        <Link href={route("about")} className={navLinkStyle}>
                            {isEn ? "About" : "Tentang"}

                            <span className={underlineStyle} />
                        </Link>
                    </div>

                    {/* RIGHT SIDE */}

                    <div className="hidden lg:flex items-center gap-4">
                        <div className="flex bg-white/5 rounded-full p-1 border border-white/5">
                            {["id", "en"].map((lang) => (
                                <button
                                    key={lang}
                                    onClick={() => toggleLanguage(lang)}
                                    className={`px-3 py-1 rounded-full text-[9px] font-black uppercase transition-all duration-300 ${
                                        (lang === "en" ? isEn : !isEn)
                                            ? "bg-gradient-to-r from-amber-500 to-yellow-500 text-[#030712]"
                                            : "text-gray-400 hover:text-white"
                                    }`}
                                >
                                    {lang}
                                </button>
                            ))}
                        </div>

                        <Link
                            href={route("login")}
                            className="bg-gradient-to-r from-amber-500 to-yellow-500 text-[#030712] px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:scale-105 transition-all"
                        >
                            {isEn ? "Login" : "Masuk"}
                        </Link>
                    </div>

                    {/* MOBILE BUTTON */}

                    <button
                        onClick={() => setIsOpen(!isOpen)}
                        className="lg:hidden text-amber-500"
                    >
                        {isOpen ? <X /> : <Menu />}
                    </button>
                </div>
            </div>

            {/* MOBILE MENU */}

            {isOpen && (
                <div className="lg:hidden bg-[#0b1329] border-t border-white/5">
                    <div className="px-6 py-6 flex flex-col gap-4">
                        <Link href={route("home")}>Home</Link>

                        <Link href={route("companies.index")}>
                            Industry Directory
                        </Link>

                        <Link href={route("sourcing-hub")}>Sourcing Hub</Link>

                        <Link href={route("market-intelligence")}>
                            Market Intelligence
                        </Link>

                        <Link href={route("tools.calculator")}>Tools</Link>

                        <Link href={route("pricing.index")}>Membership</Link>

                        <Link href={route("about")}>About</Link>

                        <hr className="border-white/10" />

                        <Link href={route("login")}>Login</Link>
                    </div>
                </div>
            )}
        </nav>
    );
}
