import { AlertTriangle, CheckCircle2, Info } from "lucide-react";

export default function EarlyWarningCard({ warnings = [] }) {
    const normalizedWarnings = Array.isArray(warnings) ? warnings : [];

    const getIcon = (severity) => {
        if (severity === "critical" || severity === "high") {
            return <AlertTriangle className="h-5 w-5 text-amber-600" />;
        }

        return <Info className="h-5 w-5 text-blue-600" />;
    };

    return (
        <section className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div className="flex items-start justify-between gap-4">
                <div>
                    <p className="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                        Risk Monitoring
                    </p>

                    <h2 className="mt-1 text-lg font-bold text-slate-900">
                        Early Warning Signals
                    </h2>

                    <p className="mt-2 text-sm text-slate-500">
                        Strategic signals requiring executive attention.
                    </p>
                </div>

                <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50">
                    <AlertTriangle className="h-5 w-5 text-amber-600" />
                </div>
            </div>

            <div className="mt-6">
                {normalizedWarnings.length === 0 ? (
                    <div className="flex items-start gap-3 rounded-xl border border-emerald-100 bg-emerald-50 p-4">
                        <CheckCircle2 className="mt-0.5 h-5 w-5 shrink-0 text-emerald-600" />

                        <div>
                            <p className="text-sm font-bold text-emerald-900">
                                No critical warnings
                            </p>

                            <p className="mt-1 text-xs leading-5 text-emerald-700">
                                No executive warning signals are currently
                                available.
                            </p>
                        </div>
                    </div>
                ) : (
                    <div className="space-y-3">
                        {normalizedWarnings.map((warning, index) => {
                            const severity =
                                warning?.severity ??
                                warning?.priority ??
                                "medium";

                            const title =
                                warning?.title ??
                                warning?.name ??
                                "Intelligence Warning";

                            const description =
                                warning?.description ?? warning?.message ?? "";

                            return (
                                <div
                                    key={warning?.id ?? `${title}-${index}`}
                                    className="rounded-xl border border-slate-100 bg-slate-50 p-4"
                                >
                                    <div className="flex items-start gap-3">
                                        <div className="mt-0.5 shrink-0">
                                            {getIcon(severity)}
                                        </div>

                                        <div className="min-w-0">
                                            <div className="flex flex-wrap items-center gap-2">
                                                <p className="text-sm font-bold text-slate-900">
                                                    {title}
                                                </p>

                                                <span className="rounded-full bg-white px-2 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                                    {severity}
                                                </span>
                                            </div>

                                            {description && (
                                                <p className="mt-1 text-xs leading-5 text-slate-500">
                                                    {description}
                                                </p>
                                            )}
                                        </div>
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                )}
            </div>
        </section>
    );
}
