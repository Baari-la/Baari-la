import { Sparkles, Building2, ArrowRight } from "lucide-react";

export default function SmartBusinessMatchingCard({ matching }) {
    const partners = matching?.partners ?? [];

    return (
        <div className="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            {/* Header */}

            <div className="border-b border-slate-100 px-6 py-5">
                <div className="flex items-center gap-3">
                    <div className="rounded-xl bg-sky-100 p-2">
                        <Sparkles className="h-6 w-6 text-sky-600" />
                    </div>

                    <div>
                        <h2 className="text-xl font-bold text-slate-900">
                            {matching?.title}
                        </h2>

                        <p className="text-sm text-slate-500">
                            {matching?.description}
                        </p>
                    </div>
                </div>
            </div>

            {/* Body */}

            <div className="divide-y">
                {partners.length === 0 && (
                    <div className="p-8 text-center text-slate-500">
                        No matching companies found.
                    </div>
                )}

                {partners.map((partner) => (
                    <div
                        key={partner.company_id}
                        className="flex items-center justify-between px-6 py-5 hover:bg-slate-50"
                    >
                        <div className="flex gap-4">
                            <div className="rounded-xl bg-slate-100 p-3">
                                <Building2 className="h-6 w-6 text-slate-600" />
                            </div>

                            <div>
                                <div className="font-semibold">
                                    {partner.company_name}
                                </div>

                                <div className="mt-1 text-sm text-slate-500">
                                    {partner.reasons?.join(" • ")}
                                </div>
                            </div>
                        </div>

                        <div className="text-right">
                            <div className="text-2xl font-black text-emerald-600">
                                {partner.score}%
                            </div>

                            <div className="text-xs uppercase tracking-wide text-slate-400">
                                {partner.level}
                            </div>
                        </div>
                    </div>
                ))}
            </div>

            <div className="border-t border-slate-100 bg-slate-50 px-6 py-4">
                <button className="flex items-center gap-2 text-sm font-semibold text-sky-600 hover:text-sky-700">
                    View All Recommended Partners
                    <ArrowRight className="h-4 w-4" />
                </button>
            </div>
        </div>
    );
}
