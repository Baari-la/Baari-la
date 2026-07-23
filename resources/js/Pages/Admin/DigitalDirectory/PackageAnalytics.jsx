import AdminLayout from "@/Layouts/AdminLayout";
import AdminStatsCard from "@/Components/Admin/AdminStatsCard";

import { Package, TrendingUp, CreditCard, BarChart3 } from "lucide-react";

export default function PackageAnalytics({ packages = [], stats = {} }) {
    return (
        <AdminLayout>
            <div className="space-y-8">
                {/* Header */}

                <div>
                    <p className="text-sm font-black uppercase tracking-[0.3em] text-emerald-600">
                        DIGITAL DIRECTORY
                    </p>

                    <h1 className="mt-2 text-5xl font-black">
                        Package Analytics
                    </h1>

                    <p className="mt-3 max-w-3xl text-slate-500">
                        Analyze package performance, revenue contribution, and
                        participant distribution across the Digital Directory
                        ecosystem.
                    </p>
                </div>

                {/* KPI */}

                <div className="grid gap-6 lg:grid-cols-4">
                    <AdminStatsCard
                        title="Packages"
                        value={stats.totalPackages ?? 0}
                        subtitle="Available Packages"
                        icon={<Package />}
                    />

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
                        subtitle="Total Companies"
                        icon={<BarChart3 />}
                    />

                    <AdminStatsCard
                        title="Average Ticket"
                        value={`Rp ${(stats.averageTicket ?? 0).toLocaleString(
                            "id-ID",
                        )}`}
                        subtitle="Average Package Value"
                        icon={<TrendingUp />}
                    />
                </div>

                {/* Package Cards */}

                <div className="grid gap-6 lg:grid-cols-3">
                    {packages.map((item) => (
                        <div
                            key={item.package}
                            className="
                                rounded-3xl
                                border
                                bg-white
                                p-8
                                shadow-sm
                            "
                        >
                            <h2 className="text-2xl font-black">
                                {item.package}
                            </h2>

                            <div className="mt-6 space-y-4">
                                <Metric
                                    label="Participants"
                                    value={item.total}
                                />

                                <Metric
                                    label="Revenue"
                                    value={`Rp ${(
                                        item.revenue ?? 0
                                    ).toLocaleString("id-ID")}`}
                                />

                                <Metric
                                    label="Average"
                                    value={`Rp ${(
                                        item.average ?? 0
                                    ).toLocaleString("id-ID")}`}
                                />

                                <Metric
                                    label="Contribution"
                                    value={`${item.percentage ?? 0}%`}
                                />
                            </div>

                            {/* Progress */}

                            <div className="mt-8">
                                <div className="flex justify-between text-sm">
                                    <span>Revenue Contribution</span>

                                    <span>{item.percentage ?? 0}%</span>
                                </div>

                                <div className="mt-2 h-3 rounded-full bg-slate-100">
                                    <div
                                        className="
                                            h-3
                                            rounded-full
                                            bg-emerald-500
                                        "
                                        style={{
                                            width: `${item.percentage ?? 0}%`,
                                        }}
                                    />
                                </div>
                            </div>
                        </div>
                    ))}
                </div>

                {/* Summary */}

                <div className="rounded-3xl border bg-white p-8">
                    <h2 className="text-2xl font-black">Executive Summary</h2>

                    <div className="mt-6 space-y-4">
                        <SummaryRow
                            label="Top Package"
                            value={stats.topPackage}
                        />

                        <SummaryRow
                            label="Highest Revenue"
                            value={`Rp ${(
                                stats.highestRevenue ?? 0
                            ).toLocaleString("id-ID")}`}
                        />

                        <SummaryRow
                            label="Most Popular"
                            value={stats.mostPopular ?? "-"}
                        />

                        <SummaryRow
                            label="Activation Rate"
                            value={`${stats.activationRate ?? 0}%`}
                        />
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}

function Metric({ label, value }) {
    return (
        <div className="flex items-center justify-between">
            <div className="text-slate-500">{label}</div>

            <div className="font-black">{value}</div>
        </div>
    );
}

function SummaryRow({ label, value }) {
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
