/*
|--------------------------------------------------------------------------
| Sustainability Section™
|--------------------------------------------------------------------------
|
| Step 2
|
| Sustainability and ESG readiness information.
|
|--------------------------------------------------------------------------
*/

import { Leaf, Recycle, Droplets, Sun, FileText } from "lucide-react";

import CheckboxCard from "../../Shared/CheckboxCard";

export default function SustainabilitySection({
    locale,
    data,
    setData,
    errors = {},
}) {
    const isEn = locale === "en";

    return (
        <section className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            {/* =======================================================
                Header
            ======================================================= */}

            <div className="flex items-start gap-4">
                <div className="rounded-2xl bg-green-100 p-3">
                    <Leaf className="h-6 w-6 text-green-600" />
                </div>

                <div>
                    <h2 className="text-2xl font-black">
                        {isEn ? "Sustainability" : "Keberlanjutan"}
                    </h2>

                    <p className="mt-2 text-sm leading-6 text-slate-500">
                        {isEn
                            ? "Help buyers understand your sustainability initiatives and ESG commitment."
                            : "Bantu buyer memahami inisiatif keberlanjutan dan komitmen ESG perusahaan Anda."}
                    </p>
                </div>
            </div>

            {/* =======================================================
                ESG Programs
            ======================================================= */}

            <div className="mt-8 grid gap-4 md:grid-cols-2">
                <CheckboxCard
                    title={isEn ? "ESG Program" : "Program ESG"}
                    description={
                        isEn
                            ? "Environmental, Social & Governance initiatives."
                            : "Inisiatif Environmental, Social & Governance."
                    }
                    checked={data.esg_program}
                    onChange={(value) => setData("esg_program", value)}
                />

                <CheckboxCard
                    title={isEn ? "Renewable Energy" : "Energi Terbarukan"}
                    description={
                        isEn
                            ? "Solar, biomass, hydro, wind or other renewable energy."
                            : "Energi surya, biomassa, hidro, angin, atau energi terbarukan lainnya."
                    }
                    checked={data.renewable_energy}
                    onChange={(value) => setData("renewable_energy", value)}
                />

                <CheckboxCard
                    title={isEn ? "Recycled Material" : "Material Daur Ulang"}
                    description={
                        isEn
                            ? "Using recycled fibers or recycled raw materials."
                            : "Menggunakan serat atau bahan baku daur ulang."
                    }
                    checked={data.recycled_material}
                    onChange={(value) => setData("recycled_material", value)}
                />

                <CheckboxCard
                    title={
                        isEn
                            ? "Wastewater Treatment"
                            : "IPAL / Wastewater Treatment"
                    }
                    description={
                        isEn
                            ? "Wastewater treatment facility."
                            : "Memiliki instalasi pengolahan air limbah."
                    }
                    checked={data.wastewater_treatment}
                    onChange={(value) => setData("wastewater_treatment", value)}
                />
            </div>

            {/* =======================================================
                Sustainability Notes
            ======================================================= */}

            <div className="mt-8">
                <label className="mb-2 flex items-center gap-2 font-semibold">
                    <FileText className="h-4 w-4 text-slate-500" />

                    {isEn
                        ? "Additional Sustainability Notes"
                        : "Catatan Tambahan"}
                </label>

                <textarea
                    rows={5}
                    value={data.sustainability_notes}
                    onChange={(e) =>
                        setData("sustainability_notes", e.target.value)
                    }
                    className={`w-full rounded-2xl border p-4 leading-7 focus:border-indigo-500 focus:outline-none ${
                        errors.sustainability_notes
                            ? "border-red-400"
                            : "border-slate-300"
                    }`}
                    placeholder={
                        isEn
                            ? "Describe your sustainability initiatives..."
                            : "Jelaskan program keberlanjutan perusahaan..."
                    }
                />

                {errors.sustainability_notes && (
                    <p className="mt-2 text-sm text-red-600">
                        {errors.sustainability_notes}
                    </p>
                )}
            </div>

            {/* =======================================================
                Sustainability Summary
            ======================================================= */}

            <div className="mt-8 rounded-2xl border border-slate-200 bg-slate-50 p-6">
                <div className="font-bold">
                    {isEn
                        ? "Sustainability Summary"
                        : "Ringkasan Keberlanjutan"}
                </div>

                <div className="mt-4 flex flex-wrap gap-2">
                    {data.esg_program && <Badge label="ESG" />}

                    {data.renewable_energy && (
                        <Badge label="Renewable Energy" />
                    )}

                    {data.recycled_material && (
                        <Badge label="Recycled Material" />
                    )}

                    {data.wastewater_treatment && (
                        <Badge label="Wastewater Treatment" />
                    )}

                    {!data.esg_program &&
                        !data.renewable_energy &&
                        !data.recycled_material &&
                        !data.wastewater_treatment && (
                            <span className="text-sm text-slate-500">
                                {isEn
                                    ? "No sustainability initiative selected."
                                    : "Belum ada program keberlanjutan yang dipilih."}
                            </span>
                        )}
                </div>
            </div>

            {/* =======================================================
                Intelligence
            ======================================================= */}

            <div className="mt-8 rounded-2xl border border-green-100 bg-green-50 p-5">
                <div className="font-bold text-green-700">
                    DIGESTEX Sustainability Intelligence™
                </div>

                <p className="mt-2 text-sm leading-6 text-slate-600">
                    {isEn
                        ? "Sustainability information helps global buyers evaluate ESG readiness, environmental compliance, and responsible sourcing practices."
                        : "Informasi keberlanjutan membantu buyer global mengevaluasi kesiapan ESG, kepatuhan lingkungan, dan praktik responsible sourcing perusahaan Anda."}
                </p>
            </div>
        </section>
    );
}

/*
|--------------------------------------------------------------------------
| Badge
|--------------------------------------------------------------------------
*/

function Badge({ label }) {
    return (
        <span className="rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-700">
            {label}
        </span>
    );
}
