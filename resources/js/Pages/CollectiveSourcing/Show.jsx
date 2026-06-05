import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, router } from "@inertiajs/react";

export default function Show({ auth, group }) {
    const currentQty = Number(group.current_quantity ?? 0);
    const targetQty = Number(group.moq_quantity ?? 0);

    const progress =
        targetQty > 0
            ? Math.min(Math.round((currentQty / targetQty) * 100), 100)
            : 0;

    const canGenerateRFQ = group.status === "moq_reached";
    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title={group.group_code} />

            <div className="max-w-6xl mx-auto p-6">
                {/* Header */}
                <div className="flex justify-between items-center mb-6">
                    <div>
                        <h1 className="text-3xl font-bold text-gray-900">
                            {group.group_code}
                        </h1>

                        <p className="text-gray-500">
                            Collective Sourcing Group
                        </p>
                    </div>

                    <Link
                        href={route("collective-sourcing.my-groups")}
                        className="bg-gray-200 hover:bg-gray-300 text-gray-800 px-5 py-3 rounded-xl"
                    >
                        Back
                    </Link>
                </div>

                {/* Group Information */}
                <div className="bg-white rounded-2xl shadow p-6 mb-6">
                    <h2 className="text-xl font-bold mb-4">
                        Group Information
                    </h2>

                    <div className="grid md:grid-cols-2 gap-6">
                        <div>
                            <strong>Product</strong>
                            <div>{group.product_name}</div>
                        </div>

                        <div>
                            <strong>HS Code</strong>
                            <div>{group.hs_code || "-"}</div>
                        </div>

                        <div>
                            <strong>Unit</strong>
                            <div>{group.unit}</div>
                        </div>

                        <div>
                            <strong>Status</strong>
                            <div>{group.status}</div>
                        </div>
                    </div>
                </div>

                {/* Progress */}
                <div className="bg-white rounded-2xl shadow p-6 mb-6">
                    <h2 className="text-xl font-bold mb-4">MOQ Progress</h2>

                    <div className="mb-3">
                        <div className="flex justify-between">
                            <span>
                                {currentQty.toLocaleString()} {group.unit}
                            </span>

                            <span>
                                {targetQty.toLocaleString()} {group.unit}
                            </span>
                        </div>
                    </div>

                    <div className="w-full bg-gray-200 rounded-full h-5">
                        <div
                            className="bg-green-600 h-5 rounded-full"
                            style={{
                                width: `${progress}%`,
                            }}
                        />
                    </div>

                    <div className="mt-3 font-semibold">
                        {progress}% Complete
                    </div>
                </div>

                {/* Members */}
                <div className="bg-white rounded-2xl shadow p-6 mb-6">
                    <h2 className="text-xl font-bold mb-4">Members</h2>

                    {group.requests?.length > 0 ? (
                        <table className="w-full">
                            <thead>
                                <tr>
                                    <th className="text-left p-3">Company</th>

                                    <th className="text-left p-3">Quantity</th>

                                    <th className="text-left p-3">Joined</th>
                                </tr>
                            </thead>

                            <tbody>
                                {group.requests.map((member) => (
                                    <tr key={member.id} className="border-t">
                                        <td className="p-3">
                                            {member.company?.nama_perusahaan ||
                                                "-"}
                                        </td>

                                        <td className="p-3">
                                            {Number(
                                                member.quantity,
                                            ).toLocaleString()}{" "}
                                            {group.unit}
                                        </td>

                                        <td className="p-3">
                                            {new Date(
                                                member.created_at,
                                            ).toLocaleDateString()}
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    ) : (
                        <div className="text-gray-500">No members yet.</div>
                    )}
                </div>

                {/* RFQ Generation */}
                {canGenerateRFQ && (
                    <div className="bg-green-50 border border-green-200 rounded-2xl p-6">
                        <h3 className="font-bold text-green-800 mb-2">
                            MOQ Reached
                        </h3>

                        <p className="text-green-700 mb-4">
                            Demand target has been achieved. RFQ can now be
                            generated.
                        </p>

                        <button
                            type="button"
                            onClick={() => {
                                if (confirm("Generate RFQ from this group?")) {
                                    router.post(
                                        route(
                                            "collective-sourcing.groups.generate-rfq",
                                            group.id,
                                        ),
                                    );
                                }
                            }}
                            className="bg-blue-600 hover:bg-blue-500 text-white px-5 py-3 rounded-xl"
                        >
                            Generate RFQ
                        </button>
                    </div>
                )}
            </div>
        </AuthenticatedLayout>
    );
}
