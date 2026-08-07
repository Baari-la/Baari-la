import { router, usePage } from "@inertiajs/react";
import {
    Building2,
    Briefcase,
    Factory,
    Cog,
    Image,
    CheckCircle,
    Languages,
} from "lucide-react";

export default function OnboardingNavbar({ currentStep = 1 }) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const steps = [
        {
            id: 1,
            route: "onboarding.company-information",
            titleEn: "Company Information",
            titleId: "Informasi Perusahaan",
            icon: Building2,
        },
        {
            id: 2,
            route: "onboarding.business-information",
            titleEn: "Business Information",
            titleId: "Informasi Bisnis",
            icon: Briefcase,
        },
        {
            id: 3,
            route: "onboarding.capabilities",
            titleEn: "Capabilities",
            titleId: "Kapabilitas",
            icon: Factory,
        },
        {
            id: 4,
            route: "onboarding.manufacturing",
            titleEn: "Trade Profile",
            titleId: "Profil Perdagangan",
            icon: Cog,
        },
        {
            id: 5,
            route: "onboarding.media-catalog",
            titleEn: "Media & Catalog",
            titleId: "Media & Katalog",
            icon: Image,
        },
        {
            id: 6,
            route: "onboarding.review-submit",
            titleEn: "Review & Submit",
            titleId: "Tinjau & Kirim",
            icon: CheckCircle,
        },
    ];

    return (
        <div className="border-b border-slate-200 bg-white shadow-sm">
            <div className="mx-auto max-w-7xl px-6 py-7">
                {/* =======================================================
                    HEADER
                ======================================================= */}
                <div className="mb-8 flex items-start justify-between">
                    {/* LEFT */}
                    <div>
                        <p className="text-xs font-black uppercase tracking-[0.35em] text-emerald-600">
                            {isEn
                                ? "DIGESTEX ONBOARDING"
                                : "ONBOARDING DIGESTEX"}
                        </p>

                        <h1 className="mt-2 text-4xl font-black tracking-tight text-slate-900">
                            Digital Company Passport™
                        </h1>

                        <p className="mt-2 text-sm text-slate-500">
                            {isEn
                                ? "Build Your Digital Industry Identity™"
                                : "Bangun Identitas Digital Industri Anda™"}
                        </p>
                    </div>

                    {/* RIGHT */}
                    <div className="flex items-start gap-10">
                        {/* ===================================================
                            LANGUAGE
                        =================================================== */}
                        <div>
                            <div className="mb-2 flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-500">
                                <Languages className="h-4 w-4" />
                                {isEn ? "Language" : "Bahasa"}
                            </div>

                            <div className="flex items-center rounded-xl border border-slate-200 bg-white p-1 shadow-sm">
                                <button
                                    type="button"
                                    onClick={() =>
                                        router.post(
                                            route("language.switch", "id"),
                                            {},
                                            {
                                                preserveScroll: true,
                                                preserveState: false,
                                            },
                                        )
                                    }
                                    className={`
                                        flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold transition-all duration-300
                                        focus:outline-none focus:ring-2 focus:ring-emerald-500
                                        ${
                                            !isEn
                                                ? "bg-emerald-500 text-white shadow-sm"
                                                : "text-slate-600 hover:bg-slate-100"
                                        }
                                    `}
                                >
                                    🇮🇩 ID
                                </button>

                                <button
                                    type="button"
                                    onClick={() =>
                                        router.post(
                                            route("language.switch", "en"),
                                            {},
                                            {
                                                preserveScroll: true,
                                                preserveState: false,
                                            },
                                        )
                                    }
                                    className={`
                                        flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold transition-all duration-300
                                        focus:outline-none focus:ring-2 focus:ring-emerald-500
                                        ${
                                            isEn
                                                ? "bg-emerald-500 text-white shadow-sm"
                                                : "text-slate-600 hover:bg-slate-100"
                                        }
                                    `}
                                >
                                    🇬🇧 EN
                                </button>
                            </div>
                        </div>

                        {/* ===================================================
                            PROGRESS
                        =================================================== */}
                        <div className="min-w-[220px] text-right">
                            <div className="text-sm font-bold uppercase tracking-wide text-slate-500">
                                {isEn
                                    ? `STEP ${currentStep} OF ${steps.length}`
                                    : `LANGKAH ${currentStep} DARI ${steps.length}`}
                            </div>

                            <div className="mt-3 h-2.5 overflow-hidden rounded-full bg-slate-200">
                                <div
                                    className="h-full rounded-full bg-emerald-500 transition-all duration-500"
                                    style={{
                                        width: `${(currentStep / steps.length) * 100}%`,
                                    }}
                                />
                            </div>
                        </div>
                    </div>
                </div>

                {/* =======================================================
                    DESKTOP STEP NAVIGATION
                ======================================================= */}
                <div className="hidden grid-cols-6 gap-4 lg:grid">
                    {steps.map((step) => {
                        const Icon = step.icon;
                        const isCurrent = currentStep === step.id;
                        const isCompleted = step.id < currentStep;
                        const isAccessible = step.id <= currentStep;

                        return (
                            <button
                                type="button"
                                key={step.id}
                                disabled={!isAccessible}
                                onClick={() => {
                                    if (!isAccessible) return;

                                    router.visit(route(step.route), {
                                        preserveScroll: true,
                                    });
                                }}
                                className={`
                                    w-full text-left rounded-2xl border p-5 transition-all duration-300
                                    focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2
                                    ${
                                        isAccessible
                                            ? "cursor-pointer hover:-translate-y-1 hover:shadow-lg"
                                            : "cursor-not-allowed opacity-60"
                                    }
                                    ${
                                        isCurrent
                                            ? "border-emerald-500 bg-emerald-50 shadow-sm"
                                            : isCompleted
                                              ? "border-emerald-200 bg-white"
                                              : "border-slate-200 bg-white"
                                    }
                                `}
                            >
                                <div className="flex items-center gap-3">
                                    <div
                                        className={`
                                            rounded-xl p-2.5
                                            ${
                                                isCompleted || isCurrent
                                                    ? "bg-emerald-500 text-white"
                                                    : "bg-slate-100 text-slate-500"
                                            }
                                        `}
                                    >
                                        <Icon className="h-5 w-5" />
                                    </div>

                                    <div>
                                        <div className="text-xs font-black uppercase tracking-wide text-slate-400">
                                            {isEn
                                                ? `STEP ${step.id}`
                                                : `LANGKAH ${step.id}`}
                                        </div>

                                        <div className="mt-1 text-sm font-bold text-slate-800">
                                            {isEn ? step.titleEn : step.titleId}
                                        </div>
                                    </div>
                                </div>
                            </button>
                        );
                    })}
                </div>

                {/* =======================================================
                    MOBILE STEP NAVIGATION
                ======================================================= */}
                <div className="flex gap-2 overflow-x-auto lg:hidden">
                    {steps.map((step) => {
                        const isCurrent = currentStep === step.id;
                        const isAccessible = step.id <= currentStep;

                        return (
                            <button
                                type="button"
                                key={step.id}
                                disabled={!isAccessible}
                                onClick={() => {
                                    if (!isAccessible) return;

                                    router.visit(route(step.route), {
                                        preserveScroll: true,
                                    });
                                }}
                                className={`
                                    whitespace-nowrap rounded-full px-4 py-2 text-xs font-black transition-all duration-300
                                    focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1
                                    ${
                                        isAccessible
                                            ? "cursor-pointer hover:bg-emerald-600 hover:text-white"
                                            : "cursor-not-allowed opacity-50"
                                    }
                                    ${
                                        isCurrent
                                            ? "bg-emerald-500 text-white"
                                            : "bg-slate-100 text-slate-600"
                                    }
                                `}
                            >
                                {isEn
                                    ? `STEP ${step.id}`
                                    : `LANGKAH ${step.id}`}
                            </button>
                        );
                    })}
                </div>
            </div>
        </div>
    );
}
