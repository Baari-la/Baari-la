import { usePage } from "@inertiajs/react";
import { FileText, Upload, Award, ShieldCheck } from "lucide-react";

export default function DocumentCenterCard({ data, setData }) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const updateField = (field, value) => {
        setData({
            ...data,
            [field]: value,
        });
    };

    return (
        <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <div className="mb-8">
                <h2 className="text-2xl font-black text-slate-900">
                    {isEn ? "Document Center" : "Pusat Dokumen"}
                </h2>

                <p className="mt-2 text-sm leading-6 text-slate-500">
                    {isEn
                        ? "Upload important company documents to strengthen credibility and improve buyer confidence."
                        : "Unggah dokumen penting perusahaan untuk meningkatkan kredibilitas dan kepercayaan buyer."}
                </p>
            </div>

            <div className="space-y-8">
                <UploadBox
                    title={isEn ? "Company Brochure" : "Brosur Perusahaan"}
                    subtitle={
                        isEn
                            ? "Corporate brochure in PDF format."
                            : "Brosur perusahaan dalam format PDF."
                    }
                    file={data.company_brochure}
                    accept=".pdf"
                    onChange={(file) => updateField("company_brochure", file)}
                />

                <UploadBox
                    title={isEn ? "Product Catalog" : "Katalog Produk"}
                    subtitle={
                        isEn
                            ? "Product catalog in PDF format."
                            : "Katalog produk dalam format PDF."
                    }
                    file={data.product_catalog}
                    accept=".pdf"
                    onChange={(file) => updateField("product_catalog", file)}
                />

                <MultiUploadBox
                    title={isEn ? "Certificates" : "Sertifikat"}
                    subtitle={
                        isEn
                            ? "ISO, OEKO-TEX®, GRS, GOTS, Higg, WRAP, Sedex, etc."
                            : "ISO, OEKO-TEX®, GRS, GOTS, Higg, WRAP, Sedex, dll."
                    }
                    files={data.certifications}
                    accept=".pdf,image/*"
                    onChange={(files) => updateField("certifications", files)}
                />
            </div>

            <div className="mt-8 rounded-2xl bg-slate-50 p-5">
                <div className="flex items-center gap-2">
                    <ShieldCheck className="h-5 w-5 text-indigo-600" />

                    <span className="font-bold text-indigo-700">
                        {isEn
                            ? "Recommended Documents"
                            : "Dokumen yang Direkomendasikan"}
                    </span>
                </div>

                <ul className="mt-4 space-y-2 text-sm text-slate-600">
                    <li>✓ Company Profile</li>
                    <li>✓ Product Catalog</li>
                    <li>✓ ISO Certificates</li>
                    <li>✓ OEKO-TEX® Certificates</li>
                    <li>✓ Sustainability Report</li>
                    <li>✓ ESG Report</li>
                </ul>
            </div>

            <div className="mt-6 rounded-2xl bg-emerald-50 p-5">
                <div className="flex items-center gap-2">
                    <Award className="h-5 w-5 text-emerald-600" />

                    <span className="font-bold text-emerald-700">
                        {isEn
                            ? "Document Intelligence™"
                            : "Document Intelligence™"}
                    </span>
                </div>

                <p className="mt-3 text-sm leading-6 text-slate-600">
                    {isEn
                        ? "Verified documents improve Company Visibility Score™, Executive Dashboard™, and Smart Business Matching™."
                        : "Dokumen terverifikasi meningkatkan Company Visibility Score™, Executive Dashboard™, dan Smart Business Matching™."}
                </p>
            </div>
        </div>
    );
}

function UploadBox({ title, subtitle, file, onChange, accept }) {
    return (
        <div>
            <label className="mb-3 block font-semibold">{title}</label>

            <div className="rounded-2xl border-2 border-dashed border-slate-300 p-8 text-center hover:border-indigo-400 hover:bg-slate-50 transition">
                <FileText className="mx-auto h-10 w-10 text-slate-400" />

                <h3 className="mt-4 font-bold text-slate-700">{title}</h3>

                <p className="mt-2 text-sm text-slate-500">{subtitle}</p>

                <label className="mt-6 inline-flex cursor-pointer items-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 font-semibold text-white hover:bg-indigo-700">
                    <Upload className="h-5 w-5" />
                    Upload
                    <input
                        type="file"
                        accept={accept}
                        className="hidden"
                        onChange={(e) => onChange(e.target.files?.[0] ?? null)}
                    />
                </label>

                {file && (
                    <div className="mt-5 rounded-xl bg-emerald-50 px-4 py-2 text-sm font-medium text-emerald-700">
                        ✓ {file.name}
                    </div>
                )}
            </div>
        </div>
    );
}

function MultiUploadBox({ title, subtitle, files, onChange, accept }) {
    return (
        <div>
            <label className="mb-3 block font-semibold">{title}</label>

            <div className="rounded-2xl border-2 border-dashed border-slate-300 p-8 text-center hover:border-indigo-400 hover:bg-slate-50 transition">
                <Award className="mx-auto h-10 w-10 text-slate-400" />

                <h3 className="mt-4 font-bold text-slate-700">{title}</h3>

                <p className="mt-2 text-sm text-slate-500">{subtitle}</p>

                <label className="mt-6 inline-flex cursor-pointer items-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 font-semibold text-white hover:bg-indigo-700">
                    <Upload className="h-5 w-5" />

                    {files?.length > 0 ? "Add More" : "Upload"}

                    <input
                        type="file"
                        multiple
                        accept={accept}
                        className="hidden"
                        onChange={(e) =>
                            onChange(Array.from(e.target.files || []))
                        }
                    />
                </label>

                {files?.length > 0 && (
                    <div className="mt-6 text-left">
                        <div className="mb-3 font-semibold text-emerald-700">
                            ✓ {files.length} file(s)
                        </div>

                        <div className="space-y-2">
                            {files.map((file, index) => (
                                <div
                                    key={index}
                                    className="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm text-emerald-700"
                                >
                                    {file.name}
                                </div>
                            ))}
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
}
