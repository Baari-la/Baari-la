import { BriefcaseBusiness } from "lucide-react";

import SectionHeader from "../Shared/SectionHeader";
import CheckboxCard from "../Shared/CheckboxCard";
import StatusBadge from "../Shared/StatusBadge";

export default function BusinessModelSection({ data, setData, isEn = true }) {
    const update = (field, value) => {
        setData(field, value);
    };

    const selectedModels = [
        data.oem && "OEM",
        data.odm && "ODM",
        data.obm && "OBM",
        data.private_label && "Private Label",
    ].filter(Boolean);

    return (
        <section className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <SectionHeader
                icon={BriefcaseBusiness}
                title={isEn ? "Business Model™" : "Model Bisnis™"}
                description={
                    isEn
                        ? "Select the business models your company supports. This information helps buyers understand how they can work with your company."
                        : "Pilih model bisnis yang didukung perusahaan. Informasi ini membantu buyer memahami bagaimana mereka dapat bekerja sama dengan perusahaan Anda."
                }
            />

            <div className="grid gap-4 md:grid-cols-2">
                <CheckboxCard
                    title="OEM Manufacturing"
                    description={
                        isEn
                            ? "Manufacture products according to buyer specifications."
                            : "Memproduksi berdasarkan spesifikasi pelanggan."
                    }
                    checked={data.oem}
                    onChange={(v) => update("oem", v)}
                />

                <CheckboxCard
                    title="ODM Manufacturing"
                    description={
                        isEn
                            ? "Provide product design and manufacturing."
                            : "Menyediakan desain produk sekaligus manufaktur."
                    }
                    checked={data.odm}
                    onChange={(v) => update("odm", v)}
                />

                <CheckboxCard
                    title="Own Brand (OBM)"
                    description={
                        isEn
                            ? "Develop and market products under your own brand."
                            : "Mengembangkan dan memasarkan produk dengan merek sendiri."
                    }
                    checked={data.obm}
                    onChange={(v) => update("obm", v)}
                />

                <CheckboxCard
                    title="Private Label"
                    description={
                        isEn
                            ? "Produce products using customer-owned branding."
                            : "Memproduksi produk dengan merek milik pelanggan."
                    }
                    checked={data.private_label}
                    onChange={(v) => update("private_label", v)}
                />
            </div>

            {/* Business Model Intelligence */}

            <div className="mt-10 rounded-2xl border border-emerald-100 bg-emerald-50 p-6">
                <div className="flex flex-wrap items-start justify-between gap-6">
                    <div>
                        <h3 className="text-lg font-black text-emerald-700">
                            Business Model Intelligence™
                        </h3>

                        <p className="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                            {isEn
                                ? "Your selected business models help buyers, sourcing teams, and Smart Business Matching™ understand how your company collaborates with partners."
                                : "Model bisnis yang dipilih membantu buyer, tim sourcing, dan Smart Business Matching™ memahami pola kerja sama yang didukung perusahaan."}
                        </p>
                    </div>

                    <div className="flex flex-wrap gap-2">
                        {selectedModels.length > 0 ? (
                            selectedModels.map((model) => (
                                <StatusBadge key={model} color="emerald">
                                    {model}
                                </StatusBadge>
                            ))
                        ) : (
                            <StatusBadge color="slate">
                                {isEn
                                    ? "No Business Model Selected"
                                    : "Belum Dipilih"}
                            </StatusBadge>
                        )}
                    </div>
                </div>
            </div>
        </section>
    );
}
