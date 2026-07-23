import AdminLayout from "@/Layouts/AdminLayout";

import AdminStatusBadge from "@/Components/Admin/AdminStatusBadge";
import AdminEmptyState from "@/Components/Admin/AdminEmptyState";

import { Link } from "@inertiajs/react";

import { CreditCard, CheckCircle2, XCircle, Receipt, Eye } from "lucide-react";

export default function PendingPayments({ participants = [] }) {
    return (
        <AdminLayout>
            <div className="space-y-8">
                {/* Header */}

                <div>
                    <p className="text-sm font-black uppercase tracking-[0.3em] text-amber-600">
                        DIGITAL DIRECTORY
                    </p>

                    <h1 className="mt-2 text-5xl font-black">
                        Pending Payments
                    </h1>

                    <p className="mt-3 text-slate-500">
                        Payments awaiting administrator verification.
                    </p>
                </div>

                {/* Table */}

                <div className="overflow-hidden rounded-3xl border bg-white shadow-sm">
                    <div className="border-b p-6">
                        <h2 className="text-2xl font-black">
                            Payment Verification Queue
                        </h2>
                    </div>

                    {participants.length === 0 ? (
                        <AdminEmptyState
                            title="No Pending Payments"
                            description="
                                There are currently no payments waiting
                                for verification.
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
                                            Payment Method
                                        </th>

                                        <th className="px-6 py-4 text-left text-sm font-bold">
                                            Status
                                        </th>

                                        <th className="px-6 py-4 text-center text-sm font-bold">
                                            Receipt
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
                                                {participant.payment_method ??
                                                    "-"}
                                            </td>

                                            <td className="px-6 py-5">
                                                <AdminStatusBadge
                                                    status={
                                                        participant.payment_status
                                                    }
                                                />
                                            </td>

                                            <td className="px-6 py-5 text-center">
                                                {participant.payment_receipt ? (
                                                    <a
                                                        href={`/storage/${participant.payment_receipt}`}
                                                        target="_blank"
                                                        rel="noreferrer"
                                                        className="
                                                                inline-flex
                                                                items-center
                                                                gap-2
                                                                rounded-xl
                                                                border
                                                                px-3
                                                                py-2
                                                                text-sm
                                                            "
                                                    >
                                                        <Receipt className="h-4 w-4" />
                                                        View
                                                    </a>
                                                ) : (
                                                    "-"
                                                )}
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

                                                    <Link
                                                        method="post"
                                                        href={route(
                                                            "admin.digital-directory.verify",
                                                            participant.id,
                                                        )}
                                                        className="
                                                                rounded-xl
                                                                bg-emerald-500
                                                                p-2
                                                                text-white
                                                            "
                                                    >
                                                        <CheckCircle2 className="h-4 w-4" />
                                                    </Link>

                                                    <Link
                                                        method="post"
                                                        href={route(
                                                            "admin.digital-directory.reject",
                                                            participant.id,
                                                        )}
                                                        className="
                                                                rounded-xl
                                                                bg-red-500
                                                                p-2
                                                                text-white
                                                            "
                                                    >
                                                        <XCircle className="h-4 w-4" />
                                                    </Link>
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
