import { ArrowDown } from "lucide-react";

import SupplyChainCompanyCard from "./SupplyChainCompanyCard";

export default function SupplyChainStage({ stage, isLast = false }) {
    return (
        <div>
            <div
                className={`rounded-xl border p-5 transition ${
                    stage.type === "current"
                        ? "border-sky-300 bg-sky-50"
                        : "border-slate-200 bg-white"
                }`}
            >
                {/* Stage Header */}

                <div className="flex items-center justify-between">
                    <div>
                        <div className="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            {stage.type}
                        </div>

                        <h3 className="mt-1 text-lg font-bold text-slate-900">
                            {stage.title}
                        </h3>
                    </div>

                    {stage.companies && (
                        <div className="rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-600">
                            {stage.companies.length} Companies
                        </div>
                    )}
                </div>

                {/* Company List */}

                {stage.companies?.length > 0 && (
                    <div className="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        {stage.companies.map((company) => (
                            <SupplyChainCompanyCard
                                key={company.company_id}
                                company={company}
                            />
                        ))}
                    </div>
                )}

                {stage.type === "current" && (
                    <div className="mt-4 rounded-lg border border-sky-200 bg-white px-4 py-3 text-sm text-sky-700">
                        This is your current position within the textile value
                        chain.
                    </div>
                )}
            </div>

            {!isLast && (
                <div className="flex justify-center py-4">
                    <ArrowDown className="h-6 w-6 text-slate-300" />
                </div>
            )}
        </div>
    );
}
