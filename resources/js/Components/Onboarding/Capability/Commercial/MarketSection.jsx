import { Globe, Map, Package, Building2 } from "lucide-react";

import CapabilitySectionTitle from "../CapabilitySectionTitle";

import Input from "../Shared/NumberInput";
import SelectInput from "../Shared/SelectInput";

const MARKET_SCOPE = [
    "Local",
    "Regional",
    "National",
    "ASEAN",
    "Asia Pacific",
    "Middle East",
    "Europe",
    "North America",
    "South America",
    "Africa",
    "Global",
];

const CUSTOMER_SEGMENTS = [
    "Manufacturer",
    "Brand Owner",
    "Retail Chain",
    "Buying Office",
    "Importer",
    "Exporter",
    "Wholesaler",
    "Distributor",
    "Government",
];

export default function MarketSection({ data, setData }) {
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
                title="Market Intelligence™"
                description="Describe your market coverage, industries served, and customer segments to improve business matching."
            />

            <div className="grid gap-6 md:grid-cols-2">
                <SelectInput
                    icon={Map}
                    label="Market Coverage"
                    value={data.market_coverage}
                    onChange={(v) => update("market_coverage", v)}
                    options={MARKET_SCOPE}
                />

                <Input
                    icon={Globe}
                    label="Countries Served"
                    value={data.countries_served}
                    onChange={(v) => update("countries_served", v)}
                    placeholder="Indonesia, Vietnam, Japan..."
                />

                <Input
                    icon={Package}
                    label="Product Categories"
                    value={data.product_categories}
                    onChange={(v) => update("product_categories", v)}
                    placeholder="Yarn, Fabric, Garment..."
                />

                <SelectInput
                    icon={Building2}
                    label="Primary Customer Segment"
                    value={data.customer_segment}
                    onChange={(v) => update("customer_segment", v)}
                    options={CUSTOMER_SEGMENTS}
                />

                <Input
                    icon={Building2}
                    label="Industries Served"
                    value={data.industries_served}
                    onChange={(v) => update("industries_served", v)}
                    placeholder="Textile, Apparel, Home Textile..."
                />
            </div>

            {/* Market Intelligence */}

            <div className="mt-8 rounded-2xl border border-blue-100 bg-blue-50 p-6">
                <div className="font-bold text-blue-700">
                    Market Intelligence™
                </div>

                <p className="mt-2 text-sm leading-6 text-slate-600">
                    Market coverage information helps manufacturers, brands,
                    sourcing teams, and Smart Business Matching™ identify
                    companies with the right regional presence, product focus,
                    and customer specialization.
                </p>
            </div>
        </div>
    );
}
