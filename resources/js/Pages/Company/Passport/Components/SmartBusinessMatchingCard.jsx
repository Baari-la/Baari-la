import { Sparkles, Building2, ArrowRight } from "lucide-react";

export default function SmartBusinessMatchingCard({ matching }) {
    const categories = matching?.categories ?? [];

    return (
        <div className="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            {/* ======================================================
                Header
            ====================================================== */}

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

            {/* ======================================================
                Categories
            ====================================================== */}

            <div className="divide-y">
                {categories.length === 0 && (
                    <div className="p-8 text-center text-slate-500">
                        No matching categories found.
                    </div>
                )}

                {categories.map((category) => (
                    <div key={category.category} className="p-6">
                        {/* Category Header */}

                        <div className="mb-5">
                            <div className="flex items-center justify-between">
                                <h3 className="font-bold text-slate-900">
                                    {category.title}
                                </h3>

                                <span
                                    className={`
                                        rounded-full
                                        px-3
                                        py-1
                                        text-xs
                                        font-semibold

                                        ${
                                            category.priority === "High"
                                                ? "bg-red-100 text-red-700"
                                                : category.priority === "Medium"
                                                  ? "bg-amber-100 text-amber-700"
                                                  : "bg-slate-100 text-slate-700"
                                        }
                                    `}
                                >
                                    {category.priority}
                                </span>
                            </div>

                            {category.description && (
                                <p className="mt-1 text-sm text-slate-500">
                                    {category.description}
                                </p>
                            )}
                        </div>

                        {/* Companies */}

                        <div className="space-y-3">
                            {category.companies.length === 0 && (
                                <div className="rounded-xl bg-slate-50 p-4 text-sm text-slate-500">
                                    No recommended companies found.
                                </div>
                            )}

                            {category.companies.map((partner) => (
                                <div
                                    key={partner.company_id}
                                    className="
                                            flex
                                            items-center
                                            justify-between
                                            rounded-2xl
                                            border
                                            border-slate-100
                                            p-4
                                            hover:bg-slate-50
                                        "
                                >
                                    <div className="flex gap-4">
                                        <div className="rounded-xl bg-slate-100 p-3">
                                            <Building2 className="h-5 w-5 text-slate-600" />
                                        </div>

                                        <div>
                                            <div className="font-semibold text-slate-900">
                                                {partner.company_name}
                                            </div>

                                            <div className="mt-1 text-sm text-slate-500">
                                                {partner.city}
                                                {" • "}
                                                {partner.country}
                                            </div>

                                            <div className="mt-1 text-xs text-slate-400">
                                                {partner.matching_reasons?.join(
                                                    " • ",
                                                )}
                                            </div>
                                        </div>
                                    </div>

                                    <div className="text-right">
                                        <div className="text-2xl font-black text-emerald-600">
                                            {partner.matching_score}%
                                        </div>

                                        <div className="text-xs uppercase tracking-wide text-slate-400">
                                            {partner.matching_level}
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                ))}
            </div>

            {/* ======================================================
                Footer
            ====================================================== */}

            <div className="border-t border-slate-100 bg-slate-50 px-6 py-4">
                <button className="flex items-center gap-2 text-sm font-semibold text-sky-600 hover:text-sky-700">
                    View All Recommended Partners
                    <ArrowRight className="h-4 w-4" />
                </button>
            </div>
        </div>
    );
}
