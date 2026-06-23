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
        "relative text-[11px] font-bold uppercase tracking-widest text-slate-700 hover:text-amber-500 transition-all duration-300 group py-2";
    const underlineStyle =
        "absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-amber-500 to-yellow-400 transition-all duration-300 group-hover:w-full";
    const mobileNavLinkStyle =
        "text-[13px] font-bold uppercase tracking-wider text-slate-700 hover:text-amber-500 transition-colors duration-300";
    return (
        <nav className="bg-white/95 backdrop-blur-xl border-b border-slate-200 sticky top-0 z-50 shadow-sm">
            <div className="max-w-7xl mx-auto px-6">
                <div className="flex justify-between h-20 items-center">
                    {/* LOGO */}

                    <Link
                        href={route("home")}
                        className="flex flex-col items-start shrink-0"
                    >
                        <img
                            src="/images/logoWeb.png"
                            className="h-12 w-auto"
                            alt="Digestex Global"
                        />

                        <p className="mt-1 text-[9px] tracking-[0.15em] leading-none">
                            <span className="text-[#0B2E59]">
                                Where Textile Meets
                            </span>{" "}
                            <span className="text-[#F97316] font-semibold">
                                Intelligence
                            </span>
                        </p>
                    </Link>

                    {/* DESKTOP MENU */}

                    <div className="hidden lg:flex items-center gap-6 xl:ml-20">
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

                        {/* Intelligence Center */}
                        <Link
                            href={route("market-intelligence")}
                            className={navLinkStyle}
                        >
                            {isEn ? "Intelligence Center" : "Pusat Intelijen"}

                            <span className="ml-2 text-[7px] bg-emerald-500/20 text-emerald-400 px-1.5 py-0.5 rounded uppercase font-bold">
                                Live
                            </span>

                            <span className={underlineStyle} />
                        </Link>

                        {/* Partner Insights */}
                        <Link
                            href={route("partner-insights.index")}
                            className={navLinkStyle}
                        >
                            {isEn ? "Partner Insights" : "Insight Mitra"}

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

                    <div className="hidden lg:flex items-center gap-5">
                        {/* LANGUAGE SWITCHER */}

                        <div className="flex items-center gap-2 bg-slate-100 rounded-full p-1 border border-slate-200 shadow-sm">
                            {[
                                {
                                    code: "id",
                                    label: "Indonesia",
                                    flag: "/images/id.png",
                                },
                                {
                                    code: "en",
                                    label: "English",
                                    flag: "/images/en.png",
                                },
                            ].map((lang, index) => (
                                <div
                                    key={lang.code}
                                    className="flex items-center"
                                >
                                    {index > 0 && (
                                        <div className="w-px h-5 bg-slate-300 mx-1" />
                                    )}

                                    <button
                                        onClick={() =>
                                            toggleLanguage(lang.code)
                                        }
                                        className={`flex items-center gap-2 px-3 py-1.5 rounded-full text-[10px] font-semibold transition-all duration-300 ${
                                            (lang.code === "en" ? isEn : !isEn)
                                                ? "bg-gradient-to-r from-amber-500 to-yellow-500 text-slate-900 shadow-md"
                                                : "text-slate-600 hover:bg-slate-200"
                                        }`}
                                    >
                                        <img
                                            src={lang.flag}
                                            alt={lang.label}
                                            className="w-4 h-4 rounded-full object-cover"
                                        />

                                        <span>{lang.label}</span>
                                    </button>
                                </div>
                            ))}
                        </div>

                        {/* LOGIN BUTTON */}

                        <Link
                            href={route("login")}
                            className="bg-gradient-to-r from-amber-500 to-yellow-500 text-slate-900 px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:scale-105 transition-all shadow-md"
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
                <div className="lg:hidden bg-white border-t border-slate-200 shadow-lg">
                    <div className="px-6 py-6 flex flex-col gap-4">
                        <Link href={route("home")}>
                            {isEn ? "Home" : "Beranda"}
                        </Link>

                        <Link href={route("companies.index")}>
                            {isEn ? "Industry Directory" : "Direktori Industri"}
                        </Link>

                        <Link href={route("sourcing-hub")}>
                            {isEn ? "Sourcing Hub" : "Sourcing Hub"}
                        </Link>

                        <Link href={route("market-intelligence")}>
                            {isEn ? "Market Intelligence" : "Intelijen Pasar"}
                        </Link>

                        <Link href={route("tools.calculator")}>
                            {isEn ? "Tools" : "Alat"}
                        </Link>

                        <Link href={route("pricing.index")}>
                            {isEn ? "Membership" : "Keanggotaan"}
                        </Link>

                        <Link href={route("about")}>
                            {isEn ? "About" : "Tentang"}
                        </Link>

                        <hr className="border-slate-200 my-2" />

                        {/* LANGUAGE SWITCHER */}

                        <div className="flex items-center gap-2">
                            {[
                                {
                                    code: "id",
                                    label: "Indonesia",
                                    flag: "/images/id.png",
                                },
                                {
                                    code: "en",
                                    label: "English",
                                    flag: "/images/en.png",
                                },
                            ].map((lang) => (
                                <button
                                    key={lang.code}
                                    onClick={() => toggleLanguage(lang.code)}
                                    className={`flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold transition-all ${
                                        (lang.code === "en" ? isEn : !isEn)
                                            ? "bg-gradient-to-r from-amber-500 to-yellow-500 text-slate-900"
                                            : "bg-slate-100 text-slate-700"
                                    }`}
                                >
                                    <img
                                        src={lang.flag}
                                        alt={lang.label}
                                        className="w-4 h-4 rounded-full object-cover"
                                    />

                                    <span>{lang.label}</span>
                                </button>
                            ))}
                        </div>

                        <Link
                            href={route("login")}
                            className="mt-2 bg-gradient-to-r from-amber-500 to-yellow-500 text-slate-900 px-4 py-3 rounded-xl text-center font-bold"
                        >
                            {isEn ? "Login" : "Masuk"}
                        </Link>
                    </div>
                </div>
            )}
        </nav>
    );
}
