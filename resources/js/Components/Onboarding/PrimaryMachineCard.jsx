import {
    Cpu,
    Factory,
    Package,
    Calendar,
    Globe,
    Gauge,
    Zap,
} from "lucide-react";

export default function PrimaryMachineCard({ data, setData, errors = {} }) {
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
                    <Cpu className="h-6 w-6 text-blue-600" />

                    <div>
                        <h2 className="text-lg font-semibold">
                            Primary Machine
                        </h2>

                        <p className="text-sm text-slate-500">
                            Main production machine used by this factory.
                        </p>
                    </div>
                </div>
            </div>

            {/* Body */}

            <div className="grid grid-cols-1 gap-6 p-6 md:grid-cols-2">
                {/* Machine Category */}

                <div>
                    <label className="mb-2 flex items-center gap-2 text-sm font-medium">
                        <Factory className="h-4 w-4" />
                        Machine Category *
                    </label>

                    <select
                        value={data.machine_category}
                        onChange={(e) =>
                            update("machine_category", e.target.value)
                        }
                        className="w-full rounded-lg border px-4 py-3"
                    >
                        <option value="">Select Category</option>

                        <option value="Spinning">Spinning</option>

                        <option value="Weaving">Weaving</option>

                        <option value="Knitting">Knitting</option>

                        <option value="Dyeing">Dyeing</option>

                        <option value="Printing">Printing</option>

                        <option value="Finishing">Finishing</option>

                        <option value="Garment">Garment</option>

                        <option value="Testing">Testing Laboratory</option>
                    </select>

                    {errors["primary_machine.machine_category"] && (
                        <p className="mt-1 text-sm text-red-500">
                            {errors["primary_machine.machine_category"]}
                        </p>
                    )}
                </div>

                {/* Machine Type */}

                <div>
                    <label className="mb-2 block text-sm font-medium">
                        Machine Type
                    </label>

                    <input
                        type="text"
                        value={data.machine_type}
                        onChange={(e) => update("machine_type", e.target.value)}
                        placeholder="Air Jet Loom"
                        className="w-full rounded-lg border px-4 py-3"
                    />
                </div>

                {/* Brand */}

                <div>
                    <label className="mb-2 block text-sm font-medium">
                        Machine Brand
                    </label>

                    <input
                        type="text"
                        value={data.machine_brand}
                        onChange={(e) =>
                            update("machine_brand", e.target.value)
                        }
                        placeholder="Toyota"
                        className="w-full rounded-lg border px-4 py-3"
                    />
                </div>

                {/* Model */}

                <div>
                    <label className="mb-2 block text-sm font-medium">
                        Machine Model
                    </label>

                    <input
                        type="text"
                        value={data.machine_model}
                        onChange={(e) =>
                            update("machine_model", e.target.value)
                        }
                        placeholder="JAT710"
                        className="w-full rounded-lg border px-4 py-3"
                    />
                </div>

                {/* Quantity */}

                <div>
                    <label className="mb-2 flex items-center gap-2 text-sm font-medium">
                        <Package className="h-4 w-4" />
                        Quantity
                    </label>

                    <input
                        type="number"
                        min="1"
                        value={data.quantity}
                        onChange={(e) => update("quantity", e.target.value)}
                        className="w-full rounded-lg border px-4 py-3"
                    />
                </div>

                {/* Year Installed */}

                <div>
                    <label className="mb-2 flex items-center gap-2 text-sm font-medium">
                        <Calendar className="h-4 w-4" />
                        Year Installed
                    </label>

                    <input
                        type="number"
                        value={data.year_installed}
                        onChange={(e) =>
                            update("year_installed", e.target.value)
                        }
                        className="w-full rounded-lg border px-4 py-3"
                    />
                </div>

                {/* Country Origin */}

                <div>
                    <label className="mb-2 flex items-center gap-2 text-sm font-medium">
                        <Globe className="h-4 w-4" />
                        Country of Origin
                    </label>

                    <input
                        type="text"
                        value={data.country_origin}
                        onChange={(e) =>
                            update("country_origin", e.target.value)
                        }
                        placeholder="Japan"
                        className="w-full rounded-lg border px-4 py-3"
                    />
                </div>

                {/* Production Capacity */}

                <div>
                    <label className="mb-2 flex items-center gap-2 text-sm font-medium">
                        <Gauge className="h-4 w-4" />
                        Production Capacity
                    </label>

                    <div className="flex gap-3">
                        <input
                            type="number"
                            value={data.production_capacity}
                            onChange={(e) =>
                                update("production_capacity", e.target.value)
                            }
                            className="flex-1 rounded-lg border px-4 py-3"
                        />

                        <select
                            value={data.capacity_unit}
                            onChange={(e) =>
                                update("capacity_unit", e.target.value)
                            }
                            className="w-36 rounded-lg border px-3 py-3"
                        >
                            <option value="">Unit</option>

                            <option value="kg/day">kg/day</option>

                            <option value="meter/day">meter/day</option>

                            <option value="yard/day">yard/day</option>

                            <option value="pcs/day">pcs/day</option>

                            <option value="ton/day">ton/day</option>
                        </select>
                    </div>
                </div>

                {/* Automation */}

                <div>
                    <label className="mb-2 flex items-center gap-2 text-sm font-medium">
                        <Zap className="h-4 w-4" />
                        Automation Level
                    </label>

                    <select
                        value={data.automation_level}
                        onChange={(e) =>
                            update("automation_level", e.target.value)
                        }
                        className="w-full rounded-lg border px-4 py-3"
                    >
                        <option value="MANUAL">Manual</option>

                        <option value="SEMI_AUTOMATIC">Semi Automatic</option>

                        <option value="FULLY_AUTOMATIC">Fully Automatic</option>
                    </select>
                </div>
            </div>
        </div>
    );
}
