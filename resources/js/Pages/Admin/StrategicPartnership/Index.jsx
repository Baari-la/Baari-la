import React from "react";

import { Head, Link, router, usePage } from "@inertiajs/react";

import {
    ArrowRight,
    BarChart3,
    Building2,
    CalendarDays,
    CheckCircle2,
    Clock3,
    Eye,
    FileSearch,
    Filter,
    Mail,
    Search,
    ShieldCheck,
    Sparkles,
    Users,
    XCircle,
} from "lucide-react";

export default function Index() {
    const { inquiries, stats = {}, filters = {} } = usePage().props;

    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    const statusOptions = [
        {
            value: "",
            label: "All Status",
        },
        {
            value: "pending",
            label: "Pending",
        },
        {
            value: "reviewing",
            label: "Reviewing",
        },
        {
            value: "contacted",
            label: "Contacted",
        },
        {
            value: "approved",
            label: "Approved",
        },
        {
            value: "rejected",
            label: "Rejected",
        },
    ];

    /*
    |--------------------------------------------------------------------------
    | CATEGORY
    |--------------------------------------------------------------------------
    */

    const categoryOptions = [
        {
            value: "",
            label: "All Categories",
        },
        {
            value: "machinery",
            label: "Machinery",
        },
        {
            value: "testing_certification",
            label: "Testing & Certification",
        },
        {
            value: "energy",
            label: "Energy & Utilities",
        },
        {
            value: "logistics",
            label: "Logistics & Supply Chain",
        },
        {
            value: "erp_plm",
            label: "ERP & PLM",
        },
        {
            value: "ai_digital",
            label: "AI & Digital Transformation",
        },
        {
            value: "digital_printing",
            label: "Digital Textile Printing",
        },
        {
            value: "sustainability",
            label: "Sustainability & Circularity",
        },
        {
            value: "raw_material",
            label: "Raw Materials & Chemicals",
        },
        {
            value: "finance",
            label: "Trade Finance & Insurance",
        },
        {
            value: "association",
            label: "Exhibition & Event Organizer",
        },
        {
            value: "institution",
            label: "Research & Education",
        },
    ];

    /*
    |--------------------------------------------------------------------------
    | FILTER
    |--------------------------------------------------------------------------
    */

    const applyFilters = (updates = {}) => {
        router.get(
            route("admin.strategic-partnerships.index"),
            {
                search: updates.search ?? filters.search ?? "",

                status: updates.status ?? filters.status ?? "",

                category: updates.category ?? filters.category ?? "",
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            },
        );
    };

    const handleSearch = (event) => {
        event.preventDefault();

        const formData = new FormData(event.currentTarget);

        applyFilters({
            search: formData.get("search") || "",
        });
    };

    const clearFilters = () => {
        router.get(
            route("admin.strategic-partnerships.index"),
            {},
            {
                preserveState: false,
                preserveScroll: true,
                replace: true,
            },
        );
    };

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    const formatCategory = (value) => {
        const item = categoryOptions.find(
            (category) => category.value === value,
        );

        return item?.label || value || "Industry Solution";
    };

    const formatDate = (value) => {
        if (!value) {
            return "-";
        }

        return new Intl.DateTimeFormat("en-GB", {
            day: "2-digit",
            month: "short",
            year: "numeric",
        }).format(new Date(value));
    };

    const hasFilters =
        Boolean(filters.search) ||
        Boolean(filters.status) ||
        Boolean(filters.category);

    return (
        <>
            <Head title="Strategic Partnership" />

            <div className="min-h-screen bg-slate-50">
                {/* =====================================================
                    HEADER
                ===================================================== */}

                <div className="border-b border-slate-200 bg-white">
                    <div className="mx-auto max-w-7xl px-6 py-7">
                        <div className="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <div className="flex items-center gap-3">
                                    <div className="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-950">
                                        <Sparkles className="h-5 w-5 text-amber-300" />
                                    </div>

                                    <div>
                                        <p className="text-xs font-black uppercase tracking-[0.2em] text-emerald-600">
                                            DIGESTEX ADMIN
                                        </p>

                                        <h1 className="mt-1 text-2xl font-black text-slate-950 sm:text-3xl">
                                            Strategic Partnership
                                        </h1>
                                    </div>
                                </div>

                                <p className="mt-3 max-w-2xl text-sm leading-6 text-slate-500">
                                    Manage Strategic Solution Partner inquiries,
                                    review partnership opportunities, and
                                    approve qualified industry solution
                                    providers.
                                </p>
                            </div>

                            <div className="flex items-center gap-3">
                                <Link
                                    href={route("strategic-partnership.create")}
                                    target="_blank"
                                    className="
                                        inline-flex
                                        items-center
                                        gap-2
                                        rounded-xl
                                        border
                                        border-slate-200
                                        bg-white
                                        px-4
                                        py-2.5
                                        text-sm
                                        font-bold
                                        text-slate-700
                                        transition
                                        hover:bg-slate-50
                                    "
                                >
                                    <Eye className="h-4 w-4" />
                                    View Public Inquiry
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <main className="mx-auto max-w-7xl space-y-6 px-6 py-7">
                    {/* =================================================
                        KPI
                    ================================================= */}

                    <div className="grid gap-4 sm:grid-cols-2 xl:grid-cols-6">
                        <StatCard
                            icon={Users}
                            label="Total"
                            value={stats.total ?? 0}
                            description="All inquiries"
                        />

                        <StatCard
                            icon={Clock3}
                            label="Pending"
                            value={stats.pending ?? 0}
                            description="Awaiting review"
                            href={route("admin.strategic-partnerships.index", {
                                status: "pending",
                            })}
                        />

                        <StatCard
                            icon={FileSearch}
                            label="Reviewing"
                            value={stats.reviewing ?? 0}
                            description="Under evaluation"
                            href={route("admin.strategic-partnerships.index", {
                                status: "reviewing",
                            })}
                        />

                        <StatCard
                            icon={Mail}
                            label="Contacted"
                            value={stats.contacted ?? 0}
                            description="Partner contacted"
                            href={route("admin.strategic-partnerships.index", {
                                status: "contacted",
                            })}
                        />

                        <StatCard
                            icon={CheckCircle2}
                            label="Approved"
                            value={stats.approved ?? 0}
                            description="Approved partners"
                            href={route("admin.strategic-partnerships.index", {
                                status: "approved",
                            })}
                        />

                        <StatCard
                            icon={XCircle}
                            label="Rejected"
                            value={stats.rejected ?? 0}
                            description="Not approved"
                            href={route("admin.strategic-partnerships.index", {
                                status: "rejected",
                            })}
                        />
                    </div>

                    {/* =================================================
                        FILTERS
                    ================================================= */}

                    <section className="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div className="mb-5 flex items-center gap-2">
                            <Filter className="h-5 w-5 text-slate-500" />

                            <h2 className="font-black text-slate-900">
                                Inquiry Filters
                            </h2>
                        </div>

                        <form
                            onSubmit={handleSearch}
                            className="grid gap-3 lg:grid-cols-[1fr_210px_240px_auto]"
                        >
                            {/* Search */}

                            <div className="relative">
                                <Search className="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />

                                <input
                                    type="text"
                                    name="search"
                                    defaultValue={filters.search || ""}
                                    placeholder="Search company, contact or email..."
                                    className="
                                        w-full
                                        rounded-xl
                                        border
                                        border-slate-200
                                        bg-slate-50
                                        py-3
                                        pl-11
                                        pr-4
                                        text-sm
                                        outline-none
                                        transition
                                        focus:border-emerald-500
                                        focus:bg-white
                                        focus:ring-2
                                        focus:ring-emerald-500/10
                                    "
                                />
                            </div>

                            {/* Status */}

                            <select
                                value={filters.status || ""}
                                onChange={(event) =>
                                    applyFilters({
                                        status: event.target.value,
                                    })
                                }
                                className="
                                    rounded-xl
                                    border
                                    border-slate-200
                                    bg-slate-50
                                    px-4
                                    py-3
                                    text-sm
                                    font-medium
                                    text-slate-700
                                    outline-none
                                    focus:border-emerald-500
                                    focus:ring-2
                                    focus:ring-emerald-500/10
                                "
                            >
                                {statusOptions.map((option) => (
                                    <option
                                        key={option.value}
                                        value={option.value}
                                    >
                                        {option.label}
                                    </option>
                                ))}
                            </select>

                            {/* Category */}

                            <select
                                value={filters.category || ""}
                                onChange={(event) =>
                                    applyFilters({
                                        category: event.target.value,
                                    })
                                }
                                className="
                                    rounded-xl
                                    border
                                    border-slate-200
                                    bg-slate-50
                                    px-4
                                    py-3
                                    text-sm
                                    font-medium
                                    text-slate-700
                                    outline-none
                                    focus:border-emerald-500
                                    focus:ring-2
                                    focus:ring-emerald-500/10
                                "
                            >
                                {categoryOptions.map((option) => (
                                    <option
                                        key={option.value}
                                        value={option.value}
                                    >
                                        {option.label}
                                    </option>
                                ))}
                            </select>

                            {/* Search */}

                            <button
                                type="submit"
                                className="
                                    inline-flex
                                    items-center
                                    justify-center
                                    gap-2
                                    rounded-xl
                                    bg-slate-950
                                    px-5
                                    py-3
                                    text-sm
                                    font-black
                                    text-white
                                    transition
                                    hover:bg-slate-800
                                "
                            >
                                <Search className="h-4 w-4" />
                                Search
                            </button>
                        </form>

                        {hasFilters && (
                            <div className="mt-4 flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3">
                                <p className="text-xs font-medium text-slate-500">
                                    Active filters are applied.
                                </p>

                                <button
                                    type="button"
                                    onClick={clearFilters}
                                    className="text-xs font-black text-emerald-600 hover:text-emerald-700"
                                >
                                    Clear Filters
                                </button>
                            </div>
                        )}
                    </section>

                    {/* =================================================
                        TABLE
                    ================================================= */}

                    <section className="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                        <div className="flex flex-col gap-3 border-b border-slate-200 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h2 className="font-black text-slate-950">
                                    Strategic Partnership Inquiries
                                </h2>

                                <p className="mt-1 text-sm text-slate-500">
                                    Review and manage incoming partnership
                                    opportunities.
                                </p>
                            </div>

                            <div className="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700">
                                <BarChart3 className="h-4 w-4" />
                                {inquiries?.total ?? 0} Records
                            </div>
                        </div>

                        <div className="overflow-x-auto">
                            <table className="min-w-full">
                                <thead className="bg-slate-50">
                                    <tr>
                                        <TableHeader>Company</TableHeader>

                                        <TableHeader>
                                            Strategic Solution
                                        </TableHeader>

                                        <TableHeader>Contact</TableHeader>

                                        <TableHeader>Status</TableHeader>

                                        <TableHeader>Submitted</TableHeader>

                                        <TableHeader align="right">
                                            Action
                                        </TableHeader>
                                    </tr>
                                </thead>

                                <tbody className="divide-y divide-slate-100">
                                    {inquiries?.data?.length ? (
                                        inquiries.data.map((inquiry) => (
                                            <InquiryRow
                                                key={inquiry.id}
                                                inquiry={inquiry}
                                                formatCategory={formatCategory}
                                                formatDate={formatDate}
                                            />
                                        ))
                                    ) : (
                                        <EmptyState />
                                    )}
                                </tbody>
                            </table>
                        </div>

                        {/* =================================================
                            PAGINATION
                        ================================================= */}

                        {inquiries?.links && inquiries.links.length > 3 && (
                            <div className="flex flex-wrap items-center justify-center gap-2 border-t border-slate-200 px-6 py-5">
                                {inquiries.links.map((link, index) => {
                                    if (!link.url) {
                                        return (
                                            <span
                                                key={index}
                                                className="rounded-lg px-3 py-2 text-sm text-slate-300"
                                                dangerouslySetInnerHTML={{
                                                    __html: link.label,
                                                }}
                                            />
                                        );
                                    }

                                    return (
                                        <Link
                                            key={index}
                                            href={link.url}
                                            className={`
                                                        rounded-lg
                                                        px-3
                                                        py-2
                                                        text-sm
                                                        font-bold
                                                        transition
                                                        ${
                                                            link.active
                                                                ? "bg-slate-950 text-white"
                                                                : "text-slate-600 hover:bg-slate-100"
                                                        }
                                                    `}
                                            dangerouslySetInnerHTML={{
                                                __html: link.label,
                                            }}
                                        />
                                    );
                                })}
                            </div>
                        )}
                    </section>
                </main>
            </div>
        </>
    );
}

/*
|--------------------------------------------------------------------------
| STAT CARD
|--------------------------------------------------------------------------
*/

function StatCard({ icon: Icon, label, value, description, href }) {
    const content = (
        <div
            className="
                rounded-3xl
                border
                border-slate-200
                bg-white
                p-5
                shadow-sm
                transition
                hover:shadow-md
            "
        >
            <div className="flex items-start justify-between">
                <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100">
                    <Icon className="h-5 w-5 text-slate-700" />
                </div>

                {href && <ArrowRight className="h-4 w-4 text-slate-300" />}
            </div>

            <div className="mt-5">
                <p className="text-xs font-bold uppercase tracking-wider text-slate-400">
                    {label}
                </p>

                <p className="mt-1 text-3xl font-black text-slate-950">
                    {value}
                </p>

                <p className="mt-1 text-xs text-slate-500">{description}</p>
            </div>
        </div>
    );

    if (!href) {
        return content;
    }

    return <Link href={href}>{content}</Link>;
}

/*
|--------------------------------------------------------------------------
| TABLE HEADER
|--------------------------------------------------------------------------
*/

function TableHeader({ children, align = "left" }) {
    return (
        <th
            className={`
                px-6
                py-4
                text-xs
                font-black
                uppercase
                tracking-wider
                text-slate-400
                ${align === "right" ? "text-right" : "text-left"}
            `}
        >
            {children}
        </th>
    );
}

/*
|--------------------------------------------------------------------------
| INQUIRY ROW
|--------------------------------------------------------------------------
*/

function InquiryRow({ inquiry, formatCategory, formatDate }) {
    return (
        <tr className="group transition hover:bg-slate-50">
            {/* Company */}

            <td className="px-6 py-5">
                <div className="flex items-start gap-3">
                    <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100">
                        <Building2 className="h-5 w-5 text-slate-600" />
                    </div>

                    <div className="min-w-0">
                        <p className="font-black text-slate-900">
                            {inquiry.company_name}
                        </p>

                        {inquiry.website_url && (
                            <p className="mt-1 max-w-[220px] truncate text-xs text-slate-400">
                                {inquiry.website_url}
                            </p>
                        )}
                    </div>
                </div>
            </td>

            {/* Category */}

            <td className="px-6 py-5">
                <div className="flex items-center gap-2">
                    <Sparkles className="h-4 w-4 shrink-0 text-amber-500" />

                    <span className="text-sm font-semibold text-slate-700">
                        {formatCategory(inquiry.partner_category)}
                    </span>
                </div>
            </td>

            {/* Contact */}

            <td className="px-6 py-5">
                <p className="text-sm font-bold text-slate-800">
                    {inquiry.contact_name}
                </p>

                <p className="mt-1 text-xs text-slate-400">{inquiry.email}</p>
            </td>

            {/* Status */}

            <td className="px-6 py-5">
                <StatusBadge status={inquiry.status} />
            </td>

            {/* Date */}

            <td className="px-6 py-5">
                <div className="flex items-center gap-2 text-sm text-slate-500">
                    <CalendarDays className="h-4 w-4" />

                    {formatDate(inquiry.created_at)}
                </div>
            </td>

            {/* Action */}

            <td className="px-6 py-5 text-right">
                <Link
                    href={route(
                        "admin.strategic-partnerships.show",
                        inquiry.id,
                    )}
                    className="
                        inline-flex
                        items-center
                        gap-2
                        rounded-xl
                        border
                        border-slate-200
                        bg-white
                        px-4
                        py-2.5
                        text-xs
                        font-black
                        text-slate-700
                        shadow-sm
                        transition
                        hover:border-slate-300
                        hover:bg-slate-50
                    "
                >
                    <Eye className="h-4 w-4" />
                    REVIEW
                </Link>
            </td>
        </tr>
    );
}

/*
|--------------------------------------------------------------------------
| STATUS BADGE
|--------------------------------------------------------------------------
*/

function StatusBadge({ status }) {
    const config = {
        pending: {
            label: "Pending",
            className: "bg-amber-50 text-amber-700 border-amber-200",
        },

        reviewing: {
            label: "Reviewing",
            className: "bg-blue-50 text-blue-700 border-blue-200",
        },

        contacted: {
            label: "Contacted",
            className: "bg-violet-50 text-violet-700 border-violet-200",
        },

        approved: {
            label: "Approved",
            className: "bg-emerald-50 text-emerald-700 border-emerald-200",
        },

        rejected: {
            label: "Rejected",
            className: "bg-red-50 text-red-700 border-red-200",
        },
    };

    const item = config[status] || {
        label: status || "Unknown",
        className: "bg-slate-50 text-slate-600 border-slate-200",
    };

    return (
        <span
            className={`
                inline-flex
                items-center
                gap-2
                rounded-full
                border
                px-3
                py-1.5
                text-xs
                font-black
                ${item.className}
            `}
        >
            <span className="h-1.5 w-1.5 rounded-full bg-current" />

            {item.label}
        </span>
    );
}

/*
|--------------------------------------------------------------------------
| EMPTY STATE
|--------------------------------------------------------------------------
*/

function EmptyState() {
    return (
        <tr>
            <td colSpan="6" className="px-6 py-16 text-center">
                <div className="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100">
                    <FileSearch className="h-6 w-6 text-slate-400" />
                </div>

                <h3 className="mt-5 font-black text-slate-900">
                    No Strategic Partnership Inquiries
                </h3>

                <p className="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                    No inquiries match the current filters. New Strategic
                    Solution Partner submissions will appear here.
                </p>
            </td>
        </tr>
    );
}
