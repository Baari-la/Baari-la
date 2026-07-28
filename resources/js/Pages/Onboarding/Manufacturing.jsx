import OnboardingLayout from "@/Layouts/OnboardingLayout";
import OnboardingNavbar from "@/Components/Onboarding/OnboardingNavbar";

import { Head, useForm, usePage } from "@inertiajs/react";

import {
    Factory,
    Cog,
    Calendar,
    Globe,
    Shield,
    ArrowRight,
} from "lucide-react";

export default function Manufacturing() {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const { data, setData, post, processing } = useForm({
        machinery_category: "",
        machinery_brand: "",
        machinery_quantity: "",
        year_installed: "",
        country_origin: "",
        factory_area: "",
        production_lines: "",
        shifts: "",
        qc_system: "",
        compliance: "",
    });

    const submit = (e) => {
        e.preventDefault();

        post(route("onboarding.manufacturing.store"));
    };

    return (
        <OnboardingLayout>
            <Head title="Manufacturing" />

            <div className="min-h-screen bg-slate-50">
                <OnboardingNavbar currentStep={4} />

                <div className="mx-auto max-w-6xl px-6 py-12">
                    <div className="rounded-3xl bg-white p-10 shadow-sm">
                        <div className="text-center">
                            <p className="text-sm font-black uppercase tracking-[0.2em] text-emerald-600">
                                STEP 4
                            </p>

                            <h1 className="mt-4 text-5xl font-black">
                                {isEn ? "Manufacturing" : "Manufaktur"}
                            </h1>

                            <p className="mt-4 text-slate-500">
                                {isEn
                                    ? "Tell us about your manufacturing capabilities."
                                    : "Beritahu kami tentang fasilitas manufaktur perusahaan Anda."}
                            </p>
                        </div>

                        <form onSubmit={submit} className="mt-10 space-y-6">
                            <div className="grid gap-6 md:grid-cols-2">
                                <Input
                                    icon={Factory}
                                    label="Machinery Category"
                                    value={data.machinery_category}
                                    onChange={(e) =>
                                        setData(
                                            "machinery_category",
                                            e.target.value,
                                        )
                                    }
                                />

                                <Input
                                    icon={Cog}
                                    label="Machinery Brand"
                                    value={data.machinery_brand}
                                    onChange={(e) =>
                                        setData(
                                            "machinery_brand",
                                            e.target.value,
                                        )
                                    }
                                />

                                <Input
                                    icon={Cog}
                                    label="Quantity"
                                    value={data.machinery_quantity}
                                    onChange={(e) =>
                                        setData(
                                            "machinery_quantity",
                                            e.target.value,
                                        )
                                    }
                                />

                                <Input
                                    icon={Calendar}
                                    label="Year Installed"
                                    value={data.year_installed}
                                    onChange={(e) =>
                                        setData(
                                            "year_installed",
                                            e.target.value,
                                        )
                                    }
                                />

                                <Input
                                    icon={Globe}
                                    label="Country of Origin"
                                    value={data.country_origin}
                                    onChange={(e) =>
                                        setData(
                                            "country_origin",
                                            e.target.value,
                                        )
                                    }
                                />

                                <Input
                                    icon={Factory}
                                    label="Factory Area (m²)"
                                    value={data.factory_area}
                                    onChange={(e) =>
                                        setData("factory_area", e.target.value)
                                    }
                                />

                                <Input
                                    icon={Factory}
                                    label="Production Lines"
                                    value={data.production_lines}
                                    onChange={(e) =>
                                        setData(
                                            "production_lines",
                                            e.target.value,
                                        )
                                    }
                                />

                                <Input
                                    icon={Factory}
                                    label="Number of Shifts"
                                    value={data.shifts}
                                    onChange={(e) =>
                                        setData("shifts", e.target.value)
                                    }
                                />

                                <Input
                                    icon={Shield}
                                    label="Quality Control System"
                                    value={data.qc_system}
                                    onChange={(e) =>
                                        setData("qc_system", e.target.value)
                                    }
                                />

                                <Input
                                    icon={Shield}
                                    label="Compliance Standards"
                                    value={data.compliance}
                                    onChange={(e) =>
                                        setData("compliance", e.target.value)
                                    }
                                />
                            </div>

                            <div className="mt-10 rounded-3xl bg-indigo-50 p-6">
                                <div className="font-black text-indigo-700">
                                    Manufacturing Intelligence™
                                </div>

                                <p className="mt-2 text-sm text-slate-600">
                                    DIGESTEX uses manufacturing data to power
                                    Company Intelligence, Smart Business
                                    Matching™, and Build My Supply Chain™.
                                </p>
                            </div>

                            <div className="flex justify-end">
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
                                        py-4
                                        font-black
                                        text-white
                                    "
                                >
                                    {isEn ? "CONTINUE" : "LANJUTKAN"}

                                    <ArrowRight className="h-5 w-5" />
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </OnboardingLayout>
    );
}

function Input({ icon: Icon, label, value, onChange }) {
    return (
        <div>
            <label className="font-semibold">{label}</label>

            <div className="relative mt-2">
                <Icon className="absolute left-3 top-3.5 h-5 w-5 text-slate-400" />

                <input
                    value={value}
                    onChange={onChange}
                    className="
                        w-full
                        rounded-xl
                        border
                        border-slate-300
                        py-3
                        pl-11
                        pr-4
                    "
                />
            </div>
        </div>
    );
}
