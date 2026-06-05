import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";

export default function MyRequests({ auth, requests }) {
    const statusColors = {
        open: "bg-blue-100 text-blue-700",
        grouped: "bg-yellow-100 text-yellow-700",
        rfq_generated: "bg-green-100 text-green-700",
        completed: "bg-gray-100 text-gray-700",
    };

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="My Requests" />

            <div className="max-w-7xl mx-auto p-6">
                {/* Header */}
                <div className="flex justify-between items-center mb-6">
                    <div>
                        <h1 className="text-3xl font-bold text-gray-900">
                            My Requests
                        </h1>

                        <p className="text-gray-500">
                            Manage your sourcing requirements
                        </p>
                    </div>

                    <Link
                        href={route("collective-sourcing.create")}
                        className="bg-blue-600 hover:bg-blue-500 text-white px-5 py-3 rounded-xl font-medium transition"
                    >
                        Create Requirement
                    </Link>
                </div>

                {/* Table */}
                <div className="bg-white rounded-2xl shadow overflow-hidden">
                    <table className="w-full">
                        <thead className="bg-gray-50">
                            <tr>
                                <th className="text-left p-4 font-semibold text-gray-700">
                                    Product
                                </th>

                                <th className="text-left p-4 font-semibold text-gray-700">
                                    Quantity
                                </th>

                                <th className="text-left p-4 font-semibold text-gray-700">
                                    Unit
                                </th>

                                <th className="text-left p-4 font-semibold text-gray-700">
                                    Status
                                </th>

                                <th className="text-left p-4 font-semibold text-gray-700">
                                    Created
                                </th>

                                <th className="text-right p-4 font-semibold text-gray-700">
                                    Action
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            {requests?.data?.length > 0 ? (
                                requests.data.map((request) => (
                                    <tr
                                        key={request.id}
                                        className="border-t hover:bg-gray-50"
                                    >
                                        <td className="p-4 text-gray-900">
                                            {request.product_name}
                                        </td>

                                        <td className="p-4 text-gray-900">
                                            {Number(
                                                request.quantity ?? 0,
                                            ).toLocaleString()}
                                        </td>

                                        <td className="p-4 text-gray-900">
                                            {request.unit}
                                        </td>

                                        <td className="p-4">
                                            <span
                                                className={`px-3 py-1 rounded-full text-sm font-medium ${
                                                    statusColors[
                                                        request.status
                                                    ] ||
                                                    "bg-gray-100 text-gray-700"
                                                }`}
                                            >
                                                {request.status}
                                            </span>
                                        </td>

                                        <td className="p-4 text-gray-900">
                                            {request.created_at
                                                ? new Date(
                                                      request.created_at,
                                                  ).toLocaleDateString("en-GB")
                                                : "-"}
                                        </td>

                                        <td className="p-4 text-right">
                                            <Link
                                                href={route(
                                                    "collective-sourcing.show-group",
                                                    request.group_id,
                                                )}
                                            >
                                                View
                                            </Link>
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td
                                        colSpan="6"
                                        className="text-center p-10 text-gray-500"
                                    >
                                        No requests found.
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
