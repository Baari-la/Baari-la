import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";

export default function Index({ auth, purchaseOrders }) {
    const totalPO = purchaseOrders.length;

    const pendingPO = purchaseOrders.filter(
        (po) => po.status === "pending",
    ).length;

    const productionPO = purchaseOrders.filter(
        (po) => po.status === "production" || po.status === "in_production",
    ).length;

    const completedPO = purchaseOrders.filter(
        (po) => po.status === "completed",
    ).length;

    const statusColors = {
        pending: "bg-yellow-100 text-yellow-700",
        confirmed: "bg-blue-100 text-blue-700",
        production: "bg-purple-100 text-purple-700",
        shipped: "bg-indigo-100 text-indigo-700",
        completed: "bg-green-100 text-green-700",
    };

    const statusLabels = {
        pending: "Pending",
        confirmed: "Confirmed",
        production: "Production",
        in_production: "In Production",
        shipped: "Shipped",
        completed: "Completed",
    };

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Purchase Orders" />

            <div className="max-w-7xl mx-auto p-6">
                {/* Header */}
                <div className="mb-6">
                    <h1 className="text-3xl font-bold text-gray-900">
                        Purchase Orders
                    </h1>

                    <p className="text-gray-500">
                        Manage and track all purchase orders
                    </p>
                </div>

                {/* Summary Cards */}
                <div className="grid md:grid-cols-4 gap-4 mb-6">
                    <div className="bg-white rounded-2xl shadow p-5">
                        <div className="text-sm text-gray-500">Total PO</div>

                        <div className="text-3xl font-bold text-gray-900">
                            {totalPO}
                        </div>
                    </div>

                    <div className="bg-yellow-50 rounded-2xl shadow p-5">
                        <div className="text-sm text-yellow-700">Pending</div>

                        <div className="text-3xl font-bold text-yellow-700">
                            {pendingPO}
                        </div>
                    </div>

                    <div className="bg-blue-50 rounded-2xl shadow p-5">
                        <div className="text-sm text-blue-700">Production</div>

                        <div className="text-3xl font-bold text-blue-700">
                            {productionPO}
                        </div>
                    </div>

                    <div className="bg-green-50 rounded-2xl shadow p-5">
                        <div className="text-sm text-green-700">Completed</div>

                        <div className="text-3xl font-bold text-green-700">
                            {completedPO}
                        </div>
                    </div>
                </div>

                {/* Table */}
                <div className="bg-white rounded-2xl shadow overflow-hidden">
                    <table className="w-full">
                        <thead className="bg-gray-50">
                            <tr>
                                <th className="p-4 text-left">PO Number</th>

                                <th className="p-4 text-left">Product</th>

                                <th className="p-4 text-left">Buyer</th>

                                <th className="p-4 text-left">Supplier</th>

                                <th className="p-4 text-left">Amount</th>

                                <th className="p-4 text-left">Status</th>

                                <th className="p-4 text-right">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            {purchaseOrders.length > 0 ? (
                                purchaseOrders.map((po) => (
                                    <tr
                                        key={po.id}
                                        className="border-t hover:bg-gray-50"
                                    >
                                        <td className="p-4 font-medium text-gray-900">
                                            {po.po_number}
                                        </td>

                                        <td className="p-4 text-gray-700">
                                            {po.rfq?.product_name || "-"}
                                        </td>

                                        <td className="p-4 text-gray-700">
                                            {po.buyer?.name || "-"}
                                        </td>

                                        <td className="p-4 text-gray-700">
                                            {po.supplier?.nama_perusahaan ||
                                                "-"}
                                        </td>

                                        <td className="p-4 text-gray-700">
                                            {po.currency}{" "}
                                            {Number(
                                                po.total_amount,
                                            ).toLocaleString(undefined, {
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2,
                                            })}
                                        </td>

                                        <td className="p-4">
                                            <span
                                                className={`px-3 py-1 rounded-full text-sm font-medium ${
                                                    statusColors[po.status]
                                                }`}
                                            >
                                                {statusLabels[po.status]}
                                            </span>
                                        </td>

                                        <td className="p-4 text-right">
                                            <Link
                                                href={route(
                                                    "purchase-orders.show",
                                                    po.id,
                                                )}
                                                className="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition"
                                            >
                                                View
                                            </Link>
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td
                                        colSpan="7"
                                        className="p-10 text-center text-gray-500"
                                    >
                                        No purchase orders found.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
