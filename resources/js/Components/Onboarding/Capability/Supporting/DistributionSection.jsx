import { Warehouse, Globe, Truck, Package } from "lucide-react";

import CapabilitySectionTitle from "../CapabilitySectionTitle";
import CheckboxCard from "../Shared/CheckboxCard";
import Input from "../Shared/NumberInput";
import SelectInput from "../Shared/SelectInput";

const COVERAGE_OPTIONS = [
    "Local",
    "Regional",
    "National",
    "Southeast Asia",
    "Asia Pacific",
    "Global",
];

export default function DistributionSection({ data, setData }) {
    const update = (field, value) => {
        setData({
            ...data,
            [field]: value,
        });
    };

    const score = [
        data.stock_available,
        data.local_warehouse,
        data.export_supply,
        data.emergency_delivery,
        data.international_shipping,
    ].filter(Boolean).length;

    const level =
        score >= 5
            ? "Excellent"
            : score >= 4
              ? "Advanced"
              : score >= 2
                ? "Standard"
                : "Basic";

    return (
        <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <CapabilitySectionTitle
                icon={Warehouse}
                title="Distribution Capability™"
                description="Describe your logistics, warehouse, delivery capability, and product availability."
            />

            <div className="grid gap-6 md:grid-cols-2">
                <Input
                    icon={Warehouse}
                    label="Warehouse Location"
                    value={data.warehouse_location}
                    onChange={(v) => update("warehouse_location", v)}
                    placeholder="Jakarta, Surabaya..."
                />

                <SelectInput
                    icon={Globe}
                    label="Distribution Coverage"
                    value={data.distribution_coverage}
                    onChange={(v) => update("distribution_coverage", v)}
                    options={COVERAGE_OPTIONS}
                />

                <Input
                    icon={Truck}
                    label="Typical Delivery Time"
                    value={data.delivery_time}
                    onChange={(v) => update("delivery_time", v)}
                    placeholder="2–5 Days"
                />

                <Input
                    icon={Package}
                    label="Average Stock Availability"
                    value={data.stock_level}
                    onChange={(v) => update("stock_level", v)}
                    placeholder="High / Medium / Low"
                />
            </div>

            <div className="mt-8 grid gap-4 md:grid-cols-2">
                <CheckboxCard
                    title="Stock Available"
                    description="Products are normally available from stock."
                    checked={data.stock_available}
                    onChange={(v) => update("stock_available", v)}
                />

                <CheckboxCard
                    title="Local Warehouse"
                    description="Maintain warehouse inventory locally."
                    checked={data.local_warehouse}
                    onChange={(v) => update("local_warehouse", v)}
                />

                <CheckboxCard
                    title="International Shipping"
                    description="Ship products internationally."
                    checked={data.international_shipping}
                    onChange={(v) => update("international_shipping", v)}
                />

                <CheckboxCard
                    title="Export Supply"
                    description="Supply export-oriented manufacturers."
                    checked={data.export_supply}
                    onChange={(v) => update("export_supply", v)}
                />

                <CheckboxCard
                    title="Emergency Delivery"
                    description="Support urgent customer requirements."
                    checked={data.emergency_delivery}
                    onChange={(v) => update("emergency_delivery", v)}
                />
            </div>

            {/* Distribution Intelligence */}

            <div className="mt-8 rounded-2xl border border-sky-100 bg-sky-50 p-6">
                <div className="flex items-center justify-between">
                    <div>
                        <div className="font-bold text-sky-700">
                            Distribution Intelligence™
                        </div>

                        <p className="mt-2 text-sm leading-6 text-slate-600">
                            Distribution capability helps manufacturers and
                            buyers evaluate supplier responsiveness, inventory
                            readiness, logistics coverage, and delivery
                            performance.
                        </p>
                    </div>

                    <div className="text-right">
                        <div className="text-3xl font-black text-sky-600">
                            {score}/5
                        </div>

                        <div className="text-sm font-semibold text-slate-500">
                            {level}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
