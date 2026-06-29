import DashboardBuilder from "@/Components/Dashboard/DashboardBuilder";
import DashboardRow from "@/Components/Dashboard/DashboardRow";
import DashboardColumn from "@/Components/Dashboard/DashboardColumn";

import PageSection from "@/Components/Common/Theme/PageSection";
import ContentContainer from "@/Components/Common/Theme/ContentContainer";

import ReportHeader from "./Summary/ReportHeader";
import ExecutiveSummary from "./Intelligence/ExecutiveSummary";
import KeyFindings from "./Intelligence/KeyFindings";
import ReportPeriod from "./Summary/ReportPeriod";
import ReportSummaryCards from "./Summary/ReportSummaryCards";

import ExportImportComparisonChart from "./Charts/ExportImportComparisonChart";
import TradeRadar from "./Intelligence/TradeRadar";

import TopDestinationGrid from "./Countries/TopDestinationGrid";
import ProductPerformanceTable from "./Products/ProductPerformanceTable";

import OpportunityCard from "./Intelligence/OpportunityCard";
import RiskCard from "./Intelligence/RiskCard";
import RecommendationCard from "./Intelligence/RecommendationCard";
import ExecutivePerformanceTable from "./ExecutiveReport/Summary/ExecutivePerformanceTable";
import ExecutivePerformancePiecesTable from "./ExecutiveReport/Summary/ExecutivePerformancePiecesTable";
import ReportFooter from "./Footer/ReportFooter";

export default function ExecutiveReport({ report }) {
    // console.log("Executive Report", report);
    return (
        <PageSection spacing="large">
            <ContentContainer size="7xl">
                <DashboardBuilder>
                    {/* =====================================================
                        REPORT HEADER
                    ====================================================== */}

                    <ReportHeader
                        title={report.title}
                        subtitle={report.subtitle}
                        reportNumber={report.reportNumber}
                        generatedAt={report.generatedAt}
                    />
                    {/* Executive Performance Summary */}
                    <div className="mt-8">
                        <ExecutivePerformanceTable data={report.comparison} />
                    </div>

                    <ExecutivePerformancePiecesTable
                        data={report.comparisonPieces}
                    />
                    {/* =====================================================
                        EXECUTIVE SUMMARY
                    ====================================================== */}

                    <div className="mt-8">
                        <ExecutiveSummary summary={report.executiveSummary} />
                    </div>

                    {/* =====================================================
                        KEY FINDINGS
                    ====================================================== */}

                    <div className="mt-8">
                        <KeyFindings findings={report.keyFindings} />
                    </div>

                    {/* =====================================================
                        REPORT PERIOD
                    ====================================================== */}

                    <div className="mt-8">
                        <ReportPeriod
                            country={report.country}
                            period={report.period}
                            compare={report.compare}
                        />
                    </div>

                    {/* =====================================================
                        SUMMARY KPI
                    ====================================================== */}

                    <div className="mt-8">
                        <ReportSummaryCards summary={report.summary} />
                    </div>

                    {/* =====================================================
                        EXPORT vs IMPORT
                    ====================================================== */}

                    <DashboardRow>
                        <DashboardColumn span={8}>
                            <ExecutivePerformanceTable
                                data={report.comparison}
                            />
                        </DashboardColumn>

                        {/* <DashboardColumn span={4}>
                            <TradeRadar radar={report.tradeRadar} />
                        </DashboardColumn> */}
                    </DashboardRow>

                    {/* =====================================================
                        COUNTRIES vs PRODUCTS
                    ====================================================== */}

                    <DashboardRow>
                        <DashboardColumn span={7}>
                            <TopDestinationGrid
                                countries={report.topCountries}
                            />
                        </DashboardColumn>

                        <DashboardColumn span={5}>
                            <ProductPerformanceTable
                                products={report.topProducts}
                            />
                        </DashboardColumn>
                    </DashboardRow>

                    {/* =====================================================
                        OPPORTUNITY vs RISK
                    ====================================================== */}

                    <DashboardRow>
                        <DashboardColumn span={6}>
                            <OpportunityCard
                                opportunities={report.opportunities}
                            />
                        </DashboardColumn>

                        <DashboardColumn span={6}>
                            <RiskCard risks={report.risks} />
                        </DashboardColumn>
                    </DashboardRow>

                    {/* =====================================================
                        EXECUTIVE RECOMMENDATION
                    ====================================================== */}

                    <div className="mt-10">
                        <RecommendationCard
                            recommendation={report.recommendation}
                        />
                    </div>

                    {/* =====================================================
                        REPORT FOOTER
                    ====================================================== */}

                    <div className="mt-10">
                        <ReportFooter report={report} />
                    </div>
                </DashboardBuilder>
            </ContentContainer>
        </PageSection>
    );
}
