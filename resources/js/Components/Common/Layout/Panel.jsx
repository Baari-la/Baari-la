export default function Panel({
    children,

    className = "",
}) {
    return (
        <div
            className={`
                rounded-3xl
                border
                border-slate-200
                bg-white
                p-8
                shadow-sm
                ${className}
            `}
        >
            {children}
        </div>
    );
}
