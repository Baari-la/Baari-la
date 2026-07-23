import AdminStatsCard from "@/Components/Admin/AdminStatsCard";

import {
    Building2,
    CreditCard,
    ShieldCheck,
    Crown,
    TrendingUp,
    Sparkles,
} from "lucide-react";

export default function DashboardStats({ stats = {} }) {
    return (
        <div>
            {/* Header */}

            <div>
                <h2 className="text-2xl font-black">Executive Overview</h2>

                <p className="mt-2 text-slate-500">
                    Key performance indicators across the DIGESTEX Global
                    Textile Intelligence Ecosystem.
                </p>
            </div>

            {/* Stats */}

            <div className="mt-6 grid gap-6 lg:grid-cols-3 xl:grid-cols-6">
                {/* Total Companies */}

                <AdminStatsCard
                    title="Companies"
                    value={stats.total_companies ?? stats.total ?? 0}
                    subtitle="Registered Companies"
                    icon={<Building2 />}
                />

                {/* Revenue */}

                <AdminStatsCard
                    title="Revenue"
                    value={`Rp ${(stats.revenue ?? 0).toLocaleString("id-ID")}`}
                    subtitle="Verified Payments"
                    icon={<CreditCard />}
                />

                {/* Verified */}

                <AdminStatsCard
                    title="Verified"
                    value={stats.verified ?? 0}
                    subtitle="Verified Companies"
                    icon={<ShieldCheck />}
                />

                {/* Gold Members */}

                <AdminStatsCard
                    title="Gold Members"
                    value={stats.gold_members ?? 0}
                    subtitle="Premium Members"
                    icon={<Crown />}
                />

                {/* Premium Requests */}

                <AdminStatsCard
                    title="Premium Requests"
                    value={stats.premium_requests ?? 0}
                    subtitle="Awaiting Approval"
                    icon={<Sparkles />}
                />

                {/* Growth */}

                <AdminStatsCard
                    title="Growth"
                    value={`${stats.growth ?? 18}%`}
                    subtitle="Monthly Growth"
                    icon={<TrendingUp />}
                />
            </div>
        </div>
    );
}
