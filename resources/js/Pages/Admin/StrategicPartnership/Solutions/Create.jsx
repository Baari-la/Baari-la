import React from "react";

import {
    ArrowLeft,
    Check,
    FileText,
    Lightbulb,
    Save,
    Sparkles,
} from "lucide-react";

import { Head, Link, useForm } from "@inertiajs/react";

export default function Create({ partner }) {
    const { data, setData, post, processing, errors } = useForm({
        title: "",
        short_description: "",
        problem_solved: "",
        solution_description: "",
        industry_applications: "",
        technology: "",
        key_benefits: "",
        is_featured: false,
        sort_order: 0,
    });

    const submit = (event) => {
        event.preventDefault();

        post(route("admin.industry-partner-solutions.store", partner.id));
    };

    return (
        <>
            <Head title={`Add Solution — ${partner.company_name}`} />

            <div className="min-h-screen bg-slate-50">
                <div className="mx-auto max-w-5xl px-6 py-8">
                    {/* =====================================================
                        HEADER
                    ===================================================== */}

                    <div>
                        <Link
                            href={route(
                                "admin.industry-partner-solutions.index",
                                partner.id,
                            )}
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
                            Back to Partner Solutions
                        </Link>

                        <div className="mt-6 flex items-center gap-4">
                            <div
                                className="
                                    flex
                                    h-14
                                    w-14
                                    items-center
                                    justify-center
                                    rounded-2xl
                                    bg-slate-950
                                "
                            >
                                <Lightbulb
                                    className="
                                        h-6
                                        w-6
                                        text-white
                                    "
                                />
                            </div>

                            <div>
                                <p
                                    className="
                                        text-xs
                                        font-black
                                        uppercase
                                        tracking-[0.2em]
                                        text-emerald-600
                                    "
                                >
                                    PARTNER SOLUTIONS
                                </p>

                                <h1
                                    className="
                                        mt-1
                                        text-3xl
                                        font-black
                                        text-slate-950
                                    "
                                >
                                    Add Solution
                                </h1>
                            </div>
                        </div>
                    </div>

                    {/* =====================================================
                        PARTNER CONTEXT
                    ===================================================== */}

                    <div
                        className="
                            mt-8
                            rounded-3xl
                            border
                            border-slate-200
                            bg-white
                            p-6
                            shadow-sm
                        "
                    >
                        <div className="flex items-center gap-4">
                            <div
                                className="
                                    flex
                                    h-12
                                    w-12
                                    items-center
                                    justify-center
                                    overflow-hidden
                                    rounded-2xl
                                    bg-slate-100
                                "
                            >
                                {partner.logo_url ? (
                                    <img
                                        src={partner.logo_url}
                                        alt={partner.company_name}
                                        className="
                                            max-h-full
                                            max-w-full
                                            object-contain
                                            p-2
                                        "
                                    />
                                ) : (
                                    <span
                                        className="
                                            text-lg
                                            font-black
                                            text-slate-400
                                        "
                                    >
                                        {partner.company_name
                                            ?.charAt(0)
                                            ?.toUpperCase()}
                                    </span>
                                )}
                            </div>

                            <div>
                                <p
                                    className="
                                        text-xs
                                        font-black
                                        uppercase
                                        tracking-wider
                                        text-slate-400
                                    "
                                >
                                    Strategic Solution Partner
                                </p>

                                <h2
                                    className="
                                        mt-1
                                        text-xl
                                        font-black
                                        text-slate-900
                                    "
                                >
                                    {partner.company_name}
                                </h2>

                                <p
                                    className="
                                        mt-1
                                        text-sm
                                        text-slate-500
                                    "
                                >
                                    {partner.category_label}
                                </p>
                            </div>
                        </div>
                    </div>

                    {/* =====================================================
                        FORM
                    ===================================================== */}

                    <form onSubmit={submit} className="mt-6 space-y-6">
                        {/* =================================================
                            BASIC INFORMATION
                        ================================================= */}

                        <section
                            className="
                                rounded-3xl
                                border
                                border-slate-200
                                bg-white
                                p-7
                                shadow-sm
                            "
                        >
                            <div className="flex items-center gap-3">
                                <div
                                    className="
                                        flex
                                        h-10
                                        w-10
                                        items-center
                                        justify-center
                                        rounded-xl
                                        bg-indigo-50
                                    "
                                >
                                    <FileText
                                        className="
                                            h-5
                                            w-5
                                            text-indigo-600
                                        "
                                    />
                                </div>

                                <div>
                                    <h2
                                        className="
                                            text-lg
                                            font-black
                                            text-slate-950
                                        "
                                    >
                                        Solution Identity
                                    </h2>

                                    <p
                                        className="
                                            mt-1
                                            text-sm
                                            text-slate-500
                                        "
                                    >
                                        Define the solution clearly for industry
                                        visitors.
                                    </p>
                                </div>
                            </div>

                            <div className="mt-7 space-y-6">
                                {/* TITLE */}

                                <Field
                                    label="Solution Title"
                                    required
                                    error={errors.title}
                                >
                                    <input
                                        type="text"
                                        value={data.title}
                                        onChange={(event) =>
                                            setData("title", event.target.value)
                                        }
                                        placeholder="e.g. Digital Textile Printing Machine"
                                        className={inputClass(errors.title)}
                                    />
                                </Field>

                                {/* SHORT DESCRIPTION */}

                                <Field
                                    label="Short Description"
                                    hint="A concise explanation shown on solution cards."
                                    error={errors.short_description}
                                >
                                    <textarea
                                        rows={3}
                                        value={data.short_description}
                                        onChange={(event) =>
                                            setData(
                                                "short_description",
                                                event.target.value,
                                            )
                                        }
                                        placeholder="Briefly describe what this solution provides..."
                                        className={textareaClass(
                                            errors.short_description,
                                        )}
                                    />
                                </Field>
                            </div>
                        </section>

                        {/* =================================================
                            PROBLEM
                        ================================================= */}

                        <section
                            className="
                                rounded-3xl
                                border
                                border-slate-200
                                bg-white
                                p-7
                                shadow-sm
                            "
                        >
                            <div className="flex items-center gap-3">
                                <div
                                    className="
                                        flex
                                        h-10
                                        w-10
                                        items-center
                                        justify-center
                                        rounded-xl
                                        bg-amber-50
                                    "
                                >
                                    <Lightbulb
                                        className="
                                            h-5
                                            w-5
                                            text-amber-600
                                        "
                                    />
                                </div>

                                <div>
                                    <h2
                                        className="
                                            text-lg
                                            font-black
                                            text-slate-950
                                        "
                                    >
                                        Industry Problem
                                    </h2>

                                    <p
                                        className="
                                            mt-1
                                            text-sm
                                            text-slate-500
                                        "
                                    >
                                        Explain the industry challenge this
                                        solution addresses.
                                    </p>
                                </div>
                            </div>

                            <div className="mt-7">
                                <Field
                                    label="Problem We Solve"
                                    error={errors.problem_solved}
                                >
                                    <textarea
                                        rows={6}
                                        value={data.problem_solved}
                                        onChange={(event) =>
                                            setData(
                                                "problem_solved",
                                                event.target.value,
                                            )
                                        }
                                        placeholder="What textile industry problem does this solution address?"
                                        className={textareaClass(
                                            errors.problem_solved,
                                        )}
                                    />
                                </Field>
                            </div>
                        </section>

                        {/* =================================================
                            SOLUTION
                        ================================================= */}

                        <section
                            className="
                                rounded-3xl
                                border
                                border-slate-200
                                bg-white
                                p-7
                                shadow-sm
                            "
                        >
                            <div className="flex items-center gap-3">
                                <div
                                    className="
                                        flex
                                        h-10
                                        w-10
                                        items-center
                                        justify-center
                                        rounded-xl
                                        bg-emerald-50
                                    "
                                >
                                    <Sparkles
                                        className="
                                            h-5
                                            w-5
                                            text-emerald-600
                                        "
                                    />
                                </div>

                                <div>
                                    <h2
                                        className="
                                            text-lg
                                            font-black
                                            text-slate-950
                                        "
                                    >
                                        Solution Intelligence
                                    </h2>

                                    <p
                                        className="
                                            mt-1
                                            text-sm
                                            text-slate-500
                                        "
                                    >
                                        Provide useful information so visitors
                                        can understand the solution without
                                        leaving DIGESTEX.
                                    </p>
                                </div>
                            </div>

                            <div className="mt-7 space-y-6">
                                {/* SOLUTION DESCRIPTION */}

                                <Field
                                    label="Solution Description"
                                    error={errors.solution_description}
                                >
                                    <textarea
                                        rows={8}
                                        value={data.solution_description}
                                        onChange={(event) =>
                                            setData(
                                                "solution_description",
                                                event.target.value,
                                            )
                                        }
                                        placeholder="Explain how the solution works, what it provides, and how it supports textile industry operations..."
                                        className={textareaClass(
                                            errors.solution_description,
                                        )}
                                    />
                                </Field>

                                {/* INDUSTRY APPLICATIONS */}

                                <Field
                                    label="Industry Applications"
                                    hint="List industries, processes, materials, or production areas where the solution can be applied."
                                    error={errors.industry_applications}
                                >
                                    <textarea
                                        rows={6}
                                        value={data.industry_applications}
                                        onChange={(event) =>
                                            setData(
                                                "industry_applications",
                                                event.target.value,
                                            )
                                        }
                                        placeholder={`Fashion
Home Textile
Sportswear
Cotton Fabric
Polyester Fabric`}
                                        className={textareaClass(
                                            errors.industry_applications,
                                        )}
                                    />
                                </Field>

                                {/* TECHNOLOGY */}

                                <Field
                                    label="Technology"
                                    hint="Technology, equipment, platform, process, or system behind the solution."
                                    error={errors.technology}
                                >
                                    <textarea
                                        rows={5}
                                        value={data.technology}
                                        onChange={(event) =>
                                            setData(
                                                "technology",
                                                event.target.value,
                                            )
                                        }
                                        placeholder="Describe the technology, equipment, platform, or process..."
                                        className={textareaClass(
                                            errors.technology,
                                        )}
                                    />
                                </Field>

                                {/* KEY BENEFITS */}

                                <Field
                                    label="Key Benefits"
                                    hint="Explain the practical or measurable benefits for textile companies."
                                    error={errors.key_benefits}
                                >
                                    <textarea
                                        rows={6}
                                        value={data.key_benefits}
                                        onChange={(event) =>
                                            setData(
                                                "key_benefits",
                                                event.target.value,
                                            )
                                        }
                                        placeholder={`Faster production
Lower sampling cost
Flexible customization
Improved production efficiency`}
                                        className={textareaClass(
                                            errors.key_benefits,
                                        )}
                                    />
                                </Field>
                            </div>
                        </section>

                        {/* =================================================
                            VISIBILITY
                        ================================================= */}

                        <section
                            className="
                                rounded-3xl
                                border
                                border-slate-200
                                bg-white
                                p-7
                                shadow-sm
                            "
                        >
                            <div className="flex items-center gap-3">
                                <div
                                    className="
                                        flex
                                        h-10
                                        w-10
                                        items-center
                                        justify-center
                                        rounded-xl
                                        bg-amber-50
                                    "
                                >
                                    <Sparkles
                                        className="
                                            h-5
                                            w-5
                                            text-amber-600
                                        "
                                    />
                                </div>

                                <div>
                                    <h2
                                        className="
                                            text-lg
                                            font-black
                                            text-slate-950
                                        "
                                    >
                                        Visibility
                                    </h2>

                                    <p
                                        className="
                                            mt-1
                                            text-sm
                                            text-slate-500
                                        "
                                    >
                                        Configure how this solution is
                                        positioned within the ecosystem.
                                    </p>
                                </div>
                            </div>

                            <div className="mt-7 grid gap-6 md:grid-cols-2">
                                {/* FEATURED */}

                                <label
                                    className="
                                        flex
                                        cursor-pointer
                                        items-start
                                        gap-4
                                        rounded-2xl
                                        border
                                        border-slate-200
                                        bg-slate-50
                                        p-5
                                        transition
                                        hover:border-amber-300
                                    "
                                >
                                    <input
                                        type="checkbox"
                                        checked={data.is_featured}
                                        onChange={(event) =>
                                            setData(
                                                "is_featured",
                                                event.target.checked,
                                            )
                                        }
                                        className="
                                            mt-1
                                            h-5
                                            w-5
                                            rounded
                                            border-slate-300
                                            text-amber-500
                                        "
                                    />

                                    <div>
                                        <p
                                            className="
                                                font-black
                                                text-slate-900
                                            "
                                        >
                                            Featured Solution
                                        </p>

                                        <p
                                            className="
                                                mt-1
                                                text-sm
                                                leading-6
                                                text-slate-500
                                            "
                                        >
                                            Give this solution priority
                                            placement when featured visibility
                                            is enabled.
                                        </p>
                                    </div>
                                </label>

                                {/* SORT ORDER */}

                                <Field
                                    label="Display Order"
                                    hint="Lower numbers appear first."
                                    error={errors.sort_order}
                                >
                                    <input
                                        type="number"
                                        min="0"
                                        value={data.sort_order}
                                        onChange={(event) =>
                                            setData(
                                                "sort_order",
                                                event.target.value,
                                            )
                                        }
                                        className={inputClass(
                                            errors.sort_order,
                                        )}
                                    />
                                </Field>
                            </div>
                        </section>

                        {/* =================================================
                            DRAFT NOTICE
                        ================================================= */}

                        <div
                            className="
                                flex
                                items-start
                                gap-4
                                rounded-2xl
                                border
                                border-blue-200
                                bg-blue-50
                                p-5
                            "
                        >
                            <div
                                className="
                                    flex
                                    h-9
                                    w-9
                                    shrink-0
                                    items-center
                                    justify-center
                                    rounded-xl
                                    bg-blue-100
                                    text-blue-600
                                "
                            >
                                <Check className="h-5 w-5" />
                            </div>

                            <div>
                                <p
                                    className="
                                        font-black
                                        text-blue-900
                                    "
                                >
                                    New solutions are saved as Draft
                                </p>

                                <p
                                    className="
                                        mt-1
                                        text-sm
                                        leading-6
                                        text-blue-700
                                    "
                                >
                                    The solution will not be visible publicly
                                    until an administrator publishes it.
                                </p>
                            </div>
                        </div>

                        {/* =================================================
                            ACTIONS
                        ================================================= */}

                        <div
                            className="
                                flex
                                flex-col-reverse
                                gap-3
                                sm:flex-row
                                sm:justify-end
                            "
                        >
                            <Link
                                href={route(
                                    "admin.industry-partner-solutions.index",
                                    partner.id,
                                )}
                                className="
                                    inline-flex
                                    items-center
                                    justify-center
                                    rounded-2xl
                                    border
                                    border-slate-200
                                    bg-white
                                    px-6
                                    py-3.5
                                    text-sm
                                    font-black
                                    text-slate-700
                                    transition
                                    hover:bg-slate-50
                                "
                            >
                                Cancel
                            </Link>

                            <button
                                type="submit"
                                disabled={processing}
                                className="
                                    inline-flex
                                    items-center
                                    justify-center
                                    gap-2
                                    rounded-2xl
                                    bg-slate-950
                                    px-7
                                    py-3.5
                                    text-sm
                                    font-black
                                    text-white
                                    transition
                                    hover:bg-slate-800
                                    disabled:cursor-not-allowed
                                    disabled:opacity-50
                                "
                            >
                                <Save className="h-4 w-4" />

                                {processing ? "Saving..." : "Save Solution"}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </>
    );
}

/*
|--------------------------------------------------------------------------
| FIELD
|--------------------------------------------------------------------------
*/

function Field({ label, required = false, hint, error, children }) {
    return (
        <div>
            <div className="flex items-center justify-between gap-4">
                <label
                    className="
                        text-sm
                        font-black
                        text-slate-900
                    "
                >
                    {label}

                    {required && <span className="ml-1 text-red-500">*</span>}
                </label>
            </div>

            {children}

            {hint && (
                <p
                    className="
                        mt-2
                        text-xs
                        leading-5
                        text-slate-500
                    "
                >
                    {hint}
                </p>
            )}

            {error && (
                <p
                    className="
                        mt-2
                        text-xs
                        font-semibold
                        text-red-600
                    "
                >
                    {error}
                </p>
            )}
        </div>
    );
}

/*
|--------------------------------------------------------------------------
| INPUT CLASS
|--------------------------------------------------------------------------
*/

function inputClass(error) {
    return `
        mt-2
        w-full
        rounded-2xl
        border
        ${error ? "border-red-300 bg-red-50" : "border-slate-200 bg-white"}
        px-4
        py-3.5
        text-sm
        font-medium
        text-slate-900
        outline-none
        transition
        placeholder:text-slate-400
        focus:border-slate-400
        focus:ring-4
        focus:ring-slate-100
    `;
}

/*
|--------------------------------------------------------------------------
| TEXTAREA CLASS
|--------------------------------------------------------------------------
*/

function textareaClass(error) {
    return `
        mt-2
        w-full
        resize-y
        rounded-2xl
        border
        ${error ? "border-red-300 bg-red-50" : "border-slate-200 bg-white"}
        px-4
        py-3.5
        text-sm
        font-medium
        leading-6
        text-slate-900
        outline-none
        transition
        placeholder:text-slate-400
        focus:border-slate-400
        focus:ring-4
        focus:ring-slate-100
    `;
}
