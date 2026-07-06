import CompanyIdentity from "./CompanyIdentity";
import CompanyBadges from "./CompanyBadges";
import ExecutiveScoreCard from "./ExecutiveScoreCard";
import CompanyActions from "./CompanyActions";
import CompanyKPI from "./CompanyKPI";

export default function ExecutiveHeader({
    company,
    passport,
    onSendRFQ,
    onContact,
    onCompare,
    onShare,
    onSave,
}) {
    return (
        <div className="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            {/* ============================================================
               Hero
            ============================================================ */}

            <div className="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 px-8 py-8 text-white">
                <div className="grid gap-10 xl:grid-cols-[1.6fr_420px]">
                    {/* Left Side */}

                    <div className="space-y-6">
                        <CompanyIdentity company={company} />

                        <CompanyBadges company={company} />

                        <CompanyActions
                            onSendRFQ={onSendRFQ}
                            onContact={onContact}
                            onCompare={onCompare}
                            onShare={onShare}
                            onSave={onSave}
                        />
                    </div>

                    {/* Right Side */}

                    <ExecutiveScoreCard scores={passport?.scores} />
                </div>
            </div>

            {/* ============================================================
               KPI Section
            ============================================================ */}

            <div className="border-t border-slate-200 bg-slate-50 px-8 py-6">
                <CompanyKPI
                    company={{
                        employees:
                            company.statistics?.employees ??
                            company.tenaga_kerja,

                        established:
                            company.statistics?.established ??
                            company.established,

                        products:
                            company.statistics?.products ??
                            company.products_count,

                        markets:
                            company.statistics?.markets ??
                            company.markets_count,

                        machines:
                            company.statistics?.machines ??
                            company.machines_count,

                        capacity:
                            company.statistics?.capacity ?? company.capacity,

                        certifications:
                            company.statistics?.certifications ??
                            company.certifications_count,
                    }}
                />
            </div>
        </div>
    );
}
