import {
    X,
    Database,
    HardDrive,
    Server,
    ShieldCheck,
    RefreshCw,
    Clock3,
    Activity,
    Globe,
    Network,
} from "lucide-react";

export default function DashboardAuditModal({
    open = false,
    onClose,
    audit = {},
}) {
    if (!open) {
        return null;
    }

    const items = [
        {
            title: "Application",
            value: audit.app ?? "Running",
            icon: Server,
        },

        {
            title: "Database",
            value: audit.database ?? "Healthy",
            icon: Database,
        },

        {
            title: "Storage",
            value: audit.storage ?? "42%",
            icon: HardDrive,
        },

        {
            title: "Cache",
            value: audit.cache ?? "Enabled",
            icon: RefreshCw,
        },

        {
            title: "Queues",
            value: audit.queues ?? "0 Failed",
            icon: ShieldCheck,
        },

        {
            title: "Last Backup",
            value: audit.lastBackup ?? "Today 03:00",
            icon: Clock3,
        },
    ];

    return (
        <div
            className="
                fixed
                inset-0
                z-50
                flex
                items-center
                justify-center
                bg-black/50
                p-6
            "
        >
            <div
                className="
                    max-h-[90vh]
                    w-full
                    max-w-6xl
                    overflow-y-auto
                    rounded-3xl
                    bg-white
                    shadow-2xl
                "
            >
                {/* Header */}

                <div className="flex items-center justify-between border-b p-8">
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

                        <h2 className="mt-2 text-4xl font-black">
                            System Audit Center
                        </h2>

                        <p className="mt-2 text-slate-500">
                            Monitor application health, infrastructure, and
                            ecosystem statistics.
                        </p>
                    </div>

                    <button
                        onClick={onClose}
                        className="rounded-2xl border p-3"
                    >
                        <X className="h-6 w-6" />
                    </button>
                </div>

                {/* Status Cards */}

                <div className="grid gap-6 p-8 lg:grid-cols-3">
                    {items.map((item) => {
                        const Icon = item.icon;

                        return (
                            <div
                                key={item.title}
                                className="
                                    rounded-3xl
                                    border
                                    bg-white
                                    p-6
                                "
                            >
                                <div className="flex items-center justify-between">
                                    <div>
                                        <div className="text-sm text-slate-500">
                                            {item.title}
                                        </div>

                                        <div className="mt-3 text-2xl font-black">
                                            {item.value}
                                        </div>
                                    </div>

                                    <div className="rounded-2xl bg-slate-100 p-3">
                                        <Icon className="h-6 w-6" />
                                    </div>
                                </div>
                            </div>
                        );
                    })}
                </div>

                {/* DIGESTEX Statistics */}

                <div className="border-t p-8">
                    <h3 className="text-2xl font-black">DIGESTEX Ecosystem</h3>

                    <div className="mt-6 grid gap-6 lg:grid-cols-3">
                        <StatCard
                            icon={<Globe />}
                            title="Companies"
                            value={audit.totalCompanies ?? "1,245"}
                        />

                        <StatCard
                            icon={<Activity />}
                            title="Trade Records"
                            value={audit.tradeRecords ?? "2.1M"}
                        />

                        <StatCard
                            icon={<Network />}
                            title="Supply Chain Reports"
                            value={audit.supplyChains ?? 35}
                        />
                    </div>
                </div>

                {/* Technical Details */}

                <div className="border-t p-8">
                    <h3 className="text-2xl font-black">Technical Details</h3>

                    <div className="mt-6 space-y-4">
                        <AuditRow
                            label="Laravel Version"
                            value={audit.laravel ?? "12.x"}
                        />

                        <AuditRow
                            label="PHP Version"
                            value={audit.php ?? "8.2.12"}
                        />

                        <AuditRow label="MySQL" value={audit.mysql ?? "8.x"} />

                        <AuditRow
                            label="Environment"
                            value={audit.environment ?? "Production"}
                        />

                        <AuditRow
                            label="Queue Driver"
                            value={audit.queue ?? "Database"}
                        />

                        <AuditRow
                            label="Storage Used"
                            value={audit.storageUsed ?? "8.4 GB"}
                        />

                        <AuditRow
                            label="Failed Jobs"
                            value={audit.failedJobs ?? 0}
                        />

                        <AuditRow
                            label="Digital Directory Participants"
                            value={audit.participants ?? 25}
                        />
                    </div>
                </div>

                {/* Footer */}

                <div className="flex justify-end gap-4 border-t p-8">
                    <button
                        className="
                            rounded-2xl
                            border
                            px-6
                            py-3
                            font-bold
                        "
                    >
                        Run Health Check
                    </button>

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
                        Export Audit Report
                    </button>
                </div>
            </div>
        </div>
    );
}

function AuditRow({ label, value }) {
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

function StatCard({ icon, title, value }) {
    return (
        <div className="rounded-3xl border p-6">
            <div className="flex items-center gap-3 text-slate-500">
                {icon}

                <span className="font-semibold">{title}</span>
            </div>

            <div className="mt-4 text-3xl font-black">{value}</div>
        </div>
    );
}
