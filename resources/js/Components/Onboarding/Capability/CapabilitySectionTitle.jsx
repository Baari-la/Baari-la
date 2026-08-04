import { ChevronRight } from "lucide-react";

export default function CapabilitySectionTitle({
    icon: Icon,
    title,
    description = "",
    className = "",
}) {
    return (
        <div className={`mb-6 border-b border-slate-200 pb-4 ${className}`}>
            <div className="flex items-center gap-3">
                {Icon && (
                    <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50">
                        <Icon className="h-5 w-5 text-indigo-600" />
                    </div>
                )}

                <div className="flex-1">
                    <div className="flex items-center gap-2">
                        <h2 className="text-xl font-bold text-slate-900">
                            {title}
                        </h2>

                        <ChevronRight className="h-4 w-4 text-slate-300" />
                    </div>

                    {description && (
                        <p className="mt-1 text-sm leading-6 text-slate-500">
                            {description}
                        </p>
                    )}
                </div>
            </div>
        </div>
    );
}
