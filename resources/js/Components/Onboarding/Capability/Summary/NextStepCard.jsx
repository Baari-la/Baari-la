/*
|--------------------------------------------------------------------------
| DIGESTEX Next Step Card™
|--------------------------------------------------------------------------
|
| Guides users to complete the next recommended actions
| based on their current Capability Profile.
|
| Examples:
|
| • Complete Export Capability
| • Add Factory Images
| • Complete OEM Information
| • Add Sustainability Program
|
|--------------------------------------------------------------------------
*/

import { ArrowRight, CheckCircle2, Circle } from "lucide-react";

export default function NextStepCard({ title = "Next Step™", steps = [] }) {
    const completed = steps.filter((step) => step.completed).length;

    return (
        <div className="rounded-3xl border border-slate-200 bg-white p-7 shadow-sm">
            <div className="flex items-center justify-between">
                <div>
                    <h3 className="text-lg font-black text-slate-900">
                        {title}
                    </h3>

                    <p className="mt-1 text-sm text-slate-500">
                        Recommended actions to improve your profile.
                    </p>
                </div>

                <div className="rounded-xl bg-indigo-50 px-3 py-2 text-sm font-bold text-indigo-600">
                    {completed}/{steps.length}
                </div>
            </div>

            <div className="mt-6 space-y-4">
                {steps.length === 0 && (
                    <div className="rounded-2xl bg-emerald-50 p-5 text-sm text-emerald-700">
                        🎉 Your capability profile is complete.
                    </div>
                )}

                {steps.map((step, index) => (
                    <div
                        key={index}
                        className="flex items-start justify-between gap-4 rounded-2xl border border-slate-100 p-4 transition hover:border-indigo-200 hover:bg-slate-50"
                    >
                        <div className="flex items-start gap-3">
                            {step.completed ? (
                                <CheckCircle2 className="mt-0.5 h-5 w-5 text-emerald-500" />
                            ) : (
                                <Circle className="mt-0.5 h-5 w-5 text-slate-300" />
                            )}

                            <div>
                                <div className="font-semibold text-slate-800">
                                    {step.title}
                                </div>

                                {step.description && (
                                    <div className="mt-1 text-sm leading-6 text-slate-500">
                                        {step.description}
                                    </div>
                                )}
                            </div>
                        </div>

                        {step.impact && (
                            <div className="whitespace-nowrap rounded-full bg-indigo-100 px-3 py-1 text-xs font-bold text-indigo-700">
                                +{step.impact}
                            </div>
                        )}
                    </div>
                ))}
            </div>

            {steps.length > 0 && (
                <div className="mt-6 flex items-center justify-end">
                    <button
                        type="button"
                        className="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700"
                    >
                        Continue Improving
                        <ArrowRight className="h-4 w-4" />
                    </button>
                </div>
            )}
        </div>
    );
}
