import React from "react";

import { ArrowLeft, Edit, Plus, Sparkles } from "lucide-react";

import { Head, Link, router } from "@inertiajs/react";

export default function Index({ partner, solutions = [] }) {
    const publish = (solution) => {
        if (!confirm(`Publish "${solution.title}"?`)) {
            return;
        }

        router.post(
            route("admin.industry-partner-solutions.publish", {
                partner: partner.id,
                solution: solution.id,
            }),
        );
    };

    const unpublish = (solution) => {
        if (!confirm(`Unpublish "${solution.title}"?`)) {
            return;
        }

        router.post(
            route("admin.industry-partner-solutions.unpublish", {
                partner: partner.id,
                solution: solution.id,
            }),
        );
    };

    return (
        <>
            <Head title={`Solutions — ${partner.company_name}`} />

            <div className="min-h-screen bg-slate-50">
                <div className="mx-auto max-w-7xl px-6 py-8">
                    <div className="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <Link
                                href={route(
                                    "admin.industry-partners.edit",
                                    partner.id,
                                )}
                                className="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-slate-900"
                            >
                                <ArrowLeft className="h-4 w-4" />
                                Back to Partner Profile
                            </Link>

                            <div className="mt-5 flex items-center gap-4">
                                <div className="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-950">
                                    <Sparkles className="h-5 w-5 text-white" />
                                </div>

                                <div>
                                    <p className="text-xs font-black uppercase tracking-[0.2em] text-emerald-600">
                                        PARTNER SOLUTIONS
                                    </p>

                                    <h1 className="mt-1 text-3xl font-black text-slate-950">
                                        {partner.company_name}
                                    </h1>
                                </div>
                            </div>
                        </div>

                        <Link
                            href={route(
                                "admin.industry-partner-solutions.create",
                                partner.id,
                            )}
                            className="inline-flex items-center justify-center gap-2 rounded-2xl bg-slate-950 px-6 py-3.5 text-sm font-black text-white transition hover:bg-slate-800"
                        >
                            <Plus className="h-4 w-4" />
                            Add Solution
                        </Link>
                    </div>

                    <div className="mt-8 space-y-4">
                        {solutions.length === 0 && (
                            <div className="rounded-3xl border border-dashed border-slate-300 bg-white p-12 text-center">
                                <Sparkles className="mx-auto h-10 w-10 text-slate-300" />

                                <h2 className="mt-4 text-xl font-black text-slate-900">
                                    No Solutions Yet
                                </h2>

                                <p className="mx-auto mt-2 max-w-lg text-sm leading-6 text-slate-500">
                                    Add the first solution that this strategic
                                    partner provides to the textile industry
                                    ecosystem.
                                </p>
                            </div>
                        )}

                        {solutions.map((solution) => (
                            <div
                                key={solution.id}
                                className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm"
                            >
                                <div className="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                                    <div>
                                        <div className="flex flex-wrap items-center gap-3">
                                            <h2 className="text-xl font-black text-slate-950">
                                                {solution.title}
                                            </h2>

                                            <span
                                                className={
                                                    solution.is_active
                                                        ? "rounded-full bg-emerald-100 px-3 py-1 text-xs font-black text-emerald-700"
                                                        : "rounded-full bg-slate-100 px-3 py-1 text-xs font-black text-slate-500"
                                                }
                                            >
                                                {solution.is_active
                                                    ? "Published"
                                                    : "Draft"}
                                            </span>

                                            {solution.is_featured && (
                                                <span className="rounded-full bg-amber-100 px-3 py-1 text-xs font-black text-amber-700">
                                                    Featured
                                                </span>
                                            )}
                                        </div>

                                        {solution.short_description && (
                                            <p className="mt-3 max-w-3xl text-sm leading-6 text-slate-500">
                                                {solution.short_description}
                                            </p>
                                        )}
                                    </div>

                                    <div className="flex flex-wrap gap-2">
                                        <Link
                                            href={route(
                                                "admin.industry-partner-solutions.edit",
                                                {
                                                    partner: partner.id,
                                                    solution: solution.id,
                                                },
                                            )}
                                            className="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50"
                                        >
                                            <Edit className="h-4 w-4" />
                                            Edit
                                        </Link>

                                        {!solution.is_active ? (
                                            <button
                                                type="button"
                                                onClick={() =>
                                                    publish(solution)
                                                }
                                                className="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-black text-white hover:bg-emerald-500"
                                            >
                                                Publish
                                            </button>
                                        ) : (
                                            <button
                                                type="button"
                                                onClick={() =>
                                                    unpublish(solution)
                                                }
                                                className="rounded-xl border border-red-200 bg-red-50 px-4 py-2.5 text-sm font-black text-red-600 hover:bg-red-100"
                                            >
                                                Unpublish
                                            </button>
                                        )}
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </>
    );
}
