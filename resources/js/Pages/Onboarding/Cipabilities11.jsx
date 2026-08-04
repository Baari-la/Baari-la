import OnboardingLayout from "@/Layouts/OnboardingLayout";
import OnboardingNavbar from "@/Components/Onboarding/OnboardingNavbar";

import { Head, useForm, usePage } from "@inertiajs/react";

import { Factory, Clock, Package, CheckCircle, ArrowRight } from "lucide-react";

export default function Capabilities() {
    const page = usePage();
    const { locale, company } = page.props;
    const isEn = locale === "en";

    // Opsi Unit Bilingual
    const unitOptions = isEn
        ? [
              { value: "pcs", label: "Pieces (Pcs)" },
              { value: "meters", label: "Meters (m)" },
              { value: "yards", label: "Yards (yd)" },
              { value: "kg", label: "Kilograms (kg)" },
              { value: "tons", label: "Tons" },
              { value: "rolls", label: "Rolls" },
              { value: "sets", label: "Sets" },
          ]
        : [
              { value: "pcs", label: "Buah / Pcs" },
              { value: "meters", label: "Meter (m)" },
              { value: "yards", label: "Yard (yd)" },
              { value: "kg", label: "Kilogram (kg)" },
              { value: "tons", label: "Ton" },
              { value: "rolls", label: "Rol / Roll" },
              { value: "sets", label: "Set" },
          ];

    const { data, setData, post, processing } = useForm({
        // Production Capacity
        production_capacity: company?.production_capacity ?? "",
        production_capacity_unit: company?.production_capacity_unit ?? "",
        monthly_capacity: company?.monthly_capacity ?? "",
        annual_capacity: company?.annual_capacity ?? "",

        // Commercial
        minimum_order_quantity: company?.minimum_order_quantity ?? "",
        minimum_order_unit: company?.minimum_order_unit ?? "",
        lead_time_days: company?.lead_time_days ?? "",
        sampling_service: company?.sampling_service ?? false,
        export_ready: company?.export_ready ?? false,

        // Manufacturing Services
        supports_oem: company?.supports_oem ?? false,
        supports_odm: company?.supports_odm ?? false,
        supports_private_label: company?.supports_private_label ?? false,
        supports_full_package: company?.supports_full_package ?? false,
        supports_cmt: company?.supports_cmt ?? false,
        supports_design_support: company?.supports_design_support ?? false,

        // Production Flexibility
        supports_small_batch: company?.supports_small_batch ?? false,
        supports_fast_sampling: company?.supports_fast_sampling ?? false,
        supports_quick_response: company?.supports_quick_response ?? false,
        supports_custom_development:
            company?.supports_custom_development ?? false,
    });

    const submit = (e) => {
        e.preventDefault();
        post(route("onboarding.capabilities.store"));
    };

    return (
        <OnboardingLayout>
            <Head title="Capabilities" />

            <div className="min-h-screen bg-slate-50">
                <OnboardingNavbar currentStep={3} />

                <div className="mx-auto max-w-6xl px-6 py-12">
                    <div className="rounded-3xl bg-white p-10 shadow-sm">
                        <div className="text-center">
                            <p className="text-sm font-black uppercase tracking-[0.2em] text-emerald-600">
                                STEP 3
                            </p>
                            <h1 className="mt-4 text-5xl font-black text-slate-900">
                                {isEn ? "Capabilities" : "Kapabilitas"}
                            </h1>
                            <p className="mx-auto mt-4 max-w-3xl leading-7 text-slate-500">
                                {isEn
                                    ? "Build your Capability Profile to showcase your production capacity, manufacturing expertise, commercial flexibility, and operational strengths across the global textile industry ecosystem"
                                    : "Bangun Capability Profile perusahaan Anda untuk menampilkan kapasitas produksi, keahlian manufaktur, fleksibilitas komersial, dan keunggulan operasional dalam ekosistem industri tekstil global"}
                            </p>
                        </div>

                        <form onSubmit={submit} className="mt-10 space-y-6">
                            {/* Section 1: Production Capacity */}
                            <div className="mt-10">
                                <h2 className="text-xl font-black text-slate-900">
                                    {isEn
                                        ? "Production Capacity"
                                        : "Kapasitas Produksi"}
                                </h2>
                                <p className="mt-1 text-sm text-slate-500">
                                    {isEn
                                        ? "Describe your manufacturing capacity and production scale."
                                        : "Jelaskan kapasitas manufaktur dan skala produksi perusahaan Anda."}
                                </p>

                                <div className="mt-6 grid gap-6 md:grid-cols-2">
                                    <Input
                                        icon={Factory}
                                        type="number"
                                        min="0"
                                        label={
                                            isEn
                                                ? "Production Capacity"
                                                : "Kapasitas Produksi"
                                        }
                                        value={data.production_capacity}
                                        onChange={(e) =>
                                            setData(
                                                "production_capacity",
                                                e.target.value,
                                            )
                                        }
                                    />
                                    <Select
                                        label={
                                            isEn
                                                ? "Capacity Unit"
                                                : "Satuan Kapasitas"
                                        }
                                        value={data.production_capacity_unit}
                                        onChange={(e) =>
                                            setData(
                                                "production_capacity_unit",
                                                e.target.value,
                                            )
                                        }
                                        options={unitOptions}
                                        placeholder={
                                            isEn
                                                ? "-- Select Unit --"
                                                : "-- Pilih Satuan --"
                                        }
                                    />
                                    <Input
                                        icon={Factory}
                                        type="number"
                                        min="0"
                                        label={
                                            isEn
                                                ? "Monthly Capacity"
                                                : "Kapasitas Bulanan"
                                        }
                                        value={data.monthly_capacity}
                                        onChange={(e) =>
                                            setData(
                                                "monthly_capacity",
                                                e.target.value,
                                            )
                                        }
                                    />
                                    <Input
                                        icon={Factory}
                                        type="number"
                                        min="0"
                                        label={
                                            isEn
                                                ? "Annual Capacity"
                                                : "Kapasitas Tahunan"
                                        }
                                        value={data.annual_capacity}
                                        onChange={(e) =>
                                            setData(
                                                "annual_capacity",
                                                e.target.value,
                                            )
                                        }
                                    />
                                </div>
                            </div>

                            {/* Section 2: Commercial Capability */}
                            <div className="mt-12">
                                <h2 className="text-xl font-black text-slate-900">
                                    {isEn
                                        ? "Commercial Capability"
                                        : "Kapabilitas Komersial"}
                                </h2>
                                <p className="mt-1 text-sm text-slate-500">
                                    {isEn
                                        ? "Provide information about your commercial production capability."
                                        : "Berikan informasi mengenai kemampuan produksi komersial perusahaan."}
                                </p>

                                <div className="mt-6 grid gap-6 md:grid-cols-2">
                                    <Input
                                        icon={Package}
                                        type="number"
                                        min="0"
                                        label={
                                            isEn
                                                ? "Minimum Order Quantity (MOQ)"
                                                : "Minimum Jumlah Pesanan (MOQ)"
                                        }
                                        value={data.minimum_order_quantity}
                                        onChange={(e) =>
                                            setData(
                                                "minimum_order_quantity",
                                                e.target.value,
                                            )
                                        }
                                    />
                                    <Select
                                        label={isEn ? "MOQ Unit" : "Satuan MOQ"}
                                        value={data.minimum_order_unit}
                                        onChange={(e) =>
                                            setData(
                                                "minimum_order_unit",
                                                e.target.value,
                                            )
                                        }
                                        options={unitOptions}
                                        placeholder={
                                            isEn
                                                ? "-- Select Unit --"
                                                : "-- Pilih Satuan --"
                                        }
                                    />
                                    <Input
                                        icon={Clock}
                                        type="number"
                                        min="0"
                                        label={
                                            isEn
                                                ? "Lead Time (Days)"
                                                : "Lead Time (Hari)"
                                        }
                                        value={data.lead_time_days}
                                        onChange={(e) =>
                                            setData(
                                                "lead_time_days",
                                                e.target.value,
                                            )
                                        }
                                    />
                                </div>
                            </div>

                            {/* Section 3: Manufacturing Services */}
                            <div className="mt-12">
                                <h2 className="text-xl font-black text-slate-900">
                                    {isEn
                                        ? "Manufacturing Services"
                                        : "Layanan Manufaktur"}
                                </h2>
                                <p className="mt-1 text-sm text-slate-500">
                                    {isEn
                                        ? "Select the manufacturing services your company provides."
                                        : "Pilih layanan manufaktur yang disediakan perusahaan Anda."}
                                </p>

                                <div className="mt-6 grid gap-4 md:grid-cols-2">
                                    <Checkbox
                                        label={
                                            isEn
                                                ? "OEM Manufacturing"
                                                : "Manufaktur OEM"
                                        }
                                        checked={data.supports_oem}
                                        onChange={(checked) =>
                                            setData("supports_oem", checked)
                                        }
                                    />
                                    <Checkbox
                                        label={
                                            isEn
                                                ? "ODM Manufacturing"
                                                : "Manufaktur ODM"
                                        }
                                        checked={data.supports_odm}
                                        onChange={(checked) =>
                                            setData("supports_odm", checked)
                                        }
                                    />
                                    <Checkbox
                                        label={
                                            isEn
                                                ? "Private Label"
                                                : "Private Label"
                                        }
                                        checked={data.supports_private_label}
                                        onChange={(checked) =>
                                            setData(
                                                "supports_private_label",
                                                checked,
                                            )
                                        }
                                    />
                                    <Checkbox
                                        label={
                                            isEn
                                                ? "Full Package Production"
                                                : "Produksi Full Package"
                                        }
                                        checked={data.supports_full_package}
                                        onChange={(checked) =>
                                            setData(
                                                "supports_full_package",
                                                checked,
                                            )
                                        }
                                    />
                                    <Checkbox
                                        label={
                                            isEn
                                                ? "CMT Manufacturing"
                                                : "Produksi CMT"
                                        }
                                        checked={data.supports_cmt}
                                        onChange={(checked) =>
                                            setData("supports_cmt", checked)
                                        }
                                    />
                                    <Checkbox
                                        label={
                                            isEn
                                                ? "Design Support"
                                                : "Dukungan Desain"
                                        }
                                        checked={data.supports_design_support}
                                        onChange={(checked) =>
                                            setData(
                                                "supports_design_support",
                                                checked,
                                            )
                                        }
                                    />
                                </div>
                            </div>

                            {/* Section 4: Service Capability */}
                            <div className="mt-12">
                                <h2 className="text-xl font-black text-slate-900">
                                    {isEn
                                        ? "Service Capability"
                                        : "Kapabilitas Layanan"}
                                </h2>

                                <div className="mt-6 grid gap-4 md:grid-cols-2">
                                    <Checkbox
                                        label={
                                            isEn
                                                ? "Export Ready"
                                                : "Siap Ekspor"
                                        }
                                        checked={data.export_ready}
                                        onChange={(checked) =>
                                            setData("export_ready", checked)
                                        }
                                    />
                                    <Checkbox
                                        label={
                                            isEn
                                                ? "Sampling Service"
                                                : "Layanan Sampel"
                                        }
                                        checked={data.sampling_service}
                                        onChange={(checked) =>
                                            setData("sampling_service", checked)
                                        }
                                    />
                                </div>
                            </div>

                            {/* Section 5: Production Flexibility */}
                            <div className="mt-12">
                                <h2 className="text-xl font-black text-slate-900">
                                    {isEn
                                        ? "Production Flexibility"
                                        : "Fleksibilitas Produksi"}
                                </h2>

                                <div className="mt-6 grid gap-4 md:grid-cols-2">
                                    <Checkbox
                                        label={
                                            isEn
                                                ? "Small Batch Production"
                                                : "Produksi Skala Kecil"
                                        }
                                        checked={data.supports_small_batch}
                                        onChange={(checked) =>
                                            setData(
                                                "supports_small_batch",
                                                checked,
                                            )
                                        }
                                    />
                                    <Checkbox
                                        label={
                                            isEn
                                                ? "Fast Sampling"
                                                : "Pembuatan Sampel Cepat"
                                        }
                                        checked={data.supports_fast_sampling}
                                        onChange={(checked) =>
                                            setData(
                                                "supports_fast_sampling",
                                                checked,
                                            )
                                        }
                                    />
                                    <Checkbox
                                        label={
                                            isEn
                                                ? "Quick Response Manufacturing"
                                                : "Produksi Respons Cepat"
                                        }
                                        checked={data.supports_quick_response}
                                        onChange={(checked) =>
                                            setData(
                                                "supports_quick_response",
                                                checked,
                                            )
                                        }
                                    />
                                    <Checkbox
                                        label={
                                            isEn
                                                ? "Custom Product Development"
                                                : "Pengembangan Produk Kustom"
                                        }
                                        checked={
                                            data.supports_custom_development
                                        }
                                        onChange={(checked) =>
                                            setData(
                                                "supports_custom_development",
                                                checked,
                                            )
                                        }
                                    />
                                </div>
                            </div>

                            {/* Information Banner */}
                            <div className="mt-10 rounded-3xl bg-emerald-50 p-6">
                                <div className="flex items-start gap-4">
                                    <CheckCircle className="mt-1 h-6 w-6 flex-shrink-0 text-emerald-600" />
                                    <div>
                                        <h3 className="font-black text-slate-900">
                                            Digital Company Passport™
                                        </h3>
                                        <p className="mt-2 text-sm leading-7 text-slate-600">
                                            {isEn
                                                ? "Your manufacturing capabilities help buyers, sourcing teams, brands, and AI-powered Smart Business Matching™ understand what your company can produce, how you operate, and which business opportunities best match your strengths."
                                                : "Kapabilitas manufaktur perusahaan Anda membantu pembeli, tim sourcing, brand, dan AI Smart Business Matching™ memahami apa yang dapat diproduksi perusahaan Anda, bagaimana Anda beroperasi, serta peluang bisnis yang paling sesuai dengan keunggulan perusahaan."}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {/* Submit Button */}
                            <div className="flex justify-end">
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="inline-flex cursor-pointer items-center gap-2 rounded-2xl bg-emerald-600 px-8 py-4 font-black text-white transition hover:bg-emerald-700 disabled:opacity-50"
                                >
                                    {isEn ? "CONTINUE" : "LANJUTKAN"}
                                    <ArrowRight className="h-5 w-5" />
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </OnboardingLayout>
    );
}

function Input({
    icon: Icon,
    label,
    value,
    onChange,
    type = "text",
    min,
    placeholder = "",
}) {
    return (
        <div>
            <label className="font-semibold text-slate-700">{label}</label>
            <div className="relative mt-2">
                {Icon && (
                    <Icon className="absolute left-3 top-3.5 h-5 w-5 text-slate-400" />
                )}
                <input
                    type={type}
                    min={min}
                    value={value}
                    placeholder={placeholder}
                    onChange={onChange}
                    className="w-full rounded-xl border border-slate-300 py-3 pl-11 pr-4 focus:border-emerald-500 focus:ring-emerald-500"
                />
            </div>
        </div>
    );
}

function Select({ label, value, onChange, options = [], placeholder = "" }) {
    return (
        <div>
            <label className="font-semibold text-slate-700">{label}</label>
            <select
                value={value}
                onChange={onChange}
                className="mt-2 w-full rounded-xl border border-slate-300 bg-white p-3 focus:border-emerald-500 focus:ring-emerald-500"
            >
                <option value="">{placeholder || `-- ${label} --`}</option>
                {options.map((option) => (
                    <option key={option.value} value={option.value}>
                        {option.label}
                    </option>
                ))}
            </select>
        </div>
    );
}

function Checkbox({ label, checked, onChange }) {
    return (
        <label className="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 p-4 transition hover:bg-slate-50">
            <input
                type="checkbox"
                checked={checked}
                onChange={(e) => onChange(e.target.checked)}
                className="h-5 w-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
            />
            <span className="font-medium text-slate-700">{label}</span>
        </label>
    );
}
