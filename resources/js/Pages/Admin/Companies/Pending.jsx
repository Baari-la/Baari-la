import AdminLayout from "@/Layouts/AdminLayout";

import { Clock3, Eye, CheckCircle2, XCircle } from "lucide-react";

import { Link } from "@inertiajs/react";

import AdminStatsCard from "@/Components/Admin/AdminStatsCard";
import AdminSearchBar from "@/Components/Admin/AdminSearchBar";
import AdminEmptyState from "@/Components/Admin/AdminEmptyState";

export default function Pending({ companies = [] }) {
    return (
        <AdminLayout>
            <div className="space-y-8">
                {/* Header */}

                <div>
                    <p className="text-sm font-black uppercase tracking-[0.2em] text-amber-600">
                        ADMIN
                    </p>

                    <h1 className="mt-2 text-5xl font-black">
                        Pending Verification
                    </h1>

                    <p className="mt-3 text-slate-500">
                        Companies waiting for administrator approval.
                    </p>
                </div>

                {/* Stats */}

                <div className="grid gap-6 lg:grid-cols-3">
                    <AdminStatsCard
                        title="Pending"
                        value={companies.length}
                        icon={<Clock3 />}
                    />

                    <AdminStatsCard
                        title="Today"
                        value={
                            companies.filter(
                                (c) =>
                                    new Date(c.created_at).toDateString() ===
                                    new Date().toDateString(),
                            ).length
                        }
                        icon={<Clock3 />}
                    />

                    <AdminStatsCard
                        title="This Week"
                        value={companies.length}
                        icon={<Clock3 />}
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

                                <th className="p-5 text-left">Sector</th>

                                <th className="p-5 text-left">Country</th>

                                <th className="p-5 text-left">Membership</th>

                                <th className="p-5 text-left">Submitted</th>

                                <th className="p-5 text-left">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            {companies.length > 0 ? (
                                companies.map((company) => (
                                    <tr key={company.id} className="border-t">
                                        <td className="p-5 font-semibold">
                                            {company.nama_perusahaan}
                                        </td>

                                        <td className="p-5">
                                            {company.sektor}
                                        </td>

                                        <td className="p-5">
                                            {company.country}
                                        </td>

                                        <td className="p-5">
                                            {company.membership_type}
                                        </td>

                                        <td className="p-5">
                                            {new Date(
                                                company.created_at,
                                            ).toLocaleDateString()}
                                        </td>

                                        <td className="p-5">
                                            <div className="flex gap-2">
                                                <Link
                                                    href={route(
                                                        "admin.companies.show",
                                                        company.id,
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

                                                <button
                                                    className="
                                                            rounded-xl
                                                            bg-emerald-500
                                                            px-3
                                                            py-2
                                                            text-white
                                                        "
                                                >
                                                    <CheckCircle2 className="h-4 w-4" />
                                                </button>

                                                <button
                                                    className="
                                                            rounded-xl
                                                            bg-red-500
                                                            px-3
                                                            py-2
                                                            text-white
                                                        "
                                                >
                                                    <XCircle className="h-4 w-4" />
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td colSpan="6" className="p-10">
                                        <AdminEmptyState
                                            title="No Pending Companies"
                                            description="
                                                All companies
                                                have been
                                                reviewed.
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
