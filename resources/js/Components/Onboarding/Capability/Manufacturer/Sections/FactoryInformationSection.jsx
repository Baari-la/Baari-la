/*
|--------------------------------------------------------------------------
| Factory Information Section™
|--------------------------------------------------------------------------
|
| DIGESTEX Capability Framework™
|
| Basic manufacturing facility information.
|
|--------------------------------------------------------------------------
*/

import { Factory, MapPin, Building2, Calendar } from "lucide-react";

export default function FactoryInformationSection({
    business,
    framework,
    data,
    setData,
}) {
    return (
        <section className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            {/* ---------------------------------------------------------
                Header
            --------------------------------------------------------- */}

            <div className="flex items-start gap-4">
                <div className="rounded-2xl bg-indigo-100 p-3">
                    <Factory className="h-6 w-6 text-indigo-600" />
                </div>

                <div>
                    <h2 className="text-2xl font-black">
                        Factory Information™
                    </h2>

                    <p className="mt-2 text-sm leading-6 text-slate-500">
                        Basic information about your manufacturing facilities
                        used by DIGESTEX Manufacturing Intelligence™.
                    </p>
                </div>
            </div>

            {/* ---------------------------------------------------------
                Form
            --------------------------------------------------------- */}

            <div className="mt-8 grid gap-6 md:grid-cols-2">
                {/* Factory Name */}

                <Field
                    icon={Building2}
                    label="Factory Name"
                    value={data.factory_name ?? ""}
                    placeholder="Factory A"
                    onChange={(value) => setData("factory_name", value)}
                />

                {/* Factory Location */}

                <Field
                    icon={MapPin}
                    label="Factory Location"
                    value={data.factory_location ?? ""}
                    placeholder="Bandung, Indonesia"
                    onChange={(value) => setData("factory_location", value)}
                />

                {/* Factory Type */}

                <SelectField
                    icon={Factory}
                    label="Factory Type"
                    value={data.factory_type ?? ""}
                    onChange={(value) => setData("factory_type", value)}
                    options={[
                        {
                            value: "",
                            label: "-- Select Factory Type --",
                        },
                        {
                            value: "owned",
                            label: "Owned Factory",
                        },
                        {
                            value: "contract",
                            label: "Contract Manufacturing",
                        },
                        {
                            value: "integrated",
                            label: "Integrated Textile Mill",
                        },
                        {
                            value: "multi_site",
                            label: "Multi-site Manufacturing",
                        },
                    ]}
                />

                {/* Operating Since */}

                <Field
                    icon={Calendar}
                    label="Operating Since"
                    value={data.factory_since ?? ""}
                    placeholder="2012"
                    onChange={(value) => setData("factory_since", value)}
                />
            </div>

            {/* ---------------------------------------------------------
                Factory Summary
            --------------------------------------------------------- */}

            <div className="mt-8 rounded-2xl border border-slate-200 bg-slate-50 p-6">
                <div className="font-bold">Factory Summary™</div>

                <div className="mt-4 grid gap-4 md:grid-cols-2">
                    <SummaryRow
                        label="Factory"
                        value={data.factory_name || "-"}
                    />

                    <SummaryRow
                        label="Location"
                        value={data.factory_location || "-"}
                    />

                    <SummaryRow label="Type" value={data.factory_type || "-"} />

                    <SummaryRow
                        label="Operating Since"
                        value={data.factory_since || "-"}
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
                    Factory information helps buyers understand your
                    manufacturing footprint, production organization, and
                    operational readiness.
                </p>
            </div>
        </section>
    );
}

/*
|--------------------------------------------------------------------------
| Field
|--------------------------------------------------------------------------
*/

function Field({ icon: Icon, label, value, onChange, placeholder }) {
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

/*
|--------------------------------------------------------------------------
| Select Field
|--------------------------------------------------------------------------
*/

function SelectField({ icon: Icon, label, value, onChange, options }) {
    return (
        <div>
            <label className="mb-2 flex items-center gap-2 font-semibold">
                <Icon className="h-4 w-4 text-slate-500" />

                {label}
            </label>

            <select
                value={value}
                onChange={(e) => onChange(e.target.value)}
                className="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-indigo-500 focus:outline-none"
            >
                {options.map((option) => (
                    <option key={option.value} value={option.value}>
                        {option.label}
                    </option>
                ))}
            </select>
        </div>
    );
}

/*
|--------------------------------------------------------------------------
| Summary Row
|--------------------------------------------------------------------------
*/

function SummaryRow({ label, value }) {
    return (
        <div className="flex items-center justify-between border-b border-slate-200 py-2">
            <span className="text-sm text-slate-500">{label}</span>

            <span className="font-semibold">{value}</span>
        </div>
    );
}
