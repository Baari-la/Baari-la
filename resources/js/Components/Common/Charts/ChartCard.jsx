import Card from "../Layout/Card";

export default function ChartCard({
    title,

    subtitle,

    actions = null,

    children,

    footer = null,

    loading = false,

    className = "",
}) {
    return (
        <Card className={`overflow-hidden ${className}`}>
            {(title || subtitle || actions) && (
                <div className="mb-6 flex flex-col gap-4 border-b border-slate-100 pb-5 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        {title && (
                            <h3 className="text-xl font-bold text-slate-900">
                                {title}
                            </h3>
                        )}

                        {subtitle && (
                            <p className="mt-2 text-sm leading-6 text-slate-500">
                                {subtitle}
                            </p>
                        )}
                    </div>

                    {actions && <div>{actions}</div>}
                </div>
            )}

            <div
                className="
                    min-h-[320px]
                    flex
                    items-center
                    justify-center
                "
            >
                {loading ? (
                    <p className="text-slate-400">Loading Chart...</p>
                ) : (
                    children
                )}
            </div>

            {footer && (
                <div className="mt-6 border-t border-slate-100 pt-5">
                    {footer}
                </div>
            )}
        </Card>
    );
}
