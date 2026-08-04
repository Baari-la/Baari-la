/*
|--------------------------------------------------------------------------
| Certification Section™
|--------------------------------------------------------------------------
|
| DIGESTEX Capability Framework™
|
| Manufacturing certifications and compliance readiness.
|
|--------------------------------------------------------------------------
*/

import { BadgeCheck, ShieldCheck, Award, FileCheck2 } from "lucide-react";

export default function CertificationSection({ framework, data, setData }) {
    const profile = framework?.capability_profile ?? "manufacturer";

    return (
        <section className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            {/* ======================================================
                Header
            ====================================================== */}

            <div className="flex items-start gap-4">
                <div className="rounded-2xl bg-emerald-100 p-3">
                    <BadgeCheck className="h-6 w-6 text-emerald-600" />
                </div>

                <div>
                    <h2 className="text-2xl font-black">
                        Certification & Compliance™
                    </h2>

                    <p className="mt-2 text-sm leading-6 text-slate-500">
                        Provide certifications that demonstrate your
                        manufacturing quality, sustainability and international
                        compliance.
                    </p>
                </div>
            </div>

            {/* ======================================================
                Framework
            ====================================================== */}

            <div className="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-5">
                <div className="font-semibold text-emerald-700">
                    Active Capability Framework
                </div>

                <div className="mt-2 text-lg font-bold">
                    {formatProfile(profile)}
                </div>
            </div>

            {/* ======================================================
                Certification Form
            ====================================================== */}

            <div className="mt-8 grid gap-6 md:grid-cols-2">
                <InputField
                    icon={Award}
                    label="Quality Certifications"
                    value={data.quality_certifications ?? ""}
                    placeholder="ISO 9001"
                    onChange={(value) =>
                        setData("quality_certifications", value)
                    }
                />

                <InputField
                    icon={ShieldCheck}
                    label="Environmental Certifications"
                    value={data.environmental_certifications ?? ""}
                    placeholder="ISO 14001"
                    onChange={(value) =>
                        setData("environmental_certifications", value)
                    }
                />

                <InputField
                    icon={BadgeCheck}
                    label="Textile Certifications"
                    value={data.textile_certifications ?? ""}
                    placeholder="OEKO-TEX®, GOTS, GRS"
                    onChange={(value) =>
                        setData("textile_certifications", value)
                    }
                />

                <InputField
                    icon={FileCheck2}
                    label="Buyer / Brand Compliance"
                    value={data.buyer_compliance ?? ""}
                    placeholder="Higg FEM, ZDHC"
                    onChange={(value) => setData("buyer_compliance", value)}
                />
            </div>

            {/* ======================================================
                Summary
            ====================================================== */}

            <div className="mt-8 rounded-2xl border border-slate-200 bg-slate-50 p-6">
                <div className="font-bold">Certification Summary™</div>

                <div className="mt-4 grid gap-3">
                    <SummaryRow
                        label="Framework"
                        value={formatProfile(profile)}
                    />

                    <SummaryRow
                        label="Quality"
                        value={data.quality_certifications || "-"}
                    />

                    <SummaryRow
                        label="Environmental"
                        value={data.environmental_certifications || "-"}
                    />

                    <SummaryRow
                        label="Textile"
                        value={data.textile_certifications || "-"}
                    />

                    <SummaryRow
                        label="Buyer Compliance"
                        value={data.buyer_compliance || "-"}
                    />
                </div>
            </div>

            {/* ======================================================
                DIGESTEX Intelligence
            ====================================================== */}

            <div className="mt-8 rounded-2xl border border-emerald-100 bg-emerald-50 p-5">
                <div className="font-bold text-emerald-700">
                    DIGESTEX Compliance Intelligence™
                </div>

                <p className="mt-2 text-sm leading-6 text-slate-600">
                    Certification data improves Buyer Matching™, Compliance
                    Readiness™, ESG Intelligence™, Global Sourcing™, and
                    international supplier visibility.
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
                className="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none"
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
