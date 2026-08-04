/*
|--------------------------------------------------------------------------
| Capability Initial State
|--------------------------------------------------------------------------
|
| Generates the initial useForm() state based on the company's
| primary business category.
|
*/

export function getCapabilityInitialState(
    category = "manufacturer",
    company = {},
) {
    const states = {
        /*
        |--------------------------------------------------------------------------
        | Manufacturer
        |--------------------------------------------------------------------------
        */

        manufacturer: {
            /*
            | Capacity Intelligence™
            */

            production_capacity: company?.production_capacity ?? "",

            capacity_unit: company?.capacity_unit ?? "",

            current_utilized_capacity: company?.current_utilized_capacity ?? "",

            current_utilized_capacity_unit:
                company?.current_utilized_capacity_unit ??
                company?.capacity_unit ??
                "",

            monthly_capacity: company?.monthly_capacity ?? "",

            annual_capacity: company?.annual_capacity ?? "",

            /*
            | Commercial
            */

            moq: company?.moq ?? "",

            moq_unit: company?.moq_unit ?? "",

            lead_time: company?.lead_time ?? "",

            /*
            | Manufacturing Services
            */

            oem: company?.oem ?? false,

            odm: company?.odm ?? false,

            private_label: company?.private_label ?? false,

            full_package: company?.full_package ?? false,

            cmt: company?.cmt ?? false,

            design_support: company?.design_support ?? false,

            /*
            | Production Flexibility
            */

            export_ready: company?.export_ready ?? false,

            sampling_service: company?.sampling_service ?? false,

            production_flexibility: company?.production_flexibility ?? false,

            supports_small_batch: company?.supports_small_batch ?? false,

            supports_fast_sampling: company?.supports_fast_sampling ?? false,

            supports_quick_response: company?.supports_quick_response ?? false,

            supports_custom_development:
                company?.supports_custom_development ?? false,
        },

        /*
        |--------------------------------------------------------------------------
        | Quality Infrastructure
        |--------------------------------------------------------------------------
        */

        quality_infrastructure: {
            laboratory_services: company?.laboratory_services ?? [],

            testing_services: company?.testing_services ?? [],

            inspection_services: company?.inspection_services ?? [],

            certification_services: company?.certification_services ?? [],

            accreditation: company?.accreditation ?? [],

            calibration_services: company?.calibration_services ?? false,

            onsite_testing: company?.onsite_testing ?? false,

            digital_reports: company?.digital_reports ?? false,

            international_recognition:
                company?.international_recognition ?? false,

            export_ready: company?.export_ready ?? false,
        },

        /*
        |--------------------------------------------------------------------------
        | Supporting Industry
        |--------------------------------------------------------------------------
        */

        supporting_industry: {
            product_categories: company?.product_categories ?? [],

            brands: company?.brands ?? "",

            technical_support: company?.technical_support ?? false,

            installation_service: company?.installation_service ?? false,

            maintenance_service: company?.maintenance_service ?? false,

            spare_parts: company?.spare_parts ?? false,

            training: company?.training ?? false,

            distribution_coverage: company?.distribution_coverage ?? "",

            export_ready: company?.export_ready ?? false,
        },

        /*
        |--------------------------------------------------------------------------
        | Commercial
        |--------------------------------------------------------------------------
        */

        commercial: {
            buyer_network: company?.buyer_network ?? "",

            sourcing_services: company?.sourcing_services ?? false,

            private_label: company?.private_label ?? false,

            market_coverage: company?.market_coverage ?? [],

            export_regions: company?.export_regions ?? [],

            export_ready: company?.export_ready ?? false,

            multilingual_team: company?.multilingual_team ?? false,

            international_logistics: company?.international_logistics ?? false,
        },
    };

    return states[category] ?? states.manufacturer;
}
