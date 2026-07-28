import { useMemo } from "react";
import { usePage } from "@inertiajs/react";

import WebsiteLayout from "@/Layouts/WebsiteLayout";

import { getDirectoryProgramContent } from "./content";

import HeroSection from "./HeroSection";
import SummarySection from "./SummarySection";
import PassportSection from "./PassportSection";
import BenefitsSection from "./BenefitsSection";
import TransformationSection from "./TransformationSection";
import MembershipJourneySection from "./MembershipJourneySection";
import CommitmentSection from "./CommitmentSection";
import CTASection from "./CTASection";

export default function Index() {
    const { locale = "id" } = usePage().props;

    const isEn = locale === "en";

    const content = useMemo(() => getDirectoryProgramContent(isEn), [isEn]);

    return (
        <WebsiteLayout title="DIGESTEX Digital Directory & Visibility Program">
            <main className="bg-gradient-to-b from-slate-50 via-white to-slate-50">
                <HeroSection content={content.hero} />

                <SummarySection content={content.summary} />

                <PassportSection content={content.passport} />

                <BenefitsSection content={content.benefits} />

                <TransformationSection content={content.transformation} />

                <MembershipJourneySection content={content.membership} />

                <CommitmentSection content={content.commitment} />

                <CTASection content={content.cta} />
            </main>
        </WebsiteLayout>
    );
}
