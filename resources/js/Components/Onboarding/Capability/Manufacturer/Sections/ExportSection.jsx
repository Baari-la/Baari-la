/*
|--------------------------------------------------------------------------
| Export Section™
|--------------------------------------------------------------------------
|
| DIGESTEX Capability Framework™
|
| Export readiness and international trade capability.
|
|--------------------------------------------------------------------------
*/

import { Globe2, Ship, FileCheck, Languages } from "lucide-react";

export default function ExportSection({ framework, data, setData }) {
    const profile = framework?.capability_profile ?? "manufacturer";

    return (
        <section className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            {/* ======================================================
                Header
            ====================================================== */}

            <div className="flex items-start gap-4">
                <div className="rounded-2xl bg-sky-100 p-3">
                    <Globe2 className="h-6 w-6 text-sky-600" />
                </div>

                <div>
                    <h2 className="text-2xl font-black">Export Capability™</h2>

                    <p className="mt-2 text-sm leading-6 text-slate-500">
                        Describe your export experience and international
                        business readiness.
                    </p>
                </div>
            </div>

            {/* ======================================================
                Framework
            ====================================================== */}

            <div className="mt-6 rounded-2xl border border-sky-200 bg-sky-50 p-5">
                <div className="font-semibold text-sky-700">
                    Active Capability Framework
                </div>

                <div className="mt-2 text-lg font-bold">
                    {formatProfile(profile)}
                </div>
            </div>

            {/* ======================================================
                Export Information
            ====================================================== */}

            <div className="mt-8 grid gap-6 md:grid-cols-2">
                <InputField
                    icon={Globe2}
                    label="Export Experience (Years)"
                    value={data.export_experience ?? ""}
                    placeholder="15"
                    onChange={(value) => setData("export_experience", value)}
                />

                <InputField
                    icon={Ship}
                    label="Main Export Destinations"
                    value={data.export_destinations ?? ""}
                    placeholder="USA, Germany, Japan"
                    onChange={(value) => setData("export_destinations", value)}
                />

                <InputField
                    icon={Languages}
                    label="Communication Languages"
                    value={data.business_languages ?? ""}
                    placeholder="English, Japanese"
                    onChange={(value) => setData("business_languages", value)}
                />

                <InputField
                    icon={FileCheck}
                    label="Export Certifications"
                    value={data.export_certifications ?? ""}
                    placeholder="OEKO-TEX®, GOTS, ISO 9001"
                    onChange={(value) =>
                        setData("export_certifications", value)
                    }
                />
            </div>

            {/* ======================================================
                Export Summary
            ====================================================== */}

            <div className="mt-8 rounded-2xl border border-slate-200 bg-slate-50 p-6">
                <div className="font-bold">Export Summary™</div>

                <div className="mt-4 grid gap-3">
                    <SummaryRow
                        label="Framework"
                        value={formatProfile(profile)}
                    />

                    <SummaryRow
                        label="Experience"
                        value={
                            data.export_experience
                                ? `${data.export_experience} Years`
                                : "-"
                        }
                    />

                    <SummaryRow
                        label="Destinations"
                        value={data.export_destinations || "-"}
                    />

                    <SummaryRow
                        label="Languages"
                        value={data.business_languages || "-"}
                    />

                    <SummaryRow
                        label="Certifications"
                        value={data.export_certifications || "-"}
                    />
                </div>
            </div>

            {/* ======================================================
                DIGESTEX Intelligence™
            ====================================================== */}

            <div className="mt-8 rounded-2xl border border-sky-100 bg-sky-50 p-5">
                <div className="font-bold text-sky-700">
                    DIGESTEX Export Intelligence™
                </div>

                <p className="mt-2 text-sm leading-6 text-slate-600">
                    Export capability strengthens your Buyer Readiness™, Global
                    Sourcing™, RFQ Matching™, and International Market
                    Visibility.
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
                className="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-sky-500 focus:outline-none"
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
