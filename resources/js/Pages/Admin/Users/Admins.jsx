import AdminLayout from "@/Layouts/AdminLayout";
import { Head } from "@inertiajs/react";

export default function Admins({ admins = [] }) {
    return (
        <AdminLayout>
            <Head title="Administrators" />

            <div className="space-y-8">
                {/* Header */}

                <div>
                    <h1 className="text-3xl font-black text-slate-900">
                        Administrators
                    </h1>

                    <p className="mt-2 text-slate-500">
                        Manage all administrator accounts across DIGESTEX.
                    </p>
                </div>

                {/* Stats */}

                <div className="grid gap-6 md:grid-cols-3">
                    <div className="rounded-3xl bg-white p-6 shadow-sm">
                        <div className="text-sm text-slate-500">
                            Total Admins
                        </div>

                        <div className="mt-2 text-3xl font-black">
                            {admins.length}
                        </div>
                    </div>

                    <div className="rounded-3xl bg-white p-6 shadow-sm">
                        <div className="text-sm text-slate-500">
                            Super Admin
                        </div>

                        <div className="mt-2 text-3xl font-black">
                            {
                                admins.filter((a) => a.role === "super_admin")
                                    .length
                            }
                        </div>
                    </div>

                    <div className="rounded-3xl bg-white p-6 shadow-sm">
                        <div className="text-sm text-slate-500">
                            Active Today
                        </div>

                        <div className="mt-2 text-3xl font-black">
                            {admins.filter((a) => a.last_login_at).length}
                        </div>
                    </div>
                </div>

                {/* Table */}

                <div className="overflow-hidden rounded-3xl bg-white shadow-sm">
                    <div className="border-b p-6">
                        <h2 className="text-xl font-bold">
                            Administrator List
                        </h2>
                    </div>

                    <table className="min-w-full">
                        <thead className="bg-slate-50">
                            <tr>
                                <th className="px-6 py-4 text-left">Name</th>

                                <th className="px-6 py-4 text-left">Email</th>

                                <th className="px-6 py-4 text-left">Role</th>

                                <th className="px-6 py-4 text-left">
                                    Last Login
                                </th>

                                <th className="px-6 py-4 text-left">Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            {admins.map((admin) => (
                                <tr key={admin.id} className="border-t">
                                    <td className="px-6 py-4 font-semibold">
                                        {admin.name}
                                    </td>

                                    <td className="px-6 py-4">{admin.email}</td>

                                    <td className="px-6 py-4">{admin.role}</td>

                                    <td className="px-6 py-4">
                                        {admin.last_login_at ?? "-"}
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
                                            Active
                                        </span>
                                    </td>
                                </tr>
                            ))}

                            {admins.length === 0 && (
                                <tr>
                                    <td
                                        colSpan="5"
                                        className="
                                            px-6
                                            py-12
                                            text-center
                                            text-slate-500
                                        "
                                    >
                                        No administrators found.
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
