import { Link } from "@inertiajs/react";

import {
    CreditCard,
    ShieldCheck,
    Building2,
    Network,
    Sparkles,
    RefreshCw,
    ArrowRight,
} from "lucide-react";

export default function DashboardQueues({
    pendingPayments = 0,
    pendingVerifications = 0,
    pendingUpdates = 0,
    pendingClaims = 0,
    premiumRequests = 0,
    supplyChainRequests = 0,
}) {
    const queues = [
        {
            title: "Pending Payments",

            value: pendingPayments,

            icon: CreditCard,

            color: "bg-amber-100 text-amber-700",

            href: route("admin.digital-directory.index", {
                status: "pending_verification",
            }),
        },

        {
            title: "Company Verification",

            value: pendingVerifications,

            icon: ShieldCheck,

            color: "bg-sky-100 text-sky-700",

            href: route("companies.index"),
        },

        {
            title: "Company Updates",

            value: pendingUpdates,

            icon: RefreshCw,

            color: "bg-violet-100 text-violet-700",

            href: route("admin.dashboard"),
        },

        {
            title: "Company Claims",

            value: pendingClaims,

            icon: Building2,

            color: "bg-indigo-100 text-indigo-700",

            href: route("admin.dashboard"),
        },

        {
            title: "Premium Requests",

            value: premiumRequests,

            icon: Sparkles,

            color: "bg-pink-100 text-pink-700",

            href: route("admin.dashboard"),
        },

        {
            title: "Supply Chain Requests",

            value: supplyChainRequests,

            icon: Network,

            color: "bg-emerald-100 text-emerald-700",

            href: route("build-my-supply-chain.index"),
        },
    ];

    return (
        <div>
            {/* Header */}

            <h2 className="text-2xl font-black">Operational Queues</h2>

            <p className="mt-2 text-slate-500">
                Items requiring administrator attention across the DIGESTEX
                ecosystem.
            </p>

            {/* Queue Cards */}

            <div className="mt-6 grid gap-6 lg:grid-cols-2 xl:grid-cols-3">
                {queues.map((item) => {
                    const Icon = item.icon;

                    return (
                        <Link
                            key={item.title}
                            href={item.href}
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
                            <div className="flex items-start justify-between">
                                <div>
                                    <div
                                        className={`
                                            inline-flex
                                            rounded-2xl
                                            p-3
                                            ${item.color}
                                        `}
                                    >
                                        <Icon className="h-6 w-6" />
                                    </div>

                                    <h3
                                        className="
                                            mt-5
                                            text-lg
                                            font-black
                                        "
                                    >
                                        {item.title}
                                    </h3>

                                    <div
                                        className="
                                            mt-3
                                            text-4xl
                                            font-black
                                        "
                                    >
                                        {item.value}
                                    </div>
                                </div>

                                <ArrowRight
                                    className="
                                        h-5
                                        w-5
                                        text-slate-400
                                    "
                                />
                            </div>
                        </Link>
                    );
                })}
            </div>
        </div>
    );
}
