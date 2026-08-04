/*
|--------------------------------------------------------------------------
| Production Section™
|--------------------------------------------------------------------------
|
| DIGESTEX Capability Framework™
|
| Production capability and manufacturing profile.
|
|--------------------------------------------------------------------------
*/

import { Cog, Package, Clock3, Layers3 } from "lucide-react";

export default function ProductionSection({ framework, data, setData }) {
    const profile = framework?.capability_profile ?? "manufacturer";

    return (
        <section className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            {/* ---------------------------------------------------------
                Header
            --------------------------------------------------------- */}

            <div className="flex items-start gap-4">
                <div className="rounded-2xl bg-indigo-100 p-3">
                    <Cog className="h-6 w-6 text-indigo-600" />
                </div>

                <div>
                    <h2 className="text-2xl font-black">
                        Production Capability™
                    </h2>

                    <p className="mt-2 text-sm leading-6 text-slate-500">
                        Describe your manufacturing capability. DIGESTEX uses
                        this information to build your Manufacturing
                        Intelligence™ profile.
                    </p>
                </div>
            </div>

            {/* ---------------------------------------------------------
                Dynamic Profile Banner
            --------------------------------------------------------- */}

            <div className="mt-6 rounded-2xl border border-indigo-200 bg-indigo-50 p-5">
                <div className="font-semibold text-indigo-700">
                    Active Manufacturing Framework
                </div>

                <div className="mt-2 text-lg font-bold">
                    {formatProfile(profile)}
                </div>
            </div>

            {/* ---------------------------------------------------------
                Production Information
            --------------------------------------------------------- */}

            <div className="mt-8 grid gap-6 md:grid-cols-2">
                <InputField
                    icon={Package}
                    label="Primary Products"
                    value={data.primary_products ?? ""}
                    placeholder="Cotton Yarn, Polyester Yarn..."
                    onChange={(value) => setData("primary_products", value)}
                />

                <InputField
                    icon={Layers3}
                    label="Production Process"
                    value={data.production_process ?? ""}
                    placeholder="Ring Spinning, Air Jet..."
                    onChange={(value) => setData("production_process", value)}
                />

                <InputField
                    icon={Clock3}
                    label="Production Schedule"
                    value={data.production_schedule ?? ""}
                    placeholder="24 Hours / 3 Shifts"
                    onChange={(value) => setData("production_schedule", value)}
                />

                <InputField
                    icon={Cog}
                    label="Production Notes"
                    value={data.production_notes ?? ""}
                    placeholder="Additional production information"
                    onChange={(value) => setData("production_notes", value)}
                />
            </div>

            {/* ---------------------------------------------------------
                Manufacturing Summary
            --------------------------------------------------------- */}

            <div className="mt-8 rounded-2xl border border-slate-200 bg-slate-50 p-6">
                <div className="font-bold">Manufacturing Summary™</div>

                <div className="mt-4 grid gap-3">
                    <SummaryRow
                        label="Framework"
                        value={formatProfile(profile)}
                    />

                    <SummaryRow
                        label="Products"
                        value={data.primary_products || "-"}
                    />

                    <SummaryRow
                        label="Production Process"
                        value={data.production_process || "-"}
                    />

                    <SummaryRow
                        label="Production Schedule"
                        value={data.production_schedule || "-"}
                    />
                </div>
            </div>

            {/* ---------------------------------------------------------
                DIGESTEX Intelligence™
            --------------------------------------------------------- */}

            <div className="mt-8 rounded-2xl border border-indigo-100 bg-indigo-50 p-5">
                <div className="font-bold text-indigo-700">
                    DIGESTEX Manufacturing Intelligence™
                </div>

                <p className="mt-2 text-sm leading-6 text-slate-600">
                    Production capability improves buyer confidence,
                    manufacturing visibility, and Smart Business Matching™
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
        <div className="flex justify-between border-b border-slate-200 py-2">
            <span className="text-sm text-slate-500">{label}</span>

            <span className="font-medium">{value}</span>
        </div>
    );
}
