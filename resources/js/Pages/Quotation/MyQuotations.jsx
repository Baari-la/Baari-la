import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";

export default function MyQuotations({ auth, quotations }) {
    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="My Quotations" />

            <div className="max-w-7xl mx-auto p-6">
                <h1 className="text-3xl font-bold text-gray-900 mb-6">
                    My Quotations
                </h1>

                <div className="bg-white rounded-2xl shadow overflow-hidden">
                    <table className="w-full">
                        <thead>
                            <tr className="bg-gray-100">
                                <th className="p-4 text-left">RFQ</th>
                                <th className="p-4 text-left">Product</th>
                                <th className="p-4 text-left">Unit Price</th>
                                <th className="p-4 text-left">Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            {quotations.data.map((quotation) => (
                                <tr key={quotation.id} className="border-t">
                                    <td className="p-4">
                                        <Link
                                            href={route(
                                                "rfqs.show",
                                                quotation.rfq.id,
                                            )}
                                            className="text-blue-600"
                                        >
                                            {quotation.rfq.rfq_number}
                                        </Link>
                                    </td>

                                    <td className="p-4">
                                        {quotation.rfq.product_name}
                                    </td>

                                    <td className="p-4">
                                        {quotation.unit_price}
                                    </td>

                                    <td className="p-4">{quotation.status}</td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
