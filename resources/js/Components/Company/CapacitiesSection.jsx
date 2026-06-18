import Swal from "sweetalert2";
import { router } from "@inertiajs/react";

export default function CapacitiesSection({ data, setData, company }) {
    const updateCapacityField = (index, field, value) => {
        const updated = (data.capacities || []).map((item, i) =>
            i === index
                ? {
                      ...item,
                      [field]: value,
                  }
                : item,
        );

        setData("capacities", updated);
    };

    const removeCapacity = (capacity, index) => {
        Swal.fire({
            title: "Delete Capacity?",
            text: "This capacity record will be permanently removed.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#ef4444",
            cancelButtonColor: "#64748b",
            confirmButtonText: "Yes, Delete",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (!result.isConfirmed) {
                return;
            }

            if (!capacity.id) {
                setData(
                    "capacities",
                    (data.capacities || []).filter((_, i) => i !== index),
                );

                return;
            }

            router.delete(
                route("companies.capacities.destroy", [
                    company.id,
                    capacity.id,
                ]),
                {
                    preserveScroll: true,
                    preserveState: true,

                    onSuccess: () => {
                        setData(
                            "capacities",
                            (data.capacities || []).filter(
                                (_, i) => i !== index,
                            ),
                        );

                        Swal.fire({
                            icon: "success",
                            title: "Deleted",
                            text: "Capacity deleted successfully.",
                            confirmButtonColor: "#22c55e",
                        });
                    },
                },
            );
        });
    };

    return (
        <div className="bg-white/5 border border-white/10 rounded-[40px] p-10">
            <div className="flex items-center justify-between mb-8">
                <div>
                    <h2 className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                        Production Capacities
                    </h2>

                    <p className="text-gray-500 text-[10px] uppercase tracking-widest mt-2">
                        Installed / Actual / Idle Production Capacity
                    </p>
                </div>

                <button
                    type="button"
                    onClick={() =>
                        setData("capacities", [
                            ...data.capacities,
                            {
                                id: null,
                                capacity_type: "",
                                item_name: "",
                                capacity_value: "",
                                capacity_unit: "kg/day",
                                capacity_category: "installed",
                                machine_count: "",
                                shift_info: "",
                                notes: "",
                            },
                        ])
                    }
                    className="bg-emerald-500 text-[#0a192f] px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-white transition-all"
                >
                    + Add Capacity
                </button>
            </div>

            <div className="space-y-6">
                {data.capacities.map((capacity, index) => (
                    <div
                        key={capacity.id || index}
                        className="border border-white/10 rounded-[30px] p-6 bg-white/5"
                    >
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {/* Capacity Type */}
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 block mb-3">
                                    Capacity Type
                                </label>

                                <select
                                    value={capacity.capacity_type ?? ""}
                                    onChange={(e) =>
                                        updateCapacityField(
                                            index,
                                            "capacity_type",
                                            e.target.value,
                                        )
                                    }
                                    className="w-full bg-[#0a192f] border-white/10 rounded-2xl text-white"
                                >
                                    <option value="">Select Type</option>
                                    <option value="spinning">Spinning</option>
                                    <option value="weaving">Weaving</option>
                                    <option value="knitting">Knitting</option>
                                    <option value="dyeing">Dyeing</option>
                                    <option value="printing">Printing</option>
                                    <option value="garment">Garment</option>
                                </select>
                            </div>

                            {/* Item Name */}
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 block mb-3">
                                    Line / Machine
                                </label>

                                <input
                                    type="text"
                                    value={capacity.item_name ?? ""}
                                    onChange={(e) => {
                                        const updated = [...data.capacities];

                                        updated[index].item_name =
                                            e.target.value;

                                        setData("capacities", updated);
                                    }}
                                    placeholder="Ring Spinning"
                                    className="w-full bg-[#0a192f] border-white/10 rounded-2xl text-white"
                                />
                            </div>

                            {/* Capacity Value */}
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 block mb-3">
                                    Capacity Value
                                </label>

                                <input
                                    type="number"
                                    value={capacity.capacity_value ?? ""}
                                    onChange={(e) => {
                                        const updated = [...data.capacities];

                                        updated[index].capacity_value =
                                            e.target.value;

                                        setData("capacities", updated);
                                    }}
                                    className="w-full bg-[#0a192f] border-white/10 rounded-2xl text-white"
                                />
                            </div>

                            {/* Unit */}
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 block mb-3">
                                    Capacity Unit
                                </label>

                                <input
                                    type="text"
                                    value={capacity.capacity_unit ?? ""}
                                    onChange={(e) => {
                                        const updated = [...data.capacities];

                                        updated[index].capacity_unit =
                                            e.target.value;

                                        setData("capacities", updated);
                                    }}
                                    placeholder="kg/day"
                                    className="w-full bg-[#0a192f] border-white/10 rounded-2xl text-white"
                                />
                            </div>

                            {/* Category */}
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 block mb-3">
                                    Capacity Category
                                </label>

                                <select
                                    value={capacity.capacity_category ?? ""}
                                    onChange={(e) => {
                                        const updated = [...data.capacities];

                                        updated[index].capacity_category =
                                            e.target.value;

                                        setData("capacities", updated);
                                    }}
                                    className="w-full bg-[#0a192f] border-white/10 rounded-2xl text-white"
                                >
                                    <option value="installed">Installed</option>

                                    <option value="actual">Actual</option>

                                    <option value="idle">Idle</option>
                                </select>
                            </div>

                            {/* Machine Count */}
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 block mb-3">
                                    Machine Count
                                </label>

                                <input
                                    type="number"
                                    value={capacity.machine_count ?? ""}
                                    onChange={(e) => {
                                        const updated = [...data.capacities];

                                        updated[index].machine_count =
                                            e.target.value;

                                        setData("capacities", updated);
                                    }}
                                    className="w-full bg-[#0a192f] border-white/10 rounded-2xl text-white"
                                />
                            </div>

                            {/* Shift */}
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 block mb-3">
                                    Shift Info
                                </label>

                                <input
                                    type="text"
                                    value={capacity.shift_info ?? ""}
                                    onChange={(e) => {
                                        const updated = [...data.capacities];

                                        updated[index].shift_info =
                                            e.target.value;

                                        setData("capacities", updated);
                                    }}
                                    placeholder="3 Shift"
                                    className="w-full bg-[#0a192f] border-white/10 rounded-2xl text-white"
                                />
                            </div>

                            {/* Notes */}
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 block mb-3">
                                    Notes
                                </label>

                                <input
                                    type="text"
                                    value={capacity.notes ?? ""}
                                    onChange={(e) => {
                                        const updated = [...data.capacities];

                                        updated[index].notes = e.target.value;

                                        setData("capacities", updated);
                                    }}
                                    className="w-full bg-[#0a192f] border-white/10 rounded-2xl text-white"
                                />
                            </div>
                        </div>
                        {/* Save */}

                        {/* DELETE BUTTON */}
                        <div className="mt-6 flex justify-end">
                            <button
                                type="button"
                                onClick={() => removeCapacity(capacity, index)}
                                className="bg-red-500 text-white px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest"
                            >
                                Remove
                            </button>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}
