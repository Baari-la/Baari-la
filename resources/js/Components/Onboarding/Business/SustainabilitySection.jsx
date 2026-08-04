import { Leaf, Recycle, Droplets, Trees } from "lucide-react";

import SectionHeader from "../Shared/SectionHeader";
import CheckboxCard from "../Shared/CheckboxCard";
import StatusBadge from "../Shared/StatusBadge";

export default function SustainabilitySection({ data, setData, isEn = true }) {
    const update = (field, value) => {
        setData(field, value);
    };

    const score = [
        data.esg_program,
        data.renewable_energy,
        data.recycled_material,
        data.wastewater_treatment,
    ].filter(Boolean).length;

    const level =
        score >= 4
            ? "Excellent"
            : score >= 3
              ? "Advanced"
              : score >= 2
                ? "Developing"
                : score >= 1
                  ? "Basic"
                  : "Not Available";

    const badgeColor =
        score >= 4
            ? "emerald"
            : score >= 3
              ? "green"
              : score >= 2
                ? "amber"
                : score >= 1
                  ? "orange"
                  : "slate";

    return (
        <section className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <SectionHeader
                icon={Leaf}
                title={isEn ? "Sustainability™" : "Keberlanjutan™"}
                description={
                    isEn
                        ? "Share your sustainability initiatives and environmental commitments. This information supports ESG visibility and buyer confidence."
                        : "Bagikan inisiatif keberlanjutan dan komitmen lingkungan perusahaan untuk meningkatkan ESG visibility dan kepercayaan buyer."
                }
            />

            <div className="grid gap-4 md:grid-cols-2">
                <CheckboxCard
                    title="ESG Program"
                    description={
                        isEn
                            ? "Environmental, Social & Governance initiatives."
                            : "Program Environmental, Social & Governance."
                    }
                    checked={data.esg_program}
                    onChange={(v) => update("esg_program", v)}
                />

                <CheckboxCard
                    title="Renewable Energy"
                    description={
                        isEn
                            ? "Use renewable energy sources."
                            : "Menggunakan energi terbarukan."
                    }
                    checked={data.renewable_energy}
                    onChange={(v) => update("renewable_energy", v)}
                />

                <CheckboxCard
                    title="Recycled Material"
                    description={
                        isEn
                            ? "Use recycled raw materials."
                            : "Menggunakan bahan baku daur ulang."
                    }
                    checked={data.recycled_material}
                    onChange={(v) => update("recycled_material", v)}
                />

                <CheckboxCard
                    title="Wastewater Treatment"
                    description={
                        isEn
                            ? "Operate wastewater treatment facilities."
                            : "Memiliki instalasi pengolahan air limbah."
                    }
                    checked={data.wastewater_treatment}
                    onChange={(v) => update("wastewater_treatment", v)}
                />
            </div>

            <div className="mt-8">
                <label className="mb-2 block text-sm font-semibold text-slate-700">
                    {isEn ? "Sustainability Notes" : "Catatan Keberlanjutan"}
                </label>

                <textarea
                    rows={5}
                    value={data.sustainability_notes ?? ""}
                    onChange={(e) =>
                        update("sustainability_notes", e.target.value)
                    }
                    placeholder={
                        isEn
                            ? "Describe your sustainability initiatives, certifications, carbon reduction programs, renewable energy usage, wastewater management, recycling programs, etc."
                            : "Jelaskan program keberlanjutan, sertifikasi, pengurangan emisi karbon, penggunaan energi terbarukan, pengolahan limbah, program daur ulang, dan sebagainya."
                    }
                    className="
                        w-full
                        rounded-2xl
                        border
                        border-slate-300
                        p-4
                        leading-7
                        outline-none
                        transition
                        focus:border-emerald-500
                        focus:ring-2
                        focus:ring-emerald-100
                    "
                />
            </div>

            {/* ESG Intelligence */}

            <div className="mt-10 rounded-2xl border border-emerald-100 bg-emerald-50 p-6">
                <div className="flex flex-wrap items-center justify-between gap-6">
                    <div>
                        <h3 className="text-lg font-black text-emerald-700">
                            ESG Intelligence™
                        </h3>

                        <p className="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                            {isEn
                                ? "Sustainability information strengthens your company profile, increases buyer confidence, and supports ESG-based sourcing and investment decisions."
                                : "Informasi keberlanjutan memperkuat profil perusahaan, meningkatkan kepercayaan buyer, serta mendukung keputusan sourcing dan investasi berbasis ESG."}
                        </p>
                    </div>

                    <div className="text-right">
                        <div className="text-3xl font-black text-emerald-700">
                            {score}/4
                        </div>

                        <div className="mt-2">
                            <StatusBadge color={badgeColor}>
                                {level}
                            </StatusBadge>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}
