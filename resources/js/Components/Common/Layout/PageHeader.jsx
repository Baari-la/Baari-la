import { ChevronRight } from "lucide-react";

export default function PageHeader({
    eyebrow,
    title,
    description,
    actions = null,
    breadcrumbs = [],
}) {
    return (
        <header className="mb-10">
            {breadcrumbs.length > 0 && (
                <nav className="mb-4 flex items-center gap-2 text-sm text-slate-500">
                    {breadcrumbs.map((item, index) => (
                        <div key={index} className="flex items-center gap-2">
                            <span>{item}</span>

                            {index < breadcrumbs.length - 1 && (
                                <ChevronRight size={14} />
                            )}
                        </div>
                    ))}
                </nav>
            )}

            {eyebrow && (
                <p className="text-xs font-bold uppercase tracking-[0.25em] text-blue-600">
                    {eyebrow}
                </p>
            )}

            <div className="mt-2 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h1 className="text-4xl font-black tracking-tight text-slate-900">
                        {title}
                    </h1>

                    {description && (
                        <p className="mt-3 max-w-3xl text-base leading-7 text-slate-600">
                            {description}
                        </p>
                    )}
                </div>

                {actions && <div>{actions}</div>}
            </div>
        </header>
    );
}
