import { Building2, MapPin, ArrowRight } from "lucide-react";
import { Link } from "@inertiajs/react";

export default function CompanyRecommendationCard({ company }) {
    return (
        <div className="rounded-xl border border-slate-200 bg-white p-4 transition hover:border-sky-300 hover:shadow-md">
            <div className="flex items-start justify-between">
                <div>
                    <h4 className="font-semibold text-slate-900">
                        {company.company_name}
                    </h4>

                    <div className="mt-2 flex items-center gap-2 text-sm text-slate-500">
                        <MapPin className="h-4 w-4" />
                        {company.city}, {company.country}
                    </div>

                    <div className="mt-2 inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                        Match Score {company.matching_score}
                    </div>
                </div>

                <Building2 className="h-8 w-8 text-slate-300" />
            </div>

            <Link
                href={`/companies/${company.company_id}`}
                className="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-sky-600"
            >
                Open Passport
                <ArrowRight className="h-4 w-4" />
            </Link>
        </div>
    );
}
