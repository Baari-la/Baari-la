export default function PageSection({
    children,

    className = "",

    spacing = "normal",
}) {
    const spaces = {
        compact: "py-10",

        normal: "py-16",

        large: "py-24",
    };

    return (
        <section
            className={`

                ${spaces[spacing]}

                ${className}

            `}
        >
            {children}
        </section>
    );
}
