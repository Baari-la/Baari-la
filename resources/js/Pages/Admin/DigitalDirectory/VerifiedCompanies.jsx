import AdminLayout from "@/Layouts/AdminLayout";

import AdminStatusBadge from "@/Components/Admin/AdminStatusBadge";
import AdminEmptyState from "@/Components/Admin/AdminEmptyState";

import { Link } from "@inertiajs/react";

import { CheckCircle2, Globe, Eye, Zap, Calendar } from "lucide-react";

export default function VerifiedCompanies({ participants = [] }) {
    return (
        <AdminLayout>
            <div className="space-y-8">
                {/* Header */}

                <div>
                    <p className="text-sm font-black uppercase tracking-[0.3em] text-emerald-600">
                        DIGITAL DIRECTORY
                    </p>

                    <h1 className="mt-2 text-5xl font-black">
                        Verified Companies
                    </h1>

                    <p className="mt-3 text-slate-500">
                        Companies with verified payments that are ready for
                        activation.
                    </p>
                </div>

                {/* Table */}

                <div className="overflow-hidden rounded-3xl border bg-white shadow-sm">
                    <div className="border-b p-6">
                        <h2 className="text-2xl font-black">
                            Verified Participant List
                        </h2>
                    </div>

                    {participants.length === 0 ? (
                        <AdminEmptyState
                            title="No Verified Companies"
                            description="
                                There are currently no verified
                                participants.
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
                                            Invoice
                                        </th>

                                        <th className="px-6 py-4 text-left text-sm font-bold">
                                            Amount
                                        </th>

                                        <th className="px-6 py-4 text-left text-sm font-bold">
                                            Verified At
                                        </th>

                                        <th className="px-6 py-4 text-left text-sm font-bold">
                                            Activation
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
                                                {participant.invoice_number}
                                            </td>

                                            <td className="px-6 py-5 font-bold">
                                                Rp{" "}
                                                {(
                                                    participant.amount ?? 0
                                                ).toLocaleString("id-ID")}
                                            </td>

                                            <td className="px-6 py-5">
                                                <div className="inline-flex items-center gap-2 text-sm">
                                                    <Calendar className="h-4 w-4" />

                                                    {participant.payment_verified_at
                                                        ? new Date(
                                                              participant.payment_verified_at,
                                                          ).toLocaleDateString()
                                                        : "-"}
                                                </div>
                                            </td>

                                            <td className="px-6 py-5">
                                                <AdminStatusBadge
                                                    status={
                                                        participant.activation_status
                                                    }
                                                />
                                            </td>

                                            <td className="px-6 py-5">
                                                <div className="flex justify-end gap-2">
                                                    <Link
                                                        href={route(
                                                            "admin.digital-directory.show",
                                                            participant.id,
                                                        )}
                                                        className="
                                                                rounded-xl
                                                                border
                                                                p-2
                                                            "
                                                    >
                                                        <Eye className="h-4 w-4" />
                                                    </Link>

                                                    {participant.activation_status !==
                                                        "active" && (
                                                        <Link
                                                            method="post"
                                                            href={route(
                                                                "admin.digital-directory.activate",
                                                                participant.id,
                                                            )}
                                                            className="
                                                                    inline-flex
                                                                    items-center
                                                                    gap-2
                                                                    rounded-xl
                                                                    bg-emerald-500
                                                                    px-4
                                                                    py-2
                                                                    text-sm
                                                                    font-bold
                                                                    text-white
                                                                "
                                                        >
                                                            <Zap className="h-4 w-4" />
                                                            Activate
                                                        </Link>
                                                    )}

                                                    {participant.activation_status ===
                                                        "active" && (
                                                        <div
                                                            className="
                                                                    inline-flex
                                                                    items-center
                                                                    gap-2
                                                                    rounded-xl
                                                                    bg-emerald-100
                                                                    px-4
                                                                    py-2
                                                                    text-sm
                                                                    font-bold
                                                                    text-emerald-700
                                                                "
                                                        >
                                                            <CheckCircle2 className="h-4 w-4" />
                                                            Active
                                                        </div>
                                                    )}
                                                </div>
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
