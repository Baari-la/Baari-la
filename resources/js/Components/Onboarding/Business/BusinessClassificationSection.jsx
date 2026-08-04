import {
    Building2,
    Factory,
    FlaskConical,
    Wrench,
    Briefcase,
} from "lucide-react";

import SectionHeader from "../Shared/SectionHeader";
import CheckboxCard from "../Shared/CheckboxCard";
import StatusBadge from "../Shared/StatusBadge";

export default function BusinessClassificationSection({
    data,
    setData,
    business,
    isEn = true,
}) {
    const update = (field, value) => {
        setData(field, value);
    };

    const category = business?.primary_business_category ?? "manufacturer";

    const valueChain = business?.value_chain_position ?? "-";

    const secondary = business?.secondary_business_categories ?? [];

    return (
        <section className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <SectionHeader
                icon={Building2}
                title={
                    isEn ? "Business Classification™" : "Klasifikasi Bisnis™"
                }
                description={
                    isEn
                        ? "Select the business activities that best describe your company. DIGESTEX automatically classifies your business and prepares a customized Capability Profile™."
                        : "Pilih aktivitas bisnis yang paling sesuai dengan perusahaan Anda. DIGESTEX akan mengklasifikasikan perusahaan secara otomatis dan menyiapkan Capability Profile™ yang sesuai."
                }
            />

            {/* Primary Manufacturing */}

            <h3 className="mb-4 text-lg font-bold text-slate-800">
                {isEn ? "Manufacturing Activities" : "Aktivitas Manufaktur"}
            </h3>

            <div className="grid gap-4 md:grid-cols-2">
                <CheckboxCard
                    title="Fiber Producer"
                    checked={data.is_fiber_producer}
                    onChange={(v) => update("is_fiber_producer", v)}
                />

                <CheckboxCard
                    title="Spinner"
                    checked={data.is_spinner}
                    onChange={(v) => update("is_spinner", v)}
                />

                <CheckboxCard
                    title="Weaving"
                    checked={data.is_weaving}
                    onChange={(v) => update("is_weaving", v)}
                />

                <CheckboxCard
                    title="Knitting"
                    checked={data.is_knitting}
                    onChange={(v) => update("is_knitting", v)}
                />

                <CheckboxCard
                    title="Dyeing & Finishing"
                    checked={data.is_dyeing_finishing}
                    onChange={(v) => update("is_dyeing_finishing", v)}
                />

                <CheckboxCard
                    title="Printing"
                    checked={data.is_printing}
                    onChange={(v) => update("is_printing", v)}
                />

                <CheckboxCard
                    title="Garment"
                    checked={data.is_garment}
                    onChange={(v) => update("is_garment", v)}
                />

                <CheckboxCard
                    title="Trader"
                    checked={data.is_trader}
                    onChange={(v) => update("is_trader", v)}
                />

                <CheckboxCard
                    title="Brand Owner"
                    checked={data.is_brand}
                    onChange={(v) => update("is_brand", v)}
                />

                <CheckboxCard
                    title="Buying Office"
                    checked={data.is_buying_office}
                    onChange={(v) => update("is_buying_office", v)}
                />
            </div>

            {/* Supporting Industry */}

            <h3 className="mt-10 mb-4 text-lg font-bold text-slate-800">
                {isEn ? "Supporting Industry" : "Industri Pendukung"}
            </h3>

            <div className="grid gap-4 md:grid-cols-2">
                <CheckboxCard
                    title="Testing Laboratory"
                    checked={data.is_testing_laboratory}
                    onChange={(v) => update("is_testing_laboratory", v)}
                />

                <CheckboxCard
                    title="Certification Body"
                    checked={data.is_certification_body}
                    onChange={(v) => update("is_certification_body", v)}
                />

                <CheckboxCard
                    title="Machinery Manufacturer / Distributor"
                    checked={data.is_machinery_supplier}
                    onChange={(v) => update("is_machinery_supplier", v)}
                />

                <CheckboxCard
                    title="Textile Accessories Supplier"
                    checked={data.is_accessories_supplier}
                    onChange={(v) => update("is_accessories_supplier", v)}
                />

                <CheckboxCard
                    title="Textile Chemicals Supplier"
                    checked={data.is_chemical_supplier}
                    onChange={(v) => update("is_chemical_supplier", v)}
                />
            </div>

            {/* Business Classification Intelligence */}

            <div className="mt-10 rounded-2xl border border-indigo-100 bg-indigo-50 p-6">
                <div className="flex flex-wrap items-center justify-between gap-6">
                    <div>
                        <h3 className="text-lg font-black text-indigo-700">
                            Business Classification™
                        </h3>

                        <p className="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                            {isEn
                                ? "DIGESTEX automatically determines your business category based on your selected activities. This classification is used to generate the appropriate Capability Profile™ and improve Smart Business Matching™."
                                : "DIGESTEX secara otomatis menentukan kategori bisnis berdasarkan aktivitas yang dipilih. Klasifikasi ini digunakan untuk membangun Capability Profile™ dan meningkatkan Smart Business Matching™."}
                        </p>
                    </div>

                    <div className="space-y-3">
                        <div>
                            <div className="mb-1 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                {isEn ? "Primary Category" : "Kategori Utama"}
                            </div>

                            <StatusBadge color="indigo">{category}</StatusBadge>
                        </div>

                        <div>
                            <div className="mb-1 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                {isEn ? "Value Chain" : "Rantai Nilai"}
                            </div>

                            <StatusBadge color="emerald">
                                {valueChain}
                            </StatusBadge>
                        </div>

                        {secondary.length > 0 && (
                            <div>
                                <div className="mb-1 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    {isEn
                                        ? "Secondary Categories"
                                        : "Kategori Tambahan"}
                                </div>

                                <div className="flex flex-wrap gap-2">
                                    {secondary.map((item) => (
                                        <StatusBadge
                                            key={item}
                                            color="blue"
                                            size="sm"
                                        >
                                            {item}
                                        </StatusBadge>
                                    ))}
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </section>
    );
}
