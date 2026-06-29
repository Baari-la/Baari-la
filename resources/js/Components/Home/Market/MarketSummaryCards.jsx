import { DollarSign, Package, Ship, Droplets } from "lucide-react";

import CurrencyCard from "./CurrencyCard";
import FreightCard from "./FreightCard";
import ContainerCard from "./ContainerCard";
import OilCard from "./OilCard";

export default function MarketSummaryCards({ summary = {} }) {
    return (
        <div className="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            <CurrencyCard
                icon={DollarSign}
                title="USD / IDR"
                value={summary.usd ?? "16,245"}
                change={summary.usdChange ?? "-0.3%"}
            />

            <FreightCard
                icon={Ship}
                title="Freight"
                value={summary.freight ?? "1,240"}
                change={summary.freightChange ?? "+1.8%"}
            />

            <ContainerCard
                icon={Package}
                title="Container"
                value={summary.container ?? "1,456"}
                change={summary.containerChange ?? "+3.2%"}
            />

            <OilCard
                icon={Droplets}
                title="Crude Oil"
                value={summary.oil ?? "68.40"}
                change={summary.oilChange ?? "-0.8%"}
            />
        </div>
    );
}
