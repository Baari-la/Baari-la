import { Factory, Layers, Package } from "lucide-react";

import CapabilitySectionTitle from "../CapabilitySectionTitle";

import Input from "../Shared/NumberInput";
import SelectInput from "../Shared/SelectInput";

const UNIT_OPTIONS = [
    "Kg",
    "Ton",
    "Meter",
    "Yard",
    "Roll",
    "Pieces",
    "Set",
    "Cone",
    "Bale",
    "Spindle",
];

export default function CapacitySection({ data, setData, business }) {
    const update = (field, value) => {
        setData({
            ...data,
            [field]: value,
        });
    };

    const installed = Number(data.production_capacity || 0);

    const utilized = Number(data.current_utilized_capacity || 0);

    const available = Math.max(installed - utilized, 0);

    const utilization =
        installed > 0 ? Math.round((utilized / installed) * 100) : 0;

    return (
        <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <CapabilitySectionTitle
                icon={Factory}
                title="Capacity Intelligence™"
                description="Describe your installed production capacity and current utilization to help buyers understand your manufacturing availability."
            />

            <div className="grid gap-6 md:grid-cols-2">
                <Input
                    icon={Factory}
                    label="Installed Capacity"
                    value={data.production_capacity}
                    onChange={(v) => update("production_capacity", v)}
                    placeholder="Example : 12,000"
                />

                <SelectInput
                    icon={Layers}
                    label="Capacity Unit"
                    value={data.capacity_unit}
                    onChange={(v) => update("capacity_unit", v)}
                    options={UNIT_OPTIONS}
                />

                <Input
                    icon={Factory}
                    label="Current Utilized Capacity"
                    value={data.current_utilized_capacity}
                    onChange={(v) => update("current_utilized_capacity", v)}
                    placeholder="Example : 8,500"
                />

                <SelectInput
                    icon={Layers}
                    label="Utilized Unit"
                    value={data.current_utilized_capacity_unit}
                    onChange={(v) =>
                        update("current_utilized_capacity_unit", v)
                    }
                    options={UNIT_OPTIONS}
                />

                <Input
                    icon={Package}
                    label="Monthly Capacity"
                    value={data.monthly_capacity}
                    onChange={(v) => update("monthly_capacity", v)}
                    placeholder="Per Month"
                />

                <Input
                    icon={Package}
                    label="Annual Capacity"
                    value={data.annual_capacity}
                    onChange={(v) => update("annual_capacity", v)}
                    placeholder="Per Year"
                />
            </div>

            {/* Capacity Intelligence */}

            <div className="mt-8 rounded-2xl bg-slate-50 p-6">
                <div className="grid gap-4 md:grid-cols-3">
                    <MetricCard
                        title="Available Capacity"
                        value={`${available.toLocaleString()} ${data.capacity_unit ?? ""}`}
                    />

                    <MetricCard title="Utilization" value={`${utilization}%`} />

                    <MetricCard
                        title="Factory Status"
                        value={
                            utilization >= 90
                                ? "Near Full Capacity"
                                : utilization >= 70
                                  ? "Healthy Capacity"
                                  : utilization >= 40
                                    ? "Available Capacity"
                                    : "Low Utilization"
                        }
                    />
                </div>
            </div>
        </div>
    );
}

/* -------------------------------------------------------------------------- */
/* Metric Card */
/* -------------------------------------------------------------------------- */

function MetricCard({ title, value }) {
    return (
        <div className="rounded-2xl border border-slate-200 bg-white p-5">
            <div className="text-xs font-semibold uppercase tracking-wide text-slate-500">
                {title}
            </div>

            <div className="mt-2 text-2xl font-black text-indigo-700">
                {value}
            </div>
        </div>
    );
}
