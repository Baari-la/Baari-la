import AdminLayout from "@/Layouts/AdminLayout";

import { Link } from "@inertiajs/react";

import {
    FileText,
    Send,
    Download,
    CheckCircle2,
    XCircle,
    ArrowRight,
} from "lucide-react";

export default function InvoiceManagement({
    invoices = {
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

                    <h1 className="mt-2 text-4xl font-black">
                        Invoice Management
                    </h1>

                    <p className="mt-3 text-slate-500">
                        Manage invoices across Digital Directory, Membership,
                        Marketplace, Sponsorship, and Subscription services.
                    </p>
                </div>

                {/* Summary */}

                <div className="grid gap-6 lg:grid-cols-4">
                    <StatCard
                        title="Total Invoices"
                        value={invoices.data.length}
                    />

                    <StatCard
                        title="Paid"
                        value={
                            invoices.data.filter(
                                (item) => item.payment_status === "verified",
                            ).length
                        }
                    />

                    <StatCard
                        title="Pending"
                        value={
                            invoices.data.filter(
                                (item) =>
                                    item.payment_status ===
                                    "pending_verification",
                            ).length
                        }
                    />

                    <StatCard
                        title="Voided"
                        value={
                            invoices.data.filter(
                                (item) => item.payment_status === "void",
                            ).length
                        }
                    />
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
                            <thead className="bg-slate-50">
                                <tr>
                                    <TH>Invoice</TH>

                                    <TH>Company</TH>

                                    <TH>Package</TH>

                                    <TH>Amount</TH>

                                    <TH>Status</TH>

                                    <TH>Created</TH>

                                    <TH>Actions</TH>
                                </tr>
                            </thead>

                            <tbody>
                                {invoices.data.map((item) => (
                                    <tr key={item.id} className="border-t">
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
                                            {Number(item.amount).toLocaleString(
                                                "id-ID",
                                            )}
                                        </TD>

                                        <TD>
                                            <StatusBadge
                                                status={item.payment_status}
                                            />
                                        </TD>

                                        <TD>{item.created_at}</TD>

                                        <TD>
                                            <div className="flex gap-2">
                                                <button
                                                    className="
                                                            rounded-xl
                                                            border
                                                            p-2
                                                            hover:bg-slate-50
                                                        "
                                                    title="Download Invoice"
                                                >
                                                    <Download className="h-4 w-4" />
                                                </button>

                                                <button
                                                    className="
                                                            rounded-xl
                                                            border
                                                            p-2
                                                            hover:bg-slate-50
                                                        "
                                                    title="Resend Invoice"
                                                >
                                                    <Send className="h-4 w-4" />
                                                </button>

                                                <Link
                                                    href={route(
                                                        "admin.digital-directory.show",
                                                        item.id,
                                                    )}
                                                    className="
                                                            rounded-xl
                                                            border
                                                            p-2
                                                            hover:bg-slate-50
                                                        "
                                                    title="View Details"
                                                >
                                                    <ArrowRight className="h-4 w-4" />
                                                </Link>
                                            </div>
                                        </TD>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </div>

                {/* Empty State */}

                {invoices.data.length === 0 && (
                    <div
                        className="
                            rounded-3xl
                            border
                            bg-white
                            p-12
                            text-center
                        "
                    >
                        <FileText className="mx-auto h-12 w-12 text-slate-300" />

                        <h3 className="mt-4 text-2xl font-black">
                            No Invoices
                        </h3>

                        <p className="mt-2 text-slate-500">
                            Generated invoices will appear here.
                        </p>
                    </div>
                )}
            </div>
        </AdminLayout>
    );
}

function StatCard({ title, value }) {
    return (
        <div
            className="
                rounded-3xl
                border
                bg-white
                p-6
            "
        >
            <div className="text-sm text-slate-500">{title}</div>

            <div className="mt-3 text-3xl font-black">{value}</div>
        </div>
    );
}

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

        void: "bg-red-100 text-red-700",
    };

    const icons = {
        verified: CheckCircle2,

        pending_verification: FileText,

        void: XCircle,
    };

    const Icon = icons[status] ?? FileText;

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
