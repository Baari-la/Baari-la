import OnboardingLayout from "@/Layouts/OnboardingLayout";
import OnboardingNavbar from "@/Components/Onboarding/OnboardingNavbar";

import FactoryInformationCard from "@/Components/Onboarding/FactoryInformationCard";
import PrimaryMachineCard from "@/Components/Onboarding/PrimaryMachineCard";
import FactorySummaryCard from "@/Components/Onboarding/FactorySummaryCard";
import StepNavigation from "@/Components/Onboarding/StepNavigation";

import { Head, useForm, usePage } from "@inertiajs/react";

export default function Manufacturing() {
    const { locale, company } = usePage().props;

    const isEn = locale === "en";

    const { data, setData, post, processing } = useForm({
        factory: {
            factory_name: company?.factory?.factory_name ?? "",

            factory_type: company?.factory?.factory_type ?? "MANUFACTURING",

            country: company?.factory?.country ?? "",

            province: company?.factory?.province ?? "",

            city: company?.factory?.city ?? "",

            production_lines: company?.factory?.production_lines ?? "",

            number_of_shifts: company?.factory?.number_of_shifts ?? "",

            quality_control_system:
                company?.factory?.quality_control_system ?? "",

            compliance_standards: company?.factory?.compliance_standards ?? "",
        },

        primary_machine: {
            machine_category: company?.primary_machine?.machine_category ?? "",

            machine_type: company?.primary_machine?.machine_type ?? "",

            machine_brand: company?.primary_machine?.machine_brand ?? "",

            machine_model: company?.primary_machine?.machine_model ?? "",

            quantity: company?.primary_machine?.quantity ?? 1,

            year_installed: company?.primary_machine?.year_installed ?? "",

            country_origin: company?.primary_machine?.country_origin ?? "",

            production_capacity:
                company?.primary_machine?.production_capacity ?? "",

            capacity_unit: company?.primary_machine?.capacity_unit ?? "",

            automation_level:
                company?.primary_machine?.automation_level ?? "SEMI_AUTOMATIC",
        },
    });

    const submit = (e) => {
        e.preventDefault();

        post(route("onboarding.manufacturing.store"));
    };

    console.dir(company);
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

                            <div className="mt-10 rounded-3xl bg-indigo-50 p-6">
                                <div className="font-black text-indigo-700">
                                    Manufacturing Intelligence™
                                </div>

                                <p className="mt-3 text-sm leading-7 text-slate-600">
                                    {isEn
                                        ? "The information you provide will power the following DIGESTEX intelligence services:"
                                        : "Informasi yang Anda berikan akan menjadi dasar bagi layanan intelijen DIGESTEX berikut:"}
                                </p>

                                <ul className="mt-5 space-y-2 text-sm text-slate-700">
                                    <li>✓ Digital Factory Passport™</li>

                                    <li>✓ Executive Dashboard™</li>

                                    <li>✓ Smart Business Matching™</li>

                                    <li>✓ Factory Verification™</li>

                                    <li>✓ Supply Chain Intelligence™</li>

                                    <li>✓ Build My Supply Chain™</li>
                                </ul>
                            </div>
                        </div>

                        <form onSubmit={submit} className="mt-10 space-y-6">
                            <div className="grid gap-8 lg:grid-cols-3">
                                <div className="space-y-8 lg:col-span-2">
                                    <FactoryInformationCard
                                        data={data.factory}
                                        setData={(factory) =>
                                            setData("factory", factory)
                                        }
                                    />

                                    <PrimaryMachineCard
                                        data={data.primary_machine}
                                        setData={(machine) =>
                                            setData("primary_machine", machine)
                                        }
                                    />
                                </div>

                                <FactorySummaryCard
                                    factory={data.factory}
                                    machine={data.primary_machine}
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
                                <StepNavigation
                                    currentStep={4}
                                    processing={processing}
                                    onBack={() => window.history.back()}
                                />
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
