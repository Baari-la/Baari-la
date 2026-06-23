import React from "react";

export default function HoverDropdown({
    icon,
    label,
    children,
    width = "w-72",
}) {
    return (
        <div className="relative group">
            <button
                className="
                    flex items-center gap-2
                    px-3 py-2
                    rounded-xl
                    text-[11px]
                    font-bold
                    tracking-wide
                    text-slate-700
                    hover:text-amber-500
                    hover:bg-slate-50
                    transition-all
                    duration-200
                "
            >
                {icon}

                <span>{label}</span>

                <span className="text-[10px] text-slate-400">▼</span>
            </button>

            <div
                className={`
                    absolute
                    left-0
                    top-full
                    mt-2
                    ${width}
                    bg-white
                    border
                    border-slate-200
                    rounded-2xl
                    shadow-xl
                    p-2
                    opacity-0
                    invisible
                    translate-y-2
                    group-hover:opacity-100
                    group-hover:visible
                    group-hover:translate-y-0
                    transition-all
                    duration-200
                    z-[9999]
                `}
            >
                {children}
            </div>
        </div>
    );
}
