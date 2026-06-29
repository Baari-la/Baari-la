export default function LinkButton({
    children,

    href = "#",
}) {
    return (
        <a
            href={href}
            className="font-semibold text-blue-600 transition hover:text-blue-700"
        >
            {children}
        </a>
    );
}
