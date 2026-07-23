import { Link } from "@inertiajs/react";

import { Building2, ArrowRight } from "lucide-react";

import AdminTable from "@/Components/Admin/AdminTable";
import AdminEmptyState from "@/Components/Admin/AdminEmptyState";
import AdminStatusBadge from "@/Components/Admin/AdminStatusBadge";

export default function DashboardRecentCompanies({ companies = [] }) {
    if (companies.length === 0) {
        return (
            <div>
                <h2 className="text-2xl font-black">Recent Companies</h2>

                <p className="mt-2 text-slate-500">
                    Recently registered companies in DIGESTEX.
                </p>

                <div className="mt-6">
                    <AdminEmptyState
                        title="No Companies Yet"
                        description="
                            No companies have been
                            registered in DIGESTEX.
                        "
                        actionText="Add Company"
                        actionHref={route("companies.create")}
                        icon={<Building2 className="h-14 w-14" />}
                    />
                </div>
            </div>
        );
    }

    const data = companies.map((company) => ({
        id: company.id,

        company_name: company.nama_perusahaan,

        category: company.category ?? "-",

        membership: company.membership_type,

        verification: company.status_verifikasi,

        created_at: company.created_at,
    }));

    return (
        <div>
            {/* Header */}

            <div className="flex items-center justify-between">
                <div>
                    <h2 className="text-2xl font-black">Recent Companies</h2>

                    <p className="mt-2 text-slate-500">
                        Recently added companies across the DIGESTEX ecosystem.
                    </p>
                </div>

                <Link
                    href={route("companies.index")}
                    className="
                        inline-flex
                        items-center
                        gap-2
                        rounded-2xl
                        bg-slate-900
                        px-5
                        py-3
                        font-bold
                        text-white
                    "
                >
                    View All
                    <ArrowRight className="h-4 w-4" />
                </Link>
            </div>

            {/* Table */}

            <div className="mt-6 overflow-hidden rounded-3xl border bg-white shadow-sm">
                <table className="min-w-full">
                    <thead className="bg-slate-50">
                        <tr>
                            <th className="px-6 py-5 text-left text-sm font-bold">
                                Company
                            </th>

                            <th className="px-6 py-5 text-left text-sm font-bold">
                                Category
                            </th>

                            <th className="px-6 py-5 text-left text-sm font-bold">
                                Membership
                            </th>

                            <th className="px-6 py-5 text-left text-sm font-bold">
                                Verification
                            </th>

                            <th className="px-6 py-5 text-left text-sm font-bold">
                                Registered
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        {data.map((row) => (
                            <tr
                                key={row.id}
                                className="
                                    border-t
                                    hover:bg-slate-50
                                "
                            >
                                <td className="px-6 py-5 font-semibold">
                                    {row.company_name}
                                </td>

                                <td className="px-6 py-5">{row.category}</td>

                                <td className="px-6 py-5">
                                    <AdminStatusBadge status={row.membership} />
                                </td>

                                <td className="px-6 py-5">
                                    <AdminStatusBadge
                                        status={row.verification}
                                    />
                                </td>

                                <td className="px-6 py-5 text-slate-500">
                                    {new Date(
                                        row.created_at,
                                    ).toLocaleDateString("id-ID")}
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </div>
    );
}
