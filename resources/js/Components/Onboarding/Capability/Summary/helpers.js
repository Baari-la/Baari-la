/*
|--------------------------------------------------------------------------
| DIGESTEX Capability Summary Helpers™
|--------------------------------------------------------------------------
|
| Shared helper functions used by the Capability Sidebar.
|
| Responsible for:
|
| • Capability Score
| • Buyer Readiness
| • Operational Readiness
| • Next Steps
| • Label Formatting
|
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Capability Score
|--------------------------------------------------------------------------
*/

export function calculateCapabilityScore(data = {}) {
    const fields = [
        data.production_capacity,
        data.capacity_unit,
        data.monthly_capacity,
        data.annual_capacity,
        data.moq,
        data.moq_unit,
        data.lead_time,

        data.oem,
        data.odm,
        data.private_label,

        data.full_package,
        data.cmt,
        data.design_support,

        data.export_ready,
        data.sampling_service,

        data.production_flexibility,
        data.small_batch,
        data.fast_sampling,
        data.quick_response,

        data.custom_product_development,
    ];

    const completed = fields.filter(Boolean).length;

    return {
        completed,
        total: fields.length,
        percentage: Math.round((completed / fields.length) * 100),
    };
}

/*
|--------------------------------------------------------------------------
| Buyer Readiness
|--------------------------------------------------------------------------
*/

export function calculateBuyerReadiness(data = {}) {
    const score = calculateCapabilityScore(data).percentage;

    if (score >= 90) {
        return "excellent";
    }

    if (score >= 70) {
        return "good";
    }

    if (score >= 50) {
        return "fair";
    }

    return "starting";
}

/*
|--------------------------------------------------------------------------
| Operational Readiness
|--------------------------------------------------------------------------
*/

export function calculateOperationalReadiness(data = {}) {
    return [
        data.oem,
        data.odm,
        data.private_label,
        data.export_ready,
        data.design_support,
        data.full_package,
        data.sampling_service,
        data.production_flexibility,
    ].filter(Boolean).length;
}

/*
|--------------------------------------------------------------------------
| Next Step Recommendation
|--------------------------------------------------------------------------
*/

export function getNextCapabilitySteps(data = {}) {
    const steps = [];

    if (!data.export_ready) {
        steps.push({
            title: "Complete Export Capability",
            description: "Provide export readiness information.",
            impact: "5%",
            completed: false,
        });
    }

    if (!data.oem) {
        steps.push({
            title: "Add OEM Manufacturing",
            description: "Specify OEM production capability.",
            impact: "3%",
            completed: false,
        });
    }

    if (!data.design_support) {
        steps.push({
            title: "Add Design Support",
            description: "Show product development capability.",
            impact: "2%",
            completed: false,
        });
    }

    if (!data.sampling_service) {
        steps.push({
            title: "Enable Sampling Service",
            description: "Improve buyer confidence with sampling.",
            impact: "2%",
            completed: false,
        });
    }

    if (!data.production_flexibility) {
        steps.push({
            title: "Complete Production Flexibility",
            description: "Describe production flexibility.",
            impact: "2%",
            completed: false,
        });
    }

    return steps;
}

/*
|--------------------------------------------------------------------------
| Format Label
|--------------------------------------------------------------------------
*/

export function formatLabel(value) {
    if (!value) {
        return "-";
    }

    return value
        .replaceAll("_", " ")
        .replace(/\b\w/g, (char) => char.toUpperCase());
}

/*
|--------------------------------------------------------------------------
| Format Capacity
|--------------------------------------------------------------------------
*/

export function formatCapacity(value, unit) {
    if (!value) {
        return "-";
    }

    return unit ? `${value} ${unit}` : value;
}
