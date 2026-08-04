import { FileText } from "lucide-react";

import SectionHeader from "../Shared/SectionHeader";

export default function CompanyDescriptionSection({
    data,
    setData,
    isEn = true,
}) {
    const update = (field, value) => {
        setData(field, value);
    };

    return (
        <section className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <SectionHeader
                icon={FileText}
                title={isEn ? "Company Overview™" : "Gambaran Umum Perusahaan™"}
                description={
                    isEn
                        ? "Describe your company, core business, products, services, and competitive strengths. This information helps buyers and DIGESTEX Company Intelligence™ understand your business profile."
                        : "Jelaskan perusahaan, kegiatan usaha utama, produk, layanan, dan keunggulan kompetitif perusahaan. Informasi ini membantu buyer dan DIGESTEX Company Intelligence™ memahami profil perusahaan."
                }
            />

            <div className="space-y-2">
                <label className="text-sm font-semibold text-slate-700">
                    {isEn ? "Company Description" : "Deskripsi Perusahaan"}
                </label>

                <textarea
                    rows={7}
                    value={data.business_description ?? ""}
                    onChange={(e) =>
                        update("business_description", e.target.value)
                    }
                    placeholder={
                        isEn
                            ? "Introduce your company, products, services, manufacturing expertise, target market, and business strengths..."
                            : "Perkenalkan perusahaan, produk, layanan, keahlian manufaktur, target pasar, dan keunggulan perusahaan..."
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
                        focus:border-indigo-500
                        focus:ring-2
                        focus:ring-indigo-100
                    "
                />

                <p className="text-sm leading-6 text-slate-500">
                    {isEn
                        ? "This description will appear in your Digital Company Passport™, Company Directory, Company Intelligence™, and Smart Business Matching™."
                        : "Deskripsi ini akan ditampilkan pada Digital Company Passport™, Company Directory, Company Intelligence™, dan Smart Business Matching™."}
                </p>
            </div>
        </section>
    );
}
