import clsx from "clsx";

export default function SecondaryButton({
    children,

    onClick,

    className = "",
}) {
    return (
        <button
            onClick={onClick}
            className={clsx(
                "inline-flex items-center justify-center",

                "rounded-xl",

                "border",

                "border-slate-300",

                "bg-white",

                "px-5 py-3",

                "font-semibold",

                "text-slate-700",

                "transition",

                "hover:bg-slate-100",

                className,
            )}
        >
            {children}
        </button>
    );
}
