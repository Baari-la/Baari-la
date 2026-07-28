import { Link, usePage, router } from "@inertiajs/react";

import { Check, Globe } from "lucide-react";

export default function ProgramNavbar({ currentStep = 1 }) {
    const { locale } = usePage().props;

    const isEn = locale === "en";
    const toggleLanguage = (lang) => {
        router.post(
            route("language.switch", {
                locale: lang,
            }),
            {},
            {
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => {},
                onError: (errors) => {},
            },
        );
    };

    const steps = [
        {
            id: 1,
            label: isEn ? "Program" : "Program",
        },

        {
            id: 2,
            label: isEn ? "Package" : "Paket",
        },

        {
            id: 3,
            label: isEn ? "Company" : "Perusahaan",
        },

        {
            id: 4,
            label: isEn ? "Review" : "Review",
        },

        {
            id: 5,
            label: isEn ? "Payment" : "Pembayaran",
        },

        {
            id: 6,
            label: isEn ? "Welcome" : "Selesai",
        },
    ];

    const percentage = Math.round((currentStep / steps.length) * 100);

    return (
        <header
            className="
                sticky
                top-0
                z-50
                border-b
                border-slate-200
                bg-white/95
                backdrop-blur
            "
        >
            <div
                className="
                    mx-auto
                    max-w-7xl
                    px-6
                    py-5
                "
            >
                {/* ======================================================
    TOP BAR
====================================================== */}

                <div className="flex items-center justify-between">
                    {/* Logo */}
                    <img
                        src="/images/logoWeb.png"
                        className="h-12 w-auto"
                        alt="Digestex Global"
                    />
                    <Link
                        href="/"
                        className="
            flex
            flex-col
        "
                    >
                        <span
                            className="
                text-xs
                font-medium
                text-slate-500
            "
                        >
                            Global Textile Intelligence Ecosystem
                        </span>
                    </Link>

                    {/* Language Switcher */}

                    <div className="flex items-center gap-3">
                        <Globe className="h-4 w-4 text-slate-500" />

                        <div
                            className="
                flex
                rounded-full
                bg-slate-100
                p-1
            "
                        >
                            <button
                                type="button"
                                onClick={() => toggleLanguage("id")}
                                className={`
                    rounded-full
                    px-4
                    py-1.5
                    text-sm
                    font-bold
                    transition-all

                    ${
                        locale === "id"
                            ? "bg-indigo-600 text-white shadow"
                            : "text-slate-500 hover:text-slate-700"
                    }
                `}
                            >
                                ID
                            </button>

                            <button
                                type="button"
                                onClick={() => toggleLanguage("en")}
                                className={`
                    rounded-full
                    px-4
                    py-1.5
                    text-sm
                    font-bold
                    transition-all

                    ${
                        locale === "en"
                            ? "bg-indigo-600 text-white shadow"
                            : "text-slate-500 hover:text-slate-700"
                    }
                `}
                            >
                                EN
                            </button>
                        </div>
                    </div>
                </div>

                {/* ======================================================
                    MOBILE
                ====================================================== */}

                <div className="mt-5 lg:hidden">
                    <div className="flex items-center justify-between">
                        <p className="font-semibold">
                            {isEn
                                ? `Step ${currentStep} of ${steps.length}`
                                : `Langkah ${currentStep} dari ${steps.length}`}
                        </p>

                        <p className="font-bold text-emerald-600">
                            {percentage}%
                        </p>
                    </div>

                    <div
                        className="
                            mt-3
                            h-2
                            rounded-full
                            bg-slate-200
                        "
                    >
                        <div
                            className="
                                h-2
                                rounded-full
                                bg-emerald-500
                                transition-all
                                duration-300
                            "
                            style={{
                                width: `${percentage}%`,
                            }}
                        />
                    </div>
                </div>

                {/* ======================================================
                    DESKTOP
                ====================================================== */}

                <div className="mt-6 hidden lg:block">
                    <div className="flex items-center">
                        {steps.map((step, index) => {
                            const completed = step.id < currentStep;

                            const active = step.id === currentStep;

                            return (
                                <div
                                    key={step.id}
                                    className="
                                            flex
                                            flex-1
                                            items-center
                                        "
                                >
                                    {/* STEP */}

                                    <div
                                        className="
                                                flex
                                                flex-col
                                                items-center
                                            "
                                    >
                                        <div
                                            className={`
                                                    flex
                                                    h-10
                                                    w-10
                                                    items-center
                                                    justify-center
                                                    rounded-full
                                                    border
                                                    text-sm
                                                    font-bold
                                                    transition

                                                    ${
                                                        completed
                                                            ? "border-emerald-500 bg-emerald-500 text-white"
                                                            : ""
                                                    }

                                                    ${
                                                        active
                                                            ? "border-indigo-600 bg-indigo-600 text-white"
                                                            : ""
                                                    }

                                                    ${
                                                        !completed && !active
                                                            ? "border-slate-300 bg-white text-slate-400"
                                                            : ""
                                                    }
                                                `}
                                        >
                                            {completed ? (
                                                <Check className="h-5 w-5" />
                                            ) : (
                                                step.id
                                            )}
                                        </div>

                                        <p
                                            className="
                                                    mt-2
                                                    text-xs
                                                    font-bold
                                                    uppercase
                                                    tracking-wide
                                                    text-slate-600
                                                "
                                        >
                                            {step.label}
                                        </p>
                                    </div>

                                    {/* CONNECTOR */}

                                    {index < steps.length - 1 && (
                                        <div
                                            className={`
                                                    mx-3
                                                    h-1
                                                    flex-1
                                                    rounded-full

                                                    ${
                                                        completed
                                                            ? "bg-emerald-500"
                                                            : "bg-slate-200"
                                                    }
                                                `}
                                        />
                                    )}
                                </div>
                            );
                        })}
                    </div>
                </div>
            </div>
        </header>
    );
}
