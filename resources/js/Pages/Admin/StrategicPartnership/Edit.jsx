import React, { useState } from "react";

import {
    ArrowLeft,
    Building2,
    Check,
    CheckCircle2,
    ExternalLink,
    Globe2,
    Image as ImageIcon,
    Save,
    ShieldCheck,
    Sparkles,
    Star,
    Upload,
} from "lucide-react";

import { Head, Link, router, useForm, usePage } from "@inertiajs/react";

export default function Edit() {
    const {
        partner,
        categories = [],
        levels = [],
        completeness = {},
        errors = {},
        flash = {},
    } = usePage().props;

    /*
    |--------------------------------------------------------------------------
    | FORM
    |--------------------------------------------------------------------------
    */

    const {
        data,
        setData,
        patch,
        processing,
        errors: formErrors,
    } = useForm({
        company_name: partner?.company_name || "",

        partner_category: partner?.partner_category || "",

        partner_level: partner?.partner_level || "platinum",

        logo_url: partner?.logo_url || "",

        website_url: partner?.website_url || "",

        short_description: partner?.short_description || "",
    });

    /*
    |--------------------------------------------------------------------------
    | SUBMIT
    |--------------------------------------------------------------------------
    */

    const submit = (event) => {
        event.preventDefault();

        patch(route("admin.industry-partners.update", partner.id), {
            preserveScroll: true,
        });
    };

    /*
    |--------------------------------------------------------------------------
    | PUBLISH
    |--------------------------------------------------------------------------
    */

    const publish = () => {
        if (
            !window.confirm(
                "Publish this Industry Partner to the public DIGESTEX ecosystem?",
            )
        ) {
            return;
        }

        router.post(
            route("admin.industry-partners.publish", partner.id),
            {},
            {
                preserveScroll: true,
            },
        );
    };

    /*
    |--------------------------------------------------------------------------
    | COMPLETENESS
    |--------------------------------------------------------------------------
    */

    const percentage = completeness?.percentage ?? 0;

    const isComplete = percentage >= 100;

    return (
        <>
            <Head title={`Edit Partner — ${partner?.company_name || ""}`} />

            <div className="min-h-screen bg-slate-50">
                {/* =====================================================
                    HEADER
                ===================================================== */}

                <header className="border-b border-slate-200 bg-white">
                    <div className="mx-auto max-w-7xl px-6 py-6">
                        <Link
                            href={route("admin.strategic-partnerships.index")}
                            className="
                                inline-flex
                                items-center
                                gap-2
                                text-sm
                                font-bold
                                text-slate-500
                                hover:text-slate-900
                            "
                        >
                            <ArrowLeft className="h-4 w-4" />
                            Back to Strategic Partnership
                        </Link>

                        <div className="mt-5 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                            <div className="flex items-center gap-4">
                                <div className="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-950">
                                    <ShieldCheck className="h-5 w-5 text-emerald-300" />
                                </div>

                                <div>
                                    <p className="text-xs font-black uppercase tracking-[0.2em] text-emerald-600">
                                        DIGESTEX ADMIN
                                    </p>

                                    <h1 className="mt-1 text-2xl font-black text-slate-950 sm:text-3xl">
                                        Complete Partner Profile
                                    </h1>
                                </div>
                            </div>

                            <div className="flex items-center gap-3">
                                <StatusBadge active={partner?.is_active} />

                                {partner?.is_active ? (
                                    <a
                                        href="#"
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
                                        "
                                    >
                                        <ExternalLink className="h-4 w-4" />
                                        Public Profile
                                    </a>
                                ) : null}
                            </div>
                        </div>
                    </div>
                </header>

                <main className="mx-auto max-w-7xl space-y-6 px-6 py-7">
                    {/* =================================================
                        PARTNER HERO
                    ================================================= */}

                    <section className="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                        <div className="bg-gradient-to-r from-slate-950 via-indigo-950 to-slate-950 p-7 text-white">
                            <div className="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                                <div className="flex items-center gap-5">
                                    <LogoPreview
                                        url={data.logo_url}
                                        name={data.company_name}
                                    />

                                    <div>
                                        <p className="text-xs font-bold uppercase tracking-[0.2em] text-emerald-300">
                                            Strategic Solution Partner
                                        </p>

                                        <h2 className="mt-2 text-3xl font-black">
                                            {data.company_name ||
                                                "Company Name"}
                                        </h2>

                                        <p className="mt-2 text-sm text-slate-400">
                                            {getCategoryLabel(
                                                categories,
                                                data.partner_category,
                                            )}
                                        </p>
                                    </div>
                                </div>

                                <div className="rounded-2xl border border-white/10 bg-white/5 p-5">
                                    <p className="text-xs font-bold uppercase tracking-wider text-slate-400">
                                        Partner Level
                                    </p>

                                    <div className="mt-2 flex items-center gap-2">
                                        <Star className="h-5 w-5 text-amber-300" />

                                        <span className="text-lg font-black">
                                            {getLevelLabel(
                                                levels,
                                                data.partner_level,
                                            )}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div className="grid gap-6 xl:grid-cols-[1fr_350px]">
                        {/* =================================================
                            FORM
                        ================================================= */}

                        <form onSubmit={submit} className="space-y-6">
                            {/* COMPANY */}

                            <section className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                                <SectionHeader
                                    icon={Building2}
                                    title="Company Profile"
                                    description="Core company information displayed in the DIGESTEX ecosystem."
                                />

                                <div className="mt-6 grid gap-5">
                                    <Field
                                        label="Company Name"
                                        required
                                        value={data.company_name}
                                        onChange={(value) =>
                                            setData("company_name", value)
                                        }
                                        error={formErrors.company_name}
                                    />

                                    <div className="grid gap-5 md:grid-cols-2">
                                        <SelectField
                                            label="Strategic Solution Category"
                                            required
                                            value={data.partner_category}
                                            options={categories}
                                            onChange={(value) =>
                                                setData(
                                                    "partner_category",
                                                    value,
                                                )
                                            }
                                            error={formErrors.partner_category}
                                        />

                                        <SelectField
                                            label="Partner Level"
                                            required
                                            value={data.partner_level}
                                            options={levels}
                                            onChange={(value) =>
                                                setData("partner_level", value)
                                            }
                                            error={formErrors.partner_level}
                                        />
                                    </div>

                                    <Field
                                        label="Website"
                                        placeholder="https://company.com"
                                        value={data.website_url}
                                        onChange={(value) =>
                                            setData("website_url", value)
                                        }
                                        error={formErrors.website_url}
                                    />

                                    <Field
                                        label="Logo URL"
                                        placeholder="https://company.com/logo.png"
                                        value={data.logo_url}
                                        onChange={(value) =>
                                            setData("logo_url", value)
                                        }
                                        error={formErrors.logo_url}
                                    />

                                    <TextAreaField
                                        label="Short Description"
                                        required
                                        value={data.short_description}
                                        onChange={(value) =>
                                            setData("short_description", value)
                                        }
                                        error={formErrors.short_description}
                                    />
                                </div>
                            </section>

                            {/* SAVE */}

                            <div className="flex flex-col gap-3 sm:flex-row sm:justify-end">
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="
                                        inline-flex
                                        items-center
                                        justify-center
                                        gap-2
                                        rounded-xl
                                        bg-slate-950
                                        px-6
                                        py-3.5
                                        text-sm
                                        font-black
                                        text-white
                                        transition
                                        hover:bg-slate-800
                                        disabled:opacity-50
                                    "
                                >
                                    <Save className="h-4 w-4" />

                                    {processing ? "SAVING..." : "SAVE PROFILE"}
                                </button>
                            </div>
                        </form>

                        {/* =================================================
                            SIDEBAR
                        ================================================= */}

                        <aside className="space-y-6">
                            {/* COMPLETENESS */}

                            <section className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                                <div className="flex items-center justify-between">
                                    <div>
                                        <p className="text-xs font-black uppercase tracking-wider text-slate-400">
                                            Profile Completeness
                                        </p>

                                        <p className="mt-1 text-3xl font-black text-slate-950">
                                            {percentage}%
                                        </p>
                                    </div>

                                    <div className="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50">
                                        <CheckCircle2 className="h-6 w-6 text-emerald-500" />
                                    </div>
                                </div>

                                <div className="mt-5 h-2 overflow-hidden rounded-full bg-slate-100">
                                    <div
                                        className="h-full rounded-full bg-emerald-500 transition-all"
                                        style={{
                                            width: `${percentage}%`,
                                        }}
                                    />
                                </div>

                                <div className="mt-5 space-y-3">
                                    <CompletenessItem
                                        label="Company Name"
                                        complete={
                                            completeness?.fields?.company_name
                                        }
                                    />

                                    <CompletenessItem
                                        label="Solution Category"
                                        complete={
                                            completeness?.fields
                                                ?.partner_category
                                        }
                                    />

                                    <CompletenessItem
                                        label="Partner Level"
                                        complete={
                                            completeness?.fields?.partner_level
                                        }
                                    />

                                    <CompletenessItem
                                        label="Short Description"
                                        complete={
                                            completeness?.fields
                                                ?.short_description
                                        }
                                    />

                                    <CompletenessItem
                                        label="Website"
                                        complete={
                                            completeness?.fields?.website_url
                                        }
                                    />

                                    <CompletenessItem
                                        label="Logo"
                                        complete={
                                            completeness?.fields?.logo_url
                                        }
                                    />
                                </div>
                            </section>

                            {/* PUBLISH */}

                            <section className="overflow-hidden rounded-3xl border border-emerald-200 bg-white shadow-sm">
                                <div className="bg-gradient-to-br from-emerald-950 to-slate-950 p-6 text-white">
                                    <div className="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-400/10">
                                        <Globe2 className="h-5 w-5 text-emerald-300" />
                                    </div>

                                    <h2 className="mt-5 text-lg font-black">
                                        Public Visibility
                                    </h2>

                                    <p className="mt-2 text-sm leading-6 text-slate-400">
                                        Publish this qualified partner to the
                                        DIGESTEX Industry Solutions ecosystem.
                                    </p>
                                </div>

                                <div className="p-6">
                                    {!isComplete ? (
                                        <div className="rounded-2xl bg-amber-50 p-4">
                                            <p className="text-sm font-bold text-amber-800">
                                                Profile not ready
                                            </p>

                                            <p className="mt-1 text-xs leading-5 text-amber-700">
                                                Complete all required profile
                                                information before publishing.
                                            </p>
                                        </div>
                                    ) : (
                                        <div className="rounded-2xl bg-emerald-50 p-4">
                                            <p className="text-sm font-bold text-emerald-800">
                                                Profile ready
                                            </p>

                                            <p className="mt-1 text-xs leading-5 text-emerald-700">
                                                All required information has
                                                been completed.
                                            </p>
                                        </div>
                                    )}

                                    <button
                                        type="button"
                                        onClick={publish}
                                        disabled={
                                            !isComplete || partner?.is_active
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
                                            disabled:opacity-40
                                        "
                                    >
                                        <Globe2 className="h-4 w-4" />

                                        {partner?.is_active
                                            ? "ALREADY PUBLISHED"
                                            : "VERIFY & PUBLISH"}
                                    </button>
                                </div>
                            </section>

                            {/* FEATURED */}

                            <section className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                                <div className="flex items-center gap-3">
                                    <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50">
                                        <Star className="h-5 w-5 text-amber-500" />
                                    </div>

                                    <div>
                                        <h2 className="font-black text-slate-950">
                                            Visibility
                                        </h2>

                                        <p className="text-xs text-slate-500">
                                            Featured placement
                                        </p>
                                    </div>
                                </div>

                                <div className="mt-5 rounded-2xl bg-slate-50 p-4">
                                    <p className="text-sm font-bold text-slate-700">
                                        {partner?.is_featured
                                            ? "Featured Partner"
                                            : "Standard Partner"}
                                    </p>

                                    <p className="mt-1 text-xs leading-5 text-slate-500">
                                        Featured placement can be managed
                                        separately from public activation.
                                    </p>
                                </div>
                            </section>
                        </aside>
                    </div>
                </main>
            </div>
        </>
    );
}

/*
|--------------------------------------------------------------------------
| SECTION HEADER
|--------------------------------------------------------------------------
*/

function SectionHeader({ icon: Icon, title, description }) {
    return (
        <div className="flex items-start gap-3">
            <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100">
                <Icon className="h-5 w-5 text-slate-600" />
            </div>

            <div>
                <h2 className="font-black text-slate-950">{title}</h2>

                <p className="mt-1 text-xs leading-5 text-slate-500">
                    {description}
                </p>
            </div>
        </div>
    );
}

/*
|--------------------------------------------------------------------------
| FIELD
|--------------------------------------------------------------------------
*/

function Field({
    label,
    required = false,
    value,
    onChange,
    placeholder,
    error,
}) {
    return (
        <div>
            <label className="mb-2 block text-xs font-black uppercase tracking-wider text-slate-400">
                {label}

                {required && <span className="ml-1 text-emerald-500">*</span>}
            </label>

            <input
                type="text"
                value={value}
                onChange={(event) => onChange(event.target.value)}
                placeholder={placeholder}
                className="
                    w-full
                    rounded-xl
                    border
                    border-slate-200
                    bg-slate-50
                    px-4
                    py-3
                    text-sm
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

            {error && (
                <p className="mt-1 text-xs font-medium text-red-500">{error}</p>
            )}
        </div>
    );
}

/*
|--------------------------------------------------------------------------
| SELECT
|--------------------------------------------------------------------------
*/

function SelectField({
    label,
    required = false,
    value,
    options,
    onChange,
    error,
}) {
    return (
        <div>
            <label className="mb-2 block text-xs font-black uppercase tracking-wider text-slate-400">
                {label}

                {required && <span className="ml-1 text-emerald-500">*</span>}
            </label>

            <select
                value={value}
                onChange={(event) => onChange(event.target.value)}
                className="
                    w-full
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
                    focus:bg-white
                    focus:ring-2
                    focus:ring-emerald-500/10
                "
            >
                {options.map((option) => (
                    <option key={option.value} value={option.value}>
                        {option.label}
                    </option>
                ))}
            </select>

            {error && (
                <p className="mt-1 text-xs font-medium text-red-500">{error}</p>
            )}
        </div>
    );
}

/*
|--------------------------------------------------------------------------
| TEXTAREA
|--------------------------------------------------------------------------
*/

function TextAreaField({ label, required = false, value, onChange, error }) {
    return (
        <div>
            <label className="mb-2 block text-xs font-black uppercase tracking-wider text-slate-400">
                {label}

                {required && <span className="ml-1 text-emerald-500">*</span>}
            </label>

            <textarea
                rows={7}
                value={value}
                onChange={(event) => onChange(event.target.value)}
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
                    focus:border-emerald-500
                    focus:bg-white
                    focus:ring-2
                    focus:ring-emerald-500/10
                "
            />

            {error && (
                <p className="mt-1 text-xs font-medium text-red-500">{error}</p>
            )}
        </div>
    );
}

/*
|--------------------------------------------------------------------------
| COMPLETENESS ITEM
|--------------------------------------------------------------------------
*/

function CompletenessItem({ label, complete }) {
    return (
        <div className="flex items-center gap-3">
            <div
                className={`
                    flex
                    h-6
                    w-6
                    items-center
                    justify-center
                    rounded-full
                    ${
                        complete
                            ? "bg-emerald-500 text-white"
                            : "bg-slate-100 text-slate-300"
                    }
                `}
            >
                <Check className="h-3.5 w-3.5" />
            </div>

            <span
                className={`
                    text-sm
                    font-semibold
                    ${complete ? "text-slate-700" : "text-slate-400"}
                `}
            >
                {label}
            </span>
        </div>
    );
}

/*
|--------------------------------------------------------------------------
| LOGO PREVIEW
|--------------------------------------------------------------------------
*/

function LogoPreview({ url, name }) {
    if (url) {
        return (
            <div className="flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-white p-2">
                <img
                    src={url}
                    alt={name}
                    className="max-h-full max-w-full object-contain"
                />
            </div>
        );
    }

    return (
        <div className="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-white/10 ring-1 ring-white/10">
            <Building2 className="h-7 w-7 text-white/70" />
        </div>
    );
}

/*
|--------------------------------------------------------------------------
| STATUS
|--------------------------------------------------------------------------
*/

function StatusBadge({ active }) {
    return (
        <span
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
                ${
                    active
                        ? "border-emerald-200 bg-emerald-50 text-emerald-700"
                        : "border-amber-200 bg-amber-50 text-amber-700"
                }
            `}
        >
            <span className="h-1.5 w-1.5 rounded-full bg-current" />

            {active ? "Published" : "Draft / Unpublished"}
        </span>
    );
}

/*
|--------------------------------------------------------------------------
| HELPERS
|--------------------------------------------------------------------------
*/

function getCategoryLabel(categories, value) {
    return (
        categories.find((item) => item.value === value)?.label ||
        value ||
        "Industry Solution"
    );
}

function getLevelLabel(levels, value) {
    return (
        levels.find((item) => item.value === value)?.label || value || "Partner"
    );
}
