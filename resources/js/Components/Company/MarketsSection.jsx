import Swal from "sweetalert2";
import { router } from "@inertiajs/react";

export default function CompanyMarketsSection({ data, setData, company }) {
    return (
        <div className="pt-6 border-t border-white/5">
            <div className="flex justify-between items-center mb-6">
                <h3 className="text-blue-400 text-xs font-black uppercase tracking-[0.3em]">
                    Markets
                </h3>

                <button
                    type="button"
                    onClick={() =>
                        setData("markets", [
                            ...(data.markets || []),
                            {
                                id: null,
                                country_name: "",
                                market_type: "export",
                            },
                        ])
                    }
                    className="bg-blue-500 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase"
                >
                    + Add Market
                </button>
            </div>

            <div className="space-y-4">
                {(data.markets || []).map((market, index) => (
                    <div
                        key={market.id || index}
                        className="grid grid-cols-1 md:grid-cols-3 gap-4 bg-white/5 border border-white/10 rounded-2xl p-4"
                    >
                        {/* Country */}

                        <input
                            type="text"
                            value={market.country_name || ""}
                            onChange={(e) => {
                                const updated = data.markets.map((item, i) =>
                                    i === index
                                        ? {
                                              ...item,
                                              country_name: e.target.value,
                                          }
                                        : item,
                                );

                                setData("markets", updated);
                            }}
                            placeholder="Country / Region"
                            className="md:col-span-2 bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                        />

                        {/* Market Type */}

                        <div className="flex gap-2">
                            <select
                                value={market.market_type || "export"}
                                onChange={(e) => {
                                    const updated = data.markets.map(
                                        (item, i) =>
                                            i === index
                                                ? {
                                                      ...item,
                                                      market_type:
                                                          e.target.value,
                                                  }
                                                : item,
                                    );

                                    setData("markets", updated);
                                }}
                                className="flex-1 bg-[#0a192f] border border-white/10 rounded-2xl text-white p-3"
                            >
                                <option value="export" className="bg-[#0a192f]">
                                    Export
                                </option>

                                <option value="import" className="bg-[#0a192f]">
                                    Import
                                </option>

                                <option
                                    value="domestic"
                                    className="bg-[#0a192f]"
                                >
                                    Domestic
                                </option>
                            </select>

                            {/* Delete */}

                            <button
                                type="button"
                                onClick={() => {
                                    Swal.fire({
                                        title: "Delete Market?",
                                        text: "This market record will be permanently removed.",
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

                                        if (!market.id) {
                                            const updated = (
                                                data.markets || []
                                            ).filter((_, i) => i !== index);

                                            setData("markets", updated);

                                            return;
                                        }

                                        /*
                                            |----------------------------------------------------------
                                            | DELETE DATABASE
                                            |----------------------------------------------------------
                                            */

                                        router.delete(
                                            route("companies.markets.destroy", [
                                                company.id,
                                                market.id,
                                            ]),
                                            {
                                                preserveScroll: true,

                                                onSuccess: () => {
                                                    const updated = (
                                                        data.markets || []
                                                    ).filter(
                                                        (_, i) => i !== index,
                                                    );

                                                    setData("markets", updated);

                                                    Swal.fire({
                                                        icon: "success",
                                                        title: "Deleted",
                                                        text: "Market deleted successfully.",
                                                        confirmButtonColor:
                                                            "#22c55e",
                                                    });
                                                },

                                                onError: () => {
                                                    Swal.fire({
                                                        icon: "error",
                                                        title: "Delete Failed",
                                                        text: "Unable to delete market.",
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
                                Remove
                            </button>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}
