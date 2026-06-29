export default function DashboardRow({
    children,

    gap = 6,
}) {
    return (
        <div
            className={`

                grid

                grid-cols-12

                gap-${gap}

            `}
        >
            {children}
        </div>
    );
}
