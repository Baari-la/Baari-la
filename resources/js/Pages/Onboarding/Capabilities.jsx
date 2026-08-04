/*
|--------------------------------------------------------------------------
| DIGESTEX Capability Framework™
|--------------------------------------------------------------------------
|
| Step 3
|
| Dynamic Capability Framework generated from
| BusinessClassificationService.
|
|--------------------------------------------------------------------------
*/

import { Head, useForm, usePage } from "@inertiajs/react";

import BaseOnboardingPage from "@/Components/Onboarding/BaseOnboardingPage";

import CapabilityFactory from "@/Components/Onboarding/Capability/CapabilityFactory";
import CapabilityRouter from "@/Components/Onboarding/Capability/CapabilityRouter";
import CapabilitySummaryCard from "@/Components/Onboarding/Capability/CapabilitySummaryCard";

import StepNavigation from "@/Components/Onboarding/StepNavigation";

import { getCapabilityInitialState } from "@/Support/Capability/getCapabilityInitialState";

export default function Capabilities({
    company,

    business,
}) {
    /*
    |--------------------------------------------------------------------------
    | Locale
    |--------------------------------------------------------------------------
    */

    const { locale } = usePage().props;

    const isEn = locale === "en";

    /*
    |--------------------------------------------------------------------------
    | Framework
    |--------------------------------------------------------------------------
    */

    const framework = business?.framework ?? {};

    /*
    |--------------------------------------------------------------------------
    | Capability Blueprint™
    |--------------------------------------------------------------------------
    */

    const blueprint = CapabilityFactory(framework);

    /*
    |--------------------------------------------------------------------------
    | Form
    |--------------------------------------------------------------------------
    */

    const {
        data,

        setData,

        post,

        processing,

        errors,
    } = useForm(
        getCapabilityInitialState(
            blueprint.profile,

            company,
        ),
    );

    /*
    |--------------------------------------------------------------------------
    | Submit
    |--------------------------------------------------------------------------
    */

    const submit = (e) => {
        e.preventDefault();

        post(route("onboarding.capabilities.store"));
    };

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    return (
        <BaseOnboardingPage
            step={3}
            header={{
                title: blueprint.title,

                description: blueprint.description,

                icon: blueprint.icon,
            }}
            intelligence={{
                title: "Capability Intelligence™",

                description: blueprint.description,
            }}
            sidebar={
                <CapabilitySummaryCard
                    business={business}
                    framework={framework}
                    blueprint={blueprint}
                    data={data}
                />
            }
            navigation={
                <StepNavigation currentStep={3} processing={processing} />
            }
        >
            <Head title={blueprint.title} />

            <form onSubmit={submit}>
                <CapabilityRouter
                    business={business}
                    framework={framework}
                    blueprint={blueprint}
                    data={data}
                    setData={setData}
                    errors={errors}
                />
            </form>
        </BaseOnboardingPage>
    );
}
