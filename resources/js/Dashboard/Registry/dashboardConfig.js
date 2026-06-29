/**
 * Dashboard Configuration
 */

export const dashboardConfig = {
    trade: {
        title: "Trade Intelligence Dashboard",

        layout: "executive",

        permission: "guest",
    },

    market: {
        title: "Market Intelligence",

        layout: "analytics",

        permission: "member",
    },

    company: {
        title: "Company Intelligence",

        layout: "analytics",

        permission: "premium",
    },

    investment: {
        title: "Investment Intelligence",

        layout: "analytics",

        permission: "premium",
    },
};

export default dashboardConfig;
