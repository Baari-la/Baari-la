const variants = {
    success: "bg-emerald-100 text-emerald-700",

    warning: "bg-amber-100 text-amber-700",

    danger: "bg-red-100 text-red-700",

    info: "bg-cyan-100 text-cyan-700",

    primary: "bg-blue-100 text-blue-700",

    secondary: "bg-slate-100 text-slate-700",
};

export default function StatusBadge({
    children,

    variant = "primary",
}) {
    return (
        <span
            className={`

                inline-flex

                items-center

                rounded-full

                px-3

                py-1

                text-xs

                font-bold

                uppercase

                tracking-wider

                ${variants[variant]}

            `}
        >
            {children}
        </span>
    );
}
