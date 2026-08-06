/*
|--------------------------------------------------------------------------
| DIGESTEX Business Locations Section™
|--------------------------------------------------------------------------
|
| Dynamic Business Locations generated from
| CompanyBlueprintFactory™
|
|--------------------------------------------------------------------------
*/

import { usePage } from "@inertiajs/react";
import { PlusCircle } from "lucide-react";
import LocationCard from "./LocationCard";

export default function BusinessLocationsSection({
    blueprint,
    locations = [],
    setLocations,
}) {
    const { locale } = usePage().props;
    const isEn = locale === "en";

    // Helper Translation
    const t = (en, id) => (isEn ? en : id);

    /*
    |--------------------------------------------------------------------------
    | Update Location
    |--------------------------------------------------------------------------
    */
    const updateLocation = (realIndex, itemBlueprint, field, value) => {
        const updated = [...locations];

        // Jika lokasi belum ada di array state global (misal: virtual default dari item.required)
        if (realIndex === -1 || !updated[realIndex]) {
            const categoryCount = locations.filter(
                (loc) => loc.location_type === itemBlueprint.key,
            ).length;

            const newLocation = {
                location_type: itemBlueprint.key,
                location_name: "",
                location_label: "",
                country: "",
                is_primary: Boolean(itemBlueprint.required),
                is_active: true,
                display_order: categoryCount + 1,
                [field]: value,
            };

            const nextLocations = [...updated, newLocation];
            setLocations(nextLocations);
            return;
        }

        // Jika lokasi sudah terdaftar di state global
        updated[realIndex] = {
            ...updated[realIndex],
            [field]: value,
        };

        setLocations(updated);
    };

    /*
    |--------------------------------------------------------------------------
    | Add Location (Kategori-Specific Display Order)
    |--------------------------------------------------------------------------
    */
    const addLocation = (itemBlueprint) => {
        const nextOrder =
            locations.filter((loc) => loc.location_type === itemBlueprint.key)
                .length + 1;

        setLocations([
            ...locations,
            {
                location_type: itemBlueprint.key,
                location_name: "",
                location_label: "",
                country: "",
                is_primary: false,
                is_active: true,
                display_order: nextOrder,
            },
        ]);
    };

    return (
        <div className="space-y-10">
            {/* Header Section */}
            <div>
                <h2 className="text-3xl font-black text-slate-900">
                    {t("Business Locations™", "Lokasi Bisnis™")}
                </h2>

                <p className="mt-3 max-w-3xl text-slate-600">
                    {t(
                        "Configure your Head Office, Factories, Warehouses and Branch Offices.",
                        "Konfigurasikan Kantor Pusat, Pabrik, Gudang dan Kantor Cabang.",
                    )}
                </p>
            </div>

            {/* Loop berdasarkan Blueprint */}
            {blueprint?.locations?.map((item) => {
                // Filter lokasi berdasarkan type
                let items = locations.filter(
                    (loc) => loc.location_type === item.key,
                );

                // Tampilkan virtual default jika item required & belum ada di state
                const isVirtualDefault = items.length === 0 && item.required;

                if (isVirtualDefault) {
                    items = [
                        {
                            location_type: item.key,
                            country: "",
                            is_primary: true,
                            is_active: true,
                            display_order: 1,
                        },
                    ];
                }

                return (
                    <div key={item.key} className="space-y-6">
                        {items.map((location, localIndex) => {
                            // Mencari realIndex yang presisi di array locations global
                            const realIndex = locations.findIndex(
                                (loc) =>
                                    loc.location_type ===
                                        location.location_type &&
                                    loc.display_order ===
                                        location.display_order,
                            );

                            return (
                                <LocationCard
                                    key={
                                        location.id ??
                                        `${item.key}-${location.display_order}`
                                    }
                                    blueprint={item}
                                    location={location}
                                    onChange={(field, value) =>
                                        updateLocation(
                                            realIndex,
                                            item,
                                            field,
                                            value,
                                        )
                                    }
                                />
                            );
                        })}

                        {/* Tombol Tambah untuk lokasi tipe Multiple */}
                        {item.multiple && (
                            <button
                                type="button"
                                onClick={() => addLocation(item)}
                                className="inline-flex items-center gap-2 rounded-2xl border border-dashed border-emerald-400 px-5 py-3 font-bold text-emerald-700 transition duration-300 hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                            >
                                <PlusCircle className="h-5 w-5" />
                                {t(
                                    `Add ${item.title}`,
                                    `Tambah ${item.titleId}`,
                                )}
                            </button>
                        )}
                    </div>
                );
            })}
        </div>
    );
}
