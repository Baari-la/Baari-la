import { usePage } from "@inertiajs/react";

import { Factory, Package, Truck, Wrench, Layers, Zap } from "lucide-react";

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

export default function CapabilityCard({ data, setData }) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const update = (field, value) => {
        setData({
            ...data,
            [field]: value,
        });
    };
    /*
|--------------------------------------------------------------------------
| Capacity Intelligence™
|--------------------------------------------------------------------------
*/

    const installedCapacity = Number(data.production_capacity || 0);

    const utilizedCapacity = Number(data.current_utilized_capacity || 0);

    const availableCapacity = Math.max(installedCapacity - utilizedCapacity, 0);

    const utilizationRate =
        installedCapacity > 0
            ? Math.round((utilizedCapacity / installedCapacity) * 100)
            : 0;

    const factoryStatus =
        utilizationRate <= 60
            ? {
                  label: isEn
                      ? "Available Capacity"
                      : "Kapasitas Masih Tersedia",
                  color: "text-emerald-600",
              }
            : utilizationRate <= 85
              ? {
                    label: isEn ? "Moderate Utilization" : "Utilisasi Sedang",
                    color: "text-amber-600",
                }
              : {
                    label: isEn
                        ? "Nearly Full Capacity"
                        : "Kapasitas Hampir Penuh",
                    color: "text-red-600",
                };

    return (
        <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            {/* =======================================================
            | Header
            ======================================================= */}

            <div className="mb-10">
                <h2 className="text-3xl font-black text-slate-900">
                    {isEn ? "Capability Profile" : "Profil Kapabilitas"}
                </h2>

                <p className="mt-3 max-w-3xl leading-7 text-slate-600">
                    {isEn
                        ? "Showcase your production capacity, manufacturing expertise, commercial capability, and operational strengths."
                        : "Tampilkan kapasitas produksi, keahlian manufaktur, kemampuan komersial, dan keunggulan operasional perusahaan."}
                </p>
            </div>
            {/* =======================================================
| Capacity Intelligence™
======================================================= */}

            <div>
                <SectionTitle icon={Factory} title="Capacity Intelligence™" />

                <p className="mt-2 text-sm text-slate-500">
                    {isEn
                        ? "Provide your installed production capacity and current production utilization to help buyers understand your manufacturing availability."
                        : "Masukkan kapasitas terpasang dan kapasitas produksi yang sedang digunakan agar buyer memahami ketersediaan kapasitas manufaktur perusahaan Anda."}
                </p>

                {/* =======================================================
    | Installed Capacity
    ======================================================= */}

                <div className="mt-8">
                    {/* <h4 className="mb-4 font-bold text-slate-700">
                        {isEn ? "Installed Capacity" : "Kapasitas Terpasang"}
                    </h4> */}

                    <div className="grid gap-6 md:grid-cols-2">
                        <Input
                            icon={Factory}
                            label={
                                isEn
                                    ? "Installed Capacity"
                                    : "Kapasitas Terpasang"
                            }
                            value={data.production_capacity}
                            onChange={(v) => update("production_capacity", v)}
                            placeholder={
                                isEn ? "Example: 12,000" : "Contoh: 12.000"
                            }
                        />

                        <Select
                            icon={Layers}
                            label={isEn ? "Unit" : "Satuan"}
                            value={data.capacity_unit}
                            onChange={(v) => update("capacity_unit", v)}
                            options={UNIT_OPTIONS}
                            placeholder={isEn ? "Select Unit" : "Pilih Satuan"}
                        />
                    </div>
                </div>

                {/* =======================================================
    | Current Utilized Capacity
    ======================================================= */}

                <div className="mt-8">
                    {/* <h4 className="mb-4 font-bold text-slate-700">
                        {isEn
                            ? "Current Utilized Capacity"
                            : "Kapasitas Terpakai Saat Ini"}
                    </h4> */}

                    <div className="grid gap-6 md:grid-cols-2">
                        <Input
                            icon={Factory}
                            label={
                                isEn
                                    ? "Current Utilized Capacity"
                                    : "Kapasitas Terpakai"
                            }
                            value={data.current_utilized_capacity}
                            onChange={(v) =>
                                update("current_utilized_capacity", v)
                            }
                            placeholder={
                                isEn ? "Example: 8,500" : "Contoh: 8.500"
                            }
                        />

                        <Select
                            icon={Layers}
                            label={isEn ? "Unit" : "Satuan"}
                            value={data.current_utilized_capacity_unit}
                            onChange={(v) =>
                                update("current_utilized_capacity_unit", v)
                            }
                            options={UNIT_OPTIONS}
                            placeholder={isEn ? "Select Unit" : "Pilih Satuan"}
                        />
                    </div>
                </div>

                {/* =======================================================
    | Monthly & Annual Capacity
    ======================================================= */}

                <div className="mt-8">
                    <h4 className="mb-4 font-bold text-slate-700">
                        {isEn ? "Production Output" : "Output Produksi"}
                    </h4>

                    <div className="grid gap-6 md:grid-cols-2">
                        <Input
                            icon={Package}
                            label={
                                isEn ? "Monthly Capacity" : "Kapasitas Bulanan"
                            }
                            value={data.monthly_capacity}
                            onChange={(v) => update("monthly_capacity", v)}
                            placeholder={isEn ? "Per Month" : "Per Bulan"}
                        />

                        <Input
                            icon={Package}
                            label={
                                isEn ? "Annual Capacity" : "Kapasitas Tahunan"
                            }
                            value={data.annual_capacity}
                            onChange={(v) => update("annual_capacity", v)}
                            placeholder={isEn ? "Per Year" : "Per Tahun"}
                        />
                    </div>
                </div>
            </div>
            {/* =======================================================
            | Commercial Capability
            ======================================================= */}

            <div className="mt-12">
                <SectionTitle
                    icon={Truck}
                    title={
                        isEn ? "Commercial Capability" : "Kapabilitas Komersial"
                    }
                />

                <p className="mt-2 text-sm text-slate-500">
                    {isEn
                        ? "Provide information about your commercial production capability."
                        : "Lengkapi informasi mengenai kemampuan produksi komersial perusahaan."}
                </p>

                <div className="mt-6 grid gap-6 md:grid-cols-2">
                    <Input
                        icon={Package}
                        label="Minimum Order Quantity (MOQ)"
                        value={data.moq}
                        onChange={(v) => update("moq", v)}
                        placeholder="500"
                    />

                    <Select
                        icon={Layers}
                        label={isEn ? "MOQ Unit" : "Satuan MOQ"}
                        value={data.moq_unit}
                        onChange={(v) => update("moq_unit", v)}
                        options={UNIT_OPTIONS}
                        placeholder={isEn ? "Select Unit" : "Pilih Satuan"}
                    />

                    <Input
                        icon={Truck}
                        label={isEn ? "Lead Time (Days)" : "Lead Time (Hari)"}
                        value={data.lead_time}
                        onChange={(v) => update("lead_time", v)}
                        placeholder="30"
                    />
                </div>
            </div>

            {/* =======================================================
            | Manufacturing Services
            ======================================================= */}

            <div className="mt-12">
                <SectionTitle
                    icon={Wrench}
                    title={
                        isEn ? "Manufacturing Services" : "Layanan Manufaktur"
                    }
                />

                <p className="mt-2 text-sm text-slate-500">
                    {isEn
                        ? "Select the manufacturing services your company provides."
                        : "Pilih layanan manufaktur yang disediakan perusahaan Anda."}
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
                        label="Private Label"
                        checked={data.private_label}
                        onChange={(v) => update("private_label", v)}
                    />

                    <Checkbox
                        label="Full Package Production"
                        checked={data.full_package}
                        onChange={(v) => update("full_package", v)}
                    />

                    <Checkbox
                        label="CMT Manufacturing"
                        checked={data.cmt}
                        onChange={(v) => update("cmt", v)}
                    />

                    <Checkbox
                        label="Design Support"
                        checked={data.design_support}
                        onChange={(v) => update("design_support", v)}
                    />
                </div>
            </div>

            {/* =======================================================
            | Service Capability
            ======================================================= */}

            <div className="mt-12">
                <SectionTitle
                    icon={Package}
                    title={isEn ? "Service Capability" : "Kapabilitas Layanan"}
                />

                <p className="mt-2 text-sm text-slate-500">
                    {isEn
                        ? "Show buyers how your company supports production and sourcing."
                        : "Tunjukkan kepada buyer bagaimana perusahaan Anda mendukung proses produksi dan sourcing."}
                </p>

                <div className="mt-6 grid gap-4 md:grid-cols-2">
                    <Checkbox
                        label="Export Ready"
                        checked={data.export_ready}
                        onChange={(v) => update("export_ready", v)}
                    />

                    <Checkbox
                        label="Sampling Service"
                        checked={data.sampling_service}
                        onChange={(v) => update("sampling_service", v)}
                    />

                    <Checkbox
                        label="Production Flexibility"
                        checked={data.production_flexibility}
                        onChange={(v) => update("production_flexibility", v)}
                    />
                </div>
            </div>

            {/* =======================================================
            | Production Flexibility
            ======================================================= */}

            <div className="mt-12">
                <SectionTitle
                    icon={Zap}
                    title={
                        isEn
                            ? "Production Flexibility"
                            : "Fleksibilitas Produksi"
                    }
                />

                <p className="mt-2 text-sm text-slate-500">
                    {isEn
                        ? "Highlight your ability to respond quickly to buyer requirements."
                        : "Tampilkan kemampuan perusahaan dalam merespons kebutuhan buyer dengan cepat."}
                </p>

                <div className="mt-6 grid gap-4 md:grid-cols-2">
                    <Checkbox
                        label="Small Batch Production"
                        checked={data.small_batch}
                        onChange={(v) => update("small_batch", v)}
                    />

                    <Checkbox
                        label="Fast Sampling"
                        checked={data.fast_sampling}
                        onChange={(v) => update("fast_sampling", v)}
                    />

                    <Checkbox
                        label="Quick Response Manufacturing"
                        checked={data.quick_response}
                        onChange={(v) => update("quick_response", v)}
                    />

                    <Checkbox
                        label="Custom Product Development"
                        checked={data.custom_product_development}
                        onChange={(v) =>
                            update("custom_product_development", v)
                        }
                    />
                </div>
            </div>

            {/* =======================================================
            | Capability Intelligence™
            ======================================================= */}

            <div
                className="
                    mt-12
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
                    <Factory className="h-7 w-7 text-indigo-600" />

                    <div>
                        <h3 className="text-xl font-black text-indigo-700">
                            Capability Intelligence™
                        </h3>

                        <p className="mt-2 text-sm leading-7 text-slate-600">
                            {isEn
                                ? "Your manufacturing capabilities help buyers, sourcing teams, brands, and AI-powered Smart Business Matching™ understand what your company can produce, how you operate, and which business opportunities best match your strengths."
                                : "Kapabilitas manufaktur membantu buyer, sourcing team, brand, dan Smart Business Matching™ memahami kemampuan produksi perusahaan, cara perusahaan beroperasi, serta peluang bisnis yang paling sesuai."}
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
/* Select Dropdown Component */
/* -------------------------------------------------------------------------- */

function Select({
    icon: Icon,
    label,
    value,
    onChange,
    options = [],
    placeholder = "Pilih",
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
                            pointer-events-none
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

                <select
                    value={value ?? ""}
                    onChange={(e) => onChange(e.target.value)}
                    className="
                        w-full
                        appearance-none
                        rounded-2xl
                        border
                        border-slate-300
                        bg-white
                        py-3
                        pl-12
                        pr-10
                        transition-all
                        duration-200
                        focus:border-indigo-500
                        focus:outline-none
                        focus:ring-4
                        focus:ring-indigo-100
                    "
                >
                    <option value="">{placeholder}</option>
                    {options.map((opt) => (
                        <option key={opt.value} value={opt.value}>
                            {opt.label}
                        </option>
                    ))}
                </select>

                {/* Custom Dropdown Arrow */}
                <div className="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg
                        className="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            strokeLinecap="round"
                            strokeLinejoin="round"
                            strokeWidth="2"
                            d="M19 9l-7 7-7-7"
                        />
                    </svg>
                </div>
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
