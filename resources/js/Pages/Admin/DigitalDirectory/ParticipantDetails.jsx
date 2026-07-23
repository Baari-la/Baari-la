import AdminLayout from "@/Layouts/AdminLayout";

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
} from "lucide-react";

export default function ParticipantDetails({ participant }) {
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
                                {participant.company_name}
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
                            {participant.activation_status}
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
                                label: "Company",
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
                                    (participant.amount ?? 0).toLocaleString(
                                        "id-ID",
                                    ),
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
                                icon: Calendar,
                                label: "Verified At",
                                value: participant.payment_verified_at ?? "-",
                            },
                        ]}
                    />
                </div>

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
                    <h2 className="text-2xl font-black">Admin Actions</h2>

                    <div className="mt-6 flex flex-wrap gap-4">
                        <button
                            className="
                                rounded-2xl
                                bg-emerald-500
                                px-6
                                py-3
                                font-bold
                                text-white
                            "
                        >
                            Activate Participant
                        </button>

                        <button
                            className="
                                rounded-2xl
                                bg-sky-500
                                px-6
                                py-3
                                font-bold
                                text-white
                            "
                        >
                            Verify Payment
                        </button>

                        <button
                            className="
                                rounded-2xl
                                bg-amber-500
                                px-6
                                py-3
                                font-bold
                                text-white
                            "
                        >
                            Generate Invoice
                        </button>

                        <button
                            className="
                                rounded-2xl
                                bg-red-500
                                px-6
                                py-3
                                font-bold
                                text-white
                            "
                        >
                            Suspend
                        </button>
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
