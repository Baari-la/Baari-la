import { useState } from "react";
import { Link, router, usePage } from "@inertiajs/react";
import { Menu, X, Globe2 } from "lucide-react";

import LogoSection from "./Components/LogoSection";
import DesktopMenu from "./Components/DesktopMenu";

export default function PublicNavbar() {
    const { props } = usePage();

    const isEn = props.locale === "en";

    const [isOpen, setIsOpen] = useState(false);

    const [isIntelOpen, setIsIntelOpen] = useState(false);

    const [isProgramOpen, setIsProgramOpen] = useState(false);

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

    /*
    |--------------------------------------------------------------------------
    | Intelligence
    |--------------------------------------------------------------------------
    */

    const intelligenceLinks = [
        {
            section: "TRADE INTELLIGENCE",
        },

        {
            routeName: "trade.fiber.intelligence",
            en: "Fiber Intelligence",
            id: "Intelijen Fiber",
        },

        {
            routeName: "trade.yarn.intelligence",
            en: "Yarn Intelligence",
            id: "Intelijen Benang",
        },

        {
            routeName: "intelligence.thread",
            en: "Thread Intelligence",
            id: "Intelijen Sewing Thread",
        },

        {
            routeName: "trade.fabric.intelligence",
            en: "Fabric Intelligence",
            id: "Intelijen Kain",
        },

        {
            routeName: "trade.garment.intelligence",
            en: "Garment Intelligence",
            id: "Intelijen Garmen",
        },

        {
            routeName: "trade.home-textile.intelligence",
            en: "Home Textile Intelligence",
            id: "Intelijen Home Textile",
        },

        {
            routeName: "trade.technical-textile.intelligence",
            en: "Technical Textile Intelligence",
            id: "Intelijen Technical Textile",
        },

        {
            routeName: "trade.specialty-textile.intelligence",
            en: "Specialty Textile Intelligence",
            id: "Intelijen Specialty Textile",
        },

        /*
    |--------------------------------------------------------------------------
    | FUTURE INTELLIGENCE MODULES
    | ---------------------------------------------------------------
    | Disabled from Public Navbar until UI + functionality are ready
    | for public / Coats demonstration.
    |--------------------------------------------------------------------------
    */

        // {
        //     routeName: "intelligence.executive.index",
        //     en: "Executive Dashboard",
        //     id: "Dashboard Eksekutif",
        // },

        // {
        //     routeName: "intelligence.company.index",
        //     en: "Company Intelligence",
        //     id: "Intelijen Perusahaan",
        // },

        // {
        //     routeName: "intelligence.knowledge-graph.index",
        //     en: "Knowledge Graph",
        //     id: "Knowledge Graph",
        // },

        // {
        //     routeName: "intelligence.master-data.index",
        //     en: "Master Data Explorer",
        //     id: "Master Data",
        // },

        // {
        //     routeName: "intelligence.visualization.index",
        //     en: "Visualization Lab",
        //     id: "Visualization Lab",
        // },

        // {
        //     routeName: "intelligence.weekly",
        //     en: "Weekly Intelligence Report",
        //     id: "Laporan Mingguan",
        // },

        // {
        //     routeName: "intelligence.news",
        //     en: "News Intelligence",
        //     id: "Intelijen Berita",
        // },

        // {
        //     routeName: "intelligence.market",
        //     en: "Market Intelligence",
        //     id: "Intelijen Pasar",
        // },

        // {
        //     routeName: "intelligence.policy",
        //     en: "Policy Intelligence",
        //     id: "Intelijen Kebijakan",
        // },

        // {
        //     routeName: "intelligence.country",
        //     en: "Country Intelligence",
        //     id: "Intelijen Negara",
        // },
    ];

    /*
    |--------------------------------------------------------------------------
    | Program
    |--------------------------------------------------------------------------
    */

    const programLinks = [
        {
            section: "DIGESTEX PROGRAM",
        },

        {
            routeName: "program.digital-directory",

            en: "Strategic Industry & Visibility Program",

            id: "Program Strategic Industry & Visibility",
        },
    ];

    /*
|--------------------------------------------------------------------------
| Upcoming Navigation
|--------------------------------------------------------------------------
*/

    const upcomingLinks = [
        {
            key: "sourcing-hub",
            en: "Sourcing Hub",
            id: "Sourcing Hub",
        },

        {
            key: "radar",
            en: "Radar",
            id: "Radar",
        },
    ];

    /*
    |--------------------------------------------------------------------------
    | Mobile Menu Item Renderer
    |--------------------------------------------------------------------------
    */

    const mobileItems = [
        {
            type: "link",
            title: isEn ? "Home" : "Beranda",
            href: route("home"),
        },

        {
            type: "link",
            title: isEn ? "Trade Intelligence" : "Intelijen Perdagangan",
            href: route("intelligence.trade"),
        },

        {
            type: "program",
            title: isEn ? "Program" : "Program",
        },

        {
            type: "upcoming",
            title: "Sourcing Hub",
        },

        {
            type: "upcoming",
            title: "Radar",
        },

        {
            type: "link",
            title: isEn ? "About" : "Tentang",
            href: route("about"),
        },
    ];

    return (
        <nav
            className="
                sticky
                top-0
                z-50
                border-b
                border-slate-200
                bg-white/95
                backdrop-blur-xl
                shadow-sm
            "
        >
            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div className="flex h-20 items-center justify-between gap-4">
                    {/* LOGO */}

                    <LogoSection />

                    {/* DESKTOP */}

                    <DesktopMenu
                        isEn={isEn}
                        intelligenceLinks={intelligenceLinks}
                        programLinks={programLinks}
                        upcomingLinks={upcomingLinks}
                        isIntelOpen={isIntelOpen}
                        setIsIntelOpen={setIsIntelOpen}
                        isProgramOpen={isProgramOpen}
                        setIsProgramOpen={setIsProgramOpen}
                    />

                    {/* DESKTOP RIGHT */}

                    <div className="hidden items-center gap-4 lg:flex">
                        <button
                            type="button"
                            onClick={() => toggleLanguage(isEn ? "id" : "en")}
                            className="
                                inline-flex
                                items-center
                                gap-2
                                rounded-xl
                                px-3
                                py-2
                                text-xs
                                font-black
                                uppercase
                                tracking-widest
                                text-slate-700
                                transition
                                hover:bg-slate-100
                            "
                        >
                            <Globe2 size={15} />

                            {isEn ? "ID" : "EN"}
                        </button>

                        <Link
                            href={route("login")}
                            className="
                                inline-flex
                                items-center
                                rounded-xl
                                bg-[#0a192f]
                                px-4
                                py-2.5
                                text-xs
                                font-black
                                uppercase
                                tracking-widest
                                text-white
                                transition
                                hover:bg-[#11294c]
                            "
                        >
                            {isEn ? "Login" : "Masuk"}
                        </Link>
                    </div>

                    {/* MOBILE BUTTON */}

                    <button
                        type="button"
                        onClick={() => setIsOpen(!isOpen)}
                        className="
                            inline-flex
                            items-center
                            justify-center
                            rounded-xl
                            p-2.5
                            text-[#0a192f]
                            transition
                            hover:bg-slate-100
                            lg:hidden
                        "
                        aria-label={isOpen ? "Close menu" : "Open menu"}
                    >
                        {isOpen ? <X size={24} /> : <Menu size={24} />}
                    </button>
                </div>

                {/* MOBILE MENU */}

                {isOpen && (
                    <div className="border-t border-slate-200 py-4 lg:hidden">
                        <div className="space-y-1">
                            {mobileItems.map((item) => (
                                <Link
                                    key={item.href}
                                    href={item.href}
                                    onClick={() => setIsOpen(false)}
                                    className="
                                            block
                                            rounded-xl
                                            px-4
                                            py-3
                                            text-sm
                                            font-bold
                                            text-[#0a192f]
                                            transition
                                            hover:bg-slate-100
                                        "
                                >
                                    {item.title}
                                </Link>
                            ))}

                            <div className="mt-3 border-t border-slate-200 pt-3">
                                <div className="flex items-center gap-2 px-4">
                                    <button
                                        type="button"
                                        onClick={() => {
                                            toggleLanguage("id");
                                            setIsOpen(false);
                                        }}
                                        className="
                                            rounded-lg
                                            border
                                            border-slate-200
                                            px-3
                                            py-2
                                            text-xs
                                            font-black
                                        "
                                    >
                                        ID
                                    </button>

                                    <button
                                        type="button"
                                        onClick={() => {
                                            toggleLanguage("en");
                                            setIsOpen(false);
                                        }}
                                        className="
                                            rounded-lg
                                            border
                                            border-slate-200
                                            px-3
                                            py-2
                                            text-xs
                                            font-black
                                        "
                                    >
                                        EN
                                    </button>

                                    <Link
                                        href={route("login")}
                                        onClick={() => setIsOpen(false)}
                                        className="
                                            ml-auto
                                            rounded-lg
                                            bg-[#0a192f]
                                            px-4
                                            py-2
                                            text-xs
                                            font-black
                                            uppercase
                                            tracking-widest
                                            text-white
                                        "
                                    >
                                        {isEn ? "Login" : "Masuk"}
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </nav>
    );
}
