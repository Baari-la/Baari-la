import { Building2, Globe, MapPin, Calendar, Factory } from "lucide-react";

export default function FactoryInformationCard({ data, setData, errors = {} }) {
    const update = (field, value) => {
        setData({
            ...data,
            [field]: value,
        });
    };

    return (
        <div className="rounded-2xl border bg-white">
            {/* Header */}

            <div className="border-b px-6 py-5">
                <div className="flex items-center gap-3">
                    <Building2 className="h-6 w-6 text-blue-600" />

                    <div>
                        <h2 className="text-lg font-semibold">
                            Factory Information
                        </h2>

                        <p className="text-sm text-slate-500">
                            Basic information about your manufacturing facility.
                        </p>
                    </div>
                </div>
            </div>

            {/* Body */}

            <div className="grid grid-cols-1 gap-6 p-6 md:grid-cols-2">
                {/* Factory Name */}

                <div className="md:col-span-2">
                    <label className="mb-2 block text-sm font-medium">
                        Factory Name *
                    </label>

                    <input
                        type="text"
                        value={data.factory_name}
                        onChange={(e) => update("factory_name", e.target.value)}
                        className="w-full rounded-lg border px-4 py-3"
                    />

                    {errors["factory.factory_name"] && (
                        <p className="mt-1 text-sm text-red-500">
                            {errors["factory.factory_name"]}
                        </p>
                    )}
                </div>

                {/* Factory Type */}

                <div>
                    <label className="mb-2 flex items-center gap-2 text-sm font-medium">
                        <Factory className="h-4 w-4" />
                        Factory Type
                    </label>

                    <select
                        value={data.factory_type}
                        onChange={(e) => update("factory_type", e.target.value)}
                        className="w-full rounded-lg border px-4 py-3"
                    >
                        <option value="MANUFACTURING">Manufacturing</option>

                        <option value="TRADING">Trading</option>

                        <option value="WAREHOUSE">Warehouse</option>

                        <option value="OFFICE">Office</option>

                        <option value="R&D">Research & Development</option>
                    </select>
                </div>

                {/* Established */}

                <div>
                    <label className="mb-2 flex items-center gap-2 text-sm font-medium">
                        <Calendar className="h-4 w-4" />
                        Established Year
                    </label>

                    <input
                        type="number"
                        value={data.factory_established_year}
                        onChange={(e) =>
                            update("factory_established_year", e.target.value)
                        }
                        className="w-full rounded-lg border px-4 py-3"
                    />
                </div>

                {/* Country */}

                <div>
                    <label className="mb-2 flex items-center gap-2 text-sm font-medium">
                        <Globe className="h-4 w-4" />
                        Country
                    </label>

                    <input
                        type="text"
                        value={data.country}
                        onChange={(e) => update("country", e.target.value)}
                        className="w-full rounded-lg border px-4 py-3"
                    />
                </div>

                {/* Province */}

                <div>
                    <label className="mb-2 flex items-center gap-2 text-sm font-medium">
                        <MapPin className="h-4 w-4" />
                        Province
                    </label>

                    <input
                        type="text"
                        value={data.province}
                        onChange={(e) => update("province", e.target.value)}
                        className="w-full rounded-lg border px-4 py-3"
                    />
                </div>

                {/* City */}

                <div>
                    <label className="mb-2 block text-sm font-medium">
                        City
                    </label>

                    <input
                        type="text"
                        value={data.city}
                        onChange={(e) => update("city", e.target.value)}
                        className="w-full rounded-lg border px-4 py-3"
                    />
                </div>

                {/* Production Lines */}

                <div>
                    <label className="mb-2 block text-sm font-medium">
                        Production Lines
                    </label>

                    <input
                        type="number"
                        value={data.production_lines}
                        onChange={(e) =>
                            update("production_lines", e.target.value)
                        }
                        className="w-full rounded-lg border px-4 py-3"
                    />
                </div>

                {/* Number of Shifts */}

                <div>
                    <label className="mb-2 block text-sm font-medium">
                        Number of Shifts
                    </label>

                    <input
                        type="number"
                        value={data.number_of_shifts}
                        onChange={(e) =>
                            update("number_of_shifts", e.target.value)
                        }
                        className="w-full rounded-lg border px-4 py-3"
                    />
                </div>

                {/* Land Area */}

                <div>
                    <label className="mb-2 block text-sm font-medium">
                        Land Area (m²)
                    </label>

                    <input
                        type="number"
                        value={data.land_area_sqm}
                        onChange={(e) =>
                            update("land_area_sqm", e.target.value)
                        }
                        className="w-full rounded-lg border px-4 py-3"
                    />
                </div>

                {/* Building Area */}

                <div>
                    <label className="mb-2 block text-sm font-medium">
                        Building Area (m²)
                    </label>

                    <input
                        type="number"
                        value={data.building_area_sqm}
                        onChange={(e) =>
                            update("building_area_sqm", e.target.value)
                        }
                        className="w-full rounded-lg border px-4 py-3"
                    />
                </div>
            </div>
        </div>
    );
}
