import OnboardingLayout from "@/Layouts/OnboardingLayout";
import OnboardingNavbar from "@/Components/Onboarding/OnboardingNavbar";

import { Head, Link, usePage } from "@inertiajs/react";

import {
    CheckCircle,
    Building2,
    Brain,
    ShieldCheck,
    ArrowRight,
    Save,
} from "lucide-react";

export default function ReviewSubmit() {
    const { locale, auth } = usePage().props;

    const isEn = locale === "en";

    const visibilityScore = 87;

    return (
        <OnboardingLayout>
            <Head title="Review & Submit" />

            <div className="min-h-screen bg-slate-50">
                <OnboardingNavbar currentStep={6} />

                <div className="mx-auto max-w-6xl px-6 py-12">
                    <div className="rounded-3xl bg-white p-10 shadow-sm">
                        {/* Header */}

                        <div className="text-center">
                            <p className="text-sm font-black uppercase tracking-[0.2em] text-emerald-600">
                                STEP 6
                            </p>

                            <h1 className="mt-4 text-5xl font-black">
                                {isEn ? "Review & Submit" : "Tinjau & Kirim"}
                            </h1>

                            <p className="mt-4 text-slate-500">
                                {isEn
                                    ? "Review your Digital Company Passport™ before submission."
                                    : "Tinjau Digital Company Passport™ Anda sebelum dikirim."}
                            </p>
                        </div>

                        {/* Checklist */}

                        <div className="mt-12 grid gap-4 md:grid-cols-2">
                            {[
                                "Company Information",
                                "Business Information",
                                "Capabilities",
                                "Manufacturing",
                                "Media & Catalog",
                            ].map((item) => (
                                <div
                                    key={item}
                                    className="
                                        flex
                                        items-center
                                        gap-4
                                        rounded-2xl
                                        border
                                        border-emerald-200
                                        bg-emerald-50
                                        p-5
                                    "
                                >
                                    <CheckCircle className="h-6 w-6 text-emerald-600" />

                                    <span className="font-semibold">
                                        {item}
                                    </span>
                                </div>
                            ))}
                        </div>

                        {/* Visibility Score */}

                        <div className="mt-10 rounded-3xl bg-amber-50 p-8">
                            <div className="flex items-center gap-4">
                                <Building2 className="h-10 w-10 text-amber-600" />

                                <div>
                                    <div className="text-sm font-black uppercase tracking-widest text-amber-600">
                                        Visibility Score™
                                    </div>

                                    <div className="mt-2 text-5xl font-black">
                                        {visibilityScore}%
                                    </div>
                                </div>
                            </div>

                            <p className="mt-4 text-slate-600">
                                Companies with higher Visibility Scores receive
                                greater exposure within the DIGESTEX ecosystem.
                            </p>
                        </div>

                        {/* Executive Insight */}

                        <div className="mt-8 rounded-3xl bg-indigo-50 p-8">
                            <div className="flex items-center gap-4">
                                <Brain className="h-10 w-10 text-indigo-600" />

                                <div>
                                    <h3 className="text-2xl font-black">
                                        Executive Insight™
                                    </h3>

                                    <p className="mt-2 text-slate-600">
                                        This company appears to be export-ready
                                        with strong manufacturing capabilities
                                        and has the potential to become a
                                        strategic supply chain partner.
                                    </p>
                                </div>
                            </div>
                        </div>

                        {/* Verification */}

                        <div className="mt-8 rounded-3xl bg-slate-900 p-8 text-white">
                            <div className="flex items-center gap-4">
                                <ShieldCheck className="h-10 w-10 text-emerald-400" />

                                <div>
                                    <h3 className="text-2xl font-black">
                                        DIGESTEX Verification
                                    </h3>

                                    <p className="mt-2 text-slate-300">
                                        After submission, our team will review
                                        your company profile before publishing
                                        it in the DIGESTEX Global Directory.
                                    </p>
                                </div>
                            </div>
                        </div>

                        {/* CTA */}

                        <div className="mt-12 flex flex-col gap-4 md:flex-row md:justify-end">
                            <button
                                className="
                                    inline-flex
                                    items-center
                                    justify-center
                                    gap-2
                                    rounded-2xl
                                    border
                                    border-slate-300
                                    px-8
                                    py-4
                                    font-black
                                "
                            >
                                <Save className="h-5 w-5" />

                                {isEn ? "SAVE DRAFT" : "SIMPAN DRAFT"}
                            </button>

                            <Link
                                href={route("onboarding.review-submit.store")}
                                method="post"
                                as="button"
                                className="
        inline-flex
        items-center
        justify-center
        gap-2
        rounded-2xl
        bg-emerald-600
        px-8
        py-4
        font-black
        text-white
        transition-all
        duration-200
        hover:-translate-y-0.5
        hover:bg-emerald-700
        hover:shadow-lg
        focus:outline-none
        focus:ring-2
        focus:ring-emerald-500
        focus:ring-offset-2
    "
                            >
                                {isEn
                                    ? "SUBMIT FOR VERIFICATION"
                                    : "KIRIM UNTUK VERIFIKASI"}

                                <ArrowRight className="h-5 w-5" />
                            </Link>
                        </div>
                    </div>

                    {/* Footer */}

                    <div className="mt-10 text-center">
                        <p className="text-sm text-slate-500">
                            {isEn
                                ? `Thank you, ${
                                      auth?.user?.name ?? ""
                                  }, for joining the DIGESTEX ecosystem.`
                                : `Terima kasih, ${
                                      auth?.user?.name ?? ""
                                  }, telah bergabung dengan ekosistem DIGESTEX.`}
                        </p>
                    </div>
                </div>
            </div>
        </OnboardingLayout>
    );
}
