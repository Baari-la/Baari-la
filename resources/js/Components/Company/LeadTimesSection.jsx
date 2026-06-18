import Swal from "sweetalert2";
import { router } from "@inertiajs/react";

export default function CompanyLeadTimesSection({ data, setData, company }) {
    return (
        <div className="pt-6 border-t border-white/5">
            <div className="flex justify-between items-center mb-6">
                <h3 className="text-yellow-400 text-xs font-black uppercase tracking-[0.3em]">
                    Lead Times
                </h3>

                <button
                    type="button"
                    onClick={() =>
                        setData("lead_times", [
                            ...(data.lead_times || []),
                            {
                                id: null,
                                lead_time_type: "",
                                days: "",
                                notes: "",
                            },
                        ])
                    }
                    className="bg-yellow-500 text-[#0a192f] px-4 py-2 rounded-xl text-[10px] font-black uppercase"
                >
                    + Add Lead Time
                </button>
            </div>

            <div className="space-y-6">
                {(data.lead_times || []).map((leadTime, index) => (
                    <div
                        key={leadTime.id || index}
                        className="bg-white/5 border border-white/10 rounded-3xl p-6 space-y-5"
                    >
                        {/* ROW 1 */}
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                    Lead Time Type
                                </label>

                                <input
                                    type="text"
                                    value={leadTime.lead_time_type || ""}
                                    onChange={(e) => {
                                        const updated = data.lead_times.map(
                                            (item, i) =>
                                                i === index
                                                    ? {
                                                          ...item,
                                                          lead_time_type:
                                                              e.target.value,
                                                      }
                                                    : item,
                                        );

                                        setData("lead_times", updated);
                                    }}
                                    placeholder="Sampling / Production / Repeat Order"
                                    className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                />
                            </div>

                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                    Days
                                </label>

                                <input
                                    type="number"
                                    value={leadTime.days || ""}
                                    onChange={(e) => {
                                        const updated = data.lead_times.map(
                                            (item, i) =>
                                                i === index
                                                    ? {
                                                          ...item,
                                                          days: e.target.value,
                                                      }
                                                    : item,
                                        );

                                        setData("lead_times", updated);
                                    }}
                                    placeholder="30"
                                    className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                />
                            </div>
                        </div>

                        {/* NOTES */}
                        <div>
                            <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                Notes
                            </label>

                            <textarea
                                rows="3"
                                value={leadTime.notes || ""}
                                onChange={(e) => {
                                    const updated = data.lead_times.map(
                                        (item, i) =>
                                            i === index
                                                ? {
                                                      ...item,
                                                      notes: e.target.value,
                                                  }
                                                : item,
                                    );

                                    setData("lead_times", updated);
                                }}
                                placeholder="Additional information..."
                                className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-4"
                            />
                        </div>

                        {/* DELETE */}
                        <div className="flex justify-end">
                            <button
                                type="button"
                                onClick={() => {
                                    Swal.fire({
                                        title: "Delete Lead Time?",
                                        text: "This lead time record will be permanently removed.",
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
                                        |----------------------------------------------------------
                                        | UNSAVED RECORD
                                        |----------------------------------------------------------
                                        */

                                        if (!leadTime.id) {
                                            const updated = (
                                                data.lead_times || []
                                            ).filter((_, i) => i !== index);

                                            setData("lead_times", updated);

                                            Swal.fire({
                                                icon: "success",
                                                title: "Removed",
                                                text: "Lead Time removed from form.",
                                                timer: 1500,
                                                showConfirmButton: false,
                                            });

                                            return;
                                        }

                                        /*
                                        |----------------------------------------------------------
                                        | DELETE DATABASE
                                        |----------------------------------------------------------
                                        */

                                        router.delete(
                                            route(
                                                "companies.lead-times.destroy",
                                                [company.id, leadTime.id],
                                            ),
                                            {
                                                preserveScroll: true,

                                                onSuccess: () => {
                                                    const updated = (
                                                        data.lead_times || []
                                                    ).filter(
                                                        (_, i) => i !== index,
                                                    );

                                                    setData(
                                                        "lead_times",
                                                        updated,
                                                    );

                                                    Swal.fire({
                                                        icon: "success",
                                                        title: "Deleted",
                                                        text: "Lead Time deleted successfully.",
                                                        confirmButtonColor:
                                                            "#22c55e",
                                                    });
                                                },

                                                onError: () => {
                                                    Swal.fire({
                                                        icon: "error",
                                                        title: "Delete Failed",
                                                        text: "Unable to delete Lead Time.",
                                                        confirmButtonColor:
                                                            "#ef4444",
                                                    });
                                                },
                                            },
                                        );
                                    });
                                }}
                                className="bg-red-500 hover:bg-red-400 text-white px-5 py-3 rounded-2xl text-xs font-black uppercase"
                            >
                                Remove Lead Time
                            </button>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}
