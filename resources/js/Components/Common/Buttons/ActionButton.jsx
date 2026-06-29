import clsx from "clsx";

export default function ActionButton({
    icon: Icon,

    children,

    onClick,

    color = "blue",
}) {
    const colors = {
        blue: "bg-blue-600 hover:bg-blue-700",

        emerald: "bg-emerald-600 hover:bg-emerald-700",

        amber: "bg-amber-500 hover:bg-amber-600",

        red: "bg-red-600 hover:bg-red-700",

        indigo: "bg-indigo-600 hover:bg-indigo-700",
    };

    return (
        <button
            onClick={onClick}
            className={clsx(
                "inline-flex items-center gap-2",

                "rounded-xl",

                "px-5 py-3",

                "font-semibold",

                "text-white",

                "transition",

                colors[color],
            )}
        >
            {Icon && <Icon size={18} />}

            {children}
        </button>
    );
}
