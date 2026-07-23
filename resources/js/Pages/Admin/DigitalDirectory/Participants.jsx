import AdminLayout from "@/Layouts/AdminLayout";

import AdminStatsCard from "@/Components/Admin/AdminStatsCard";
import AdminStatusBadge from "@/Components/Admin/AdminStatusBadge";
import AdminEmptyState from "@/Components/Admin/AdminEmptyState";

import { Link } from "@inertiajs/react";

import { Users, CreditCard, CheckCircle2, Globe, Eye } from "lucide-react";

export default function Participants({ participants = [], stats = {} }) {
    return (
        <AdminLayout>
            <div className="space-y-8">
                {/* Header */}

                <div>
                    <p className="text-sm font-black uppercase tracking-[0.3em] text-emerald-600">
                        DIGITAL DIRECTORY
                    </p>

                    <h1 className="mt-2 text-5xl font-black">Participants</h1>

                    <p className="mt-3 max-w-3xl text-slate-500">
                        Manage participants of the DIGESTEX Digital Directory &
                        Visibility Program.
                    </p>
                </div>

                {/* Stats */}

                <div className="grid gap-6 lg:grid-cols-5">
                    <AdminStatsCard
                        title="Participants"
                        value={stats.total ?? 0}
                        subtitle="All Companies"
                        icon={<Users />}
                    />

                    <AdminStatsCard
                        title="Pending"
                        value={stats.pending ?? 0}
                        subtitle="Awaiting Payment"
                        icon={<CreditCard />}
                    />

                    <AdminStatsCard
                        title="Verified"
                        value={stats.verified ?? 0}
                        subtitle="Payment Verified"
                        icon={<CheckCircle2 />}
                    />

                    <AdminStatsCard
                        title="Active"
                        value={stats.active ?? 0}
                        subtitle="Activated Companies"
                        icon={<Globe />}
                    />

                    <AdminStatsCard
                        title="Revenue"
                        value={`Rp ${(stats.revenue ?? 0).toLocaleString(
                            "id-ID",
                        )}`}
                        subtitle="Verified Payments"
                        icon={<CreditCard />}
                    />
                </div>

                {/* Table */}

                <div className="overflow-hidden rounded-3xl border bg-white shadow-sm">
                    <div className="border-b p-6">
                        <h2 className="text-2xl font-black">
                            Participant List
                        </h2>

                        <p className="mt-2 text-slate-500">
                            All registered companies participating in the
                            Digital Directory Program.
                        </p>
                    </div>

                    {participants.length === 0 ? (
                        <AdminEmptyState
                            title="No Participants"
                            description="
                                There are currently no participants
                                registered.
                            "
                        />
                    ) : (
                        <div className="overflow-x-auto">
                            <table className="min-w-full">
                                <thead className="bg-slate-50">
                                    <tr>
                                        <th className="px-6 py-4 text-left text-sm font-bold">
                                            Company
                                        </th>

                                        <th className="px-6 py-4 text-left text-sm font-bold">
                                            Package
                                        </th>

                                        <th className="px-6 py-4 text-left text-sm font-bold">
                                            Country
                                        </th>

                                        <th className="px-6 py-4 text-left text-sm font-bold">
                                            Payment
                                        </th>

                                        <th className="px-6 py-4 text-left text-sm font-bold">
                                            Activation
                                        </th>

                                        <th className="px-6 py-4 text-left text-sm font-bold">
                                            Registered
                                        </th>

                                        <th className="px-6 py-4 text-right text-sm font-bold">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    {participants.map((participant) => (
                                        <tr
                                            key={participant.id}
                                            className="border-t"
                                        >
                                            <td className="px-6 py-5">
                                                <div className="font-bold">
                                                    {participant.company_name}
                                                </div>

                                                <div className="text-sm text-slate-500">
                                                    {participant.pic_name}
                                                </div>
                                            </td>

                                            <td className="px-6 py-5">
                                                {participant.package}
                                            </td>

                                            <td className="px-6 py-5">
                                                {participant.country}
                                            </td>

                                            <td className="px-6 py-5">
                                                <AdminStatusBadge
                                                    status={
                                                        participant.payment_status
                                                    }
                                                />
                                            </td>

                                            <td className="px-6 py-5">
                                                <AdminStatusBadge
                                                    status={
                                                        participant.activation_status
                                                    }
                                                />
                                            </td>

                                            <td className="px-6 py-5 text-sm text-slate-500">
                                                {new Date(
                                                    participant.created_at,
                                                ).toLocaleDateString()}
                                            </td>

                                            <td className="px-6 py-5 text-right">
                                                <Link
                                                    href={route(
                                                        "admin.digital-directory.show",
                                                        participant.id,
                                                    )}
                                                    className="
                                                            inline-flex
                                                            items-center
                                                            gap-2
                                                            rounded-2xl
                                                            bg-slate-900
                                                            px-4
                                                            py-2
                                                            text-sm
                                                            font-bold
                                                            text-white
                                                        "
                                                >
                                                    <Eye className="h-4 w-4" />
                                                    View
                                                </Link>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    )}
                </div>
            </div>
        </AdminLayout>
    );
}
