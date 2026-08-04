import { usePage, router } from "@inertiajs/react";
import { ArrowLeft, ArrowRight, Loader2 } from "lucide-react";

const routes = {
    1: {
        back: null,
        next: "onboarding.company-information",
    },
    2: {
        back: "onboarding.company-information",
        next: "onboarding.business-information",
    },
    3: {
        back: "onboarding.business-information",
        next: "onboarding.capabilities",
    },
    4: {
        back: "onboarding.capabilities",
        next: "onboarding.manufacturing",
    },
    5: {
        back: "onboarding.manufacturing",
        next: "onboarding.media-catalog",
    },
    6: {
        back: "onboarding.media-catalog",
        next: "onboarding.review-submit",
    },
};

export default function StepNavigation({ currentStep, processing = false }) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const config = routes[currentStep] ?? {};

    return (
        <div className="mt-10 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div className="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <div className="text-sm font-bold uppercase tracking-widest text-emerald-600">
                        {isEn
                            ? `Step ${currentStep} of 6`
                            : `Langkah ${currentStep} dari 6`}
                    </div>

                    <p className="mt-2 text-sm text-slate-500">
                        {isEn
                            ? "Save your progress before continuing to the next onboarding step."
                            : "Simpan progres Anda sebelum melanjutkan ke langkah berikutnya."}
                    </p>
                </div>

                <div className="flex gap-3">
                    {config.back && (
                        <button
                            type="button"
                            onClick={() => router.visit(route(config.back))}
                            className="
                                inline-flex
                                items-center
                                gap-2
                                rounded-2xl
                                border
                                border-slate-300
                                px-6
                                py-3
                                font-semibold
                                text-slate-700
                                transition
                                hover:bg-slate-100
                            "
                        >
                            <ArrowLeft className="h-5 w-5" />

                            {isEn ? "Back" : "Kembali"}
                        </button>
                    )}

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
                            py-3
                            font-bold
                            text-white
                            transition
                            hover:bg-emerald-700
                            disabled:cursor-not-allowed
                            disabled:opacity-60
                        "
                    >
                        {processing ? (
                            <>
                                <Loader2 className="h-5 w-5 animate-spin" />

                                {isEn ? "Saving..." : "Menyimpan..."}
                            </>
                        ) : (
                            <>
                                {isEn
                                    ? "Save & Continue"
                                    : "Simpan & Lanjutkan"}

                                <ArrowRight className="h-5 w-5" />
                            </>
                        )}
                    </button>
                </div>
            </div>
        </div>
    );
}
