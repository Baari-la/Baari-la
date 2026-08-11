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

export default function Edit({ partner, solution }) {
    const { data, setData, patch, processing, errors } = useForm({
        title: solution.title ?? "",
        short_description: solution.short_description ?? "",
        problem_solved: solution.problem_solved ?? "",
        solution_description: solution.solution_description ?? "",
        industry_applications: solution.industry_applications ?? "",
        technology: solution.technology ?? "",
        key_benefits: solution.key_benefits ?? "",
        is_featured: Boolean(solution.is_featured),
        sort_order: solution.sort_order ?? 0,
    });

    const submit = (event) => {
        event.preventDefault();

        patch(
            route("admin.industry-partner-solutions.update", {
                partner: partner.id,
                solution: solution.id,
            }),
        );
    };

    return (
        <>
            <Head title={`Edit Solution — ${partner.company_name}`} />

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

                        <div className="mt-6 flex items-center justify-between gap-5">
                            <div className="flex items-center gap-4">
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
                                        Edit Solution
                                    </h1>
                                </div>
                            </div>

                            {/* STATUS */}

                            <div
                                className={
                                    solution.is_active
                                        ? `
                                            rounded-full
                                            border
                                            border-emerald-200
                                            bg-emerald-50
                                            px-4
                                            py-2
                                            text-xs
                                            font-black
                                            uppercase
                                            tracking-wider
                                            text-emerald-700
                                        `
                                        : `
                                            rounded-full
                                            border
                                            border-amber-200
                                            bg-amber-50
                                            px-4
                                            py-2
                                            text-xs
                                            font-black
                                            uppercase
                                            tracking-wider
                                            text-amber-700
                                        `
                                }
                            >
                                {solution.is_active ? "Published" : "Draft"}
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
                        <div className="flex items-center justify-between gap-5">
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

                            <div className="hidden text-right sm:block">
                                <p
                                    className="
                                        text-xs
                                        font-bold
                                        uppercase
                                        tracking-wider
                                        text-slate-400
                                    "
                                >
                                    Solution
                                </p>

                                <p
                                    className="
                                        mt-1
                                        text-sm
                                        font-black
                                        text-slate-900
                                    "
                                >
                                    #{solution.id}
                                </p>
                            </div>
                        </div>
                    </div>

                    {/* =====================================================
                        FORM
                    ===================================================== */}

                    <form onSubmit={submit} className="mt-6 space-y-6">
                        {/* =================================================
                            SOLUTION IDENTITY
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
                                        Define how this solution is presented
                                        within the DIGESTEX ecosystem.
                                    </p>
                                </div>
                            </div>

                            <div className="mt-7 space-y-6">
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

                                <Field
                                    label="Short Description"
                                    hint="A concise explanation shown on solution cards and discovery pages."
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
                                        placeholder="Briefly describe this solution..."
                                        className={textareaClass(
                                            errors.short_description,
                                        )}
                                    />
                                </Field>
                            </div>
                        </section>

                        {/* =================================================
                            INDUSTRY PROBLEM
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
                                        placeholder="What industry problem does this solution address?"
                                        className={textareaClass(
                                            errors.problem_solved,
                                        )}
                                    />
                                </Field>
                            </div>
                        </section>

                        {/* =================================================
                            SOLUTION INTELLIGENCE
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
                                        Give visitors enough information to
                                        understand the solution without leaving
                                        DIGESTEX.
                                    </p>
                                </div>
                            </div>

                            <div className="mt-7 space-y-6">
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
                                        placeholder="Explain how the solution works and how it supports textile industry operations..."
                                        className={textareaClass(
                                            errors.solution_description,
                                        )}
                                    />
                                </Field>

                                <Field
                                    label="Industry Applications"
                                    hint="Describe where this solution can be applied."
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
                                        placeholder="Fashion, Home Textile, Sportswear, Cotton Fabric, Polyester Fabric..."
                                        className={textareaClass(
                                            errors.industry_applications,
                                        )}
                                    />
                                </Field>

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
                                        placeholder="Improved productivity, lower production cost, reduced water consumption..."
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
                                        Configure solution visibility within the
                                        ecosystem.
                                    </p>
                                </div>
                            </div>

                            <div className="mt-7 grid gap-6 md:grid-cols-2">
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
                                            Prioritize this solution in featured
                                            placements.
                                        </p>
                                    </div>
                                </label>

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
                            PUBLICATION STATUS
                        ================================================= */}

                        <div
                            className={
                                solution.is_active
                                    ? `
                                        flex
                                        items-start
                                        gap-4
                                        rounded-2xl
                                        border
                                        border-emerald-200
                                        bg-emerald-50
                                        p-5
                                    `
                                    : `
                                        flex
                                        items-start
                                        gap-4
                                        rounded-2xl
                                        border
                                        border-blue-200
                                        bg-blue-50
                                        p-5
                                    `
                            }
                        >
                            <div
                                className={
                                    solution.is_active
                                        ? `
                                            flex
                                            h-9
                                            w-9
                                            shrink-0
                                            items-center
                                            justify-center
                                            rounded-xl
                                            bg-emerald-100
                                            text-emerald-600
                                        `
                                        : `
                                            flex
                                            h-9
                                            w-9
                                            shrink-0
                                            items-center
                                            justify-center
                                            rounded-xl
                                            bg-blue-100
                                            text-blue-600
                                        `
                                }
                            >
                                <Check className="h-5 w-5" />
                            </div>

                            <div>
                                <p
                                    className={
                                        solution.is_active
                                            ? "font-black text-emerald-900"
                                            : "font-black text-blue-900"
                                    }
                                >
                                    {solution.is_active
                                        ? "Solution is Published"
                                        : "Solution is currently Draft"}
                                </p>

                                <p
                                    className={
                                        solution.is_active
                                            ? "mt-1 text-sm leading-6 text-emerald-700"
                                            : "mt-1 text-sm leading-6 text-blue-700"
                                    }
                                >
                                    {solution.is_active
                                        ? "This solution is currently visible on the public DIGESTEX ecosystem."
                                        : "Save your changes first, then publish the solution from the Solutions list when the content is ready."}
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

                                {processing ? "Saving..." : "Save Changes"}
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
