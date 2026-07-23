import AdminLayout from "@/Layouts/AdminLayout";

import { Building2, ShieldCheck, Clock3, Crown, Eye } from "lucide-react";

import { Link } from "@inertiajs/react";

import AdminStatsCard from "@/Components/Admin/AdminStatsCard";
import AdminSearchBar from "@/Components/Admin/AdminSearchBar";
import AdminStatusBadge from "@/Components/Admin/AdminStatusBadge";
import AdminEmptyState from "@/Components/Admin/AdminEmptyState";

export default function Index({ companies = [], stats = {} }) {
    return (
        <AdminLayout>
            <div className="space-y-8">
                {/* Header */}

                <div>
                    <p className="text-sm font-black uppercase tracking-[0.2em] text-emerald-600">
                        ADMIN
                    </p>

                    <h1 className="mt-2 text-5xl font-black">Companies</h1>

                    <p className="mt-3 text-slate-500">
                        Manage all companies across the DIGESTEX ecosystem.
                    </p>
                </div>

                {/* Stats */}

                <div className="grid gap-6 lg:grid-cols-4">
                    <AdminStatsCard
                        title="Total"
                        value={stats.total ?? 0}
                        icon={<Building2 />}
                    />

                    <AdminStatsCard
                        title="Verified"
                        value={stats.verified ?? 0}
                        icon={<ShieldCheck />}
                    />

                    <AdminStatsCard
                        title="Pending"
                        value={stats.pending ?? 0}
                        icon={<Clock3 />}
                    />

                    <AdminStatsCard
                        title="Gold"
                        value={stats.gold ?? 0}
                        icon={<Crown />}
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

                                <th className="p-5 text-left">Status</th>

                                <th className="p-5 text-left">Verified</th>

                                <th className="p-5 text-left">Action</th>
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
                                            <AdminStatusBadge
                                                status={
                                                    company.status_verifikasi
                                                }
                                            />
                                        </td>

                                        <td className="p-5">
                                            {company.last_verified_at ?? "-"}
                                        </td>

                                        <td className="p-5">
                                            <Link
                                                href={route(
                                                    "admin.companies.show",
                                                    company.id,
                                                )}
                                                className="
                                                        inline-flex
                                                        items-center
                                                        gap-2
                                                        rounded-xl
                                                        bg-slate-900
                                                        px-4
                                                        py-2
                                                        text-white
                                                    "
                                            >
                                                <Eye className="h-4 w-4" />
                                                View
                                            </Link>
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td colSpan="7" className="p-10">
                                        <AdminEmptyState
                                            title="No Companies Found"
                                            description="
                                                No companies
                                                available.
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
