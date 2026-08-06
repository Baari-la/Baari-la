/*
|--------------------------------------------------------------------------
| DIGESTEX Industry Blueprint™
|--------------------------------------------------------------------------
|
| Step 3
|
| Launch Version
|
| UI menggunakan Industry Blueprint™
| Business Engine masih menggunakan Capability Engine™
| sampai proses launching selesai.
|
|--------------------------------------------------------------------------
*/

import { Head, useForm } from "@inertiajs/react";

import BaseOnboardingPage from "@/Components/Onboarding/BaseOnboardingPage";

import { getCapabilityInitialState } from "@/Support/Capability/getCapabilityInitialState";

import IndustryBlueprintFactory from "@/Components/Onboarding/Industry/IndustryBlueprintFactory";
import IndustryRouter from "@/Components/Onboarding/Industry/IndustryRouter";
import IndustryHeader from "@/Components/Onboarding/Industry/IndustryHeader";
import IndustrySummaryCard from "@/Components/Onboarding/Industry/IndustrySummaryCard";
import IndustryIntelligenceCard from "@/Components/Onboarding/Industry/IndustryIntelligenceCard";

import StepNavigation from "@/Components/Onboarding/StepNavigation";

export default function Capabilities({ company, business }) {
    /*
    |--------------------------------------------------------------------------
    | Business Category
    |--------------------------------------------------------------------------
    */

    const category = business?.primary_business_category ?? "manufacturer";

    /*
    |--------------------------------------------------------------------------
    | Industry Blueprint™
    |--------------------------------------------------------------------------
    */

    const blueprint = IndustryBlueprintFactory(business) ?? {
        title: "Industry Capability™",
        description: "",
    };

    /*
    |--------------------------------------------------------------------------
    | Form
    |--------------------------------------------------------------------------
    */

    const { data, setData, post, processing, errors } = useForm(
        getCapabilityInitialState(category, company),
    );

    /*
    |--------------------------------------------------------------------------
    | Submit
    |--------------------------------------------------------------------------
    */

    const submit = (e) => {
        e.preventDefault();

        console.log("=== STEP 3 SUBMIT ===");
        console.log(data);

        post(route("onboarding.capabilities.store"), {
            preserveScroll: true,

            onSuccess: () => {
                console.log("SUCCESS");
            },

            onError: (errors) => {
                console.log("VALIDATION ERROR");
                console.log(errors);
            },

            onFinish: () => {
                console.log("FINISHED");
            },
        });
    };

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    return (
        <>
            <Head title={blueprint.title} />

            <form onSubmit={submit}>
                <BaseOnboardingPage
                    step={3}
                    header={<IndustryHeader blueprint={blueprint} />}
                    sidebar={
                        <>
                            <IndustrySummaryCard
                                blueprint={blueprint}
                                business={business}
                                data={data}
                            />

                            <IndustryIntelligenceCard blueprint={blueprint} />
                        </>
                    }
                    navigation={
                        <StepNavigation
                            currentStep={3}
                            processing={processing}
                        />
                    }
                >
                    <IndustryRouter
                        blueprint={blueprint}
                        business={business}
                        data={data}
                        setData={setData}
                        errors={errors}
                    />
                </BaseOnboardingPage>
            </form>
        </>
    );
}
