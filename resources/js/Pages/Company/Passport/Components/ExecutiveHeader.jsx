import CompanyIdentity from "./Header/CompanyIdentity";
import ExecutiveScoreCard from "./Header/ExecutiveScoreCard";
import CompanyKPI from "./Header/CompanyKPI";
import CompanyActions from "./Header/CompanyActions";
import CompanyBadges from "./Header/CompanyBadges";

export default function ExecutiveHeader({ passport }) {
    return (
        <section className="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-lg">
            <div className="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 p-8">
                <div className="grid gap-8 lg:grid-cols-[2fr_1fr]">
                    <div className="space-y-6">
                        <CompanyIdentity passport={passport} />

                        <CompanyBadges passport={passport} />

                        <CompanyKPI passport={passport} />

                        <CompanyActions passport={passport} />
                    </div>

                    <ExecutiveScoreCard passport={passport} />
                </div>
            </div>
        </section>
    );
}
