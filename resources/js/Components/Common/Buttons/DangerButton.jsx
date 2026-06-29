export default function DangerButton({
    children,

    onClick,
}) {
    return (
        <button
            onClick={onClick}
            className="rounded-xl bg-red-600 px-5 py-3 font-semibold text-white transition hover:bg-red-700"
        >
            {children}
        </button>
    );
}
