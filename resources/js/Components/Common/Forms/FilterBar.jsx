export default function FilterBar({
    children,

    className = "",
}) {
    return (
        <div
            className={`

                flex

                flex-wrap

                items-center

                gap-3

                rounded-2xl

                bg-slate-50

                p-3

                ${className}

            `}
        >
            {children}
        </div>
    );
}
