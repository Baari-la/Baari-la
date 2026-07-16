import {
    Users,
    Globe,
    Package,
    Factory,
    Gauge,
    Boxes,
    Clock3,
} from "lucide-react";

export default function CompanyKPI({ passport }) {
    const stats = passport?.summary ?? {};

    const items = [
        {
            label: "Employees",
            value: stats.employees ?? "-",
            icon: Users,
        },

        {
            label: "Markets",
            value: stats.markets ?? 0,
            icon: Globe,
        },

        {
            label: "Products",
            value: stats.products ?? 0,
            icon: Package,
        },

        {
            label: "Machines",
            value: stats.machines ?? 0,
            icon: Factory,
        },

        {
            label: "Capacity",
            value: stats.capacities ?? 0,
            icon: Gauge,
        },

        {
            label: "MOQ",
            value: stats.moqs ?? 0,
            icon: Boxes,
        },

        {
            label: "Lead Time",
            value: stats.lead_times ?? 0,
            icon: Clock3,
        },
    ];

    return (
        <div className="grid gap-4 md:grid-cols-4 xl:grid-cols-7">
            {items.map((item) => (
                <div
                    key={item.label}
                    className="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"
                >
                    <item.icon className="mb-3 h-6 w-6 text-slate-700" />

                    <div className="text-2xl font-bold">{item.value}</div>

                    <div className="mt-1 text-sm text-slate-500">
                        {item.label}
                    </div>
                </div>
            ))}
        </div>
    );
}
