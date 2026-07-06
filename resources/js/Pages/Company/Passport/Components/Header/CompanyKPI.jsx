import {
    Users,
    Calendar,
    Package,
    Globe2,
    Factory,
    Gauge,
    ShieldCheck,
} from "lucide-react";

export default function CompanyKPI({ company = {} }) {
    const items = [
        {
            label: "Employees",
            value: company.employees ?? "-",
            icon: Users,
        },
        {
            label: "Established",
            value: company.established ?? "-",
            icon: Calendar,
        },
        {
            label: "Products",
            value: company.products ?? 0,
            icon: Package,
        },
        {
            label: "Markets",
            value: company.markets ?? 0,
            icon: Globe2,
        },
        {
            label: "Machines",
            value: company.machines ?? 0,
            icon: Factory,
        },
        {
            label: "Capacity",
            value: company.capacity ?? "-",
            icon: Gauge,
        },
        {
            label: "Certifications",
            value: company.certifications ?? 0,
            icon: ShieldCheck,
        },
    ];

    return (
        <div className="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            {items.map((item) => (
                <KpiCard key={item.label} {...item} />
            ))}
        </div>
    );
}

function KpiCard({ icon: Icon, label, value }) {
    return (
        <div className="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md">
            <div className="flex items-center justify-between">
                <div>
                    <div className="text-xs uppercase tracking-wider text-slate-500">
                        {label}
                    </div>

                    <div className="mt-2 text-2xl font-bold text-slate-800">
                        {value}
                    </div>
                </div>

                <div className="rounded-xl bg-slate-100 p-3">
                    <Icon className="h-5 w-5 text-slate-700" />
                </div>
            </div>
        </div>
    );
}
