import {
    Database,
    HardDrive,
    RefreshCw,
    Download,
    ShieldCheck,
    Server,
    AlertTriangle,
    Activity,
    Archive,
} from "lucide-react";

export default function DashboardDatabaseManagement({ stats = {} }) {
    const items = [
        {
            title: "Database Tables",
            value: stats.tables ?? 124,
            icon: Database,
        },

        {
            title: "Total Records",
            value: stats.records ?? "1.2M",
            icon: HardDrive,
        },

        {
            title: "Storage Usage",
            value: stats.storage ?? "8.4 GB",
            icon: Archive,
        },

        {
            title: "Last Backup",
            value: stats.lastBackup ?? "Today 03:00",
            icon: Download,
        },

        {
            title: "Failed Jobs",
            value: stats.failedJobs ?? 0,
            icon: AlertTriangle,
        },

        {
            title: "Health",
            value: stats.health ?? "Healthy",
            icon: ShieldCheck,
        },
    ];

    return (
        <div className="space-y-6">
            {/* Header */}

            <div>
                <h2 className="text-2xl font-black">Database Management</h2>

                <p className="mt-2 text-slate-500">
                    Monitor database health, backups, storage, queues, and
                    infrastructure across the DIGESTEX ecosystem.
                </p>
            </div>

            {/* Statistics */}

            <div className="grid gap-6 lg:grid-cols-3 xl:grid-cols-6">
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
                                shadow-sm
                            "
                        >
                            <div className="flex items-center justify-between">
                                <div>
                                    <div
                                        className="
                                            text-sm
                                            text-slate-500
                                        "
                                    >
                                        {item.title}
                                    </div>

                                    <div
                                        className="
                                            mt-3
                                            text-2xl
                                            font-black
                                        "
                                    >
                                        {item.value}
                                    </div>
                                </div>

                                <div
                                    className="
                                        rounded-2xl
                                        bg-slate-100
                                        p-3
                                    "
                                >
                                    <Icon className="h-6 w-6 text-slate-700" />
                                </div>
                            </div>
                        </div>
                    );
                })}
            </div>

            {/* Actions */}

            <div className="grid gap-6 lg:grid-cols-4">
                <ActionCard
                    title="Run Backup"
                    description="
                        Create a new database
                        backup.
                    "
                    icon={Download}
                />

                <ActionCard
                    title="Optimize Database"
                    description="
                        Optimize tables and
                        improve performance.
                    "
                    icon={RefreshCw}
                />

                <ActionCard
                    title="Server Status"
                    description="
                        View infrastructure
                        health and uptime.
                    "
                    icon={Server}
                />

                <ActionCard
                    title="Health Check"
                    description="
                        Run application and
                        queue diagnostics.
                    "
                    icon={Activity}
                />
            </div>
        </div>
    );
}

function ActionCard({ title, description, icon: Icon }) {
    return (
        <button
            className="
                rounded-3xl
                border
                bg-white
                p-6
                text-left
                shadow-sm
                transition
                hover:-translate-y-1
                hover:shadow-md
            "
        >
            <div
                className="
                    flex
                    h-14
                    w-14
                    items-center
                    justify-center
                    rounded-2xl
                    bg-emerald-100
                    text-emerald-600
                "
            >
                <Icon className="h-7 w-7" />
            </div>

            <h3 className="mt-6 text-xl font-black">{title}</h3>

            <p className="mt-2 text-sm text-slate-500">{description}</p>
        </button>
    );
}
