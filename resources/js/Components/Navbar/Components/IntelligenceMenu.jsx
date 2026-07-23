import { Link } from "@inertiajs/react";
import { ChevronDown } from "lucide-react";

export default function IntelligenceMenu({
    isEn,
    links = [],
    isOpen,
    setIsOpen,
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

    return (
        <div
            className="relative pb-2"
            onMouseEnter={() => setIsOpen(true)}
            onMouseLeave={() => setIsOpen(false)}
        >
            {/* BUTTON */}

            <button className={navLinkStyle}>
                INTELLIGENCE
                <span
                    className="
                        ml-2
                        rounded-full
                        bg-indigo-100
                        px-2
                        py-0.5
                        text-[8px]
                        font-black
                        text-indigo-700
                    "
                >
                    AI
                </span>
                <ChevronDown className="ml-1 inline h-3 w-3" />
            </button>

            {/* DROPDOWN */}

            <div
                className={`
                    absolute
                    left-0
                    top-full

                    w-[340px]

                    origin-top-left
                    overflow-hidden
                    rounded-2xl
                    border
                    border-slate-200
                    bg-white
                    py-2
                    shadow-2xl

                    transition-all
                    duration-200

                    ${
                        isOpen
                            ? "opacity-100 scale-100"
                            : "pointer-events-none opacity-0 scale-95"
                    }
                `}
            >
                {links.map((item, index) => {
                    if (item.section) {
                        return (
                            <div key={`section-${index}`}>
                                {index !== 0 && (
                                    <div className="my-2 border-t" />
                                )}

                                <div
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
                            </div>
                        );
                    }

                    return (
                        <Link
                            key={item.routeName}
                            href={route(item.routeName)}
                            className={dropdownItemStyle}
                        >
                            {isEn ? item.en : item.id}
                        </Link>
                    );
                })}
            </div>
        </div>
    );
}
