export default function ContainerCard({
    icon: Icon,

    title,

    value,

    change,
}) {
    const positive = String(change).includes("+");

    return (
        <div className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div className="flex items-center justify-between">
                <div>
                    <p className="text-xs font-bold uppercase tracking-wider text-slate-500">
                        {title}
                    </p>

                    <h3 className="mt-3 text-3xl font-black text-slate-900">
                        {value}
                    </h3>

                    <span
                        className={`mt-3 inline-flex rounded-full px-3 py-1 text-xs font-bold ${
                            positive
                                ? "bg-emerald-100 text-emerald-700"
                                : "bg-red-100 text-red-600"
                        }`}
                    >
                        {change}
                    </span>
                </div>

                <div className="rounded-2xl bg-orange-50 p-4">
                    <Icon size={28} className="text-orange-600" />
                </div>
            </div>
        </div>
    );
}
