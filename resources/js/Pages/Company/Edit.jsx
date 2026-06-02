import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, useForm, router, Link } from "@inertiajs/react";
import Swal from "sweetalert2";

export default function Edit({ auth, company }) {
    console.log(company.machines);
    console.log("EDIT PAGE LOADED");
    const isEn = auth.locale === "en";
    const { data, setData, post, processing, errors } = useForm({
        _method: "post",
        /*

        |--------------------------------------------------------------------------
        | Basic Company Data
        |--------------------------------------------------------------------------
        */
        nama_perusahaan: company.nama_perusahaan || "",
        category: company.category || "", // Ditambahkan agar tidak undefined
        pimpinan: company.pimpinan || "",
        tenaga_kerja: company.tenaga_kerja || "",
        alamat_lengkap: company.alamat_lengkap || "",
        telepon: company.telepon || "",
        email_web: company.email_web || "",
        membership_type: company.membership_type || "public",
        /*

        |--------------------------------------------------------------------------
        | Location Fields
        |--------------------------------------------------------------------------
        */
        city: company.city || "", // Ditambahkan agar tidak undefined
        wilayah: company.wilayah || "", // Ditambahkan agar tidak undefined
        /*

        |--------------------------------------------------------------------------
        | Legacy Fallback Fields
        |--------------------------------------------------------------------------
        */
        produk: company.produk || "",
        pasar_ekspor: company.pasar_ekspor || "",
        /*

        |--------------------------------------------------------------------------
        | Relational Data
        |--------------------------------------------------------------------------
        */
        products: company.products || [],
        markets: company.markets || [],
        certifications: company.certifications || [],
        capacities: company.capacities || [],
        machines: company.machines || [],
        moqs: company.moqs || [],
        lead_times: company.leadTimes || [],

        contacts: company.contacts || [],
        links: company.links || [],
        images: company.images || [],
        /*

        |--------------------------------------------------------------------------
        | Inventory
        |--------------------------------------------------------------------------
        */
        stock_ready_caption: company.stock_ready_caption || "",
        stock_qty: company.stock_qty || 0,
        stock_unit: company.stock_unit || "kg",
        price: company.price || 0,
    });

    const saveMachines = () => {
        Swal.fire({
            title: "Save Machine Changes?",
            text: "All added, updated, and removed machines will be synchronized to the database.",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#22c55e",
            cancelButtonColor: "#64748b",
            confirmButtonText: "Yes, Save Changes",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (result.isConfirmed) {
                post(route("companies.machines.update", company.id), {
                    preserveScroll: true,

                    onSuccess: () => {
                        Swal.fire({
                            icon: "success",
                            title: "Machines Saved",
                            text: "Machine data has been successfully updated.",
                            confirmButtonColor: "#22c55e",
                        });
                    },

                    onError: () => {
                        Swal.fire({
                            icon: "error",
                            title: "Save Failed",
                            text: "Unable to save machine data.",
                            confirmButtonColor: "#ef4444",
                        });
                    },
                });
            }
        });
    };
    const saveCapacities = () => {
        post(
            route("companies.capacities.update", company.id),
            {
                capacities: data.capacities,
            },
            {
                preserveScroll: true,

                onSuccess: () => {
                    Swal.fire({
                        icon: "success",
                        title: "Saved Successfully",
                        text: "Capacity data updated.",
                    });
                },

                onError: () => {
                    Swal.fire({
                        icon: "error",
                        title: "Save Failed",
                        text: "Unable to save capacity data.",
                    });
                },
            },
        );
    };

    const saveMoqs = () => {
        post(route("companies.moqs.update", company.id), {
            preserveScroll: true,

            onSuccess: () => {
                Swal.fire({
                    icon: "success",
                    title: "MOQ Saved",
                    text: "MOQ information updated successfully.",
                    confirmButtonColor: "#22c55e",
                });
            },

            onError: () => {
                Swal.fire({
                    icon: "error",
                    title: "Save Failed",
                    text: "Unable to save MOQ information.",
                    confirmButtonColor: "#ef4444",
                });
            },
        });
    };
    const saveLeadTimes = () => {
        post(
            route("companies.lead-times.update", company.id),
            {
                lead_times: data.lead_times,
            },
            {
                preserveScroll: true,

                onSuccess: () => {
                    Swal.fire({
                        icon: "success",
                        title: "Lead Times Saved",
                        text: "Lead time information updated successfully.",
                    });
                },

                onError: () => {
                    Swal.fire({
                        icon: "error",
                        title: "Save Failed",
                        text: "Unable to save lead time data.",
                    });
                },
            },
        );
    };
    const saveProducts = () => {
        post(
            route("companies.products.update", company.id),
            {
                products: data.products,
            },
            {
                preserveScroll: true,

                onSuccess: () => {
                    Swal.fire({
                        icon: "success",
                        title: "Products Saved",
                    });
                },
            },
        );
    };
    const saveMarkets = () => {
        post(
            route("companies.markets.update", company.id),
            {
                markets: data.markets,
            },
            {
                preserveScroll: true,

                onSuccess: () => {
                    Swal.fire({
                        icon: "success",
                        title: "Markets Saved",
                    });
                },
            },
        );
    };
    const saveContacts = () => {
        post(
            route("companies.contacts.update", company.id),
            {
                contacts: data.contacts,
            },
            {
                preserveScroll: true,

                onSuccess: () => {
                    Swal.fire({
                        icon: "success",
                        title: "Contacts Saved",
                    });
                },
            },
        );
    };
    const saveLinks = () => {
        post(
            route("companies.links.update", company.id),
            {
                links: data.links,
            },
            {
                preserveScroll: true,

                onSuccess: () => {
                    Swal.fire({
                        icon: "success",
                        title: "Links Saved",
                    });
                },
            },
        );
    };
    const saveCertifications = () => {
        post(route("companies.certifications.update", company.id), {
            forceFormData: true,
            preserveScroll: true,

            onSuccess: () => {
                Swal.fire({
                    icon: "success",
                    title: "Certifications Saved",
                });
            },
        });
    };
    const saveImages = () => {
        post(route("companies.images.update", company.id), {
            forceFormData: true,
            preserveScroll: true,

            onSuccess: () => {
                Swal.fire({
                    icon: "success",
                    title: "Images Saved",
                });
            },
        });
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route("companies.update", company.id), {
            forceFormData: true,
        });
    };

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title={`Edit - ${company.nama_perusahaan}`} />

            <div className="py-12 bg-[#0a192f] min-h-screen text-white">
                <div className="max-w-6xl mx-auto px-6">
                    {/* HEADER */}
                    <div className="flex items-center gap-4 mb-10">
                        <div className="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-600/30">
                            <i className="fas fa-edit text-white"></i>
                        </div>
                        <div>
                            <h1 className="text-3xl font-black uppercase italic tracking-tighter text-white leading-none">
                                Admin{" "}
                                <span className="text-blue-500">
                                    Data Editor
                                </span>
                            </h1>
                            <p className="text-gray-500 text-[10px] font-bold uppercase tracking-[0.3em] mt-2">
                                Sedang mengedit: {company.nama_perusahaan}
                            </p>
                        </div>
                    </div>

                    <form
                        onSubmit={handleSubmit}
                        className="bg-white/5 border border-white/10 p-10 rounded-[40px] space-y-10 backdrop-blur-xl"
                    >
                        {/* BASIC DATA & CEO */}
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-3">
                                    Category
                                </label>
                                <input
                                    type="text"
                                    value={data.category}
                                    onChange={(e) =>
                                        setData("category", e.target.value)
                                    }
                                    className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                />
                            </div>

                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-3">
                                    CEO / Director
                                </label>
                                <input
                                    type="text"
                                    value={data.pimpinan}
                                    onChange={(e) =>
                                        setData("pimpinan", e.target.value)
                                    }
                                    className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                />
                            </div>
                        </div>

                        {/* LOCATION & CONTACT */}
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-8 pt-6 border-t border-white/5">
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-3">
                                    City
                                </label>
                                <input
                                    type="text"
                                    value={data.city}
                                    onChange={(e) =>
                                        setData("city", e.target.value)
                                    }
                                    className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                />
                            </div>

                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-3">
                                    Province
                                </label>
                                <input
                                    type="text"
                                    value={data.wilayah}
                                    onChange={(e) =>
                                        setData("wilayah", e.target.value)
                                    }
                                    className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                />
                            </div>

                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-3">
                                    Workforce
                                </label>
                                <input
                                    type="text"
                                    value={data.tenaga_kerja}
                                    onChange={(e) =>
                                        setData("tenaga_kerja", e.target.value)
                                    }
                                    className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                />
                            </div>

                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-3">
                                    Telephone
                                </label>
                                <input
                                    type="text"
                                    value={data.telepon}
                                    onChange={(e) =>
                                        setData("telepon", e.target.value)
                                    }
                                    className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                />
                            </div>

                            <div className="md:col-span-2">
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-3">
                                    Email / Website
                                </label>
                                <input
                                    type="text"
                                    value={data.email_web}
                                    onChange={(e) =>
                                        setData("email_web", e.target.value)
                                    }
                                    className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                />
                            </div>
                        </div>

                        {/* PRODUCTS RELATIONAL */}
                        <div className="pt-6 border-t border-white/5">
                            <div className="flex justify-between items-center mb-6">
                                <h3 className="text-yellow-500 text-xs font-black uppercase tracking-[0.3em]">
                                    Company Products
                                </h3>
                                <button
                                    type="button"
                                    onClick={() =>
                                        setData("products", [
                                            ...data.products,
                                            { product_name: "" },
                                        ])
                                    }
                                    className="bg-yellow-500 text-[#0a192f] px-4 py-2 rounded-xl text-[10px] font-black uppercase"
                                >
                                    + Add Product
                                </button>
                            </div>

                            <div className="space-y-4">
                                {data.products.map((product, index) => (
                                    <div key={index} className="flex gap-4">
                                        <input
                                            type="text"
                                            value={product.product_name}
                                            onChange={(e) => {
                                                const updated =
                                                    data.products.map((p, i) =>
                                                        i === index
                                                            ? {
                                                                  ...p,
                                                                  product_name:
                                                                      e.target
                                                                          .value,
                                                              }
                                                            : p,
                                                    );
                                                setData("products", updated);
                                            }}
                                            placeholder="Product Name"
                                            className="flex-1 bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                        />
                                        <button
                                            type="button"
                                            onClick={() => {
                                                Swal.fire({
                                                    title: "Delete Product?",
                                                    text: "This product record will be permanently removed.",
                                                    icon: "warning",
                                                    showCancelButton: true,
                                                    confirmButtonColor:
                                                        "#ef4444",
                                                    cancelButtonColor:
                                                        "#64748b",
                                                    confirmButtonText:
                                                        "Yes, Delete",
                                                    cancelButtonText: "Cancel",
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        if (!product.id) {
                                                            const updated = (
                                                                data.products ||
                                                                []
                                                            ).filter(
                                                                (_, i) =>
                                                                    i !== index,
                                                            );

                                                            setData(
                                                                "products",
                                                                updated,
                                                            );

                                                            return;
                                                        }

                                                        router.delete(
                                                            route(
                                                                "companies.products.destroy",
                                                                [
                                                                    company.id,
                                                                    product.id,
                                                                ],
                                                            ),
                                                            {
                                                                preserveScroll: true,

                                                                onSuccess:
                                                                    () => {
                                                                        const updated =
                                                                            (
                                                                                data.products ||
                                                                                []
                                                                            ).filter(
                                                                                (
                                                                                    _,
                                                                                    i,
                                                                                ) =>
                                                                                    i !==
                                                                                    index,
                                                                            );

                                                                        setData(
                                                                            "products",
                                                                            updated,
                                                                        );
                                                                    },
                                                            },
                                                        );
                                                    }
                                                });
                                            }}
                                            className="bg-red-500 hover:bg-red-400 text-white px-5 py-3 rounded-2xl text-xs font-black uppercase"
                                        >
                                            Remove Product
                                        </button>
                                    </div>
                                ))}
                            </div>
                        </div>
                        {/* CAPACITY SECTION */}
                        <div className="bg-white/5 border border-white/10 rounded-[40px] p-10">
                            <div className="flex items-center justify-between mb-8">
                                <div>
                                    <h2 className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                                        Production Capacities
                                    </h2>

                                    <p className="text-gray-500 text-[10px] uppercase tracking-widest mt-2">
                                        Installed / Actual / Idle Production
                                        Capacity
                                    </p>
                                </div>

                                <button
                                    type="button"
                                    onClick={() =>
                                        setData("capacities", [
                                            ...data.capacities,
                                            {
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
                                        key={index}
                                        className="border border-white/10 rounded-[30px] p-6 bg-white/5"
                                    >
                                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            {/* Capacity Type */}
                                            <div>
                                                <label className="text-[10px] font-black uppercase text-gray-500 block mb-3">
                                                    Capacity Type
                                                </label>

                                                <select
                                                    value={
                                                        capacity.capacity_type
                                                    }
                                                    onChange={(e) => {
                                                        const updated = [
                                                            ...data.capacities,
                                                        ];

                                                        updated[
                                                            index
                                                        ].capacity_type =
                                                            e.target.value;

                                                        setData(
                                                            "capacities",
                                                            updated,
                                                        );
                                                    }}
                                                    className="w-full bg-[#0a192f] border-white/10 rounded-2xl text-white"
                                                >
                                                    <option value="">
                                                        Select Type
                                                    </option>
                                                    <option value="spinning">
                                                        Spinning
                                                    </option>
                                                    <option value="weaving">
                                                        Weaving
                                                    </option>
                                                    <option value="knitting">
                                                        Knitting
                                                    </option>
                                                    <option value="dyeing">
                                                        Dyeing
                                                    </option>
                                                    <option value="printing">
                                                        Printing
                                                    </option>
                                                    <option value="garment">
                                                        Garment
                                                    </option>
                                                </select>
                                            </div>

                                            {/* Item Name */}
                                            <div>
                                                <label className="text-[10px] font-black uppercase text-gray-500 block mb-3">
                                                    Line / Machine
                                                </label>

                                                <input
                                                    type="text"
                                                    value={capacity.item_name}
                                                    onChange={(e) => {
                                                        const updated = [
                                                            ...data.capacities,
                                                        ];

                                                        updated[
                                                            index
                                                        ].item_name =
                                                            e.target.value;

                                                        setData(
                                                            "capacities",
                                                            updated,
                                                        );
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
                                                    value={
                                                        capacity.capacity_value
                                                    }
                                                    onChange={(e) => {
                                                        const updated = [
                                                            ...data.capacities,
                                                        ];

                                                        updated[
                                                            index
                                                        ].capacity_value =
                                                            e.target.value;

                                                        setData(
                                                            "capacities",
                                                            updated,
                                                        );
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
                                                    value={
                                                        capacity.capacity_unit
                                                    }
                                                    onChange={(e) => {
                                                        const updated = [
                                                            ...data.capacities,
                                                        ];

                                                        updated[
                                                            index
                                                        ].capacity_unit =
                                                            e.target.value;

                                                        setData(
                                                            "capacities",
                                                            updated,
                                                        );
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
                                                    value={
                                                        capacity.capacity_category
                                                    }
                                                    onChange={(e) => {
                                                        const updated = [
                                                            ...data.capacities,
                                                        ];

                                                        updated[
                                                            index
                                                        ].capacity_category =
                                                            e.target.value;

                                                        setData(
                                                            "capacities",
                                                            updated,
                                                        );
                                                    }}
                                                    className="w-full bg-[#0a192f] border-white/10 rounded-2xl text-white"
                                                >
                                                    <option value="installed">
                                                        Installed
                                                    </option>

                                                    <option value="actual">
                                                        Actual
                                                    </option>

                                                    <option value="idle">
                                                        Idle
                                                    </option>
                                                </select>
                                            </div>

                                            {/* Machine Count */}
                                            <div>
                                                <label className="text-[10px] font-black uppercase text-gray-500 block mb-3">
                                                    Machine Count
                                                </label>

                                                <input
                                                    type="number"
                                                    value={
                                                        capacity.machine_count
                                                    }
                                                    onChange={(e) => {
                                                        const updated = [
                                                            ...data.capacities,
                                                        ];

                                                        updated[
                                                            index
                                                        ].machine_count =
                                                            e.target.value;

                                                        setData(
                                                            "capacities",
                                                            updated,
                                                        );
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
                                                    value={capacity.shift_info}
                                                    onChange={(e) => {
                                                        const updated = [
                                                            ...data.capacities,
                                                        ];

                                                        updated[
                                                            index
                                                        ].shift_info =
                                                            e.target.value;

                                                        setData(
                                                            "capacities",
                                                            updated,
                                                        );
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
                                                    value={capacity.notes}
                                                    onChange={(e) => {
                                                        const updated = [
                                                            ...data.capacities,
                                                        ];

                                                        updated[index].notes =
                                                            e.target.value;

                                                        setData(
                                                            "capacities",
                                                            updated,
                                                        );
                                                    }}
                                                    className="w-full bg-[#0a192f] border-white/10 rounded-2xl text-white"
                                                />
                                            </div>
                                        </div>
                                        {/* Save */}
                                        <button
                                            type="button"
                                            onClick={saveCapacities}
                                            className="bg-green-500 hover:bg-green-400 text-[#0a192f] px-4 py-2 rounded-xl text-xs font-black uppercase"
                                        >
                                            Save Capacities
                                        </button>

                                        {/* DELETE BUTTON */}
                                        <div className="mt-6 flex justify-end">
                                            <button
                                                type="button"
                                                onClick={() => {
                                                    const updated =
                                                        data.capacities.filter(
                                                            (_, i) =>
                                                                i !== index,
                                                        );

                                                    setData(
                                                        "capacities",
                                                        updated,
                                                    );
                                                }}
                                                className="bg-red-500 text-white px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest"
                                            >
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                        {/* MACHINES */}
                        <div className="pt-6 border-t border-white/5">
                            <div className="flex justify-between items-center mb-6">
                                <h3 className="text-yellow-400 text-xs font-black uppercase tracking-[0.3em]">
                                    Machinery Fleet
                                </h3>

                                <button
                                    type="button"
                                    onClick={() =>
                                        setData("machines", [
                                            ...(data.machines || []),
                                            {
                                                machine_category: "",
                                                machine_type: "",

                                                machine_brand: "",
                                                machine_model: "",

                                                quantity: 1,

                                                production_capacity: "",
                                                capacity_unit: "kg/day",

                                                working_width: "",
                                                gauge_specification: "",

                                                year_installed: "",

                                                machine_condition: "good",

                                                automation_level: "automatic",

                                                country_origin: "",

                                                is_active: true,

                                                notes: "",
                                            },
                                        ])
                                    }
                                    className="bg-yellow-500 text-[#0a192f] px-4 py-2 rounded-xl text-[10px] font-black uppercase"
                                >
                                    + Add Machine
                                </button>
                            </div>

                            <div className="space-y-6">
                                {(data.machines || []).map((machine, index) => (
                                    <div
                                        key={index}
                                        className="bg-white/5 border border-white/10 rounded-3xl p-6 space-y-5"
                                    >
                                        {/* ROW 1 */}
                                        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            {/* CATEGORY */}
                                            <div>
                                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                                    Category
                                                </label>

                                                <select
                                                    value={
                                                        machine.machine_category ||
                                                        ""
                                                    }
                                                    onChange={(e) => {
                                                        const updated = [
                                                            ...(data.machines ||
                                                                []),
                                                        ];
                                                        updated[
                                                            index
                                                        ].machine_category =
                                                            e.target.value;
                                                        setData(
                                                            "machines",
                                                            updated,
                                                        );
                                                    }}
                                                    className="w-full bg-[#0a192f] border border-white/10 rounded-2xl p-3 text-white"
                                                >
                                                    <option value="">
                                                        Select Category
                                                    </option>

                                                    <option value="spinning">
                                                        Spinning
                                                    </option>

                                                    <option value="knitting">
                                                        Knitting
                                                    </option>

                                                    <option value="weaving">
                                                        Weaving
                                                    </option>

                                                    <option value="dyeing">
                                                        Dyeing
                                                    </option>

                                                    <option value="printing">
                                                        Printing
                                                    </option>

                                                    <option value="finishing">
                                                        Finishing
                                                    </option>

                                                    <option value="garment">
                                                        Garment
                                                    </option>

                                                    <option value="embroidery">
                                                        Embroidery
                                                    </option>
                                                </select>
                                            </div>

                                            {/* TYPE */}
                                            <div>
                                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                                    Machine Type
                                                </label>

                                                <input
                                                    type="text"
                                                    value={
                                                        machine.machine_type ||
                                                        ""
                                                    }
                                                    onChange={(e) => {
                                                        const updated = [
                                                            ...(data.machines ||
                                                                []),
                                                        ];
                                                        updated[
                                                            index
                                                        ].machine_type =
                                                            e.target.value;
                                                        setData(
                                                            "machines",
                                                            updated,
                                                        );
                                                    }}
                                                    placeholder="Air Jet Loom"
                                                    className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                                />
                                            </div>

                                            {/* QTY */}
                                            <div>
                                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                                    Quantity
                                                </label>

                                                <input
                                                    type="number"
                                                    value={
                                                        machine.quantity || 0
                                                    }
                                                    onChange={(e) => {
                                                        const updated = [
                                                            ...(data.machines ||
                                                                []),
                                                        ];
                                                        updated[
                                                            index
                                                        ].quantity =
                                                            e.target.value;
                                                        setData(
                                                            "machines",
                                                            updated,
                                                        );
                                                    }}
                                                    className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                                />
                                            </div>
                                        </div>

                                        {/* ROW 2 */}
                                        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <input
                                                type="text"
                                                placeholder="Brand"
                                                value={
                                                    machine.machine_brand || ""
                                                }
                                                onChange={(e) => {
                                                    const updated = [
                                                        ...(data.machines ||
                                                            []),
                                                    ];
                                                    updated[
                                                        index
                                                    ].machine_brand =
                                                        e.target.value;
                                                    setData(
                                                        "machines",
                                                        updated,
                                                    );
                                                }}
                                                className="bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                            />

                                            <input
                                                type="text"
                                                placeholder="Model"
                                                value={
                                                    machine.machine_model || ""
                                                }
                                                onChange={(e) => {
                                                    const updated = [
                                                        ...(data.machines ||
                                                            []),
                                                    ];
                                                    updated[
                                                        index
                                                    ].machine_model =
                                                        e.target.value;
                                                    setData(
                                                        "machines",
                                                        updated,
                                                    );
                                                }}
                                                className="bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                            />

                                            <input
                                                type="text"
                                                placeholder="Country Origin"
                                                value={
                                                    machine.country_origin || ""
                                                }
                                                onChange={(e) => {
                                                    const updated = [
                                                        ...(data.machines ||
                                                            []),
                                                    ];
                                                    updated[
                                                        index
                                                    ].country_origin =
                                                        e.target.value;
                                                    setData(
                                                        "machines",
                                                        updated,
                                                    );
                                                }}
                                                className="bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                            />
                                        </div>

                                        {/* ROW 3 */}
                                        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <input
                                                type="number"
                                                placeholder="Production Capacity"
                                                value={
                                                    machine.production_capacity ||
                                                    ""
                                                }
                                                onChange={(e) => {
                                                    const updated = [
                                                        ...(data.machines ||
                                                            []),
                                                    ];
                                                    updated[
                                                        index
                                                    ].production_capacity =
                                                        e.target.value;
                                                    setData(
                                                        "machines",
                                                        updated,
                                                    );
                                                }}
                                                className="bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                            />

                                            <input
                                                type="text"
                                                placeholder="Capacity Unit"
                                                value={
                                                    machine.capacity_unit || ""
                                                }
                                                onChange={(e) => {
                                                    const updated = [
                                                        ...(data.machines ||
                                                            []),
                                                    ];
                                                    updated[
                                                        index
                                                    ].capacity_unit =
                                                        e.target.value;
                                                    setData(
                                                        "machines",
                                                        updated,
                                                    );
                                                }}
                                                className="bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                            />

                                            <input
                                                type="text"
                                                placeholder="Year Installed"
                                                value={
                                                    machine.year_installed || ""
                                                }
                                                onChange={(e) => {
                                                    const updated = [
                                                        ...(data.machines ||
                                                            []),
                                                    ];
                                                    updated[
                                                        index
                                                    ].year_installed =
                                                        e.target.value;
                                                    setData(
                                                        "machines",
                                                        updated,
                                                    );
                                                }}
                                                className="bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                            />
                                        </div>

                                        {/* ROW 4 */}
                                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <input
                                                type="text"
                                                placeholder="Working Width"
                                                value={
                                                    machine.working_width || ""
                                                }
                                                onChange={(e) => {
                                                    const updated = [
                                                        ...(data.machines ||
                                                            []),
                                                    ];
                                                    updated[
                                                        index
                                                    ].working_width =
                                                        e.target.value;
                                                    setData(
                                                        "machines",
                                                        updated,
                                                    );
                                                }}
                                                className="bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                            />

                                            <input
                                                type="text"
                                                placeholder="Gauge Specification"
                                                value={
                                                    machine.gauge_specification ||
                                                    ""
                                                }
                                                onChange={(e) => {
                                                    const updated = [
                                                        ...(data.machines ||
                                                            []),
                                                    ];
                                                    updated[
                                                        index
                                                    ].gauge_specification =
                                                        e.target.value;
                                                    setData(
                                                        "machines",
                                                        updated,
                                                    );
                                                }}
                                                className="bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                            />
                                        </div>

                                        {/* NOTES */}
                                        <textarea
                                            rows="3"
                                            placeholder="Notes..."
                                            value={machine.notes || ""}
                                            onChange={(e) => {
                                                const updated = [
                                                    ...(data.machines || []),
                                                ];
                                                updated[index].notes =
                                                    e.target.value;
                                                setData("machines", updated);
                                            }}
                                            className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-4"
                                        />
                                        {/* Save */}
                                        <button
                                            type="button"
                                            onClick={saveMachines}
                                            className="bg-green-500 hover:bg-green-400 text-[#0a192f] px-4 py-2 rounded-xl text-xs font-black uppercase"
                                        >
                                            Save Machines
                                        </button>

                                        {/* DELETE */}
                                        <button
                                            type="button"
                                            onClick={() => {
                                                Swal.fire({
                                                    title: "Delete Machine?",
                                                    text: "This machine record will be permanently removed.",
                                                    icon: "warning",
                                                    showCancelButton: true,
                                                    confirmButtonColor:
                                                        "#ef4444",
                                                    cancelButtonColor:
                                                        "#64748b",
                                                    confirmButtonText:
                                                        "Yes, Delete",
                                                    cancelButtonText: "Cancel",
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        /*
                |--------------------------------------------------------------------------
                | NEW UNSAVED MACHINE
                |--------------------------------------------------------------------------
                */

                                                        if (!machine.id) {
                                                            const updated = (
                                                                data.machines ||
                                                                []
                                                            ).filter(
                                                                (_, i) =>
                                                                    i !== index,
                                                            );

                                                            setData(
                                                                "machines",
                                                                updated,
                                                            );

                                                            Swal.fire({
                                                                icon: "success",
                                                                title: "Removed",
                                                                text: "Machine removed from form.",
                                                                timer: 1500,
                                                                showConfirmButton: false,
                                                            });

                                                            return;
                                                        }

                                                        /*
                |--------------------------------------------------------------------------
                | DELETE FROM DATABASE
                |--------------------------------------------------------------------------
                */

                                                        router.delete(
                                                            route(
                                                                "companies.machines.destroy",
                                                                [
                                                                    company.id,
                                                                    machine.id,
                                                                ],
                                                            ),
                                                            {
                                                                preserveScroll: true,

                                                                onSuccess:
                                                                    () => {
                                                                        const updated =
                                                                            (
                                                                                data.machines ||
                                                                                []
                                                                            ).filter(
                                                                                (
                                                                                    _,
                                                                                    i,
                                                                                ) =>
                                                                                    i !==
                                                                                    index,
                                                                            );

                                                                        setData(
                                                                            "machines",
                                                                            updated,
                                                                        );

                                                                        Swal.fire(
                                                                            {
                                                                                icon: "success",
                                                                                title: "Deleted",
                                                                                text: "Machine deleted successfully.",
                                                                                confirmButtonColor:
                                                                                    "#22c55e",
                                                                            },
                                                                        );
                                                                    },

                                                                onError: () => {
                                                                    Swal.fire({
                                                                        icon: "error",
                                                                        title: "Delete Failed",
                                                                        text: "Unable to delete machine.",
                                                                        confirmButtonColor:
                                                                            "#ef4444",
                                                                    });
                                                                },
                                                            },
                                                        );
                                                    }
                                                });
                                            }}
                                            className="bg-red-500 hover:bg-red-400 text-white px-5 py-3 rounded-2xl text-xs font-black uppercase"
                                        >
                                            Remove Machine
                                        </button>
                                    </div>
                                ))}
                            </div>
                        </div>
                        {/* MOQ INFORMATION */}
                        <div className="pt-6 border-t border-white/5">
                            <div className="flex justify-between items-center mb-6">
                                <div>
                                    <h3 className="text-yellow-400 text-xs font-black uppercase tracking-[0.3em]">
                                        Minimum Order Quantity (MOQ)
                                    </h3>

                                    <p className="text-gray-500 text-[10px] uppercase mt-2">
                                        Define minimum order requirements for
                                        buyers
                                    </p>
                                </div>

                                <div className="flex gap-3">
                                    {/* SAVE */}
                                    <button
                                        type="button"
                                        onClick={saveMoqs}
                                        className="bg-green-500 hover:bg-green-400 text-[#0a192f] px-4 py-2 rounded-xl text-xs font-black uppercase"
                                    >
                                        Save MOQ
                                    </button>

                                    {/* ADD */}
                                    <button
                                        type="button"
                                        onClick={() => {
                                            setData("moqs", [
                                                ...(data.moqs || []),
                                                {
                                                    product_name: "",
                                                    minimum_quantity: "",
                                                    unit: "",
                                                    notes: "",
                                                },
                                            ]);
                                        }}
                                        className="bg-yellow-500 hover:bg-yellow-400 text-[#0a192f] px-4 py-2 rounded-xl text-xs font-black uppercase"
                                    >
                                        + Add MOQ
                                    </button>
                                </div>
                            </div>

                            {(data.moqs || []).length > 0 ? (
                                <div className="space-y-6">
                                    {data.moqs.map((moq, index) => (
                                        <div
                                            key={index}
                                            className="bg-white/5 border border-white/10 rounded-3xl p-6 space-y-5"
                                        >
                                            {/* HEADER */}
                                            <div className="flex justify-between items-center">
                                                <h4 className="font-black text-white text-xs uppercase">
                                                    MOQ #{index + 1}
                                                </h4>

                                                <button
                                                    type="button"
                                                    onClick={() => {
                                                        Swal.fire({
                                                            title: "Delete MOQ?",
                                                            text: "This MOQ record will be permanently removed.",
                                                            icon: "warning",
                                                            showCancelButton: true,
                                                            confirmButtonColor:
                                                                "#ef4444",
                                                            cancelButtonColor:
                                                                "#64748b",
                                                            confirmButtonText:
                                                                "Yes, Delete",
                                                            cancelButtonText:
                                                                "Cancel",
                                                        }).then((result) => {
                                                            if (
                                                                result.isConfirmed
                                                            ) {
                                                                /*
                |--------------------------------------------------------------------------
                | NEW UNSAVED MOQ
                |--------------------------------------------------------------------------
                */

                                                                if (!moq.id) {
                                                                    const updated =
                                                                        (
                                                                            data.moqs ||
                                                                            []
                                                                        ).filter(
                                                                            (
                                                                                _,
                                                                                i,
                                                                            ) =>
                                                                                i !==
                                                                                index,
                                                                        );

                                                                    setData(
                                                                        "moqs",
                                                                        updated,
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
                | DELETE FROM DATABASE
                |--------------------------------------------------------------------------
                */

                                                                router.delete(
                                                                    route(
                                                                        "companies.moqs.destroy",
                                                                        [
                                                                            company.id,
                                                                            moq.id,
                                                                        ],
                                                                    ),
                                                                    {
                                                                        preserveScroll: true,

                                                                        onSuccess:
                                                                            () => {
                                                                                const updated =
                                                                                    (
                                                                                        data.moqs ||
                                                                                        []
                                                                                    ).filter(
                                                                                        (
                                                                                            _,
                                                                                            i,
                                                                                        ) =>
                                                                                            i !==
                                                                                            index,
                                                                                    );

                                                                                setData(
                                                                                    "moqs",
                                                                                    updated,
                                                                                );

                                                                                Swal.fire(
                                                                                    {
                                                                                        icon: "success",
                                                                                        title: "Deleted",
                                                                                        text: "MOQ deleted successfully.",
                                                                                        confirmButtonColor:
                                                                                            "#22c55e",
                                                                                    },
                                                                                );
                                                                            },

                                                                        onError:
                                                                            () => {
                                                                                Swal.fire(
                                                                                    {
                                                                                        icon: "error",
                                                                                        title: "Delete Failed",
                                                                                        text: "Unable to delete MOQ.",
                                                                                        confirmButtonColor:
                                                                                            "#ef4444",
                                                                                    },
                                                                                );
                                                                            },
                                                                    },
                                                                );
                                                            }
                                                        });
                                                    }}
                                                    className="bg-red-500 hover:bg-red-400 text-white px-5 py-3 rounded-2xl text-xs font-black uppercase"
                                                >
                                                    Remove MOQ
                                                </button>
                                            </div>

                                            {/* FORM */}
                                            <div className="grid md:grid-cols-2 gap-4">
                                                {/* PRODUCT */}
                                                <div>
                                                    <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                                        Product
                                                    </label>

                                                    <input
                                                        type="text"
                                                        value={
                                                            moq.product_name ||
                                                            ""
                                                        }
                                                        onChange={(e) => {
                                                            const updated = [
                                                                ...(data.moqs ||
                                                                    []),
                                                            ];

                                                            updated[
                                                                index
                                                            ].product_name =
                                                                e.target.value;

                                                            setData(
                                                                "moqs",
                                                                updated,
                                                            );
                                                        }}
                                                        className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                                        placeholder="Yarn / Fabric / Garment"
                                                    />
                                                </div>

                                                {/* MOQ */}
                                                <div>
                                                    <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                                        Minimum Quantity
                                                    </label>

                                                    <input
                                                        type="number"
                                                        value={
                                                            moq.minimum_quantity ||
                                                            ""
                                                        }
                                                        onChange={(e) => {
                                                            const updated = [
                                                                ...(data.moqs ||
                                                                    []),
                                                            ];

                                                            updated[
                                                                index
                                                            ].minimum_quantity =
                                                                e.target.value;

                                                            setData(
                                                                "moqs",
                                                                updated,
                                                            );
                                                        }}
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
                                                        onChange={(e) => {
                                                            const updated = [
                                                                ...(data.moqs ||
                                                                    []),
                                                            ];

                                                            updated[
                                                                index
                                                            ].unit =
                                                                e.target.value;

                                                            setData(
                                                                "moqs",
                                                                updated,
                                                            );
                                                        }}
                                                        className="w-full bg-[#0a192f] border border-white/10 rounded-2xl text-white p-3"
                                                    >
                                                        <option value="">
                                                            Select Unit
                                                        </option>
                                                        <option value="Kg">
                                                            Kg
                                                        </option>
                                                        <option value="Ton">
                                                            Ton
                                                        </option>
                                                        <option value="Meter">
                                                            Meter
                                                        </option>
                                                        <option value="Yard">
                                                            Yard
                                                        </option>
                                                        <option value="Pcs">
                                                            Pcs
                                                        </option>
                                                        <option value="Dozen">
                                                            Dozen
                                                        </option>
                                                    </select>
                                                </div>

                                                {/* NOTES */}
                                                <div>
                                                    <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                                        Notes
                                                    </label>

                                                    <input
                                                        type="text"
                                                        value={moq.notes || ""}
                                                        onChange={(e) => {
                                                            const updated = [
                                                                ...(data.moqs ||
                                                                    []),
                                                            ];

                                                            updated[
                                                                index
                                                            ].notes =
                                                                e.target.value;

                                                            setData(
                                                                "moqs",
                                                                updated,
                                                            );
                                                        }}
                                                        className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                                        placeholder="Optional notes"
                                                    />
                                                </div>
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

                        {/* LEAD TIMES */}
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
                                {(data.lead_times || []).map(
                                    (leadTime, index) => (
                                        <div
                                            key={index}
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
                                                        value={
                                                            leadTime.lead_time_type ||
                                                            ""
                                                        }
                                                        onChange={(e) => {
                                                            const updated = [
                                                                ...(data.lead_times ||
                                                                    []),
                                                            ];

                                                            updated[
                                                                index
                                                            ].lead_time_type =
                                                                e.target.value;

                                                            setData(
                                                                "lead_times",
                                                                updated,
                                                            );
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
                                                        value={
                                                            leadTime.days || ""
                                                        }
                                                        onChange={(e) => {
                                                            const updated = [
                                                                ...(data.lead_times ||
                                                                    []),
                                                            ];

                                                            updated[
                                                                index
                                                            ].days =
                                                                e.target.value;

                                                            setData(
                                                                "lead_times",
                                                                updated,
                                                            );
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
                                                        const updated = [
                                                            ...(data.lead_times ||
                                                                []),
                                                        ];

                                                        updated[index].notes =
                                                            e.target.value;

                                                        setData(
                                                            "lead_times",
                                                            updated,
                                                        );
                                                    }}
                                                    placeholder="Additional information..."
                                                    className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-4"
                                                />
                                            </div>
                                            {/* save */}

                                            {/* DELETE */}
                                            <button
                                                type="button"
                                                onClick={() => {
                                                    Swal.fire({
                                                        title: "Delete Lead Time?",
                                                        text: "This lead time record will be permanently removed.",
                                                        icon: "warning",
                                                        showCancelButton: true,
                                                        confirmButtonColor:
                                                            "#ef4444",
                                                        cancelButtonColor:
                                                            "#64748b",
                                                        confirmButtonText:
                                                            "Yes, Delete",
                                                        cancelButtonText:
                                                            "Cancel",
                                                    }).then((result) => {
                                                        if (
                                                            result.isConfirmed
                                                        ) {
                                                            /*
                |--------------------------------------------------------------------------
                | NEW UNSAVED LEAD TIME
                |--------------------------------------------------------------------------
                */

                                                            if (!leadTime.id) {
                                                                const updated =
                                                                    (
                                                                        data.leadTimes ||
                                                                        []
                                                                    ).filter(
                                                                        (
                                                                            _,
                                                                            i,
                                                                        ) =>
                                                                            i !==
                                                                            index,
                                                                    );

                                                                setData(
                                                                    "leadTimes",
                                                                    updated,
                                                                );

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
                |--------------------------------------------------------------------------
                | DELETE FROM DATABASE
                |--------------------------------------------------------------------------
                */

                                                            router.delete(
                                                                route(
                                                                    "companies.lead-times.destroy",
                                                                    [
                                                                        company.id,
                                                                        leadTime.id,
                                                                    ],
                                                                ),
                                                                {
                                                                    preserveScroll: true,

                                                                    onSuccess:
                                                                        () => {
                                                                            const updated =
                                                                                (
                                                                                    data.leadTimes ||
                                                                                    []
                                                                                ).filter(
                                                                                    (
                                                                                        _,
                                                                                        i,
                                                                                    ) =>
                                                                                        i !==
                                                                                        index,
                                                                                );

                                                                            setData(
                                                                                "leadTimes",
                                                                                updated,
                                                                            );

                                                                            Swal.fire(
                                                                                {
                                                                                    icon: "success",
                                                                                    title: "Deleted",
                                                                                    text: "Lead Time deleted successfully.",
                                                                                    confirmButtonColor:
                                                                                        "#22c55e",
                                                                                },
                                                                            );
                                                                        },

                                                                    onError:
                                                                        () => {
                                                                            Swal.fire(
                                                                                {
                                                                                    icon: "error",
                                                                                    title: "Delete Failed",
                                                                                    text: "Unable to delete Lead Time.",
                                                                                    confirmButtonColor:
                                                                                        "#ef4444",
                                                                                },
                                                                            );
                                                                        },
                                                                },
                                                            );
                                                        }
                                                    });
                                                }}
                                                className="bg-red-500 hover:bg-red-400 text-white px-5 py-3 rounded-2xl text-xs font-black uppercase"
                                            >
                                                Remove Lead Time
                                            </button>
                                        </div>
                                    ),
                                )}
                            </div>
                        </div>
                        {/* CONTACTS */}
                        <div className="pt-6 border-t border-white/5">
                            <div className="flex justify-between items-center mb-6">
                                <h3 className="text-orange-400 text-xs font-black uppercase tracking-[0.3em]">
                                    Company Contacts
                                </h3>

                                <button
                                    type="button"
                                    onClick={() =>
                                        setData("contacts", [
                                            ...(data.contacts || []),
                                            {
                                                contact_name: "",
                                                position: "",
                                                phone: "",
                                                email: "",
                                            },
                                        ])
                                    }
                                    className="bg-orange-500 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase"
                                >
                                    + Add Contact
                                </button>
                            </div>

                            <div className="space-y-6">
                                {(data.contacts || []).map((contact, index) => (
                                    <div
                                        key={index}
                                        className="grid grid-cols-1 md:grid-cols-2 gap-4 bg-white/5 border border-white/10 rounded-3xl p-6"
                                    >
                                        {/* CONTACT NAME */}
                                        <div>
                                            <label className="text-[10px] uppercase tracking-widest text-gray-500 font-black block mb-2">
                                                Contact Name
                                            </label>

                                            <input
                                                type="text"
                                                value={
                                                    contact?.contact_name || ""
                                                }
                                                onChange={(e) => {
                                                    const updated = [
                                                        ...(data.contacts ||
                                                            []),
                                                    ];

                                                    updated[index] = {
                                                        ...updated[index],
                                                        contact_name:
                                                            e.target.value,
                                                    };

                                                    setData(
                                                        "contacts",
                                                        updated,
                                                    );
                                                }}
                                                placeholder="John Doe"
                                                className="w-full bg-[#0a192f] border-white/10 rounded-2xl text-white p-3"
                                            />
                                        </div>

                                        {/* POSITION */}
                                        <div>
                                            <label className="text-[10px] uppercase tracking-widest text-gray-500 font-black block mb-2">
                                                Position
                                            </label>

                                            <input
                                                type="text"
                                                value={contact?.position || ""}
                                                onChange={(e) => {
                                                    const updated = [
                                                        ...(data.contacts ||
                                                            []),
                                                    ];

                                                    updated[index] = {
                                                        ...updated[index],
                                                        position:
                                                            e.target.value,
                                                    };

                                                    setData(
                                                        "contacts",
                                                        updated,
                                                    );
                                                }}
                                                placeholder="Export Manager"
                                                className="w-full bg-[#0a192f] border-white/10 rounded-2xl text-white p-3"
                                            />
                                        </div>

                                        {/* PHONE */}
                                        <div>
                                            <label className="text-[10px] uppercase tracking-widest text-gray-500 font-black block mb-2">
                                                Phone
                                            </label>

                                            <input
                                                type="text"
                                                value={contact?.phone || ""}
                                                onChange={(e) => {
                                                    const updated = [
                                                        ...(data.contacts ||
                                                            []),
                                                    ];

                                                    updated[index] = {
                                                        ...updated[index],
                                                        phone: e.target.value,
                                                    };

                                                    setData(
                                                        "contacts",
                                                        updated,
                                                    );
                                                }}
                                                placeholder="+62..."
                                                className="w-full bg-[#0a192f] border-white/10 rounded-2xl text-white p-3"
                                            />
                                        </div>

                                        {/* EMAIL */}
                                        <div>
                                            <label className="text-[10px] uppercase tracking-widest text-gray-500 font-black block mb-2">
                                                Email
                                            </label>

                                            <input
                                                type="email"
                                                value={contact?.email || ""}
                                                onChange={(e) => {
                                                    const updated = [
                                                        ...(data.contacts ||
                                                            []),
                                                    ];

                                                    updated[index] = {
                                                        ...updated[index],
                                                        email: e.target.value,
                                                    };

                                                    setData(
                                                        "contacts",
                                                        updated,
                                                    );
                                                }}
                                                placeholder="email@company.com"
                                                className="w-full bg-[#0a192f] border-white/10 rounded-2xl text-white p-3"
                                            />
                                        </div>
                                        {/* SAVE */}
                                        <button
                                            type="button"
                                            onClick={saveContacts}
                                            className="bg-green-500 hover:bg-green-400 text-[#0a192f] px-4 py-2 rounded-xl text-xs font-black uppercase"
                                        >
                                            Save Contacts
                                        </button>
                                        {/* DELETE */}
                                        <div className="md:col-span-2 flex justify-end">
                                            <button
                                                type="button"
                                                onClick={() => {
                                                    Swal.fire({
                                                        title: "Delete Contact?",
                                                        text: "This contact record will be permanently removed.",
                                                        icon: "warning",
                                                        showCancelButton: true,
                                                        confirmButtonColor:
                                                            "#ef4444",
                                                        cancelButtonColor:
                                                            "#64748b",
                                                        confirmButtonText:
                                                            "Yes, Delete",
                                                        cancelButtonText:
                                                            "Cancel",
                                                    }).then((result) => {
                                                        if (
                                                            result.isConfirmed
                                                        ) {
                                                            if (!contact.id) {
                                                                const updated =
                                                                    (
                                                                        data.contacts ||
                                                                        []
                                                                    ).filter(
                                                                        (
                                                                            _,
                                                                            i,
                                                                        ) =>
                                                                            i !==
                                                                            index,
                                                                    );

                                                                setData(
                                                                    "contacts",
                                                                    updated,
                                                                );

                                                                Swal.fire({
                                                                    icon: "success",
                                                                    title: "Removed",
                                                                    text: "Contact removed from form.",
                                                                    timer: 1500,
                                                                    showConfirmButton: false,
                                                                });

                                                                return;
                                                            }

                                                            router.delete(
                                                                route(
                                                                    "companies.contacts.destroy",
                                                                    [
                                                                        company.id,
                                                                        contact.id,
                                                                    ],
                                                                ),
                                                                {
                                                                    preserveScroll: true,

                                                                    onSuccess:
                                                                        () => {
                                                                            const updated =
                                                                                (
                                                                                    data.contacts ||
                                                                                    []
                                                                                ).filter(
                                                                                    (
                                                                                        _,
                                                                                        i,
                                                                                    ) =>
                                                                                        i !==
                                                                                        index,
                                                                                );

                                                                            setData(
                                                                                "contacts",
                                                                                updated,
                                                                            );

                                                                            Swal.fire(
                                                                                {
                                                                                    icon: "success",
                                                                                    title: "Deleted",
                                                                                    text: "Contact deleted successfully.",
                                                                                    confirmButtonColor:
                                                                                        "#22c55e",
                                                                                },
                                                                            );
                                                                        },

                                                                    onError:
                                                                        () => {
                                                                            Swal.fire(
                                                                                {
                                                                                    icon: "error",
                                                                                    title: "Delete Failed",
                                                                                    text: "Unable to delete contact.",
                                                                                    confirmButtonColor:
                                                                                        "#ef4444",
                                                                                },
                                                                            );
                                                                        },
                                                                },
                                                            );
                                                        }
                                                    });
                                                }}
                                                className="bg-red-500 hover:bg-red-400 text-white px-5 py-3 rounded-2xl text-xs font-black uppercase"
                                            >
                                                Remove Contact
                                            </button>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* IMAGES */}
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
                                        key={index}
                                        className="grid grid-cols-1 md:grid-cols-4 gap-4 bg-white/5 border border-white/5 rounded-3xl p-5"
                                    >
                                        {/* IMAGE TYPE */}
                                        <div>
                                            <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                                Type
                                            </label>

                                            <select
                                                value={
                                                    image.image_type ||
                                                    "factory"
                                                }
                                                onChange={(e) => {
                                                    const updated = [
                                                        ...(data.images || []),
                                                    ];

                                                    updated[index].image_type =
                                                        e.target.value;

                                                    setData("images", updated);
                                                }}
                                                className="w-full bg-[#0a192f] text-white border border-white/10 rounded-2xl p-3"
                                            >
                                                <option
                                                    value="factory"
                                                    className="bg-[#0a192f] text-white"
                                                >
                                                    Factory
                                                </option>

                                                <option
                                                    value="product"
                                                    className="bg-[#0a192f] text-white"
                                                >
                                                    Product
                                                </option>

                                                <option
                                                    value="machine"
                                                    className="bg-[#0a192f] text-white"
                                                >
                                                    Machine
                                                </option>

                                                <option
                                                    value="office"
                                                    className="bg-[#0a192f] text-white"
                                                >
                                                    Office
                                                </option>

                                                <option
                                                    value="warehouse"
                                                    className="bg-[#0a192f] text-white"
                                                >
                                                    Warehouse
                                                </option>

                                                <option
                                                    value="team"
                                                    className="bg-[#0a192f] text-white"
                                                >
                                                    Team
                                                </option>

                                                <option
                                                    value="certificate"
                                                    className="bg-[#0a192f] text-white"
                                                >
                                                    Certificate
                                                </option>
                                            </select>
                                        </div>

                                        {/* IMAGE URL */}
                                        <div className="md:col-span-2">
                                            <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                                External Image URL
                                            </label>

                                            <input
                                                type="text"
                                                value={image.image_url || ""}
                                                onChange={(e) => {
                                                    const updated = [
                                                        ...(data.images || []),
                                                    ];

                                                    updated[index].image_url =
                                                        e.target.value;

                                                    setData("images", updated);
                                                }}
                                                placeholder="https://..."
                                                className="w-full bg-white/5 border-white/10 rounded-2xl text-white p-3"
                                            />
                                        </div>

                                        {/* CAPTION */}
                                        <div>
                                            <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                                Caption
                                            </label>

                                            <input
                                                type="text"
                                                value={image.caption || ""}
                                                onChange={(e) => {
                                                    const updated = [
                                                        ...(data.images || []),
                                                    ];

                                                    updated[index].caption =
                                                        e.target.value;

                                                    setData("images", updated);
                                                }}
                                                placeholder="Image caption"
                                                className="w-full bg-white/5 border-white/10 rounded-2xl text-white p-3"
                                            />
                                        </div>

                                        {/* FILE UPLOAD */}
                                        <div className="md:col-span-4">
                                            <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                                Upload Image
                                            </label>

                                            <input
                                                type="file"
                                                accept="image/*"
                                                onChange={(e) => {
                                                    const updated = [
                                                        ...(data.images || []),
                                                    ];

                                                    updated[index].image_file =
                                                        e.target.files[0];

                                                    setData("images", updated);
                                                }}
                                                className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                            />
                                        </div>

                                        {/* IMAGE PREVIEW */}
                                        {(image.image_url ||
                                            image.image_file) && (
                                            <div className="md:col-span-4">
                                                <img
                                                    src={
                                                        image.image_file
                                                            ? URL.createObjectURL(
                                                                  image.image_file,
                                                              )
                                                            : image.image_url
                                                    }
                                                    alt="preview"
                                                    className="w-full h-64 object-cover rounded-2xl border border-white/10"
                                                />
                                            </div>
                                        )}
                                        {/* SAVE */}
                                        <button
                                            type="button"
                                            onClick={saveImages}
                                            className="bg-green-500 hover:bg-green-400 text-[#0a192f] px-4 py-2 rounded-xl text-xs font-black uppercase"
                                        >
                                            Save Images
                                        </button>
                                        {/* DELETE */}
                                        <div className="md:col-span-4">
                                            <button
                                                type="button"
                                                onClick={() => {
                                                    Swal.fire({
                                                        title: "Delete Image?",
                                                        text: "This image will be permanently removed.",
                                                        icon: "warning",
                                                        showCancelButton: true,
                                                        confirmButtonColor:
                                                            "#ef4444",
                                                        cancelButtonColor:
                                                            "#64748b",
                                                        confirmButtonText:
                                                            "Yes, Delete",
                                                        cancelButtonText:
                                                            "Cancel",
                                                    }).then((result) => {
                                                        if (
                                                            result.isConfirmed
                                                        ) {
                                                            if (!image.id) {
                                                                const updated =
                                                                    (
                                                                        data.images ||
                                                                        []
                                                                    ).filter(
                                                                        (
                                                                            _,
                                                                            i,
                                                                        ) =>
                                                                            i !==
                                                                            index,
                                                                    );

                                                                setData(
                                                                    "images",
                                                                    updated,
                                                                );

                                                                Swal.fire({
                                                                    icon: "success",
                                                                    title: "Removed",
                                                                    text: "Image removed from form.",
                                                                    timer: 1500,
                                                                    showConfirmButton: false,
                                                                });

                                                                return;
                                                            }

                                                            router.delete(
                                                                route(
                                                                    "companies.images.destroy",
                                                                    [
                                                                        company.id,
                                                                        image.id,
                                                                    ],
                                                                ),
                                                                {
                                                                    preserveScroll: true,

                                                                    onSuccess:
                                                                        () => {
                                                                            const updated =
                                                                                (
                                                                                    data.images ||
                                                                                    []
                                                                                ).filter(
                                                                                    (
                                                                                        _,
                                                                                        i,
                                                                                    ) =>
                                                                                        i !==
                                                                                        index,
                                                                                );

                                                                            setData(
                                                                                "images",
                                                                                updated,
                                                                            );

                                                                            Swal.fire(
                                                                                {
                                                                                    icon: "success",
                                                                                    title: "Deleted",
                                                                                    text: "Image deleted successfully.",
                                                                                    confirmButtonColor:
                                                                                        "#22c55e",
                                                                                },
                                                                            );
                                                                        },

                                                                    onError:
                                                                        () => {
                                                                            Swal.fire(
                                                                                {
                                                                                    icon: "error",
                                                                                    title: "Delete Failed",
                                                                                    text: "Unable to delete image.",
                                                                                    confirmButtonColor:
                                                                                        "#ef4444",
                                                                                },
                                                                            );
                                                                        },
                                                                },
                                                            );
                                                        }
                                                    });
                                                }}
                                                className="bg-red-500 hover:bg-red-400 text-white px-5 py-3 rounded-2xl text-xs font-black uppercase"
                                            >
                                                Remove Image
                                            </button>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                        {/* EXPORT MARKETS */}
                        <div className="pt-6 border-t border-white/5">
                            <div className="flex justify-between items-center mb-6">
                                <h3 className="text-blue-400 text-xs font-black uppercase tracking-[0.3em]">
                                    Markets
                                </h3>

                                <button
                                    type="button"
                                    onClick={() =>
                                        setData("markets", [
                                            ...data.markets,
                                            {
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
                                {data.markets.map((market, index) => (
                                    <div
                                        key={index}
                                        className="grid grid-cols-3 gap-4"
                                    >
                                        <input
                                            type="text"
                                            value={market.country_name}
                                            onChange={(e) => {
                                                const updated = [
                                                    ...data.markets,
                                                ];

                                                updated[index].country_name =
                                                    e.target.value;

                                                setData("markets", updated);
                                            }}
                                            placeholder="Country"
                                            className="col-span-2 bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                        />
                                        <div className="flex gap-2">
                                            <select
                                                value={market.market_type}
                                                onChange={(e) => {
                                                    const updated =
                                                        data.markets.map(
                                                            (m, i) =>
                                                                i === index
                                                                    ? {
                                                                          ...m,
                                                                          market_type:
                                                                              e
                                                                                  .target
                                                                                  .value,
                                                                      }
                                                                    : m,
                                                        );
                                                    setData("markets", updated);
                                                }}
                                                className="flex-1 bg-[#0a192f] border border-white/10 rounded-2xl text-white p-3"
                                            >
                                                <option
                                                    value="export"
                                                    className="bg-[#0a192f]"
                                                >
                                                    Export
                                                </option>
                                                <option
                                                    value="import"
                                                    className="bg-[#0a192f]"
                                                >
                                                    Import
                                                </option>
                                                <option
                                                    value="domestic"
                                                    className="bg-[#0a192f]"
                                                >
                                                    Domestic
                                                </option>
                                            </select>
                                            <button
                                                type="button"
                                                onClick={() => {
                                                    Swal.fire({
                                                        title: "Delete Market?",
                                                        text: "This market record will be permanently removed.",
                                                        icon: "warning",
                                                        showCancelButton: true,
                                                        confirmButtonColor:
                                                            "#ef4444",
                                                        cancelButtonColor:
                                                            "#64748b",
                                                        confirmButtonText:
                                                            "Yes, Delete",
                                                    }).then((result) => {
                                                        if (
                                                            result.isConfirmed
                                                        ) {
                                                            if (!market.id) {
                                                                setData(
                                                                    "markets",
                                                                    (
                                                                        data.markets ||
                                                                        []
                                                                    ).filter(
                                                                        (
                                                                            _,
                                                                            i,
                                                                        ) =>
                                                                            i !==
                                                                            index,
                                                                    ),
                                                                );

                                                                return;
                                                            }

                                                            router.delete(
                                                                route(
                                                                    "companies.markets.destroy",
                                                                    [
                                                                        company.id,
                                                                        market.id,
                                                                    ],
                                                                ),
                                                                {
                                                                    preserveScroll: true,

                                                                    onSuccess:
                                                                        () => {
                                                                            setData(
                                                                                "markets",
                                                                                (
                                                                                    data.markets ||
                                                                                    []
                                                                                ).filter(
                                                                                    (
                                                                                        _,
                                                                                        i,
                                                                                    ) =>
                                                                                        i !==
                                                                                        index,
                                                                                ),
                                                                            );
                                                                        },
                                                                },
                                                            );
                                                        }
                                                    });
                                                }}
                                                className="bg-red-500 hover:bg-red-400 text-white px-5 py-3 rounded-2xl text-xs font-black uppercase"
                                            >
                                                Remove Market
                                            </button>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* CERTIFICATIONS */}
                        {/* CERTIFICATIONS */}
                        <div className="pt-6 border-t border-white/5">
                            <div className="flex justify-between items-center mb-6">
                                <h3 className="text-yellow-400 text-xs font-black uppercase tracking-[0.3em]">
                                    Certifications
                                </h3>

                                <button
                                    type="button"
                                    onClick={() =>
                                        setData("certifications", [
                                            ...(data.certifications || []),
                                            {
                                                certification_name: "",
                                                category: "quality",
                                                certification_code: "",
                                                issuer: "",
                                                certificate_number: "",
                                                description: "",

                                                certificate_file: null,
                                                logo_file: null,

                                                logo_url: "",

                                                issued_at: "",
                                                valid_until: "",

                                                status: "active",

                                                is_verified: false,
                                                is_featured: false,

                                                sort_order: 0,
                                            },
                                        ])
                                    }
                                    className="bg-yellow-500 text-[#0a192f] px-4 py-2 rounded-xl text-[10px] font-black uppercase"
                                >
                                    + Add Certification
                                </button>
                            </div>

                            <div className="space-y-6">
                                {(data.certifications || []).map(
                                    (certification, index) => (
                                        <div
                                            key={index}
                                            className="bg-white/5 border border-white/10 rounded-3xl p-6 space-y-5"
                                        >
                                            {/* ROW 1 */}
                                            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                {/* CERTIFICATION NAME */}
                                                <div>
                                                    <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                                        Certification Name
                                                    </label>

                                                    <input
                                                        type="text"
                                                        value={
                                                            certification.certification_name ||
                                                            ""
                                                        }
                                                        onChange={(e) => {
                                                            const updated = [
                                                                ...(data.certifications ||
                                                                    []),
                                                            ];

                                                            updated[
                                                                index
                                                            ].certification_name =
                                                                e.target.value;

                                                            setData(
                                                                "certifications",
                                                                updated,
                                                            );
                                                        }}
                                                        placeholder="ISO 9001"
                                                        className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                                    />
                                                </div>

                                                {/* CATEGORY */}
                                                <div>
                                                    <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                                        Category
                                                    </label>

                                                    <select
                                                        value={
                                                            certification.category ||
                                                            "quality"
                                                        }
                                                        onChange={(e) => {
                                                            const updated = [
                                                                ...(data.certifications ||
                                                                    []),
                                                            ];

                                                            updated[
                                                                index
                                                            ].category =
                                                                e.target.value;

                                                            setData(
                                                                "certifications",
                                                                updated,
                                                            );
                                                        }}
                                                        className="w-full bg-[#0a192f] text-white border border-white/10 rounded-2xl p-3"
                                                    >
                                                        <option value="quality">
                                                            Quality
                                                        </option>

                                                        <option value="safety">
                                                            Safety
                                                        </option>

                                                        <option value="environment">
                                                            Environment
                                                        </option>

                                                        <option value="sustainability">
                                                            Sustainability
                                                        </option>

                                                        <option value="security">
                                                            Security
                                                        </option>

                                                        <option value="textile_compliance">
                                                            Textile Compliance
                                                        </option>

                                                        <option value="social_compliance">
                                                            Social Compliance
                                                        </option>
                                                    </select>
                                                </div>

                                                {/* CODE */}
                                                <div>
                                                    <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                                        Code
                                                    </label>

                                                    <input
                                                        type="text"
                                                        value={
                                                            certification.certification_code ||
                                                            ""
                                                        }
                                                        onChange={(e) => {
                                                            const updated = [
                                                                ...(data.certifications ||
                                                                    []),
                                                            ];

                                                            updated[
                                                                index
                                                            ].certification_code =
                                                                e.target.value;

                                                            setData(
                                                                "certifications",
                                                                updated,
                                                            );
                                                        }}
                                                        placeholder="ISO9001"
                                                        className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                                    />
                                                </div>
                                            </div>

                                            {/* ROW 2 */}
                                            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                {/* ISSUER */}
                                                <div>
                                                    <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                                        Issuer
                                                    </label>

                                                    <input
                                                        type="text"
                                                        value={
                                                            certification.issuer ||
                                                            ""
                                                        }
                                                        onChange={(e) => {
                                                            const updated = [
                                                                ...(data.certifications ||
                                                                    []),
                                                            ];

                                                            updated[
                                                                index
                                                            ].issuer =
                                                                e.target.value;

                                                            setData(
                                                                "certifications",
                                                                updated,
                                                            );
                                                        }}
                                                        placeholder="OEKO TEX"
                                                        className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                                    />
                                                </div>

                                                {/* CERTIFICATE NUMBER */}
                                                <div>
                                                    <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                                        Certificate Number
                                                    </label>

                                                    <input
                                                        type="text"
                                                        value={
                                                            certification.certificate_number ||
                                                            ""
                                                        }
                                                        onChange={(e) => {
                                                            const updated = [
                                                                ...(data.certifications ||
                                                                    []),
                                                            ];

                                                            updated[
                                                                index
                                                            ].certificate_number =
                                                                e.target.value;

                                                            setData(
                                                                "certifications",
                                                                updated,
                                                            );
                                                        }}
                                                        placeholder="CERT-2026"
                                                        className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                                    />
                                                </div>

                                                {/* STATUS */}
                                                <div>
                                                    <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                                        Status
                                                    </label>

                                                    <select
                                                        value={
                                                            certification.status ||
                                                            "active"
                                                        }
                                                        onChange={(e) => {
                                                            const updated = [
                                                                ...(data.certifications ||
                                                                    []),
                                                            ];

                                                            updated[
                                                                index
                                                            ].status =
                                                                e.target.value;

                                                            setData(
                                                                "certifications",
                                                                updated,
                                                            );
                                                        }}
                                                        className="w-full bg-[#0a192f] text-white border border-white/10 rounded-2xl p-3"
                                                    >
                                                        <option value="active">
                                                            Active
                                                        </option>

                                                        <option value="expired">
                                                            Expired
                                                        </option>

                                                        <option value="pending">
                                                            Pending
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            {/* DESCRIPTION */}
                                            <div>
                                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                                    Description
                                                </label>

                                                <textarea
                                                    rows="4"
                                                    value={
                                                        certification.description ||
                                                        ""
                                                    }
                                                    onChange={(e) => {
                                                        const updated = [
                                                            ...(data.certifications ||
                                                                []),
                                                        ];

                                                        updated[
                                                            index
                                                        ].description =
                                                            e.target.value;

                                                        setData(
                                                            "certifications",
                                                            updated,
                                                        );
                                                    }}
                                                    placeholder="Certification description..."
                                                    className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-4"
                                                />
                                            </div>
                                            {/* CERTIFICATE FILES */}
                                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                {/* CERTIFICATE PDF */}
                                                <div>
                                                    <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                                        Certificate PDF
                                                    </label>

                                                    <input
                                                        type="file"
                                                        accept=".pdf"
                                                        onChange={(e) => {
                                                            const file =
                                                                e.target
                                                                    .files[0];

                                                            const updated = [
                                                                ...(data.certifications ||
                                                                    []),
                                                            ];

                                                            updated[
                                                                index
                                                            ].certificate_file =
                                                                file;

                                                            setData(
                                                                "certifications",
                                                                updated,
                                                            );
                                                        }}
                                                        className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                                    />

                                                    {/* EXISTING FILE */}
                                                    {certification.certificate_file &&
                                                        typeof certification.certificate_file ===
                                                            "string" && (
                                                            <a
                                                                href={`/storage/${certification.certificate_file}`}
                                                                target="_blank"
                                                                rel="noopener noreferrer"
                                                                className="mt-3 inline-flex items-center gap-2 text-xs text-blue-400 hover:text-blue-300 transition-all"
                                                            >
                                                                <i className="fas fa-file-pdf"></i>
                                                                View Uploaded
                                                                PDF
                                                            </a>
                                                        )}

                                                    {/* NEW FILE PREVIEW */}
                                                    {certification.certificate_file &&
                                                        typeof certification.certificate_file !==
                                                            "string" && (
                                                            <div className="mt-3 text-[11px] text-emerald-400 font-bold">
                                                                <i className="fas fa-check-circle mr-2"></i>

                                                                {
                                                                    certification
                                                                        .certificate_file
                                                                        .name
                                                                }
                                                            </div>
                                                        )}
                                                </div>

                                                {/* CERTIFICATION LOGO */}
                                                <div>
                                                    <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                                        Certification Logo
                                                    </label>

                                                    <input
                                                        type="file"
                                                        accept="image/*"
                                                        onChange={(e) => {
                                                            const file =
                                                                e.target
                                                                    .files[0];

                                                            const updated = [
                                                                ...(data.certifications ||
                                                                    []),
                                                            ];

                                                            updated[
                                                                index
                                                            ].logo_file = file;

                                                            /*
                |--------------------------------------------------------------------------
                | LIVE PREVIEW
                |--------------------------------------------------------------------------
                */

                                                            if (file) {
                                                                updated[
                                                                    index
                                                                ].logo_preview =
                                                                    URL.createObjectURL(
                                                                        file,
                                                                    );
                                                            }

                                                            setData(
                                                                "certifications",
                                                                updated,
                                                            );
                                                        }}
                                                        className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                                    />

                                                    {/* EXISTING LOGO */}
                                                    {certification.logo_url &&
                                                        !certification.logo_preview && (
                                                            <div className="mt-4">
                                                                <img
                                                                    src={`/storage/${certification.logo_url}`}
                                                                    alt="logo"
                                                                    className="h-24 object-contain rounded-2xl bg-white p-3 border border-white/10"
                                                                />
                                                            </div>
                                                        )}

                                                    {/* LIVE PREVIEW */}
                                                    {certification.logo_preview && (
                                                        <div className="mt-4">
                                                            <img
                                                                src={
                                                                    certification.logo_preview
                                                                }
                                                                alt="preview"
                                                                className="h-24 object-contain rounded-2xl bg-white p-3 border border-white/10"
                                                            />
                                                        </div>
                                                    )}
                                                </div>
                                            </div>

                                            {/* CHECKBOXES */}
                                            <div className="flex flex-wrap gap-6">
                                                <label className="flex items-center gap-3 text-xs text-white font-bold">
                                                    <input
                                                        type="checkbox"
                                                        checked={
                                                            certification.is_verified ||
                                                            false
                                                        }
                                                        onChange={(e) => {
                                                            const updated = [
                                                                ...(data.certifications ||
                                                                    []),
                                                            ];

                                                            updated[
                                                                index
                                                            ].is_verified =
                                                                e.target.checked;

                                                            setData(
                                                                "certifications",
                                                                updated,
                                                            );
                                                        }}
                                                    />
                                                    Verified
                                                </label>

                                                <label className="flex items-center gap-3 text-xs text-white font-bold">
                                                    <input
                                                        type="checkbox"
                                                        checked={
                                                            certification.is_featured ||
                                                            false
                                                        }
                                                        onChange={(e) => {
                                                            const updated = [
                                                                ...(data.certifications ||
                                                                    []),
                                                            ];

                                                            updated[
                                                                index
                                                            ].is_featured =
                                                                e.target.checked;

                                                            setData(
                                                                "certifications",
                                                                updated,
                                                            );
                                                        }}
                                                    />
                                                    Featured
                                                </label>
                                            </div>
                                            {/* save */}
                                            <button
                                                type="button"
                                                onClick={saveCertifications}
                                                className="bg-green-500 hover:bg-green-400 text-[#0a192f] px-4 py-2 rounded-xl text-xs font-black uppercase"
                                            >
                                                Save Certifications
                                            </button>
                                            {/* DELETE */}
                                            <button
                                                type="button"
                                                onClick={() => {
                                                    Swal.fire({
                                                        title: "Delete Certification?",
                                                        text: "This certification will be permanently removed.",
                                                        icon: "warning",
                                                        showCancelButton: true,
                                                        confirmButtonColor:
                                                            "#ef4444",
                                                        cancelButtonColor:
                                                            "#64748b",
                                                        confirmButtonText:
                                                            "Yes, Delete",
                                                    }).then((result) => {
                                                        if (
                                                            result.isConfirmed
                                                        ) {
                                                            if (
                                                                !certification.id
                                                            ) {
                                                                setData(
                                                                    "certifications",
                                                                    (
                                                                        data.certifications ||
                                                                        []
                                                                    ).filter(
                                                                        (
                                                                            _,
                                                                            i,
                                                                        ) =>
                                                                            i !==
                                                                            index,
                                                                    ),
                                                                );

                                                                return;
                                                            }

                                                            router.delete(
                                                                route(
                                                                    "companies.certifications.destroy",
                                                                    [
                                                                        company.id,
                                                                        certification.id,
                                                                    ],
                                                                ),
                                                                {
                                                                    preserveScroll: true,

                                                                    onSuccess:
                                                                        () => {
                                                                            setData(
                                                                                "certifications",
                                                                                (
                                                                                    data.certifications ||
                                                                                    []
                                                                                ).filter(
                                                                                    (
                                                                                        _,
                                                                                        i,
                                                                                    ) =>
                                                                                        i !==
                                                                                        index,
                                                                                ),
                                                                            );
                                                                        },
                                                                },
                                                            );
                                                        }
                                                    });
                                                }}
                                                className="bg-red-500 hover:bg-red-400 text-white px-5 py-3 rounded-2xl text-xs font-black uppercase"
                                            >
                                                Remove Certification
                                            </button>
                                        </div>
                                    ),
                                )}
                            </div>
                        </div>
                        {/* LINKS */}
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
                                        key={index}
                                        className="grid grid-cols-1 md:grid-cols-4 gap-4"
                                    >
                                        {/* TYPE */}
                                        <select
                                            value={link?.link_type || "website"}
                                            onChange={(e) => {
                                                const updated = [
                                                    ...(data.links || []),
                                                ];

                                                updated[index] = {
                                                    ...updated[index],
                                                    link_type: e.target.value,
                                                };

                                                setData("links", updated);
                                            }}
                                            className="bg-white/5 border-white/10 rounded-2xl text-white p-3"
                                        >
                                            <option
                                                value="website"
                                                className="bg-[#0a192f] text-white"
                                            >
                                                Website
                                            </option>

                                            <option
                                                value="instagram"
                                                className="bg-[#0a192f] text-white"
                                            >
                                                Instagram
                                            </option>

                                            <option
                                                value="facebook"
                                                className="bg-[#0a192f] text-white"
                                            >
                                                Facebook
                                            </option>

                                            <option
                                                value="linkedin"
                                                className="bg-[#0a192f] text-white"
                                            >
                                                LinkedIn
                                            </option>

                                            <option
                                                value="youtube"
                                                className="bg-[#0a192f] text-white"
                                            >
                                                YouTube
                                            </option>

                                            <option
                                                value="tiktok"
                                                className="bg-[#0a192f] text-white"
                                            >
                                                TikTok
                                            </option>

                                            <option
                                                value="marketplace"
                                                className="bg-[#0a192f] text-white"
                                            >
                                                Marketplace
                                            </option>
                                        </select>

                                        {/* URL */}
                                        <input
                                            type="text"
                                            value={link?.url || ""}
                                            onChange={(e) => {
                                                const updated = [
                                                    ...(data.links || []),
                                                ];

                                                updated[index] = {
                                                    ...updated[index],
                                                    url: e.target.value,
                                                };

                                                setData("links", updated);
                                            }}
                                            placeholder="https://..."
                                            className="md:col-span-2 bg-white/5 border-white/10 rounded-2xl text-white p-3"
                                        />
                                        {/* SAVE */}
                                        <button
                                            type="button"
                                            onClick={saveLinks}
                                            className="bg-green-500 hover:bg-green-400 text-[#0a192f] px-4 py-2 rounded-xl text-xs font-black uppercase"
                                        >
                                            Save Links
                                        </button>
                                        {/* DELETE */}
                                        <button
                                            type="button"
                                            onClick={() => {
                                                Swal.fire({
                                                    title: "Delete Link?",
                                                    text: "This link record will be permanently removed.",
                                                    icon: "warning",
                                                    showCancelButton: true,
                                                    confirmButtonColor:
                                                        "#ef4444",
                                                    cancelButtonColor:
                                                        "#64748b",
                                                    confirmButtonText:
                                                        "Yes, Delete",
                                                    cancelButtonText: "Cancel",
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        if (!link.id) {
                                                            const updated = (
                                                                data.links || []
                                                            ).filter(
                                                                (_, i) =>
                                                                    i !== index,
                                                            );

                                                            setData(
                                                                "links",
                                                                updated,
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

                                                        router.delete(
                                                            route(
                                                                "companies.links.destroy",
                                                                [
                                                                    company.id,
                                                                    link.id,
                                                                ],
                                                            ),
                                                            {
                                                                preserveScroll: true,

                                                                onSuccess:
                                                                    () => {
                                                                        const updated =
                                                                            (
                                                                                data.links ||
                                                                                []
                                                                            ).filter(
                                                                                (
                                                                                    _,
                                                                                    i,
                                                                                ) =>
                                                                                    i !==
                                                                                    index,
                                                                            );

                                                                        setData(
                                                                            "links",
                                                                            updated,
                                                                        );

                                                                        Swal.fire(
                                                                            {
                                                                                icon: "success",
                                                                                title: "Deleted",
                                                                                text: "Link deleted successfully.",
                                                                                confirmButtonColor:
                                                                                    "#22c55e",
                                                                            },
                                                                        );
                                                                    },

                                                                onError: () => {
                                                                    Swal.fire({
                                                                        icon: "error",
                                                                        title: "Delete Failed",
                                                                        text: "Unable to delete link.",
                                                                        confirmButtonColor:
                                                                            "#ef4444",
                                                                    });
                                                                },
                                                            },
                                                        );
                                                    }
                                                });
                                            }}
                                            className="bg-red-500 hover:bg-red-400 text-white px-5 py-3 rounded-2xl text-xs font-black uppercase"
                                        >
                                            Remove Link
                                        </button>
                                    </div>
                                ))}
                            </div>
                        </div>
                        {/* STOCK & INVENTORY */}
                        <div className="bg-emerald-500/5 border border-emerald-500/20 p-10 rounded-[50px] shadow-2xl shadow-emerald-500/5">
                            <div className="flex items-center gap-4 mb-8 border-b border-white/5 pb-6">
                                <div className="h-12 w-12 bg-emerald-500/20 rounded-2xl flex items-center justify-center text-emerald-500 shadow-lg">
                                    <i className="fas fa-layer-group text-xl"></i>
                                </div>

                                <div>
                                    <h3 className="text-white text-lg font-black uppercase italic tracking-tighter">
                                        Inventory &{" "}
                                        <span className="text-emerald-500">
                                            Price Radar
                                        </span>
                                    </h3>

                                    <p className="text-gray-500 text-[10px] font-bold uppercase tracking-widest italic">
                                        Update ready stock information
                                    </p>
                                </div>
                            </div>

                            <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div className="md:col-span-2">
                                    <label className="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3 block italic">
                                        Ready Stock Description
                                    </label>

                                    <input
                                        type="text"
                                        value={data.stock_ready_caption}
                                        onChange={(e) =>
                                            setData(
                                                "stock_ready_caption",
                                                e.target.value,
                                            )
                                        }
                                        className="w-full bg-[#0a192f] border-white/10 rounded-2xl text-white py-4 px-6"
                                    />
                                </div>

                                <div className="flex gap-4">
                                    <div className="flex-1">
                                        <label className="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3 block italic">
                                            Quantity
                                        </label>

                                        <input
                                            type="number"
                                            value={data.stock_qty}
                                            onChange={(e) =>
                                                setData(
                                                    "stock_qty",
                                                    e.target.value,
                                                )
                                            }
                                            className="w-full bg-[#0a192f] border-white/10 rounded-2xl text-white py-4 px-6"
                                        />
                                    </div>

                                    <div className="w-1/3">
                                        <label className="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3 block italic">
                                            Unit
                                        </label>

                                        <select
                                            value={data.stock_unit}
                                            onChange={(e) =>
                                                setData(
                                                    "stock_unit",
                                                    e.target.value,
                                                )
                                            }
                                            className="w-full bg-[#0a192f] border-white/10 rounded-2xl text-white py-4 px-6"
                                        >
                                            <option value="Kg">Kg</option>
                                            <option value="Pcs">Pcs</option>
                                            <option value="Roll">Roll</option>
                                            <option value="Yard">Yard</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label className="text-[10px] font-black text-yellow-500 uppercase tracking-widest mb-3 block italic">
                                        Price Benchmark (IDR)
                                    </label>

                                    <div className="relative">
                                        <span className="absolute left-6 top-1/2 -translate-y-1/2 text-gray-500 font-bold text-sm">
                                            Rp
                                        </span>

                                        <input
                                            type="number"
                                            value={data.price}
                                            onChange={(e) =>
                                                setData("price", e.target.value)
                                            }
                                            className="w-full bg-[#0a192f] border-white/10 rounded-2xl text-white py-4 pl-14 pr-6"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* BUTTON ACTION */}
                        <div className="flex gap-4 pt-6">
                            <button
                                type="submit"
                                disabled={processing}
                                className="flex-grow bg-blue-600 text-white font-black py-5 rounded-3xl uppercase tracking-widest hover:bg-blue-500 transition-all shadow-2xl shadow-blue-600/20 active:scale-95"
                            >
                                {processing
                                    ? "Updating..."
                                    : "Update Big Data Intelligence"}
                            </button>

                            <Link
                                href={route("companies.index")}
                                className="px-10 py-5 border border-white/10 rounded-3xl font-black uppercase text-[10px] tracking-widest hover:bg-white/5 transition-all flex items-center"
                            >
                                Cancel
                            </Link>
                        </div>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
