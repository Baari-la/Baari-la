import Swal from "sweetalert2";
import { router } from "@inertiajs/react";

export default function CompanyLinksSection({ data, setData, company }) {
    const updateLinkField = (index, field, value) => {
        const updated = (data.links || []).map((item, i) =>
            i === index
                ? {
                      ...item,
                      [field]: value,
                  }
                : item,
        );

        setData("links", updated);
    };

    const removeLink = (link, index) => {
        Swal.fire({
            title: "Delete Link?",
            text: "This link record will be permanently removed.",
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

            if (!link.id) {
                setData(
                    "links",
                    (data.links || []).filter((_, i) => i !== index),
                );

                Swal.fire({
                    icon: "success",
                    title: "Removed",
                    text: "Link removed from form.",
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
                route("companies.links.destroy", [company.id, link.id]),
                {
                    preserveScroll: true,
                    preserveState: true,

                    onSuccess: () => {
                        setData(
                            "links",
                            (data.links || []).filter((_, i) => i !== index),
                        );

                        Swal.fire({
                            icon: "success",
                            title: "Deleted",
                            text: "Link deleted successfully.",
                            confirmButtonColor: "#22c55e",
                        });
                    },

                    onError: () => {
                        Swal.fire({
                            icon: "error",
                            title: "Delete Failed",
                            text: "Unable to delete link.",
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
                <h3 className="text-cyan-400 text-xs font-black uppercase tracking-[0.3em]">
                    Company Links
                </h3>

                <button
                    type="button"
                    onClick={() =>
                        setData("links", [
                            ...(data.links || []),
                            {
                                id: null,
                                link_type: "website",
                                url: "",
                            },
                        ])
                    }
                    className="bg-cyan-500 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase"
                >
                    + Add Link
                </button>
            </div>

            <div className="space-y-4">
                {(data.links || []).map((link, index) => (
                    <div
                        key={link.id || index}
                        className="grid grid-cols-1 md:grid-cols-4 gap-4"
                    >
                        {/* TYPE */}
                        <select
                            value={link.link_type || "website"}
                            onChange={(e) =>
                                updateLinkField(
                                    index,
                                    "link_type",
                                    e.target.value,
                                )
                            }
                            className="bg-[#0a192f] border border-white/10 rounded-2xl text-white p-3"
                        >
                            <option value="website">Website</option>

                            <option value="instagram">Instagram</option>

                            <option value="facebook">Facebook</option>

                            <option value="linkedin">LinkedIn</option>

                            <option value="youtube">YouTube</option>

                            <option value="tiktok">TikTok</option>

                            <option value="marketplace">Marketplace</option>
                        </select>

                        {/* URL */}
                        <input
                            type="text"
                            value={link.url || ""}
                            onChange={(e) =>
                                updateLinkField(index, "url", e.target.value)
                            }
                            placeholder="https://..."
                            className="md:col-span-2 bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                        />

                        {/* DELETE */}
                        <button
                            type="button"
                            onClick={() => removeLink(link, index)}
                            className="bg-red-500 hover:bg-red-400 text-white px-5 py-3 rounded-2xl text-xs font-black uppercase"
                        >
                            Remove Link
                        </button>
                    </div>
                ))}
            </div>
        </div>
    );
}
