import { Building2, MapPin, BadgeCheck, ArrowRight } from "lucide-react";

export default function SupplyChainCompanyCard({ company }) {
    return (
        <div className="rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-sky-300 hover:shadow">
            {/* Company */}

            <div className="flex items-start gap-3">
                <div className="rounded-lg bg-slate-100 p-3">
                    <Building2 className="h-5 w-5 text-slate-600" />
                </div>

                <div className="flex-1">
                    <div className="font-semibold text-slate-900">
                        {company.company_name}
                    </div>

                    <div className="mt-1 flex items-center gap-1 text-sm text-slate-500">
                        <MapPin className="h-4 w-4" />
                        {company.city}, {company.country}
                    </div>
                </div>
            </div>

            {/* Membership */}

            {company.membership && (
                <div className="mt-4 flex items-center gap-2 text-sm text-emerald-600">
                    <BadgeCheck className="h-4 w-4" />

                    {company.membership}
                </div>
            )}

            {/* Matching Score */}

            <div className="mt-5 flex items-center justify-between">
                <div>
                    <div className="text-xs uppercase tracking-wider text-slate-400">
                        Matching Score
                    </div>

                    <div className="text-2xl font-black text-emerald-600">
                        {company.matching_score ?? 0}%
                    </div>
                </div>

                <button className="flex items-center gap-1 text-sm font-semibold text-sky-600 transition hover:text-sky-700">
                    View
                    <ArrowRight className="h-4 w-4" />
                </button>
            </div>
        </div>
    );
}
