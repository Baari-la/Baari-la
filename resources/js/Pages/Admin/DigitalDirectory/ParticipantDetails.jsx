import AdminLayout from "@/Layouts/AdminLayout";
import { router } from "@inertiajs/react";
import AdminStatusBadge from "@/Components/Admin/AdminStatusBadge";

import {
    Building2,
    Mail,
    Phone,
    Globe,
    CreditCard,
    ShieldCheck,
    Zap,
    CheckCircle2,
    Calendar,
    User,
    Clock3,
    XCircle,
    FileText,
} from "lucide-react";

export default function ParticipantDetails({ participant }) {
    /*
|--------------------------------------------------------------------------
| Payment Verification
|--------------------------------------------------------------------------
*/

    const verifyPayment = () => {
        if (
            !window.confirm(`Verify payment for ${participant.company_name}?`)
        ) {
            return;
        }

        router.post(
            route("admin.payments.manual-transfer.approve", participant.id),
            {},
            {
                preserveScroll: true,
            },
        );
    };
    const displayCompanyName =
        participant.connected_company?.name || participant.company_name || "-";

    return (
        <AdminLayout>
            <div className="space-y-8">
                {/* Hero */}

                <div
                    className="
                        rounded-3xl
                        bg-slate-900
                        p-10
                        text-white
                    "
                >
                    <div className="flex items-start justify-between">
                        <div>
                            <p
                                className="
                                    text-xs
                                    font-black
                                    uppercase
                                    tracking-[0.3em]
                                    text-emerald-400
                                "
                            >
                                DIGITAL DIRECTORY
                            </p>

                            <h1 className="mt-3 text-5xl font-black">
                                {displayCompanyName}
                            </h1>

                            <p className="mt-4 text-lg text-slate-300">
                                {participant.package}
                            </p>
                        </div>

                        <div
                            className="
                                rounded-2xl
                                bg-emerald-500
                                px-5
                                py-3
                                font-black
                            "
                        >
                            <AdminStatusBadge
                                status={participant.activation_status}
                            />
                        </div>
                    </div>
                </div>

                {/* Information */}

                <div className="grid gap-6 lg:grid-cols-2">
                    <InfoCard
                        title="Company Information"
                        items={[
                            {
                                icon: Building2,
                                label: "Connected Company",
                                value:
                                    participant.connected_company?.name ||
                                    "Not connected",
                            },

                            {
                                icon: Building2,
                                label: "Registration Company Name",
                                value: participant.company_name,
                            },

                            {
                                icon: User,
                                label: "PIC",
                                value: participant.pic_name,
                            },

                            {
                                icon: Mail,
                                label: "Email",
                                value: participant.email,
                            },

                            {
                                icon: Phone,
                                label: "Phone",
                                value: participant.phone,
                            },

                            {
                                icon: Globe,
                                label: "Country",
                                value: participant.country,
                            },
                        ]}
                    />

                    <InfoCard
                        title="Payment Information"
                        items={[
                            {
                                icon: CreditCard,
                                label: "Invoice",
                                value: participant.invoice_number,
                            },

                            {
                                icon: CreditCard,
                                label: "Amount",
                                value:
                                    "Rp " +
                                    Number(
                                        participant.amount ?? 0,
                                    ).toLocaleString("id-ID"),
                            },

                            {
                                icon: CreditCard,
                                label: "Method",
                                value: participant.payment_method,
                            },

                            {
                                icon: CreditCard,
                                label: "Gateway",
                                value: participant.payment_gateway,
                            },
                            {
                                icon: FileText,
                                label: "Payment Reference",
                                value: participant.payment_reference ?? "-",
                            },

                            {
                                icon: Calendar,
                                label: "Payment Submitted",
                                value: participant.paid_at
                                    ? new Date(
                                          participant.paid_at,
                                      ).toLocaleString("id-ID")
                                    : "-",
                            },
                            {
                                icon:
                                    participant.payment_status === "verified"
                                        ? CheckCircle2
                                        : Clock3,

                                label: "Payment Status",

                                value:
                                    participant.payment_status === "verified"
                                        ? "Verified"
                                        : participant.payment_status ===
                                            "pending_verification"
                                          ? "Pending Verification"
                                          : participant.payment_status,
                            },
                            {
                                icon: Calendar,
                                label: "Verified At",
                                value: participant.payment_verified_at ?? "-",
                            },
                        ]}
                    />
                </div>
                {/* Payment Receipt */}

                {participant.payment_receipt && (
                    <div
                        className="
            rounded-3xl
            border
            border-slate-200
            bg-white
            p-6
            shadow-sm
        "
                    >
                        <div
                            className="
                flex
                flex-col
                gap-5
                sm:flex-row
                sm:items-center
                sm:justify-between
            "
                        >
                            <div className="flex items-center gap-4">
                                <div
                                    className="
                        rounded-2xl
                        bg-emerald-50
                        p-3
                        text-emerald-600
                    "
                                >
                                    <FileText className="h-6 w-6" />
                                </div>

                                <div>
                                    <div className="font-black text-slate-900">
                                        Payment Receipt
                                    </div>

                                    <p className="mt-1 text-sm text-slate-500">
                                        Bank transfer receipt submitted by the
                                        participant.
                                    </p>
                                </div>
                            </div>

                            <a
                                href={route(
                                    "admin.payments.manual-transfer.receipt",
                                    participant.id,
                                )}
                                target="_blank"
                                rel="noopener noreferrer"
                                className="
                    inline-flex
                    items-center
                    justify-center
                    gap-2
                    rounded-2xl
                    border
                    border-slate-200
                    bg-white
                    px-5
                    py-3
                    text-sm
                    font-bold
                    text-slate-700
                    transition
                    hover:border-emerald-300
                    hover:bg-emerald-50
                    hover:text-emerald-700
                "
                            >
                                <FileText className="h-4 w-4" />
                                View Receipt
                            </a>
                        </div>
                    </div>
                )}
                {/* Services */}

                <div className="rounded-3xl border bg-white p-8">
                    <h2 className="text-2xl font-black">Activated Services</h2>

                    <div className="mt-8 grid gap-6 lg:grid-cols-2">
                        <ServiceCard
                            title="Visibility Score™"
                            active={participant.visibility_score_active}
                        />

                        <ServiceCard
                            title="Company Passport™"
                            active={participant.company_passport_active}
                        />

                        <ServiceCard
                            title="Executive Dashboard™"
                            active={participant.executive_dashboard_active}
                        />

                        <ServiceCard
                            title="Smart Matching™"
                            active={participant.smart_matching_active}
                        />

                        <ServiceCard
                            title="Build My Supply Chain™"
                            active={participant.build_supply_chain_active}
                        />
                    </div>
                </div>

                {/* Admin Actions */}

                <div className="rounded-3xl border bg-white p-8">
                    <div>
                        <h2 className="text-2xl font-black">Admin Actions</h2>

                        <p className="mt-2 text-sm text-slate-500">
                            Manage payment verification and program activation
                            for this participant.
                        </p>
                    </div>

                    <div className="mt-6 flex flex-wrap gap-4">
                        {/* Verify Manual Transfer */}

                        {participant.payment_method === "Bank Transfer" &&
                            participant.payment_status ===
                                "pending_verification" && (
                                <button
                                    type="button"
                                    onClick={verifyPayment}
                                    className="
                        inline-flex
                        items-center
                        gap-2
                        rounded-2xl
                        bg-sky-500
                        px-6
                        py-3
                        font-bold
                        text-white
                        transition
                        hover:bg-sky-600
                    "
                                >
                                    <CheckCircle2 className="h-5 w-5" />
                                    Verify Payment
                                </button>
                            )}

                        {/* Activate Program */}

                        {participant.payment_status === "verified" &&
                            participant.company_id &&
                            participant.activation_status !== "active" && (
                                <button
                                    type="button"
                                    onClick={() =>
                                        router.post(
                                            route(
                                                "admin.digital-directory.activate",
                                                participant.id,
                                            ),
                                        )
                                    }
                                    className="
                        inline-flex
                        items-center
                        gap-2
                        rounded-2xl
                        bg-emerald-600
                        px-6
                        py-3
                        font-bold
                        text-white
                        transition
                        hover:bg-emerald-700
                    "
                                >
                                    <CheckCircle2 className="h-5 w-5" />

                                    {participant.activation_status ===
                                    "inactive"
                                        ? "Reactivate Program"
                                        : "Activate Program"}
                                </button>
                            )}

                        {/* Deactivate Program */}

                        {participant.activation_status === "active" && (
                            <button
                                type="button"
                                onClick={() => {
                                    if (
                                        window.confirm(
                                            "Deactivate this program? All active program services will be disabled.",
                                        )
                                    ) {
                                        router.post(
                                            route(
                                                "admin.digital-directory.deactivate",
                                                participant.id,
                                            ),
                                        );
                                    }
                                }}
                                className="
                    rounded-2xl
                    bg-red-50
                    px-6
                    py-3
                    font-bold
                    text-red-700
                    transition
                    hover:bg-red-100
                "
                            >
                                Deactivate Program
                            </button>
                        )}
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}

function InfoCard({ title, items }) {
    return (
        <div className="rounded-3xl border bg-white p-8">
            <h2 className="text-2xl font-black">{title}</h2>

            <div className="mt-6 space-y-4">
                {items.map((item, index) => {
                    const Icon = item.icon;

                    return (
                        <div
                            key={index}
                            className="
                                    flex
                                    items-center
                                    gap-4
                                "
                        >
                            <div
                                className="
                                        rounded-xl
                                        bg-slate-100
                                        p-3
                                    "
                            >
                                <Icon className="h-5 w-5" />
                            </div>

                            <div>
                                <div className="text-sm text-slate-500">
                                    {item.label}
                                </div>

                                <div className="font-bold">{item.value}</div>
                            </div>
                        </div>
                    );
                })}
            </div>
        </div>
    );
}

function ServiceCard({ title, active }) {
    return (
        <div
            className="
                flex
                items-center
                justify-between
                rounded-2xl
                border
                p-5
            "
        >
            <div className="flex items-center gap-3">
                <Zap className="h-5 w-5 text-emerald-500" />

                <div className="font-bold">{title}</div>
            </div>

            {active ? (
                <div
                    className="
                        flex
                        items-center
                        gap-2
                        rounded-xl
                        bg-emerald-100
                        px-4
                        py-2
                        text-emerald-700
                    "
                >
                    <CheckCircle2 className="h-4 w-4" />
                    Active
                </div>
            ) : (
                <div
                    className="
                        rounded-xl
                        bg-slate-100
                        px-4
                        py-2
                    "
                >
                    Inactive
                </div>
            )}
        </div>
    );
}
