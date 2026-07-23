import AdminLayout from "@/Layouts/AdminLayout";

import { Link } from "@inertiajs/react";

import {
    Receipt,
    CheckCircle2,
    Clock3,
    XCircle,
    ArrowRight,
} from "lucide-react";

export default function Transactions({
    transactions = {
        data: [],
    },
}) {
    return (
        <AdminLayout>
            <div className="space-y-8">
                {/* Header */}

                <div>
                    <p
                        className="
                            text-xs
                            font-black
                            uppercase
                            tracking-[0.3em]
                            text-emerald-600
                        "
                    >
                        DIGESTEX
                    </p>

                    <h1 className="mt-2 text-4xl font-black">Transactions</h1>

                    <p className="mt-3 text-slate-500">
                        Monitor all transactions across Digital Directory,
                        Membership, Marketplace, and Executive Services.
                    </p>
                </div>

                {/* Table */}

                <div
                    className="
                        overflow-hidden
                        rounded-3xl
                        border
                        bg-white
                        shadow-sm
                    "
                >
                    <div className="overflow-x-auto">
                        <table className="min-w-full">
                            <thead
                                className="
                                    border-b
                                    bg-slate-50
                                "
                            >
                                <tr>
                                    <TH>Invoice</TH>

                                    <TH>Company</TH>

                                    <TH>Package</TH>

                                    <TH>Amount</TH>

                                    <TH>Method</TH>

                                    <TH>Status</TH>

                                    <TH>Created</TH>

                                    <TH>Action</TH>
                                </tr>
                            </thead>

                            <tbody>
                                {transactions.data.map((item) => (
                                    <tr
                                        key={item.id}
                                        className="
                                                border-b
                                                last:border-0
                                            "
                                    >
                                        <TD>{item.invoice_number ?? "-"}</TD>

                                        <TD>
                                            <div className="font-bold">
                                                {item.company_name}
                                            </div>

                                            <div className="text-sm text-slate-500">
                                                {item.email}
                                            </div>
                                        </TD>

                                        <TD>{item.package}</TD>

                                        <TD>
                                            Rp{" "}
                                            {Number(
                                                item.amount ?? 0,
                                            ).toLocaleString("id-ID")}
                                        </TD>

                                        <TD>{item.payment_method ?? "N/A"}</TD>

                                        <TD>
                                            <StatusBadge
                                                status={item.payment_status}
                                            />
                                        </TD>

                                        <TD>{item.created_at}</TD>

                                        <TD>
                                            <Link
                                                href={route(
                                                    "admin.digital-directory.show",
                                                    item.id,
                                                )}
                                                className="
                                                        inline-flex
                                                        items-center
                                                        gap-2
                                                        rounded-xl
                                                        border
                                                        px-4
                                                        py-2
                                                        text-sm
                                                        font-semibold
                                                        transition
                                                        hover:bg-slate-50
                                                    "
                                            >
                                                View
                                                <ArrowRight className="h-4 w-4" />
                                            </Link>
                                        </TD>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </div>

                {/* Empty State */}

                {transactions.data.length === 0 && (
                    <div
                        className="
                            rounded-3xl
                            border
                            bg-white
                            p-12
                            text-center
                        "
                    >
                        <Receipt className="mx-auto h-12 w-12 text-slate-300" />

                        <h3 className="mt-4 text-2xl font-black">
                            No Transactions
                        </h3>

                        <p className="mt-2 text-slate-500">
                            There are no transactions available.
                        </p>
                    </div>
                )}
            </div>
        </AdminLayout>
    );
}

/*
|--------------------------------------------------------------------------
| Components
|--------------------------------------------------------------------------
*/

function TH({ children }) {
    return (
        <th
            className="
                px-6
                py-4
                text-left
                text-sm
                font-black
                uppercase
                tracking-wide
                text-slate-500
            "
        >
            {children}
        </th>
    );
}

function TD({ children }) {
    return <td className="px-6 py-5">{children}</td>;
}

function StatusBadge({ status }) {
    const styles = {
        verified: "bg-emerald-100 text-emerald-700",

        pending_verification: "bg-amber-100 text-amber-700",

        rejected: "bg-red-100 text-red-700",
    };

    const icons = {
        verified: CheckCircle2,

        pending_verification: Clock3,

        rejected: XCircle,
    };

    const Icon = icons[status] ?? Clock3;

    return (
        <div
            className={`
                inline-flex
                items-center
                gap-2
                rounded-full
                px-3
                py-1
                text-sm
                font-bold
                ${styles[status] ?? "bg-slate-100 text-slate-700"}
            `}
        >
            <Icon className="h-4 w-4" />

            {status}
        </div>
    );
}
