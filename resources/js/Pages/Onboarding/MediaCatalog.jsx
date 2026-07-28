import OnboardingLayout from "@/Layouts/OnboardingLayout";
import OnboardingNavbar from "@/Components/Onboarding/OnboardingNavbar";

import { Head, useForm, usePage } from "@inertiajs/react";

import { Image, FileText, Video, Upload, ArrowRight } from "lucide-react";

export default function MediaCatalog() {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const { data, setData, post, processing } = useForm({
        company_logo: null,
        cover_image: null,
        factory_photos: [],
        product_photos: [],
        machinery_photos: [],
        certifications: [],
        company_video: "",
        company_brochure: null,
        product_catalog: null,
    });

    const submit = (e) => {
        e.preventDefault();

        post(route("onboarding.media-catalog.store"), {
            forceFormData: true,
        });
    };

    return (
        <OnboardingLayout>
            <Head title="Media & Catalog" />

            <div className="min-h-screen bg-slate-50">
                <OnboardingNavbar currentStep={5} />

                <div className="mx-auto max-w-6xl px-6 py-12">
                    <div className="rounded-3xl bg-white p-10 shadow-sm">
                        <div className="text-center">
                            <p className="text-sm font-black uppercase tracking-[0.2em] text-emerald-600">
                                STEP 5
                            </p>

                            <h1 className="mt-4 text-5xl font-black">
                                {isEn ? "Media & Catalog" : "Media & Katalog"}
                            </h1>

                            <p className="mt-4 text-slate-500">
                                {isEn
                                    ? "Show the world your company."
                                    : "Tunjukkan perusahaan Anda kepada dunia."}
                            </p>
                        </div>

                        <form onSubmit={submit} className="mt-10 space-y-8">
                            <UploadField
                                label="Company Logo"
                                accept="image/*"
                                onChange={(e) =>
                                    setData("company_logo", e.target.files[0])
                                }
                            />

                            <UploadField
                                label="Cover Image"
                                accept="image/*"
                                onChange={(e) =>
                                    setData("cover_image", e.target.files[0])
                                }
                            />

                            <UploadField
                                label="Factory Photos"
                                multiple
                                accept="image/*"
                                onChange={(e) =>
                                    setData(
                                        "factory_photos",
                                        Array.from(e.target.files),
                                    )
                                }
                            />

                            <UploadField
                                label="Product Photos"
                                multiple
                                accept="image/*"
                                onChange={(e) =>
                                    setData(
                                        "product_photos",
                                        Array.from(e.target.files),
                                    )
                                }
                            />

                            <UploadField
                                label="Machinery Photos"
                                multiple
                                accept="image/*"
                                onChange={(e) =>
                                    setData(
                                        "machinery_photos",
                                        Array.from(e.target.files),
                                    )
                                }
                            />

                            <UploadField
                                label="Certifications"
                                multiple
                                accept="image/*,.pdf"
                                onChange={(e) =>
                                    setData(
                                        "certifications",
                                        Array.from(e.target.files),
                                    )
                                }
                            />

                            <div>
                                <label className="font-semibold">
                                    Company Video (YouTube)
                                </label>

                                <input
                                    type="text"
                                    value={data.company_video}
                                    onChange={(e) =>
                                        setData("company_video", e.target.value)
                                    }
                                    className="
                                        mt-2
                                        w-full
                                        rounded-xl
                                        border
                                        border-slate-300
                                        p-3
                                    "
                                    placeholder="https://youtube.com/..."
                                />
                            </div>

                            <UploadField
                                label="Company Brochure"
                                accept=".pdf"
                                onChange={(e) =>
                                    setData(
                                        "company_brochure",
                                        e.target.files[0],
                                    )
                                }
                            />

                            <UploadField
                                label="Product Catalog"
                                accept=".pdf"
                                onChange={(e) =>
                                    setData(
                                        "product_catalog",
                                        e.target.files[0],
                                    )
                                }
                            />

                            <div className="rounded-3xl bg-amber-50 p-6">
                                <div className="font-black text-amber-700">
                                    Visibility Score™
                                </div>

                                <p className="mt-2 text-sm text-slate-600">
                                    Companies with complete photos, brochures,
                                    and catalogs receive higher visibility
                                    within the DIGESTEX ecosystem.
                                </p>
                            </div>

                            <div className="flex justify-end">
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="
                                        inline-flex
                                        items-center
                                        gap-2
                                        rounded-2xl
                                        bg-emerald-600
                                        px-8
                                        py-4
                                        font-black
                                        text-white
                                    "
                                >
                                    {isEn ? "CONTINUE" : "LANJUTKAN"}

                                    <ArrowRight className="h-5 w-5" />
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </OnboardingLayout>
    );
}

function UploadField({ label, onChange, accept, multiple = false }) {
    return (
        <div>
            <label className="font-semibold">{label}</label>

            <div
                className="
                    mt-2
                    rounded-2xl
                    border-2
                    border-dashed
                    border-slate-300
                    p-8
                    text-center
                "
            >
                <Upload className="mx-auto h-10 w-10 text-slate-400" />

                <p className="mt-3 text-sm text-slate-500">Click to upload</p>

                <input
                    type="file"
                    multiple={multiple}
                    accept={accept}
                    onChange={onChange}
                    className="mt-4"
                />
            </div>
        </div>
    );
}
