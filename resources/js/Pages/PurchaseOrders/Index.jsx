import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";

export default function Index({
    auth,
    purchaseOrders,
}) {
    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Purchase Orders" />

            {/* Menambahkan text-gray-900 sebagai warna teks utama halaman */}
            <div className="max-w-7xl mx-auto p-6 text-gray-900">

                {/* Mengubah text-white menjadi text-gray-900 agar judul terlihat */}
                <h1 className="text-3xl font-bold mb-6 text-gray-900">
                    Purchase Orders
                </h1>

                <div className="bg-white rounded-2xl shadow overflow-hidden">

                    <table className="w-full">

                        {/* Menambahkan text-gray-700 untuk teks header tabel */}
                        <thead className="bg-gray-100 text-gray-700 font-semibold">
                            <tr>
                                <th className="p-4 text-left">
                                    PO Number
                                </th>

                                <th className="p-4 text-left">
                                    Supplier
                                </th>

                                <th className="p-4 text-left">
                                    Amount
                                </th>

                                <th className="p-4 text-left">
                                    Status
                                </th>

                                <th className="p-4">
                                    Action
                                </th>
                            </tr>
                        </thead>

                        {/* Menambahkan text-gray-600 untuk teks isi tabel */}
                        <tbody className="text-gray-600">

                            {purchaseOrders.map((po) => (

                                <tr
                                    key={po.id}
                                    className="border-t hover:bg-gray-50 transition-colors"
                                >
                                    <td className="p-4 font-medium text-gray-900">
                                        {po.po_number}
                                    </td>

                                    <td className="p-4">
                                        {po.supplier?.nama_perusahaan}
                                    </td>

                                    <td className="p-4">
                                        {po.currency}{" "}
                                        {Number(
                                            po.total_amount
                                        ).toLocaleString()}
                                    </td>

                                    <td className="p-4">
                                        {po.status}
                                    </td>

                                    <td className="p-4 text-center">

                                        <Link
                                            href={route(
                                                "purchase-orders.show",
                                                po.id
                                            )}
                                            className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors inline-block"
                                        >
                                            View
                                        </Link>

                                    </td>
                                </tr>

                            ))}

                        </tbody>

                    </table>

                </div>
            </div>
        </AuthenticatedLayout>
    );
}
