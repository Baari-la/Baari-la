import Swal from "sweetalert2";
import { router } from "@inertiajs/react";
import ProductCategories from "@/constants/ProductCategories";
import ProductApplications from "@/constants/ProductApplications";
import ProductStatuses from "@/constants/ProductStatuses";

export default function CompanyProductsSection({ data, setData, company }) {
    /*
    |--------------------------------------------------------------------------
    | Update Product Field
    |--------------------------------------------------------------------------
    */

    const updateProduct = (index, field, value) => {
        setData(
            "products",

            data.products.map((product, i) =>
                i === index
                    ? {
                          ...product,

                          [field]: value,
                      }
                    : product,
            ),
        );
    };

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    return (
        <div className="pt-6 border-t border-white/5">
            <div className="flex justify-between items-center mb-6">
                <h3 className="text-yellow-500 text-xs font-black uppercase tracking-[0.3em]">
                    Company Products
                </h3>

                <button
                    type="button"
                    onClick={() =>
                        setData(
                            "products",

                            [
                                ...(data.products || []),

                                {
                                    id: null,

                                    product_name: "",

                                    product_name_en: "",

                                    hs_code: "",

                                    category: "",

                                    application: "",

                                    description: "",

                                    is_primary: false,

                                    status: "Active",
                                },
                            ],
                        )
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
                            onChange={(e) =>
                                updateProduct(
                                    index,
                                    "product_name",
                                    e.target.value,
                                )
                            }
                            placeholder="Product Name"
                            className="w-full bg-white/5 border border-white/10 rounded-xl text-white p-3"
                        />

                        {/* Product Name (English) */}

                        <input
                            type="text"
                            value={product.product_name_en || ""}
                            onChange={(e) =>
                                updateProduct(
                                    index,
                                    "product_name_en",
                                    e.target.value,
                                )
                            }
                            placeholder="Product Name (English)"
                            className="w-full bg-white/5 border border-white/10 rounded-xl text-white p-3"
                        />

                        <div className="grid md:grid-cols-2 gap-4">
                            {/* HS Code */}

                            <input
                                type="text"
                                value={product.hs_code || ""}
                                onChange={(e) =>
                                    updateProduct(
                                        index,
                                        "hs_code",
                                        e.target.value,
                                    )
                                }
                                placeholder="HS Code"
                                className="bg-white/5 border border-white/10 rounded-xl text-white p-3"
                            />

                            {/* Product Category */}

                            <select
                                value={product.category || ""}
                                onChange={(e) =>
                                    updateProduct(
                                        index,
                                        "category",
                                        e.target.value,
                                    )
                                }
                                className="bg-white/5 border border-white/10 rounded-xl text-white p-3"
                            >
                                <option value="">Select Category</option>

                                {ProductCategories.map((category) => (
                                    <option
                                        key={category.id}
                                        value={category.id}
                                    >
                                        {category.label}
                                    </option>
                                ))}
                            </select>
                        </div>

                        {/* Product Application */}

                        <select
                            value={product.application || ""}
                            onChange={(e) =>
                                updateProduct(
                                    index,
                                    "application",
                                    e.target.value,
                                )
                            }
                            className="w-full bg-white/5 border border-white/10 rounded-xl text-white p-3"
                        >
                            <option value="">Select Application</option>

                            {ProductApplications.map((application) => (
                                <option
                                    key={application.id}
                                    value={application.id}
                                    className="text-black"
                                >
                                    {application.label}
                                </option>
                            ))}
                        </select>
                        {/* Product Description */}

                        <textarea
                            rows="3"
                            value={product.description || ""}
                            onChange={(e) =>
                                updateProduct(
                                    index,
                                    "description",
                                    e.target.value,
                                )
                            }
                            placeholder="Product Description"
                            className="w-full bg-white/5 border border-white/10 rounded-xl text-white p-3"
                        />

                        {/* Product Status */}

                        <select
                            value={product.status || "active"}
                            onChange={(e) =>
                                updateProduct(index, "status", e.target.value)
                            }
                            className="w-full bg-white/5 border border-white/10 rounded-xl text-white p-3"
                        >
                            {ProductStatuses.map((status) => (
                                <option
                                    key={status.id}
                                    value={status.id}
                                    className="text-black"
                                >
                                    {status.label}
                                </option>
                            ))}
                        </select>

                        {/* Primary Product */}

                        <label className="flex items-center gap-3 text-white">
                            <input
                                type="checkbox"
                                checked={product.is_primary || false}
                                onChange={(e) => {
                                    setData(
                                        "products",

                                        data.products.map((p, i) => ({
                                            ...p,

                                            is_primary:
                                                i === index
                                                    ? e.target.checked
                                                    : false,
                                        })),
                                    );
                                }}
                            />

                            <span className="text-sm">Primary Product</span>
                        </label>
                        {/* Remove Product */}

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

                                        /*
                                        |--------------------------------------------------------------------------
                                        | Unsaved Product
                                        |--------------------------------------------------------------------------
                                        */

                                        if (!product.id) {
                                            setData(
                                                "products",

                                                data.products.filter(
                                                    (_, i) => i !== index,
                                                ),
                                            );

                                            return;
                                        }

                                        /*
                                        |--------------------------------------------------------------------------
                                        | Delete From Database
                                        |--------------------------------------------------------------------------
                                        */

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
