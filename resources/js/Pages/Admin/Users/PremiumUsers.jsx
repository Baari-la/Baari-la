import AdminLayout from "@/Layouts/AdminLayout";
import { Head } from "@inertiajs/react";

export default function PremiumUsers({ users = [] }) {
    return (
        <AdminLayout>
            <Head title="Premium Users" />

            <div className="space-y-8">
                {/* Header */}

                <div>
                    <h1 className="text-3xl font-black text-slate-900">
                        Premium Users
                    </h1>

                    <p className="mt-2 text-slate-500">
                        Manage all premium subscriptions across the DIGESTEX
                        ecosystem.
                    </p>
                </div>

                {/* Stats */}

                <div className="grid gap-6 md:grid-cols-4">
                    <div className="rounded-3xl bg-white p-6 shadow-sm">
                        <div className="text-sm text-slate-500">
                            Total Premium
                        </div>

                        <div className="mt-2 text-3xl font-black">
                            {users.length}
                        </div>
                    </div>

                    <div className="rounded-3xl bg-white p-6 shadow-sm">
                        <div className="text-sm text-slate-500">Active</div>

                        <div className="mt-2 text-3xl font-black">
                            {users.filter((u) => u.is_premium).length}
                        </div>
                    </div>

                    <div className="rounded-3xl bg-white p-6 shadow-sm">
                        <div className="text-sm text-slate-500">
                            Company Owners
                        </div>

                        <div className="mt-2 text-3xl font-black">
                            {users.filter((u) => u.company_id).length}
                        </div>
                    </div>

                    <div className="rounded-3xl bg-white p-6 shadow-sm">
                        <div className="text-sm text-slate-500">
                            Expiring Soon
                        </div>

                        <div className="mt-2 text-3xl font-black">
                            {users.filter((u) => u.premium_expires_at).length}
                        </div>
                    </div>
                </div>

                {/* Table */}

                <div className="overflow-hidden rounded-3xl bg-white shadow-sm">
                    <div className="border-b p-6">
                        <h2 className="text-xl font-bold">
                            Premium Membership List
                        </h2>
                    </div>

                    <table className="min-w-full">
                        <thead className="bg-slate-50">
                            <tr>
                                <th className="px-6 py-4 text-left">Name</th>

                                <th className="px-6 py-4 text-left">Email</th>

                                <th className="px-6 py-4 text-left">Company</th>

                                <th className="px-6 py-4 text-left">Package</th>

                                <th className="px-6 py-4 text-left">
                                    Expired At
                                </th>

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

                                    <td className="px-6 py-4">
                                        {user.premium_plan ?? "Premium"}
                                    </td>

                                    <td className="px-6 py-4">
                                        {user.premium_expires_at ?? "-"}
                                    </td>

                                    <td className="px-6 py-4">
                                        <span
                                            className={`
                                                rounded-full
                                                px-3
                                                py-1
                                                text-xs
                                                font-bold

                                                ${
                                                    user.is_premium
                                                        ? "bg-emerald-100 text-emerald-700"
                                                        : "bg-red-100 text-red-700"
                                                }
                                            `}
                                        >
                                            {user.is_premium
                                                ? "Active"
                                                : "Expired"}
                                        </span>
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
                                        No premium users found.
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
