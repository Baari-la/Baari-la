import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";

export default function MyGroups({ auth, groups }) {
    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="My Groups" />

            <div className="max-w-7xl mx-auto p-6">
                <h1 className="text-3xl font-bold mb-6 text-gray-900">
                    My Groups
                </h1>

                <div className="bg-white rounded-2xl shadow overflow-hidden">
                    <table className="w-full">
                        <thead className="bg-gray-100">
                            <tr>
                                <th className="p-4 text-left">Product</th>
                                <th className="p-4 text-left">MOQ</th>
                                <th className="p-4 text-left">Current</th>
                                <th className="p-4 text-left">Members</th>
                                <th className="p-4 text-left">Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            {groups.data.map((group) => (
                                <tr key={group.id} className="border-t">
                                    <td className="p-4">
                                        {group.product_name}
                                    </td>

                                    <td className="p-4">
                                        {group.moq_quantity}
                                    </td>

                                    <td className="p-4">
                                        {group.current_quantity}
                                    </td>

                                    <td className="p-4">
                                        {group.requests_count}
                                    </td>

                                    <td className="p-4">{group.status}</td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
