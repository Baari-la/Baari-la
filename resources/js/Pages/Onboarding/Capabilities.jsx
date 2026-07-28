import OnboardingLayout from "@/Layouts/OnboardingLayout";
import OnboardingNavbar from "@/Components/Onboarding/OnboardingNavbar";

import { Head, useForm, usePage } from "@inertiajs/react";

import {
    Factory,
    Clock,
    Package,
    Globe,
    CheckCircle,
    ArrowRight,
} from "lucide-react";

export default function Capabilities() {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const { data, setData, post, processing } = useForm({
        production_capacity: "",
        monthly_capacity: "",
        moq: "",
        lead_time: "",
        production_type: "",
        export_ready: "yes",
        oem_odm: "yes",
        sustainability: "",
        sampling_service: "yes",
    });

    const submit = (e) => {
        e.preventDefault();

        post(route("onboarding.capabilities.store"));
    };

    return (
        <OnboardingLayout>
            <Head title="Capabilities" />

            <div className="min-h-screen bg-slate-50">
                <OnboardingNavbar currentStep={3} />

                <div className="mx-auto max-w-6xl px-6 py-12">
                    <div className="rounded-3xl bg-white p-10 shadow-sm">
                        <div className="text-center">
                            <p className="text-sm font-black uppercase tracking-[0.2em] text-emerald-600">
                                STEP 3
                            </p>

                            <h1 className="mt-4 text-5xl font-black">
                                {isEn ? "Capabilities" : "Kapabilitas"}
                            </h1>

                            <p className="mt-4 text-slate-500">
                                {isEn
                                    ? "Tell us what your company can do."
                                    : "Beritahu kami kemampuan perusahaan Anda."}
                            </p>
                        </div>

                        <form onSubmit={submit} className="mt-10 space-y-6">
                            <div className="grid gap-6 md:grid-cols-2">
                                <Input
                                    icon={Factory}
                                    label="Production Capacity"
                                    value={data.production_capacity}
                                    onChange={(e) =>
                                        setData(
                                            "production_capacity",
                                            e.target.value,
                                        )
                                    }
                                />

                                <Input
                                    icon={Factory}
                                    label="Monthly Capacity"
                                    value={data.monthly_capacity}
                                    onChange={(e) =>
                                        setData(
                                            "monthly_capacity",
                                            e.target.value,
                                        )
                                    }
                                />

                                <Input
                                    icon={Package}
                                    label="Minimum Order Quantity (MOQ)"
                                    value={data.moq}
                                    onChange={(e) =>
                                        setData("moq", e.target.value)
                                    }
                                />

                                <Input
                                    icon={Clock}
                                    label="Lead Time"
                                    value={data.lead_time}
                                    onChange={(e) =>
                                        setData("lead_time", e.target.value)
                                    }
                                />

                                <Input
                                    icon={Factory}
                                    label="Production Type"
                                    value={data.production_type}
                                    onChange={(e) =>
                                        setData(
                                            "production_type",
                                            e.target.value,
                                        )
                                    }
                                />

                                <Select
                                    label="Export Ready"
                                    value={data.export_ready}
                                    onChange={(e) =>
                                        setData("export_ready", e.target.value)
                                    }
                                />

                                <Select
                                    label="OEM / ODM Available"
                                    value={data.oem_odm}
                                    onChange={(e) =>
                                        setData("oem_odm", e.target.value)
                                    }
                                />

                                <Input
                                    icon={Globe}
                                    label="Sustainability Program"
                                    value={data.sustainability}
                                    onChange={(e) =>
                                        setData(
                                            "sustainability",
                                            e.target.value,
                                        )
                                    }
                                />

                                <Select
                                    label="Sampling Service"
                                    value={data.sampling_service}
                                    onChange={(e) =>
                                        setData(
                                            "sampling_service",
                                            e.target.value,
                                        )
                                    }
                                />
                            </div>

                            <div className="mt-10 rounded-3xl bg-emerald-50 p-6">
                                <div className="flex items-center gap-3">
                                    <CheckCircle className="h-6 w-6 text-emerald-600" />

                                    <div>
                                        <div className="font-black">
                                            Digital Company Passport™
                                        </div>

                                        <div className="text-sm text-slate-600">
                                            Capabilities are used for Smart
                                            Business Matching™ and Build My
                                            Supply Chain™.
                                        </div>
                                    </div>
                                </div>
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

function Select({ label, value, onChange }) {
    return (
        <div>
            <label className="font-semibold">{label}</label>

            <select
                value={value}
                onChange={onChange}
                className="
                    mt-2
                    w-full
                    rounded-xl
                    border
                    border-slate-300
                    p-3
                "
            >
                <option value="yes">Yes</option>
                <option value="no">No</option>
            </select>
        </div>
    );
}
