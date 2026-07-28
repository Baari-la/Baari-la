import {
    CheckCircle2,
    Clock3,
    XCircle,
    AlertCircle,
    ShieldCheck,
} from "lucide-react";

export default function AdminStatusBadge({ status = "draft" }) {
    const statuses = {
        /*
        |--------------------------------------------------------------------------
        | Payment
        |--------------------------------------------------------------------------
        */

        waiting_payment: {
            label: "Waiting Payment",
            className: "bg-slate-100 text-slate-700",
            icon: Clock3,
        },

        pending_verification: {
            label: "Pending Verification",
            className: "bg-amber-100 text-amber-700",
            icon: AlertCircle,
        },

        verified: {
            label: "Verified",
            className: "bg-emerald-100 text-emerald-700",
            icon: CheckCircle2,
        },

        rejected: {
            label: "Rejected",
            className: "bg-red-100 text-red-700",
            icon: XCircle,
        },

        /*
|--------------------------------------------------------------------------
| Company Claims / Ownership Verification
|--------------------------------------------------------------------------
*/

        pending: {
            label: "Pending",
            className: "bg-amber-100 text-amber-700",
            icon: Clock3,
        },

        approved: {
            label: "Approved",
            className: "bg-emerald-100 text-emerald-700",
            icon: CheckCircle2,
        },

        /*
        |--------------------------------------------------------------------------
        | Activation
        |--------------------------------------------------------------------------
        */

        draft: {
            label: "Draft",
            className: "bg-slate-100 text-slate-700",
            icon: Clock3,
        },

        submitted: {
            label: "Submitted",
            className: "bg-sky-100 text-sky-700",
            icon: ShieldCheck,
        },

        active: {
            label: "Active",
            className: "bg-emerald-100 text-emerald-700",
            icon: CheckCircle2,
        },

        inactive: {
            label: "Inactive",
            className: "bg-red-100 text-red-700",
            icon: XCircle,
        },

        /*
        |--------------------------------------------------------------------------
        | Membership
        |--------------------------------------------------------------------------
        */

        gold_member: {
            label: "Gold Member",
            className: "bg-yellow-100 text-yellow-700",
            icon: ShieldCheck,
        },

        public: {
            label: "Public",
            className: "bg-slate-100 text-slate-700",
            icon: ShieldCheck,
        },
    };

    const current = statuses[status] ?? statuses.draft;

    const Icon = current.icon;

    return (
        <span
            className={`
                inline-flex
                items-center
                gap-2
                rounded-full
                px-3
                py-1.5
                text-xs
                font-bold

                ${current.className}
            `}
        >
            <Icon className="h-4 w-4" />

            {current.label}
        </span>
    );
}
