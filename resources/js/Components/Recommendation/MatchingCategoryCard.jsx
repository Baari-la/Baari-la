import CompanyRecommendationCard from "./CompanyRecommendationCard";

export default function MatchingCategoryCard({ category }) {
    return (
        <div className="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div className="border-b border-slate-100 px-6 py-4">
                <div className="flex items-center justify-between">
                    <div>
                        <h3 className="text-lg font-bold text-slate-900">
                            {category.title}
                        </h3>

                        <p className="mt-1 text-sm text-slate-500">
                            {category.description}
                        </p>
                    </div>

                    <div
                        className={`rounded-full px-3 py-1 text-xs font-bold ${
                            category.priority === "High"
                                ? "bg-red-100 text-red-700"
                                : category.priority === "Medium"
                                  ? "bg-amber-100 text-amber-700"
                                  : "bg-slate-100 text-slate-700"
                        }`}
                    >
                        {category.priority}
                    </div>
                </div>
            </div>

            <div className="grid gap-4 p-6 md:grid-cols-2">
                {category.companies.length > 0 ? (
                    category.companies.map((company) => (
                        <CompanyRecommendationCard
                            key={company.company_id}
                            company={company}
                        />
                    ))
                ) : (
                    <div className="text-sm text-slate-500">
                        No recommended companies available yet.
                    </div>
                )}
            </div>
        </div>
    );
}
