/**
 * Dashboard Permissions
 */

export const permissions = {
    guest: ["tradeSummary", "exportTrend"],

    member: ["tradeSummary", "exportTrend", "topCountries", "topHSCodes"],

    premium: [
        "tradeSummary",

        "exportTrend",

        "topCountries",

        "topHSCodes",

        "tradeBalance",

        "earlyWarning",

        "aiInsight",
    ],

    admin: "*",
};

export default permissions;
