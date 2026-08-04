import { usePage } from "@inertiajs/react";

import {
    Building2,
    Award,
    BadgeCheck,
    ShieldCheck,
    ChevronRight,
} from "lucide-react";

import StatusBadge from "../Shared/StatusBadge";

/*
|--------------------------------------------------------------------------
| Helper Functions
|--------------------------------------------------------------------------
*/

function calculateProfileCompletion(data, business) {
    const completed = [
        data.business_description,
        data.year_established,
        data.legal_entity,
        data.employee_range,
        data.factory_count,

        business?.primary_business_category,
        business?.value_chain_position,

        data.domestic_market,
        data.export_market,

        data.esg_program,
    ].filter(Boolean).length;

    return Math.round((completed / 10) * 100);
}

function calculateBusinessModel(data) {
    const models = [];

    if (data.oem) models.push("OEM");
    if (data.odm) models.push("ODM");
    if (data.obm) models.push("OBM");
    if (data.private_label) models.push("Private Label");

    return models;
}

function calculateESGScore(data) {
    return [
        data.esg_program,
        data.renewable_energy,
        data.recycled_material,
        data.wastewater_treatment,
    ].filter(Boolean).length;
}

function calculateBuyerReadiness(completion, esgScore) {
    let score = completion;

    score += esgScore * 5;

    if (score > 100) score = 100;

    if (score >= 90)
        return {
            label: "Excellent",
            color: "emerald",
        };

    if (score >= 75)
        return {
            label: "Good",
            color: "blue",
        };

    if (score >= 55)
        return {
            label: "Fair",
            color: "amber",
        };

    return {
        label: "Getting Started",
        color: "slate",
    };
}

function calculateESGLevel(score) {
    if (score >= 4)
        return {
            label: "Excellent",
            color: "emerald",
        };

    if (score >= 3)
        return {
            label: "Advanced",
            color: "green",
        };

    if (score >= 2)
        return {
            label: "Developing",
            color: "amber",
        };

    if (score >= 1)
        return {
            label: "Basic",
            color: "orange",
        };

    return {
        label: "Not Available",
        color: "slate",
    };
}

/*
|--------------------------------------------------------------------------
| Business Summary Card
|--------------------------------------------------------------------------
*/

export default function BusinessSummaryCard({ data, business }) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    /*
    |--------------------------------------------------------------------------
    | Intelligence
    |--------------------------------------------------------------------------
    */

    const completion = calculateProfileCompletion(data, business);

    const businessModels = calculateBusinessModel(data);

    const esgScore = calculateESGScore(data);

    const esg = calculateESGLevel(esgScore);

    const readiness = calculateBuyerReadiness(completion, esgScore);

    /*
    |--------------------------------------------------------------------------
    | Business Classification
    |--------------------------------------------------------------------------
    */

    const primaryCategory = business?.primary_business_category ?? "-";

    const valueChain = business?.value_chain_position ?? "-";

    const secondaryCategories = business?.secondary_business_categories ?? [];

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    return (
        <div className="sticky top-6 space-y-6">
            {/* ======================================================
             | Part 2
             | Business Intelligence™
             ====================================================== */}

            {/* Business Intelligence Card */}

            {/* ======================================================
             | Part 3
             | Profile Score™
             | ESG Readiness™
             | Buyer Readiness™
             ====================================================== */}

            {/* Business Profile Score */}

            {/* ESG Readiness */}

            {/* Buyer Readiness */}

            {/* ======================================================
             | Part 4
             | Next Step™
             ====================================================== */}

            {/* Next Capability */}
        </div>
    );
}
{
    /* ======================================================
 | Business Intelligence™
 ====================================================== */
}

<div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
    <div className="flex items-center gap-3">
        <Building2 className="h-7 w-7 text-indigo-600" />

        <div>
            <h2 className="text-xl font-black">Business Intelligence™</h2>

            <p className="text-sm text-slate-500">
                {isEn ? "Live Classification" : "Klasifikasi Langsung"}
            </p>
        </div>
    </div>

    <div className="mt-8 space-y-5">
        <SummaryRow
            label={isEn ? "Primary Category" : "Kategori Utama"}
            value={<StatusBadge color="indigo">{primaryCategory}</StatusBadge>}
        />

        <SummaryRow
            label={isEn ? "Value Chain" : "Value Chain"}
            value={<StatusBadge color="emerald">{valueChain}</StatusBadge>}
        />

        <SummaryRow
            label={isEn ? "Business Model" : "Model Bisnis"}
            value={
                businessModels.length > 0 ? (
                    <div className="flex flex-wrap justify-end gap-2">
                        {businessModels.map((model) => (
                            <StatusBadge key={model} color="blue" size="sm">
                                {model}
                            </StatusBadge>
                        ))}
                    </div>
                ) : (
                    "-"
                )
            }
        />

        <SummaryRow
            label={isEn ? "Market" : "Pasar"}
            value={
                <div className="flex flex-wrap justify-end gap-2">
                    {data.domestic_market && (
                        <StatusBadge color="green" size="sm">
                            Domestic
                        </StatusBadge>
                    )}

                    {data.export_market && (
                        <StatusBadge color="indigo" size="sm">
                            Export
                        </StatusBadge>
                    )}

                    {!data.domestic_market && !data.export_market && "-"}
                </div>
            }
        />

        <SummaryRow
            label={isEn ? "Secondary" : "Kategori Tambahan"}
            value={
                secondaryCategories.length ? (
                    <div className="flex flex-wrap justify-end gap-2">
                        {secondaryCategories.map((item) => (
                            <StatusBadge key={item} color="slate" size="sm">
                                {item}
                            </StatusBadge>
                        ))}
                    </div>
                ) : (
                    "-"
                )
            }
        />
    </div>

    <div className="mt-8 rounded-2xl border border-indigo-100 bg-indigo-50 p-5">
        <div className="flex items-center justify-between">
            <div>
                <div className="text-sm font-bold text-indigo-700">
                    {isEn ? "Capability Preview™" : "Pratinjau Capability™"}
                </div>

                <p className="mt-1 text-sm leading-6 text-slate-600">
                    {primaryCategory === "manufacturer"
                        ? isEn
                            ? "Manufacturer Capability will be displayed in the next step."
                            : "Manufacturer Capability akan ditampilkan pada langkah berikutnya."
                        : primaryCategory === "quality_infrastructure"
                          ? isEn
                              ? "Quality Capability will be displayed in the next step."
                              : "Quality Capability akan ditampilkan pada langkah berikutnya."
                          : primaryCategory === "supporting_industry"
                            ? isEn
                                ? "Supporting Industry Capability will be displayed in the next step."
                                : "Supporting Industry Capability akan ditampilkan pada langkah berikutnya."
                            : isEn
                              ? "Commercial Capability will be displayed in the next step."
                              : "Commercial Capability akan ditampilkan pada langkah berikutnya."}
                </p>
            </div>

            <ChevronRight className="h-6 w-6 text-indigo-400" />
        </div>
    </div>
</div>;

{
    /* ======================================================
 | Business Profile Score™
 ====================================================== */
}

<div className="rounded-3xl border border-emerald-200 bg-emerald-50 p-8">
    <div className="flex items-center gap-3">
        <Award className="h-7 w-7 text-emerald-600" />

        <div>
            <h3 className="text-lg font-black text-emerald-700">
                Business Profile Score™
            </h3>

            <p className="text-sm text-slate-600">
                {isEn ? "Profile Completeness" : "Kelengkapan Profil"}
            </p>
        </div>
    </div>

    <div className="mt-6 text-center">
        <div className="text-5xl font-black text-emerald-600">
            {completion}%
        </div>

        <p className="mt-3 text-sm leading-6 text-slate-600">
            {isEn
                ? "A more complete profile improves company visibility and increases matching accuracy."
                : "Profil yang lebih lengkap meningkatkan visibilitas perusahaan dan akurasi pencarian."}
        </p>
    </div>

    <div className="mt-6 h-3 overflow-hidden rounded-full bg-emerald-100">
        <div
            className="h-full rounded-full bg-emerald-500 transition-all duration-500"
            style={{
                width: `${completion}%`,
            }}
        />
    </div>
</div>;

{
    /* ======================================================
 | ESG Readiness™
 ====================================================== */
}

<div className="rounded-3xl border border-green-200 bg-green-50 p-8">
    <div className="flex items-center gap-3">
        <ShieldCheck className="h-7 w-7 text-green-600" />

        <div>
            <h3 className="text-lg font-black text-green-700">
                ESG Readiness™
            </h3>

            <p className="text-sm text-slate-600">
                {isEn ? "Environmental Readiness" : "Kesiapan ESG"}
            </p>
        </div>
    </div>

    <div className="mt-6 flex items-center justify-between">
        <div className="text-5xl font-black text-green-600">{esgScore}/4</div>

        <StatusBadge color={esg.color}>{esg.label}</StatusBadge>
    </div>

    <div className="mt-5 grid grid-cols-2 gap-3">
        <StatusBadge color={data.esg_program ? "emerald" : "slate"}>
            ESG
        </StatusBadge>

        <StatusBadge color={data.renewable_energy ? "emerald" : "slate"}>
            Renewable
        </StatusBadge>

        <StatusBadge color={data.recycled_material ? "emerald" : "slate"}>
            Recycled
        </StatusBadge>

        <StatusBadge color={data.wastewater_treatment ? "emerald" : "slate"}>
            Wastewater
        </StatusBadge>
    </div>
</div>;

{
    /* ======================================================
 | Buyer Readiness™
 ====================================================== */
}

<div className="rounded-3xl border border-indigo-200 bg-indigo-50 p-8">
    <div className="flex items-center gap-3">
        <BadgeCheck className="h-7 w-7 text-indigo-600" />

        <div>
            <h3 className="text-lg font-black text-indigo-700">
                Buyer Readiness™
            </h3>

            <p className="text-sm text-slate-600">
                {isEn
                    ? "Buyer Confidence Indicator"
                    : "Indikator Kesiapan Buyer"}
            </p>
        </div>
    </div>

    <div className="mt-6 flex items-center justify-between">
        <div className="text-3xl font-black text-indigo-700">
            {readiness.label}
        </div>

        <StatusBadge color={readiness.color}>{readiness.label}</StatusBadge>
    </div>

    <p className="mt-5 text-sm leading-6 text-slate-600">
        {isEn
            ? "Buyer Readiness is calculated from profile completeness, business identity, market presence, and ESG information."
            : "Buyer Readiness dihitung berdasarkan kelengkapan profil, identitas bisnis, market presence, dan informasi ESG."}
    </p>
</div>;
{
    /* ======================================================
 | Next Step™
 ====================================================== */
}

<div className="rounded-3xl border border-indigo-200 bg-gradient-to-br from-indigo-50 to-blue-50 p-8">
    <div className="flex items-center gap-3">
        <ChevronRight className="h-7 w-7 text-indigo-600" />

        <div>
            <h3 className="text-lg font-black text-indigo-700">Next Step™</h3>

            <p className="text-sm text-slate-600">
                {isEn
                    ? "Customized Capability Profile"
                    : "Capability Profile yang Disesuaikan"}
            </p>
        </div>
    </div>

    <div className="mt-6">
        <div className="text-2xl font-black text-indigo-700">
            {getCapabilityTitle(primaryCategory)}
        </div>

        <p className="mt-3 text-sm leading-6 text-slate-600">
            {getCapabilityDescription(primaryCategory, isEn)}
        </p>
    </div>

    <div className="mt-6 space-y-3">
        {getCapabilityItems(primaryCategory).map((item) => (
            <div
                key={item}
                className="flex items-center gap-3 rounded-xl bg-white px-4 py-3 shadow-sm"
            >
                <ChevronRight className="h-4 w-4 text-indigo-500" />

                <span className="text-sm font-medium text-slate-700">
                    {item}
                </span>
            </div>
        ))}
    </div>
</div>;
function SummaryRow({ label, value }) {
    return (
        <div className="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
            <div className="text-sm font-medium text-slate-600">{label}</div>

            <div className="max-w-[220px] text-right font-semibold text-slate-800">
                {value}
            </div>
        </div>
    );
}
function getCapabilityTitle(category) {
    switch (category) {
        case "manufacturer":
            return "Manufacturer Capability™";

        case "quality_infrastructure":
            return "Quality Capability™";

        case "supporting_industry":
            return "Supporting Industry Capability™";

        case "commercial":
            return "Commercial Capability™";

        default:
            return "Capability Profile™";
    }
}

function getCapabilityDescription(category, isEn) {
    switch (category) {
        case "manufacturer":
            return isEn
                ? "DIGESTEX will prepare a manufacturing capability profile focusing on production capacity, factory utilization, manufacturing services, and operational flexibility."
                : "DIGESTEX akan menyiapkan profil kapabilitas manufaktur yang berfokus pada kapasitas produksi, utilisasi pabrik, layanan manufaktur, dan fleksibilitas operasional.";

        case "quality_infrastructure":
            return isEn
                ? "DIGESTEX will prepare a quality capability profile covering laboratory services, accreditation, testing, inspection, and certification."
                : "DIGESTEX akan menyiapkan profil kapabilitas quality yang mencakup laboratorium, akreditasi, pengujian, inspeksi, dan sertifikasi.";

        case "supporting_industry":
            return isEn
                ? "DIGESTEX will prepare a supporting industry capability profile including products, distribution, technical support, and industrial services."
                : "DIGESTEX akan menyiapkan profil industri pendukung yang mencakup produk, distribusi, technical support, dan layanan industri.";

        case "commercial":
            return isEn
                ? "DIGESTEX will prepare a commercial capability profile including buyer network, sourcing activities, market coverage, and export readiness."
                : "DIGESTEX akan menyiapkan profil kapabilitas komersial yang mencakup buyer network, sourcing, market coverage, dan export readiness.";

        default:
            return isEn
                ? "DIGESTEX will prepare your capability profile."
                : "DIGESTEX akan menyiapkan Capability Profile perusahaan Anda.";
    }
}

function getCapabilityItems(category) {
    switch (category) {
        case "manufacturer":
            return [
                "Capacity Intelligence™",

                "Production Services",

                "Commercial Capability",

                "Production Flexibility",
            ];

        case "quality_infrastructure":
            return [
                "Laboratory Services",

                "Accreditation",

                "Certification",

                "Inspection",
            ];

        case "supporting_industry":
            return [
                "Products",

                "Technical Support",

                "Distribution",

                "Industrial Services",
            ];

        case "commercial":
            return [
                "Market Coverage",

                "Buyer Network",

                "Export Readiness",

                "Trade Services",
            ];

        default:
            return ["Capability Profile"];
    }
}
