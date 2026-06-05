import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";

export default function MyGroups({ auth, groups }) {
    const statusColors = {
        open: "bg-blue-100 text-blue-700",
        moq_reached: "bg-green-100 text-green-700",
        rfq_generated: "bg-purple-100 text-purple-700",
        completed: "bg-gray-100 text-gray-700",
    };

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="My Groups" />

            <div className="max-w-7xl mx-auto p-6">
                {/* Header */}
                <div className="mb-6">
                    <h1 className="text-3xl font-bold text-gray-900">
                        My Groups
                    </h1>

                    <p className="text-gray-500">
                        Collective sourcing groups you participate in
                    </p>
                </div>

                {/* Table */}
                <div className="bg-white rounded-2xl shadow overflow-hidden">
                    <table className="w-full">
                        <thead className="bg-gray-50">
                            <tr>
                                <th className="text-left p-4">Group</th>

                                <th className="text-left p-4">Product</th>

                                <th className="text-left p-4">Total Demand</th>

                                <th className="text-left p-4">MOQ Target</th>

                                <th className="text-left p-4">Members</th>

                                <th className="text-left p-4">Status</th>

                                <th className="text-right p-4">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            {groups?.data?.length > 0 ? (
                                groups.data.map((group) => (
                                    <tr
                                        key={group.id}
                                        className="border-t hover:bg-gray-50"
                                    >
                                        <td className="p-4">
                                            {group.group_code}
                                        </td>

                                        <td className="p-4">
                                            {group.product_name}
                                        </td>

                                        <td className="p-4">
                                            {Number(
                                                group.current_quantity ?? 0,
                                            ).toLocaleString()}
                                        </td>

                                        <td className="p-4">
                                            {Number(
                                                group.target_quantity ?? 0,
                                            ).toLocaleString()}
                                        </td>

                                        <td className="p-4">
                                            {group.members_count ?? 0}
                                        </td>

                                        <td className="p-4">
                                            <span
                                                className={`px-3 py-1 rounded-full text-sm font-medium ${
                                                    statusColors[
                                                        group.status
                                                    ] ||
                                                    "bg-gray-100 text-gray-700"
                                                }`}
                                            >
                                                {group.status}
                                            </span>
                                        </td>

                                        <td className="p-4 text-right">
                                            <Link
                                                href={route(
                                                    "collective-sourcing.groups.show",
                                                    group.id,
                                                )}
                                                className="text-blue-600 hover:text-blue-800 font-medium"
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
                                        className="text-center p-10 text-gray-500"
                                    >
                                        You have not joined any groups yet.
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
