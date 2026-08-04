/*
|--------------------------------------------------------------------------
| Capacity Section™
|--------------------------------------------------------------------------
|
| DIGESTEX Capability Framework™
|
| Production capacity and operational capability.
|
|--------------------------------------------------------------------------
*/

import { Gauge, Package, CalendarClock, Boxes } from "lucide-react";

export default function CapacitySection({ framework, data, setData }) {
    const profile = framework?.capability_profile ?? "manufacturer";

    return (
        <section className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            {/* ======================================================
                Header
            ====================================================== */}

            <div className="flex items-start gap-4">
                <div className="rounded-2xl bg-indigo-100 p-3">
                    <Gauge className="h-6 w-6 text-indigo-600" />
                </div>

                <div>
                    <h2 className="text-2xl font-black">
                        Production Capacity™
                    </h2>

                    <p className="mt-2 text-sm leading-6 text-slate-500">
                        Describe your production capability to help buyers
                        understand your manufacturing scale.
                    </p>
                </div>
            </div>

            {/* ======================================================
                Active Framework
            ====================================================== */}

            <div className="mt-6 rounded-2xl border border-indigo-200 bg-indigo-50 p-5">
                <div className="font-semibold text-indigo-700">
                    Active Capability Framework
                </div>

                <div className="mt-2 text-lg font-bold">
                    {formatProfile(profile)}
                </div>
            </div>

            {/* ======================================================
                Capacity Form
            ====================================================== */}

            <div className="mt-8 grid gap-6 md:grid-cols-2">
                <InputField
                    icon={Package}
                    label="Monthly Capacity"
                    value={data.monthly_capacity ?? ""}
                    placeholder="250 Tons"
                    onChange={(value) => setData("monthly_capacity", value)}
                />

                <InputField
                    icon={Boxes}
                    label="Minimum Order Quantity (MOQ)"
                    value={data.moq ?? ""}
                    placeholder="500 kg"
                    onChange={(value) => setData("moq", value)}
                />

                <InputField
                    icon={CalendarClock}
                    label="Lead Time"
                    value={data.lead_time ?? ""}
                    placeholder="30 Days"
                    onChange={(value) => setData("lead_time", value)}
                />

                <InputField
                    icon={Gauge}
                    label="Current Utilization (%)"
                    value={data.capacity_utilization ?? ""}
                    placeholder="85%"
                    onChange={(value) => setData("capacity_utilization", value)}
                />
            </div>

            {/* ======================================================
                Summary
            ====================================================== */}

            <div className="mt-8 rounded-2xl border border-slate-200 bg-slate-50 p-6">
                <div className="font-bold">Capacity Summary™</div>

                <div className="mt-4 grid gap-3">
                    <SummaryRow
                        label="Framework"
                        value={formatProfile(profile)}
                    />

                    <SummaryRow
                        label="Monthly Capacity"
                        value={data.monthly_capacity || "-"}
                    />

                    <SummaryRow label="MOQ" value={data.moq || "-"} />

                    <SummaryRow
                        label="Lead Time"
                        value={data.lead_time || "-"}
                    />

                    <SummaryRow
                        label="Utilization"
                        value={data.capacity_utilization || "-"}
                    />
                </div>
            </div>

            {/* ======================================================
                DIGESTEX Intelligence
            ====================================================== */}

            <div className="mt-8 rounded-2xl border border-indigo-100 bg-indigo-50 p-5">
                <div className="font-bold text-indigo-700">
                    DIGESTEX Capacity Intelligence™
                </div>

                <p className="mt-2 text-sm leading-6 text-slate-600">
                    Capacity information improves Buyer Matching™, Production
                    Planning™, Smart Sourcing™, and Manufacturing Intelligence™
                    across the DIGESTEX ecosystem.
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
