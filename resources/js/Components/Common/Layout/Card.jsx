export default function Card({
    children,

    className = "",

    hover = true,
}) {
    return (
        <div
            className={`
                rounded-3xl
                border
                border-slate-200
                bg-white
                p-6
                shadow-sm
                transition-all
                duration-300

                ${hover ? "hover:-translate-y-1 hover:shadow-lg" : ""}

                ${className}
            `}
        >
            {children}
        </div>
    );
}
