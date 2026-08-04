import { Globe, Package, Truck } from "lucide-react";

import CapabilitySectionTitle from "../CapabilitySectionTitle";

import NumberInput from "../Shared/NumberInput";
import SelectInput from "../Shared/SelectInput";
import CheckboxCard from "../Shared/CheckboxCard";

const MOQ_UNITS = [
    "Kg",
    "Ton",
    "Meter",
    "Yard",
    "Roll",
    "Pieces",
    "Set",
    "Cone",
    "Bale",
];

export default function CommercialSection({ data, setData }) {
    const update = (field, value) => {
        setData({
            ...data,
            [field]: value,
        });
    };

    return (
        <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <CapabilitySectionTitle
                icon={Globe}
                title="Commercial Capability™"
                description="Provide commercial production information to help buyers evaluate your manufacturing readiness and sourcing flexibility."
            />

            <div className="grid gap-6 md:grid-cols-2">
                <NumberInput
                    icon={Package}
                    label="Minimum Order Quantity (MOQ)"
                    value={data.moq}
                    onChange={(v) => update("moq", v)}
                    placeholder="Example : 500"
                />

                <SelectInput
                    icon={Package}
                    label="MOQ Unit"
                    value={data.moq_unit}
                    onChange={(v) => update("moq_unit", v)}
                    options={MOQ_UNITS}
                />

                <NumberInput
                    icon={Truck}
                    label="Lead Time (Days)"
                    value={data.lead_time}
                    onChange={(v) => update("lead_time", v)}
                    placeholder="Example : 30"
                />
            </div>

            <div className="mt-8 grid gap-4 md:grid-cols-2">
                <CheckboxCard
                    title="Export Ready"
                    description="Able to supply international markets with export documentation."
                    checked={data.export_ready}
                    onChange={(v) => update("export_ready", v)}
                />

                <CheckboxCard
                    title="Sampling Service"
                    description="Able to provide development samples before production."
                    checked={data.sampling_service}
                    onChange={(v) => update("sampling_service", v)}
                />
            </div>

            {/* Commercial Intelligence */}

            <div className="mt-8 rounded-2xl border border-emerald-100 bg-emerald-50 p-6">
                <div className="font-bold text-emerald-700">
                    Commercial Intelligence™
                </div>

                <p className="mt-2 text-sm leading-6 text-slate-600">
                    MOQ, lead time, export readiness, and sampling capability
                    are among the first criteria used by buyers and sourcing
                    teams when evaluating new manufacturing partners.
                </p>
            </div>
        </div>
    );
}
