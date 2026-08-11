import React from "react";

import { ArrowLeft, FileText, Save } from "lucide-react";

import { Head, Link, useForm } from "@inertiajs/react";

export default function Edit({ partner, solution, specification }) {
    const { data, setData, patch, processing, errors } = useForm({
        name: specification.name ?? "",
        value: specification.value ?? "",
        unit: specification.unit ?? "",
        sort_order: specification.sort_order ?? 0,
        is_active: Boolean(specification.is_active),
    });

    const submit = (e) => {
        e.preventDefault();

        patch(
            route("admin.industry-partner-solution-specifications.update", {
                partner: partner.id,
                solution: solution.id,
                specification: specification.id,
            }),
        );
    };

    return (
        <>
            <Head title={`Edit Specification — ${solution.title}`} />

            <div className="min-h-screen bg-slate-50">
                <div className="mx-auto max-w-4xl px-6 py-8">
                    {/* =====================================================
                        BACK
                    ===================================================== */}

                    <Link
                        href={route(
                            "admin.industry-partner-solution-specifications.index",
                            {
                                partner: partner.id,
                                solution: solution.id,
                            },
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
                        Back to Technical Specifications
                    </Link>

                    {/* =====================================================
                        HEADER
                    ===================================================== */}

                    <div className="mt-8">
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
                            Edit Specification
                        </h1>

                        <p
                            className="
                                mt-2
                                text-sm
                                text-slate-500
                            "
                        >
                            Update technical information for{" "}
                            <span className="font-bold text-slate-700">
                                {solution.title}
                            </span>
                            .
                        </p>
                    </div>

                    {/* =====================================================
                        SOLUTION INFO
                    ===================================================== */}

                    <div
                        className="
                            mt-8
                            flex
                            items-start
                            gap-4
                            rounded-3xl
                            border
                            border-slate-200
                            bg-white
                            p-6
                            shadow-sm
                        "
                    >
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
                                    text-lg
                                    font-black
                                    text-slate-900
                                "
                            >
                                {solution.title}
                            </h2>

                            <p
                                className="
                                    mt-1
                                    text-sm
                                    text-slate-500
                                "
                            >
                                {partner.company_name}
                            </p>
                        </div>
                    </div>

                    {/* =====================================================
                        FORM
                    ===================================================== */}

                    <form
                        onSubmit={submit}
                        className="
                            mt-6
                            overflow-hidden
                            rounded-3xl
                            border
                            border-slate-200
                            bg-white
                            shadow-sm
                        "
                    >
                        <div className="p-6 sm:p-8">
                            {/* =================================================
                                NAME
                            ================================================= */}

                            <div>
                                <label
                                    htmlFor="name"
                                    className="
                                        block
                                        text-sm
                                        font-black
                                        text-slate-800
                                    "
                                >
                                    Specification Name
                                    <span className="ml-1 text-red-500">*</span>
                                </label>

                                <p
                                    className="
                                        mt-1
                                        text-xs
                                        text-slate-400
                                    "
                                >
                                    Example: Maximum Printing Width
                                </p>

                                <input
                                    id="name"
                                    type="text"
                                    value={data.name}
                                    onChange={(e) =>
                                        setData("name", e.target.value)
                                    }
                                    placeholder="Maximum Printing Width"
                                    className="
                                        mt-3
                                        w-full
                                        rounded-2xl
                                        border
                                        border-slate-200
                                        bg-slate-50
                                        px-4
                                        py-3
                                        text-sm
                                        text-slate-900
                                        outline-none
                                        transition
                                        focus:border-emerald-500
                                        focus:bg-white
                                        focus:ring-2
                                        focus:ring-emerald-100
                                    "
                                />

                                {errors.name && (
                                    <p
                                        className="
                                            mt-2
                                            text-xs
                                            font-semibold
                                            text-red-500
                                        "
                                    >
                                        {errors.name}
                                    </p>
                                )}
                            </div>

                            {/* =================================================
                                VALUE + UNIT
                            ================================================= */}

                            <div
                                className="
                                    mt-6
                                    grid
                                    gap-6
                                    sm:grid-cols-[1fr_180px]
                                "
                            >
                                <div>
                                    <label
                                        htmlFor="value"
                                        className="
                                            block
                                            text-sm
                                            font-black
                                            text-slate-800
                                        "
                                    >
                                        Value
                                        <span className="ml-1 text-red-500">
                                            *
                                        </span>
                                    </label>

                                    <p
                                        className="
                                            mt-1
                                            text-xs
                                            text-slate-400
                                        "
                                    >
                                        Enter the technical specification value.
                                    </p>

                                    <input
                                        id="value"
                                        type="text"
                                        value={data.value}
                                        onChange={(e) =>
                                            setData("value", e.target.value)
                                        }
                                        placeholder="1800"
                                        className="
                                            mt-3
                                            w-full
                                            rounded-2xl
                                            border
                                            border-slate-200
                                            bg-slate-50
                                            px-4
                                            py-3
                                            text-sm
                                            text-slate-900
                                            outline-none
                                            transition
                                            focus:border-emerald-500
                                            focus:bg-white
                                            focus:ring-2
                                            focus:ring-emerald-100
                                        "
                                    />

                                    {errors.value && (
                                        <p
                                            className="
                                                mt-2
                                                text-xs
                                                font-semibold
                                                text-red-500
                                            "
                                        >
                                            {errors.value}
                                        </p>
                                    )}
                                </div>

                                <div>
                                    <label
                                        htmlFor="unit"
                                        className="
                                            block
                                            text-sm
                                            font-black
                                            text-slate-800
                                        "
                                    >
                                        Unit
                                    </label>

                                    <p
                                        className="
                                            mt-1
                                            text-xs
                                            text-slate-400
                                        "
                                    >
                                        Optional
                                    </p>

                                    <input
                                        id="unit"
                                        type="text"
                                        value={data.unit}
                                        onChange={(e) =>
                                            setData("unit", e.target.value)
                                        }
                                        placeholder="mm"
                                        className="
                                            mt-3
                                            w-full
                                            rounded-2xl
                                            border
                                            border-slate-200
                                            bg-slate-50
                                            px-4
                                            py-3
                                            text-sm
                                            text-slate-900
                                            outline-none
                                            transition
                                            focus:border-emerald-500
                                            focus:bg-white
                                            focus:ring-2
                                            focus:ring-emerald-100
                                        "
                                    />

                                    {errors.unit && (
                                        <p
                                            className="
                                                mt-2
                                                text-xs
                                                font-semibold
                                                text-red-500
                                            "
                                        >
                                            {errors.unit}
                                        </p>
                                    )}
                                </div>
                            </div>

                            {/* =================================================
                                DISPLAY ORDER
                            ================================================= */}

                            <div className="mt-6">
                                <label
                                    htmlFor="sort_order"
                                    className="
                                        block
                                        text-sm
                                        font-black
                                        text-slate-800
                                    "
                                >
                                    Display Order
                                </label>

                                <p
                                    className="
                                        mt-1
                                        text-xs
                                        text-slate-400
                                    "
                                >
                                    Lower numbers appear first.
                                </p>

                                <input
                                    id="sort_order"
                                    type="number"
                                    min="0"
                                    value={data.sort_order}
                                    onChange={(e) =>
                                        setData("sort_order", e.target.value)
                                    }
                                    className="
                                        mt-3
                                        w-full
                                        rounded-2xl
                                        border
                                        border-slate-200
                                        bg-slate-50
                                        px-4
                                        py-3
                                        text-sm
                                        text-slate-900
                                        outline-none
                                        transition
                                        focus:border-emerald-500
                                        focus:bg-white
                                        focus:ring-2
                                        focus:ring-emerald-100
                                    "
                                />

                                {errors.sort_order && (
                                    <p
                                        className="
                                            mt-2
                                            text-xs
                                            font-semibold
                                            text-red-500
                                        "
                                    >
                                        {errors.sort_order}
                                    </p>
                                )}
                            </div>

                            {/* =================================================
                                STATUS
                            ================================================= */}

                            <div
                                className="
                                    mt-6
                                    rounded-2xl
                                    border
                                    border-slate-200
                                    bg-slate-50
                                    p-4
                                "
                            >
                                <label
                                    className="
                                        flex
                                        cursor-pointer
                                        items-center
                                        gap-3
                                    "
                                >
                                    <input
                                        type="checkbox"
                                        checked={data.is_active}
                                        onChange={(e) =>
                                            setData(
                                                "is_active",
                                                e.target.checked,
                                            )
                                        }
                                        className="
                                            h-4
                                            w-4
                                            rounded
                                            border-slate-300
                                            text-emerald-600
                                            focus:ring-emerald-500
                                        "
                                    />

                                    <div>
                                        <p
                                            className="
                                                text-sm
                                                font-black
                                                text-slate-800
                                            "
                                        >
                                            Active Specification
                                        </p>

                                        <p
                                            className="
                                                mt-1
                                                text-xs
                                                text-slate-500
                                            "
                                        >
                                            Active specifications can be
                                            displayed on the public solution
                                            page.
                                        </p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {/* =====================================================
                            FOOTER
                        ===================================================== */}

                        <div
                            className="
                                flex
                                flex-col-reverse
                                gap-3
                                border-t
                                border-slate-200
                                bg-slate-50
                                px-6
                                py-5
                                sm:flex-row
                                sm:justify-end
                                sm:px-8
                            "
                        >
                            <Link
                                href={route(
                                    "admin.industry-partner-solution-specifications.index",
                                    {
                                        partner: partner.id,
                                        solution: solution.id,
                                    },
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
                                    py-3
                                    text-sm
                                    font-black
                                    text-slate-700
                                    transition
                                    hover:bg-slate-100
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
                                    px-6
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
                                <Save className="h-4 w-4" />

                                {processing
                                    ? "Updating..."
                                    : "Update Specification"}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </>
    );
}
