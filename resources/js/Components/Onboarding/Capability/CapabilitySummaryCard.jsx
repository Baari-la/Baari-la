import { usePage } from "@inertiajs/react";

import {
    Package,
    Factory,
    Globe,
    ShieldCheck,
    Award,
    BadgeCheck,
    TrendingUp,
} from "lucide-react";

export default function CapabilitySummaryCard({ data, blueprint, framework }) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const UNIT_OPTIONS = [
        { value: "kg", label: "Kilogram (Kg)" },
        { value: "ton", label: "Ton" },
        { value: "meter", label: "Meter" },
        { value: "yard", label: "Yard" },
        { value: "pcs", label: "Pieces (Pcs)" },
        { value: "roll", label: "Roll" },
        { value: "cone", label: "Cone" },
        { value: "bale", label: "Bale" },
        { value: "box", label: "Box" },
        { value: "set", label: "Set" },
    ];

    // Helper function untuk mengambil label unit berdasarkan value
    const getUnitLabel = (unitValue) => {
        if (!unitValue) return "";
        const matchedUnit = UNIT_OPTIONS.find((u) => u.value === unitValue);
        return matchedUnit ? matchedUnit.label : unitValue;
    };

    const completed = [
        data.production_capacity,
        data.capacity_unit,
        data.monthly_capacity,
        data.annual_capacity,
        data.moq,
        data.moq_unit,
        data.lead_time,
    ].filter((v) => v !== "" && v !== null && v !== undefined).length;

    const completion = Math.round((completed / 7) * 100);

    const profileStatus =
        completion >= 90
            ? isEn
                ? "Complete"
                : "Lengkap"
            : completion >= 50
              ? isEn
                  ? "In Progress"
                  : "Dalam Proses"
              : isEn
                ? "Draft"
                : "Draft";

    const readiness =
        completion >= 90
            ? isEn
                ? "Excellent"
                : "Sangat Baik"
            : completion >= 70
              ? isEn
                  ? "Good"
                  : "Baik"
              : completion >= 40
                ? isEn
                    ? "Fair"
                    : "Cukup"
                : isEn
                  ? "Getting Started"
                  : "Baru Dimulai";

    const readinessColor =
        completion >= 90
            ? "text-emerald-600"
            : completion >= 70
              ? "text-blue-600"
              : completion >= 40
                ? "text-amber-600"
                : "text-slate-500";

    return (
        <div className="sticky top-6 space-y-6">
            {/* Capability Passport */}

            <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                <div className="flex items-center gap-3">
                    <blueprint.icon
                        className={`h-7 w-7 text-${blueprint.color}-600`}
                    />

                    <div>
                        <h2>{blueprint.title}</h2>

                        <p>{blueprint.description}</p>
                    </div>
                </div>

                <div className="mt-8 space-y-4">
                    <SummaryRow
                        icon={Factory}
                        label={
                            isEn ? "Production Capacity" : "Kapasitas Produksi"
                        }
                        value={
                            data.production_capacity
                                ? `${data.production_capacity} ${getUnitLabel(data.capacity_unit)}`
                                : "-"
                        }
                    />

                    <SummaryRow
                        icon={Package}
                        label={isEn ? "Monthly Capacity" : "Kapasitas Bulanan"}
                        value={
                            data.monthly_capacity
                                ? `${data.monthly_capacity} ${getUnitLabel(data.capacity_unit)}`
                                : "-"
                        }
                    />

                    <SummaryRow
                        icon={Package}
                        label={isEn ? "Annual Capacity" : "Kapasitas Tahunan"}
                        value={
                            data.annual_capacity
                                ? `${data.annual_capacity} ${getUnitLabel(data.capacity_unit)}`
                                : "-"
                        }
                    />

                    <SummaryRow
                        icon={Package}
                        label="MOQ"
                        value={
                            data.moq
                                ? `${data.moq} ${getUnitLabel(data.moq_unit)}`
                                : "-"
                        }
                    />

                    <SummaryRow
                        icon={TrendingUp}
                        label="Lead Time"
                        value={
                            data.lead_time
                                ? `${data.lead_time} ${isEn ? "Days" : "Hari"}`
                                : "-"
                        }
                    />

                    <StatusRow
                        icon={BadgeCheck}
                        label="OEM"
                        active={data.oem}
                    />

                    <StatusRow
                        icon={BadgeCheck}
                        label="ODM"
                        active={data.odm}
                    />

                    <StatusRow
                        icon={Globe}
                        label={isEn ? "Export Ready" : "Siap Ekspor"}
                        active={data.export_ready}
                    />

                    <StatusRow
                        icon={Package}
                        label={isEn ? "Sampling Service" : "Layanan Sampling"}
                        active={data.sampling_service}
                    />
                </div>
            </div>

            {/* Score */}

            <div className="rounded-3xl border border-emerald-200 bg-emerald-50 p-8">
                <div className="flex items-center gap-2">
                    <Award className="h-6 w-6 text-emerald-600" />

                    <h3 className="font-black text-emerald-700">
                        Capability Score™
                    </h3>
                </div>

                <div className="mt-5 text-center">
                    <div className="text-5xl font-black text-emerald-600">
                        {completion}%
                    </div>

                    <div className="mt-3 text-sm text-slate-600">
                        {isEn
                            ? "Capability Completion"
                            : "Kelengkapan Kapabilitas"}
                    </div>
                </div>

                <div className="mt-6 h-3 overflow-hidden rounded-full bg-emerald-100">
                    <div
                        className="h-full rounded-full bg-emerald-500 transition-all duration-500"
                        style={{
                            width: `${completion}%`,
                        }}
                    />
                </div>
            </div>

            {/* Status */}

            <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                <div className="flex items-center gap-2">
                    <ShieldCheck className="h-6 w-6 text-indigo-600" />

                    <h3 className="font-black">Capability Status</h3>
                </div>

                <div className="mt-5 text-xl font-black text-indigo-600">
                    {profileStatus}
                </div>

                <p className="mt-3 text-sm leading-6 text-slate-600">
                    {isEn
                        ? "Complete your capabilities to strengthen Company Intelligence™ and Smart Business Matching™."
                        : "Lengkapi kapabilitas perusahaan untuk memperkuat Company Intelligence™ dan Smart Business Matching™."}
                </p>
            </div>

            {/* Buyer Readiness */}

            <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                <div className="flex items-center gap-2">
                    <BadgeCheck className={`h-6 w-6 ${readinessColor}`} />

                    <h3 className="font-black">Buyer Readiness™</h3>
                </div>

                <div className={`mt-5 text-2xl font-black ${readinessColor}`}>
                    {readiness}
                </div>

                <p className="mt-3 text-sm leading-6 text-slate-600">
                    {isEn
                        ? "Detailed capability information helps buyers identify your strengths and improves Smart Business Matching™."
                        : "Informasi kapabilitas yang lengkap membantu buyer memahami keunggulan perusahaan dan meningkatkan Smart Business Matching™."}
                </p>
            </div>
        </div>
    );
}

function SummaryRow({ icon: Icon, label, value }) {
    return (
        <div className="flex items-center justify-between border-b border-slate-100 pb-3">
            <div className="flex items-center gap-3">
                <Icon className="h-5 w-5 text-slate-400" />

                <span className="text-sm font-medium">{label}</span>
            </div>

            <span
                className="
        max-w-[170px]
        truncate
        text-right
        text-sm
        font-bold
        text-slate-900
    "
            >
                {value}
            </span>
        </div>
    );
}

/* -------------------------------------------------------------------------- */
/* Status Row */
/* -------------------------------------------------------------------------- */

function StatusRow({ icon: Icon, label, active }) {
    return (
        <div className="flex items-center justify-between border-b border-slate-100 pb-3">
            <div className="flex items-center gap-3">
                <Icon className="h-5 w-5 text-slate-400" />

                <span className="text-sm font-medium">{label}</span>
            </div>

            <span
                className={
                    active
                        ? "inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold tracking-wide text-emerald-700"
                        : "inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-bold tracking-wide text-slate-500"
                }
            >
                {active ? "YES" : "NO"}
            </span>
        </div>
    );
}
