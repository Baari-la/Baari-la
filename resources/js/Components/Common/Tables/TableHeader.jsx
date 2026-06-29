export default function TableHeader({
    title,

    description,

    total,

    actions,
}) {
    return (
        <div className="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 className="text-2xl font-bold text-slate-900">{title}</h2>

                {description && (
                    <p className="mt-2 text-sm text-slate-500">{description}</p>
                )}
            </div>

            <div className="flex items-center gap-4">
                {typeof total !== "undefined" && (
                    <span className="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700">
                        {total} Records
                    </span>
                )}

                {actions}
            </div>
        </div>
    );
}
