import Swal from "sweetalert2";
import { router } from "@inertiajs/react";

export default function CompanyMoqsSection({ data, setData, company }) {
    const updateMoqField = (index, field, value) => {
        const updated = (data.moqs || []).map((item, i) =>
            i === index
                ? {
                      ...item,
                      [field]: value,
                  }
                : item,
        );

        setData("moqs", updated);
    };

    const removeMoq = (moq, index) => {
        Swal.fire({
            title: "Delete MOQ?",
            text: "This MOQ record will be permanently removed.",
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

            /*
            |--------------------------------------------------------------------------
            | UNSAVED RECORD
            |--------------------------------------------------------------------------
            */

            if (!moq.id) {
                setData(
                    "moqs",
                    (data.moqs || []).filter((_, i) => i !== index),
                );

                Swal.fire({
                    icon: "success",
                    title: "Removed",
                    text: "MOQ removed from form.",
                    timer: 1500,
                    showConfirmButton: false,
                });

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | DATABASE RECORD
            |--------------------------------------------------------------------------
            */

            router.delete(
                route("companies.moqs.destroy", [company.id, moq.id]),
                {
                    preserveScroll: true,
                    preserveState: true,

                    onSuccess: () => {
                        setData(
                            "moqs",
                            (data.moqs || []).filter((_, i) => i !== index),
                        );

                        Swal.fire({
                            icon: "success",
                            title: "Deleted",
                            text: "MOQ deleted successfully.",
                            confirmButtonColor: "#22c55e",
                        });
                    },

                    onError: () => {
                        Swal.fire({
                            icon: "error",
                            title: "Delete Failed",
                            text: "Unable to delete MOQ.",
                            confirmButtonColor: "#ef4444",
                        });
                    },
                },
            );
        });
    };

    return (
        <div className="pt-6 border-t border-white/5">
            <div className="flex justify-between items-center mb-6">
                <h3 className="text-green-400 text-xs font-black uppercase tracking-[0.3em]">
                    Minimum Order Quantity (MOQ)
                </h3>

                <button
                    type="button"
                    onClick={() =>
                        setData("moqs", [
                            ...(data.moqs || []),
                            {
                                id: null,
                                product_name: "",
                                minimum_quantity: "",
                                unit: "",
                                notes: "",
                            },
                        ])
                    }
                    className="bg-green-500 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase"
                >
                    + Add MOQ
                </button>
            </div>

            {(data.moqs || []).length > 0 ? (
                <div className="space-y-6">
                    {(data.moqs || []).map((moq, index) => (
                        <div
                            key={moq.id || index}
                            className="bg-white/5 border border-white/10 rounded-3xl p-6"
                        >
                            <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                                {/* PRODUCT */}
                                <div>
                                    <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                        Product Name
                                    </label>

                                    <input
                                        type="text"
                                        value={moq.product_name || ""}
                                        onChange={(e) =>
                                            updateMoqField(
                                                index,
                                                "product_name",
                                                e.target.value,
                                            )
                                        }
                                        className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                        placeholder="Cotton Yarn"
                                    />
                                </div>

                                {/* MOQ */}
                                <div>
                                    <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                        Minimum Quantity
                                    </label>

                                    <input
                                        type="number"
                                        value={moq.minimum_quantity || ""}
                                        onChange={(e) =>
                                            updateMoqField(
                                                index,
                                                "minimum_quantity",
                                                e.target.value,
                                            )
                                        }
                                        className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                        placeholder="1000"
                                    />
                                </div>

                                {/* UNIT */}
                                <div>
                                    <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                        Unit
                                    </label>

                                    <select
                                        value={moq.unit || ""}
                                        onChange={(e) =>
                                            updateMoqField(
                                                index,
                                                "unit",
                                                e.target.value,
                                            )
                                        }
                                        className="w-full bg-[#0a192f] border border-white/10 rounded-2xl text-white p-3"
                                    >
                                        <option value="">Select Unit</option>
                                        <option value="Kg">Kg</option>
                                        <option value="Ton">Ton</option>
                                        <option value="Meter">Meter</option>
                                        <option value="Yard">Yard</option>
                                        <option value="Pcs">Pcs</option>
                                        <option value="Dozen">Dozen</option>
                                    </select>
                                </div>

                                {/* DELETE */}
                                <div className="flex items-end">
                                    <button
                                        type="button"
                                        onClick={() => removeMoq(moq, index)}
                                        className="w-full bg-red-500 hover:bg-red-400 text-white px-5 py-3 rounded-2xl text-xs font-black uppercase"
                                    >
                                        Remove MOQ
                                    </button>
                                </div>
                            </div>

                            {/* NOTES */}
                            <div className="mt-4">
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                    Notes
                                </label>

                                <input
                                    type="text"
                                    value={moq.notes || ""}
                                    onChange={(e) =>
                                        updateMoqField(
                                            index,
                                            "notes",
                                            e.target.value,
                                        )
                                    }
                                    className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                    placeholder="Optional notes"
                                />
                            </div>
                        </div>
                    ))}
                </div>
            ) : (
                <div className="text-center py-8 text-slate-400 border border-dashed rounded-2xl">
                    No MOQ information added yet.
                </div>
            )}
        </div>
    );
}
