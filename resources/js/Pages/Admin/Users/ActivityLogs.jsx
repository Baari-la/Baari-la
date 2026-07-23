import AdminLayout from "@/Layouts/AdminLayout";
import { Head } from "@inertiajs/react";

export default function ActivityLogs({ logs = [] }) {
    return (
        <AdminLayout>
            <Head title="Activity Logs" />

            <div className="space-y-8">
                {/* Header */}

                <div>
                    <h1 className="text-3xl font-black text-slate-900">
                        Activity Logs
                    </h1>

                    <p className="mt-2 text-slate-500">
                        Monitor user activities across the DIGESTEX ecosystem
                        including approvals, claims, payments, and
                        administrative actions.
                    </p>
                </div>

                {/* Stats */}

                <div className="grid gap-6 md:grid-cols-4">
                    <div className="rounded-3xl bg-white p-6 shadow-sm">
                        <div className="text-sm text-slate-500">Total Logs</div>

                        <div className="mt-2 text-3xl font-black">
                            {logs.length}
                        </div>
                    </div>

                    <div className="rounded-3xl bg-white p-6 shadow-sm">
                        <div className="text-sm text-slate-500">Approvals</div>

                        <div className="mt-2 text-3xl font-black">
                            {
                                logs.filter((log) => log.action === "approved")
                                    .length
                            }
                        </div>
                    </div>

                    <div className="rounded-3xl bg-white p-6 shadow-sm">
                        <div className="text-sm text-slate-500">Rejections</div>

                        <div className="mt-2 text-3xl font-black">
                            {
                                logs.filter((log) => log.action === "rejected")
                                    .length
                            }
                        </div>
                    </div>

                    <div className="rounded-3xl bg-white p-6 shadow-sm">
                        <div className="text-sm text-slate-500">Payments</div>

                        <div className="mt-2 text-3xl font-black">
                            {
                                logs.filter((log) => log.action === "payment")
                                    .length
                            }
                        </div>
                    </div>
                </div>

                {/* Table */}

                <div className="overflow-hidden rounded-3xl bg-white shadow-sm">
                    <div className="border-b p-6">
                        <h2 className="text-xl font-bold">Recent Activities</h2>
                    </div>

                    <table className="min-w-full">
                        <thead className="bg-slate-50">
                            <tr>
                                <th className="px-6 py-4 text-left">User</th>

                                <th className="px-6 py-4 text-left">Action</th>

                                <th className="px-6 py-4 text-left">Company</th>

                                <th className="px-6 py-4 text-left">Details</th>

                                <th className="px-6 py-4 text-left">Date</th>
                            </tr>
                        </thead>

                        <tbody>
                            {logs.map((log) => (
                                <tr key={log.id} className="border-t">
                                    <td className="px-6 py-4">
                                        <div className="font-semibold">
                                            {log.user?.name ?? "System"}
                                        </div>

                                        <div className="text-sm text-slate-500">
                                            {log.user?.email ?? "-"}
                                        </div>
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
                                                    log.action === "approved"
                                                        ? "bg-emerald-100 text-emerald-700"
                                                        : log.action ===
                                                            "rejected"
                                                          ? "bg-red-100 text-red-700"
                                                          : "bg-blue-100 text-blue-700"
                                                }
                                            `}
                                        >
                                            {log.action}
                                        </span>
                                    </td>

                                    <td className="px-6 py-4">
                                        {log.company?.nama_perusahaan ?? "-"}
                                    </td>

                                    <td className="px-6 py-4">{log.details}</td>

                                    <td className="px-6 py-4">
                                        {log.created_at}
                                    </td>
                                </tr>
                            ))}

                            {logs.length === 0 && (
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
                                        No activity logs found.
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
