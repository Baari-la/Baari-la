/**
 * DIGESTEX Widget Registry
 *
 * Every dashboard widget
 * is registered here.
 */

import TradeSummaryCards from "@/Components/Dashboard/TradeSummaryCards";
import ExportImportTrend from "@/Components/Dashboard/ExportImportTrend";
import TopCountries from "@/Components/Dashboard/TopCountries";
import TopHSCodes from "@/Components/Dashboard/TopHSCodes";
import TradeBalanceCard from "@/Components/Dashboard/TradeBalanceCard";
import EarlyWarningCard from "@/Components/Dashboard/EarlyWarningCard";
import AIInsightCard from "@/Components/Dashboard/AIInsightCard";

export const widgets = {
    tradeSummary: TradeSummaryCards,

    exportTrend: ExportImportTrend,

    topCountries: TopCountries,

    topHSCodes: TopHSCodes,

    tradeBalance: TradeBalanceCard,

    earlyWarning: EarlyWarningCard,

    aiInsight: AIInsightCard,
};

export default widgets;
