import { Users, Building2, ShoppingBag, Handshake } from "lucide-react";

import CapabilitySectionTitle from "../CapabilitySectionTitle";

import Input from "../Shared/NumberInput";
import SelectInput from "../Shared/SelectInput";
import CheckboxCard from "../Shared/CheckboxCard";

const BUYER_TYPES = [
    "Global Brand",
    "Retail Chain",
    "Buying Office",
    "Importer",
    "Wholesaler",
    "Distributor",
    "Private Label",
    "Government",
    "Manufacturer",
];

export default function BuyerSection({ data, setData }) {
    const update = (field, value) => {
        setData({
            ...data,
            [field]: value,
        });
    };

    const capabilityScore = [
        data.private_label_service,
        data.sourcing_service,
        data.vendor_matching,
        data.product_development_support,
    ].filter(Boolean).length;

    const capabilityLevel =
        capabilityScore >= 4
            ? "Excellent"
            : capabilityScore >= 3
              ? "Advanced"
              : capabilityScore >= 2
                ? "Standard"
                : "Basic";

    return (
        <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <CapabilitySectionTitle
                icon={Users}
                title="Buyer Intelligence™"
                description="Describe your buyer network, sourcing capability, and commercial services."
            />

            <div className="grid gap-6 md:grid-cols-2">
                <Input
                    icon={Building2}
                    label="Major Buyers / Clients"
                    value={data.major_buyers}
                    onChange={(v) => update("major_buyers", v)}
                    placeholder="Nike, Adidas, Uniqlo..."
                />

                <SelectInput
                    icon={ShoppingBag}
                    label="Primary Buyer Type"
                    value={data.primary_buyer_type}
                    onChange={(v) => update("primary_buyer_type", v)}
                    options={BUYER_TYPES}
                />

                <Input
                    icon={Handshake}
                    label="Buyer Regions"
                    value={data.buyer_regions}
                    onChange={(v) => update("buyer_regions", v)}
                    placeholder="Europe, USA, Japan..."
                />

                <Input
                    icon={Users}
                    label="Active Buyer Network"
                    value={data.active_buyers}
                    onChange={(v) => update("active_buyers", v)}
                    placeholder="Example : 120 Buyers"
                />
            </div>

            <div className="mt-8 grid gap-4 md:grid-cols-2">
                <CheckboxCard
                    title="Private Label Service"
                    description="Support buyer private label programs."
                    checked={data.private_label_service}
                    onChange={(v) => update("private_label_service", v)}
                />

                <CheckboxCard
                    title="Sourcing Service"
                    description="Provide supplier sourcing and procurement."
                    checked={data.sourcing_service}
                    onChange={(v) => update("sourcing_service", v)}
                />

                <CheckboxCard
                    title="Vendor Matching"
                    description="Recommend manufacturers based on buyer requirements."
                    checked={data.vendor_matching}
                    onChange={(v) => update("vendor_matching", v)}
                />

                <CheckboxCard
                    title="Product Development Support"
                    description="Support buyers during product development."
                    checked={data.product_development_support}
                    onChange={(v) => update("product_development_support", v)}
                />
            </div>

            {/* Buyer Intelligence */}

            <div className="mt-8 rounded-2xl border border-indigo-100 bg-indigo-50 p-6">
                <div className="flex items-center justify-between">
                    <div>
                        <div className="font-bold text-indigo-700">
                            Buyer Intelligence™
                        </div>

                        <p className="mt-2 text-sm leading-6 text-slate-600">
                            Buyer network information helps manufacturers,
                            suppliers, and Smart Business Matching™ identify
                            trusted commercial partners with established
                            sourcing relationships.
                        </p>
                    </div>

                    <div className="text-right">
                        <div className="text-3xl font-black text-indigo-600">
                            {capabilityScore}/4
                        </div>

                        <div className="text-sm font-semibold text-slate-500">
                            {capabilityLevel}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
