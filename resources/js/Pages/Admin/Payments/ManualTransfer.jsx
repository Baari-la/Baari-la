import AdminLayout from "@/Layouts/AdminLayout";

import { Link, router } from "@inertiajs/react";

import {
    Landmark,
    CheckCircle2,
    XCircle,
    Clock3,
    ArrowRight,
    FileText,
} from "lucide-react";

export default function ManualTransfer({
    payments = {
        data: [],
    },
}) {
    /*
|--------------------------------------------------------------------------
| Payment Actions
|--------------------------------------------------------------------------
*/

    const approvePayment = (item) => {
        if (!window.confirm(`Approve payment from ${item.company_name}?`)) {
            return;
        }

        router.post(
            route("admin.payments.manual-transfer.approve", item.id),
            {},
            {
                preserveScroll: true,
            },
        );
    };

    const rejectPayment = (item) => {
        if (!window.confirm(`Reject payment from ${item.company_name}?`)) {
            return;
        }

        router.post(
            route("admin.payments.manual-transfer.reject", item.id),
            {},
            {
                preserveScroll: true,
            },
        );
    };

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
                        Manual Transfer
                    </h1>

                    <p className="mt-3 text-slate-500">
                        Review bank transfer receipts and verify payments
                        submitted by participants.
                    </p>
                </div>

                {/* Summary */}

                <div className="grid gap-6 lg:grid-cols-4">
                    <Card
                        title="Total Transfers"
                        value={payments.data.length}
                    />

                    <Card
                        title="Verified"
                        value={
                            payments.data.filter(
                                (p) => p.payment_status === "verified",
                            ).length
                        }
                    />

                    <Card
                        title="Pending"
                        value={
                            payments.data.filter(
                                (p) =>
                                    p.payment_status === "pending_verification",
                            ).length
                        }
                    />

                    <Card
                        title="Rejected"
                        value={
                            payments.data.filter(
                                (p) => p.payment_status === "rejected",
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
                                    <TH>Company</TH>

                                    <TH>Package</TH>

                                    <TH>Amount</TH>

                                    <TH>Receipt</TH>

                                    <TH>Status</TH>

                                    <TH>Submitted</TH>

                                    <TH>Action</TH>
                                </tr>
                            </thead>

                            <tbody>
                                {payments.data.map((item) => (
                                    <tr
                                        key={item.id}
                                        className="
                                                border-t
                                            "
                                    >
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
                                            {item.payment_receipt ? (
                                                <span className="font-semibold text-emerald-600">
                                                    Uploaded
                                                </span>
                                            ) : (
                                                <span className="text-slate-400">
                                                    No Receipt
                                                </span>
                                            )}
                                        </TD>

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
                                                Review
                                                <ArrowRight className="h-4 w-4" />
                                            </Link>
                                        </TD>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </div>

                {/* Empty */}

                {payments.data.length === 0 && (
                    <div
                        className="
                            rounded-3xl
                            border
                            bg-white
                            p-12
                            text-center
                        "
                    >
                        <Landmark className="mx-auto h-12 w-12 text-slate-300" />

                        <h3 className="mt-4 text-2xl font-black">
                            No Manual Transfers
                        </h3>

                        <p className="mt-2 text-slate-500">
                            Submitted bank transfers will appear here.
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

function Card({ title, value }) {
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
