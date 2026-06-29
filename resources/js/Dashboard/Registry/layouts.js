/**
 * Dashboard Layout Registry
 */

export const layouts = {
    executive: [
        {
            id: "summary",

            widget: "tradeSummary",

            span: 12,
        },

        {
            id: "trend",

            widget: "exportTrend",

            span: 8,
        },

        {
            id: "ai",

            widget: "aiInsight",

            span: 4,
        },
    ],

    analytics: [
        {
            widget: "topCountries",

            span: 6,
        },

        {
            widget: "topHSCodes",

            span: 6,
        },

        {
            widget: "tradeBalance",

            span: 12,
        },
    ],
};

export default layouts;
