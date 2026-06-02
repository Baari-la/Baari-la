import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";

export default function Index({ auth, rfqs }) {
    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="RFQ Marketplace" />

            <div className="max-w-7xl mx-auto p-6 text-gray-900">
                <div className="flex justify-between items-center mb-6">
                    <div>
                        <h1 className="text-3xl font-bold text-gray-900">
                            Request For Quotation
                        </h1>

                        <p className="text-gray-500 mt-1">
                            Manage your RFQs and supplier quotations.
                        </p>
                    </div>

                    <Link
                        href={route("rfqs.create")}
                        className="bg-blue-600 hover:bg-blue-500 text-white px-5 py-3 rounded-xl font-semibold transition"
                    >
                        Create RFQ
                    </Link>
                </div>

                <div className="bg-white rounded-2xl shadow overflow-hidden border border-gray-100">
                    <table className="w-full">
                        <thead className="bg-gray-100 text-gray-700">
                            <tr>
                                <th className="text-left p-4 font-bold">
                                    RFQ Number
                                </th>
                                <th className="text-left p-4 font-bold">
                                    Product
                                </th>
                                <th className="text-left p-4 font-bold">
                                    Quantity
                                </th>
                                <th className="text-left p-4 font-bold">
                                    Destination
                                </th>
                                <th className="text-left p-4 font-bold">
                                    Quotations
                                </th>
                                <th className="text-left p-4 font-bold">
                                    Status
                                </th>
                                <th className="text-left p-4 font-bold">
                                    Action
                                </th>
                            </tr>
                        </thead>

                        <tbody className="divide-y divide-gray-100 bg-white text-gray-900">
                            {rfqs.data.length === 0 ? (
                                <tr>
                                    <td
                                        colSpan="7"
                                        className="p-8 text-center text-gray-500"
                                    >
                                        No RFQ found.
                                    </td>
                                </tr>
                            ) : (
                                rfqs.data.map((rfq) => (
                                    <tr
                                        key={rfq.id}
                                        className="hover:bg-gray-50 transition"
                                    >
                                        <td className="p-4 font-semibold text-gray-900">
                                            {rfq.rfq_number}
                                        </td>

                                        <td className="p-4 text-gray-800">
                                            {rfq.product_name}
                                        </td>

                                        <td className="p-4 text-gray-800">
                                            {rfq.required_quantity} {rfq.unit}
                                        </td>

                                        <td className="p-4 text-gray-800">
                                            {rfq.destination_country || "-"}
                                        </td>

                                        <td className="p-4 text-gray-800 font-medium">
                                            {rfq.quotations?.length || 0}
                                        </td>

                                        <td className="p-4">
                                            <span
                                                className={`px-3 py-1 rounded-full text-xs font-bold inline-block ${
                                                    rfq.status === "open"
                                                        ? "bg-green-100 text-green-800"
                                                        : "bg-gray-100 text-gray-800"
                                                }`}
                                            >
                                                {rfq.status}
                                            </span>
                                        </td>

                                        <td className="p-4">
                                            <Link
                                                href={route(
                                                    "rfqs.show",
                                                    rfq.id,
                                                )}
                                                className="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg text-sm transition inline-block font-medium"
                                            >
                                                View
                                            </Link>
                                        </td>
                                    </tr>
                                ))
                            )}
                        </tbody>
                    </table>
                </div>

                {/* Pagination */}
                {rfqs.links && (
                    <div className="flex flex-wrap gap-2 mt-6">
                        {rfqs.links.map((link, index) => (
                            <Link
                                key={index}
                                href={link.url || "#"}
                                dangerouslySetInnerHTML={{
                                    __html: link.label,
                                }}
                                className={`px-4 py-2 rounded-lg border text-sm font-medium transition ${
                                    link.active
                                        ? "bg-blue-600 text-white border-blue-600"
                                        : "bg-white text-gray-700 border-gray-300 hover:bg-gray-50"
                                }`}
                            />
                        ))}
                    </div>
                )}
            </div>
        </AuthenticatedLayout>
    );
}
