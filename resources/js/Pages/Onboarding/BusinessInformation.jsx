import { Head, useForm, usePage } from "@inertiajs/react";

import OnboardingLayout from "@/Layouts/OnboardingLayout";
import OnboardingNavbar from "@/Components/Onboarding/OnboardingNavbar";

import BusinessInformationCard from "@/Components/Onboarding/Business/BusinessInformationCard";

export default function BusinessInformation() {
    /*
    |--------------------------------------------------------------------------
    | Page Props
    |--------------------------------------------------------------------------
    */

    const { locale, company } = usePage().props;

    /*
    |--------------------------------------------------------------------------
    | Business Information Form
    |--------------------------------------------------------------------------
    */

    const { data, setData, post, processing, errors } = useForm({
        /*
        |--------------------------------------------------------------------------
        | Company Description
        |--------------------------------------------------------------------------
        */

        business_description: company?.business_description ?? "",

        /*
        |--------------------------------------------------------------------------
        | Company Profile
        |--------------------------------------------------------------------------
        */

        year_established: company?.year_established ?? "",

        legal_entity: company?.legal_entity ?? "",

        employee_range: company?.employee_range ?? "",

        factory_count: company?.factory_count ?? "",

        /*
        |--------------------------------------------------------------------------
        | Business Activities
        |--------------------------------------------------------------------------
        */

        is_fiber_producer: company?.is_fiber_producer ?? false,

        is_spinner: company?.is_spinner ?? false,

        is_weaving: company?.is_weaving ?? false,

        is_knitting: company?.is_knitting ?? false,

        is_dyeing_finishing: company?.is_dyeing_finishing ?? false,

        is_printing: company?.is_printing ?? false,

        is_garment: company?.is_garment ?? false,

        is_trader: company?.is_trader ?? false,

        is_brand: company?.is_brand ?? false,

        is_buying_office: company?.is_buying_office ?? false,

        is_testing_laboratory: company?.is_testing_laboratory ?? false,

        is_certification_body: company?.is_certification_body ?? false,

        is_machinery_supplier: company?.is_machinery_supplier ?? false,

        is_accessories_supplier: company?.is_accessories_supplier ?? false,

        is_chemical_supplier: company?.is_chemical_supplier ?? false,

        /*
        |--------------------------------------------------------------------------
        | Business Strategy
        |--------------------------------------------------------------------------
        */

        oem: company?.oem ?? false,

        odm: company?.odm ?? false,

        obm: company?.obm ?? false,

        private_label: company?.private_label ?? false,

        /*
        |--------------------------------------------------------------------------
        | Market Coverage
        |--------------------------------------------------------------------------
        */

        domestic_market: company?.domestic_market ?? true,

        export_market: company?.export_market ?? false,

        export_experience_years: company?.export_experience_years ?? "",

        /*
        |--------------------------------------------------------------------------
        | Sustainability
        |--------------------------------------------------------------------------
        */

        esg_program: company?.esg_program ?? false,

        renewable_energy: company?.renewable_energy ?? false,

        recycled_material: company?.recycled_material ?? false,

        wastewater_treatment: company?.wastewater_treatment ?? false,

        sustainability_notes: company?.sustainability_notes ?? "",
    });

    /*
    |--------------------------------------------------------------------------
    | Submit
    |--------------------------------------------------------------------------
    */

    const submit = (event) => {
        event.preventDefault();

        post(route("onboarding.business-information.store"));
    };

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    return (
        <OnboardingLayout>
            <Head title="Business Information" />

            <div className="min-h-screen bg-slate-50">
                <OnboardingNavbar currentStep={2} />

                <div className="mx-auto max-w-7xl px-6 py-12">
                    <BusinessInformationCard
                        locale={locale}
                        company={company}
                        data={data}
                        setData={setData}
                        errors={errors}
                        processing={processing}
                        submit={submit}
                    />
                </div>
            </div>
        </OnboardingLayout>
    );
}
