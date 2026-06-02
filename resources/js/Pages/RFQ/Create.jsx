import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, useForm } from "@inertiajs/react";

export default function Create({ auth }) {
    const { data, setData, post, processing, errors } = useForm({
        product_name: "",
        hs_code: "",
        description: "",
        required_quantity: "",
        unit: "PCS",
        required_delivery_date: "",
        destination_country: "",
        attachments: [],
    });

    const submit = (e) => {
        e.preventDefault();

        post(route("rfqs.store"));
    };

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Create RFQ" />

            <div className="max-w-4xl mx-auto p-6">
                <div className="mb-6">
                    <h1 className="text-3xl font-bold">Create RFQ</h1>

                    <p className="text-gray-500 mt-2">
                        Submit a Request for Quotation.
                    </p>
                </div>

                <form
                    onSubmit={submit}
                    className="bg-white text-gray-900 rounded-2xl shadow p-6 space-y-6"
                >
                    {/* Product */}

                    <div>
                        <label className="block font-semibold mb-2">
                            Product Name
                        </label>

                        <input
                            type="text"
                            value={data.product_name}
                            onChange={(e) =>
                                setData("product_name", e.target.value)
                            }
                            className="w-full border rounded-xl p-3 bg-white text-gray-900"
                        />

                        {errors.product_name && (
                            <div className="text-red-500 text-sm mt-1">
                                {errors.product_name}
                            </div>
                        )}
                    </div>

                    {/* HS Code */}

                    <div>
                        <label className="block font-semibold mb-2">
                            HS Code
                        </label>

                        <input
                            type="text"
                            value={data.hs_code}
                            onChange={(e) => setData("hs_code", e.target.value)}
                            className="w-full
        border
        rounded-xl
        p-3
        bg-white
        text-gray-300"
                        />
                    </div>

                    {/* Quantity */}

                    <div>
                        <label className="block font-semibold mb-2">
                            Required Quantity
                        </label>

                        <input
                            type="number"
                            value={data.required_quantity}
                            onChange={(e) =>
                                setData("required_quantity", e.target.value)
                            }
                            className="w-full border rounded-xl p-3"
                        />

                        {errors.required_quantity && (
                            <div className="text-red-500 text-sm mt-1">
                                {errors.required_quantity}
                            </div>
                        )}
                    </div>

                    {/* Unit */}

                    <div>
                        <label className="block font-semibold mb-2">Unit</label>

                        <select
                            value={data.unit}
                            onChange={(e) => setData("unit", e.target.value)}
                            className="w-full border rounded-xl p-3"
                        >
                            <option value="PCS">PCS</option>

                            <option value="KG">KG</option>

                            <option value="METER">METER</option>

                            <option value="YARD">YARD</option>
                        </select>
                    </div>

                    {/* Delivery Date */}

                    <div>
                        <label className="block font-semibold mb-2">
                            Required Delivery Date
                        </label>

                        <input
                            type="date"
                            value={data.required_delivery_date}
                            onChange={(e) =>
                                setData(
                                    "required_delivery_date",
                                    e.target.value,
                                )
                            }
                            className="w-full border rounded-xl p-3"
                        />
                    </div>

                    {/* Destination */}

                    <div>
                        <label className="block font-semibold mb-2">
                            Destination Country
                        </label>

                        <input
                            type="text"
                            value={data.destination_country}
                            onChange={(e) =>
                                setData("destination_country", e.target.value)
                            }
                            className="w-full border rounded-xl p-3"
                        />
                    </div>

                    {/* Description */}

                    <div>
                        <label className="block font-semibold mb-2">
                            Description
                        </label>

                        <textarea
                            rows="5"
                            value={data.description}
                            onChange={(e) =>
                                setData("description", e.target.value)
                            }
                            className="w-full border rounded-xl p-3"
                        />
                    </div>
                    <div>
                        <label className="block font-semibold mb-2 text-gray-900 dark:text-white">
                            Attachment Files
                        </label>

                        <input
                            type="file"
                            multiple
                            onChange={(e) =>
                                setData(
                                    "attachments",
                                    Array.from(e.target.files),
                                )
                            }
                            className="w-full border rounded-xl p-3 bg-white text-gray-900"
                        />

                        <p className="text-sm text-gray-500 mt-1">
                            PDF, DOC, DOCX, XLS, XLSX
                        </p>

                        {errors.attachments && (
                            <div className="text-red-500 text-sm mt-1">
                                {errors.attachments}
                            </div>
                        )}
                    </div>
                    <div className="flex gap-3">
                        <button
                            type="submit"
                            disabled={processing}
                            className="bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 rounded-xl"
                        >
                            Submit RFQ
                        </button>

                        <Link
                            href={route("rfqs.index")}
                            className="bg-gray-200 px-6 py-3 rounded-xl"
                        >
                            Cancel
                        </Link>
                    </div>
                </form>
            </div>
        </AuthenticatedLayout>
    );
}
