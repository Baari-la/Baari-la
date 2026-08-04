import { FlaskConical, Clock3, Package, Truck } from "lucide-react";

import CapabilitySectionTitle from "../CapabilitySectionTitle";

import NumberInput from "../Shared/NumberInput";
import SelectInput from "../Shared/SelectInput";
import CheckboxCard from "../Shared/CheckboxCard";

const TURNAROUND_OPTIONS = [
    "Same Day",
    "24 Hours",
    "2 Days",
    "3 Days",
    "5 Days",
    "1 Week",
    "Custom",
];

export default function LaboratorySection({ data, setData }) {
    const update = (field, value) => {
        setData({
            ...data,
            [field]: value,
        });
    };

    return (
        <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <CapabilitySectionTitle
                icon={FlaskConical}
                title="Laboratory Capability™"
                description="Describe your laboratory facilities, testing services, and operational capability."
            />

            <div className="grid gap-6 md:grid-cols-2">
                <NumberInput
                    icon={FlaskConical}
                    label="Testing Capacity"
                    value={data.testing_capacity}
                    onChange={(v) => update("testing_capacity", v)}
                    placeholder="Example : 500"
                />

                <SelectInput
                    icon={Package}
                    label="Capacity Unit"
                    value={data.testing_capacity_unit}
                    onChange={(v) => update("testing_capacity_unit", v)}
                    options={[
                        "Samples / Day",
                        "Samples / Week",
                        "Samples / Month",
                    ]}
                />

                <SelectInput
                    icon={Clock3}
                    label="Standard Turnaround Time"
                    value={data.turnaround_time}
                    onChange={(v) => update("turnaround_time", v)}
                    options={TURNAROUND_OPTIONS}
                />
            </div>

            <div className="mt-8 grid gap-4 md:grid-cols-2">
                <CheckboxCard
                    title="Sample Acceptance"
                    description="Accept testing samples from external customers."
                    checked={data.sample_acceptance}
                    onChange={(v) => update("sample_acceptance", v)}
                />

                <CheckboxCard
                    title="Sample Pickup Service"
                    description="Provide sample pickup from customer locations."
                    checked={data.sample_pickup}
                    onChange={(v) => update("sample_pickup", v)}
                />

                <CheckboxCard
                    title="Rush Testing"
                    description="Provide expedited testing services."
                    checked={data.rush_testing}
                    onChange={(v) => update("rush_testing", v)}
                />

                <CheckboxCard
                    title="Digital Test Report"
                    description="Issue reports electronically."
                    checked={data.digital_report}
                    onChange={(v) => update("digital_report", v)}
                />
            </div>

            {/* Laboratory Intelligence */}

            <div className="mt-8 rounded-2xl border border-cyan-100 bg-cyan-50 p-6">
                <div className="font-bold text-cyan-700">
                    Laboratory Intelligence™
                </div>

                <p className="mt-2 text-sm leading-6 text-slate-600">
                    Laboratory capacity, turnaround time, and operational
                    services help brands and manufacturers identify testing
                    partners capable of supporting product development,
                    compliance, and export requirements.
                </p>
            </div>
        </div>
    );
}
