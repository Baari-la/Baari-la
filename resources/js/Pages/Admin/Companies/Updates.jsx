import AdminLayout from "@/Layouts/AdminLayout";

import { RefreshCw, Eye, CheckCircle2, XCircle } from "lucide-react";

import { Link } from "@inertiajs/react";

import AdminStatsCard from "@/Components/Admin/AdminStatsCard";
import AdminStatusBadge from "@/Components/Admin/AdminStatusBadge";
import AdminSearchBar from "@/Components/Admin/AdminSearchBar";
import AdminEmptyState from "@/Components/Admin/AdminEmptyState";

export default function Updates({ updates = [] }) {
    return (
        <AdminLayout>
            <div className="space-y-8">
                {/* Header */}

                <div>
                    <p className="text-sm font-black uppercase tracking-[0.2em] text-sky-600">
                        ADMIN
                    </p>

                    <h1 className="mt-2 text-5xl font-black">
                        Company Updates
                    </h1>

                    <p className="mt-3 text-slate-500">
                        Review and approve company profile updates.
                    </p>
                </div>

                {/* Stats */}

                <div className="grid gap-6 lg:grid-cols-3">
                    <AdminStatsCard
                        title="Pending"
                        value={
                            updates.filter((u) => u.status === "pending").length
                        }
                        icon={<RefreshCw />}
                    />

                    <AdminStatsCard
                        title="Approved"
                        value={
                            updates.filter((u) => u.status === "approved")
                                .length
                        }
                        icon={<CheckCircle2 />}
                    />

                    <AdminStatsCard
                        title="Rejected"
                        value={
                            updates.filter((u) => u.status === "rejected")
                                .length
                        }
                        icon={<XCircle />}
                    />
                </div>

                {/* Search */}

                <AdminSearchBar />

                {/* Table */}

                <div className="overflow-hidden rounded-3xl border bg-white shadow-sm">
                    <table className="min-w-full">
                        <thead className="bg-slate-50">
                            <tr>
                                <th className="p-5 text-left">Company</th>

                                <th className="p-5 text-left">Requested By</th>

                                <th className="p-5 text-left">Submitted</th>

                                <th className="p-5 text-left">Status</th>

                                <th className="p-5 text-left">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            {updates.length > 0 ? (
                                updates.map((update) => (
                                    <tr key={update.id} className="border-t">
                                        <td className="p-5 font-semibold">
                                            {update.company?.nama_perusahaan}
                                        </td>

                                        <td className="p-5">
                                            {update.user?.name}
                                        </td>

                                        <td className="p-5">
                                            {new Date(
                                                update.created_at,
                                            ).toLocaleDateString()}
                                        </td>

                                        <td className="p-5">
                                            <AdminStatusBadge
                                                status={update.status}
                                            />
                                        </td>

                                        <td className="p-5">
                                            <div className="flex gap-2">
                                                <Link
                                                    href={route(
                                                        "admin.companies.show",
                                                        update.company_id,
                                                    )}
                                                    className="
                                                            rounded-xl
                                                            bg-slate-900
                                                            px-3
                                                            py-2
                                                            text-white
                                                        "
                                                >
                                                    <Eye className="h-4 w-4" />
                                                </Link>

                                                {update.status ===
                                                    "pending" && (
                                                    <>
                                                        <Link
                                                            method="post"
                                                            href={route(
                                                                "admin.companies.updates.approve",
                                                                update.id,
                                                            )}
                                                            className="
                                                                    rounded-xl
                                                                    bg-emerald-500
                                                                    px-3
                                                                    py-2
                                                                    text-white
                                                                "
                                                        >
                                                            <CheckCircle2 className="h-4 w-4" />
                                                        </Link>

                                                        <Link
                                                            method="post"
                                                            href={route(
                                                                "admin.companies.updates.reject",
                                                                update.id,
                                                            )}
                                                            className="
                                                                    rounded-xl
                                                                    bg-red-500
                                                                    px-3
                                                                    py-2
                                                                    text-white
                                                                "
                                                        >
                                                            <XCircle className="h-4 w-4" />
                                                        </Link>
                                                    </>
                                                )}
                                            </div>
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td colSpan="5" className="p-10">
                                        <AdminEmptyState
                                            title="No Updates Found"
                                            description="
                                                There are no
                                                company update
                                                requests.
                                            "
                                        />
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
