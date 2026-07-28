import AdminLayout from "@/Layouts/AdminLayout";
import { Link } from "@inertiajs/react";

import {
    Users,
    CreditCard,
    ShieldCheck,
    CheckCircle2,
    Search,
    Eye,
    Sparkles,
    Globe,
} from "lucide-react";

export default function Index({ participants, stats }) {
    return (
        <AdminLayout>
            <div className="space-y-8">
                {/* Header */}

                <div>
                    <h1 className="text-4xl font-black">Digital Directory</h1>

                    <p className="mt-2 text-slate-500">
                        Manage Digital Directory & Visibility Program
                        participants.
                    </p>
                </div>

                {/* Stats */}

                <div className="grid gap-6 lg:grid-cols-6">
                    <StatCard
                        title="Participants"
                        value={stats.total}
                        icon={<Users />}
                    />

                    <StatCard
                        title="Revenue"
                        value={new Intl.NumberFormat("id-ID", {
                            style: "currency",
                            currency: "IDR",
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0,
                        }).format(Number(stats.revenue ?? 0))}
                        icon={<CreditCard />}
                    />

                    <StatCard
                        title="Executive"
                        value={stats.executive}
                        icon={<ShieldCheck />}
                    />

                    <StatCard
                        title="Visibility"
                        value={stats.visibility}
                        icon={<Sparkles />}
                    />

                    <StatCard
                        title="Verified"
                        value={stats.verified}
                        icon={<CheckCircle2 />}
                    />

                    <StatCard
                        title="Active"
                        value={stats.active}
                        icon={<Globe />}
                    />
                </div>

                {/* Search */}

                <div
                    className="
                        flex
                        items-center
                        gap-3
                        rounded-2xl
                        border
                        bg-white
                        p-4
                        shadow-sm
                    "
                >
                    <Search
                        className="
                            h-5
                            w-5
                            text-slate-400
                        "
                    />

                    <input
                        placeholder="
                            Search company...
                        "
                        className="
                            w-full
                            outline-none
                        "
                    />
                </div>

                {/* Table */}

                <div
                    className="
                        overflow-hidden
                        rounded-3xl
                        border
                        bg-white
                        shadow-sm
                    "
                >
                    <table className="w-full">
                        <thead
                            className="
                                bg-slate-50
                                text-left
                            "
                        >
                            <tr>
                                <th className="p-5">Company</th>

                                <th>Package</th>

                                <th>Payment</th>

                                <th>Activation</th>

                                <th>Created</th>

                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            {participants.data.map((participant) => (
                                <tr
                                    key={participant.id}
                                    className="
                                            border-t
                                        "
                                >
                                    <td className="p-5">
                                        <div className="font-bold">
                                            {participant.company
                                                ?.nama_perusahaan ||
                                                participant.company_name}
                                        </div>

                                        <div
                                            className="
                                                    text-sm
                                                    text-slate-500
                                                "
                                        >
                                            {participant.email}
                                        </div>
                                    </td>

                                    <td>{participant.package}</td>

                                    <td>
                                        <PaymentBadge
                                            status={participant.payment_status}
                                        />
                                    </td>

                                    <td>
                                        <ActivationBadge
                                            status={
                                                participant.activation_status
                                            }
                                        />
                                    </td>

                                    <td>
                                        {new Date(
                                            participant.created_at,
                                        ).toLocaleDateString()}
                                    </td>

                                    <td>
                                        <Link
                                            href={route(
                                                "admin.digital-directory.show",
                                                participant.id,
                                            )}
                                            className="
                                                    inline-flex
                                                    items-center
                                                    gap-2
                                                    rounded-xl
                                                    bg-slate-900
                                                    px-4
                                                    py-2
                                                    text-sm
                                                    text-white
                                                "
                                        >
                                            <Eye className="h-4 w-4" />
                                            View
                                        </Link>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                {/* Pagination */}

                {participants.links && (
                    <div className="flex gap-2">
                        {participants.links.map((link) => (
                            <div
                                key={link.label}
                                dangerouslySetInnerHTML={{
                                    __html: link.label,
                                }}
                                className="
                                        rounded-lg
                                        border
                                        px-3
                                        py-2
                                    "
                            />
                        ))}
                    </div>
                )}
            </div>
        </AdminLayout>
    );
}

function StatCard({ title, value, icon }) {
    return (
        <div
            className="
                rounded-3xl
                border
                bg-white
                p-6
                shadow-sm
            "
        >
            <div
                className="
                    flex
                    items-center
                    justify-between
                "
            >
                <div>
                    <p
                        className="
                            text-sm
                            text-slate-500
                        "
                    >
                        {title}
                    </p>

                    <div
                        className="
                            mt-2
                            text-3xl
                            font-black
                        "
                    >
                        {value}
                    </div>
                </div>

                {icon}
            </div>
        </div>
    );
}

function PaymentBadge({ status }) {
    const classes = {
        waiting_payment: "bg-slate-100 text-slate-700",

        pending_verification: "bg-amber-100 text-amber-700",

        verified: "bg-emerald-100 text-emerald-700",

        rejected: "bg-red-100 text-red-700",

        paid: "bg-sky-100 text-sky-700",
    };

    return (
        <span
            className={`
                rounded-full
                px-3
                py-1
                text-xs
                font-bold

                ${classes[status] ?? classes.waiting_payment}
            `}
        >
            {status}
        </span>
    );
}

function ActivationBadge({ status }) {
    const classes = {
        draft: "bg-slate-100 text-slate-700",

        active: "bg-emerald-100 text-emerald-700",

        inactive: "bg-red-100 text-red-700",

        submitted: "bg-sky-100 text-sky-700",
    };

    return (
        <span
            className={`
                rounded-full
                px-3
                py-1
                text-xs
                font-bold

                ${classes[status] ?? classes.draft}
            `}
        >
            {status}
        </span>
    );
}
