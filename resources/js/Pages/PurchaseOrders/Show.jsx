import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, router } from "@inertiajs/react";

export default function Show({
    auth,
    purchaseOrder,
}) {
    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title={purchaseOrder.po_number} />

            <div className="max-w-5xl mx-auto p-6 text-gray-900">

                {/* Header */}

                <div className="flex justify-between items-center mb-6">
                    <h1 className="text-3xl font-bold">
                        {purchaseOrder.po_number}
                    </h1>

                    <Link
                        href={route("purchase-orders.index")}
                        className="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg"
                    >
                        Back
                    </Link>
                </div>

                <div className="bg-white rounded-2xl shadow p-6">

                    <div className="grid md:grid-cols-2 gap-6">

                        {/* Supplier */}

                        <div>
                            <strong className="text-gray-700">
                                Supplier
                            </strong>

                            <div className="text-gray-600 mt-1">
                                {purchaseOrder.supplier?.nama_perusahaan}
                            </div>
                        </div>

                        {/* Status */}

                        <div>
                            <strong className="text-gray-700">
                                Status
                            </strong>

                            <div className="mt-2">
                                {purchaseOrder.status === "pending" && (
                                    <span className="inline-flex px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-sm font-semibold">
                                        Pending
                                    </span>
                                )}

                                {purchaseOrder.status === "confirmed" && (
                                    <span className="inline-flex px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-sm font-semibold">
                                        Confirmed
                                    </span>
                                )}

                                {purchaseOrder.status === "production" && (
                                    <span className="inline-flex px-3 py-1 rounded-full bg-indigo-100 text-indigo-800 text-sm font-semibold">
                                        Production
                                    </span>
                                )}

                                {purchaseOrder.status === "shipped" && (
                                    <span className="inline-flex px-3 py-1 rounded-full bg-purple-100 text-purple-800 text-sm font-semibold">
                                        Shipped
                                    </span>
                                )}

                                {purchaseOrder.status === "completed" && (
                                    <span className="inline-flex px-3 py-1 rounded-full bg-green-100 text-green-800 text-sm font-semibold">
                                        Completed
                                    </span>
                                )}

                                {purchaseOrder.status === "cancelled" && (
                                    <span className="inline-flex px-3 py-1 rounded-full bg-red-100 text-red-800 text-sm font-semibold">
                                        Cancelled
                                    </span>
                                )}
                            </div>

                            {/* Workflow Buttons */}

                            {purchaseOrder.status === "pending" && (
                                <button
                                    type="button"
                                    onClick={() =>
                                        router.post(
                                            route(
                                                "purchase-orders.confirm",
                                                purchaseOrder.id
                                            )
                                        )
                                    }
                                    className="mt-4 bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg"
                                >
                                    Confirm Order
                                </button>
                            )}

                            {purchaseOrder.status === "confirmed" && (
                                <button
                                    type="button"
                                    onClick={() =>
                                        router.post(
                                            route(
                                                "purchase-orders.production",
                                                purchaseOrder.id
                                            )
                                        )
                                    }
                                    className="mt-4 bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg"
                                >
                                    Start Production
                                </button>
                            )}

                            {purchaseOrder.status === "production" && (
                                <button
                                    type="button"
                                    onClick={() =>
                                        router.post(
                                            route(
                                                "purchase-orders.shipped",
                                                purchaseOrder.id
                                            )
                                        )
                                    }
                                    className="mt-4 bg-purple-600 hover:bg-purple-500 text-white px-4 py-2 rounded-lg"
                                >
                                    Mark as Shipped
                                </button>
                            )}

                            {purchaseOrder.status === "shipped" && (
                                <button
                                    type="button"
                                    onClick={() =>
                                        router.post(
                                            route(
                                                "purchase-orders.completed",
                                                purchaseOrder.id
                                            )
                                        )
                                    }
                                    className="mt-4 bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded-lg"
                                >
                                    Complete Order
                                </button>
                            )}

                            {purchaseOrder.status === "completed" && (
                                <div className="mt-4 text-green-600 font-semibold">
                                    ✓ Order Completed
                                </div>
                            )}
                        </div>

                        {/* Unit Price */}

                        <div>
                            <strong className="text-gray-700">
                                Unit Price
                            </strong>

                            <div className="text-gray-600 mt-1">
                                {purchaseOrder.currency}{" "}
                                {Number(
                                    purchaseOrder.unit_price
                                ).toLocaleString()}
                            </div>
                        </div>

                        {/* Quantity */}

                        <div>
                            <strong className="text-gray-700">
                                Quantity
                            </strong>

                            <div className="text-gray-600 mt-1">
                                {Number(
                                    purchaseOrder.quantity
                                ).toLocaleString()}
                            </div>
                        </div>

                        {/* Total Amount */}

                        <div>
                            <strong className="text-gray-700">
                                Total Amount
                            </strong>

                            <div className="text-lg font-bold text-green-600 mt-1">
                                {purchaseOrder.currency}{" "}
                                {Number(
                                    purchaseOrder.total_amount
                                ).toLocaleString()}
                            </div>
                        </div>

                        {/* Delivery Date */}

                        <div>
                            <strong className="text-gray-700">
                                Delivery Date
                            </strong>

                            <div className="text-gray-600 mt-1">
                                {purchaseOrder.delivery_date || "-"}
                            </div>
                        </div>
                    </div>

                    {/* Workflow Timeline */}

                    <div className="mt-8 border-t pt-6">
                        <h2 className="text-lg font-semibold mb-4">
                            Order Progress
                        </h2>

                        <div className="flex flex-wrap gap-3">
                            <span className="px-3 py-1 rounded-full bg-blue-100">
                                Confirmed
                            </span>

                            <span className="px-3 py-1 rounded-full bg-indigo-100">
                                Production
                            </span>

                            <span className="px-3 py-1 rounded-full bg-purple-100">
                                Shipped
                            </span>

                            <span className="px-3 py-1 rounded-full bg-green-100">
                                Completed
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </AuthenticatedLayout>
    );
}