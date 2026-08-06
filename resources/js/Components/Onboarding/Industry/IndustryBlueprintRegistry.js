/*
|--------------------------------------------------------------------------
| DIGESTEX Industry Blueprint Registry™
|--------------------------------------------------------------------------
|
| Central registry for every Industry Blueprint.
|
| Every blueprint defines:
|
| • Component
| • Sections
| • Capacity Units
| • Dashboard Widgets
| • Intelligence Modules
| • Calculations
| • Summary Cards
| • Smart Business Matching
|
|--------------------------------------------------------------------------
*/

import ManufacturerCapability from "../Capability/Manufacturer/ManufacturerCapability";
import QualityCapability from "../Capability/Quality/QualityCapability";
import SupportingCapability from "../Capability/Supporting/SupportingCapability";
import CommercialCapability from "../Capability/Commercial/CommercialCapability";

/*
|--------------------------------------------------------------------------
| Registry
|--------------------------------------------------------------------------
*/

const IndustryBlueprintRegistry = {
    /*
    |--------------------------------------------------------------------------
    | Manufacturer
    |--------------------------------------------------------------------------
    */

    manufacturer: {
        id: "manufacturer",

        title: "Manufacturer Capability™",

        description:
            "Configure your manufacturing capability, production capacity and operational readiness.",

        component: ManufacturerCapability,

        sections: [
            "factory",
            "production",
            "machinery",
            "capacity",
            "commercial",
            "export",
            "certification",
            "sustainability",
            "flexibility",
        ],

        units: ["ton", "kg", "meter", "yard", "roll"],

        calculations: {
            capacity: true,
            utilization: true,
            availableCapacity: true,
            productionStatus: true,
        },

        dashboard: ["capacity", "machinery", "production", "buyer_readiness"],

        intelligence: [
            "Capacity Intelligence™",
            "Technology Intelligence™",
            "Buyer Readiness™",
        ],

        smartMatching: true,
    },

    /*
    |--------------------------------------------------------------------------
    | Spinner
    |--------------------------------------------------------------------------
    */

    manufacturer_spinner: {
        id: "manufacturer_spinner",

        title: "Spinner Capability™",

        description: "Configure yarn manufacturing capability.",

        component: ManufacturerCapability,

        sections: [
            "factory",
            "production",
            "machinery",
            "capacity",
            "commercial",
            "export",
            "certification",
            "sustainability",
            "flexibility",
        ],

        units: ["ton", "kg", "cone", "bale"],

        calculations: {
            capacity: true,
            utilization: true,
            availableCapacity: true,
            productionStatus: true,
        },

        dashboard: ["capacity", "spindle", "machinery", "buyer_readiness"],

        intelligence: ["Capacity Intelligence™", "Technology Intelligence™"],

        smartMatching: true,
    },

    /*
    |--------------------------------------------------------------------------
    | Weaving
    |--------------------------------------------------------------------------
    */

    manufacturer_weaving: {
        id: "manufacturer_weaving",

        title: "Weaving Capability™",

        description: "Configure weaving capability and production capacity.",

        component: ManufacturerCapability,

        sections: [
            "factory",
            "production",
            "machinery",
            "capacity",
            "commercial",
            "export",
            "certification",
            "sustainability",
            "flexibility",
        ],

        units: ["meter", "yard", "roll", "beam"],

        calculations: {
            capacity: true,
            utilization: true,
            availableCapacity: true,
            productionStatus: true,
        },

        dashboard: ["capacity", "loom", "machinery"],

        intelligence: ["Capacity Intelligence™", "Technology Intelligence™"],

        smartMatching: true,
    },

    /*
    |--------------------------------------------------------------------------
    | Knitting
    |--------------------------------------------------------------------------
    */

    manufacturer_knitting: {
        id: "manufacturer_knitting",

        title: "Knitting Capability™",

        description: "Configure knitting capability.",

        component: ManufacturerCapability,

        sections: [
            "factory",
            "production",
            "machinery",
            "capacity",
            "commercial",
            "export",
            "certification",
            "sustainability",
            "flexibility",
        ],

        units: ["kg", "ton", "meter", "yard", "roll"],

        calculations: {
            capacity: true,
            utilization: true,
            availableCapacity: true,
            productionStatus: true,
        },

        dashboard: ["capacity", "machines"],

        intelligence: ["Capacity Intelligence™"],

        smartMatching: true,
    },

    /*
    |--------------------------------------------------------------------------
    | Garment
    |--------------------------------------------------------------------------
    */

    manufacturer_garment: {
        id: "manufacturer_garment",

        title: "Garment Capability™",

        description: "Configure garment manufacturing capability.",

        component: ManufacturerCapability,

        sections: [
            "factory",
            "production",
            "machinery",
            "capacity",
            "commercial",
            "export",
            "certification",
            "sustainability",
            "flexibility",
        ],

        units: ["pieces", "dozen", "set"],

        calculations: {
            capacity: true,
            utilization: true,
            availableCapacity: true,
            productionStatus: true,
        },

        dashboard: ["capacity", "lines", "operators"],

        intelligence: ["Capacity Intelligence™", "Buyer Readiness™"],

        smartMatching: true,
    },

    /*
    |--------------------------------------------------------------------------
    | Supporting Industry
    |--------------------------------------------------------------------------
    */

    supporting_machinery: {
        id: "supporting_machinery",

        title: "Machinery Supplier™",

        description: "Configure machinery products and services.",

        component: SupportingCapability,

        sections: ["products", "technical_support", "distribution"],

        units: ["unit", "set"],

        calculations: {},

        dashboard: ["products", "coverage"],

        intelligence: ["Technology Intelligence™"],

        smartMatching: true,
    },

    supporting_chemical: {
        id: "supporting_chemical",

        title: "Chemical Supplier™",

        description: "Configure textile chemical capability.",

        component: SupportingCapability,

        sections: ["products", "technical_support", "distribution"],

        units: ["ton", "kg", "liter", "drum"],

        calculations: {},

        dashboard: ["products"],

        intelligence: ["Chemical Intelligence™"],

        smartMatching: true,
    },

    /*
    |--------------------------------------------------------------------------
    | Quality Infrastructure
    |--------------------------------------------------------------------------
    */

    quality_laboratory: {
        id: "quality_laboratory",

        title: "Testing Laboratory™",

        description: "Configure laboratory capability.",

        component: QualityCapability,

        sections: ["laboratory", "accreditation", "certification"],

        units: [],

        calculations: {},

        dashboard: ["testing_scope", "accreditation"],

        intelligence: ["Laboratory Intelligence™"],

        smartMatching: true,
    },

    quality_certification: {
        id: "quality_certification",

        title: "Certification Body™",

        description: "Configure certification capability.",

        component: QualityCapability,

        sections: ["certification", "accreditation"],

        units: [],

        calculations: {},

        dashboard: ["certifications"],

        intelligence: ["Compliance Intelligence™"],

        smartMatching: true,
    },

    /*
    |--------------------------------------------------------------------------
    | Commercial
    |--------------------------------------------------------------------------
    */

    commercial_trader: {
        id: "commercial_trader",

        title: "Trading Company™",

        description: "Configure sourcing and trading capability.",

        component: CommercialCapability,

        sections: ["market", "buyer", "export"],

        units: [],

        calculations: {},

        dashboard: ["markets"],

        intelligence: ["Market Intelligence™"],

        smartMatching: true,
    },
};

export default IndustryBlueprintRegistry;
