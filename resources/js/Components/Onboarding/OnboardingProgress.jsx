import {
    CheckCircle2,
    Circle,
    Building2,
    FileText,
    Factory,
    Image,
    ShieldCheck,
    Flag,
} from "lucide-react";

const STEPS = [
    {
        step: 1,
        title: "Company Identity",
        description: "Verify company identity",
        icon: Building2,
    },
    {
        step: 2,
        title: "Company Profile",
        description: "Basic company information",
        icon: FileText,
    },
    {
        step: 3,
        title: "Products & Markets",
        description: "Products and export markets",
        icon: Flag,
    },
    {
        step: 4,
        title: "Factory Passport",
        description: "Factory & primary machine",
        icon: Factory,
    },
    {
        step: 5,
        title: "Media & Catalog",
        description: "Images and company catalog",
        icon: Image,
    },
    {
        step: 6,
        title: "Finish",
        description: "Verification & activation",
        icon: ShieldCheck,
    },
];

export default function OnboardingProgress({ currentStep = 1 }) {
    return (
        <div className="rounded-2xl border bg-white shadow-sm">
            {/* Header */}

            <div className="border-b px-6 py-5">
                <h2 className="text-lg font-semibold">Onboarding Progress</h2>

                <p className="mt-1 text-sm text-slate-500">
                    Complete all steps to activate your Digital Company
                    Passport.
                </p>
            </div>

            {/* Steps */}

            <div className="p-6">
                <div className="space-y-5">
                    {STEPS.map((item) => {
                        const Icon = item.icon;

                        const completed = item.step < currentStep;

                        const active = item.step === currentStep;

                        return (
                            <div
                                key={item.step}
                                className="flex items-start gap-4"
                            >
                                {/* Status */}

                                <div className="mt-0.5">
                                    {completed ? (
                                        <CheckCircle2 className="h-6 w-6 text-green-600" />
                                    ) : active ? (
                                        <div className="flex h-6 w-6 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white">
                                            {item.step}
                                        </div>
                                    ) : (
                                        <Circle className="h-6 w-6 text-slate-300" />
                                    )}
                                </div>

                                {/* Content */}

                                <div className="flex-1">
                                    <div className="flex items-center gap-2">
                                        <Icon
                                            className={`h-4 w-4 ${
                                                active
                                                    ? "text-blue-600"
                                                    : completed
                                                      ? "text-green-600"
                                                      : "text-slate-400"
                                            }`}
                                        />

                                        <h3
                                            className={`font-medium ${
                                                active
                                                    ? "text-blue-700"
                                                    : completed
                                                      ? "text-green-700"
                                                      : "text-slate-500"
                                            }`}
                                        >
                                            {item.title}
                                        </h3>
                                    </div>

                                    <p className="mt-1 text-sm text-slate-500">
                                        {item.description}
                                    </p>
                                </div>
                            </div>
                        );
                    })}
                </div>
            </div>

            {/* Footer */}

            <div className="border-t bg-slate-50 px-6 py-5">
                <div className="flex items-center justify-between">
                    <span className="text-sm text-slate-500">Progress</span>

                    <span className="font-semibold text-blue-700">
                        Step {currentStep} / {STEPS.length}
                    </span>
                </div>

                <div className="mt-4 h-2 overflow-hidden rounded-full bg-slate-200">
                    <div
                        className="h-full rounded-full bg-blue-600 transition-all duration-300"
                        style={{
                            width: `${(currentStep / STEPS.length) * 100}%`,
                        }}
                    />
                </div>
            </div>
        </div>
    );
}
