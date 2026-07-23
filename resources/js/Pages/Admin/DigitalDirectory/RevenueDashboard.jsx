import AdminLayout from "@/Layouts/AdminLayout";
import AdminStatsCard from "@/Components/Admin/AdminStatsCard";

import {
    CreditCard,
    TrendingUp,
    Users,
    Globe,
    BarChart3,
    PieChart,
} from "lucide-react";

export default function RevenueDashboard({ stats = {} }) {
    const packages = stats.packages ?? [];

    return (
        <AdminLayout>
            <div className="space-y-8">
                {/* Header */}

                <div>
                    <p className="text-sm font-black uppercase tracking-[0.3em] text-emerald-600">
                        DIGITAL DIRECTORY
                    </p>

                    <h1 className="mt-2 text-5xl font-black">
                        Revenue Dashboard
                    </h1>

                    <p className="mt-3 max-w-3xl text-slate-500">
                        Monitor revenue performance, package distribution, and
                        participant growth across the Digital Directory
                        ecosystem.
                    </p>
                </div>

                {/* KPI */}

                <div className="grid gap-6 lg:grid-cols-4">
                    <AdminStatsCard
                        title="Revenue"
                        value={`Rp ${(stats.totalRevenue ?? 0).toLocaleString(
                            "id-ID",
                        )}`}
                        subtitle="Total Revenue"
                        icon={<CreditCard />}
                    />

                    <AdminStatsCard
                        title="Participants"
                        value={stats.totalParticipants ?? 0}
                        subtitle="Registered Companies"
                        icon={<Users />}
                    />

                    <AdminStatsCard
                        title="Average Ticket"
                        value={`Rp ${(stats.averageTicket ?? 0).toLocaleString(
                            "id-ID",
                        )}`}
                        subtitle="Average Package Value"
                        icon={<TrendingUp />}
                    />

                    <AdminStatsCard
                        title="Active"
                        value={stats.activeCompanies ?? 0}
                        subtitle="Activated Companies"
                        icon={<Globe />}
                    />
                </div>

                {/* Revenue Summary */}

                <div className="grid gap-6 lg:grid-cols-2">
                    <div className="rounded-3xl border bg-white p-8">
                        <div className="flex items-center gap-3">
                            <BarChart3 className="h-6 w-6" />

                            <h2 className="text-2xl font-black">
                                Revenue Summary
                            </h2>
                        </div>

                        <div className="mt-8 space-y-4">
                            <RevenueRow
                                label="Total Revenue"
                                value={`Rp ${(
                                    stats.totalRevenue ?? 0
                                ).toLocaleString("id-ID")}`}
                            />

                            <RevenueRow
                                label="Monthly Revenue"
                                value={`Rp ${(
                                    stats.monthlyRevenue ?? 0
                                ).toLocaleString("id-ID")}`}
                            />

                            <RevenueRow
                                label="Verified Payments"
                                value={stats.verifiedPayments ?? 0}
                            />

                            <RevenueRow
                                label="Pending Payments"
                                value={stats.pendingPayments ?? 0}
                            />

                            <RevenueRow
                                label="Activation Rate"
                                value={`${stats.activationRate ?? 0}%`}
                            />
                        </div>
                    </div>

                    {/* Package Distribution */}

                    <div className="rounded-3xl border bg-white p-8">
                        <div className="flex items-center gap-3">
                            <PieChart className="h-6 w-6" />

                            <h2 className="text-2xl font-black">
                                Package Distribution
                            </h2>
                        </div>

                        <div className="mt-8 space-y-4">
                            {packages.map((item) => (
                                <div
                                    key={item.package}
                                    className="
                                            flex
                                            items-center
                                            justify-between
                                            rounded-2xl
                                            bg-slate-50
                                            p-4
                                        "
                                >
                                    <div>
                                        <div className="font-bold">
                                            {item.package}
                                        </div>

                                        <div className="text-sm text-slate-500">
                                            {item.total} Companies
                                        </div>
                                    </div>

                                    <div className="text-xl font-black">
                                        Rp{" "}
                                        {(item.revenue ?? 0).toLocaleString(
                                            "id-ID",
                                        )}
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>

                {/* Top Countries */}

                <div className="rounded-3xl border bg-white p-8">
                    <h2 className="text-2xl font-black">Top Countries</h2>

                    <div className="mt-6 grid gap-4 lg:grid-cols-4">
                        {(stats.topCountries ?? []).map((country) => (
                            <div
                                key={country.country}
                                className="
                                    rounded-2xl
                                    bg-slate-50
                                    p-6
                                "
                            >
                                <div className="text-sm text-slate-500">
                                    Country
                                </div>

                                <div className="mt-2 text-xl font-black">
                                    {country.country}
                                </div>

                                <div className="mt-3 text-sm text-slate-500">
                                    {country.total} Participants
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}

function RevenueRow({ label, value }) {
    return (
        <div
            className="
                flex
                items-center
                justify-between
                rounded-2xl
                bg-slate-50
                p-4
            "
        >
            <div className="font-semibold">{label}</div>

            <div className="font-black">{value}</div>
        </div>
    );
}
