import { Link, router, usePage } from "@inertiajs/react";
import {
    Building2,
    Search,
    Eye,
    Pencil,
    Settings2,
    CheckCircle2,
    XCircle,
    Star,
    Layers3,
} from "lucide-react";
import { useState } from "react";

export default function Index() {
    const { partners, stats, filters = {} } = usePage().props;

    const [search, setSearch] = useState(filters.search || "");

    const submitSearch = (e) => {
        e.preventDefault();

        router.get(
            route("admin.industry-partners.index"),
            {
                search,
                status: filters.status || undefined,
                category: filters.category || undefined,
            },
            {
                preserveState: true,
                replace: true,
            },
        );
    };

    return (
        <div className="space-y-6">
            {/* HEADER */}
            <div>
                <div className="flex items-center gap-3">
                    <div className="flex h-11 w-11 items-center justify-center rounded-2xl bg-amber-400/10">
                        <Building2 className="h-5 w-5 text-amber-400" />
                    </div>

                    <div>
                        <h1 className="text-2xl font-black text-slate-900">
                            Industry Partners
                        </h1>

                        <p className="mt-1 text-sm text-slate-500">
                            Manage DIGESTEX Strategic Solution Partners
                        </p>
                    </div>
                </div>
            </div>

            {/* STATS */}
            <div className="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <StatCard
                    label="Total Partners"
                    value={stats.total}
                    icon={Building2}
                />

                <StatCard
                    label="Active"
                    value={stats.active}
                    icon={CheckCircle2}
                />

                <StatCard
                    label="Inactive"
                    value={stats.inactive}
                    icon={XCircle}
                />

                <StatCard label="Featured" value={stats.featured} icon={Star} />
            </div>

            {/* SEARCH */}
            <div className="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <form
                    onSubmit={submitSearch}
                    className="flex flex-col gap-3 md:flex-row"
                >
                    <div className="relative flex-1">
                        <Search className="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />

                        <input
                            type="text"
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                            placeholder="Search company or category..."
                            className="
                                w-full
                                rounded-xl
                                border
                                border-slate-200
                                bg-slate-50
                                py-3
                                pl-12
                                pr-4
                                text-sm
                                outline-none
                                transition
                                focus:border-amber-400
                                focus:bg-white
                            "
                        />
                    </div>

                    <button
                        type="submit"
                        className="
                            inline-flex
                            items-center
                            justify-center
                            gap-2
                            rounded-xl
                            bg-slate-900
                            px-6
                            py-3
                            text-sm
                            font-bold
                            text-white
                            transition
                            hover:bg-slate-800
                        "
                    >
                        <Search className="h-4 w-4" />
                        Search
                    </button>
                </form>
            </div>

            {/* TABLE */}
            <div className="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div className="overflow-x-auto">
                    <table className="min-w-full">
                        <thead className="border-b border-slate-200 bg-slate-50">
                            <tr>
                                <th className="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-slate-500">
                                    Partner
                                </th>

                                <th className="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-slate-500">
                                    Category
                                </th>

                                <th className="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-slate-500">
                                    Level
                                </th>

                                <th className="px-6 py-4 text-center text-xs font-black uppercase tracking-wider text-slate-500">
                                    Solutions
                                </th>

                                <th className="px-6 py-4 text-center text-xs font-black uppercase tracking-wider text-slate-500">
                                    Status
                                </th>

                                <th className="px-6 py-4 text-right text-xs font-black uppercase tracking-wider text-slate-500">
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        <tbody className="divide-y divide-slate-100">
                            {partners.data?.length > 0 ? (
                                partners.data.map((partner) => (
                                    <tr
                                        key={partner.id}
                                        className="transition hover:bg-slate-50"
                                    >
                                        {/* PARTNER */}
                                        <td className="px-6 py-5">
                                            <div className="flex items-center gap-4">
                                                <div className="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-slate-100">
                                                    {partner.logo_url ? (
                                                        <img
                                                            src={
                                                                partner.logo_url
                                                            }
                                                            alt={
                                                                partner.company_name
                                                            }
                                                            className="h-full w-full object-contain"
                                                        />
                                                    ) : (
                                                        <Building2 className="h-5 w-5 text-slate-400" />
                                                    )}
                                                </div>

                                                <div>
                                                    <div className="font-bold text-slate-900">
                                                        {partner.company_name}
                                                    </div>

                                                    <div className="mt-1 text-xs text-slate-500">
                                                        ID #{partner.id}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        {/* CATEGORY */}
                                        <td className="px-6 py-5">
                                            <div className="flex items-center gap-2 text-sm text-slate-700">
                                                <Layers3 className="h-4 w-4 text-slate-400" />

                                                {partner.category_label ||
                                                    partner.partner_category}
                                            </div>
                                        </td>

                                        {/* LEVEL */}
                                        <td className="px-6 py-5">
                                            <span className="rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">
                                                {partner.partner_level_label ||
                                                    partner.partner_level}
                                            </span>
                                        </td>

                                        {/* SOLUTIONS */}
                                        <td className="px-6 py-5 text-center">
                                            <span className="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">
                                                <Settings2 className="h-3.5 w-3.5" />

                                                {partner.solutions_count ?? 0}
                                            </span>
                                        </td>

                                        {/* STATUS */}
                                        <td className="px-6 py-5 text-center">
                                            {partner.is_active ? (
                                                <span className="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                                                    <CheckCircle2 className="h-3.5 w-3.5" />
                                                    Active
                                                </span>
                                            ) : (
                                                <span className="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">
                                                    <XCircle className="h-3.5 w-3.5" />
                                                    Inactive
                                                </span>
                                            )}
                                        </td>

                                        {/* ACTIONS */}
                                        <td className="px-6 py-5">
                                            <div className="flex justify-end gap-2">
                                                <Link
                                                    href={route(
                                                        "admin.industry-partners.edit",
                                                        partner.id,
                                                    )}
                                                    className="
                                                        inline-flex
                                                        items-center
                                                        gap-2
                                                        rounded-lg
                                                        border
                                                        border-slate-200
                                                        px-3
                                                        py-2
                                                        text-xs
                                                        font-bold
                                                        text-slate-700
                                                        transition
                                                        hover:bg-slate-50
                                                    "
                                                >
                                                    <Pencil className="h-3.5 w-3.5" />
                                                    Edit
                                                </Link>

                                                <Link
                                                    href={route(
                                                        "admin.industry-partner-solutions.index",
                                                        partner.id,
                                                    )}
                                                    className="
                                                        inline-flex
                                                        items-center
                                                        gap-2
                                                        rounded-lg
                                                        bg-slate-900
                                                        px-3
                                                        py-2
                                                        text-xs
                                                        font-bold
                                                        text-white
                                                        transition
                                                        hover:bg-slate-800
                                                    "
                                                >
                                                    <Settings2 className="h-3.5 w-3.5" />
                                                    Solutions
                                                </Link>
                                            </div>
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td
                                        colSpan="6"
                                        className="px-6 py-16 text-center"
                                    >
                                        <Building2 className="mx-auto h-10 w-10 text-slate-300" />

                                        <div className="mt-4 font-bold text-slate-700">
                                            No Industry Partners Found
                                        </div>

                                        <p className="mt-1 text-sm text-slate-400">
                                            Try another search or review
                                            approved partner inquiries.
                                        </p>
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>

            {/* PAGINATION */}
            {partners.links?.length > 3 && (
                <div className="flex flex-wrap gap-2">
                    {partners.links.map((link, index) => (
                        <Link
                            key={index}
                            href={link.url || "#"}
                            preserveState
                            className={`
                                rounded-lg
                                px-3
                                py-2
                                text-xs
                                font-bold
                                ${
                                    link.active
                                        ? "bg-slate-900 text-white"
                                        : "bg-white text-slate-600 border border-slate-200"
                                }
                                ${
                                    !link.url
                                        ? "pointer-events-none opacity-40"
                                        : "hover:bg-slate-50"
                                }
                            `}
                            dangerouslySetInnerHTML={{
                                __html: link.label,
                            }}
                        />
                    ))}
                </div>
            )}
        </div>
    );
}

/* =========================================================
   STAT CARD
========================================================= */

function StatCard({ label, value, icon: Icon }) {
    return (
        <div className="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div className="flex items-center justify-between">
                <div>
                    <div className="text-xs font-black uppercase tracking-wider text-slate-400">
                        {label}
                    </div>

                    <div className="mt-2 text-3xl font-black text-slate-900">
                        {value ?? 0}
                    </div>
                </div>

                <div className="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100">
                    <Icon className="h-5 w-5 text-slate-600" />
                </div>
            </div>
        </div>
    );
}
