/*
|--------------------------------------------------------------------------
| Business Strategy Section™
|--------------------------------------------------------------------------
|
| Step 2
|
| Defines how the company operates in the market.
|
|--------------------------------------------------------------------------
*/

import { BriefcaseBusiness, Target } from "lucide-react";

import CheckboxCard from "../../Shared/CheckboxCard";

export default function BusinessStrategySection({ locale, data, setData }) {
    const isEn = locale === "en";

    const update = (field, value) => {
        setData(field, value);
    };

    return (
        <section className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            {/* ------------------------------------------------------------ */}
            {/* Header */}
            {/* ------------------------------------------------------------ */}

            <div className="flex items-start gap-4">
                <div className="rounded-2xl bg-amber-100 p-3">
                    <BriefcaseBusiness className="h-6 w-6 text-amber-600" />
                </div>

                <div>
                    <h2 className="text-2xl font-black">
                        {isEn ? "Business Strategy" : "Strategi Bisnis"}
                    </h2>

                    <p className="mt-2 text-sm leading-6 text-slate-500">
                        {isEn
                            ? "Select the business models currently operated by your company."
                            : "Pilih model bisnis yang dijalankan oleh perusahaan Anda."}
                    </p>
                </div>
            </div>

            {/* ------------------------------------------------------------ */}
            {/* Business Models */}
            {/* ------------------------------------------------------------ */}

            <div className="mt-8 grid gap-4 md:grid-cols-2">
                <CheckboxCard
                    title="OEM"
                    description="Original Equipment Manufacturer"
                    checked={data.oem}
                    onChange={(value) => update("oem", value)}
                />

                <CheckboxCard
                    title="ODM"
                    description="Original Design Manufacturer"
                    checked={data.odm}
                    onChange={(value) => update("odm", value)}
                />

                <CheckboxCard
                    title="OBM"
                    description="Own Brand Manufacturer"
                    checked={data.obm}
                    onChange={(value) => update("obm", value)}
                />

                <CheckboxCard
                    title={isEn ? "Private Label" : "Private Label"}
                    description="Produce under customer's brand"
                    checked={data.private_label}
                    onChange={(value) => update("private_label", value)}
                />
            </div>

            {/* ------------------------------------------------------------ */}
            {/* Business Model Summary */}
            {/* ------------------------------------------------------------ */}

            <div className="mt-8 rounded-2xl border border-slate-200 bg-slate-50 p-6">
                <div className="flex items-center gap-2">
                    <Target className="h-5 w-5 text-indigo-600" />

                    <span className="font-bold">
                        {isEn
                            ? "Business Model Summary"
                            : "Ringkasan Model Bisnis"}
                    </span>
                </div>

                <div className="mt-4 flex flex-wrap gap-2">
                    {data.oem && <Badge label="OEM" />}

                    {data.odm && <Badge label="ODM" />}

                    {data.obm && <Badge label="OBM" />}

                    {data.private_label && <Badge label="Private Label" />}

                    {!data.oem &&
                        !data.odm &&
                        !data.obm &&
                        !data.private_label && (
                            <span className="text-sm text-slate-500">
                                {isEn
                                    ? "No business model selected yet."
                                    : "Belum ada model bisnis yang dipilih."}
                            </span>
                        )}
                </div>
            </div>

            {/* ------------------------------------------------------------ */}
            {/* Intelligence */}
            {/* ------------------------------------------------------------ */}

            <div className="mt-8 rounded-2xl border border-amber-100 bg-amber-50 p-5">
                <div className="font-bold text-amber-700">
                    DIGESTEX Intelligence™
                </div>

                <p className="mt-2 text-sm leading-6 text-slate-600">
                    {isEn
                        ? "Business Strategy helps DIGESTEX understand how your company serves buyers and brands. This information will improve Smart Business Matching™, Buyer Intelligence™, and Supply Chain Recommendations."
                        : "Strategi bisnis membantu DIGESTEX memahami bagaimana perusahaan Anda melayani buyer dan brand. Informasi ini akan meningkatkan Smart Business Matching™, Buyer Intelligence™, dan rekomendasi Supply Chain."}
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
        <span className="rounded-full bg-indigo-100 px-4 py-2 text-sm font-semibold text-indigo-700">
            {label}
        </span>
    );
}
