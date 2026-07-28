import OnboardingLayout from "@/Layouts/OnboardingLayout";
import OnboardingNavbar from "@/Components/Onboarding/OnboardingNavbar";

import { Head, useForm, usePage } from "@inertiajs/react";

import {
    Briefcase,
    Calendar,
    Users,
    Globe,
    Package,
    ArrowRight,
} from "lucide-react";

export default function BusinessInformation() {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const { data, setData, post, processing } = useForm({
        company_description: "",
        established_year: "",
        employees: "",
        business_type: "",
        main_products: "",
        export_markets: "",
        hs_codes: "",
        certifications: "",
        linkedin: "",
    });

    const submit = (e) => {
        e.preventDefault();

        post(route("onboarding.business-information.store"));
    };

    return (
        <OnboardingLayout>
            <Head title="Business Information" />

            <div className="min-h-screen bg-slate-50">
                <OnboardingNavbar currentStep={2} />

                <div className="mx-auto max-w-6xl px-6 py-12">
                    <div className="rounded-3xl bg-white p-10 shadow-sm">
                        <div className="text-center">
                            <p className="text-sm font-black uppercase tracking-[0.2em] text-emerald-600">
                                STEP 2
                            </p>

                            <h1 className="mt-4 text-5xl font-black">
                                {isEn
                                    ? "Business Information"
                                    : "Informasi Bisnis"}
                            </h1>

                            <p className="mt-4 text-slate-500">
                                {isEn
                                    ? "Tell us about your business."
                                    : "Beritahu kami tentang bisnis Anda."}
                            </p>
                        </div>

                        <form onSubmit={submit} className="mt-10 space-y-6">
                            {/* Description */}

                            <div>
                                <label className="font-bold">
                                    Company Description
                                </label>

                                <textarea
                                    rows={5}
                                    value={data.company_description}
                                    onChange={(e) =>
                                        setData(
                                            "company_description",
                                            e.target.value,
                                        )
                                    }
                                    className="
                                        mt-2
                                        w-full
                                        rounded-2xl
                                        border
                                        border-slate-300
                                        p-4
                                    "
                                />
                            </div>

                            <div className="grid gap-6 md:grid-cols-2">
                                <Input
                                    icon={Calendar}
                                    label="Established Year"
                                    value={data.established_year}
                                    onChange={(e) =>
                                        setData(
                                            "established_year",
                                            e.target.value,
                                        )
                                    }
                                />

                                <Input
                                    icon={Users}
                                    label="Number of Employees"
                                    value={data.employees}
                                    onChange={(e) =>
                                        setData("employees", e.target.value)
                                    }
                                />

                                <Input
                                    icon={Briefcase}
                                    label="Business Type"
                                    value={data.business_type}
                                    onChange={(e) =>
                                        setData("business_type", e.target.value)
                                    }
                                />

                                <Input
                                    icon={Package}
                                    label="Main Products"
                                    value={data.main_products}
                                    onChange={(e) =>
                                        setData("main_products", e.target.value)
                                    }
                                />

                                <Input
                                    icon={Globe}
                                    label="Export Markets"
                                    value={data.export_markets}
                                    onChange={(e) =>
                                        setData(
                                            "export_markets",
                                            e.target.value,
                                        )
                                    }
                                />

                                <Input
                                    icon={Package}
                                    label="HS Codes"
                                    value={data.hs_codes}
                                    onChange={(e) =>
                                        setData("hs_codes", e.target.value)
                                    }
                                />

                                <Input
                                    icon={Briefcase}
                                    label="Certifications"
                                    value={data.certifications}
                                    onChange={(e) =>
                                        setData(
                                            "certifications",
                                            e.target.value,
                                        )
                                    }
                                />

                                <Input
                                    icon={Globe}
                                    label="LinkedIn"
                                    value={data.linkedin}
                                    onChange={(e) =>
                                        setData("linkedin", e.target.value)
                                    }
                                />
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
