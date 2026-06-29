import StatusBadge from "../Common/Badges/StatusBadge";

export default function WidgetHeader({
    title,

    subtitle,

    badge,

    actions,
}) {
    return (
        <div className="mb-6 flex items-start justify-between">
            <div>
                <div className="flex items-center gap-3">
                    <h3 className="text-xl font-bold">{title}</h3>

                    {badge && <StatusBadge>{badge}</StatusBadge>}
                </div>

                {subtitle && (
                    <p className="mt-2 text-sm text-slate-500">{subtitle}</p>
                )}
            </div>

            {actions}
        </div>
    );
}
