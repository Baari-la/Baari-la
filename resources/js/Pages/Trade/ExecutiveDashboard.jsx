import ExecutiveOverview from "@/Components/Trade/Executive/ExecutiveOverview";
import GlobalTradeEarlyWarning from "@/Components/Trade/Executive/GlobalTradeEarlyWarning";
import GlobalTextileRadar from "@/Components/Trade/Executive/GlobalTextileRadar";
import AIExecutiveRecommendationCard from "@/Components/Trade/Executive/AIExecutiveRecommendationCard";
import CountryLeaderboard from "@/Components/Trade/Executive/CountryLeaderboard";
import ExecutiveSectorTabs from "@/Components/Trade/Executive/ExecutiveSectorTabs";
import SectorSummaryCard from "@/Components/Trade/Executive/SectorSummaryCard";
import SectorOverviewMap from "@/Components/Trade/Executive/SectorOverviewMap";
import SupplyChainIntelligence from "@/Components/Trade/Executive/SupplyChainIntelligence";
import ExecutiveExportMonitor from "@/Components/Trade/Executive/ExecutiveExportMonitor";
import TopHsLeaderboard from "@/Components/Trade/Executive/TopHsLeaderboard";

export default function ExecutiveDashboard(props) {
    return (
        <div className="mx-auto max-w-7xl space-y-6 p-6">
            {/* Executive Hero */}
            <ExecutiveSectorTabs sectors={props.sectors} />
            {/* <ExecutiveOverview {...props.executiveOverview} /> */}

            {/* Intelligence */}
            <ExecutiveOverview {...props.executiveOverview} />
            <SectorSummaryCard {...props.sectorSummary} />
            <SectorOverviewMap sector={props.executiveOverview.sector} />
            <SupplyChainIntelligence data={props.supplyChain} />
            <ExecutiveExportMonitor {...props.exportMonitor} />
            <TopHsLeaderboard {...props.hsLeaderboard} />

            <div className="grid grid-cols-1 gap-6 xl:grid-cols-2">
                <GlobalTradeEarlyWarning {...props.earlyWarning} />

                <GlobalTextileRadar radar={props.globalRadar} />
            </div>

            {/* AI Recommendation */}

            <AIExecutiveRecommendationCard
                recommendations={props.recommendations}
            />

            {/* Country Intelligence */}

            <CountryLeaderboard countries={props.countries} />
        </div>
    );
}
