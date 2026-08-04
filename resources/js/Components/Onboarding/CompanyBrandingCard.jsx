import { usePage } from "@inertiajs/react";
import { Image, Palette, Upload } from "lucide-react";

export default function CompanyBrandingCard({ data, setData }) {
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
                    {isEn ? "Company Branding" : "Branding Perusahaan"}
                </h2>

                <p className="mt-2 text-sm leading-6 text-slate-500">
                    {isEn
                        ? "Build your company's digital identity through logo, cover image and brand information."
                        : "Bangun identitas digital perusahaan melalui logo, gambar sampul, dan informasi branding."}
                </p>
            </div>

            <div className="space-y-8">
                {/* Company Logo */}

                <UploadBox
                    title={isEn ? "Company Logo" : "Logo Perusahaan"}
                    subtitle={isEn ? "PNG, JPG or SVG" : "PNG, JPG atau SVG"}
                    file={data.company_logo}
                    onChange={(file) => updateField("company_logo", file)}
                    accept="image/*"
                />

                {/* Cover Image */}

                <UploadBox
                    title={isEn ? "Cover Image" : "Gambar Sampul"}
                    subtitle={
                        isEn
                            ? "Recommended landscape image"
                            : "Disarankan gambar landscape"
                    }
                    file={data.cover_image}
                    onChange={(file) => updateField("cover_image", file)}
                    accept="image/*"
                />

                {/* Company Tagline */}

                <div>
                    <label className="mb-2 block font-semibold">
                        {isEn ? "Company Tagline" : "Tagline Perusahaan"}
                    </label>

                    <div className="relative">
                        <Palette className="absolute left-4 top-3.5 h-5 w-5 text-slate-400" />

                        <input
                            type="text"
                            value={data.tagline}
                            onChange={(e) =>
                                updateField("tagline", e.target.value)
                            }
                            placeholder={
                                isEn
                                    ? "Connecting Textile Intelligence..."
                                    : "Contoh: Connecting Textile Intelligence..."
                            }
                            className="
                                w-full
                                rounded-xl
                                border
                                border-slate-300
                                py-3
                                pl-12
                                pr-4
                                focus:border-indigo-500
                                focus:outline-none
                                focus:ring-2
                                focus:ring-indigo-100
                            "
                        />
                    </div>
                </div>

                {/* Brand Color */}

                <div>
                    <label className="mb-2 block font-semibold">
                        {isEn ? "Brand Color" : "Warna Brand"}
                    </label>

                    <div className="relative">
                        <Palette className="absolute left-4 top-3.5 h-5 w-5 text-slate-400" />

                        <input
                            type="text"
                            value={data.brand_color}
                            onChange={(e) =>
                                updateField("brand_color", e.target.value)
                            }
                            placeholder={
                                isEn
                                    ? "Example: Navy Blue"
                                    : "Contoh: Navy Blue"
                            }
                            className="
                                w-full
                                rounded-xl
                                border
                                border-slate-300
                                py-3
                                pl-12
                                pr-4
                                focus:border-indigo-500
                                focus:outline-none
                                focus:ring-2
                                focus:ring-indigo-100
                            "
                        />
                    </div>
                </div>
            </div>
        </div>
    );
}

function UploadBox({ title, subtitle, file, onChange, accept }) {
    return (
        <div>
            <label className="mb-3 block font-semibold">{title}</label>

            <div
                className="
                    rounded-2xl
                    border-2
                    border-dashed
                    border-slate-300
                    p-8
                    transition
                    hover:border-indigo-400
                    hover:bg-slate-50
                "
            >
                <div className="flex flex-col items-center text-center">
                    <Image className="h-10 w-10 text-slate-400" />

                    <h3 className="mt-4 font-bold text-slate-700">{title}</h3>

                    <p className="mt-2 text-sm text-slate-500">{subtitle}</p>

                    <label
                        className="
                            mt-6
                            inline-flex
                            cursor-pointer
                            items-center
                            gap-2
                            rounded-xl
                            bg-indigo-600
                            px-5
                            py-3
                            font-semibold
                            text-white
                            hover:bg-indigo-700
                        "
                    >
                        <Upload className="h-5 w-5" />
                        Upload
                        <input
                            type="file"
                            accept={accept}
                            onChange={(e) =>
                                onChange(e.target.files?.[0] ?? null)
                            }
                            className="hidden"
                        />
                    </label>

                    {file && (
                        <div className="mt-5 rounded-xl bg-emerald-50 px-4 py-2 text-sm font-medium text-emerald-700">
                            ✓ {file.name}
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
}
