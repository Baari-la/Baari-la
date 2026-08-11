import React from "react";

import { ArrowLeft, Edit3, FileText, Plus, Power, Trash2 } from "lucide-react";

import { Head, Link, router } from "@inertiajs/react";

export default function Index({ partner, solution, specifications = [] }) {
    const deleteSpecification = (specification) => {
        if (!window.confirm(`Delete "${specification.name}"?`)) {
            return;
        }

        router.delete(
            route("admin.industry-partner-solution-specifications.destroy", {
                partner: partner.id,
                solution: solution.id,
                specification: specification.id,
            }),
        );
    };

    const toggleStatus = (specification) => {
        router.patch(
            route("admin.industry-partner-solution-specifications.status", {
                partner: partner.id,
                solution: solution.id,
                specification: specification.id,
            }),
        );
    };

    return (
        <>
            <Head title={`Technical Specifications — ${solution.title}`} />

            <div className="min-h-screen bg-slate-50">
                <div className="mx-auto max-w-6xl px-6 py-8">
                    {/* =====================================================
                        BACK
                    ===================================================== */}

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

                    {/* =====================================================
                        HEADER
                    ===================================================== */}

                    <div
                        className="
                            mt-6
                            flex
                            flex-col
                            gap-5
                            sm:flex-row
                            sm:items-end
                            sm:justify-between
                        "
                    >
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
                                TECHNICAL SPECIFICATIONS
                            </p>

                            <h1
                                className="
                                    mt-2
                                    text-3xl
                                    font-black
                                    text-slate-950
                                "
                            >
                                {solution.title}
                            </h1>

                            <p
                                className="
                                    mt-2
                                    text-sm
                                    text-slate-500
                                "
                            >
                                {partner.company_name}
                            </p>
                        </div>

                        <Link
                            href={route(
                                "admin.industry-partner-solution-specifications.create",
                                {
                                    partner: partner.id,
                                    solution: solution.id,
                                },
                            )}
                            className="
                                inline-flex
                                items-center
                                justify-center
                                gap-2
                                rounded-2xl
                                bg-slate-950
                                px-6
                                py-3.5
                                text-sm
                                font-black
                                text-white
                                transition
                                hover:bg-slate-800
                            "
                        >
                            <Plus className="h-4 w-4" />
                            Add Specification
                        </Link>
                    </div>

                    {/* =====================================================
                        SOLUTION CONTEXT
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
                        <div className="flex items-start gap-4">
                            <div
                                className="
                                    flex
                                    h-12
                                    w-12
                                    shrink-0
                                    items-center
                                    justify-center
                                    rounded-2xl
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
                                <p
                                    className="
                                        text-xs
                                        font-black
                                        uppercase
                                        tracking-wider
                                        text-slate-400
                                    "
                                >
                                    Partner Solution
                                </p>

                                <h2
                                    className="
                                        mt-1
                                        text-xl
                                        font-black
                                        text-slate-900
                                    "
                                >
                                    {solution.title}
                                </h2>

                                {solution.short_description && (
                                    <p
                                        className="
                                            mt-2
                                            max-w-3xl
                                            text-sm
                                            leading-6
                                            text-slate-500
                                        "
                                    >
                                        {solution.short_description}
                                    </p>
                                )}
                            </div>
                        </div>
                    </div>

                    {/* =====================================================
                        SPECIFICATIONS
                    ===================================================== */}

                    <div className="mt-6">
                        {specifications.length === 0 ? (
                            <div
                                className="
                                    rounded-3xl
                                    border
                                    border-dashed
                                    border-slate-300
                                    bg-white
                                    px-6
                                    py-16
                                    text-center
                                "
                            >
                                <div
                                    className="
                                        mx-auto
                                        flex
                                        h-14
                                        w-14
                                        items-center
                                        justify-center
                                        rounded-2xl
                                        bg-slate-100
                                    "
                                >
                                    <FileText
                                        className="
                                            h-6
                                            w-6
                                            text-slate-400
                                        "
                                    />
                                </div>

                                <h3
                                    className="
                                        mt-5
                                        text-xl
                                        font-black
                                        text-slate-900
                                    "
                                >
                                    No Technical Specifications
                                </h3>

                                <p
                                    className="
                                        mx-auto
                                        mt-2
                                        max-w-md
                                        text-sm
                                        leading-6
                                        text-slate-500
                                    "
                                >
                                    Add technical specifications so DIGESTEX
                                    visitors can better understand this
                                    solution.
                                </p>

                                <Link
                                    href={route(
                                        "admin.industry-partner-solution-specifications.create",
                                        {
                                            partner: partner.id,
                                            solution: solution.id,
                                        },
                                    )}
                                    className="
                                        mt-6
                                        inline-flex
                                        items-center
                                        gap-2
                                        rounded-2xl
                                        bg-slate-950
                                        px-6
                                        py-3
                                        text-sm
                                        font-black
                                        text-white
                                        transition
                                        hover:bg-slate-800
                                    "
                                >
                                    <Plus className="h-4 w-4" />
                                    Add First Specification
                                </Link>
                            </div>
                        ) : (
                            <div
                                className="
                                    overflow-hidden
                                    rounded-3xl
                                    border
                                    border-slate-200
                                    bg-white
                                    shadow-sm
                                "
                            >
                                {/* TABLE HEADER */}

                                <div
                                    className="
                                        hidden
                                        border-b
                                        border-slate-200
                                        bg-slate-50
                                        px-6
                                        py-4
                                        md:grid
                                        md:grid-cols-[80px_1fr_180px_120px_180px]
                                        md:items-center
                                        md:gap-4
                                    "
                                >
                                    <div
                                        className="
                                            text-xs
                                            font-black
                                            uppercase
                                            tracking-wider
                                            text-slate-400
                                        "
                                    >
                                        Order
                                    </div>

                                    <div
                                        className="
                                            text-xs
                                            font-black
                                            uppercase
                                            tracking-wider
                                            text-slate-400
                                        "
                                    >
                                        Specification
                                    </div>

                                    <div
                                        className="
                                            text-xs
                                            font-black
                                            uppercase
                                            tracking-wider
                                            text-slate-400
                                        "
                                    >
                                        Value
                                    </div>

                                    <div
                                        className="
                                            text-xs
                                            font-black
                                            uppercase
                                            tracking-wider
                                            text-slate-400
                                        "
                                    >
                                        Status
                                    </div>

                                    <div
                                        className="
                                            text-right
                                            text-xs
                                            font-black
                                            uppercase
                                            tracking-wider
                                            text-slate-400
                                        "
                                    >
                                        Actions
                                    </div>
                                </div>

                                {/* ROWS */}

                                <div className="divide-y divide-slate-100">
                                    {specifications.map((specification) => (
                                        <div
                                            key={specification.id}
                                            className="
                                                    px-6
                                                    py-5
                                                    transition
                                                    hover:bg-slate-50
                                                "
                                        >
                                            <div
                                                className="
                                                        grid
                                                        gap-4
                                                        md:grid-cols-[80px_1fr_180px_120px_180px]
                                                        md:items-center
                                                    "
                                            >
                                                {/* ORDER */}

                                                <div
                                                    className="
                                                            flex
                                                            items-center
                                                            gap-2
                                                        "
                                                >
                                                    <span
                                                        className="
                                                                text-xs
                                                                font-black
                                                                text-slate-400
                                                            "
                                                    >
                                                        #
                                                    </span>

                                                    <span
                                                        className="
                                                                font-black
                                                                text-slate-700
                                                            "
                                                    >
                                                        {
                                                            specification.sort_order
                                                        }
                                                    </span>
                                                </div>

                                                {/* NAME */}

                                                <div>
                                                    <p
                                                        className="
                                                                font-black
                                                                text-slate-900
                                                            "
                                                    >
                                                        {specification.name}
                                                    </p>

                                                    <p
                                                        className="
                                                                mt-1
                                                                text-xs
                                                                text-slate-400
                                                            "
                                                    >
                                                        ID #{specification.id}
                                                    </p>
                                                </div>

                                                {/* VALUE */}

                                                <div>
                                                    <span
                                                        className="
                                                                font-bold
                                                                text-slate-800
                                                            "
                                                    >
                                                        {specification.value}
                                                    </span>

                                                    {specification.unit && (
                                                        <span
                                                            className="
                                                                    ml-1
                                                                    text-sm
                                                                    font-semibold
                                                                    text-slate-400
                                                                "
                                                        >
                                                            {specification.unit}
                                                        </span>
                                                    )}
                                                </div>

                                                {/* STATUS */}

                                                <div>
                                                    <button
                                                        type="button"
                                                        onClick={() =>
                                                            toggleStatus(
                                                                specification,
                                                            )
                                                        }
                                                        className={
                                                            specification.is_active
                                                                ? `
                                                                        inline-flex
                                                                        items-center
                                                                        gap-2
                                                                        rounded-full
                                                                        border
                                                                        border-emerald-200
                                                                        bg-emerald-50
                                                                        px-3
                                                                        py-1.5
                                                                        text-xs
                                                                        font-black
                                                                        text-emerald-700
                                                                    `
                                                                : `
                                                                        inline-flex
                                                                        items-center
                                                                        gap-2
                                                                        rounded-full
                                                                        border
                                                                        border-slate-200
                                                                        bg-slate-100
                                                                        px-3
                                                                        py-1.5
                                                                        text-xs
                                                                        font-black
                                                                        text-slate-500
                                                                    `
                                                        }
                                                    >
                                                        <span
                                                            className={
                                                                specification.is_active
                                                                    ? "h-1.5 w-1.5 rounded-full bg-emerald-500"
                                                                    : "h-1.5 w-1.5 rounded-full bg-slate-400"
                                                            }
                                                        />

                                                        {specification.is_active
                                                            ? "Active"
                                                            : "Inactive"}
                                                    </button>
                                                </div>

                                                {/* ACTIONS */}

                                                <div
                                                    className="
                                                            flex
                                                            items-center
                                                            justify-start
                                                            gap-2
                                                            md:justify-end
                                                        "
                                                >
                                                    <Link
                                                        href={route(
                                                            "admin.industry-partner-solution-specifications.edit",
                                                            {
                                                                partner:
                                                                    partner.id,
                                                                solution:
                                                                    solution.id,
                                                                specification:
                                                                    specification.id,
                                                            },
                                                        )}
                                                        className="
                                                                inline-flex
                                                                items-center
                                                                gap-2
                                                                rounded-xl
                                                                border
                                                                border-slate-200
                                                                bg-white
                                                                px-3
                                                                py-2
                                                                text-xs
                                                                font-black
                                                                text-slate-700
                                                                transition
                                                                hover:bg-slate-100
                                                            "
                                                    >
                                                        <Edit3 className="h-3.5 w-3.5" />
                                                        Edit
                                                    </Link>

                                                    <button
                                                        type="button"
                                                        onClick={() =>
                                                            deleteSpecification(
                                                                specification,
                                                            )
                                                        }
                                                        className="
                                                                inline-flex
                                                                items-center
                                                                justify-center
                                                                rounded-xl
                                                                border
                                                                border-red-200
                                                                bg-red-50
                                                                p-2
                                                                text-red-600
                                                                transition
                                                                hover:bg-red-100
                                                            "
                                                        title="Delete"
                                                    >
                                                        <Trash2 className="h-4 w-4" />
                                                    </button>
                                                </div>
                                            </div>

                                            {/* MOBILE STATUS / ACTION */}

                                            <div
                                                className="
                                                        mt-4
                                                        flex
                                                        items-center
                                                        justify-between
                                                        border-t
                                                        border-slate-100
                                                        pt-4
                                                        md:hidden
                                                    "
                                            >
                                                <button
                                                    type="button"
                                                    onClick={() =>
                                                        toggleStatus(
                                                            specification,
                                                        )
                                                    }
                                                    className="
                                                            inline-flex
                                                            items-center
                                                            gap-2
                                                            text-xs
                                                            font-black
                                                            text-slate-500
                                                        "
                                                >
                                                    <Power className="h-4 w-4" />

                                                    {specification.is_active
                                                        ? "Active"
                                                        : "Inactive"}
                                                </button>

                                                <div className="flex gap-2">
                                                    <Link
                                                        href={route(
                                                            "admin.industry-partner-solution-specifications.edit",
                                                            {
                                                                partner:
                                                                    partner.id,
                                                                solution:
                                                                    solution.id,
                                                                specification:
                                                                    specification.id,
                                                            },
                                                        )}
                                                        className="
                                                                rounded-xl
                                                                border
                                                                border-slate-200
                                                                px-3
                                                                py-2
                                                                text-xs
                                                                font-black
                                                                text-slate-700
                                                            "
                                                    >
                                                        Edit
                                                    </Link>
                                                </div>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        )}
                    </div>

                    {/* =====================================================
                        FOOTER NAVIGATION
                    ===================================================== */}

                    <div className="mt-6">
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
                                font-black
                                text-slate-500
                                transition
                                hover:text-slate-900
                            "
                        >
                            <ArrowLeft className="h-4 w-4" />
                            Back to Solutions
                        </Link>
                    </div>
                </div>
            </div>
        </>
    );
}
