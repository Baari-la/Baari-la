import AdminLayout from "@/Layouts/AdminLayout";

import { TrendingUp, CreditCard, Calendar, Landmark } from "lucide-react";

export default function Revenue({ stats = {} }) {
    const cards = [
        {
            title: "Today",
            value: `Rp ${Number(stats.today ?? 0).toLocaleString("id-ID")}`,
            icon: Calendar,
        },

        {
            title: "This Month",
            value: `Rp ${Number(stats.month ?? 0).toLocaleString("id-ID")}`,
            icon: TrendingUp,
        },

        {
            title: "This Year",
            value: `Rp ${Number(stats.year ?? 0).toLocaleString("id-ID")}`,
            icon: Landmark,
        },

        {
            title: "Total Revenue",
            value: `Rp ${Number(stats.total ?? 0).toLocaleString("id-ID")}`,
            icon: CreditCard,
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

                    <h1 className="mt-2 text-4xl font-black">
                        Revenue Dashboard
                    </h1>

                    <p className="mt-3 text-slate-500">
                        Executive overview of DIGESTEX revenue across Digital
                        Directory, Membership, Marketplace, and future
                        subscription services.
                    </p>
                </div>

                {/* KPI */}

                <div className="grid gap-6 lg:grid-cols-4">
                    {cards.map((item) => {
                        const Icon = item.icon;

                        return (
                            <div
                                key={item.title}
                                className="
                                    rounded-3xl
                                    border
                                    bg-white
                                    p-6
                                    shadow-sm
                                "
                            >
                                <div className="flex items-center justify-between">
                                    <div>
                                        <div className="text-sm text-slate-500">
                                            {item.title}
                                        </div>

                                        <div
                                            className="
                                                mt-3
                                                text-3xl
                                                font-black
                                            "
                                        >
                                            {item.value}
                                        </div>
                                    </div>

                                    <div
                                        className="
                                            rounded-2xl
                                            bg-emerald-100
                                            p-3
                                            text-emerald-600
                                        "
                                    >
                                        <Icon className="h-6 w-6" />
                                    </div>
                                </div>
                            </div>
                        );
                    })}
                </div>

                {/* Executive Metrics */}

                <div className="grid gap-6 lg:grid-cols-3">
                    <MetricCard
                        title="Transactions"
                        value={stats.transactions ?? 0}
                    />

                    <MetricCard
                        title="Average Revenue"
                        value={`Rp ${Math.round(
                            (stats.total ?? 0) /
                                Math.max(stats.transactions ?? 1, 1),
                        ).toLocaleString("id-ID")}`}
                    />

                    <MetricCard title="Growth" value="12%" />
                </div>

                {/* Revenue Breakdown */}

                <div
                    className="
                        rounded-3xl
                        border
                        bg-white
                        p-8
                        shadow-sm
                    "
                >
                    <h2 className="text-2xl font-black">Revenue Breakdown</h2>

                    <div className="mt-8 space-y-5">
                        <BreakdownRow
                            label="Verified Company"
                            value="Rp 25.000.000"
                        />

                        <BreakdownRow
                            label="Visibility Partner"
                            value="Rp 50.000.000"
                        />

                        <BreakdownRow
                            label="Executive Partner"
                            value="Rp 100.000.000"
                        />

                        <BreakdownRow
                            label="Future Subscription Revenue"
                            value="Rp 0"
                        />
                    </div>
                </div>

                {/* Footer */}

                <div
                    className="
                        rounded-3xl
                        border
                        bg-slate-900
                        p-8
                        text-white
                    "
                >
                    <h3 className="text-2xl font-black">Executive Insight</h3>

                    <p className="mt-4 text-slate-300">
                        DIGESTEX revenue currently originates from Digital
                        Directory participants. Future revenue streams will
                        include subscriptions, marketplace commissions,
                        sponsorships, premium memberships, and executive
                        services.
                    </p>
                </div>
            </div>
        </AdminLayout>
    );
}

function MetricCard({ title, value }) {
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

function BreakdownRow({ label, value }) {
    return (
        <div className="flex items-center justify-between border-b pb-4">
            <div className="font-semibold">{label}</div>

            <div className="font-black">{value}</div>
        </div>
    );
}
