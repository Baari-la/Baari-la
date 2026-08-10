import { usePage } from "@inertiajs/react";

import AppLayout from "@/Layouts/WebsiteLayout";

import ExecutiveHeader from "./Components/ExecutiveHeader";

import OverallScoreCard from "./Components/Dashboard/OverallScoreCard";
import CompletionProgress from "./Components/Dashboard/CompletionProgress";

import RecommendationPanel from "./Components/Recommendation/RecommendationPanel";

import SmartBusinessMatchingCard from "./Components/SmartBusinessMatchingCard";
import BuildMySupplyChain from "./Components/BuildMySupplyChain";

export default function Index({ passport }) {
    const { locale } = usePage().props;

    const isEn = locale === "en";
    console.log("=================================");
    console.log("COMPANY PASSPORT");
    console.log("Company:", passport?.company);
    console.log("Company ID:", passport?.company_id);
    console.log("Company Name:", passport?.company_name);
    console.log("MATCHING:", passport?.matching);
    console.log("=================================");

    return (
        <AppLayout>
            <div className="mx-auto max-w-7xl space-y-8 px-6 py-8">
                {/* =====================================================
                    EXECUTIVE HEADER
                ===================================================== */}

                <ExecutiveHeader passport={passport} />

                {/* =====================================================
                    INTELLIGENCE PROFILE
                ===================================================== */}

                <div className="grid gap-6 xl:grid-cols-[0.8fr_1.2fr]">
                    <OverallScoreCard passport={passport} />

                    <CompletionProgress passport={passport} isEn={isEn} />
                </div>

                {/* =====================================================
                    NEXT BEST ACTIONS
                ===================================================== */}

                <RecommendationPanel passport={passport} isEn={isEn} />

                {/* =====================================================
                    SMART BUSINESS MATCHING
                ===================================================== */}

                <SmartBusinessMatchingCard matching={passport?.matching} />

                {/* =====================================================
                    BUILD MY SUPPLY CHAIN
                ===================================================== */}

                <BuildMySupplyChain
                    supplyChain={passport?.build_supply_chain}
                />
            </div>
        </AppLayout>
    );
}
