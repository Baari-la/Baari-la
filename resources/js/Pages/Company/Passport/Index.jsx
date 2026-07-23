import AppLayout from "@/Layouts/WebsiteLayout";
import SmartBusinessMatching from "@/Components/Recommendation/SmartBusinessMatching";
import BuildMySupplyChain from "./Components/BuildMySupplyChain";
import ExecutiveHeader from "./Components/ExecutiveHeader";
import SmartBusinessMatchingCard from "./Components/SmartBusinessMatchingCard";

export default function Index({ passport }) {
    return (
        <AppLayout>
            <div className="mx-auto max-w-7xl space-y-8 px-6 py-8">
                <ExecutiveHeader passport={passport} />
                <SmartBusinessMatchingCard matching={passport.matching} />

                <SmartBusinessMatching matching={passport.matching} />

                <BuildMySupplyChain supplyChain={passport.build_supply_chain} />
            </div>
        </AppLayout>
    );
}
