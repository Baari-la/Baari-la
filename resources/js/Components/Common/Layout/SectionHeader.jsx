export default function SectionHeader({
    badge,

    title,

    description,

    align = "left",
}) {
    const alignment = align === "center" ? "text-center mx-auto" : "";

    return (
        <div className={`max-w-4xl ${alignment}`}>
            {badge && (
                <span className="inline-flex rounded-full bg-blue-100 px-4 py-2 text-xs font-bold uppercase tracking-[0.25em] text-blue-700">
                    {badge}
                </span>
            )}

            <h2 className="mt-5 text-4xl font-black tracking-tight text-slate-900">
                {title}
            </h2>

            {description && (
                <p className="mt-5 text-lg leading-8 text-slate-600">
                    {description}
                </p>
            )}
        </div>
    );
}
