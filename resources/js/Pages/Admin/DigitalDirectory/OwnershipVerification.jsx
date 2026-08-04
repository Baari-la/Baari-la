import AdminLayout from "@/Layouts/AdminLayout";
import AdminStatusBadge from "@/Components/Admin/AdminStatusBadge";
import { Link } from "@inertiajs/react";

import { Building2, ShieldCheck, CheckCircle2, XCircle } from "lucide-react";

export default function OwnershipVerification({ claims = [], stats = {} }) {
    const getCompanyName = (claim) => {
        return (
            claim.companyIdentity?.canonical_name ??
            claim.company?.nama_perusahaan ??
            claim.claimed_company_name ??
            "-"
        );
    };

    const getClaimType = (claim) => {
        if (claim.company_identity_id) {
            return "canonical";
        }

        if (claim.company_id) {
            return "legacy";
        }

        return "manual";
    };
    return (
        <AdminLayout>
            <div className="space-y-8">
                {/* Header */}

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
                        DIGITAL DIRECTORY
                    </p>

                    <h1 className="mt-2 text-4xl font-black">
                        Ownership Verification
                    </h1>

                    <p className="mt-3 text-slate-500">
                        Review company ownership requests submitted by Digital
                        Directory & Visibility Program participants.
                    </p>
                </div>

                {/* Statistics */}

                <div className="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                    <StatCard
                        title="Total"
                        value={stats.total ?? 0}
                        icon={Building2}
                    />

                    <StatCard
                        title="Pending"
                        value={stats.pending ?? 0}
                        icon={ShieldCheck}
                    />

                    <StatCard
                        title="Approved"
                        value={stats.approved ?? 0}
                        icon={CheckCircle2}
                    />

                    <StatCard
                        title="Rejected"
                        value={stats.rejected ?? 0}
                        icon={XCircle}
                    />
                </div>

                {/* Verification Queue */}

                <div
                    className="
                        overflow-hidden
                        rounded-3xl
                        border
                        bg-white
                        shadow-sm
                    "
                >
                    <div className="border-b px-6 py-5">
                        <h2 className="text-xl font-black">
                            Ownership Verification Queue
                        </h2>

                        <p className="mt-1 text-sm text-slate-500">
                            Ownership requests associated with Digital Directory
                            participants.
                        </p>
                    </div>

                    {claims.length > 0 ? (
                        <div className="overflow-x-auto">
                            <table className="min-w-full">
                                <thead className="bg-slate-50">
                                    <tr>
                                        <TH>Company</TH>
                                        <TH>Requested By</TH>
                                        <TH>Email</TH>
                                        <TH>Status</TH>
                                        <TH>Submitted</TH>
                                        <TH>Actions</TH>
                                    </tr>
                                </thead>

                                <tbody>
                                    {claims.map((claim) => (
                                        <tr key={claim.id} className="border-t">
                                            <TD>
                                                <div className="space-y-2">
                                                    <div className="font-bold text-slate-900">
                                                        {getCompanyName(claim)}
                                                    </div>

                                                    <ClaimTypeBadge
                                                        type={getClaimType(
                                                            claim,
                                                        )}
                                                    />
                                                </div>
                                            </TD>

                                            <TD>{claim.user?.name ?? "-"}</TD>

                                            <TD>{claim.user?.email ?? "-"}</TD>

                                            <TD>
                                                <AdminStatusBadge
                                                    status={claim.status}
                                                />
                                            </TD>

                                            <TD>
                                                {formatDate(
                                                    claim.submitted_at ??
                                                        claim.created_at,
                                                )}
                                            </TD>
                                            <TD>
                                                {claim.status === "pending" ? (
                                                    <div className="flex items-center gap-2">
                                                        <Link
                                                            method="post"
                                                            href={route(
                                                                "admin.companies.claims.approve",
                                                                claim.id,
                                                            )}
                                                            as="button"
                                                            preserveScroll
                                                            className="
                    rounded-xl
                    bg-emerald-600
                    px-4
                    py-2
                    text-xs
                    font-bold
                    text-white
                    transition
                    hover:bg-emerald-700
                "
                                                        >
                                                            Approve
                                                        </Link>

                                                        <Link
                                                            method="post"
                                                            href={route(
                                                                "admin.companies.claims.reject",
                                                                claim.id,
                                                            )}
                                                            as="button"
                                                            preserveScroll
                                                            className="
                    rounded-xl
                    bg-red-50
                    px-4
                    py-2
                    text-xs
                    font-bold
                    text-red-700
                    transition
                    hover:bg-red-100
                "
                                                        >
                                                            Reject
                                                        </Link>
                                                    </div>
                                                ) : (
                                                    <span className="text-slate-400">
                                                        Completed
                                                    </span>
                                                )}
                                            </TD>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    ) : (
                        <div className="p-12 text-center">
                            <ShieldCheck
                                className="
                                    mx-auto
                                    h-12
                                    w-12
                                    text-slate-300
                                "
                            />

                            <h3 className="mt-4 text-xl font-black">
                                No Ownership Requests
                            </h3>

                            <p className="mt-2 text-slate-500">
                                Ownership requests from Digital Directory
                                participants will appear here.
                            </p>
                        </div>
                    )}
                </div>
            </div>
        </AdminLayout>
    );
}

/*
|--------------------------------------------------------------------------
| Components
|--------------------------------------------------------------------------
*/
function ClaimTypeBadge({ type }) {
    if (type === "canonical") {
        return (
            <span className="inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-black uppercase tracking-wide text-emerald-700">
                Canonical Identity
            </span>
        );
    }

    if (type === "legacy") {
        return (
            <span className="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-black uppercase tracking-wide text-slate-600">
                Legacy Company
            </span>
        );
    }

    return (
        <span className="inline-flex rounded-full bg-amber-50 px-2.5 py-1 text-[10px] font-black uppercase tracking-wide text-amber-700">
            Manual / Unmatched
        </span>
    );
}

function StatCard({ title, value, icon: Icon }) {
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
            <div className="flex items-center justify-between">
                <div>
                    <div className="text-sm font-semibold text-slate-500">
                        {title}
                    </div>

                    <div className="mt-3 text-3xl font-black">{value}</div>
                </div>

                <div
                    className="
                        rounded-2xl
                        bg-slate-100
                        p-3
                        text-slate-600
                    "
                >
                    <Icon className="h-6 w-6" />
                </div>
            </div>
        </div>
    );
}

function TH({ children }) {
    return (
        <th
            className="
                px-6
                py-4
                text-left
                text-xs
                font-black
                uppercase
                tracking-wide
                text-slate-500
            "
        >
            {children}
        </th>
    );
}

function TD({ children }) {
    return <td className="px-6 py-5 text-sm text-slate-700">{children}</td>;
}

function formatDate(value) {
    if (!value) {
        return "-";
    }

    return new Date(value).toLocaleDateString("id-ID");
}
