import { ArrowLeft, ArrowRight, Loader2, Save } from "lucide-react";

export default function StepNavigation({
    processing = false,

    currentStep = 1,

    totalSteps = 6,

    backLabel = "Back",

    nextLabel = "Save & Continue",

    onBack,

    submit = true,

    showBack = true,

    showNext = true,
}) {
    return (
        <div className="rounded-2xl border bg-white">
            <div className="flex flex-col gap-6 p-6 lg:flex-row lg:items-center lg:justify-between">
                {/* Left */}

                <div>
                    <h3 className="font-semibold">
                        Step {currentStep} of {totalSteps}
                    </h3>

                    <p className="mt-1 text-sm text-slate-500">
                        Save your progress before continuing to the next
                        onboarding step.
                    </p>
                </div>

                {/* Right */}

                <div className="flex items-center gap-3">
                    {showBack && (
                        <button
                            type="button"
                            onClick={onBack}
                            className="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-5 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                        >
                            <ArrowLeft className="h-4 w-4" />

                            {backLabel}
                        </button>
                    )}

                    {showNext && (
                        <button
                            type={submit ? "submit" : "button"}
                            disabled={processing}
                            className="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-60"
                        >
                            {processing ? (
                                <>
                                    <Loader2 className="h-4 w-4 animate-spin" />
                                    Saving...
                                </>
                            ) : (
                                <>
                                    <Save className="h-4 w-4" />

                                    {nextLabel}

                                    <ArrowRight className="h-4 w-4" />
                                </>
                            )}
                        </button>
                    )}
                </div>
            </div>
        </div>
    );
}
