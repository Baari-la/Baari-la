import { Link } from "@inertiajs/react";

import IntelligenceMenu from "./IntelligenceMenu";
import VisibilityMenu from "./VisibilityMenu";
import EcosystemMenu from "./EcosystemMenu";

export default function DesktopMenu({
    isEn,

    intelligenceLinks,

    visibilityLinks,

    ecosystemLinks,

    isIntelOpen,
    setIsIntelOpen,

    isVisibilityOpen,
    setIsVisibilityOpen,

    isEcosystemOpen,
    setIsEcosystemOpen,
}) {
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

    return (
        <div className="hidden lg:flex items-center gap-6 xl:ml-20">
            {/* HOME */}

            <Link href={route("home")} className={navLinkStyle}>
                {isEn ? "Home" : "Beranda"}

                <span className={underlineStyle} />
            </Link>

            {/* INTELLIGENCE */}

            <IntelligenceMenu
                isEn={isEn}
                links={intelligenceLinks}
                isOpen={isIntelOpen}
                setIsOpen={setIsIntelOpen}
            />

            {/* VISIBILITY */}

            <VisibilityMenu
                isEn={isEn}
                links={visibilityLinks}
                isOpen={isVisibilityOpen}
                setIsOpen={setIsVisibilityOpen}
            />

            {/* ECOSYSTEM */}

            <EcosystemMenu
                isEn={isEn}
                links={ecosystemLinks}
                isOpen={isEcosystemOpen}
                setIsOpen={setIsEcosystemOpen}
            />

            {/* TOOLS */}

            <Link href={route("tools.calculator")} className={navLinkStyle}>
                TOOLS
                <span className={underlineStyle} />
            </Link>

            {/* ABOUT */}

            <Link href={route("about")} className={navLinkStyle}>
                {isEn ? "About" : "Tentang"}

                <span className={underlineStyle} />
            </Link>
        </div>
    );
}
