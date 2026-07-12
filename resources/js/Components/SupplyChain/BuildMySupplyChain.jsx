import SupplyChainHeader from "./SupplyChainHeader";
import SupplyChainStage from "./SupplyChainStage";

export default function BuildMySupplyChain({ supplyChain }) {
    if (!supplyChain) {
        return null;
    }

    const stages = supplyChain.stages ?? [];

    return (
        <div className="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            {/* Header */}

            <SupplyChainHeader supplyChain={supplyChain} />

            {/* Supply Chain Flow */}

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
    );
}
