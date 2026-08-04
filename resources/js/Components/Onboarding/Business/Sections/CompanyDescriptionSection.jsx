/*
|--------------------------------------------------------------------------
| Company Description Section™
|--------------------------------------------------------------------------
|
| Step 2
|
| Company Overview
|
|--------------------------------------------------------------------------
*/

import { Building2 } from "lucide-react";

export default function CompanyDescriptionSection({
    locale,
    data,
    setData,
    errors = {},
}) {
    const isEn = locale === "en";

    return (
        <section className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            {/* ------------------------------------------------------------ */}
            {/* Header */}
            {/* ------------------------------------------------------------ */}

            <div className="flex items-start gap-4">
                <div className="rounded-2xl bg-indigo-100 p-3">
                    <Building2 className="h-6 w-6 text-indigo-600" />
                </div>

                <div>
                    <h2 className="text-2xl font-black">
                        {isEn ? "Company Description" : "Deskripsi Perusahaan"}
                    </h2>

                    <p className="mt-2 text-sm leading-6 text-slate-500">
                        {isEn
                            ? "Introduce your company and describe your products, capabilities, business focus, and competitive strengths."
                            : "Perkenalkan perusahaan Anda serta jelaskan produk, kemampuan, fokus bisnis, dan keunggulan perusahaan."}
                    </p>
                </div>
            </div>

            {/* ------------------------------------------------------------ */}
            {/* Textarea */}
            {/* ------------------------------------------------------------ */}

            <div className="mt-8">
                <label className="mb-2 block font-semibold">
                    {isEn ? "Company Overview" : "Profil Singkat Perusahaan"}
                </label>

                <textarea
                    rows={8}
                    value={data.business_description}
                    onChange={(event) =>
                        setData("business_description", event.target.value)
                    }
                    className={`
                        w-full rounded-2xl border bg-white p-5
                        leading-7 transition
                        focus:border-indigo-500
                        focus:ring-2
                        focus:ring-indigo-200
                        focus:outline-none

                        ${
                            errors.business_description
                                ? "border-red-400"
                                : "border-slate-300"
                        }
                    `}
                    placeholder={
                        isEn
                            ? "Introduce your company..."
                            : "Perkenalkan perusahaan Anda..."
                    }
                />

                {errors.business_description && (
                    <p className="mt-2 text-sm text-red-600">
                        {errors.business_description}
                    </p>
                )}
            </div>

            {/* ------------------------------------------------------------ */}
            {/* Intelligence */}
            {/* ------------------------------------------------------------ */}

            <div className="mt-8 rounded-2xl border border-indigo-100 bg-indigo-50 p-5">
                <div className="font-bold text-indigo-700">
                    DIGESTEX Intelligence™
                </div>

                <p className="mt-2 text-sm leading-6 text-slate-600">
                    {isEn
                        ? "A clear company description improves visibility in Company Intelligence™, Buyer Search™, Smart Business Matching™, and AI-powered recommendations."
                        : "Deskripsi perusahaan yang jelas akan meningkatkan visibilitas pada Company Intelligence™, Buyer Search™, Smart Business Matching™, dan rekomendasi berbasis AI."}
                </p>
            </div>
        </section>
    );
}
