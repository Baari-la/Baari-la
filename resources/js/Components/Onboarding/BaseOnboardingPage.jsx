import OnboardingLayout from "@/Layouts/OnboardingLayout";
import OnboardingNavbar from "@/Components/Onboarding/OnboardingNavbar";

import { Head } from "@inertiajs/react";

export default function BaseOnboardingPage({
    currentStep,

    title,

    description,

    intelligence = {},

    sidebar = null,

    navigation = null,

    banner = null,

    children,
}) {
    const {
        title: intelligenceTitle,

        description: intelligenceDescription,

        items = [],
    } = intelligence;

    return (
        <OnboardingLayout>
            <Head title={title} />

            <div className="min-h-screen bg-slate-50">
                <OnboardingNavbar currentStep={currentStep} />

                <div className="mx-auto max-w-7xl px-6 py-12">
                    <div className="rounded-3xl bg-white p-10 shadow-sm">
                        {/* ===================================================== */}
                        {/* HEADER */}
                        {/* ===================================================== */}

                        <div className="text-center">
                            <p className="text-sm font-black uppercase tracking-[0.2em] text-emerald-600">
                                STEP {currentStep}
                            </p>

                            <h1 className="mt-4 text-5xl font-black text-slate-900">
                                {title}
                            </h1>

                            {description && (
                                <p className="mx-auto mt-5 max-w-3xl leading-8 text-slate-600">
                                    {description}
                                </p>
                            )}
                        </div>

                        {/* ===================================================== */}
                        {/* OPTIONAL BANNER */}
                        {/* ===================================================== */}

                        {banner && <div className="mt-10">{banner}</div>}

                        {/* ===================================================== */}
                        {/* INTELLIGENCE */}
                        {/* ===================================================== */}

                        {intelligenceTitle && (
                            <div className="mt-10 rounded-3xl bg-indigo-50 p-8">
                                <h2 className="text-xl font-black text-indigo-700">
                                    {intelligenceTitle}
                                </h2>

                                {intelligenceDescription && (
                                    <p className="mt-3 text-sm leading-7 text-slate-600">
                                        {intelligenceDescription}
                                    </p>
                                )}

                                {items.length > 0 && (
                                    <div className="mt-6 grid gap-3 md:grid-cols-2">
                                        {items.map((item) => (
                                            <div
                                                key={item}
                                                className="flex items-center gap-2"
                                            >
                                                <span className="font-black text-emerald-600">
                                                    ✓
                                                </span>

                                                <span className="text-slate-700">
                                                    {item}
                                                </span>
                                            </div>
                                        ))}
                                    </div>
                                )}
                            </div>
                        )}

                        {/* ===================================================== */}
                        {/* CONTENT */}
                        {/* ===================================================== */}

                        <div className="mt-10 grid gap-8 lg:grid-cols-3">
                            {/* LEFT */}

                            <div className="space-y-8 lg:col-span-2">
                                {children}
                            </div>

                            {/* RIGHT */}

                            {sidebar && <div>{sidebar}</div>}
                        </div>

                        {/* ===================================================== */}
                        {/* FOOTER */}
                        {/* ===================================================== */}

                        {navigation && (
                            <div className="mt-10">{navigation}</div>
                        )}
                    </div>
                </div>
            </div>
        </OnboardingLayout>
    );
}
