import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, useForm, usePage, router } from "@inertiajs/react";

export default function Show({ auth, rfq }) {
    const { data, setData, post, processing, errors } = useForm({
        unit_price: "",
        minimum_order_quantity: "",
        lead_time_days: "",
        remarks: "",
    });

    const submitQuotation = (e) => {
        e.preventDefault();

        post(route("quotations.store", rfq.id));
    };
    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title={rfq.rfq_number} />

            <div className="max-w-6xl mx-auto p-6 text-gray-900">
                <div className="flex justify-between items-center mb-6">
                    <div>
                        <h1 className="text-3xl font-bold text-gray-900">
                            {rfq.rfq_number}
                        </h1>
                        <p className="text-gray-500">RFQ Detail</p>
                    </div>

                    <Link
                        href={route("rfqs.index")}
                        className="bg-gray-200 hover:bg-gray-300 text-gray-800 px-5 py-3 rounded-xl transition font-medium"
                    >
                        Back
                    </Link>
                </div>

                {/* RFQ Detail */}
                <div className="bg-white rounded-2xl shadow p-6 mb-6">
                    <div className="grid md:grid-cols-2 gap-6">
                        <div>
                            <strong className="text-gray-700 block mb-1">
                                Product
                            </strong>
                            <div className="text-gray-900">
                                {rfq.product_name}
                            </div>
                        </div>

                        <div>
                            <strong className="text-gray-700 block mb-1">
                                HS Code
                            </strong>
                            <div className="text-gray-900">
                                {rfq.hs_code || "-"}
                            </div>
                        </div>

                        <div>
                            <strong className="text-gray-700 block mb-1">
                                Quantity
                            </strong>
                            <div className="text-gray-900">
                                {rfq.required_quantity} {rfq.unit}
                            </div>
                        </div>

                        <div>
                            <strong className="text-gray-700 block mb-1">
                                Destination
                            </strong>
                            <div className="text-gray-900">
                                {rfq.destination_country || "-"}
                            </div>
                        </div>

                        <div>
                            <strong className="text-gray-700 block mb-1">
                                Delivery Date
                            </strong>
                            <div className="text-gray-900">
                                {rfq.required_delivery_date
                                    ? rfq.required_delivery_date.substring(
                                          0,
                                          10,
                                      )
                                    : "-"}
                            </div>
                        </div>

                        <div>
                            <strong className="text-gray-700 block mb-1">
                                Status
                            </strong>

                            <div>
                                {rfq.status === "closed" ? (
                                    <span className="bg-gray-800 text-white px-3 py-1 rounded-full text-sm">
                                        CLOSED
                                    </span>
                                ) : rfq.status === "awarded" ? (
                                    <span className="bg-green-600 text-white px-3 py-1 rounded-full text-sm">
                                        AWARDED
                                    </span>
                                ) : (
                                    <span className="bg-blue-600 text-white px-3 py-1 rounded-full text-sm">
                                        {rfq.status.toUpperCase()}
                                    </span>
                                )}
                            </div>
                        </div>
                    </div>

                    <div className="mt-6">
                        <strong className="text-gray-700 block mb-1">
                            Description
                        </strong>
                        <div className="mt-2 whitespace-pre-line text-gray-900">
                            {rfq.description || "-"}
                        </div>
                    </div>
                </div>
                {auth?.user?.id === rfq.user_id &&
                    rfq.status === "awarded" &&
                    rfq.awarded_quotation_id && (
                        <div className="bg-green-50 border border-green-200 rounded-2xl p-6 mb-6">
                            <h3 className="font-bold text-green-800 mb-2">
                                Supplier Awarded
                            </h3>

                            <p className="text-green-700 mb-4">
                                Supplier has been selected. You can now close
                                this RFQ.
                            </p>

                            <button
                                type="button"
                                onClick={() => {
                                    if (
                                        confirm(
                                            "Close this RFQ? No more actions will be allowed.",
                                        )
                                    ) {
                                        router.post(
                                            route("rfqs.close", rfq.id),
                                        );
                                    }
                                }}
                                className="bg-gray-900 hover:bg-black text-white px-5 py-3 rounded-xl"
                            >
                                Close RFQ
                            </button>
                        </div>
                    )}

                {/* Files */}
                <div className="bg-white rounded-2xl shadow p-6 mb-6">
                    <h2 className="text-xl font-bold mb-4 text-gray-900">
                        Attachments
                    </h2>

                    {rfq.files?.length > 0 ? (
                        <div className="space-y-2">
                            {rfq.files.map((file) => (
                                <div
                                    key={file.id}
                                    className="border border-gray-200 text-gray-900 rounded-xl p-3"
                                >
                                    <a
                                        href={`/storage/${file.file_path}`}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="text-blue-400 hover:text-blue-800 underline"
                                    >
                                        {file.file_name}
                                    </a>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <div className="text-gray-500">No files uploaded.</div>
                    )}
                </div>

                {/* Submit Quotation */}

                {["open", "quoted"].includes(rfq.status) ? (
                    <div className="bg-white rounded-2xl shadow p-6 mt-8">
                        <h2 className="text-xl font-bold text-gray-900 mb-6">
                            Submit Quotation
                        </h2>

                        {/* Controller Errors */}

                        {errors.company && (
                            <div className="mb-4 p-3 rounded-xl bg-red-100 text-red-700 border border-red-200">
                                {errors.company}
                            </div>
                        )}

                        {errors.rfq && (
                            <div className="mb-4 p-3 rounded-xl bg-red-100 text-red-700 border border-red-200">
                                {errors.rfq}
                            </div>
                        )}

                        {errors.quotation && (
                            <div className="mb-4 p-3 rounded-xl bg-red-100 text-red-700 border border-red-200">
                                {errors.quotation}
                            </div>
                        )}

                        <form onSubmit={submitQuotation} className="space-y-6">
                            <div className="grid md:grid-cols-2 gap-6">
                                {/* Unit Price */}

                                <div>
                                    <label className="block text-sm font-semibold text-gray-700 mb-2">
                                        Unit Price *
                                    </label>

                                    <input
                                        type="number"
                                        step="0.01"
                                        required
                                        placeholder="Example: 2.85"
                                        value={data.unit_price}
                                        onChange={(e) =>
                                            setData(
                                                "unit_price",
                                                e.target.value,
                                            )
                                        }
                                        className="w-full border border-gray-300 rounded-xl p-3 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    />

                                    {errors.unit_price && (
                                        <div className="text-red-500 text-sm mt-1">
                                            {errors.unit_price}
                                        </div>
                                    )}
                                </div>

                                {/* MOQ */}

                                <div>
                                    <label className="block text-sm font-semibold text-gray-700 mb-2">
                                        Minimum Order Quantity
                                    </label>

                                    <input
                                        type="number"
                                        step="0.01"
                                        placeholder="Example: 1000"
                                        value={data.minimum_order_quantity}
                                        onChange={(e) =>
                                            setData(
                                                "minimum_order_quantity",
                                                e.target.value,
                                            )
                                        }
                                        className="w-full border border-gray-300 rounded-xl p-3 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    />

                                    {errors.minimum_order_quantity && (
                                        <div className="text-red-500 text-sm mt-1">
                                            {errors.minimum_order_quantity}
                                        </div>
                                    )}
                                </div>

                                {/* Lead Time */}

                                <div>
                                    <label className="block text-sm font-semibold text-gray-700 mb-2">
                                        Lead Time (Days)
                                    </label>

                                    <input
                                        type="number"
                                        placeholder="Example: 30"
                                        value={data.lead_time_days}
                                        onChange={(e) =>
                                            setData(
                                                "lead_time_days",
                                                e.target.value,
                                            )
                                        }
                                        className="w-full border border-gray-300 rounded-xl p-3 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    />

                                    {errors.lead_time_days && (
                                        <div className="text-red-500 text-sm mt-1">
                                            {errors.lead_time_days}
                                        </div>
                                    )}
                                </div>
                            </div>

                            {/* Remarks */}

                            <div>
                                <label className="block text-sm font-semibold text-gray-700 mb-2">
                                    Remarks
                                </label>

                                <textarea
                                    rows="5"
                                    placeholder="Additional information about your quotation..."
                                    value={data.remarks}
                                    onChange={(e) =>
                                        setData("remarks", e.target.value)
                                    }
                                    className="w-full border border-gray-300 rounded-xl p-3 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                />

                                {errors.remarks && (
                                    <div className="text-red-500 text-sm mt-1">
                                        {errors.remarks}
                                    </div>
                                )}
                            </div>

                            {/* Submit */}

                            <div className="flex items-center gap-3">
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="bg-blue-600 hover:bg-blue-500 disabled:bg-gray-400 disabled:cursor-not-allowed text-white px-6 py-3 rounded-xl font-medium transition"
                                >
                                    {processing
                                        ? "Submitting..."
                                        : "Submit Quotation"}
                                </button>

                                <span className="text-sm text-gray-500">
                                    One quotation per company.
                                </span>
                            </div>
                        </form>
                    </div>
                ) : (
                    <div className="bg-yellow-50 border border-yellow-300 rounded-2xl p-6 mt-8">
                        <h2 className="text-lg font-bold text-yellow-800 mb-2">
                            Quotation Closed
                        </h2>

                        <p className="text-yellow-700">
                            This RFQ is no longer accepting quotations.
                        </p>

                        <div className="mt-3 text-sm text-yellow-800">
                            Current Status:
                            <span className="font-semibold ml-2 uppercase">
                                {rfq.status}
                            </span>
                        </div>
                    </div>
                )}

                {/* Quotations */}
                <div className="bg-white rounded-2xl shadow p-6">
                    <h2 className="text-xl font-bold mb-4 text-gray-900">
                        Quotations
                    </h2>

                    {rfq.quotations?.length > 0 ? (
                        <div className="space-y-4">
                            {rfq.quotations.map((quotation) => (
                                <div
                                    key={quotation.id}
                                    className="border border-gray-200 rounded-xl p-4 text-gray-900"
                                >
                                    <div className="mb-1">
                                        <strong className="text-gray-700">
                                            Company:
                                        </strong>{" "}
                                        {quotation.company?.nama_perusahaan}
                                    </div>

                                    <div className="mb-1">
                                        <strong className="text-gray-700">
                                            Unit Price:
                                        </strong>{" "}
                                        {quotation.unit_price}
                                    </div>

                                    <div className="mb-1">
                                        <strong className="text-gray-700">
                                            MOQ:
                                        </strong>{" "}
                                        {quotation.minimum_order_quantity ||
                                            "-"}
                                    </div>

                                    <div className="mb-1">
                                        <strong className="text-gray-700">
                                            Lead Time:
                                        </strong>{" "}
                                        {quotation.lead_time_days || "-"} days
                                    </div>

                                    <div>
                                        <strong className="text-gray-700">
                                            Status:
                                        </strong>{" "}
                                        {quotation.status}
                                    </div>

                                    {/* SUBMITTED */}
                                    {auth.user.id === rfq.user_id &&
                                        quotation.status === "submitted" && (
                                            <div className="flex gap-2 mt-4">
                                                <button
                                                    type="button"
                                                    onClick={() =>
                                                        router.post(
                                                            route(
                                                                "quotations.accept",
                                                                quotation.id,
                                                            ),
                                                        )
                                                    }
                                                    className="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded-lg"
                                                >
                                                    Accept
                                                </button>

                                                <button
                                                    type="button"
                                                    onClick={() =>
                                                        router.post(
                                                            route(
                                                                "quotations.reject",
                                                                quotation.id,
                                                            ),
                                                        )
                                                    }
                                                    className="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded-lg"
                                                >
                                                    Reject
                                                </button>
                                            </div>
                                        )}

                                    {/* ACCEPTED */}
                                    {auth.user.id === rfq.user_id &&
                                        quotation.status === "accepted" && (
                                            <div className="flex gap-2 mt-4">
                                                <button
                                                    type="button"
                                                    onClick={() =>
                                                        router.post(
                                                            route(
                                                                "quotations.award",
                                                                quotation.id,
                                                            ),
                                                        )
                                                    }
                                                    className="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg"
                                                >
                                                    Award Supplier
                                                </button>
                                            </div>
                                        )}

                                    {/* AWARDED */}
                                    {quotation.status === "awarded" && (
                                        <div className="mt-4">
                                            <span className="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-semibold">
                                                ✓ Awarded Supplier
                                            </span>
                                        </div>
                                    )}

                                    {/* REJECTED */}
                                    {quotation.status === "rejected" && (
                                        <div className="mt-4">
                                            <span className="inline-flex items-center px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm font-semibold">
                                                ✕ Rejected
                                            </span>
                                        </div>
                                    )}
                                </div>
                            ))}
                        </div>
                    ) : (
                        <div className="text-gray-500">No quotations yet.</div>
                    )}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
