/*
|--------------------------------------------------------------------------
| DIGESTEX Industry Router™
|--------------------------------------------------------------------------
|
| Blueprint-driven router.
|
| The router does not contain business logic.
| It simply renders the component defined by
| IndustryBlueprintFactory™.
|
|--------------------------------------------------------------------------
*/

export default function IndustryRouter({
    business,
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

    if (!blueprint?.component) {
        return (
            <div className="rounded-2xl border border-red-200 bg-red-50 p-6">
                <h3 className="font-bold text-red-700">
                    Industry Blueprint Not Found
                </h3>

                <p className="mt-2 text-sm text-red-600">
                    No Industry Blueprint is registered for this business
                    classification.
                </p>
            </div>
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Blueprint
    |--------------------------------------------------------------------------
    */

    const BlueprintComponent = blueprint.component;

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    return (
        <BlueprintComponent
            business={business}
            blueprint={blueprint}
            framework={business?.framework}
            data={data}
            setData={setData}
            errors={errors}
        />
    );
}
