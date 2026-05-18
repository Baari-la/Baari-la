import { Link } from "@inertiajs/react";

export default function ResponsiveNavLink({
    active = false,
    className = "",
    children,
    ...props
}) {
    return (
        <Link
            {...props}
            className={
                "block w-full text-left transition-all duration-300 ease-in-out focus:outline-none " +
                (active
                    ? "bg-amber-500/15 border-l-4 border-amber-500 text-amber-400 shadow-[inset_4px_0_15px_rgba(245,158,11,0.15)] font-bold"
                    : "border-l-4 border-transparent text-slate-300 hover:text-amber-400 hover:bg-amber-500/10 hover:border-l-amber-500/50") +
                " " +
                className
            }
        >
            {children}
        </Link>
    );
}
