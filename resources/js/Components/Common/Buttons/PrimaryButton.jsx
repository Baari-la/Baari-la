import clsx from "clsx";

export default function PrimaryButton({
    children,

    type = "button",

    onClick,

    disabled = false,

    fullWidth = false,

    className = "",
}) {
    return (
        <button
            type={type}
            onClick={onClick}
            disabled={disabled}
            className={clsx(
                "inline-flex items-center justify-center rounded-xl",

                "bg-blue-600 text-white",

                "px-5 py-3",

                "font-semibold",

                "transition-all duration-300",

                "hover:bg-blue-700",

                "disabled:cursor-not-allowed",

                "disabled:opacity-50",

                fullWidth && "w-full",

                className,
            )}
        >
            {children}
        </button>
    );
}
