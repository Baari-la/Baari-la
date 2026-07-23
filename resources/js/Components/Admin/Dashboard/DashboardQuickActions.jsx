import { Link } from "@inertiajs/react";

import {
    Building2,
    Globe,
    CreditCard,
    Network,
    Users,
    Plus,
} from "lucide-react";

export default function DashboardQuickActions() {
    const actions = [
        {
            title: "Add Company",
            description: "Create a new company profile.",
            icon: Building2,
            href: route("companies.create"),
        },

        {
            title: "Digital Directory",
            description: "Manage participants and payments.",
            icon: Globe,
            href: route("admin.digital-directory.index"),
        },

        {
            title: "Transactions",
            description: "View payment transactions.",
            icon: CreditCard,
            href: "#",
        },

        {
            title: "Build My Supply Chain™",
            description: "Generate supply chain intelligence.",
            icon: Network,
            href: route("build-my-supply-chain.index"),
        },

        {
            title: "Users",
            description: "Manage platform users.",
            icon: Users,
            href: route("users.index"),
        },

        {
            title: "New Program",
            description: "Launch a new DIGESTEX initiative.",
            icon: Plus,
            href: "#",
        },
    ];

    return (
        <div>
            <h2 className="text-2xl font-black">Quick Actions</h2>

            <p className="mt-2 text-slate-500">
                Frequently used actions for DIGESTEX administrators.
            </p>

            <div className="mt-6 grid gap-6 lg:grid-cols-3">
                {actions.map((action) => {
                    const Icon = action.icon;

                    return (
                        <Link
                            key={action.title}
                            href={action.href}
                            className="
                                rounded-3xl
                                border
                                bg-white
                                p-6
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

                            <h3
                                className="
                                    mt-6
                                    text-xl
                                    font-black
                                "
                            >
                                {action.title}
                            </h3>

                            <p
                                className="
                                    mt-2
                                    text-sm
                                    text-slate-500
                                "
                            >
                                {action.description}
                            </p>
                        </Link>
                    );
                })}
            </div>
        </div>
    );
}
