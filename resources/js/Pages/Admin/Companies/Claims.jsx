import AdminLayout from "@/Layouts/AdminLayout";

import { Building2, UserCheck, CheckCircle2, XCircle, Eye } from "lucide-react";

import { Link } from "@inertiajs/react";

import AdminStatsCard from "@/Components/Admin/AdminStatsCard";
import AdminStatusBadge from "@/Components/Admin/AdminStatusBadge";
import AdminSearchBar from "@/Components/Admin/AdminSearchBar";
import AdminEmptyState from "@/Components/Admin/AdminEmptyState";

export default function Claims({ claims = [] }) {
    return (
        <AdminLayout>
            <div className="space-y-8">
                {/* Header */}

                <div>
                    <p className="text-sm font-black uppercase tracking-[0.2em] text-indigo-600">
                        ADMIN
                    </p>

                    <h1 className="mt-2 text-5xl font-black">Company Claims</h1>

                    <p className="mt-3 text-slate-500">
                        Review ownership requests submitted by users.
                    </p>
                </div>

                {/* Stats */}

                <div className="grid gap-6 lg:grid-cols-4">
                    <AdminStatsCard
                        title="Total"
                        value={claims.length}
                        icon={<Building2 />}
                    />

                    <AdminStatsCard
                        title="Pending"
                        value={
                            claims.filter((c) => c.status === "pending").length
                        }
                        icon={<UserCheck />}
                    />

                    <AdminStatsCard
                        title="Approved"
                        value={
                            claims.filter((c) => c.status === "approved").length
                        }
                        icon={<CheckCircle2 />}
                    />

                    <AdminStatsCard
                        title="Rejected"
                        value={
                            claims.filter((c) => c.status === "rejected").length
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

                                <th className="p-5 text-left">Email</th>

                                <th className="p-5 text-left">Status</th>

                                <th className="p-5 text-left">Submitted</th>

                                <th className="p-5 text-left">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            {claims.length > 0 ? (
                                claims.map((claim) => (
                                    <tr key={claim.id} className="border-t">
                                        <td className="p-5 font-semibold">
                                            {claim.company?.nama_perusahaan}
                                        </td>

                                        <td className="p-5">
                                            {claim.user?.name}
                                        </td>

                                        <td className="p-5">
                                            {claim.user?.email}
                                        </td>

                                        <td className="p-5">
                                            <AdminStatusBadge
                                                status={claim.status}
                                            />
                                        </td>

                                        <td className="p-5">
                                            {new Date(
                                                claim.created_at,
                                            ).toLocaleDateString()}
                                        </td>

                                        <td className="p-5">
                                            <div className="flex gap-2">
                                                <Link
                                                    href={route(
                                                        "admin.companies.show",
                                                        claim.company_id,
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

                                                {claim.status === "pending" && (
                                                    <>
                                                        <Link
                                                            method="post"
                                                            href={route(
                                                                "admin.claims.approve",
                                                                claim.id,
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
                                                                "admin.claims.reject",
                                                                claim.id,
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
                                    <td colSpan="6" className="p-10">
                                        <AdminEmptyState
                                            title="No Claims Found"
                                            description="
                                                No company
                                                ownership
                                                requests have
                                                been submitted.
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
