import MarketHeader from "./MarketHeader";
import MarketSummaryCards from "./MarketSummaryCards";
import CottonChart from "./CottonChart";
import AIMarketBrief from "./AIMarketBrief";

export default function MarketIntelligenceSection({
    marketHistory = [],
    summary = {},
}) {
    return (
        <section id="market-intelligence" className="bg-slate-50 py-24">
            <div className="mx-auto max-w-7xl px-6 lg:px-8">
                <MarketHeader />

                <div className="mt-10">
                    <MarketSummaryCards summary={summary} />
                </div>

                <div className="mt-10 grid gap-8 xl:grid-cols-3">
                    <div className="xl:col-span-2">
                        <CottonChart data={marketHistory} />
                    </div>

                    <div>
                        <AIMarketBrief />
                    </div>
                </div>
            </div>
        </section>
    );
}
