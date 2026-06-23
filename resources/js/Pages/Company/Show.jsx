import AuthenticatedLayout from "@/layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";

import CompanyHero from "@/components/company/companyshow/CompanyHero";
import CompanyCredentials from "@/components/company/companyshow/CompanyCredentials";
import CompanyCertifications from "@/components/company/companyshow/CompanyCertifications";
import CompanyCapacities from "@/components/company/companyshow/CompanyCapacities";
import CompanyMachines from "@/components/company/companyshow/CompanyMachines";
import CompanySupplyTerms from "@/components/company/companyshow/CompanySupplyTerms";
import CompanyShowcase from "@/components/company/companyshow/CompanyShowcase";
import CompanyLeadership from "@/components/company/companyshow/CompanyLeadership";
import BuyerReviews from "@/components/company/companyshow/BuyerReviews";
import CompanyIntelligence from "@/components/company/companyshow/CompanyIntelligence";

export default function Show({
    company,
    auth,
    reviewSummary,
    credentials,
    trustScore,
    profileCompleteness,
    companyRoleLabel,
    companyAge,
}) {
    const isEn = auth.locale === "en";

    const getProfileStatus = (percentage) => {
        if (percentage >= 90) return "industry showcase";
        if (percentage >= 75) return "high visibility";
        if (percentage >= 50) return "good visibility";
        if (percentage >= 25) return "growing visibility";

        return "getting started";
    };

    const getTrustLevel = (score) => {
        if (score >= 90) {
            return {
                label: "elite supplier",
                color: "bg-emerald-100 text-emerald-800",
            };
        }

        if (score >= 75) {
            return {
                label: "trusted supplier",
                color: "bg-blue-100 text-blue-800",
            };
        }

        return {
            label: "standard supplier",
            color: "bg-gray-100 text-gray-800",
        };
    };

    const trustLevel = getTrustLevel(trustScore?.score || 0);

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head
                title={`${company.nama_perusahaan} | Industrial Intelligence`}
            />

            <div className="min-h-screen bg-[#0a192f] text-white">
                <div className="max-w-6xl mx-auto px-6 py-12">
                    {/* BREADCRUMB */}
                    <Link
                        href={route("companies.index")}
                        className="
                            inline-flex items-center
                            mb-8
                            text-[10px]
                            font-black
                            uppercase
                            tracking-[0.25em]
                            text-yellow-500
                            hover:text-white
                            transition-colors
                        "
                    >
                        ← {isEn ? "Back to Directory" : "Kembali ke Direktori"}
                    </Link>

                    {/* HERO */}
                    <CompanyHero
                        company={company}
                        auth={auth}
                        isEn={isEn}
                        trustLevel={trustLevel}
                        trustScore={trustScore}
                        companyRoleLabel={companyRoleLabel}
                        companyAge={companyAge}
                    />

                    {/* TRUST & VERIFICATION */}
                    <section className="mt-12">
                        <CompanyCredentials
                            company={company}
                            credentials={credentials}
                            profileCompleteness={profileCompleteness}
                            getProfileStatus={getProfileStatus}
                        />
                    </section>

                    {/* CERTIFICATIONS & COMPLIANCE */}
                    <section className="mt-12">
                        <CompanyCertifications company={company} />
                    </section>

                    {/* MANUFACTURING CAPABILITIES */}
                    <section className="mt-12">
                        <CompanyCapacities company={company} />
                    </section>

                    {/* MACHINERY & TECHNOLOGY */}
                    <section className="mt-12">
                        <CompanyMachines company={company} />
                    </section>

                    {/* MOQ & DELIVERY TERMS */}
                    <section className="mt-12">
                        <CompanySupplyTerms company={company} />
                    </section>

                    {/* PRODUCT PORTFOLIO */}
                    <section className="mt-12">
                        <CompanyShowcase company={company} />
                    </section>

                    {/* EXECUTIVE LEADERSHIP */}
                    <section className="mt-12">
                        <CompanyLeadership company={company} />
                    </section>

                    {/* BUYER INSIGHTS */}
                    <section className="mt-12">
                        <BuyerReviews
                            company={company}
                            reviewSummary={reviewSummary}
                        />
                    </section>

                    {/* MARKET INTELLIGENCE */}
                    <section className="mt-16">
                        <CompanyIntelligence company={company} />
                    </section>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
