import Swal from "sweetalert2";
import { router } from "@inertiajs/react";

export default function ImagesSection({ data, setData, company }) {
    const updateImageField = (index, field, value) => {
        const updated = (data.images || []).map((item, i) =>
            i === index
                ? {
                      ...item,
                      [field]: value,
                  }
                : item,
        );

        setData("images", updated);
    };

    const removeImage = (image, index) => {
        Swal.fire({
            title: "Delete Image?",
            text: "This image will be permanently removed.",
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

            if (!image.id) {
                setData(
                    "images",
                    (data.images || []).filter((_, i) => i !== index),
                );

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | DATABASE RECORD
            |--------------------------------------------------------------------------
            */

            router.delete(
                route("companies.images.destroy", [company.id, image.id]),
                {
                    preserveScroll: true,
                    preserveState: true,

                    onSuccess: () => {
                        setData(
                            "images",
                            (data.images || []).filter((_, i) => i !== index),
                        );

                        Swal.fire({
                            icon: "success",
                            title: "Deleted",
                            text: "Image deleted successfully.",
                        });
                    },
                },
            );
        });
    };

    return (
        <div className="pt-6 border-t border-white/5">
            <div className="flex justify-between items-center mb-6">
                <h3 className="text-pink-400 text-xs font-black uppercase tracking-[0.3em]">
                    Company Images
                </h3>

                <button
                    type="button"
                    onClick={() =>
                        setData("images", [
                            ...(data.images || []),
                            {
                                id: null,

                                image_type: "factory",

                                image_url: "",

                                image_file: null,

                                caption: "",
                            },
                        ])
                    }
                    className="bg-pink-500 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase"
                >
                    + Add Image
                </button>
            </div>

            <div className="space-y-6">
                {(data.images || []).map((image, index) => (
                    <div
                        key={image.id || index}
                        className="grid grid-cols-1 md:grid-cols-4 gap-4 bg-white/5 border border-white/5 rounded-3xl p-5"
                    >
                        {/* TYPE */}

                        <select
                            value={image.image_type || "factory"}
                            onChange={(e) =>
                                updateImageField(
                                    index,
                                    "image_type",
                                    e.target.value,
                                )
                            }
                        >
                            <option value="factory">Factory</option>

                            <option value="product">Product</option>

                            <option value="machine">Machine</option>

                            <option value="office">Office</option>

                            <option value="warehouse">Warehouse</option>

                            <option value="team">Team</option>

                            <option value="certificate">Certificate</option>
                        </select>

                        {/* URL */}

                        <input
                            type="text"
                            value={image.image_url || ""}
                            onChange={(e) =>
                                updateImageField(
                                    index,
                                    "image_url",
                                    e.target.value,
                                )
                            }
                        />

                        {/* CAPTION */}

                        <input
                            type="text"
                            value={image.caption || ""}
                            onChange={(e) =>
                                updateImageField(
                                    index,
                                    "caption",
                                    e.target.value,
                                )
                            }
                        />

                        {/* FILE */}

                        <input
                            type="file"
                            accept="image/*"
                            onChange={(e) =>
                                updateImageField(
                                    index,
                                    "image_file",
                                    e.target.files?.[0] || null,
                                )
                            }
                        />

                        {/* PREVIEW */}

                        {(image.image_url || image.image_file) && (
                            <img
                                src={
                                    image.image_file
                                        ? URL.createObjectURL(image.image_file)
                                        : image.image_url
                                }
                                alt="preview"
                                className="w-full h-64 object-cover rounded-2xl"
                            />
                        )}

                        {/* DELETE */}

                        <button
                            type="button"
                            onClick={() => removeImage(image, index)}
                            className="bg-red-500 text-white px-5 py-3 rounded-2xl text-xs font-black uppercase"
                        >
                            Remove Image
                        </button>
                    </div>
                ))}
            </div>
        </div>
    );
}
