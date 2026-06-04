import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, useForm } from "@inertiajs/react";

export default function Create({ auth }) {
    const { data, setData, post, processing, errors } = useForm({
        product_category: "",
        product_name: "",
        specification: "",
        unit: "KG",
        moq_quantity: "",
        quantity: "",
        required_month: "",
        destination_country: "",
        destination_city: "",
        notes: "",
    });

    const submit = (e) => {
        e.preventDefault();

        post(route("collective-sourcing.store"));
    };

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Create Requirement" />
            <div className="max-w-4xl mx-auto p-6 text-gray-100">
                <div className="flex justify-between items-center mb-6">
                    <div>
                        <h1 className="text-3xl font-black uppercase tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-white via-gray-100 to-gray-400 drop-shadow-[0_2px_10px_rgba(255,255,255,0.1)]">
                            Create Requirement
                        </h1>
                        <p className="text-gray-400 text-sm mt-1">
                            Submit your demand and join MOQ Matching Network.
                        </p>
                    </div>

                    <Link
                        href={route("collective-sourcing.index")}
                        className="px-5 py-2.5 rounded-xl bg-white/5 border border-white/10 text-xs font-black uppercase tracking-widest text-white transition-all duration-300 hover:bg-white/10 hover:text-amber-400"
                    >
                        Back
                    </Link>
                </div>

                {/* Form menggunakan tema gelap premium agar teks input otomatis kontras */}
                <div className="bg-[#0b1329]/80 backdrop-blur-xl border border-white/5 rounded-2xl shadow-2xl p-6">
                    <form onSubmit={submit} className="space-y-6">
                        {/* Category */}
                        <div>
                            <label className="block mb-2 text-xs font-black uppercase tracking-widest text-amber-500/80">
                                Product Category *
                            </label>

                            {/* Perbaikan Select: text-white dengan bg gelap agar opsi terlihat */}
                            <select
                                value={data.product_category}
                                onChange={(e) =>
                                    setData("product_category", e.target.value)
                                }
                                className="w-full bg-black/40 border border-white/10 text-white rounded-xl p-3 text-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none transition-all"
                            >
                                <option
                                    value=""
                                    className="bg-[#0b1329] text-gray-400"
                                >
                                    Select Category
                                </option>
                                <option
                                    value="Fiber"
                                    className="bg-[#0b1329] text-white"
                                >
                                    Fiber
                                </option>
                                <option
                                    value="Yarn"
                                    className="bg-[#0b1329] text-white"
                                >
                                    Yarn
                                </option>
                                <option
                                    value="Fabric"
                                    className="bg-[#0b1329] text-white"
                                >
                                    Fabric
                                </option>
                                <option
                                    value="Garment Accessories"
                                    className="bg-[#0b1329] text-white"
                                >
                                    Garment Accessories
                                </option>
                                <option
                                    value="Chemical"
                                    className="bg-[#0b1329] text-white"
                                >
                                    Chemical
                                </option>
                                <option
                                    value="Other"
                                    className="bg-[#0b1329] text-white"
                                >
                                    Other
                                </option>
                            </select>

                            {errors.product_category && (
                                <div className="text-red-400 text-xs font-mono mt-1">
                                    {errors.product_category}
                                </div>
                            )}
                        </div>

                        {/* Product Name */}
                        <div>
                            <label className="block mb-2 text-xs font-black uppercase tracking-widest text-amber-500/80">
                                Product Name *
                            </label>

                            <input
                                type="text"
                                value={data.product_name}
                                onChange={(e) =>
                                    setData("product_name", e.target.value)
                                }
                                className="w-full bg-black/40 border border-white/10 text-white rounded-xl p-3 text-sm placeholder-gray-600 focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none transition-all"
                                placeholder="Polyester Yarn 30/1"
                            />

                            {errors.product_name && (
                                <div className="text-red-400 text-xs font-mono mt-1">
                                    {errors.product_name}
                                </div>
                            )}
                        </div>

                        {/* Specification */}
                        <div>
                            <label className="block mb-2 text-xs font-black uppercase tracking-widest text-amber-500/80">
                                Specification *
                            </label>

                            <textarea
                                rows="4"
                                value={data.specification}
                                onChange={(e) =>
                                    setData("specification", e.target.value)
                                }
                                className="w-full bg-black/40 border border-white/10 text-white rounded-xl p-3 text-sm placeholder-gray-600 focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none transition-all"
                                placeholder="RW, AA Grade, 100% Polyester"
                            />
                        </div>

                        {/* MOQ + Quantity */}
                        <div className="grid md:grid-cols-2 gap-6">
                            <div>
                                <label className="block mb-2 text-xs font-black uppercase tracking-widest text-amber-500/80">
                                    Supplier MOQ *
                                </label>

                                <input
                                    type="number"
                                    value={data.moq_quantity}
                                    onChange={(e) =>
                                        setData("moq_quantity", e.target.value)
                                    }
                                    className="w-full bg-black/40 border border-white/10 text-white rounded-xl p-3 text-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none transition-all"
                                />
                            </div>

                            <div>
                                <label className="block mb-2 text-xs font-black uppercase tracking-widest text-amber-500/80">
                                    My Requirement *
                                </label>

                                <input
                                    type="number"
                                    value={data.quantity}
                                    onChange={(e) =>
                                        setData("quantity", e.target.value)
                                    }
                                    className="w-full bg-black/40 border border-white/10 text-white rounded-xl p-3 text-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none transition-all"
                                />
                            </div>
                        </div>

                        {/* Unit */}
                        <div>
                            <label className="block mb-2 text-xs font-black uppercase tracking-widest text-amber-500/80">
                                Unit *
                            </label>

                            <input
                                type="text"
                                value={data.unit}
                                onChange={(e) =>
                                    setData("unit", e.target.value)
                                }
                                className="w-full bg-black/40 border border-white/10 text-white rounded-xl p-3 text-sm placeholder-gray-600 focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none transition-all"
                                placeholder="KG"
                            />
                        </div>

                        {/* Month */}
                        <div>
                            <label className="block mb-2 text-xs font-black uppercase tracking-widest text-amber-500/80">
                                Required Month *
                            </label>

                            <input
                                type="month"
                                value={data.required_month}
                                onChange={(e) =>
                                    setData("required_month", e.target.value)
                                }
                                className="w-full bg-black/40 border border-white/10 text-white rounded-xl p-3 text-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none transition-all"
                            />
                        </div>

                        {/* Destination */}
                        <div className="grid md:grid-cols-2 gap-6">
                            <div>
                                <label className="block mb-2 text-xs font-black uppercase tracking-widest text-amber-500/80">
                                    Destination Country
                                </label>

                                <input
                                    type="text"
                                    value={data.destination_city}
                                    onChange={(e) =>
                                        setData(
                                            "destination_city",
                                            e.target.value,
                                        )
                                    }
                                    className="w-full bg-black/40 border border-white/10 text-white rounded-xl p-3 text-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none transition-all"
                                />
                            </div>
                        </div>

                        {/* Notes */}
                        <div>
                            <label className="block mb-2 text-xs font-black uppercase tracking-widest text-amber-500/80">
                                Notes
                            </label>

                            <textarea
                                rows="4"
                                value={data.notes}
                                onChange={(e) =>
                                    setData("notes", e.target.value)
                                }
                                className="w-full bg-black/40 border border-white/10 text-white rounded-xl p-3 text-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none transition-all"
                            />
                        </div>

                        {/* Tombol Submit Premium Grid */}
                        <div className="flex justify-end pt-4">
                            <button
                                type="submit"
                                disabled={processing}
                                className="w-full sm:w-auto px-6 py-3 rounded-xl bg-gradient-to-r from-amber-600 to-yellow-500 hover:from-amber-500 hover:to-yellow-400 text-[#030712] font-black uppercase tracking-widest text-xs shadow-lg shadow-amber-500/10 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {processing
                                    ? "Submitting..."
                                    : "Submit Requirement"}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
