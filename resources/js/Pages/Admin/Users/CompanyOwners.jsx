import AdminLayout from "@/Layouts/AdminLayout";
import { Head, Link } from "@inertiajs/react";

export default function CompanyOwners({ users = [] }) {
    return (
        <AdminLayout>
            <Head title="Company Owners" />

            <div className="space-y-8">
                {/* Header */}

                <div>
                    <h1 className="text-3xl font-black text-slate-900">
                        Company Owners
                    </h1>

                    <p className="mt-2 text-slate-500">
                        Manage users who have successfully claimed and verified
                        company ownership.
                    </p>
                </div>

                {/* Stats */}

                <div className="grid gap-6 md:grid-cols-4">
                    <div className="rounded-3xl bg-white p-6 shadow-sm">
                        <div className="text-sm text-slate-500">
                            Total Owners
                        </div>

                        <div className="mt-2 text-3xl font-black">
                            {users.length}
                        </div>
                    </div>

                    <div className="rounded-3xl bg-white p-6 shadow-sm">
                        <div className="text-sm text-slate-500">Verified</div>

                        <div className="mt-2 text-3xl font-black">
                            {
                                users.filter(
                                    (u) =>
                                        u.company?.status_verifikasi ===
                                        "verified",
                                ).length
                            }
                        </div>
                    </div>

                    <div className="rounded-3xl bg-white p-6 shadow-sm">
                        <div className="text-sm text-slate-500">
                            Premium Owners
                        </div>

                        <div className="mt-2 text-3xl font-black">
                            {users.filter((u) => u.is_premium).length}
                        </div>
                    </div>

                    <div className="rounded-3xl bg-white p-6 shadow-sm">
                        <div className="text-sm text-slate-500">
                            Export Companies
                        </div>

                        <div className="mt-2 text-3xl font-black">
                            {
                                users.filter((u) => u.company?.pasar_ekspor)
                                    .length
                            }
                        </div>
                    </div>
                </div>

                {/* Table */}

                <div className="overflow-hidden rounded-3xl bg-white shadow-sm">
                    <div className="border-b p-6">
                        <h2 className="text-xl font-bold">
                            Company Owner List
                        </h2>
                    </div>

                    <table className="min-w-full">
                        <thead className="bg-slate-50">
                            <tr>
                                <th className="px-6 py-4 text-left">Owner</th>

                                <th className="px-6 py-4 text-left">Company</th>

                                <th className="px-6 py-4 text-left">
                                    Membership
                                </th>

                                <th className="px-6 py-4 text-left">
                                    Verification
                                </th>

                                <th className="px-6 py-4 text-left">Premium</th>

                                <th className="px-6 py-4 text-left">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            {users.map((user) => (
                                <tr key={user.id} className="border-t">
                                    <td className="px-6 py-4">
                                        <div className="font-semibold">
                                            {user.name}
                                        </div>

                                        <div className="text-sm text-slate-500">
                                            {user.email}
                                        </div>
                                    </td>

                                    <td className="px-6 py-4">
                                        <div className="font-semibold">
                                            {user.company?.nama_perusahaan ??
                                                "-"}
                                        </div>

                                        <div className="text-sm text-slate-500">
                                            {user.company?.city ?? "-"}
                                        </div>
                                    </td>

                                    <td className="px-6 py-4">
                                        {user.company?.membership_type ?? "-"}
                                    </td>

                                    <td className="px-6 py-4">
                                        <span
                                            className="
                                                rounded-full
                                                bg-emerald-100
                                                px-3
                                                py-1
                                                text-xs
                                                font-bold
                                                text-emerald-700
                                            "
                                        >
                                            {user.company?.status_verifikasi ??
                                                "-"}
                                        </span>
                                    </td>

                                    <td className="px-6 py-4">
                                        {user.is_premium ? "YES" : "NO"}
                                    </td>

                                    <td className="px-6 py-4">
                                        {user.company && (
                                            <Link
                                                href={route(
                                                    "admin.companies.show",
                                                    user.company.id,
                                                )}
                                                className="
                                                    rounded-xl
                                                    bg-slate-900
                                                    px-4
                                                    py-2
                                                    text-sm
                                                    font-semibold
                                                    text-white
                                                "
                                            >
                                                View Company
                                            </Link>
                                        )}
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
                                        No company owners found.
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
