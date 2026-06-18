import Swal from "sweetalert2";
import { router } from "@inertiajs/react";

export default function CompanyProductsSection({ data, setData, company }) {
    return (
        <div className="pt-6 border-t border-white/5">
            <div className="flex justify-between items-center mb-6">
                <h3 className="text-yellow-500 text-xs font-black uppercase tracking-[0.3em]">
                    Company Products
                </h3>

                <button
                    type="button"
                    onClick={() =>
                        setData("products", [
                            ...(data.products || []),
                            {
                                id: null,
                                product_name: "",
                                product_name_en: "",
                                hs_code: "",
                                category: "",
                                description: "",
                                is_primary: false,
                            },
                        ])
                    }
                    className="bg-yellow-500 text-[#0a192f] px-4 py-2 rounded-xl text-[10px] font-black uppercase"
                >
                    + Add Product
                </button>
            </div>

            <div className="space-y-6">
                {(data.products || []).map((product, index) => (
                    <div
                        key={product.id || index}
                        className="bg-white/5 border border-white/10 rounded-2xl p-5 space-y-4"
                    >
                        {/* Product Name */}

                        <input
                            type="text"
                            value={product.product_name || ""}
                            onChange={(e) => {
                                const updated = data.products.map((p, i) =>
                                    i === index
                                        ? {
                                              ...p,
                                              product_name: e.target.value,
                                          }
                                        : p,
                                );

                                setData("products", updated);
                            }}
                            placeholder="Product Name"
                            className="w-full bg-white/5 border border-white/10 rounded-xl text-white p-3"
                        />

                        {/* English Name */}

                        <input
                            type="text"
                            value={product.product_name_en || ""}
                            onChange={(e) => {
                                const updated = data.products.map((p, i) =>
                                    i === index
                                        ? {
                                              ...p,
                                              product_name_en: e.target.value,
                                          }
                                        : p,
                                );

                                setData("products", updated);
                            }}
                            placeholder="Product Name (English)"
                            className="w-full bg-white/5 border border-white/10 rounded-xl text-white p-3"
                        />

                        <div className="grid md:grid-cols-2 gap-4">
                            {/* HS Code */}

                            <input
                                type="text"
                                value={product.hs_code || ""}
                                onChange={(e) => {
                                    const updated = data.products.map((p, i) =>
                                        i === index
                                            ? {
                                                  ...p,
                                                  hs_code: e.target.value,
                                              }
                                            : p,
                                    );

                                    setData("products", updated);
                                }}
                                placeholder="HS Code"
                                className="bg-white/5 border border-white/10 rounded-xl text-white p-3"
                            />

                            {/* Category */}

                            <input
                                type="text"
                                value={product.category || ""}
                                onChange={(e) => {
                                    const updated = data.products.map((p, i) =>
                                        i === index
                                            ? {
                                                  ...p,
                                                  category: e.target.value,
                                              }
                                            : p,
                                    );

                                    setData("products", updated);
                                }}
                                placeholder="Category"
                                className="bg-white/5 border border-white/10 rounded-xl text-white p-3"
                            />
                        </div>

                        {/* Description */}

                        <textarea
                            rows="3"
                            value={product.description || ""}
                            onChange={(e) => {
                                const updated = data.products.map((p, i) =>
                                    i === index
                                        ? {
                                              ...p,
                                              description: e.target.value,
                                          }
                                        : p,
                                );

                                setData("products", updated);
                            }}
                            placeholder="Product Description"
                            className="w-full bg-white/5 border border-white/10 rounded-xl text-white p-3"
                        />

                        {/* Primary Product */}

                        <label className="flex items-center gap-2 text-white text-sm">
                            <input
                                type="checkbox"
                                checked={product.is_primary || false}
                                onChange={(e) => {
                                    const updated = data.products.map(
                                        (p, i) => ({
                                            ...p,
                                            is_primary:
                                                i === index
                                                    ? e.target.checked
                                                    : false,
                                        }),
                                    );

                                    setData("products", updated);
                                }}
                            />
                            Primary Product
                        </label>

                        {/* Delete */}

                        <div className="flex justify-end">
                            <button
                                type="button"
                                onClick={() => {
                                    Swal.fire({
                                        title: "Delete Product?",
                                        text: "This product record will be permanently removed.",
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

                                        if (!product.id) {
                                            setData(
                                                "products",
                                                data.products.filter(
                                                    (_, i) => i !== index,
                                                ),
                                            );

                                            return;
                                        }

                                        router.delete(
                                            route(
                                                "companies.products.destroy",
                                                [company.id, product.id],
                                            ),
                                            {
                                                preserveScroll: true,

                                                onSuccess: () => {
                                                    setData(
                                                        "products",
                                                        data.products.filter(
                                                            (_, i) =>
                                                                i !== index,
                                                        ),
                                                    );
                                                },
                                            },
                                        );
                                    });
                                }}
                                className="bg-red-500 hover:bg-red-400 text-white px-5 py-3 rounded-xl text-xs font-black uppercase"
                            >
                                Remove Product
                            </button>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}
