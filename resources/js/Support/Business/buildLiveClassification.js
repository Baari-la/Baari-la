/*
|--------------------------------------------------------------------------
| DIGESTEX Frontend Decision Engine™
|--------------------------------------------------------------------------
|
| Live Classification Builder
|
| Mirrors BusinessClassificationService on the backend.
|
| This file NEVER talks to the server.
|
|--------------------------------------------------------------------------
*/

export default function buildLiveClassification(data = {}) {
    /*
    |--------------------------------------------------------------------------
    | Core Classification
    |--------------------------------------------------------------------------
    */

    const primaryCategory = detectPrimaryCategory(data);

    const industryType = detectIndustryType(primaryCategory);

    const lineOfBusiness = detectLineOfBusiness(data);

    const primaryLineOfBusiness = detectPrimaryLineOfBusiness(lineOfBusiness);

    const valueChain = detectValueChain(data, primaryCategory);

    /*
    |--------------------------------------------------------------------------
    | Framework
    |--------------------------------------------------------------------------
    */

    const capabilityProfile = detectCapabilityProfile(
        primaryCategory,
        primaryLineOfBusiness,
    );

    const manufacturingProfile = detectManufacturingProfile(
        primaryCategory,
        primaryLineOfBusiness,
    );

    const modules = detectModules(capabilityProfile);

    /*
    |--------------------------------------------------------------------------
    | Output
    |--------------------------------------------------------------------------
    */

    return {
        primaryCategory,

        industryType,

        valueChain,

        lineOfBusiness,

        primaryBusiness: primaryLineOfBusiness,

        capabilityProfile,

        manufacturingProfile,

        modules,
    };
}

/* ==========================================================================
|--------------------------------------------------------------------------
| Primary Category
|--------------------------------------------------------------------------
========================================================================== */

function detectPrimaryCategory(data) {
    if (
        data.is_fiber_producer ||
        data.is_spinner ||
        data.is_weaving ||
        data.is_knitting ||
        data.is_dyeing_finishing ||
        data.is_printing ||
        data.is_garment
    ) {
        return "manufacturer";
    }

    if (data.is_testing_laboratory || data.is_certification_body) {
        return "quality_infrastructure";
    }

    if (
        data.is_machinery_supplier ||
        data.is_accessories_supplier ||
        data.is_chemical_supplier
    ) {
        return "supporting_industry";
    }

    if (data.is_trader || data.is_brand || data.is_buying_office) {
        return "commercial";
    }

    return "general";
}

/* ==========================================================================
|--------------------------------------------------------------------------
| Industry Type
|--------------------------------------------------------------------------
========================================================================== */

function detectIndustryType(primary) {
    return (
        {
            manufacturer: "textile_manufacturer",

            quality_infrastructure: "quality_services",

            supporting_industry: "textile_supporting",

            commercial: "commercial_services",
        }[primary] ?? "general"
    );
}

/* ==========================================================================
|--------------------------------------------------------------------------
| Line Of Business
|--------------------------------------------------------------------------
========================================================================== */

function detectLineOfBusiness(data) {
    const lines = [];

    if (data.is_fiber_producer) lines.push("fiber");

    if (data.is_spinner) lines.push("spinner");

    if (data.is_weaving) lines.push("weaving");

    if (data.is_knitting) lines.push("knitting");

    if (data.is_dyeing_finishing) lines.push("dyeing_finishing");

    if (data.is_printing) lines.push("printing");

    if (data.is_garment) lines.push("garment");

    if (data.is_testing_laboratory) lines.push("testing_laboratory");

    if (data.is_certification_body) lines.push("certification_body");

    if (data.is_machinery_supplier) lines.push("machinery_supplier");

    if (data.is_accessories_supplier) lines.push("accessories_supplier");

    if (data.is_chemical_supplier) lines.push("chemical_supplier");

    if (data.is_trader) lines.push("trader");

    if (data.is_brand) lines.push("brand_owner");

    if (data.is_buying_office) lines.push("buying_office");

    return lines.length ? lines : ["general"];
}

/* ==========================================================================
|--------------------------------------------------------------------------
| Primary Line Of Business
|--------------------------------------------------------------------------
========================================================================== */

function detectPrimaryLineOfBusiness(lines) {
    return lines[0] ?? "general";
}

/* ==========================================================================
|--------------------------------------------------------------------------
| Value Chain
|--------------------------------------------------------------------------
========================================================================== */

function detectValueChain(data, primary) {
    if (data.is_fiber_producer || data.is_spinner) {
        return "upstream";
    }

    if (
        data.is_weaving ||
        data.is_knitting ||
        data.is_dyeing_finishing ||
        data.is_printing
    ) {
        return "midstream";
    }

    if (data.is_garment || data.is_brand) {
        return "downstream";
    }

    if (primary === "supporting_industry") {
        return "supporting";
    }

    if (primary === "quality_infrastructure") {
        return "supporting";
    }

    if (primary === "commercial") {
        return "commercial";
    }

    return "general";
}

/* ==========================================================================
|--------------------------------------------------------------------------
| Capability Profile
|--------------------------------------------------------------------------
========================================================================== */

function detectCapabilityProfile(primary, line) {
    const map = {
        fiber: "manufacturer_fiber",

        spinner: "manufacturer_spinner",

        weaving: "manufacturer_weaving",

        knitting: "manufacturer_knitting",

        dyeing_finishing: "manufacturer_dyeing",

        printing: "manufacturer_printing",

        garment: "manufacturer_garment",

        testing_laboratory: "quality_laboratory",

        certification_body: "quality_certification",

        machinery_supplier: "supporting_machinery",

        accessories_supplier: "supporting_accessories",

        chemical_supplier: "supporting_chemical",

        trader: "commercial_trader",

        brand_owner: "commercial_brand",

        buying_office: "commercial_buying_office",
    };

    return map[line] ?? primary;
}

/* ==========================================================================
|--------------------------------------------------------------------------
| Manufacturing Profile
|--------------------------------------------------------------------------
========================================================================== */

function detectManufacturingProfile(primary, line) {
    const map = {
        fiber: "fiber_factory",

        spinner: "spinning_factory",

        weaving: "weaving_factory",

        knitting: "knitting_factory",

        dyeing_finishing: "dyeing_factory",

        printing: "printing_factory",

        garment: "garment_factory",

        testing_laboratory: "laboratory_facility",

        machinery_supplier: "machinery_facility",
    };

    return map[line] ?? primary;
}

/* ==========================================================================
|--------------------------------------------------------------------------
| Modules
|--------------------------------------------------------------------------
========================================================================== */

function detectModules(profile) {
    const map = {
        manufacturer_spinner: [
            "capacity",
            "spindle",
            "fiber",
            "count_range",
            "commercial",
        ],

        manufacturer_weaving: ["loom", "fabric", "capacity", "commercial"],

        manufacturer_knitting: ["machine", "fabric", "capacity", "commercial"],

        manufacturer_garment: [
            "production",
            "capacity",
            "buyers",
            "commercial",
        ],

        quality_laboratory: [
            "testing",
            "laboratory",
            "accreditation",
            "certification",
        ],

        supporting_machinery: [
            "products",
            "technical_support",
            "distribution",
            "after_sales",
        ],
    };

    return map[profile] ?? ["overview"];
}
