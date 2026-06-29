export default function ContentContainer({
    children,

    size = "7xl",
}) {
    const widths = {
        xl: "max-w-xl",

        "2xl": "max-w-2xl",

        "4xl": "max-w-4xl",

        "5xl": "max-w-5xl",

        "6xl": "max-w-6xl",

        "7xl": "max-w-7xl",

        full: "max-w-full",
    };

    return (
        <div
            className={`

                mx-auto

                px-6

                lg:px-8

                ${widths[size]}

            `}
        >
            {children}
        </div>
    );
}
