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
            title: "Remove Image?",
            text: "This image will be removed from the company media profile.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#ef4444",
            cancelButtonColor: "#475569",
            confirmButtonText: "Remove",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (!result.isConfirmed) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | UNSAVED IMAGE
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
            | CANONICAL MEDIA
            |--------------------------------------------------------------------------
            */

            router.delete(
                route("companies.identity-media.destroy", [
                    company.id,
                    image.id,
                ]),
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

                    onError: () => {
                        Swal.fire({
                            icon: "error",
                            title: "Delete Failed",
                            text: "Unable to delete this canonical media.",
                        });
                    },
                },
            );
        });
    };

    return (
        <div className="pt-6 border-t border-white/5">
            {/* HEADER */}
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
                                image_path: "",
                                title: "",
                                caption: "",
                                is_featured: false,
                                sort_order: 0,
                                verification_status: "draft",
                                image_file: null,
                            },
                        ])
                    }
                    className="
                        bg-pink-500
                        hover:bg-pink-400
                        text-white
                        px-4
                        py-2
                        rounded-xl
                        text-[10px]
                        font-black
                        uppercase
                        tracking-wide
                        transition
                    "
                >
                    + Add Image
                </button>
            </div>

            {/* IMAGE LIST */}
            <div className="space-y-5">
                {(data.images || []).map((image, index) => {
                    const previewUrl = image.image_file
                        ? URL.createObjectURL(image.image_file)
                        : image.image_url || "";

                    const isSaved = Boolean(image.id);

                    return (
                        <div
                            key={image.id || `new-image-${index}`}
                            className="
                                bg-white/[0.035]
                                border
                                border-white/10
                                rounded-3xl
                                p-4
                                md:p-5
                                hover:border-white/15
                                transition
                            "
                        >
                            {/* INPUT ROW */}
                            <div
                                className="
                                    grid
                                    grid-cols-1
                                    md:grid-cols-4
                                    gap-3
                                "
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
                                    className="
                                        w-full
                                        rounded-xl
                                        border
                                        border-slate-600
                                        bg-slate-900
                                        px-3
                                        py-3
                                        text-sm
                                        text-white
                                        outline-none
                                        focus:border-blue-500
                                    "
                                >
                                    <option value="factory">Factory</option>

                                    <option value="product">Product</option>

                                    <option value="machine">Machine</option>

                                    <option value="office">Office</option>

                                    <option value="warehouse">Warehouse</option>

                                    <option value="team">Team</option>

                                    <option value="certificate">
                                        Certificate
                                    </option>
                                </select>

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
                                    placeholder="Caption"
                                    className="
                                        w-full
                                        rounded-xl
                                        border
                                        border-slate-600
                                        bg-slate-900
                                        px-3
                                        py-3
                                        text-sm
                                        text-white
                                        placeholder-slate-500
                                        outline-none
                                        focus:border-blue-500
                                    "
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
                                    className="
                                        w-full
                                        rounded-xl
                                        border
                                        border-slate-600
                                        bg-slate-900
                                        px-2
                                        py-2
                                        text-sm
                                        text-slate-300
                                        file:mr-3
                                        file:rounded-lg
                                        file:border-0
                                        file:bg-blue-600
                                        file:px-3
                                        file:py-2
                                        file:text-xs
                                        file:font-bold
                                        file:text-white
                                        hover:file:bg-blue-500
                                    "
                                />

                                {/* STATUS */}
                                <div
                                    className="
                                        flex
                                        items-center
                                        justify-start
                                        md:justify-end
                                    "
                                >
                                    {isSaved ? (
                                        <span
                                            className="
                                                inline-flex
                                                items-center
                                                gap-2
                                                rounded-full
                                                border
                                                border-emerald-400/20
                                                bg-emerald-400/10
                                                px-3
                                                py-2
                                                text-[9px]
                                                font-black
                                                uppercase
                                                tracking-wider
                                                text-emerald-400
                                            "
                                        >
                                            <span
                                                className="
                                                    h-1.5
                                                    w-1.5
                                                    rounded-full
                                                    bg-emerald-400
                                                "
                                            />
                                            Canonical Media
                                        </span>
                                    ) : (
                                        <span
                                            className="
                                                inline-flex
                                                items-center
                                                gap-2
                                                rounded-full
                                                border
                                                border-amber-400/20
                                                bg-amber-400/10
                                                px-3
                                                py-2
                                                text-[9px]
                                                font-black
                                                uppercase
                                                tracking-wider
                                                text-amber-400
                                            "
                                        >
                                            New Image
                                        </span>
                                    )}
                                </div>
                            </div>

                            {/* PREVIEW AREA */}
                            <div className="mt-4 flex flex-wrap items-end gap-4">
                                {previewUrl ? (
                                    <div className="relative group">
                                        <img
                                            src={previewUrl}
                                            alt={
                                                image.caption || "Company media"
                                            }
                                            className="
                                                w-[220px]
                                                h-[160px]
                                                object-cover
                                                rounded-2xl
                                                border
                                                border-white/10
                                                shadow-lg
                                            "
                                        />

                                        {/* MEDIA TYPE */}
                                        <div
                                            className="
                                                absolute
                                                left-2
                                                top-2
                                                rounded-lg
                                                bg-black/70
                                                backdrop-blur-sm
                                                px-2
                                                py-1
                                                text-[9px]
                                                font-black
                                                uppercase
                                                tracking-wider
                                                text-white
                                            "
                                        >
                                            {image.image_type || "Factory"}
                                        </div>
                                    </div>
                                ) : (
                                    <div
                                        className="
                                            flex
                                            w-[220px]
                                            h-[160px]
                                            items-center
                                            justify-center
                                            rounded-2xl
                                            border
                                            border-dashed
                                            border-white/15
                                            bg-slate-900/50
                                            text-xs
                                            text-slate-500
                                        "
                                    >
                                        No image selected
                                    </div>
                                )}

                                {/* REMOVE */}
                                <button
                                    type="button"
                                    onClick={() => removeImage(image, index)}
                                    className="
                                        mb-1
                                        rounded-xl
                                        border
                                        border-red-400/20
                                        bg-red-500/10
                                        px-4
                                        py-2
                                        text-[10px]
                                        font-black
                                        uppercase
                                        tracking-wide
                                        text-red-400
                                        transition
                                        hover:bg-red-500
                                        hover:text-white
                                    "
                                >
                                    Remove
                                </button>
                            </div>
                        </div>
                    );
                })}
            </div>
        </div>
    );
}
