import clsx from "clsx";

export default function IconButton({
    icon: Icon,

    onClick,

    title,

    className = "",
}) {
    return (
        <button
            onClick={onClick}
            title={title}
            className={clsx(
                "rounded-xl",

                "border",

                "border-slate-200",

                "bg-white",

                "p-3",

                "transition",

                "hover:bg-slate-100",

                className,
            )}
        >
            {Icon && <Icon size={18} />}
        </button>
    );
}
