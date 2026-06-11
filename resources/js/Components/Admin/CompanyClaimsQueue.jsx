import { router } from "@inertiajs/react";

export default function CompanyClaimsQueue({ pendingClaims = [] }) {
    const approveClaim = (id) => {
        if (!confirm("Approve this company claim?")) {
            return;
        }

        router.post(route("admin.company-claims.approve", id));
    };

    const rejectClaim = (id) => {
        if (!confirm("Reject this company claim?")) {
            return;
        }

        router.post(route("admin.company-claims.reject", id));
    };

    return (
        <div className="bg-white/5 border border-white/10 rounded-[30px] p-6 mt-6">
            <div className="flex items-center justify-between mb-4">
                <h3 className="text-white font-black uppercase tracking-widest text-xs">
                    Company Claim Requests
                </h3>

                <span className="bg-yellow-500 text-black px-3 py-1 rounded-full text-[10px] font-black">
                    {pendingClaims.length}
                </span>
            </div>

            {pendingClaims.length === 0 ? (
                <div className="text-center py-8 text-gray-500 text-sm">
                    No pending company claims
                </div>
            ) : (
                <div className="overflow-x-auto">
                    <table className="w-full text-sm">
                        <thead>
                            <tr className="border-b border-white/10">
                                <th className="text-left py-3 text-gray-400">
                                    Company
                                </th>

                                <th className="text-left py-3 text-gray-400">
                                    User
                                </th>

                                <th className="text-left py-3 text-gray-400">
                                    Email
                                </th>

                                <th className="text-left py-3 text-gray-400">
                                    Submitted
                                </th>

                                <th className="text-right py-3 text-gray-400">
                                    Action
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            {pendingClaims.map((claim) => (
                                <tr
                                    key={claim.id}
                                    className="border-b border-white/5"
                                >
                                    <td className="py-3">
                                        {claim.company?.nama_perusahaan}
                                    </td>

                                    <td className="py-3">{claim.user?.name}</td>

                                    <td className="py-3">{claim.email}</td>

                                    <td className="py-3">
                                        {new Date(
                                            claim.submitted_at,
                                        ).toLocaleDateString()}
                                    </td>

                                    <td className="py-3 text-right">
                                        <button
                                            onClick={() =>
                                                approveClaim(claim.id)
                                            }
                                            className="bg-green-600 hover:bg-green-500 px-3 py-2 rounded-lg text-xs font-bold mr-2"
                                        >
                                            Approve
                                        </button>

                                        <button
                                            onClick={() =>
                                                rejectClaim(claim.id)
                                            }
                                            className="bg-red-600 hover:bg-red-500 px-3 py-2 rounded-lg text-xs font-bold"
                                        >
                                            Reject
                                        </button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            )}
        </div>
    );
}
