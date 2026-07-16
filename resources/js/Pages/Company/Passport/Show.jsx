import AppLayout from "@/Layouts/AppLayout";

import ExecutiveHeader from "./Components/Header/ExecutiveHeader";

import OverallScoreCard from "./Components/Dashboard/OverallScoreCard";
import CompletionProgress from "./Components/Dashboard/CompletionProgress";

import SmartBusinessMatching from "./Components/Recommendation/SmartBusinessMatching";

import IdentityPassport from "./Components/Passports/IdentityPassport";
import BusinessPassport from "./Components/Passports/BusinessPassport";
import FactoryPassport from "./Components/Passports/FactoryPassport";
import CapabilityPassport from "./Components/Passports/CapabilityPassport";
import CompliancePassport from "./Components/Passports/CompliancePassport";
import SupplyChainPassport from "./Components/Passports/SupplyChainPassport";
import MarketPassport from "./Components/Passports/MarketPassport";
import VerificationPassport from "./Components/Passports/VerificationPassport";
import BusinessReadinessPassport from "./Components/Passports/BusinessReadinessPassport";

import RecommendationPanel from "./Components/Recommendation/RecommendationPanel";

export default function Show({ passport }) {
    return (
        <AppLayout title="Digital Company Passport">
            <div className="mx-auto max-w-7xl space-y-8 px-6 py-8">
                {/* ==========================================================
                    Executive Header
                ========================================================== */}

                <ExecutiveHeader passport={passport} />

                {/* ==========================================================
                    Executive Dashboard
                ========================================================== */}

                <div className="grid gap-6 lg:grid-cols-2">
                    <OverallScoreCard passport={passport} />

                    <CompletionProgress passport={passport} />
                </div>

                {/* ------------------------------------------------------------------ */
                /* Smart Business Matching                                             */
                /* ------------------------------------------------------------------ */}

                <SmartBusinessMatching matching={passport.matching} />

                {/* ------------------------------------------------------------------ */
                /* Build My Supply Chain                                               */
                /* ------------------------------------------------------------------ */}

                <BuildMySupplyChain supplyChain={passport.build_supply_chain} />

                {/* ==========================================================
                    Digital Company Passport
                ========================================================== */}

                <div className="grid gap-6">
                    <IdentityPassport passport={passport} />

                    <BusinessPassport passport={passport} />

                    <FactoryPassport passport={passport} />

                    <CapabilityPassport passport={passport} />

                    <CompliancePassport passport={passport} />

                    <SupplyChainPassport passport={passport} />

                    <MarketPassport passport={passport} />

                    <VerificationPassport passport={passport} />

                    <BusinessReadinessPassport passport={passport} />
                </div>

                {/* ==========================================================
                    Executive Recommendations
                ========================================================== */}

                <RecommendationPanel passport={passport} />
            </div>
        </AppLayout>
    );
}
