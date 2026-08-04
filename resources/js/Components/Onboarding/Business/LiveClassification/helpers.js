/*
|--------------------------------------------------------------------------
| DIGESTEX Live Classification Helpers™
|--------------------------------------------------------------------------
|
| Shared helper functions for the Live Classification sidebar.
|
| These helpers are presentation helpers only.
|
| Business rules remain in:
|
| resources/js/Support/Business/buildLiveClassification.js
|
|--------------------------------------------------------------------------
*/

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
| Category Description
|--------------------------------------------------------------------------
*/

export function getCategoryDescription(category) {
    switch (category) {
        case "manufacturer":
            return "Your company has been classified as a textile manufacturer.";

        case "quality_infrastructure":
            return "Your company provides testing, inspection, certification, or quality assurance services.";

        case "supporting_industry":
            return "Your company supports textile manufacturing through machinery, chemicals, accessories, engineering, or industrial services.";

        case "commercial":
            return "Your company focuses on commercial activities including trading, sourcing, buying office operations, or brand management.";

        default:
            return "Select one or more business activities to let DIGESTEX classify your company automatically.";
    }
}

/*
|--------------------------------------------------------------------------
| Industry Description
|--------------------------------------------------------------------------
*/

export function getIndustryDescription(type) {
    switch (type) {
        case "textile_manufacturer":
            return "DIGESTEX identifies your company as a textile manufacturing business.";

        case "quality_services":
            return "DIGESTEX identifies your company as a quality infrastructure organization.";

        case "textile_supporting":
            return "DIGESTEX identifies your company as a supporting industry provider.";

        case "commercial_services":
            return "DIGESTEX identifies your company as a commercial business.";

        default:
            return "Industry type will be determined automatically.";
    }
}

/*
|--------------------------------------------------------------------------
| Value Chain Description
|--------------------------------------------------------------------------
*/

export function getValueChainDescription(chain) {
    switch (chain) {
        case "upstream":
            return "Raw materials, fibers and yarn manufacturing.";

        case "midstream":
            return "Fabric production and textile processing.";

        case "downstream":
            return "Finished products and apparel manufacturing.";

        case "supporting":
            return "Supporting products and industrial services.";

        case "commercial":
            return "Trading, sourcing and buying activities.";

        default:
            return "Waiting for business classification.";
    }
}

/*
|--------------------------------------------------------------------------
| Value Chain Progress
|--------------------------------------------------------------------------
*/

export function getValueChainProgress(chain) {
    switch (chain) {
        case "upstream":
            return 20;

        case "midstream":
            return 55;

        case "downstream":
            return 90;

        case "supporting":
            return 50;

        case "commercial":
            return 100;

        default:
            return 0;
    }
}

/*
|--------------------------------------------------------------------------
| Value Chain Color
|--------------------------------------------------------------------------
*/

export function getValueChainColor(chain) {
    switch (chain) {
        case "upstream":
            return "bg-blue-500";

        case "midstream":
            return "bg-violet-500";

        case "downstream":
            return "bg-emerald-500";

        case "supporting":
            return "bg-amber-500";

        case "commercial":
            return "bg-indigo-500";

        default:
            return "bg-slate-300";
    }
}

/*
|--------------------------------------------------------------------------
| Framework Status
|--------------------------------------------------------------------------
*/

export function isFrameworkReady(classification = {}) {
    return (
        classification.capabilityProfile &&
        classification.capabilityProfile !== "general"
    );
}

/*
|--------------------------------------------------------------------------
| Module Status
|--------------------------------------------------------------------------
*/

export function isModuleReady(modules = []) {
    return modules.length > 0;
}

/*
|--------------------------------------------------------------------------
| Module Count
|--------------------------------------------------------------------------
*/

export function moduleCount(modules = []) {
    return modules.length;
}

/*
|--------------------------------------------------------------------------
| Next Step
|--------------------------------------------------------------------------
*/

export function getNextStep(classification = {}) {
    if (
        !classification.capabilityProfile ||
        classification.capabilityProfile === "general"
    ) {
        return "Business Information";
    }

    return "Capability Profile™";
}

/*
|--------------------------------------------------------------------------
| Intelligence Message
|--------------------------------------------------------------------------
*/

export function getIntelligenceMessage(classification = {}) {
    if (
        !classification.primaryCategory ||
        classification.primaryCategory === "general"
    ) {
        return "Waiting for business classification.";
    }

    return "DIGESTEX Decision Engine™ has analyzed your business and prepared the most appropriate capability framework.";
}

/*
|--------------------------------------------------------------------------
| AI Readiness
|--------------------------------------------------------------------------
*/

export function calculateReadiness(classification = {}) {
    let score = 0;

    if (classification.primaryCategory !== "general") score += 20;

    if (classification.industryType !== "general") score += 20;

    if (classification.valueChain !== "general") score += 20;

    if (classification.capabilityProfile !== "general") score += 20;

    if ((classification.modules ?? []).length > 0) score += 20;

    return score;
}

/*
|--------------------------------------------------------------------------
| Readiness Label
|--------------------------------------------------------------------------
*/

export function getReadinessLabel(score) {
    if (score >= 100) {
        return "Ready";
    }

    if (score >= 80) {
        return "Excellent";
    }

    if (score >= 60) {
        return "Good";
    }

    if (score >= 40) {
        return "Developing";
    }

    return "Waiting";
}
