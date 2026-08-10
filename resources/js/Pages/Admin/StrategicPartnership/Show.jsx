import React, { useState } from "react";

import {
    ArrowLeft,
    Building2,
    CalendarDays,
    CheckCircle2,
    ChevronDown,
    Clock3,
    ExternalLink,
    FileText,
    Globe2,
    Handshake,
    Mail,
    MapPin,
    MessageSquare,
    Phone,
    ShieldCheck,
    Sparkles,
    Target,
    UserRound,
    XCircle,
} from "lucide-react";

import { Head, Link, router, usePage } from "@inertiajs/react";

export default function Show() {
    const { inquiry, locale } = usePage().props;

    const isEn = locale === "en";

    /*
    |--------------------------------------------------------------------------
    | STATE
    |--------------------------------------------------------------------------
    */

    const [status, setStatus] = useState(inquiry?.status || "pending");

    const [adminNotes, setAdminNotes] = useState(inquiry?.admin_notes || "");

    const [saving, setSaving] = useState(false);

    const [approving, setApproving] = useState(false);

    /*
    |--------------------------------------------------------------------------
    | STATUS OPTIONS
    |--------------------------------------------------------------------------
    */

    const statusOptions = [
        {
            value: "pending",
            label: isEn ? "Pending" : "Menunggu Review",
        },

        {
            value: "reviewing",
            label: isEn ? "Reviewing" : "Sedang Direview",
        },

        {
            value: "contacted",
            label: isEn ? "Contacted" : "Sudah Dihubungi",
        },

        {
            value: "approved",
            label: isEn ? "Approved" : "Disetujui",
        },

        {
            value: "rejected",
            label: isEn ? "Rejected" : "Ditolak",
        },
    ];

    /*
    |--------------------------------------------------------------------------
    | CATEGORY
    |--------------------------------------------------------------------------
    */

    const categoryLabels = {
        machinery: isEn
            ? "Textile & Garment Machinery"
            : "Mesin Tekstil & Garmen",

        testing_certification: "Testing & Certification",

        energy: isEn ? "Energy & Utilities" : "Energi & Utilities",

        logistics: isEn
            ? "Logistics & Supply Chain"
            : "Logistik & Supply Chain",

        erp_plm: "ERP & PLM",

        ai_digital: isEn
            ? "AI & Digital Transformation"
            : "AI & Transformasi Digital",

        digital_printing: isEn
            ? "Digital Textile Printing"
            : "Digital Textile Printing",

        sustainability: isEn
            ? "Sustainability & Circularity"
            : "Sustainability & Circularity",

        raw_material: isEn
            ? "Raw Materials & Textile Chemicals"
            : "Bahan Baku & Bahan Kimia Tekstil",

        finance: isEn
            ? "Trade Finance & Insurance"
            : "Trade Finance & Insurance",

        association: isEn
            ? "Exhibition & Event Organizers"
            : "Penyelenggara Pameran & Event",

        institution: isEn
            ? "Industry Research & Education"
            : "Riset & Pendidikan Industri",
    };

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    const categoryLabel =
        categoryLabels[inquiry?.partner_category] ||
        inquiry?.partner_category ||
        "Industry Solution";

    const formatDate = (value) => {
        if (!value) {
            return "-";
        }

        return new Intl.DateTimeFormat(isEn ? "en-GB" : "id-ID", {
            day: "2-digit",
            month: "short",
            year: "numeric",
            hour: "2-digit",
            minute: "2-digit",
        }).format(new Date(value));
    };

    const statusConfig = {
        pending: {
            label: isEn ? "Pending" : "Pending",
            className: "border-amber-200 bg-amber-50 text-amber-700",
            icon: Clock3,
        },

        reviewing: {
            label: isEn ? "Reviewing" : "Reviewing",
            className: "border-blue-200 bg-blue-50 text-blue-700",
            icon: FileText,
        },

        contacted: {
            label: isEn ? "Contacted" : "Contacted",
            className: "border-violet-200 bg-violet-50 text-violet-700",
            icon: MessageSquare,
        },

        approved: {
            label: isEn ? "Approved" : "Approved",
            className: "border-emerald-200 bg-emerald-50 text-emerald-700",
            icon: CheckCircle2,
        },

        rejected: {
            label: isEn ? "Rejected" : "Rejected",
            className: "border-red-200 bg-red-50 text-red-700",
            icon: XCircle,
        },
    };

    const currentStatus = statusConfig[inquiry?.status] || statusConfig.pending;

    const CurrentStatusIcon = currentStatus.icon;

    /*
    |--------------------------------------------------------------------------
    | SAVE REVIEW
    |--------------------------------------------------------------------------
    */

    const saveReview = () => {
        setSaving(true);

        router.patch(
            route("admin.strategic-partnerships.status", inquiry.id),
            {
                status,
                admin_notes: adminNotes,
            },
            {
                preserveScroll: true,

                onFinish: () => {
                    setSaving(false);
                },
            },
        );
    };

    /*
    |--------------------------------------------------------------------------
    | APPROVE
    |--------------------------------------------------------------------------
    */

    const approvePartner = () => {
        if (
            !window.confirm(
                isEn
                    ? "Approve this inquiry as a Strategic Partner? The partner profile will be created but will NOT be published automatically."
                    : "Setujui inquiry ini sebagai Strategic Partner? Profile partner akan dibuat tetapi TIDAK langsung dipublikasikan.",
            )
        ) {
            return;
        }

        setApproving(true);

        router.post(
            route("admin.strategic-partnerships.approve", inquiry.id),
            {},
            {
                preserveScroll: true,

                onFinish: () => {
                    setApproving(false);
                },
            },
        );
    };

    /*
    |--------------------------------------------------------------------------
    | BACK
    |--------------------------------------------------------------------------
    */

    const backToIndex = () => {
        router.get(route("admin.strategic-partnerships.index"));
    };

    /*
    |--------------------------------------------------------------------------
    | RENDER
    |--------------------------------------------------------------------------
    */

    return (
        <>
            <Head
                title={
                    isEn
                        ? "Strategic Partnership Review"
                        : "Review Strategic Partnership"
                }
            />

            <div className="min-h-screen bg-slate-50">
                {/* =====================================================
                    HEADER
                ===================================================== */}

                <header className="border-b border-slate-200 bg-white">
                    <div className="mx-auto max-w-7xl px-6 py-6">
                        <div className="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <button
                                    type="button"
                                    onClick={backToIndex}
                                    className="
                                        inline-flex
                                        items-center
                                        gap-2
                                        text-sm
                                        font-bold
                                        text-slate-500
                                        transition
                                        hover:text-slate-900
                                    "
                                >
                                    <ArrowLeft className="h-4 w-4" />

                                    {isEn
                                        ? "Back to Inquiries"
                                        : "Kembali ke Inquiries"}
                                </button>

                                <div className="mt-5 flex items-start gap-4">
                                    <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-slate-950">
                                        <Sparkles className="h-5 w-5 text-amber-300" />
                                    </div>

                                    <div>
                                        <p className="text-xs font-black uppercase tracking-[0.2em] text-emerald-600">
                                            DIGESTEX ADMIN
                                        </p>

                                        <h1 className="mt-1 text-2xl font-black text-slate-950 sm:text-3xl">
                                            {isEn
                                                ? "Strategic Partnership Review"
                                                : "Review Strategic Partnership"}
                                        </h1>
                                    </div>
                                </div>
                            </div>

                            {/* Status */}

                            <StatusBadge
                                status={inquiry?.status}
                                config={statusConfig}
                            />
                        </div>
                    </div>
                </header>

                <main className="mx-auto max-w-7xl space-y-6 px-6 py-7">
                    {/* =================================================
                        COMPANY HERO
                    ================================================= */}

                    <section className="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                        <div className="bg-gradient-to-r from-slate-950 via-indigo-950 to-slate-950 p-7 text-white sm:p-8">
                            <div className="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                                <div className="flex items-start gap-5">
                                    <div className="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-white/10 ring-1 ring-white/10">
                                        <Building2 className="h-7 w-7 text-white" />
                                    </div>

                                    <div>
                                        <p className="text-xs font-bold uppercase tracking-[0.2em] text-emerald-300">
                                            {isEn
                                                ? "Strategic Partnership Inquiry"
                                                : "Strategic Partnership Inquiry"}
                                        </p>

                                        <h2 className="mt-2 text-3xl font-black">
                                            {inquiry?.company_name}
                                        </h2>

                                        <div className="mt-3 flex flex-wrap items-center gap-2">
                                            <span className="inline-flex items-center gap-2 rounded-full border border-amber-400/20 bg-amber-400/10 px-3 py-1.5 text-xs font-bold text-amber-300">
                                                <Sparkles className="h-3.5 w-3.5" />

                                                {categoryLabel}
                                            </span>

                                            <span className="text-sm text-slate-400">
                                                #{inquiry?.id}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {inquiry?.website_url && (
                                    <a
                                        href={inquiry.website_url}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="
                                            inline-flex
                                            items-center
                                            justify-center
                                            gap-2
                                            rounded-xl
                                            border
                                            border-white/10
                                            bg-white/5
                                            px-4
                                            py-3
                                            text-sm
                                            font-bold
                                            text-white
                                            transition
                                            hover:bg-white/10
                                        "
                                    >
                                        <Globe2 className="h-4 w-4" />
                                        Website
                                        <ExternalLink className="h-3.5 w-3.5" />
                                    </a>
                                )}
                            </div>
                        </div>

                        {/* Submission meta */}

                        <div className="grid divide-y border-t border-slate-200 sm:grid-cols-3 sm:divide-x sm:divide-y-0">
                            <MetaItem
                                icon={CalendarDays}
                                label={isEn ? "Submitted" : "Dikirim"}
                                value={formatDate(inquiry?.created_at)}
                            />

                            <MetaItem
                                icon={MapPin}
                                label={isEn ? "Source" : "Sumber"}
                                value={
                                    inquiry?.source || "strategic_partnership"
                                }
                            />

                            <MetaItem
                                icon={Globe2}
                                label="Locale"
                                value={inquiry?.locale || "id"}
                            />
                        </div>
                    </section>

                    <div className="grid gap-6 xl:grid-cols-[1fr_360px]">
                        {/* =================================================
                            LEFT CONTENT
                        ================================================= */}

                        <div className="space-y-6">
                            {/* COMPANY + CONTACT */}

                            <div className="grid gap-6 lg:grid-cols-2">
                                <InfoSection
                                    title={
                                        isEn
                                            ? "Company Information"
                                            : "Informasi Perusahaan"
                                    }
                                    icon={Building2}
                                >
                                    <InfoField
                                        label={
                                            isEn
                                                ? "Company Name"
                                                : "Nama Perusahaan"
                                        }
                                        value={inquiry?.company_name}
                                    />

                                    <InfoField
                                        label="Website"
                                        value={inquiry?.website_url}
                                        isLink
                                    />
                                </InfoSection>

                                <InfoSection
                                    title={isEn ? "Contact Person" : "Kontak"}
                                    icon={UserRound}
                                >
                                    <InfoField
                                        label={isEn ? "Name" : "Nama"}
                                        value={inquiry?.contact_name}
                                    />

                                    <InfoField
                                        label={isEn ? "Job Title" : "Jabatan"}
                                        value={inquiry?.job_title}
                                    />

                                    <InfoField
                                        label="Email"
                                        value={inquiry?.email}
                                        icon={Mail}
                                        isLink
                                    />

                                    <InfoField
                                        label={isEn ? "Phone" : "Telepon"}
                                        value={inquiry?.phone}
                                        icon={Phone}
                                    />
                                </InfoSection>
                            </div>

                            {/* SOLUTION */}

                            <InfoSection
                                title={
                                    isEn
                                        ? "Solution Description"
                                        : "Deskripsi Solusi"
                                }
                                icon={Sparkles}
                                large
                            >
                                <div className="rounded-2xl bg-slate-50 p-5">
                                    <p className="whitespace-pre-line text-sm leading-7 text-slate-700">
                                        {inquiry?.solution_description || "-"}
                                    </p>
                                </div>
                            </InfoSection>

                            {/* PARTNERSHIP INTEREST */}

                            <InfoSection
                                title={
                                    isEn
                                        ? "Partnership Interest"
                                        : "Minat Kemitraan"
                                }
                                icon={Handshake}
                                large
                            >
                                <ContentBox
                                    value={inquiry?.partnership_interest}
                                    emptyText={
                                        isEn
                                            ? "No partnership interest details provided."
                                            : "Belum ada informasi minat kemitraan."
                                    }
                                />
                            </InfoSection>

                            {/* TARGET MARKET */}

                            <InfoSection
                                title={isEn ? "Target Market" : "Target Market"}
                                icon={Target}
                                large
                            >
                                <ContentBox
                                    value={inquiry?.target_market}
                                    emptyText={
                                        isEn
                                            ? "No target market information provided."
                                            : "Belum ada informasi target market."
                                    }
                                />
                            </InfoSection>

                            {/* PROPOSED VALUE */}

                            <InfoSection
                                title={
                                    isEn
                                        ? "Proposed Strategic Value"
                                        : "Nilai Strategis yang Ditawarkan"
                                }
                                icon={ShieldCheck}
                                large
                            >
                                <ContentBox
                                    value={inquiry?.proposed_value}
                                    emptyText={
                                        isEn
                                            ? "No proposed strategic value provided."
                                            : "Belum ada informasi nilai strategis."
                                    }
                                />
                            </InfoSection>
                        </div>

                        {/* =================================================
                            RIGHT SIDEBAR
                        ================================================= */}

                        <aside className="space-y-6">
                            {/* STATUS */}

                            <section className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                                <div className="flex items-center gap-3">
                                    <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100">
                                        <FileText className="h-5 w-5 text-slate-600" />
                                    </div>

                                    <div>
                                        <h2 className="font-black text-slate-950">
                                            {isEn
                                                ? "Admin Review"
                                                : "Review Admin"}
                                        </h2>

                                        <p className="text-xs text-slate-500">
                                            {isEn
                                                ? "Manage inquiry workflow"
                                                : "Kelola workflow inquiry"}
                                        </p>
                                    </div>
                                </div>

                                {/* Status Select */}

                                <div className="mt-6">
                                    <label className="mb-2 block text-xs font-black uppercase tracking-wider text-slate-400">
                                        {isEn ? "Status" : "Status"}
                                    </label>

                                    <div className="relative">
                                        <select
                                            value={status}
                                            onChange={(event) =>
                                                setStatus(event.target.value)
                                            }
                                            className="
                                                w-full
                                                appearance-none
                                                rounded-xl
                                                border
                                                border-slate-200
                                                bg-slate-50
                                                px-4
                                                py-3
                                                pr-10
                                                text-sm
                                                font-bold
                                                text-slate-700
                                                outline-none
                                                transition
                                                focus:border-emerald-500
                                                focus:bg-white
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

                                        <ChevronDown className="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                                    </div>
                                </div>

                                {/* Admin Notes */}

                                <div className="mt-5">
                                    <label className="mb-2 block text-xs font-black uppercase tracking-wider text-slate-400">
                                        {isEn ? "Admin Notes" : "Catatan Admin"}
                                    </label>

                                    <textarea
                                        value={adminNotes}
                                        onChange={(event) =>
                                            setAdminNotes(event.target.value)
                                        }
                                        rows={6}
                                        placeholder={
                                            isEn
                                                ? "Add internal review notes..."
                                                : "Tambahkan catatan review internal..."
                                        }
                                        className="
                                            w-full
                                            resize-none
                                            rounded-xl
                                            border
                                            border-slate-200
                                            bg-slate-50
                                            px-4
                                            py-3
                                            text-sm
                                            leading-6
                                            text-slate-700
                                            outline-none
                                            transition
                                            placeholder:text-slate-400
                                            focus:border-emerald-500
                                            focus:bg-white
                                            focus:ring-2
                                            focus:ring-emerald-500/10
                                        "
                                    />
                                </div>

                                {/* Save */}

                                <button
                                    type="button"
                                    onClick={saveReview}
                                    disabled={saving}
                                    className="
                                        mt-4
                                        inline-flex
                                        w-full
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
                                        disabled:cursor-not-allowed
                                        disabled:opacity-50
                                    "
                                >
                                    <CheckCircle2 className="h-4 w-4" />

                                    {saving
                                        ? isEn
                                            ? "SAVING..."
                                            : "MENYIMPAN..."
                                        : isEn
                                          ? "SAVE REVIEW"
                                          : "SIMPAN REVIEW"}
                                </button>
                            </section>

                            {/* APPROVAL */}

                            <section className="overflow-hidden rounded-3xl border border-emerald-200 bg-white shadow-sm">
                                <div className="bg-gradient-to-br from-emerald-950 to-slate-950 p-6 text-white">
                                    <div className="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-400/10">
                                        <ShieldCheck className="h-5 w-5 text-emerald-300" />
                                    </div>

                                    <h2 className="mt-5 text-lg font-black">
                                        {isEn
                                            ? "Strategic Partner Approval"
                                            : "Persetujuan Strategic Partner"}
                                    </h2>

                                    <p className="mt-2 text-sm leading-6 text-slate-400">
                                        {isEn
                                            ? "Approve this qualified inquiry and create an Industry Partner profile."
                                            : "Setujui inquiry yang telah memenuhi kualifikasi dan buat profile Industry Partner."}
                                    </p>
                                </div>

                                <div className="p-6">
                                    <div className="rounded-2xl bg-slate-50 p-4">
                                        <div className="flex items-start gap-3">
                                            <CheckCircle2 className="mt-0.5 h-5 w-5 shrink-0 text-emerald-500" />

                                            <div>
                                                <p className="text-sm font-bold text-slate-800">
                                                    {isEn
                                                        ? "Approved does not mean Published"
                                                        : "Approved tidak berarti Published"}
                                                </p>

                                                <p className="mt-1 text-xs leading-5 text-slate-500">
                                                    {isEn
                                                        ? "The partner profile will be created as inactive. Admin can complete and publish it later."
                                                        : "Profile partner akan dibuat sebagai inactive. Admin dapat melengkapi dan mempublikasikannya kemudian."}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <button
                                        type="button"
                                        onClick={approvePartner}
                                        disabled={
                                            approving ||
                                            inquiry?.status === "approved"
                                        }
                                        className="
                                            mt-5
                                            inline-flex
                                            w-full
                                            items-center
                                            justify-center
                                            gap-2
                                            rounded-xl
                                            bg-emerald-500
                                            px-5
                                            py-3.5
                                            text-sm
                                            font-black
                                            text-white
                                            transition
                                            hover:bg-emerald-600
                                            disabled:cursor-not-allowed
                                            disabled:opacity-50
                                        "
                                    >
                                        <ShieldCheck className="h-4 w-4" />

                                        {inquiry?.status === "approved"
                                            ? isEn
                                                ? "ALREADY APPROVED"
                                                : "SUDAH DISETUJUI"
                                            : approving
                                              ? isEn
                                                  ? "APPROVING..."
                                                  : "MEMPROSES..."
                                              : isEn
                                                ? "APPROVE AS STRATEGIC PARTNER"
                                                : "SETUJUI SEBAGAI STRATEGIC PARTNER"}
                                    </button>
                                </div>
                            </section>

                            {/* CONTACT */}

                            {(inquiry?.email || inquiry?.phone) && (
                                <section className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                                    <h2 className="font-black text-slate-950">
                                        {isEn ? "Contact" : "Kontak"}
                                    </h2>

                                    <div className="mt-4 space-y-3">
                                        {inquiry.email && (
                                            <a
                                                href={`mailto:${inquiry.email}`}
                                                className="flex items-center gap-3 rounded-xl bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
                                            >
                                                <Mail className="h-4 w-4 text-slate-400" />

                                                <span className="truncate">
                                                    {inquiry.email}
                                                </span>
                                            </a>
                                        )}

                                        {inquiry.phone && (
                                            <a
                                                href={`tel:${inquiry.phone}`}
                                                className="flex items-center gap-3 rounded-xl bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
                                            >
                                                <Phone className="h-4 w-4 text-slate-400" />

                                                {inquiry.phone}
                                            </a>
                                        )}
                                    </div>
                                </section>
                            )}
                        </aside>
                    </div>
                </main>
            </div>
        </>
    );
}

/*
|--------------------------------------------------------------------------
| INFO SECTION
|--------------------------------------------------------------------------
*/

function InfoSection({ title, icon: Icon, children, large = false }) {
    return (
        <section
            className={`
                rounded-3xl
                border
                border-slate-200
                bg-white
                p-6
                shadow-sm
                ${large ? "" : ""}
            `}
        >
            <div className="flex items-center gap-3">
                <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100">
                    <Icon className="h-5 w-5 text-slate-600" />
                </div>

                <h2 className="font-black text-slate-950">{title}</h2>
            </div>

            <div className="mt-5 space-y-4">{children}</div>
        </section>
    );
}

/*
|--------------------------------------------------------------------------
| INFO FIELD
|--------------------------------------------------------------------------
*/

function InfoField({ label, value, icon: Icon, isLink = false }) {
    if (!value) {
        return null;
    }

    return (
        <div>
            <p className="text-xs font-bold uppercase tracking-wider text-slate-400">
                {label}
            </p>

            {isLink ? (
                <a
                    href={label === "Email" ? `mailto:${value}` : value}
                    target={label === "Email" ? undefined : "_blank"}
                    rel={label === "Email" ? undefined : "noopener noreferrer"}
                    className="mt-1 flex items-center gap-2 break-all text-sm font-semibold text-emerald-600 hover:text-emerald-700"
                >
                    {Icon && <Icon className="h-4 w-4 shrink-0" />}

                    {value}

                    {label !== "Email" && (
                        <ExternalLink className="h-3.5 w-3.5 shrink-0" />
                    )}
                </a>
            ) : (
                <p className="mt-1 flex items-center gap-2 text-sm font-semibold text-slate-800">
                    {Icon && (
                        <Icon className="h-4 w-4 shrink-0 text-slate-400" />
                    )}

                    {value}
                </p>
            )}
        </div>
    );
}

/*
|--------------------------------------------------------------------------
| CONTENT BOX
|--------------------------------------------------------------------------
*/

function ContentBox({ value, emptyText }) {
    return (
        <div className="rounded-2xl bg-slate-50 p-5">
            {value ? (
                <p className="whitespace-pre-line text-sm leading-7 text-slate-700">
                    {value}
                </p>
            ) : (
                <p className="text-sm italic text-slate-400">{emptyText}</p>
            )}
        </div>
    );
}

/*
|--------------------------------------------------------------------------
| META ITEM
|--------------------------------------------------------------------------
*/

function MetaItem({ icon: Icon, label, value }) {
    return (
        <div className="flex items-center gap-3 px-6 py-4">
            <Icon className="h-4 w-4 shrink-0 text-slate-400" />

            <div className="min-w-0">
                <p className="text-[10px] font-black uppercase tracking-wider text-slate-400">
                    {label}
                </p>

                <p className="mt-0.5 truncate text-xs font-bold text-slate-700">
                    {value}
                </p>
            </div>
        </div>
    );
}

/*
|--------------------------------------------------------------------------
| STATUS BADGE
|--------------------------------------------------------------------------
*/

function StatusBadge({ status, config }) {
    const item = config[status] || config.pending;

    const Icon = item.icon;

    return (
        <div
            className={`
                inline-flex
                items-center
                gap-2
                rounded-full
                border
                px-4
                py-2
                text-xs
                font-black
                ${item.className}
            `}
        >
            <Icon className="h-4 w-4" />

            {item.label}
        </div>
    );
}
