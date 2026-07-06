import {
    Building2,
    MapPin,
    Globe,
    Factory,
    Users,
    Calendar,
} from "lucide-react";

export default function CompanyIdentity({ company }) {
    const badges = [];

    if (company.membership_type) {
        badges.push(company.membership_type);
    }

    if (company.verification_status === "Verified") {
        badges.push("Verified Company");
    }

    if (company.export_markets && company.export_markets.length > 0) {
        badges.push("Multi-Market Supplier");
    }

    return (
        <div className="space-y-6">
            {/* Company Name */}

            <div>
                <h1 className="text-4xl font-bold tracking-tight text-white">
                    {company.company_name}
                </h1>

                <div className="mt-3 flex flex-wrap items-center gap-3 text-slate-300">
                    {company.country_name && (
                        <div className="flex items-center gap-2">
                            <Globe className="h-4 w-4" />
                            <span>{company.country_name}</span>
                        </div>
                    )}

                    {company.city && (
                        <div className="flex items-center gap-2">
                            <MapPin className="h-4 w-4" />
                            <span>{company.city}</span>
                        </div>
                    )}

                    {company.business_sector && (
                        <div className="flex items-center gap-2">
                            <Factory className="h-4 w-4" />
                            <span>{company.business_sector}</span>
                        </div>
                    )}
                </div>
            </div>

            {/* Membership & Status */}

            <div className="flex flex-wrap gap-2">
                {badges.map((badge) => (
                    <span
                        key={badge}
                        className="rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-white"
                    >
                        {badge}
                    </span>
                ))}
            </div>

            {/* Executive Quick Facts */}

            <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <QuickItem
                    icon={Building2}
                    label="Company ID"
                    value={`#${company.company_id ?? "-"}`}
                />

                <QuickItem
                    icon={Users}
                    label="Employees"
                    value={company.employees ?? "-"}
                />

                <QuickItem
                    icon={Calendar}
                    label="Established"
                    value={company.established ?? "-"}
                />

                <QuickItem
                    icon={Globe}
                    label="Export Markets"
                    value={
                        company.export_market_count ??
                        company.export_markets?.length ??
                        "-"
                    }
                />
            </div>
        </div>
    );
}

function QuickItem({ icon: Icon, label, value }) {
    return (
        <div className="rounded-xl border border-white/10 bg-white/5 p-4 backdrop-blur">
            <div className="flex items-center gap-2 text-slate-300">
                <Icon className="h-4 w-4" />

                <span className="text-xs uppercase tracking-wider">
                    {label}
                </span>
            </div>

            <div className="mt-2 text-lg font-bold text-white">{value}</div>
        </div>
    );
}
