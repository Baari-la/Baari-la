/*
|--------------------------------------------------------------------------
| Machinery Section™
|--------------------------------------------------------------------------
|
| DIGESTEX Capability Framework™
|
| Manufacturing machinery information.
|
|--------------------------------------------------------------------------
*/

import { Cpu, Factory, Settings, Wrench } from "lucide-react";

export default function MachinerySection({ framework, data, setData }) {
    const profile = framework?.capability_profile ?? "manufacturer";

    return (
        <section className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            {/* ======================================================
                Header
            ====================================================== */}

            <div className="flex items-start gap-4">
                <div className="rounded-2xl bg-indigo-100 p-3">
                    <Cpu className="h-6 w-6 text-indigo-600" />
                </div>

                <div>
                    <h2 className="text-2xl font-black">
                        Machinery Capability™
                    </h2>

                    <p className="mt-2 text-sm leading-6 text-slate-500">
                        Describe the major machinery used in your manufacturing
                        facilities.
                    </p>
                </div>
            </div>

            {/* ======================================================
                Framework Banner
            ====================================================== */}

            <div className="mt-6 rounded-2xl border border-indigo-200 bg-indigo-50 p-5">
                <div className="font-semibold text-indigo-700">
                    Active Manufacturing Framework
                </div>

                <div className="mt-2 text-lg font-bold">
                    {formatProfile(profile)}
                </div>
            </div>

            {/* ======================================================
                Machinery Form
            ====================================================== */}

            <div className="mt-8 grid gap-6 md:grid-cols-2">
                <InputField
                    icon={Factory}
                    label="Machine Category"
                    value={data.machine_category ?? ""}
                    placeholder="Air Jet Loom"
                    onChange={(value) => setData("machine_category", value)}
                />

                <InputField
                    icon={Cpu}
                    label="Machine Brand"
                    value={data.machine_brand ?? ""}
                    placeholder="Toyota"
                    onChange={(value) => setData("machine_brand", value)}
                />

                <InputField
                    icon={Settings}
                    label="Total Machines"
                    value={data.machine_quantity ?? ""}
                    placeholder="120"
                    onChange={(value) => setData("machine_quantity", value)}
                />

                <InputField
                    icon={Wrench}
                    label="Year Installed"
                    value={data.machine_year ?? ""}
                    placeholder="2022"
                    onChange={(value) => setData("machine_year", value)}
                />
            </div>

            {/* ======================================================
                Summary
            ====================================================== */}

            <div className="mt-8 rounded-2xl border border-slate-200 bg-slate-50 p-6">
                <div className="font-bold">Machinery Summary™</div>

                <div className="mt-4 grid gap-3">
                    <SummaryRow
                        label="Framework"
                        value={formatProfile(profile)}
                    />

                    <SummaryRow
                        label="Category"
                        value={data.machine_category || "-"}
                    />

                    <SummaryRow
                        label="Brand"
                        value={data.machine_brand || "-"}
                    />

                    <SummaryRow
                        label="Quantity"
                        value={data.machine_quantity || "-"}
                    />

                    <SummaryRow
                        label="Year Installed"
                        value={data.machine_year || "-"}
                    />
                </div>
            </div>

            {/* ======================================================
                DIGESTEX Intelligence™
            ====================================================== */}

            <div className="mt-8 rounded-2xl border border-indigo-100 bg-indigo-50 p-5">
                <div className="font-bold text-indigo-700">
                    DIGESTEX Machine Intelligence™
                </div>

                <p className="mt-2 text-sm leading-6 text-slate-600">
                    Machinery information helps buyers understand your
                    manufacturing technology, production capability, automation
                    level, and factory readiness.
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
