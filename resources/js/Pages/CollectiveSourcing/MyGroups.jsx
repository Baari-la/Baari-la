import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";

export default function MyGroups({ auth, groups }) {
    const statusColors = {
        open: "bg-blue-100 text-blue-700",
        moq_reached: "bg-green-100 text-green-700",
        rfq_created: "bg-purple-100 text-purple-700",
        completed: "bg-gray-100 text-gray-700",
    };

    const statusLabels = {
        open: "Open",
        moq_reached: "MOQ Reached",
        rfq_created: "RFQ Generated",
        completed: "Completed",
    };

    const formatNumber = (value) =>
        Number(value || 0).toLocaleString(undefined, {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2,
        });

    const totalGroups = groups?.data?.length || 0;

    const moqReached =
        groups?.data?.filter(
            (group) =>
                Number(group.current_quantity || 0) >=
                Number(group.moq_quantity || 0),
        ).length || 0;

    const rfqGenerated =
        groups?.data?.filter((group) => group.status === "rfq_created")
            .length || 0;

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

                {/* Summary */}
                <div className="grid md:grid-cols-3 gap-4 mb-6">
                    <div className="bg-white rounded-2xl shadow p-5">
                        <div className="text-sm text-gray-500">
                            Total Groups
                        </div>

                        <div className="text-3xl font-bold text-gray-900 mt-2">
                            {totalGroups}
                        </div>
                    </div>

                    <div className="bg-white rounded-2xl shadow p-5">
                        <div className="text-sm text-gray-500">MOQ Reached</div>

                        <div className="text-3xl font-bold text-green-600 mt-2">
                            {moqReached}
                        </div>
                    </div>

                    <div className="bg-white rounded-2xl shadow p-5">
                        <div className="text-sm text-gray-500">
                            RFQ Generated
                        </div>

                        <div className="text-3xl font-bold text-purple-600 mt-2">
                            {rfqGenerated}
                        </div>
                    </div>
                </div>

                {/* Table */}
                <div className="bg-white rounded-2xl shadow overflow-hidden">
                    <table className="w-full">
                        <thead className="bg-gray-50">
                            <tr>
                                <th className="p-4 text-left">Group</th>
                                <th className="p-4 text-left">Product</th>
                                <th className="p-4 text-left">Qty Progress</th>
                                <th className="p-4 text-left">Members</th>
                                <th className="p-4 text-left">Status</th>
                                <th className="p-4 text-right">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            {groups?.data?.length > 0 ? (
                                groups.data.map((group) => {
                                    const currentQty = Number(
                                        group.current_quantity || 0,
                                    );

                                    const moqQty = Number(
                                        group.moq_quantity || 0,
                                    );

                                    const progress =
                                        moqQty > 0
                                            ? Math.round(
                                                  (currentQty / moqQty) * 100,
                                              )
                                            : 0;

                                    const progressColor =
                                        progress >= 100
                                            ? "bg-green-600"
                                            : progress >= 50
                                              ? "bg-yellow-500"
                                              : "bg-red-500";

                                    return (
                                        <tr
                                            key={group.id}
                                            className="border-t hover:bg-gray-50"
                                        >
                                            {/* Group */}
                                            <td className="p-4">
                                                <div className="font-medium text-gray-900">
                                                    {group.group_code}
                                                </div>

                                                {group.rfq?.rfq_number && (
                                                    <div className="text-xs text-gray-500 mt-1">
                                                        RFQ:{" "}
                                                        {group.rfq.rfq_number}
                                                    </div>
                                                )}
                                            </td>

                                            {/* Product */}
                                            <td className="p-4">
                                                <div className="font-medium text-gray-900">
                                                    {group.product_name}
                                                </div>

                                                {group.specification && (
                                                    <div className="text-xs text-gray-500 mt-1">
                                                        Spec:{" "}
                                                        {group.specification}
                                                    </div>
                                                )}
                                            </td>

                                            {/* Progress */}
                                            <td className="p-4 min-w-[220px]">
                                                <div className="text-sm text-gray-700 mb-2">
                                                    {formatNumber(currentQty)} /{" "}
                                                    {formatNumber(moqQty)}{" "}
                                                    {group.unit}
                                                </div>

                                                <div className="w-full bg-gray-200 rounded-full h-2">
                                                    <div
                                                        className={`${progressColor} h-2 rounded-full`}
                                                        style={{
                                                            width: `${Math.min(
                                                                progress,
                                                                100,
                                                            )}%`,
                                                        }}
                                                    />
                                                </div>

                                                <div className="text-xs mt-1">
                                                    {progress >= 100 ? (
                                                        <span className="font-medium text-green-700">
                                                            MOQ Reached (
                                                            {progress}%)
                                                        </span>
                                                    ) : (
                                                        <span className="text-gray-600">
                                                            {progress}%
                                                        </span>
                                                    )}
                                                </div>
                                            </td>

                                            {/* Members */}
                                            <td className="p-4">
                                                {group.requests_count || 0}
                                            </td>

                                            {/* Status */}
                                            <td className="p-4">
                                                <span
                                                    className={`px-3 py-1 rounded-full text-sm font-medium ${
                                                        statusColors[
                                                            group.status
                                                        ] ||
                                                        "bg-gray-100 text-gray-700"
                                                    }`}
                                                >
                                                    {statusLabels[
                                                        group.status
                                                    ] || group.status}
                                                </span>
                                            </td>

                                            {/* Action */}
                                            <td className="p-4 text-right">
                                                {group.rfq_id ? (
                                                    <Link
                                                        href={route(
                                                            "rfqs.show",
                                                            group.rfq_id,
                                                        )}
                                                        className="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition"
                                                    >
                                                        View RFQ
                                                    </Link>
                                                ) : (
                                                    <span className="text-gray-400">
                                                        -
                                                    </span>
                                                )}
                                            </td>
                                        </tr>
                                    );
                                })
                            ) : (
                                <tr>
                                    <td
                                        colSpan="6"
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
