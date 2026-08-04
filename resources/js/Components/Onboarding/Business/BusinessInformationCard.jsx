/*
|--------------------------------------------------------------------------
| DIGESTEX Business Information™
|--------------------------------------------------------------------------
|
| Step 2
|
| This component orchestrates all Business Information
| sections and the Live Classification™ sidebar.
|
|--------------------------------------------------------------------------
*/

import buildLiveClassification from "@/Support/Business/buildLiveClassification";

import LiveClassificationCard from "./LiveClassification/LiveClassificationCard";

/*
|--------------------------------------------------------------------------
| Sections
|--------------------------------------------------------------------------
*/

import CompanyDescriptionSection from "./Sections/CompanyDescriptionSection";
import BusinessActivitiesSection from "./Sections/BusinessActivitiesSection";
import BusinessStrategySection from "./Sections/BusinessStrategySection";
import CompanyProfileSection from "./Sections/CompanyProfileSection";
import MarketCoverageSection from "./Sections/MarketCoverageSection";
import SustainabilitySection from "./Sections/SustainabilitySection";

export default function BusinessInformationCard({
    locale,
    company,
    data,
    setData,
    errors,
    processing,
    submit,
}) {
    /*
    |--------------------------------------------------------------------------
    | Live Classification™
    |--------------------------------------------------------------------------
    */

    const classification = buildLiveClassification(data);

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    return (
        <form onSubmit={submit} className="grid gap-8 xl:grid-cols-3">
            {/* ============================================================
                LEFT
            ============================================================ */}

            <div className="space-y-8 xl:col-span-2">
                <CompanyDescriptionSection
                    locale={locale}
                    data={data}
                    setData={setData}
                    errors={errors}
                />

                <BusinessActivitiesSection
                    locale={locale}
                    data={data}
                    setData={setData}
                    errors={errors}
                />

                <BusinessStrategySection
                    locale={locale}
                    data={data}
                    setData={setData}
                    errors={errors}
                />

                <CompanyProfileSection
                    locale={locale}
                    data={data}
                    setData={setData}
                    errors={errors}
                />

                <MarketCoverageSection
                    locale={locale}
                    data={data}
                    setData={setData}
                    errors={errors}
                />

                <SustainabilitySection
                    locale={locale}
                    data={data}
                    setData={setData}
                    errors={errors}
                />
            </div>

            {/* ============================================================
                RIGHT SIDEBAR
            ============================================================ */}

            <div className="space-y-6 xl:sticky xl:top-6 xl:self-start">
                <LiveClassificationCard classification={classification} />

                <div className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <button
                        type="submit"
                        disabled={processing}
                        className="w-full rounded-xl bg-indigo-600 px-5 py-3 font-semibold text-white transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        {processing ? "Saving..." : "Save & Continue"}
                    </button>

                    <p className="mt-3 text-center text-xs text-slate-500">
                        Your business information will be used to automatically
                        determine the most suitable Capability Framework™.
                    </p>
                </div>
            </div>
        </form>
    );
}
