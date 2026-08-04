import OnboardingLayout from "@/Layouts/OnboardingLayout";
import OnboardingNavbar from "@/Components/Onboarding/OnboardingNavbar";

import CompanyBrandingCard from "@/Components/Onboarding/CompanyBrandingCard";
import FactoryGalleryCard from "@/Components/Onboarding/FactoryGalleryCard";
import ProductGalleryCard from "@/Components/Onboarding/ProductGalleryCard";
import DocumentCenterCard from "@/Components/Onboarding/DocumentCenterCard";
import VideoCenterCard from "@/Components/Onboarding/VideoCenterCard";
import MediaSummaryCard from "@/Components/Onboarding/MediaSummaryCard";
import StepNavigation from "@/Components/Onboarding/StepNavigation";

import { Head, useForm, usePage } from "@inertiajs/react";

export default function MediaCatalog() {
    const { locale, company, media = {} } = usePage().props;

    const isEn = locale === "en";

    const { data, setData, post, processing } = useForm({
        branding: {
            company_logo: media.branding?.company_logo ?? null,

            cover_image: media.branding?.cover_image ?? null,

            tagline: media.branding?.tagline ?? "",

            brand_color: media.branding?.brand_color ?? "",
        },

        factory_gallery: media.factory_gallery ?? [],

        product_gallery: media.product_gallery ?? [],

        documents: {
            company_brochure: media.documents?.company_brochure ?? null,

            product_catalog: media.documents?.product_catalog ?? null,

            certifications: media.documents?.certifications ?? [],
        },

        videos: {
            company_video: media.videos?.company_video ?? "",

            factory_tour: media.videos?.factory_tour ?? "",

            production_process: media.videos?.production_process ?? "",
        },
    });

    const submit = (e) => {
        e.preventDefault();

        post(route("onboarding.media-catalog.store"), {
            forceFormData: true,
            preserveScroll: true,
        });
    };

    return (
        <OnboardingLayout>
            <Head
                title={isEn ? "Digital Media Center™" : "Pusat Media Digital™"}
            />

            <div className="min-h-screen bg-slate-50">
                <OnboardingNavbar currentStep={5} />

                <div className="mx-auto max-w-7xl px-6 py-12">
                    <div className="rounded-3xl bg-white p-10 shadow-sm">
                        {/* -------------------------------------------------- */}
                        {/* Header */}
                        {/* -------------------------------------------------- */}

                        <div className="text-center">
                            <p className="text-sm font-black uppercase tracking-[0.2em] text-emerald-600">
                                STEP 5
                            </p>

                            <h1 className="mt-4 text-5xl font-black">
                                {isEn
                                    ? "Digital Media Center™"
                                    : "Pusat Media Digital™"}
                            </h1>

                            <p className="mx-auto mt-5 max-w-3xl text-slate-600 leading-8">
                                {isEn
                                    ? "Upload branding assets, factory galleries, product photos, documents and videos to improve visibility and buyer confidence across the DIGESTEX ecosystem."
                                    : "Unggah branding perusahaan, galeri pabrik, foto produk, dokumen dan video untuk meningkatkan visibilitas dan kepercayaan buyer di seluruh ekosistem DIGESTEX."}
                            </p>
                        </div>

                        {/* -------------------------------------------------- */}
                        {/* Intelligence */}
                        {/* -------------------------------------------------- */}

                        <div className="mt-10 rounded-3xl bg-indigo-50 p-8">
                            <h2 className="text-xl font-black text-indigo-700">
                                Media Intelligence™
                            </h2>

                            <p className="mt-3 text-sm leading-7 text-slate-600">
                                {isEn
                                    ? "Your media assets will power multiple DIGESTEX intelligence services."
                                    : "Media perusahaan akan menjadi fondasi berbagai layanan intelijen DIGESTEX."}
                            </p>

                            <div className="mt-6 grid gap-3 md:grid-cols-2">
                                <div>✓ Digital Company Passport™</div>

                                <div>✓ Executive Dashboard™</div>

                                <div>✓ Company Visibility Score™</div>

                                <div>✓ Smart Business Matching™</div>

                                <div>✓ Buyer Readiness™</div>

                                <div>✓ Public Company Directory</div>
                            </div>
                        </div>

                        {/* -------------------------------------------------- */}
                        {/* Form */}
                        {/* -------------------------------------------------- */}

                        <form onSubmit={submit} className="mt-10">
                            <div className="grid gap-8 lg:grid-cols-3">
                                {/* LEFT */}

                                <div className="space-y-8 lg:col-span-2">
                                    <CompanyBrandingCard
                                        data={data.branding}
                                        setData={(branding) =>
                                            setData("branding", branding)
                                        }
                                    />

                                    <FactoryGalleryCard
                                        data={data.factory_gallery}
                                        setData={(gallery) =>
                                            setData("factory_gallery", gallery)
                                        }
                                    />

                                    <ProductGalleryCard
                                        data={data.product_gallery}
                                        setData={(gallery) =>
                                            setData("product_gallery", gallery)
                                        }
                                    />

                                    <DocumentCenterCard
                                        data={data.documents}
                                        setData={(documents) =>
                                            setData("documents", documents)
                                        }
                                    />

                                    <VideoCenterCard
                                        data={data.videos}
                                        setData={(videos) =>
                                            setData("videos", videos)
                                        }
                                    />
                                </div>

                                {/* RIGHT */}

                                <MediaSummaryCard
                                    company={company}
                                    data={data}
                                />
                            </div>

                            {/* Navigation */}

                            <div className="mt-10">
                                <StepNavigation
                                    currentStep={5}
                                    processing={processing}
                                />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </OnboardingLayout>
    );
}
