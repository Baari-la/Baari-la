import { Link, usePage } from "@inertiajs/react";

import {
    LayoutDashboard,
    Building2,
    Globe,
    CreditCard,
    Network,
    Users,
    Database,
    Settings,
    BarChart3,
    ChevronDown,
} from "lucide-react";

export default function AdminSidebar() {
    const { url } = usePage();

    const menus = [
        {
            title: "Dashboard",
            icon: LayoutDashboard,
            href: route("admin.dashboard"),
        },

        {
            title: "Companies",
            icon: Building2,

            children: [
                {
                    title: "All Companies",
                    href: route("admin.companies.index"),
                },

                {
                    title: "Pending Verification",
                    href: route("admin.companies.pending"),
                },

                {
                    title: "Company Updates",
                    href: route("admin.companies.updates"),
                },

                {
                    title: "Company Claims",
                    href: route("admin.companies.claims"),
                },
            ],
        },

        {
            title: "Digital Directory",
            icon: Globe,

            children: [
                {
                    title: "Participants",
                    href: route("admin.digital-directory.index"),
                },

                {
                    title: "Pending Payments",
                    href: route("admin.digital-directory.pending-payments"),
                },

                {
                    title: "Ownership Verification",
                    href: route(
                        "admin.digital-directory.ownership-verification",
                    ),
                },

                {
                    title: "Verified Participants",
                    href: route("admin.digital-directory.verified"),
                },

                {
                    title: "Revenue Dashboard",
                    href: route("admin.digital-directory.revenue"),
                },
            ],
        },

        {
            title: "Payments",
            icon: CreditCard,

            children: [
                {
                    title: "Dashboard",
                    href: route("admin.payments.index"),
                },

                {
                    title: "Transactions",
                    href: route("admin.payments.transactions"),
                },

                {
                    title: "QRIS",
                    href: route("admin.payments.qris"),
                },

                {
                    title: "Manual Transfer",
                    href: route("admin.payments.manual-transfer"),
                },

                {
                    title: "Revenue",
                    href: route("admin.payments.revenue"),
                },

                {
                    title: "Invoice Management",
                    href: route("admin.payments.invoice-management"),
                },
            ],
        },

        {
            title: "Intelligence",
            icon: Network,

            children: [
                {
                    title: "Trade Dashboard",
                    href: "#",
                },

                {
                    title: "Executive Intelligence",
                    href: "#",
                },

                {
                    title: "Build My Supply Chain",
                    href: "#",
                },

                {
                    title: "Smart Matching",
                    href: "#",
                },
            ],
        },

        {
            title: "Users",
            icon: Users,

            children: [
                {
                    title: "All Users",
                    href: route("admin.users.index"),
                },

                {
                    title: "Admins",
                    href: route("admin.users.admins"),
                },

                {
                    title: "Premium Users",
                    href: route("admin.users.premium"),
                },

                {
                    title: "Company Owners",
                    href: route("admin.users.company-owners"),
                },

                {
                    title: "Pending Verification",
                    href: route("admin.users.pending-verification"),
                },

                {
                    title: "Activity Logs",
                    href: route("admin.users.activity-logs"),
                },
            ],
        },

        {
            title: "Database",
            icon: Database,

            children: [
                {
                    title: "Database Health",
                    href: "#",
                },

                {
                    title: "Backups",
                    href: "#",
                },

                {
                    title: "Audit Logs",
                    href: "#",
                },

                {
                    title: "Import Kemendag",
                    href: "#",
                },
            ],
        },

        {
            title: "Settings",
            icon: Settings,

            children: [
                {
                    title: "Dashboard",
                    href: route("admin.settings.index"),
                },

                {
                    title: "General Settings",
                    href: route("admin.settings.general"),
                },

                {
                    title: "Membership",
                    href: route("admin.settings.membership"),
                },

                {
                    title: "Payment Gateway",
                    href: route("admin.settings.payment-gateway"),
                },

                {
                    title: "Email",
                    href: route("admin.settings.email"),
                },

                {
                    title: "Localization",
                    href: route("admin.settings.localization"),
                },

                {
                    title: "Security",
                    href: route("admin.settings.security"),
                },

                {
                    title: "Storage",
                    href: route("admin.settings.storage"),
                },

                {
                    title: "Queue Management",
                    href: route("admin.settings.queue"),
                },

                {
                    title: "System Health",
                    href: route("admin.settings.system-health"),
                },
            ],
        },
    ];

    return (
        <aside className="flex min-h-screen w-72 flex-col border-r bg-slate-900 text-white">
            {/* Logo */}

            <div className="border-b border-white/10 p-6">
                <h1 className="text-2xl font-black">DIGESTEX</h1>

                <p className="mt-1 text-sm text-slate-400">
                    Admin Command Center
                </p>
            </div>

            {/* Menu */}

            <nav className="flex-1 space-y-2 overflow-y-auto p-4">
                {menus.map((menu) => {
                    const Icon = menu.icon;

                    if (menu.children) {
                        return (
                            <div
                                key={menu.title}
                                className="rounded-2xl bg-white/5 p-3"
                            >
                                <div className="flex items-center justify-between">
                                    <div className="flex items-center gap-3">
                                        <Icon className="h-5 w-5" />

                                        <span className="font-semibold">
                                            {menu.title}
                                        </span>
                                    </div>

                                    <ChevronDown className="h-4 w-4 text-slate-400" />
                                </div>

                                <div className="mt-3 space-y-1 pl-8">
                                    {menu.children.map((child) => (
                                        <Link
                                            key={child.title}
                                            href={child.href}
                                            className="
                                                    block
                                                    rounded-lg
                                                    px-3
                                                    py-2
                                                    text-sm
                                                    text-slate-300
                                                    transition
                                                    hover:bg-white/10
                                                "
                                        >
                                            {child.title}
                                        </Link>
                                    ))}
                                </div>
                            </div>
                        );
                    }

                    return (
                        <Link
                            key={menu.title}
                            href={menu.href}
                            className="
                                flex
                                items-center
                                gap-3
                                rounded-2xl
                                px-4
                                py-3
                                transition
                                hover:bg-white/10
                            "
                        >
                            <Icon className="h-5 w-5" />

                            <span className="font-semibold">{menu.title}</span>
                        </Link>
                    );
                })}
            </nav>

            {/* Footer */}

            <div className="border-t border-white/10 p-6">
                <div className="font-semibold">DIGESTEX v1.0</div>

                <div className="mt-1 text-xs text-slate-500">
                    Global Textile Intelligence Ecosystem
                </div>
            </div>
        </aside>
    );
}
