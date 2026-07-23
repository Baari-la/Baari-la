import AdminLayout from "@/Layouts/AdminLayout";

import { Link } from "@inertiajs/react";

import {
    CreditCard,
    Receipt,
    QrCode,
    Landmark,
    TrendingUp,
    FileText,
    ArrowRight,
} from "lucide-react";

export default function Index({ stats = {} }) {
    const menus = [
        {
            title: "Transactions",
            description:
                "View all payment transactions across the DIGESTEX ecosystem.",
            icon: Receipt,
            href: route("admin.payments.transactions"),
            value: stats.transactions ?? 0,
        },

        {
            title: "QRIS",
            description: "Monitor QRIS payments and gateway status.",
            icon: QrCode,
            href: route("admin.payments.qris"),
            value: stats.qris ?? 0,
        },

        {
            title: "Manual Transfer",
            description: "Review and verify manual bank transfers.",
            icon: Landmark,
            href: route("admin.payments.manual-transfer"),
            value: stats.manualTransfers ?? 0,
        },

        {
            title: "Revenue",
            description: "Analyze revenue performance and financial trends.",
            icon: TrendingUp,
            href: route("admin.payments.revenue"),
            value: `Rp ${(stats.revenue ?? 0).toLocaleString("id-ID")}`,
        },

        {
            title: "Invoice Management",
            description: "Generate, resend, and manage invoices.",
            icon: FileText,
            href: route("admin.payments.invoice-management"),
            value: stats.invoices ?? 0,
        },
    ];

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

                    <h1
                        className="
                            mt-2
                            text-4xl
                            font-black
                        "
                    >
                        Payments
                    </h1>

                    <p className="mt-3 text-slate-500">
                        Centralized payment management across Digital Directory,
                        Membership, Marketplace, and Executive Services.
                    </p>
                </div>

                {/* Executive Stats */}

                <div
                    className="
                        grid
                        gap-6
                        lg:grid-cols-4
                    "
                >
                    <StatCard
                        title="Revenue"
                        value={`Rp ${(stats.revenue ?? 0).toLocaleString(
                            "id-ID",
                        )}`}
                    />

                    <StatCard
                        title="Transactions"
                        value={stats.transactions ?? 0}
                    />

                    <StatCard title="Pending" value={stats.pending ?? 0} />

                    <StatCard title="Invoices" value={stats.invoices ?? 0} />
                </div>

                {/* Modules */}

                <div
                    className="
                        grid
                        gap-6
                        lg:grid-cols-2
                    "
                >
                    {menus.map((item) => {
                        const Icon = item.icon;

                        return (
                            <Link
                                key={item.title}
                                href={item.href}
                                className="
                                        rounded-3xl
                                        border
                                        bg-white
                                        p-8
                                        shadow-sm
                                        transition
                                        hover:shadow-md
                                    "
                            >
                                <div
                                    className="
                                            flex
                                            items-start
                                            justify-between
                                        "
                                >
                                    <div>
                                        <div
                                            className="
                                                    flex
                                                    h-16
                                                    w-16
                                                    items-center
                                                    justify-center
                                                    rounded-2xl
                                                    bg-emerald-100
                                                    text-emerald-600
                                                "
                                        >
                                            <Icon className="h-8 w-8" />
                                        </div>

                                        <h2
                                            className="
                                                    mt-6
                                                    text-2xl
                                                    font-black
                                                "
                                        >
                                            {item.title}
                                        </h2>

                                        <p
                                            className="
                                                    mt-3
                                                    text-sm
                                                    text-slate-500
                                                "
                                        >
                                            {item.description}
                                        </p>

                                        <div
                                            className="
                                                    mt-6
                                                    text-3xl
                                                    font-black
                                                "
                                        >
                                            {item.value}
                                        </div>
                                    </div>

                                    <ArrowRight
                                        className="
                                                h-6
                                                w-6
                                                text-slate-400
                                            "
                                    />
                                </div>
                            </Link>
                        );
                    })}
                </div>
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
                shadow-sm
            "
        >
            <div className="text-sm text-slate-500">{title}</div>

            <div
                className="
                    mt-3
                    text-3xl
                    font-black
                "
            >
                {value}
            </div>
        </div>
    );
}
