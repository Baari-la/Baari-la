/*
|--------------------------------------------------------------------------
| DIGESTEX Company Blueprint Factory™
|--------------------------------------------------------------------------
|
| Generates the Company Identity Blueprint used by
| Step 1 (Company Identity & Business Locations™).
|
| This factory converts the registry into a UI-ready blueprint.
|
| Launch Version
|--------------------------------------------------------------------------
*/

import CompanyBlueprintRegistry from "./CompanyBlueprintRegistry";

export default function CompanyBlueprintFactory() {
    /*
    |--------------------------------------------------------------------------
    | Registry
    |--------------------------------------------------------------------------
    */

    const locations = Object.values(CompanyBlueprintRegistry);

    /*
    |--------------------------------------------------------------------------
    | Build Blueprint
    |--------------------------------------------------------------------------
    */

    return {
        /*
        |--------------------------------------------------------------------------
        | Identity
        |--------------------------------------------------------------------------
        */

        title: "Company Identity™",

        titleId: "Identitas Perusahaan™",

        subtitle: "Company Identity & Business Locations™",

        subtitleId: "Identitas Perusahaan & Lokasi Bisnis™",

        description:
            "Build your Digital Company Passport™ by completing your company identity and operational locations.",

        descriptionId:
            "Bangun Digital Company Passport™ dengan melengkapi identitas perusahaan dan lokasi operasional.",

        /*
        |--------------------------------------------------------------------------
        | Sections
        |--------------------------------------------------------------------------
        */

        sections: {
            company_identity: true,

            head_office: true,

            business_locations: true,
        },

        /*
        |--------------------------------------------------------------------------
        | Supported Locations
        |--------------------------------------------------------------------------
        */

        locations,

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        statistics: {
            totalLocationTypes: locations.length,

            requiredLocations: locations.filter((item) => item.required).length,

            optionalLocations: locations.filter((item) => !item.required)
                .length,
        },
    };
}
