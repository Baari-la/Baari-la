import { Link, usePage, router } from "@inertiajs/react";
import { useState } from "react";

import { Menu, X, ChevronDown } from "lucide-react";

export default function PublicNavbar() {
    const { props } = usePage();

    const isEn = props.locale === "en";

    const [isOpen, setIsOpen] = useState(false);

    const [isIntelOpen, setIsIntelOpen] = useState(false);

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

    const navLinkStyle = `
    relative
    text-[11px]
    font-bold
    uppercase
    tracking-widest
    text-slate-700
    hover:text-amber-500
    transition-all
    duration-300
    group
    py-2
`;

    const underlineStyle = `
    absolute
    bottom-0
    left-0
    w-0
    h-0.5
    bg-gradient-to-r
    from-amber-500
    to-yellow-400
    transition-all
    duration-300
    group-hover:w-full
`;

    const mobileNavLinkStyle =
        "text-[13px] font-bold uppercase tracking-wider text-slate-700 hover:text-amber-500 transition-colors duration-300";
    const dropdownItemStyle = `
    block
    px-4
    py-3
    text-[11px]
    font-semibold
    uppercase
    tracking-wider
    text-slate-700
    hover:bg-slate-50
    hover:text-amber-500
    transition-colors
`;

    // List sub-menu Intelligence Center agar DRY (Don't Repeat Yourself)
    const intelligenceLinks = [
        {
            section: "PLATFORM",
        },

        {
            routeName: "intelligence.executive.index",

            en: "Executive Dashboard",

            id: "Dashboard Eksekutif",
        },

        {
            routeName: "intelligence.company.index",

            en: "Company Intelligence",

            id: "Intelijen Perusahaan",
        },

        {
            routeName: "intelligence.knowledge-graph.index",

            en: "Knowledge Graph",

            id: "Knowledge Graph",
        },

        {
            routeName: "intelligence.master-data.index",

            en: "Master Data",

            id: "Master Data",
        },

        {
            routeName: "intelligence.visualization.index",

            en: "Visualization Lab",

            id: "Visualization Lab",
        },

        {
            section: "INSIGHTS",
        },

        {
            routeName: "intelligence.news",

            en: "News Intelligence",

            id: "Intelijen Berita",
        },

        {
            routeName: "intelligence.market",

            en: "Market Intelligence",

            id: "Intelijen Pasar",
        },

        {
            routeName: "intelligence.trade",

            en: "Trade Intelligence",

            id: "Intelijen Perdagangan",
        },

        {
            routeName: "intelligence.policy",

            en: "Policy Intelligence",

            id: "Intelijen Kebijakan",
        },

        {
            routeName: "intelligence.country",

            en: "Country Intelligence",

            id: "Intelijen Negara",
        },
        {
            section: "INSIGHTS",
        },

        {
            routeName: "intelligence.weekly",

            en: "Weekly Intelligence Report",

            id: "Laporan Mingguan",
        },

        {
            routeName: "intelligence.news",

            en: "News Intelligence",

            id: "Intelijen Berita",
        },
    ];

    const visibilityLinks = [
        {
            section: "VISIBILITY",
        },

        {
            routeName: "companies.index",
            en: "Industry Directory",
            id: "Direktori Industri",
        },

        {
            routeName: "passport.demo",
            en: "Digital Company Passport",
            id: "Paspor Digital Perusahaan",
        },

        {
            routeName: "program.digital-directory-visibility",

            en: "Visibility Program 2026",
            id: "Program Visibility 2026",
        },

        {
            routeName: "ranking.index",
            en: "Executive Rankings",
            id: "Peringkat Eksekutif",
        },

        {
            routeName: "pricing.index",
            en: "Membership",
            id: "Keanggotaan",
        },
    ];
    const ecosystemLinks = [
        {
            section: "ECOSYSTEM",
        },

        {
            routeName: "smart-business-matching",

            en: "Smart Business Matching™",

            id: "Smart Business Matching™",
        },

        {
            routeName: "build-my-supply-chain",

            en: "Build My Supply Chain™",

            id: "Build My Supply Chain™",
        },

        {
            routeName: "buyer-discovery",

            en: "Buyer Discovery™",

            id: "Buyer Discovery™",
        },

        {
            routeName: "sourcing-hub",

            en: "Sourcing Hub",

            id: "Sourcing Hub",
        },
    ];
    const [isVisibilityOpen, setIsVisibilityOpen] = useState(false);

    const [isEcosystemOpen, setIsEcosystemOpen] = useState(false);

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
                        <div
                            className="relative pb-2"
                            onMouseEnter={() => setIsIntelOpen(true)}
                            onMouseLeave={() => setIsIntelOpen(false)}
                        >
                            <button className={navLinkStyle}>
                                INTELLIGENCE
                                <span
                                    className="
            ml-2
            px-2
            py-0.5
            rounded-full
            bg-indigo-100
            text-indigo-700
            text-[8px]
            font-black
        "
                                >
                                    AI
                                </span>
                                <ChevronDown
                                    className="
            inline
            ml-1
            w-3
            h-3
        "
                                />
                            </button>

                            <div
                                className={`
        absolute
        left-0
        top-full

        w-[340px]

        bg-white
        border
        border-slate-200

        rounded-2xl
        shadow-2xl

        overflow-hidden
        origin-top-left

        py-2
        z-50

        transition-all
        duration-200

        ${
            isIntelOpen
                ? "opacity-100 scale-100"
                : "opacity-0 scale-95 pointer-events-none"
        }
    `}
                            >
                                {intelligenceLinks.map((item, index) => {
                                    if (item.section) {
                                        return (
                                            <>
                                                {index !== 0 && (
                                                    <div className="border-t my-2" />
                                                )}

                                                <div
                                                    key={index}
                                                    className="
                    px-4
                    pt-2
                    pb-1
                    text-[10px]
                    font-black
                    tracking-[0.2em]
                    text-slate-400
                "
                                                >
                                                    {item.section}
                                                </div>
                                            </>
                                        );
                                    }

                                    return (
                                        <Link
                                            key={index}
                                            href={route(item.routeName)}
                                            className={dropdownItemStyle}
                                        >
                                            {isEn ? item.en : item.id}
                                        </Link>
                                    );
                                })}
                            </div>
                        </div>

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
                        <Link
                            href={route("admin.import-kemendag")}
                            className="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-md"
                        >
                            <svg
                                className="w-5 h-5 mr-3"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    strokeWidth="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"
                                />
                            </svg>
                            Import Data Kemendag
                        </Link>
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
