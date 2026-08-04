import { usePage } from "@inertiajs/react";

import { Building2, Factory, Briefcase } from "lucide-react";

export default function BusinessInformationCard({ data, setData }) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const update = (field, value) => {
        setData(field, value);
    };

    return (
        <div
            className="
                rounded-3xl
                border
                border-slate-200
                bg-white
                p-8
                shadow-sm
            "
        >
            {/* =======================================================
            | Header
            ======================================================= */}

            <div className="mb-8">
                <h2 className="text-2xl font-black text-slate-900">
                    {isEn ? "Business Information" : "Informasi Bisnis"}
                </h2>

                <p className="mt-2 text-sm leading-7 text-slate-500">
                    {isEn
                        ? "Build your Business Intelligence Profile™ to help buyers, brands, and investors understand your business profile, market coverage, and competitive strengths."
                        : "Bangun Business Intelligence Profile™ untuk membantu buyer, brand, dan investor memahami profil bisnis, cakupan pasar, serta kekuatan kompetitif perusahaan Anda."}
                </p>
            </div>

            {/* =======================================================
            | Company Description
            ======================================================= */}

            <SectionTitle
                icon={Building2}
                title={isEn ? "Company Description" : "Deskripsi Perusahaan"}
            />

            <div className="mt-5">
                <label className="mb-2 block font-semibold text-slate-700">
                    {isEn ? "Company Overview" : "Gambaran Umum Perusahaan"}
                </label>

                <textarea
                    rows={5}
                    value={data.business_description ?? ""}
                    onChange={(e) =>
                        update("business_description", e.target.value)
                    }
                    placeholder={
                        isEn
                            ? "Describe your company, products, customers, strengths and business focus..."
                            : "Jelaskan perusahaan, produk, pelanggan, keunggulan, dan fokus bisnis perusahaan..."
                    }
                    className="
                        w-full
                        rounded-2xl
                        border
                        border-slate-300
                        p-4
                        leading-7
                        transition
                        focus:border-indigo-500
                        focus:outline-none
                        focus:ring-2
                        focus:ring-indigo-100
                    "
                />
            </div>

            {/* =======================================================
            | Business Activities
            ======================================================= */}

            <div className="mt-10">
                <SectionTitle
                    icon={Factory}
                    title={isEn ? "Business Activities" : "Aktivitas Bisnis"}
                />

                <p className="mt-2 text-sm text-slate-500">
                    {isEn
                        ? "Select all activities that represent your company's business."
                        : "Pilih seluruh aktivitas yang sesuai dengan kegiatan bisnis perusahaan Anda."}
                </p>

                <div className="mt-6 grid gap-4 md:grid-cols-2">
                    <Checkbox
                        label="Fiber Producer"
                        checked={data.is_fiber_producer}
                        onChange={(v) => update("is_fiber_producer", v)}
                    />

                    <Checkbox
                        label="Spinner"
                        checked={data.is_spinner}
                        onChange={(v) => update("is_spinner", v)}
                    />

                    <Checkbox
                        label="Weaving"
                        checked={data.is_weaving}
                        onChange={(v) => update("is_weaving", v)}
                    />

                    <Checkbox
                        label="Knitting"
                        checked={data.is_knitting}
                        onChange={(v) => update("is_knitting", v)}
                    />

                    <Checkbox
                        label="Dyeing & Finishing"
                        checked={data.is_dyeing_finishing}
                        onChange={(v) => update("is_dyeing_finishing", v)}
                    />

                    <Checkbox
                        label="Printing"
                        checked={data.is_printing}
                        onChange={(v) => update("is_printing", v)}
                    />

                    <Checkbox
                        label="Garment"
                        checked={data.is_garment}
                        onChange={(v) => update("is_garment", v)}
                    />

                    <Checkbox
                        label="Trader"
                        checked={data.is_trader}
                        onChange={(v) => update("is_trader", v)}
                    />

                    <Checkbox
                        label="Brand Owner"
                        checked={data.is_brand}
                        onChange={(v) => update("is_brand", v)}
                    />

                    <Checkbox
                        label="Buying Office"
                        checked={data.is_buying_office}
                        onChange={(v) => update("is_buying_office", v)}
                    />
                </div>
            </div>

            {/* =======================================================
            | Business Strategy
            ======================================================= */}

            <div className="mt-10">
                <SectionTitle
                    icon={Briefcase}
                    title={isEn ? "Business Strategy" : "Strategi Bisnis"}
                />

                <p className="mt-2 text-sm text-slate-500">
                    {isEn
                        ? "Select the business models and commercial strategies offered by your company."
                        : "Pilih model bisnis dan strategi komersial yang dijalankan perusahaan Anda."}
                </p>

                <div className="mt-6 grid gap-4 md:grid-cols-2">
                    <Checkbox
                        label="OEM Manufacturing"
                        checked={data.oem}
                        onChange={(v) => update("oem", v)}
                    />

                    <Checkbox
                        label="ODM Manufacturing"
                        checked={data.odm}
                        onChange={(v) => update("odm", v)}
                    />

                    <Checkbox
                        label="Own Brand (OBM)"
                        checked={data.obm}
                        onChange={(v) => update("obm", v)}
                    />

                    <Checkbox
                        label="Private Label"
                        checked={data.private_label}
                        onChange={(v) => update("private_label", v)}
                    />
                </div>
            </div>

            {/* =======================================================
            | Company Profile
            ======================================================= */}

            <div className="mt-10">
                <SectionTitle
                    icon={Building2}
                    title={isEn ? "Company Profile" : "Profil Perusahaan"}
                />

                <p className="mt-2 text-sm text-slate-500">
                    {isEn
                        ? "General business information used for Business Intelligence™, benchmarking and executive analysis."
                        : "Informasi umum perusahaan yang digunakan untuk Business Intelligence™, benchmarking, dan analisis eksekutif."}
                </p>

                <div className="mt-6 grid gap-6 md:grid-cols-2">
                    <Input
                        icon={Calendar}
                        label={isEn ? "Established Year" : "Tahun Berdiri"}
                        value={data.year_established}
                        onChange={(v) => update("year_established", v)}
                    />

                    <Input
                        icon={Users}
                        label={isEn ? "Employee Range" : "Jumlah Karyawan"}
                        value={data.employee_range}
                        onChange={(v) => update("employee_range", v)}
                    />

                    <Input
                        icon={Briefcase}
                        label={isEn ? "Legal Entity" : "Badan Hukum"}
                        value={data.legal_entity}
                        onChange={(v) => update("legal_entity", v)}
                    />

                    <Input
                        icon={Factory}
                        label={isEn ? "Factory Count" : "Jumlah Pabrik"}
                        value={data.factory_count}
                        onChange={(v) => update("factory_count", v)}
                    />
                </div>
            </div>

            {/* =======================================================
            | Market Coverage
            ======================================================= */}

            <div className="mt-10">
                <SectionTitle
                    icon={Globe}
                    title={isEn ? "Market Coverage" : "Cakupan Pasar"}
                />

                <p className="mt-2 text-sm text-slate-500">
                    {isEn
                        ? "Tell buyers where your products are currently sold."
                        : "Beritahu buyer wilayah pemasaran produk perusahaan Anda."}
                </p>

                <div className="mt-6 grid gap-6 md:grid-cols-2">
                    <Input
                        icon={Globe}
                        label={isEn ? "Domestic Market" : "Pasar Domestik"}
                        value={data.domestic_market}
                        onChange={(v) => update("domestic_market", v)}
                    />

                    <Input
                        icon={Globe}
                        label={isEn ? "Export Markets" : "Pasar Ekspor"}
                        value={data.export_markets}
                        onChange={(v) => update("export_markets", v)}
                    />

                    <Input
                        icon={Globe}
                        label={
                            isEn
                                ? "Main Export Countries"
                                : "Negara Tujuan Ekspor"
                        }
                        value={data.export_countries}
                        onChange={(v) => update("export_countries", v)}
                    />

                    <Input
                        icon={Globe}
                        label={
                            isEn
                                ? "Main Customers / Brands"
                                : "Buyer / Brand Utama"
                        }
                        value={data.main_customers}
                        onChange={(v) => update("main_customers", v)}
                    />
                </div>
            </div>

            {/* =======================================================
            | Sustainability
            ======================================================= */}

            <div className="mt-10">
                <SectionTitle
                    icon={Leaf}
                    title={isEn ? "Sustainability" : "Keberlanjutan"}
                />

                <p className="mt-2 text-sm text-slate-500">
                    {isEn
                        ? "Show your company's sustainability commitments."
                        : "Tunjukkan komitmen keberlanjutan perusahaan Anda."}
                </p>

                <div className="mt-6 grid gap-4 md:grid-cols-2">
                    <Checkbox
                        label="Recycled Materials"
                        checked={data.recycled_material}
                        onChange={(v) => update("recycled_material", v)}
                    />

                    <Checkbox
                        label="Renewable Energy"
                        checked={data.renewable_energy}
                        onChange={(v) => update("renewable_energy", v)}
                    />

                    <Checkbox
                        label="Water Treatment"
                        checked={data.water_treatment}
                        onChange={(v) => update("water_treatment", v)}
                    />

                    <Checkbox
                        label="Carbon Reduction Program"
                        checked={data.carbon_reduction}
                        onChange={(v) => update("carbon_reduction", v)}
                    />

                    <Checkbox
                        label="ESG Program"
                        checked={data.esg_program}
                        onChange={(v) => update("esg_program", v)}
                    />

                    <Checkbox
                        label="Circular Economy"
                        checked={data.circular_economy}
                        onChange={(v) => update("circular_economy", v)}
                    />
                </div>
            </div>

            {/* =======================================================
            | Business Intelligence™
            ======================================================= */}

            <div
                className="
                    mt-10
                    rounded-3xl
                    border
                    border-indigo-100
                    bg-gradient-to-r
                    from-indigo-50
                    to-slate-50
                    p-8
                "
            >
                <div className="flex items-center gap-3">
                    <Briefcase className="h-7 w-7 text-indigo-600" />

                    <div>
                        <h3 className="text-xl font-black text-indigo-700">
                            Business Intelligence™
                        </h3>

                        <p className="mt-2 text-sm leading-7 text-slate-600">
                            {isEn
                                ? "Your business information becomes the foundation for Executive Dashboard™, Company Intelligence™, Trade Intelligence™, Smart Business Matching™, Buyer Readiness™, and Build My Supply Chain™."
                                : "Informasi bisnis menjadi fondasi Executive Dashboard™, Company Intelligence™, Trade Intelligence™, Smart Business Matching™, Buyer Readiness™, dan Build My Supply Chain™."}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    );
}

/* -------------------------------------------------------------------------- */
/* Section Title */
/* -------------------------------------------------------------------------- */

function SectionTitle({ icon: Icon, title }) {
    return (
        <div className="flex items-center gap-3">
            <div
                className="
                    flex
                    h-10
                    w-10
                    items-center
                    justify-center
                    rounded-xl
                    bg-indigo-100
                "
            >
                <Icon className="h-5 w-5 text-indigo-600" />
            </div>

            <div>
                <h3 className="text-lg font-black text-slate-900">{title}</h3>
            </div>
        </div>
    );
}

/* -------------------------------------------------------------------------- */
/* Input */
/* -------------------------------------------------------------------------- */

function Input({
    icon: Icon,
    label,
    value,
    onChange,
    type = "text",
    placeholder = "",
}) {
    return (
        <div>
            <label className="block font-semibold text-slate-700">
                {label}
            </label>

            <div className="relative mt-2">
                {Icon && (
                    <Icon
                        className="
                            absolute
                            left-4
                            top-1/2
                            h-5
                            w-5
                            -translate-y-1/2
                            text-slate-400
                        "
                    />
                )}

                <input
                    type={type}
                    value={value ?? ""}
                    placeholder={placeholder}
                    onChange={(e) => onChange(e.target.value)}
                    className="
                        w-full
                        rounded-2xl
                        border
                        border-slate-300
                        bg-white
                        py-3
                        pl-12
                        pr-4
                        transition-all
                        duration-200
                        focus:border-indigo-500
                        focus:outline-none
                        focus:ring-4
                        focus:ring-indigo-100
                    "
                />
            </div>
        </div>
    );
}

/* -------------------------------------------------------------------------- */
/* Checkbox */
/* -------------------------------------------------------------------------- */

function Checkbox({ label, checked, onChange }) {
    return (
        <label
            className="
                flex
                cursor-pointer
                items-center
                gap-3
                rounded-2xl
                border
                border-slate-200
                bg-white
                p-4
                transition-all
                duration-200
                hover:border-indigo-300
                hover:bg-indigo-50
            "
        >
            <input
                type="checkbox"
                checked={!!checked}
                onChange={(e) => onChange(e.target.checked)}
                className="
                    h-5
                    w-5
                    rounded
                    border-slate-300
                    text-indigo-600
                    focus:ring-indigo-500
                "
            />

            <span
                className="
                    text-sm
                    font-medium
                    text-slate-700
                "
            >
                {label}
            </span>
        </label>
    );
}
