import AdminLayout from "@/Layouts/AdminLayout";

import { QrCode, Clock3, CheckCircle2, XCircle } from "lucide-react";

export default function QRIS({
    payments = {
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

                    <h1 className="mt-2 text-4xl font-black">QRIS Payments</h1>

                    <p className="mt-3 text-slate-500">
                        Monitor QRIS transactions, gateway references,
                        expiration, and payment status.
                    </p>
                </div>

                {/* Summary */}

                <div className="grid gap-6 lg:grid-cols-4">
                    <Card title="Total QRIS" value={payments.data.length} />

                    <Card
                        title="Paid"
                        value={
                            payments.data.filter(
                                (item) => item.payment_status === "verified",
                            ).length
                        }
                    />

                    <Card
                        title="Pending"
                        value={
                            payments.data.filter(
                                (item) =>
                                    item.payment_status ===
                                    "pending_verification",
                            ).length
                        }
                    />

                    <Card
                        title="Expired"
                        value={
                            payments.data.filter(
                                (item) => item.payment_status === "expired",
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

                                    <TH>QRIS Ref</TH>

                                    <TH>Amount</TH>

                                    <TH>Gateway</TH>

                                    <TH>Status</TH>

                                    <TH>Created</TH>
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

                                        <TD>{item.qris_reference ?? "-"}</TD>

                                        <TD>
                                            Rp{" "}
                                            {Number(item.amount).toLocaleString(
                                                "id-ID",
                                            )}
                                        </TD>

                                        <TD>
                                            {item.payment_gateway ?? "QRIS"}
                                        </TD>

                                        <TD>
                                            <StatusBadge
                                                status={item.payment_status}
                                            />
                                        </TD>

                                        <TD>{item.created_at}</TD>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </div>

                {/* Empty State */}

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
                        <QrCode className="mx-auto h-12 w-12 text-slate-300" />

                        <h3 className="mt-4 text-2xl font-black">
                            No QRIS Payments
                        </h3>

                        <p className="mt-2 text-slate-500">
                            QRIS transactions will appear here.
                        </p>
                    </div>
                )}
            </div>
        </AdminLayout>
    );
}

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

        expired: "bg-red-100 text-red-700",
    };

    const icons = {
        verified: CheckCircle2,

        pending_verification: Clock3,

        expired: XCircle,
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
