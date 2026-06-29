export default function Divider({
    label,

    className = "",
}) {
    if (!label) {
        return <hr className={`border-slate-200 ${className}`} />;
    }

    return (
        <div className={`relative ${className}`}>
            <hr className="border-slate-200" />

            <span
                className="
                    absolute
                    left-1/2
                    top-1/2
                    -translate-x-1/2
                    -translate-y-1/2
                    bg-white
                    px-4
                    text-xs
                    font-semibold
                    uppercase
                    tracking-widest
                    text-slate-400
                "
            >
                {label}
            </span>
        </div>
    );
}
