/*
|--------------------------------------------------------------------------
| Phase 1 (Launch)
|--------------------------------------------------------------------------
|
| Trade Roles
| Export Experience
| Countries
| Supply Chain
|
|--------------------------------------------------------------------------
|
| Phase 2
|--------------------------------------------------------------------------
|
| HS Code
| Trade Statistics
| Export Volume
| Import Volume
| Customs Verification
| Buyer Trust AI
|
|--------------------------------------------------------------------------
*/

import { Head, useForm, usePage } from "@inertiajs/react";

import BaseOnboardingPage from "@/Components/Onboarding/BaseOnboardingPage";

import TradeRoleCard from "@/Components/Onboarding/Trade/TradeRoleCard";
import TradeExperienceCard from "@/Components/Onboarding/Trade/TradeExperienceCard";
import TradeCountriesCard from "@/Components/Onboarding/Trade/TradeCountriesCard";
import SupplyChainCard from "@/Components/Onboarding/Trade/SupplyChainCard";
import BuyerDecisionDashboard from "@/Components/Onboarding/Trade/BuyerDecisionDashboard";

import StepNavigation from "@/Components/Onboarding/StepNavigation";

export default function TradeProfile() {
    const { locale, company } = usePage().props;

    const isEn = locale === "en";

    const { data, setData, post, transform, processing, errors } = useForm({
        trade_roles: company?.trade_roles ?? [],
        export_experience: company?.export_experience ?? "",
        export_since: company?.export_since ?? "",
        export_countries: company?.export_countries ?? [],
        import_countries: company?.import_countries ?? [],
        main_industries: company?.main_industries ?? [],
        domestic_markets: company?.domestic_markets ?? [],

        /*
        |--------------------------------------------------------------------------
        | Future Trade Intelligence™
        |--------------------------------------------------------------------------
        */
        export_products: company?.export_products ?? [],
        import_products: company?.import_products ?? [],
        trade_notes: company?.trade_notes ?? "",
    });

    const submit = (e) => {
        e.preventDefault();

        // Menggunakan transform dengan cara yang lebih disarankan
        transform((data) => ({
            ...data,
            trade_roles: (data.trade_roles || []).filter(Boolean),
            export_countries: (data.export_countries || []).filter(
                (country) =>
                    typeof country === "string" && country.trim() !== "",
            ),
            import_countries: (data.import_countries || []).filter(
                (country) =>
                    typeof country === "string" && country.trim() !== "",
            ),
            main_industries: (data.main_industries || []).filter(Boolean),
        }));

        post(route("onboarding.trade-profile.store"), {
            preserveScroll: true,
            onSuccess: () => {
                console.log("Trade Profile saved.");
            },
        });
    };

    if (import.meta.env.DEV) {
        console.group("Trade Profile Debugger");
        console.log("Form Data:", data);
        console.log("Form Errors:", errors);
        console.groupEnd();
    }

    return (
        <>
            <Head title="Trade Profile" />
            <BaseOnboardingPage
                currentStep={4}
                title={
                    isEn
                        ? "Trade & Supply Chain Profile™"
                        : "Profil Perdagangan & Supply Chain™"
                }
                description={
                    isEn
                        ? "Help international buyers understand your company's role in the textile supply chain."
                        : "Bantu buyer internasional memahami peran perusahaan Anda dalam rantai pasok tekstil."
                }
                intelligence={{
                    title: "Trade Intelligence™",
                    description: isEn
                        ? "The information below will improve your Buyer Trust™, Visibility™, and future Smart Business Matching™."
                        : "Informasi berikut akan meningkatkan Buyer Trust™, Visibility™, dan Smart Business Matching™ di masa mendatang.",
                    items: [
                        "Buyer Trust™",
                        "Trade Profile™",
                        "Supply Chain Intelligence™",
                        "Sourcing Hub™",
                        "Global RFQ™",
                        "Smart Business Matching™",
                    ],
                }}
                sidebar={
                    <div className="sticky top-24">
                        <BuyerDecisionDashboard company={company} data={data} />
                    </div>
                }
            >
                <form onSubmit={submit} className="space-y-8">
                    <div className="rounded-3xl border border-indigo-100 bg-gradient-to-r from-indigo-50 via-white to-emerald-50 p-8">
                        <div className="text-sm font-black uppercase tracking-widest text-indigo-600">
                            STEP 4
                        </div>

                        <h2 className="mt-3 text-3xl font-black text-slate-900">
                            {isEn
                                ? "Trade & Supply Chain Profile™"
                                : "Profil Perdagangan & Supply Chain™"}
                        </h2>

                        <p className="mt-5 max-w-3xl leading-7 text-slate-600">
                            {isEn
                                ? "This information helps international buyers understand your company's trade activities and improves your Buyer Trust™, Visibility™, and future Global RFQ opportunities."
                                : "Informasi ini membantu buyer internasional memahami aktivitas perdagangan perusahaan Anda serta meningkatkan Buyer Trust™, Visibility™, dan peluang Global RFQ di masa depan."}
                        </p>
                    </div>

                    {/* Component Cards with Error Handling Passed */}
                    <TradeRoleCard
                        data={data}
                        setData={setData}
                        errors={errors}
                    />
                    <TradeExperienceCard
                        data={data}
                        setData={setData}
                        errors={errors}
                    />
                    <TradeCountriesCard
                        data={data}
                        setData={setData}
                        errors={errors}
                    />
                    <SupplyChainCard
                        data={data}
                        setData={setData}
                        errors={errors}
                    />

                    {/* Trade Intelligence Box */}
                    <div className="rounded-3xl border border-indigo-100 bg-gradient-to-r from-indigo-50 via-white to-emerald-50 p-8">
                        <div className="text-lg font-black text-indigo-700">
                            {isEn ? "Why This Matters" : "Mengapa Ini Penting"}
                        </div>

                        <p className="mt-4 leading-7 text-slate-600">
                            {isEn
                                ? "Companies with complete trade profiles receive higher visibility in DIGESTEX Sourcing Hub™ and will be easier for international buyers to evaluate."
                                : "Perusahaan dengan profil perdagangan yang lengkap akan memperoleh visibilitas lebih tinggi di DIGESTEX Sourcing Hub™ dan lebih mudah dievaluasi oleh buyer internasional."}
                        </p>

                        <div className="mt-6 grid gap-4 md:grid-cols-3">
                            <Feature title="Buyer Trust™" score="Increase" />

                            <Feature title="Visibility™" score="Increase" />

                            <Feature title="Global RFQ™" score="Coming Soon" />
                        </div>
                    </div>

                    {/* Dev Only Payload Debugger */}
                    {import.meta.env.DEV && (
                        <div className="rounded-2xl border border-slate-200 bg-slate-900 p-6">
                            <div className="mb-2 text-xs uppercase tracking-widest text-slate-500">
                                Development Only
                            </div>
                            <div className="mb-3 font-bold text-emerald-400">
                                Trade Profile Payload Debugger
                            </div>
                            <pre className="overflow-auto text-xs text-emerald-300">
                                {JSON.stringify(data, null, 2)}
                            </pre>
                        </div>
                    )}

                    <StepNavigation currentStep={4} processing={processing} />
                </form>
            </BaseOnboardingPage>
        </>
    );
}

function Feature({ title, score }) {
    return (
        <div className="rounded-2xl border border-white bg-white p-5 shadow-sm">
            <div className="text-sm font-semibold text-slate-500">{title}</div>

            <div className="mt-2 text-2xl font-black text-emerald-600">
                {score}
            </div>
        </div>
    );
}
