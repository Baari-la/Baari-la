import AdminLayout from "@/Layouts/AdminLayout";
import { Head, Link } from "@inertiajs/react";

export default function Index() {
    const settings = [
        {
            title: "General Settings",
            description:
                "Platform name, logo, tagline, timezone, and company information.",
            route: "admin.settings.general",
        },

        {
            title: "Membership Settings",
            description:
                "Configure Free, API Member, Premium, and Executive memberships.",
            route: "admin.settings.membership",
        },

        {
            title: "Payment Gateway",
            description:
                "Manage QRIS, Midtrans, Xendit, Stripe, and PayPal integrations.",
            route: "admin.settings.payment-gateway",
        },

        {
            title: "Email Settings",
            description: "SMTP, Mailgun, Resend, and notification settings.",
            route: "admin.settings.email",
        },

        {
            title: "Localization",
            description:
                "Configure locale, currency, timezone, and language preferences.",
            route: "admin.settings.localization",
        },

        {
            title: "Security",
            description:
                "Manage login policies, session timeout, and security settings.",
            route: "admin.settings.security",
        },

        {
            title: "Storage",
            description:
                "Manage local storage, backups, and cloud storage providers.",
            route: "admin.settings.storage",
        },

        {
            title: "Queue Management",
            description:
                "Monitor queues, failed jobs, and background processing.",
            route: "admin.settings.queue",
        },

        {
            title: "System Health",
            description:
                "Check Laravel, PHP, MySQL, cache, and server health status.",
            route: "admin.settings.system-health",
        },
    ];

    return (
        <AdminLayout>
            <Head title="Settings" />

            <div className="space-y-8">
                {/* Header */}

                <div>
                    <h1 className="text-3xl font-black text-slate-900">
                        Settings
                    </h1>

                    <p className="mt-2 text-slate-500">
                        Configure and manage all DIGESTEX platform settings from
                        a single console.
                    </p>
                </div>

                {/* Overview */}

                <div className="grid gap-6 md:grid-cols-4">
                    <div className="rounded-3xl bg-white p-6 shadow-sm">
                        <div className="text-sm text-slate-500">
                            Total Modules
                        </div>

                        <div className="mt-2 text-3xl font-black">9</div>
                    </div>

                    <div className="rounded-3xl bg-white p-6 shadow-sm">
                        <div className="text-sm text-slate-500">
                            Environment
                        </div>

                        <div className="mt-2 text-xl font-black">
                            Production
                        </div>
                    </div>

                    <div className="rounded-3xl bg-white p-6 shadow-sm">
                        <div className="text-sm text-slate-500">Laravel</div>

                        <div className="mt-2 text-xl font-black">v12</div>
                    </div>

                    <div className="rounded-3xl bg-white p-6 shadow-sm">
                        <div className="text-sm text-slate-500">PHP</div>

                        <div className="mt-2 text-xl font-black">8.2</div>
                    </div>
                </div>

                {/* Settings Grid */}

                <div className="grid gap-6 md:grid-cols-3">
                    {settings.map((setting) => (
                        <Link
                            key={setting.title}
                            href={route(setting.route)}
                            className="
                                rounded-3xl
                                bg-white
                                p-6
                                shadow-sm
                                transition
                                hover:-translate-y-1
                                hover:shadow-md
                            "
                        >
                            <h2 className="text-xl font-bold">
                                {setting.title}
                            </h2>

                            <p className="mt-3 text-sm text-slate-500">
                                {setting.description}
                            </p>

                            <div className="mt-6 text-sm font-bold text-emerald-600">
                                Open Settings →
                            </div>
                        </Link>
                    ))}
                </div>

                {/* Footer */}

                <div
                    className="
                        rounded-3xl
                        bg-slate-900
                        p-8
                        text-white
                    "
                >
                    <h2 className="text-2xl font-black">
                        DIGESTEX Executive Console
                    </h2>

                    <p className="mt-3 text-slate-300">
                        Centralized administration for the Global Textile
                        Intelligence Ecosystem.
                    </p>

                    <div className="mt-6 grid gap-4 md:grid-cols-3">
                        <div>
                            <div className="text-sm text-slate-400">
                                Version
                            </div>

                            <div className="font-bold">v1.0</div>
                        </div>

                        <div>
                            <div className="text-sm text-slate-400">
                                Platform
                            </div>

                            <div className="font-bold">DIGESTEX</div>
                        </div>

                        <div>
                            <div className="text-sm text-slate-400">
                                Tagline
                            </div>

                            <div className="font-bold">
                                Where Textile Meets Intelligence
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
