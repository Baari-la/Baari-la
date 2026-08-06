/*
|--------------------------------------------------------------------------
| DIGESTEX Company Blueprint Registry™
|--------------------------------------------------------------------------
|
| Central registry for Company Identity & Business Locations.
|
| Launch Version
|
| Supported Location Types:
|
| - Head Office
| - Factory
| - Warehouse
| - Branch Office
|
| Future:
|
| - Sales Office
| - Representative Office
| - Distribution Center
| - R&D Center
| - Laboratory
|
|--------------------------------------------------------------------------
*/

import { Building2, Factory, Warehouse, Building } from "lucide-react";

const CompanyBlueprintRegistry = {
    /*
    |--------------------------------------------------------------------------
    | Head Office
    |--------------------------------------------------------------------------
    */

    head_office: {
        key: "head_office",

        title: "Head Office",

        titleId: "Kantor Pusat",

        icon: Building2,

        multiple: false,

        required: true,

        color: "emerald",
    },

    /*
    |--------------------------------------------------------------------------
    | Factory
    |--------------------------------------------------------------------------
    */

    factory: {
        key: "factory",

        title: "Factory",

        titleId: "Pabrik",

        icon: Factory,

        multiple: true,

        required: false,

        color: "blue",
    },

    /*
    |--------------------------------------------------------------------------
    | Warehouse
    |--------------------------------------------------------------------------
    */

    warehouse: {
        key: "warehouse",

        title: "Warehouse",

        titleId: "Gudang",

        icon: Warehouse,

        multiple: true,

        required: false,

        color: "amber",
    },

    /*
    |--------------------------------------------------------------------------
    | Branch Office
    |--------------------------------------------------------------------------
    */

    branch: {
        key: "branch",

        title: "Branch Office",

        titleId: "Kantor Cabang",

        icon: Building,

        multiple: true,

        required: false,

        color: "purple",
    },
};

export default CompanyBlueprintRegistry;
