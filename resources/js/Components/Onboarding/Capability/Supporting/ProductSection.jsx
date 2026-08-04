import { Package, Layers, Tags, Factory } from "lucide-react";

import CapabilitySectionTitle from "../CapabilitySectionTitle";

import Input from "../Shared/NumberInput";
import SelectInput from "../Shared/SelectInput";

const PRODUCT_CATEGORIES = [
    "Textile Machinery",
    "Knitting Machinery",
    "Weaving Machinery",
    "Spinning Machinery",
    "Dyeing Machinery",
    "Printing Machinery",
    "Finishing Machinery",
    "Industrial Automation",
    "Textile Chemicals",
    "Textile Dyes",
    "Auxiliary Chemicals",
    "Lubricants",
    "Textile Accessories",
    "Buttons",
    "Zippers",
    "Labels",
    "Sewing Thread",
    "Needles",
    "Spare Parts",
    "Software",
    "Other",
];

const INDUSTRIES = [
    "Fiber",
    "Spinning",
    "Weaving",
    "Knitting",
    "Dyeing",
    "Printing",
    "Garment",
    "Home Textile",
    "Technical Textile",
];

export default function ProductSection({ data, setData }) {
    const update = (field, value) => {
        setData({
            ...data,
            [field]: value,
        });
    };

    return (
        <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <CapabilitySectionTitle
                icon={Package}
                title="Product Portfolio™"
                description="Describe your products, brands, and industries served to improve Smart Business Matching™."
            />

            <div className="grid gap-6 md:grid-cols-2">
                <SelectInput
                    icon={Layers}
                    label="Primary Product Category"
                    value={data.primary_product_category}
                    onChange={(v) => update("primary_product_category", v)}
                    options={PRODUCT_CATEGORIES}
                />

                <Input
                    icon={Tags}
                    label="Brands"
                    value={data.brands}
                    onChange={(v) => update("brands", v)}
                    placeholder="Example : Picanol, Toyota, YKK..."
                />

                <Input
                    icon={Package}
                    label="Main Products"
                    value={data.main_products}
                    onChange={(v) => update("main_products", v)}
                    placeholder="Example : Air Jet Looms, Sewing Thread..."
                />

                <SelectInput
                    icon={Factory}
                    label="Industry Served"
                    value={data.industry_served}
                    onChange={(v) => update("industry_served", v)}
                    options={INDUSTRIES}
                />

                <Input
                    icon={Package}
                    label="Applications"
                    value={data.product_applications}
                    onChange={(v) => update("product_applications", v)}
                    placeholder="Example : Denim, Apparel, Home Textile..."
                />
            </div>

            {/* Product Intelligence */}

            <div className="mt-8 rounded-2xl border border-blue-100 bg-blue-50 p-6">
                <div className="font-bold text-blue-700">
                    Product Intelligence™
                </div>

                <p className="mt-2 text-sm leading-6 text-slate-600">
                    Product portfolio information helps manufacturers, sourcing
                    teams, and Smart Business Matching™ identify suppliers based
                    on product categories, brands, applications, and industries
                    served.
                </p>
            </div>
        </div>
    );
}
