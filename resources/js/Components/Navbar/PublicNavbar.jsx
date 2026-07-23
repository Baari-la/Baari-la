import { useState } from "react";
import { router, usePage } from "@inertiajs/react";

import LogoSection from "./Components/LogoSection";
import DesktopMenu from "./Components/DesktopMenu";
import LanguageSwitcher from "./Components/LanguageSwitcher";
import LoginButton from "./Components/LoginButton";
import MobileMenu from "./Components/MobileMenu";

export default function PublicNavbar() {
    const { props } = usePage();

    const isEn = props.locale === "en";

    /*
    |--------------------------------------------------------------------------
    | States
    |--------------------------------------------------------------------------
    */

    const [isOpen, setIsOpen] = useState(false);

    const [isIntelOpen, setIsIntelOpen] = useState(false);

    const [isVisibilityOpen, setIsVisibilityOpen] = useState(false);

    const [isEcosystemOpen, setIsEcosystemOpen] = useState(false);

    /*
    |--------------------------------------------------------------------------
    | Language Switch
    |--------------------------------------------------------------------------
    */

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

            en: "Master Data Explorer",

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
            routeName: "intelligence.weekly",

            en: "Weekly Intelligence Report",

            id: "Laporan Mingguan",
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
    ];

    /*
    |--------------------------------------------------------------------------
    | Visibility
    |--------------------------------------------------------------------------
    */

    const visibilityLinks = [
        {
            section: "VISIBILITY",
        },

        {
            routeName: "companies.index",

            en: "Industry Directoriy",

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

    /*
    |--------------------------------------------------------------------------
    | Ecosystem
    |--------------------------------------------------------------------------
    */

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
            <div className="mx-auto max-w-7xl px-6">
                <div className="flex h-20 items-center justify-between">
                    {/* LOGO */}

                    <LogoSection />

                    {/* DESKTOP MENU */}

                    <DesktopMenu
                        isEn={isEn}
                        intelligenceLinks={intelligenceLinks}
                        visibilityLinks={visibilityLinks}
                        ecosystemLinks={ecosystemLinks}
                        isIntelOpen={isIntelOpen}
                        setIsIntelOpen={setIsIntelOpen}
                        isVisibilityOpen={isVisibilityOpen}
                        setIsVisibilityOpen={setIsVisibilityOpen}
                        isEcosystemOpen={isEcosystemOpen}
                        setIsEcosystemOpen={setIsEcosystemOpen}
                    />

                    {/* RIGHT SIDE */}

                    <div
                        className="
                            hidden
                            lg:flex
                            items-center
                            gap-5
                        "
                    >
                        <LanguageSwitcher
                            isEn={isEn}
                            toggleLanguage={toggleLanguage}
                        />

                        <LoginButton isEn={isEn} />
                    </div>

                    {/* MOBILE */}

                    <MobileMenu
                        isOpen={isOpen}
                        setIsOpen={setIsOpen}
                        isEn={isEn}
                        toggleLanguage={toggleLanguage}
                        intelligenceLinks={intelligenceLinks}
                        visibilityLinks={visibilityLinks}
                        ecosystemLinks={ecosystemLinks}
                    />
                </div>
            </div>
        </nav>
    );
}
