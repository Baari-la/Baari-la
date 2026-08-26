import { Link } from "@inertiajs/react";

import IntelligenceMenu from "./IntelligenceMenu";
import ProgramMenu from "./ProgramMenu";

export default function DesktopMenu({
    isEn,

    intelligenceLinks,
    programLinks,
    upcomingLinks,

    isIntelOpen,
    setIsIntelOpen,

    isProgramOpen,
    setIsProgramOpen,
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

    const upcomingStyle = `
        relative
        inline-flex
        items-center
        gap-2
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
        cursor-default
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

            {/* PROGRAM */}

            <ProgramMenu
                isEn={isEn}
                links={programLinks}
                isOpen={isProgramOpen}
                setIsOpen={setIsProgramOpen}
            />

            {/* SOURCING HUB — UPCOMING */}

            <div className="relative">
                <button
                    type="button"
                    className={upcomingStyle}
                    aria-label={
                        isEn
                            ? "Sourcing Hub — Upcoming"
                            : "Sourcing Hub — Segera Hadir"
                    }
                >
                    {isEn ? "Sourcing Hub" : "Sourcing Hub"}

                    <span
                        className="
                            rounded-full
                            bg-slate-100
                            px-2
                            py-0.5
                            text-[8px]
                            font-black
                            tracking-wider
                            text-slate-500
                        "
                    >
                        UPCOMING
                    </span>

                    <span className={underlineStyle} />
                </button>
            </div>

            {/* RADAR — UPCOMING */}

            <div className="relative">
                <button
                    type="button"
                    className={upcomingStyle}
                    aria-label={
                        isEn ? "Radar — Upcoming" : "Radar — Segera Hadir"
                    }
                >
                    RADAR
                    <span
                        className="
                            rounded-full
                            bg-indigo-50
                            px-2
                            py-0.5
                            text-[8px]
                            font-black
                            tracking-wider
                            text-indigo-600
                        "
                    >
                        UPCOMING
                    </span>
                    <span className={underlineStyle} />
                </button>
            </div>

            {/* ABOUT */}

            <Link href={route("about")} className={navLinkStyle}>
                {isEn ? "About" : "Tentang"}

                <span className={underlineStyle} />
            </Link>
        </div>
    );
}
