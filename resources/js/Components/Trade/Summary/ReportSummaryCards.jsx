import {
    TrendingUp,
    TrendingDown,
    Globe,
    Scale,
    Package,
    Boxes,
} from "lucide-react";

import MetricCard from "@/Components/Common/Cards/MetricCard";

export default function ReportSummaryCards({ summary }) {
    return (
        <div className="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            <MetricCard
                title="Export"
                value={summary.export}
                trend={summary.exportGrowth}
                icon={TrendingUp}
                color="emerald"
                subtitle="Compared with previous year"
            />

            <MetricCard
                title="Import"
                value={summary.import}
                trend={summary.importGrowth}
                icon={TrendingDown}
                color="amber"
                subtitle="Compared with previous year"
            />

            <MetricCard
                title="Trade Balance"
                value={summary.balance}
                icon={Scale}
                color="blue"
                subtitle="Export minus Import"
            />

            <MetricCard
                title="Destination Countries"
                value={summary.countries}
                icon={Globe}
                color="indigo"
                subtitle="Export Markets"
            />

            <MetricCard
                title="HS Codes"
                value={summary.hsCodes}
                icon={Package}
                color="blue"
                subtitle="Products Covered"
            />

            <MetricCard
                title="Trade Records"
                value={summary.records}
                icon={Boxes}
                color="emerald"
                subtitle="Official Customs Data"
            />
        </div>
    );
}
