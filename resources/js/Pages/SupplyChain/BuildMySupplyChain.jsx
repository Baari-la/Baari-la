import SupplyChainHeader from "@/Components/SupplyChain/SupplyChainHeader";
import SupplyChainStage from "@/Components/SupplyChain/SupplyChainStage";
import SupplyChainGraph from "@/Components/SupplyChain/SupplyChainGraph";
import SupplierMatching from "@/Components/SupplyChain/SupplierMatching";
import BuyerDiscovery from "@/Components/SupplyChain/BuyerDiscovery";
import ExecutiveSupplyChainReport from "@/Components/SupplyChain/ExecutiveSupplyChainReport";

export default function BuildMySupplyChain({
    supplyChain,
    graph = {},
    suppliers = [],
    buyers = [],
    report = {},
}) {
    if (!supplyChain) {
        return null;
    }

    const stages = supplyChain.stages ?? [];

    return (
        <div className="space-y-6">
            {/* Executive Report */}

            <ExecutiveSupplyChainReport {...report} />

            {/* Supply Chain Builder */}

            <div
                className="
                    overflow-hidden
                    rounded-2xl
                    border
                    border-slate-200
                    bg-white
                    shadow-sm
                "
            >
                {/* Header */}

                <SupplyChainHeader supplyChain={supplyChain} />

                {/* Flow */}

                <div className="space-y-4 p-6">
                    {stages.map((stage, index) => (
                        <SupplyChainStage
                            key={`${stage.type}-${stage.title}-${index}`}
                            stage={stage}
                            isLast={index === stages.length - 1}
                        />
                    ))}
                </div>
            </div>

            {/* Supply Chain Graph */}

            <SupplyChainGraph {...graph} />

            {/* Supplier Matching */}

            <SupplierMatching suppliers={suppliers} />

            {/* Buyer Discovery */}

            <BuyerDiscovery buyers={buyers} />
        </div>
    );
}
