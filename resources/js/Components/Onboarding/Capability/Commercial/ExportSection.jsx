import { Globe, Ship, Languages, FileText } from "lucide-react";

import SectionHeader from "../Shared/SectionHeader";
import NumberInput from "../Shared/NumberInput";
import SelectInput from "../Shared/SelectInput";
import CheckboxCard from "../Shared/CheckboxCard";
import StatusBadge from "../Shared/StatusBadge";

const INCOTERMS = [
    { value: "EXW", label: "EXW" },
    { value: "FCA", label: "FCA" },
    { value: "FOB", label: "FOB" },
    { value: "CFR", label: "CFR" },
    { value: "CIF", label: "CIF" },
    { value: "CPT", label: "CPT" },
    { value: "CIP", label: "CIP" },
    { value: "DAP", label: "DAP" },
    { value: "DPU", label: "DPU" },
    { value: "DDP", label: "DDP" },
];

const SHIPPING_METHODS = [
    { value: "Sea Freight", label: "Sea Freight" },
    { value: "Air Freight", label: "Air Freight" },
    { value: "Courier", label: "Courier" },
    { value: "Multimodal", label: "Multimodal" },
];

export default function ExportSection({ data, setData, isEn = true }) {
    const update = (field, value) => {
        setData(field, value);
    };

    const exportScore = [
        data.export_documentation,
        data.certificate_of_origin,
        data.customs_support,
        data.international_shipping,
        data.multilingual_sales,
    ].filter(Boolean).length;

    const exportLevel =
        exportScore >= 5
            ? "Excellent"
            : exportScore >= 4
              ? "Advanced"
              : exportScore >= 2
                ? "Standard"
                : "Basic";

    return (
        <section className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <SectionHeader
                icon={Globe}
                title="Export Capability™"
                description={
                    isEn
                        ? "Describe your international trade capability, export documentation, logistics readiness, and buyer communication."
                        : "Jelaskan kemampuan ekspor, dokumentasi perdagangan, kesiapan logistik, dan komunikasi dengan buyer internasional."
                }
            />

            {/* Basic Export Information */}

            <div className="mt-6 grid gap-6 md:grid-cols-2">
                <div>
                    <label className="mb-2 block text-sm font-semibold text-slate-700">
                        {isEn ? "Export Countries" : "Negara Tujuan Ekspor"}
                    </label>

                    <input
                        type="text"
                        value={data.export_countries ?? ""}
                        onChange={(e) =>
                            update("export_countries", e.target.value)
                        }
                        placeholder={
                            isEn
                                ? "USA, Japan, Germany..."
                                : "Amerika, Jepang, Jerman..."
                        }
                        className="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-indigo-500 focus:outline-none"
                    />
                </div>

                <NumberInput
                    icon={Ship}
                    label={
                        isEn
                            ? "Export Experience (Years)"
                            : "Pengalaman Ekspor (Tahun)"
                    }
                    value={data.export_experience}
                    onChange={(v) => update("export_experience", v)}
                    placeholder="15"
                />

                <SelectInput
                    icon={FileText}
                    label={isEn ? "Preferred Incoterm" : "Incoterm Utama"}
                    value={data.incoterm}
                    onChange={(v) => update("incoterm", v)}
                    options={INCOTERMS}
                    placeholder={isEn ? "Select Incoterm" : "Pilih Incoterm"}
                />

                <SelectInput
                    icon={Ship}
                    label={isEn ? "Shipping Method" : "Metode Pengiriman"}
                    value={data.shipping_method}
                    onChange={(v) => update("shipping_method", v)}
                    options={SHIPPING_METHODS}
                    placeholder={isEn ? "Select Shipping" : "Pilih Pengiriman"}
                />

                <div className="md:col-span-2">
                    <label className="mb-2 block text-sm font-semibold text-slate-700">
                        {isEn ? "Business Languages" : "Bahasa Bisnis"}
                    </label>

                    <input
                        type="text"
                        value={data.business_languages ?? ""}
                        onChange={(e) =>
                            update("business_languages", e.target.value)
                        }
                        placeholder={
                            isEn
                                ? "English, Japanese, Mandarin..."
                                : "Indonesia, Inggris, Jepang..."
                        }
                        className="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-indigo-500 focus:outline-none"
                    />
                </div>
            </div>

            {/* Export Services */}

            <div className="mt-8 grid gap-4 md:grid-cols-2">
                <CheckboxCard
                    title="Export Documentation"
                    checked={data.export_documentation}
                    onChange={(v) => update("export_documentation", v)}
                />

                <CheckboxCard
                    title="Certificate of Origin"
                    checked={data.certificate_of_origin}
                    onChange={(v) => update("certificate_of_origin", v)}
                />

                <CheckboxCard
                    title="Customs Support"
                    checked={data.customs_support}
                    onChange={(v) => update("customs_support", v)}
                />

                <CheckboxCard
                    title="International Shipping"
                    checked={data.international_shipping}
                    onChange={(v) => update("international_shipping", v)}
                />

                <CheckboxCard
                    title="Multilingual Sales Team"
                    checked={data.multilingual_sales}
                    onChange={(v) => update("multilingual_sales", v)}
                />
            </div>

            {/* Export Intelligence */}

            <div className="mt-10 rounded-2xl border border-emerald-100 bg-emerald-50 p-6">
                <div className="flex flex-wrap items-center justify-between gap-6">
                    <div>
                        <h3 className="text-lg font-black text-emerald-700">
                            Export Intelligence™
                        </h3>

                        <p className="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                            {isEn
                                ? "Export capability strengthens your visibility in Smart Business Matching™, Buyer Readiness™, and Global Company Directory."
                                : "Kapabilitas ekspor meningkatkan visibilitas perusahaan dalam Smart Business Matching™, Buyer Readiness™, dan Global Company Directory."}
                        </p>
                    </div>

                    <div className="text-center">
                        <div className="text-4xl font-black text-emerald-600">
                            {exportScore}/5
                        </div>

                        <div className="mt-2">
                            <StatusBadge color="emerald">
                                {exportLevel}
                            </StatusBadge>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}
