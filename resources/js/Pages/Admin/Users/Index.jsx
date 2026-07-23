import AdminLayout from "@/Layouts/AdminLayout";
import { Head, Link } from "@inertiajs/react";

export default function Index({ users = [], stats = {} }) {
    return (
        <AdminLayout>
            <Head title="Users" />

            <div className="space-y-8">
                {/* Header */}

                <div>
                    <h1 className="text-3xl font-black text-slate-900">
                        User Management
                    </h1>

                    <p className="mt-2 text-slate-500">
                        Manage all DIGESTEX users across the ecosystem.
                    </p>
                </div>

                {/* Stats */}

                <div className="grid gap-6 md:grid-cols-4">
                    <div className="rounded-3xl bg-white p-6 shadow-sm">
                        <div className="text-sm text-slate-500">
                            Total Users
                        </div>

                        <div className="mt-2 text-3xl font-black">
                            {stats.total_users ?? 0}
                        </div>
                    </div>

                    <div className="rounded-3xl bg-white p-6 shadow-sm">
                        <div className="text-sm text-slate-500">
                            Premium Users
                        </div>

                        <div className="mt-2 text-3xl font-black">
                            {stats.premium_users ?? 0}
                        </div>
                    </div>

                    <div className="rounded-3xl bg-white p-6 shadow-sm">
                        <div className="text-sm text-slate-500">
                            Company Owners
                        </div>

                        <div className="mt-2 text-3xl font-black">
                            {stats.company_owners ?? 0}
                        </div>
                    </div>

                    <div className="rounded-3xl bg-white p-6 shadow-sm">
                        <div className="text-sm text-slate-500">
                            Administrators
                        </div>

                        <div className="mt-2 text-3xl font-black">
                            {stats.admins ?? 0}
                        </div>
                    </div>
                </div>

                {/* Quick Menu */}

                <div className="grid gap-4 md:grid-cols-5">
                    <Link
                        href={route("admin.users.admins")}
                        className="rounded-2xl bg-white p-5 shadow-sm hover:shadow-md"
                    >
                        <div className="font-bold">Admins</div>

                        <div className="mt-1 text-sm text-slate-500">
                            Manage administrators
                        </div>
                    </Link>

                    <Link
                        href={route("admin.users.premium")}
                        className="rounded-2xl bg-white p-5 shadow-sm hover:shadow-md"
                    >
                        <div className="font-bold">Premium Users</div>

                        <div className="mt-1 text-sm text-slate-500">
                            Paid memberships
                        </div>
                    </Link>

                    <Link
                        href={route("admin.users.company-owners")}
                        className="rounded-2xl bg-white p-5 shadow-sm hover:shadow-md"
                    >
                        <div className="font-bold">Company Owners</div>

                        <div className="mt-1 text-sm text-slate-500">
                            Claimed companies
                        </div>
                    </Link>

                    <Link
                        href={route("admin.users.pending-verification")}
                        className="rounded-2xl bg-white p-5 shadow-sm hover:shadow-md"
                    >
                        <div className="font-bold">Pending Verification</div>

                        <div className="mt-1 text-sm text-slate-500">
                            Awaiting approval
                        </div>
                    </Link>

                    <Link
                        href={route("admin.users.activity-logs")}
                        className="rounded-2xl bg-white p-5 shadow-sm hover:shadow-md"
                    >
                        <div className="font-bold">Activity Logs</div>

                        <div className="mt-1 text-sm text-slate-500">
                            User activities
                        </div>
                    </Link>
                </div>

                {/* Users Table */}

                <div className="overflow-hidden rounded-3xl bg-white shadow-sm">
                    <div className="border-b p-6">
                        <h2 className="text-xl font-bold">Latest Users</h2>
                    </div>

                    <table className="min-w-full">
                        <thead className="bg-slate-50">
                            <tr>
                                <th className="px-6 py-4 text-left">Name</th>

                                <th className="px-6 py-4 text-left">Email</th>

                                <th className="px-6 py-4 text-left">Company</th>

                                <th className="px-6 py-4 text-left">Role</th>

                                <th className="px-6 py-4 text-left">Status</th>
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

                                    <td className="px-6 py-4">{user.role}</td>

                                    <td className="px-6 py-4">
                                        {user.email_verified_at
                                            ? "Verified"
                                            : "Pending"}
                                    </td>
                                </tr>
                            ))}

                            {users.length === 0 && (
                                <tr>
                                    <td
                                        colSpan="5"
                                        className="
                                            px-6
                                            py-10
                                            text-center
                                            text-slate-500
                                        "
                                    >
                                        No users found.
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
