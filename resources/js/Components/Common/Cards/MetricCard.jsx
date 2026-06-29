import Card from "../Layout/Card";

export default function MetricCard({
    title,

    value,

    icon: Icon,

    trend,

    subtitle,

    color = "blue",

    variant = "default",
}) {
    const colors = {
        blue: "bg-blue-50 text-blue-600",

        emerald: "bg-emerald-50 text-emerald-600",

        amber: "bg-amber-50 text-amber-600",

        red: "bg-red-50 text-red-600",

        indigo: "bg-indigo-50 text-indigo-600",
    };

    return (
        <Card>
            <div className="flex items-start justify-between">
                <div>
                    <p className="text-xs font-bold uppercase tracking-widest text-slate-500">
                        {title}
                    </p>

                    <h3 className="mt-3 text-3xl font-black text-slate-900">
                        {value}
                    </h3>

                    {subtitle && (
                        <p className="mt-2 text-sm text-slate-500">
                            {subtitle}
                        </p>
                    )}

                    {trend && (
                        <span className="mt-4 inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">
                            {trend}
                        </span>
                    )}
                </div>

                {Icon && (
                    <div className={`rounded-2xl p-4 ${colors[color]}`}>
                        <Icon size={28} />
                    </div>
                )}
            </div>
        </Card>
    );
}
