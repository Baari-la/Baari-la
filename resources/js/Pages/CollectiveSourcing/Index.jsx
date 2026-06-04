import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";

export default function Index({ auth, groups }) {
    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Open Demand Groups" />

            <div className="max-w-7xl mx-auto p-6">
                {/* Header */}

                <div className="flex justify-between items-center mb-6">
                    <div>
                        <h1 className="text-3xl font-bold text-gray-900">
                            Open Demand Groups
                        </h1>

                        <p className="text-gray-500">MOQ Matching Network</p>
                    </div>

                    <Link
                        href={route("collective-sourcing.create")}
                        className="bg-blue-600 hover:bg-blue-500 text-white px-5 py-3 rounded-xl"
                    >
                        Create Requirement
                    </Link>
                </div>

                {/* Table */}

                <div className="bg-white rounded-2xl shadow overflow-hidden">
                    <div className="overflow-x-auto">
                        {/* Summary Card di atas table */}
                        <div className="grid md:grid-cols-3 gap-4 mb-6">
                            <div className="bg-white p-5 rounded-2xl shadow">
                                <div className="text-gray-500 text-sm">
                                    Open Groups
                                </div>

                                <div className="text-3xl font-bold text-gray-900">
                                    {groups.data.length}
                                </div>
                            </div>

                            <div className="bg-white p-5 rounded-2xl shadow">
                                <div className="text-gray-500 text-sm">
                                    MOQ Reached
                                </div>

                                <div className="text-3xl font-bold text-green-600">
                                    {
                                        groups.data.filter(
                                            (g) => g.status === "moq_reached",
                                        ).length
                                    }
                                </div>
                            </div>

                            <div className="bg-white p-5 rounded-2xl shadow">
                                <div className="text-gray-500 text-sm">
                                    Active Members
                                </div>

                                <div className="text-3xl font-bold text-blue-600">
                                    {groups.data.reduce(
                                        (sum, g) => sum + g.members_count,
                                        0,
                                    )}
                                </div>
                            </div>
                        </div>
                        {/* Batas summary card */}
                        <table className="w-full">
                            <thead className="bg-gray-100">
                                <tr>
                                    <th className="text-left p-4">Product</th>

                                    <th className="text-left p-4">MOQ</th>

                                    <th className="text-left p-4">Current</th>

                                    <th className="text-left p-4">Members</th>

                                    <th className="text-left p-4">Progress</th>

                                    <th className="text-left p-4">Status</th>

                                    <th className="text-left p-4">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                {groups.data.length > 0 ? (
                                    groups.data.map((group) => (
                                        <tr key={group.id} className="border-t">
                                            <td className="p-4">
                                                <div className="font-semibold text-gray-900">
                                                    {group.product_name}
                                                </div>

                                                <div className="text-sm text-gray-500">
                                                    {group.specification}
                                                </div>
                                            </td>

                                            <td className="p-4">
                                                {Number(
                                                    group.moq_quantity,
                                                ).toLocaleString()}{" "}
                                                {group.unit}
                                            </td>

                                            <td className="p-4">
                                                {Number(
                                                    group.current_quantity,
                                                ).toLocaleString()}{" "}
                                                {group.unit}
                                            </td>

                                            <td className="p-4">
                                                {group.members_count}
                                            </td>

                                            <td className="p-4 min-w-[220px]">
                                                <div className="w-full bg-gray-200 rounded-full h-3">
                                                    <div
                                                        className="bg-blue-600 h-3 rounded-full"
                                                        style={{
                                                            width: `${Math.min(
                                                                group.progress,
                                                                100,
                                                            )}%`,
                                                        }}
                                                    />
                                                </div>

                                                <div className="text-sm text-gray-600 mt-1">
                                                    {group.status ===
                                                    "moq_reached"
                                                        ? "100%+"
                                                        : `${group.progress}%`}
                                                </div>
                                            </td>

                                            <td className="p-4">
                                                {group.status === "open" && (
                                                    <span className="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-sm">
                                                        Open
                                                    </span>
                                                )}

                                                {group.status ===
                                                    "moq_reached" && (
                                                    <span className="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm">
                                                        MOQ Reached
                                                    </span>
                                                )}

                                                {group.status ===
                                                    "rfq_created" && (
                                                    <span className="px-3 py-1 rounded-full bg-purple-100 text-purple-700 text-sm">
                                                        RFQ Created
                                                    </span>
                                                )}

                                                {group.status === "closed" && (
                                                    <span className="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-sm">
                                                        Closed
                                                    </span>
                                                )}
                                            </td>

                                            <td className="p-4">
                                                <Link
                                                    href={route(
                                                        "collective-sourcing.show",
                                                        group.id,
                                                    )}
                                                    className="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300"
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
                                            className="text-center p-8 text-gray-500"
                                        >
                                            No demand groups found.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>

                {/* Pagination */}

                {groups.links && (
                    <div className="flex flex-wrap gap-2 mt-6">
                        {groups.links.map((link, index) => (
                            <Link
                                key={index}
                                href={link.url || "#"}
                                dangerouslySetInnerHTML={{
                                    __html: link.label,
                                }}
                                className={`px-3 py-2 rounded-lg border ${
                                    link.active
                                        ? "bg-blue-600 text-white"
                                        : "bg-white text-gray-700"
                                }`}
                            />
                        ))}
                    </div>
                )}
            </div>
        </AuthenticatedLayout>
    );
}
