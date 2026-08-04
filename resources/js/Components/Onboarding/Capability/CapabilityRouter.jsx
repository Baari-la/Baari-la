/*
|--------------------------------------------------------------------------
| DIGESTEX Capability Router™
|--------------------------------------------------------------------------
|
| Blueprint-driven router.
|
| This router contains no business logic.
| It simply renders the capability component resolved by
| CapabilityFactory through the Capability Blueprint.
|
| Flow
|
| BusinessClassificationService
|         │
|         ▼
| Framework
|         │
|         ▼
| CapabilityFactory
|         │
|         ▼
| CapabilityBlueprintRegistry
|         │
|         ▼
| Blueprint
|         │
|         ▼
| CapabilityRouter
|         │
|         ▼
| Capability Component
|
|--------------------------------------------------------------------------
*/

export default function CapabilityRouter({
    business,
    framework,
    blueprint,
    data,
    setData,
    errors,
}) {
    /*
    |--------------------------------------------------------------------------
    | Safety Check
    |--------------------------------------------------------------------------
    */

    if (!blueprint) {
        return (
            <div className="rounded-3xl border border-red-200 bg-red-50 p-6">
                <h3 className="text-lg font-bold text-red-700">
                    Capability Blueprint Not Found
                </h3>

                <p className="mt-2 text-sm text-red-600">
                    The Capability Factory could not resolve a blueprint for
                    this business profile.
                </p>

                <div className="mt-5 rounded-xl border border-red-100 bg-white p-4 text-sm">
                    <div>
                        <strong>Capability Profile:</strong>{" "}
                        {framework?.capability_profile ?? "-"}
                    </div>

                    <div className="mt-1">
                        <strong>Business Category:</strong>{" "}
                        {business?.primary_business_category ?? "-"}
                    </div>

                    <div className="mt-1">
                        <strong>Industry Type:</strong>{" "}
                        {business?.industry_type ?? "-"}
                    </div>
                </div>
            </div>
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Resolve Component
    |--------------------------------------------------------------------------
    */

    const CapabilityComponent = blueprint.component;

    /*
    |--------------------------------------------------------------------------
    | Missing Component
    |--------------------------------------------------------------------------
    */

    if (!CapabilityComponent) {
        return (
            <div className="rounded-3xl border border-amber-200 bg-amber-50 p-6">
                <h3 className="text-lg font-bold text-amber-700">
                    Capability Component Missing
                </h3>

                <p className="mt-2 text-sm text-amber-700">
                    The blueprint exists, but no React component has been
                    assigned to this capability profile.
                </p>

                <div className="mt-5 rounded-xl border border-amber-100 bg-white p-4 text-sm">
                    <div>
                        <strong>Capability Profile:</strong>{" "}
                        {blueprint.profile ?? framework?.capability_profile}
                    </div>

                    <div className="mt-1">
                        <strong>Blueprint Title:</strong>{" "}
                        {blueprint.title ?? "-"}
                    </div>
                </div>
            </div>
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    return (
        <CapabilityComponent
            business={business}
            framework={framework}
            blueprint={blueprint}
            data={data}
            setData={setData}
            errors={errors}
        />
    );
}
