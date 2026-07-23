import AdminLayout from "@/Layouts/AdminLayout";
import { Link, router } from "@inertiajs/react";

import {
    Building2,
    CreditCard,
    ShieldCheck,
    Globe,
    User,
    CheckCircle2,
    XCircle,
    ArrowLeft,
    Sparkles,
} from "lucide-react";

export default function Show({ participant }) {
    return (
        <AdminLayout>
            <div className="mx-auto max-w-7xl space-y-8">
                {/* Header */}

                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-4xl font-black">
                            {participant.company_name}
                        </h1>

                        <p className="mt-2 text-slate-500">
                            Digital Directory Participant
                        </p>
                    </div>

                    <Link
                        href={route("admin.digital-directory.index")}
                        className="
                            inline-flex
                            items-center
                            gap-2
                            rounded-2xl
                            border
                            px-5
                            py-3
                            font-bold
                        "
                    >
                        <ArrowLeft className="h-4 w-4" />
                        Back
                    </Link>
                </div>

                {/* Summary */}

                <div className="grid gap-6 lg:grid-cols-4">
                    <Card
                        title="Package"
                        value={participant.package}
                        icon={<Sparkles />}
                    />

                    <Card
                        title="Payment"
                        value={participant.payment_status}
                        icon={<CreditCard />}
                    />

                    <Card
                        title="Activation"
                        value={participant.activation_status}
                        icon={<ShieldCheck />}
                    />

                    <Card
                        title="Amount"
                        value={`Rp ${Number(
                            participant.amount,
                        ).toLocaleString()}`}
                        icon={<CheckCircle2 />}
                    />
                </div>

                {/* Company */}

                <section className="rounded-3xl border bg-white p-8 shadow-sm">
                    <div className="flex items-center gap-3">
                        <Building2 />

                        <h2 className="text-2xl font-black">
                            Company Information
                        </h2>
                    </div>

                    <div className="mt-8 grid gap-6 md:grid-cols-2">
                        <InfoRow
                            icon={<Building2 />}
                            label="Company"
                            value={participant.company_name}
                        />

                        <InfoRow
                            icon={<User />}
                            label="PIC"
                            value={participant.pic_name}
                        />

                        <InfoRow
                            icon={<User />}
                            label="Position"
                            value={participant.position}
                        />

                        <InfoRow
                            icon={<Globe />}
                            label="Country"
                            value={participant.country}
                        />

                        <InfoRow
                            icon={<Globe />}
                            label="City"
                            value={participant.city}
                        />

                        <InfoRow
                            icon={<Globe />}
                            label="Website"
                            value={participant.website}
                        />
                    </div>
                </section>

                {/* Payment */}

                <section className="rounded-3xl border bg-white p-8 shadow-sm">
                    <div className="flex items-center gap-3">
                        <CreditCard />

                        <h2 className="text-2xl font-black">
                            Payment Information
                        </h2>
                    </div>

                    <div className="mt-8 grid gap-6 md:grid-cols-2">
                        <InfoRow
                            label="Method"
                            value={participant.payment_method}
                        />

                        <InfoRow
                            label="Gateway"
                            value={participant.payment_gateway}
                        />

                        <InfoRow
                            label="Transaction ID"
                            value={participant.transaction_id}
                        />

                        <InfoRow
                            label="Reference"
                            value={participant.payment_reference}
                        />

                        <InfoRow
                            label="Status"
                            value={participant.payment_status}
                        />

                        <InfoRow label="Paid At" value={participant.paid_at} />
                    </div>
                </section>

                {/* Receipt */}

                <section className="rounded-3xl border bg-white p-8 shadow-sm">
                    <h2 className="text-2xl font-black">Payment Receipt</h2>

                    {participant.payment_receipt ? (
                        <div className="mt-6">
                            <a
                                href={`/storage/${participant.payment_receipt}`}
                                target="_blank"
                                rel="noreferrer"
                                className="
                                    inline-flex
                                    rounded-xl
                                    bg-slate-900
                                    px-5
                                    py-3
                                    font-bold
                                    text-white
                                "
                            >
                                View Receipt
                            </a>
                        </div>
                    ) : (
                        <p className="mt-4 text-slate-500">
                            No receipt uploaded.
                        </p>
                    )}
                </section>

                {/* Services */}

                <section className="rounded-3xl border bg-white p-8 shadow-sm">
                    <h2 className="text-2xl font-black">DIGESTEX Services</h2>

                    <div className="mt-8 grid gap-4 md:grid-cols-2">
                        <Service
                            title="Company Passport"
                            active={participant.company_passport_active}
                        />

                        <Service
                            title="Visibility Score"
                            active={participant.visibility_score_active}
                        />

                        <Service
                            title="Executive Dashboard"
                            active={participant.executive_dashboard_active}
                        />

                        <Service
                            title="Smart Matching"
                            active={participant.smart_matching_active}
                        />

                        <Service
                            title="Build My Supply Chain"
                            active={participant.build_supply_chain_active}
                        />
                    </div>
                </section>

                {/* Notes */}

                <section className="rounded-3xl border bg-white p-8 shadow-sm">
                    <h2 className="text-2xl font-black">Admin Notes</h2>

                    <div className="mt-6 rounded-2xl bg-slate-50 p-6">
                        {participant.admin_notes ?? "No notes."}
                    </div>
                </section>

                {/* Actions */}

                <div className="flex flex-wrap gap-4">
                    <button
                        onClick={() =>
                            router.post(
                                route(
                                    "admin.digital-directory.verify",
                                    participant.id,
                                ),
                            )
                        }
                        className="
                            rounded-2xl
                            bg-emerald-500
                            px-6
                            py-4
                            font-bold
                            text-white
                        "
                    >
                        Verify Payment
                    </button>

                    <button
                        onClick={() =>
                            router.post(
                                route(
                                    "admin.digital-directory.reject",
                                    participant.id,
                                ),
                            )
                        }
                        className="
                            rounded-2xl
                            bg-red-500
                            px-6
                            py-4
                            font-bold
                            text-white
                        "
                    >
                        Reject
                    </button>

                    <button
                        onClick={() =>
                            router.post(
                                route(
                                    "admin.digital-directory.activate",
                                    participant.id,
                                ),
                            )
                        }
                        className="
                            rounded-2xl
                            border
                            px-6
                            py-4
                            font-bold
                        "
                    >
                        Activate
                    </button>

                    <button
                        onClick={() =>
                            router.post(
                                route(
                                    "admin.digital-directory.deactivate",
                                    participant.id,
                                ),
                            )
                        }
                        className="
                            rounded-2xl
                            border
                            px-6
                            py-4
                            font-bold
                        "
                    >
                        Deactivate
                    </button>
                </div>
            </div>
        </AdminLayout>
    );
}

function Card({ title, value, icon }) {
    return (
        <div className="rounded-3xl border bg-white p-6 shadow-sm">
            <div className="flex items-center justify-between">
                {icon}

                <span className="text-sm text-slate-500">{title}</span>
            </div>

            <div className="mt-4 text-2xl font-black">{value ?? "-"}</div>
        </div>
    );
}

function InfoRow({ icon, label, value }) {
    return (
        <div className="rounded-2xl bg-slate-50 p-5">
            <div className="flex items-center gap-2 text-slate-500">
                {icon}

                <span className="text-sm font-semibold">{label}</span>
            </div>

            <div className="mt-2 text-lg font-semibold">{value || "-"}</div>
        </div>
    );
}

function Service({ title, active }) {
    return (
        <div className="flex items-center gap-3 rounded-2xl bg-slate-50 p-4">
            {active ? (
                <CheckCircle2 className="text-emerald-500" />
            ) : (
                <XCircle className="text-slate-400" />
            )}

            <span className="font-semibold">{title}</span>
        </div>
    );
}
