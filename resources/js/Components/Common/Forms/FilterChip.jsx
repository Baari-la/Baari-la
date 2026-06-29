export default function FilterChip({
    active = false,

    children,

    onClick,
}) {
    return (
        <button
            onClick={onClick}
            className={`

                rounded-full

                px-4

                py-2

                text-sm

                font-semibold

                transition

                ${
                    active
                        ? "bg-blue-600 text-white"
                        : "bg-white text-slate-600 hover:bg-slate-100"
                }

            `}
        >
            {children}
        </button>
    );
}
