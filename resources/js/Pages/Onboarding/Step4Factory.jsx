import { Head, router, useForm } from "@inertiajs/react";
import { Building2, Factory, Cpu, ArrowLeft, ArrowRight } from "lucide-react";

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";

import FactoryInformationCard from "@/Components/Onboarding/FactoryInformationCard";
import PrimaryMachineCard from "@/Components/Onboarding/PrimaryMachineCard";
import FactorySummaryCard from "@/Components/Onboarding/FactorySummaryCard";
import OnboardingProgress from "@/Components/Onboarding/OnboardingProgress";
import StepNavigation from "@/Components/Onboarding/StepNavigation";

export default function Step4Factory({ auth, passport = null }) {
    const { data, setData, processing, errors } = useForm({
        factory: {
            factory_name: passport?.factory_name ?? "",
            factory_type: passport?.factory_type ?? "MANUFACTURING",

            country: passport?.country ?? "",
            province: passport?.province ?? "",
            city: passport?.city ?? "",

            factory_established_year: passport?.factory_established_year ?? "",

            land_area_sqm: passport?.land_area_sqm ?? "",
            building_area_sqm: passport?.building_area_sqm ?? "",

            production_lines: passport?.production_lines ?? "",
            number_of_shifts: passport?.number_of_shifts ?? "",
        },

        primary_machine: {
            machine_category: passport?.primary_machine?.machine_category ?? "",

            machine_brand: passport?.primary_machine?.machine_brand ?? "",

            machine_model: passport?.primary_machine?.machine_model ?? "",

            quantity: passport?.primary_machine?.quantity ?? 1,

            year_installed: passport?.primary_machine?.year_installed ?? "",

            country_origin: passport?.primary_machine?.country_origin ?? "",
        },
    });

    function submit(e) {
        e.preventDefault();

        router.post(route("onboarding.factory.store"), data, {
            preserveScroll: true,
        });
    }

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Digital Factory Passport" />

            <div className="mx-auto max-w-7xl px-6 py-8">
                <div className="grid grid-cols-12 gap-8">
                    {/* LEFT */}

                    <div className="col-span-12 lg:col-span-8">
                        <div className="rounded-2xl border bg-white shadow-sm">
                            {/* Header */}

                            <div className="border-b p-8">
                                <div className="flex items-center gap-3">
                                    <Factory className="h-8 w-8 text-blue-600" />

                                    <div>
                                        <h1 className="text-2xl font-bold">
                                            Digital Factory Passport™
                                        </h1>

                                        <p className="mt-2 text-sm text-slate-500">
                                            Register your manufacturing facility
                                            and primary production machine to
                                            unlock Manufacturing Intelligence,
                                            Smart Business Matching and
                                            Executive Dashboard.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {/* Body */}

                            <form onSubmit={submit} className="space-y-8 p-8">
                                <FactoryInformationCard
                                    data={data.factory}
                                    setData={(value) =>
                                        setData("factory", value)
                                    }
                                    errors={errors}
                                />

                                <PrimaryMachineCard
                                    data={data.primary_machine}
                                    setData={(value) =>
                                        setData("primary_machine", value)
                                    }
                                    errors={errors}
                                />

                                {/* Navigation */}

                                <StepNavigation
                                    currentStep={4}
                                    processing={processing}
                                    onBack={() => window.history.back()}
                                />
                            </form>
                        </div>
                    </div>

                    {/* RIGHT */}

                    <div className="col-span-12 lg:col-span-4 space-y-6">
                        <OnboardingProgress currentStep={4} />

                        <FactorySummaryCard
                            factory={data.factory}
                            machine={data.primary_machine}
                        />

                        <div className="rounded-2xl border bg-gradient-to-br from-blue-50 to-white p-6">
                            <div className="flex items-center gap-2">
                                <Cpu className="h-5 w-5 text-blue-600" />

                                <h3 className="font-semibold">
                                    Manufacturing Intelligence™
                                </h3>
                            </div>

                            <ul className="mt-4 space-y-3 text-sm text-slate-600">
                                <li>✓ Executive Dashboard</li>

                                <li>✓ Factory Passport</li>

                                <li>✓ Smart Business Matching</li>

                                <li>✓ Build My Supply Chain™</li>

                                <li>✓ Capacity Intelligence</li>

                                <li>✓ Factory Verification</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
