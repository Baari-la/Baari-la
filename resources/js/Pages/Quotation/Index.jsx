import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";

export default function Index({ auth, quotations }) {
    const getStatusBadge = (status) => {
        switch (status) {
            case "submitted":
                return (
                    <span className="px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-xs font-semibold">
                        🟡 Submitted
                    </span>
                );

            case "accepted":
                return (
                    <span className="px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-semibold">
                        🔵 Accepted
                    </span>
                );

            case "awarded":
                return (
                    <span className="px-3 py-1 rounded-full bg-green-100 text-green-800 text-xs font-semibold">
                        🟢 Awarded
                    </span>
                );

            case "rejected":
                return (
                    <span className="px-3 py-1 rounded-full bg-red-100 text-red-800 text-xs font-semibold">
                        🔴 Rejected
                    </span>
                );

            default:
                return (
                    <span className="px-3 py-1 rounded-full bg-gray-100 text-gray-800 text-xs font-semibold">
                        {status}
                    </span>
                );
        }
    };

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="My Quotations" />

            <div className="max-w-7xl mx-auto p-6">
                <div className="mb-8">
                    <h1 className="text-3xl font-bold text-gray-900">
                        My Quotations
                    </h1>

                    <p className="text-gray-500 mt-2">
                        Track all quotations submitted by your company.
                    </p>
                </div>

                <div className="bg-white rounded-2xl shadow overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="w-full">
                            <thead className="bg-gray-50 border-b">
                                <tr>
                                    <th className="text-left px-6 py-4 text-sm font-semibold text-gray-700">
                                        RFQ Number
                                    </th>

                                    <th className="text-left px-6 py-4 text-sm font-semibold text-gray-700">
                                        Product
                                    </th>

                                    <th className="text-left px-6 py-4 text-sm font-semibold text-gray-700">
                                        Quantity
                                    </th>

                                    <th className="text-left px-6 py-4 text-sm font-semibold text-gray-700">
                                        Unit Price
                                    </th>

                                    <th className="text-left px-6 py-4 text-sm font-semibold text-gray-700">
                                        Status
                                    </th>

                                    <th className="text-left px-6 py-4 text-sm font-semibold text-gray-700">
                                        Date
                                    </th>

                                    <th className="text-left px-6 py-4 text-sm font-semibold text-gray-700">
                                        Action
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                {quotations.length > 0 ? (
                                    quotations.map((quotation) => (
                                        <tr
                                            key={quotation.id}
                                            className="border-b hover:bg-gray-50"
                                        >
                                            <td className="px-6 py-4 text-gray-900 font-medium">
                                                {quotation.rfq?.rfq_number}
                                            </td>

                                            <td className="px-6 py-4 text-gray-900">
                                                {quotation.rfq?.product_name}
                                            </td>

                                            <td className="px-6 py-4 text-gray-900">
                                                {
                                                    quotation.rfq
                                                        ?.required_quantity
                                                }{" "}
                                                {quotation.rfq?.unit}
                                            </td>

                                            <td className="px-6 py-4 text-gray-900">
                                                {quotation.unit_price}
                                            </td>

                                            <td className="px-6 py-4">
                                                {getStatusBadge(
                                                    quotation.status,
                                                )}
                                            </td>

                                            <td className="px-6 py-4 text-gray-600">
                                                {quotation.created_at?.substring(
                                                    0,
                                                    10,
                                                )}
                                            </td>

                                            <td className="px-6 py-4">
                                                <Link
                                                    href={route(
                                                        "rfqs.show",
                                                        quotation.rfq_id,
                                                    )}
                                                    className="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg text-sm font-medium"
                                                >
                                                    View RFQ
                                                </Link>
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td
                                            colSpan="7"
                                            className="px-6 py-10 text-center text-gray-500"
                                        >
                                            No quotations found.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
