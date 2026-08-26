import { Link } from "@inertiajs/react";
import { ChevronDown } from "lucide-react";

export default function ProgramMenu({ isEn, links = [], isOpen, setIsOpen }) {
    const navLinkStyle = `
        relative
        flex
        items-center
        gap-1
        text-[11px]
        font-bold
        uppercase
        tracking-widest
        text-slate-700
        hover:text-amber-500
        transition-all
        duration-300
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
        <div
            className="relative"
            onMouseEnter={() => setIsOpen(true)}
            onMouseLeave={() => setIsOpen(false)}
        >
            <button type="button" className={`${navLinkStyle} group`}>
                {isEn ? "Program" : "Program"}

                <ChevronDown
                    className={`
                        h-3.5
                        w-3.5
                        transition-transform
                        duration-200
                        ${isOpen ? "rotate-180" : ""}
                    `}
                />

                <span className={underlineStyle} />
            </button>

            {isOpen && (
                <div
                    className="
        absolute
        left-1/2
        top-full
        z-50
        w-80
        -translate-x-1/2
        pt-3
    "
                >
                    {links.map((item, index) => {
                        if (item.section) {
                            return (
                                <div
                                    key={`section-${index}`}
                                    className="
                                        border-b
                                        border-slate-100
                                        bg-slate-50
                                        px-5
                                        py-3
                                        text-[9px]
                                        font-black
                                        uppercase
                                        tracking-[0.2em]
                                        text-slate-400
                                    "
                                >
                                    {item.section}
                                </div>
                            );
                        }

                        return (
                            <Link
                                key={item.routeName || `program-${index}`}
                                href={route(item.routeName)}
                                onClick={() => setIsOpen(false)}
                                className="
                                    block
                                    px-5
                                    py-4
                                    text-sm
                                    font-bold
                                    text-slate-700
                                    transition
                                    hover:bg-amber-50
                                    hover:text-amber-600
                                "
                            >
                                {isEn ? item.en : item.id}
                            </Link>
                        );
                    })}
                </div>
            )}
        </div>
    );
}
