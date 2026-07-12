import {
    Users,
    Globe2,
    Package,
    Factory,
    Gauge,
    Boxes,
    Clock3,
} from "lucide-react";

export default function CompanyStatisticsCards({ passport }) {
    const company = passport?.company ?? {};

    const statistics = passport?.statistics ?? {};

    const items = [
        {
            label: "Employees",
            value: company.employee_count ?? company.employees ?? 0,
            suffix: "",
            icon: Users,
        },

        {
            label: "Markets",
            value: statistics.markets ?? 0,
            suffix: "",
            icon: Globe2,
        },

        {
            label: "Products",
            value: statistics.products ?? 0,
            suffix: "",
            icon: Package,
        },

        {
            label: "Machines",
            value: statistics.machines ?? 0,
            suffix: "",
            icon: Factory,
        },

        {
            label: "Capacity",
            value: statistics.capacity ?? "-",
            suffix: "",
            icon: Gauge,
        },

        {
            label: "MOQ",
            value: statistics.moq ?? "-",
            suffix: "",
            icon: Boxes,
        },

        {
            label: "Lead Time",
            value: statistics.lead_time ?? "-",
            suffix: "",
            icon: Clock3,
        },
    ];

    return (
        <div className="grid grid-cols-2 gap-4 md:grid-cols-4 xl:grid-cols-7">
            {items.map((item) => (
                <StatisticCard key={item.label} {...item} />
            ))}
        </div>
    );
}

function StatisticCard({ icon: Icon, label, value, suffix }) {
    return (
        <div className="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div className="flex justify-between">
                <Icon className="h-7 w-7 text-sky-600" />
            </div>

            <div className="mt-6">
                <div className="text-3xl font-bold text-slate-900">
                    {value}
                    {suffix}
                </div>

                <div className="mt-2 text-sm text-slate-500">{label}</div>
            </div>
        </div>
    );
}
