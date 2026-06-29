export default function PageGrid({
    children,

    columns = 4,

    gap = 6,
}) {
    const grids = {
        1: "grid-cols-1",

        2: "grid-cols-1 lg:grid-cols-2",

        3: "grid-cols-1 lg:grid-cols-3",

        4: "grid-cols-1 md:grid-cols-2 xl:grid-cols-4",
    };

    return (
        <div
            className={`

                grid

                ${grids[columns]}

                gap-${gap}

            `}
        >
            {children}
        </div>
    );
}
