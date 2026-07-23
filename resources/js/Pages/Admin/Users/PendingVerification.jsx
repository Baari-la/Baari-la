import AdminLayout from "@/Layouts/AdminLayout";
import { Head, Link } from "@inertiajs/react";

export default function PendingVerification({ users = [] }) {
    return (
        <AdminLayout>
            <Head title="Pending Verification" />

            <div className="space-y-8">
                {/* Header */}

                <div>
                    <h1 className="text-3xl font-black text-slate-900">
                        Pending Verification
                    </h1>

                    <p className="mt-2 text-slate-500">
                        Review users awaiting email, company, or membership
                        verification.
                    </p>
                </div>

                {/* Summary */}

                <div className="grid gap-6 md:grid-cols-4">
                    <div className="rounded-3xl bg-white p-6 shadow-sm">
                        <div className="text-sm text-slate-500">
                            Total Pending
                        </div>

                        <div className="mt-2 text-3xl font-black">
                            {users.length}
                        </div>
                    </div>

                    <div className="rounded-3xl bg-white p-6 shadow-sm">
                        <div className="text-sm text-slate-500">
                            Email Pending
                        </div>

                        <div className="mt-2 text-3xl font-black">
                            {users.filter((u) => !u.email_verified_at).length}
                        </div>
                    </div>

                    <div className="rounded-3xl bg-white p-6 shadow-sm">
                        <div className="text-sm text-slate-500">
                            Company Pending
                        </div>

                        <div className="mt-2 text-3xl font-black">
                            {
                                users.filter(
                                    (u) =>
                                        u.company &&
                                        u.company.status_verifikasi ===
                                            "pending",
                                ).length
                            }
                        </div>
                    </div>

                    <div className="rounded-3xl bg-white p-6 shadow-sm">
                        <div className="text-sm text-slate-500">
                            Premium Pending
                        </div>

                        <div className="mt-2 text-3xl font-black">
                            {
                                users.filter(
                                    (u) => u.premium_status === "pending",
                                ).length
                            }
                        </div>
                    </div>
                </div>

                {/* Table */}

                <div className="overflow-hidden rounded-3xl bg-white shadow-sm">
                    <div className="border-b p-6">
                        <h2 className="text-xl font-bold">
                            Verification Queue
                        </h2>
                    </div>

                    <table className="min-w-full">
                        <thead className="bg-slate-50">
                            <tr>
                                <th className="px-6 py-4 text-left">Name</th>

                                <th className="px-6 py-4 text-left">Email</th>

                                <th className="px-6 py-4 text-left">Company</th>

                                <th className="px-6 py-4 text-left">
                                    Verification Type
                                </th>

                                <th className="px-6 py-4 text-left">
                                    Submitted
                                </th>

                                <th className="px-6 py-4 text-left">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            {users.map((user) => (
                                <tr key={user.id} className="border-t">
                                    <td className="px-6 py-4 font-semibold">
                                        {user.name}
                                    </td>

                                    <td className="px-6 py-4">{user.email}</td>

                                    <td className="px-6 py-4">
                                        {user.company?.nama_perusahaan ?? "-"}
                                    </td>

                                    <td className="px-6 py-4">
                                        {!user.email_verified_at
                                            ? "Email Verification"
                                            : user.company
                                                    ?.status_verifikasi ===
                                                "pending"
                                              ? "Company Verification"
                                              : user.premium_status ===
                                                  "pending"
                                                ? "Premium Verification"
                                                : "-"}
                                    </td>

                                    <td className="px-6 py-4">
                                        {user.created_at}
                                    </td>

                                    <td className="px-6 py-4">
                                        <div className="flex gap-2">
                                            <button
                                                className="
                                                    rounded-xl
                                                    bg-emerald-600
                                                    px-4
                                                    py-2
                                                    text-sm
                                                    font-semibold
                                                    text-white
                                                "
                                            >
                                                Approve
                                            </button>

                                            <button
                                                className="
                                                    rounded-xl
                                                    bg-red-600
                                                    px-4
                                                    py-2
                                                    text-sm
                                                    font-semibold
                                                    text-white
                                                "
                                            >
                                                Reject
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            ))}

                            {users.length === 0 && (
                                <tr>
                                    <td
                                        colSpan="6"
                                        className="
                                            px-6
                                            py-12
                                            text-center
                                            text-slate-500
                                        "
                                    >
                                        No pending verifications.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
        </AdminLayout>
    );
}
