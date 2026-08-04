/*
|--------------------------------------------------------------------------
| DIGESTEX Manufacturer Capability™
|--------------------------------------------------------------------------
|
| Dynamic renderer for all Manufacturer Capability
| Frameworks.
|
|--------------------------------------------------------------------------
*/

import SECTION_REGISTRY from "./SectionRegistry";

export default function ManufacturerCapability({
    blueprint,
    business,
    framework,
    data,
    setData,
}) {
    const sections = blueprint?.sections ?? [];

    return (
        <div className="space-y-10">
            {sections.map((sectionKey) => {
                const Section = SECTION_REGISTRY[sectionKey];

                if (!Section) {
                    return null;
                }

                return (
                    <Section
                        key={sectionKey}
                        business={business}
                        framework={framework}
                        data={data}
                        setData={setData}
                    />
                );
            })}
        </div>
    );
}
