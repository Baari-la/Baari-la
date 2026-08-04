import { ChevronRight } from "lucide-react";

export default function SectionHeader({
    icon: Icon,
    title,
    description,
    badge = null,
    children = null,
    className = "",
}) {
    return (
        <div className={`mb-8 border-b border-slate-200 pb-5 ${className}`}>
            <div className="flex items-start justify-between gap-6">
                <div className="flex items-start gap-4 flex-1">
                    {Icon && (
                        <div className="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50">
                            <Icon className="h-6 w-6 text-indigo-600" />
                        </div>
                    )}

                    <div className="flex-1">
                        <div className="flex flex-wrap items-center gap-3">
                            <h2 className="text-2xl font-bold text-slate-900">
                                {title}
                            </h2>

                            {badge && (
                                <span className="rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700">
                                    {badge}
                                </span>
                            )}
                        </div>

                        {description && (
                            <p className="mt-2 max-w-3xl text-sm leading-7 text-slate-500">
                                {description}
                            </p>
                        )}
                    </div>
                </div>

                {children && (
                    <div className="hidden lg:flex items-center gap-2">
                        {children}

                        <ChevronRight className="h-5 w-5 text-slate-300" />
                    </div>
                )}
            </div>
        </div>
    );
}
