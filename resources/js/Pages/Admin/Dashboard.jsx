import { useState } from "react";

import AdminLayout from "@/Layouts/AdminLayout";

import DashboardHeader from "@/Components/Admin/Dashboard/DashboardHeader";
import DashboardMediaModeration from "@/Components/Admin/Dashboard/DashboardMediaModeration";
import DashboardQuickActions from "@/Components/Admin/Dashboard/DashboardQuickActions";

import DashboardStats from "@/Components/Admin/Dashboard/DashboardStats";

import DashboardSearch from "@/Components/Admin/Dashboard/DashboardSearch";

import DashboardQueues from "@/Components/Admin/Dashboard/DashboardQueues";

import DashboardRecentCompanies from "@/Components/Admin/Dashboard/DashboardRecentCompanies";

import DashboardDatabaseManagement from "@/Components/Admin/Dashboard/DashboardDatabaseManagement";

import DashboardAuditModal from "@/Components/Admin/Dashboard/DashboardAuditModal";

export default function Dashboard({
    stats = {},
    recentCompanies = [],
    databaseStats = {},

    pendingPayments = 0,
    pendingProgramOwnerships = 0,

    pendingVerifications = 0,

    pendingUpdatesCount = 0,
    pendingClaimsCount = 0,

    supplyChainRequests = 0,
    pendingMediaModeration = 0,
    mediaModeration = [],
    pendingUpdates = [],
    pendingClaims = [],

    audit = {},
}) {
    const [openAudit, setOpenAudit] = useState(false);

    return (
        <AdminLayout>
            <div className="space-y-8">
                {/* Header */}

                <DashboardHeader />

                {/* Quick Actions */}

                <DashboardQuickActions />

                {/* Executive Overview */}

                <DashboardStats stats={stats} />

                {/* Global Search */}

                <DashboardSearch />

                {/* Operational Queues */}

                <DashboardQueues
                    pendingPayments={pendingPayments}
                    pendingProgramOwnerships={pendingProgramOwnerships}
                    pendingVerifications={pendingVerifications}
                    pendingUpdates={pendingUpdatesCount}
                    pendingClaims={pendingClaimsCount}
                    premiumRequests={stats.premium_requests}
                    supplyChainRequests={supplyChainRequests}
                    pendingMediaModeration={pendingMediaModeration}
                />
                <DashboardMediaModeration media={mediaModeration} />
                {/* Recent Companies */}

                <DashboardRecentCompanies companies={recentCompanies} />

                {/* Database */}

                <DashboardDatabaseManagement stats={databaseStats} />

                {/* Audit Button */}

                <div className="flex justify-end">
                    <button
                        onClick={() => setOpenAudit(true)}
                        className="
                            rounded-2xl
                            bg-slate-900
                            px-6
                            py-3
                            font-bold
                            text-white
                        "
                    >
                        Open System Audit
                    </button>
                </div>

                {/* Audit Modal */}

                <DashboardAuditModal
                    open={openAudit}
                    onClose={() => setOpenAudit(false)}
                />
            </div>
        </AdminLayout>
    );
}
