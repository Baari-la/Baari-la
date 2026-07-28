import { usePage } from "@inertiajs/react";
import {
    Building2,
    Briefcase,
    Factory,
    Cog,
    Image,
    CheckCircle,
} from "lucide-react";

export default function OnboardingNavbar({ currentStep = 1 }) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const steps = [
        {
            id: 1,
            titleEn: "Company Information",
            titleId: "Informasi Perusahaan",
            icon: Building2,
        },

        {
            id: 2,
            titleEn: "Business Information",
            titleId: "Informasi Bisnis",
            icon: Briefcase,
        },

        {
            id: 3,
            titleEn: "Capabilities",
            titleId: "Kapabilitas",
            icon: Factory,
        },

        {
            id: 4,
            titleEn: "Manufacturing",
            titleId: "Manufaktur",
            icon: Cog,
        },

        {
            id: 5,
            titleEn: "Media & Catalog",
            titleId: "Media & Katalog",
            icon: Image,
        },

        {
            id: 6,
            titleEn: "Review & Submit",
            titleId: "Tinjau & Kirim",
            icon: CheckCircle,
        },
    ];

    return (
        <div className="border-b bg-white shadow-sm">
            <div className="mx-auto max-w-7xl px-6 py-6">
                {/* Header */}

                <div className="mb-6 flex items-center justify-between">
                    <div>
                        <p className="text-xs font-black uppercase tracking-[0.3em] text-emerald-600">
                            {isEn
                                ? "DIGESTEX ONBOARDING"
                                : "ONBOARDING DIGESTEX"}
                        </p>

                        <h2 className="mt-2 text-2xl font-black text-slate-900">
                            Digital Company Passport™
                        </h2>
                    </div>

                    <div className="text-right">
                        <div className="text-sm font-bold text-slate-500">
                            {isEn
                                ? `STEP ${currentStep} OF ${steps.length}`
                                : `LANGKAH ${currentStep} DARI ${steps.length}`}
                        </div>

                        <div className="mt-2 h-2 w-48 overflow-hidden rounded-full bg-slate-200">
                            <div
                                className="h-full rounded-full bg-emerald-500 transition-all"
                                style={{
                                    width: `${
                                        (currentStep / steps.length) * 100
                                    }%`,
                                }}
                            />
                        </div>
                    </div>
                </div>

                {/* Desktop */}

                <div className="hidden grid-cols-6 gap-4 lg:grid">
                    {steps.map((step) => {
                        const Icon = step.icon;

                        const isActive = currentStep === step.id;

                        const isCompleted = currentStep > step.id;

                        return (
                            <div
                                key={step.id}
                                className={`
                                    rounded-2xl
                                    border
                                    p-4
                                    transition

                                    ${
                                        isActive
                                            ? "border-emerald-500 bg-emerald-50"
                                            : isCompleted
                                              ? "border-emerald-200 bg-white"
                                              : "border-slate-200 bg-white"
                                    }
                                `}
                            >
                                <div className="flex items-center gap-3">
                                    <div
                                        className={`
                                            rounded-xl
                                            p-2

                                            ${
                                                isCompleted
                                                    ? "bg-emerald-500 text-white"
                                                    : isActive
                                                      ? "bg-emerald-500 text-white"
                                                      : "bg-slate-100 text-slate-500"
                                            }
                                        `}
                                    >
                                        <Icon className="h-4 w-4" />
                                    </div>

                                    <div>
                                        <div className="text-xs font-black text-slate-400">
                                            STEP {step.id}
                                        </div>

                                        <div className="text-sm font-bold">
                                            {isEn ? step.titleEn : step.titleId}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        );
                    })}
                </div>

                {/* Mobile */}

                <div className="flex gap-2 overflow-x-auto lg:hidden">
                    {steps.map((step) => {
                        const isActive = currentStep === step.id;

                        return (
                            <div
                                key={step.id}
                                className={`
                                    whitespace-nowrap
                                    rounded-full
                                    px-4
                                    py-2
                                    text-xs
                                    font-black

                                    ${
                                        isActive
                                            ? "bg-emerald-500 text-white"
                                            : "bg-slate-100 text-slate-600"
                                    }
                                `}
                            >
                                {step.id}
                            </div>
                        );
                    })}
                </div>
            </div>
        </div>
    );
}
