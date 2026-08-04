/*
|--------------------------------------------------------------------------
| Commercial Section™
|--------------------------------------------------------------------------
|
| DIGESTEX Capability Framework™
|
| Commercial capabilities and buyer readiness.
|
|--------------------------------------------------------------------------
*/

import { BriefcaseBusiness, Globe2, CreditCard, Truck } from "lucide-react";

export default function CommercialSection({ framework, data, setData }) {
    const profile = framework?.capability_profile ?? "manufacturer";

    return (
        <section className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            {/* ======================================================
                Header
            ====================================================== */}

            <div className="flex items-start gap-4">
                <div className="rounded-2xl bg-amber-100 p-3">
                    <BriefcaseBusiness className="h-6 w-6 text-amber-600" />
                </div>

                <div>
                    <h2 className="text-2xl font-black">
                        Commercial Capability™
                    </h2>

                    <p className="mt-2 text-sm leading-6 text-slate-500">
                        Describe how your company works with buyers, brands and
                        global sourcing partners.
                    </p>
                </div>
            </div>

            {/* ======================================================
                Framework
            ====================================================== */}

            <div className="mt-6 rounded-2xl border border-amber-200 bg-amber-50 p-5">
                <div className="font-semibold text-amber-700">
                    Active Capability Framework
                </div>

                <div className="mt-2 text-lg font-bold">
                    {formatProfile(profile)}
                </div>
            </div>

            {/* ======================================================
                Commercial Information
            ====================================================== */}

            <div className="mt-8 grid gap-6 md:grid-cols-2">
                <InputField
                    icon={Globe2}
                    label="Primary Export Markets"
                    value={data.primary_export_markets ?? ""}
                    placeholder="USA, EU, Japan"
                    onChange={(value) =>
                        setData("primary_export_markets", value)
                    }
                />

                <InputField
                    icon={Truck}
                    label="Preferred Incoterms"
                    value={data.incoterms ?? ""}
                    placeholder="FOB, CIF, EXW"
                    onChange={(value) => setData("incoterms", value)}
                />

                <InputField
                    icon={CreditCard}
                    label="Payment Terms"
                    value={data.payment_terms ?? ""}
                    placeholder="TT, LC at Sight"
                    onChange={(value) => setData("payment_terms", value)}
                />

                <InputField
                    icon={BriefcaseBusiness}
                    label="Main Customer Types"
                    value={data.customer_types ?? ""}
                    placeholder="Brand, Importer, Retailer"
                    onChange={(value) => setData("customer_types", value)}
                />
            </div>

            {/* ======================================================
                Summary
            ====================================================== */}

            <div className="mt-8 rounded-2xl border border-slate-200 bg-slate-50 p-6">
                <div className="font-bold">Commercial Summary™</div>

                <div className="mt-4 grid gap-3">
                    <SummaryRow
                        label="Framework"
                        value={formatProfile(profile)}
                    />

                    <SummaryRow
                        label="Export Markets"
                        value={data.primary_export_markets || "-"}
                    />

                    <SummaryRow
                        label="Incoterms"
                        value={data.incoterms || "-"}
                    />

                    <SummaryRow
                        label="Payment Terms"
                        value={data.payment_terms || "-"}
                    />

                    <SummaryRow
                        label="Customer Types"
                        value={data.customer_types || "-"}
                    />
                </div>
            </div>

            {/* ======================================================
                DIGESTEX Intelligence™
            ====================================================== */}

            <div className="mt-8 rounded-2xl border border-amber-100 bg-amber-50 p-5">
                <div className="font-bold text-amber-700">
                    DIGESTEX Commercial Intelligence™
                </div>

                <p className="mt-2 text-sm leading-6 text-slate-600">
                    Commercial capability improves Buyer Matching™, RFQ
                    Matching™, Export Intelligence™, and Global Supply Chain
                    recommendations.
                </p>
            </div>
        </section>
    );
}

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function formatProfile(profile) {
    return profile
        .replaceAll("_", " ")
        .replace(/\b\w/g, (c) => c.toUpperCase());
}

function InputField({ icon: Icon, label, value, onChange, placeholder }) {
    return (
        <div>
            <label className="mb-2 flex items-center gap-2 font-semibold">
                <Icon className="h-4 w-4 text-slate-500" />

                {label}
            </label>

            <input
                type="text"
                value={value}
                placeholder={placeholder}
                onChange={(e) => onChange(e.target.value)}
                className="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-indigo-500 focus:outline-none"
            />
        </div>
    );
}

function SummaryRow({ label, value }) {
    return (
        <div className="flex items-center justify-between border-b border-slate-200 py-2">
            <span className="text-sm text-slate-500">{label}</span>

            <span className="font-medium">{value}</span>
        </div>
    );
}
