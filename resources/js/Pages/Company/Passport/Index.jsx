import AppLayout from "@/Layouts/AppLayout";

import ExecutiveHeader from "./Components/ExecutiveHeader";
import OverallScoreCard from "./Components/OverallScoreCard";
import CompletionProgress from "./Components/CompletionProgress";

import IdentityPassport from "./Components/IdentityPassport";
import BusinessPassport from "./Components/BusinessPassport";
import FactoryPassport from "./Components/FactoryPassport";

import CapabilityPassport from "./Components/CapabilityPassport";
import CompliancePassport from "./Components/CompliancePassport";
import SupplyChainPassport from "./Components/SupplyChainPassport";
import MarketPassport from "./Components/MarketPassport";

import VerificationPassport from "./Components/VerificationPassport";
import BusinessReadinessPassport from "./Components/BusinessReadinessPassport";

import RecommendationPanel from "./Components/RecommendationPanel";

export default function Index({ passport }) {
    const company = passport.summary ?? {};

    return (
        <AppLayout>
            <div className="mx-auto max-w-7xl space-y-8 px-6 py-8">
                {/* ==========================================================
                    Executive Header
                =========================================================== */}

                <ExecutiveHeader company={company} />

                {/* ==========================================================
                    Overall Score
                =========================================================== */}

                <OverallScoreCard passport={passport} />

                {/* ==========================================================
                    Completion Progress
                =========================================================== */}

                <CompletionProgress passport={passport} />

                {/* ==========================================================
                    Identity
                =========================================================== */}

                <IdentityPassport passport={passport.passport.profile} />

                {/* ==========================================================
                    Business
                =========================================================== */}

                <BusinessPassport passport={passport.passport.profile} />

                {/* ==========================================================
                    Factory
                =========================================================== */}

                <FactoryPassport passport={passport.passport.supply_chain} />

                {/* ==========================================================
                    Capability
                =========================================================== */}

                <CapabilityPassport passport={passport.passport.capability} />

                {/* ==========================================================
                    Compliance
                =========================================================== */}

                <CompliancePassport passport={passport.passport.compliance} />

                {/* ==========================================================
                    Supply Chain
                =========================================================== */}

                <SupplyChainPassport
                    passport={passport.passport.supply_chain}
                />

                {/* ==========================================================
                    Market
                =========================================================== */}

                <MarketPassport passport={passport.passport.market} />

                {/* ==========================================================
                    Verification
                =========================================================== */}

                <VerificationPassport passport={passport.passport.profile} />

                {/* ==========================================================
                    Business Readiness
                =========================================================== */}

                <BusinessReadinessPassport
                    passport={passport.passport.readiness}
                />

                {/* ==========================================================
                    Executive Recommendation
                =========================================================== */}

                <RecommendationPanel passport={passport} />
            </div>
        </AppLayout>
    );
}
