export default function Badge({
    children,

    color = "emerald",

    size = "md",

    rounded = "full",

    uppercase = true,

    className = "",
}) {
    const colors = {
        slate: "bg-slate-100 text-slate-700",

        gray: "bg-gray-100 text-gray-700",

        blue: "bg-blue-100 text-blue-700",

        cyan: "bg-cyan-100 text-cyan-700",

        emerald: "bg-emerald-100 text-emerald-700",

        green: "bg-green-100 text-green-700",

        amber: "bg-amber-100 text-amber-700",

        yellow: "bg-yellow-100 text-yellow-700",

        orange: "bg-orange-100 text-orange-700",

        rose: "bg-rose-100 text-rose-700",

        red: "bg-red-100 text-red-700",

        purple: "bg-purple-100 text-purple-700",

        indigo: "bg-indigo-100 text-indigo-700",
    };

    const sizes = {
        sm: "px-2.5 py-1 text-xs",

        md: "px-3 py-1.5 text-sm",

        lg: "px-4 py-2 text-base",
    };

    const radius = {
        full: "rounded-full",

        xl: "rounded-xl",

        lg: "rounded-lg",
    };

    return (
        <span
            className={`
                inline-flex
                items-center
                justify-center
                font-semibold
                tracking-wide
                ${uppercase ? "uppercase" : ""}
                ${colors[color] || colors.emerald}
                ${sizes[size] || sizes.md}
                ${radius[rounded] || radius.full}
                ${className}
            `}
        >
            {children}
        </span>
    );
}
