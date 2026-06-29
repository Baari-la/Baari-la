export default function DashboardBuilder({
    children,

    className = "",
}) {
    return (
        <div
            className={`

                mx-auto

                max-w-7xl

                space-y-8

                px-6

                py-8

                ${className}

            `}
        >
            {children}
        </div>
    );
}
