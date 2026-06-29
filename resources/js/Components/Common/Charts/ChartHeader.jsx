import StatusBadge from "../Badges/StatusBadge";

export default function ChartHeader({
    title,

    subtitle,

    badge,

    actions,
}) {
    return (
        <div className="mb-6 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <div className="flex items-center gap-3">
                    <h2 className="text-2xl font-bold text-slate-900">
                        {title}
                    </h2>

                    {badge && <StatusBadge>{badge}</StatusBadge>}
                </div>

                {subtitle && (
                    <p className="mt-2 text-sm leading-6 text-slate-500">
                        {subtitle}
                    </p>
                )}
            </div>

            {actions && <div>{actions}</div>}
        </div>
    );
}
