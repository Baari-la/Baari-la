import { Head, Link, router } from "@inertiajs/react";
import { Search, ArrowRight, ShieldCheck, X } from "lucide-react";
import { useEffect, useRef, useState } from "react";

import AppLayout from "@/Layouts/WebsiteLayout";

export default function Directory({ identities, filters }) {
    const [search, setSearch] = useState(filters?.search || "");
    const firstRender = useRef(true);

    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    useEffect(() => {
        if (firstRender.current) {
            firstRender.current = false;
            return;
        }

        const timer = setTimeout(() => {
            router.get(
                route("company-passport.index"),
                {
                    search: search || undefined,
                },
                {
                    preserveState: true,
                    preserveScroll: true,
                    replace: true,
                },
            );
        }, 400);

        return () => clearTimeout(timer);
    }, [search]);

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

    const goToPage = (url) => {
        if (!url) {
            return;
        }

        router.visit(url, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    /*
    |--------------------------------------------------------------------------
    | Clear Search
    |--------------------------------------------------------------------------
    */

    const clearSearch = () => {
        setSearch("");
    };

    return (
        <AppLayout>
            <Head title="Company Passport Directory" />

            <div className="min-h-screen bg-slate-50">
                {/* =====================================================
                    HERO
                ===================================================== */}

                <section className="bg-[#0B2E59]">
                    <div className="mx-auto max-w-7xl px-6 py-16 lg:px-8">
                        <div className="max-w-3xl">
                            <p className="text-[10px] font-black uppercase tracking-[0.3em] text-amber-400">
                                DIGESTEX GLOBAL TEXTILE INTELLIGENCE
                            </p>

                            <h1 className="mt-4 text-4xl font-black text-white md:text-5xl">
                                Company{" "}
                                <span className="text-amber-400">Passport</span>
                            </h1>

                            <p className="mt-5 text-sm leading-7 text-slate-300">
                                Discover structured company intelligence from
                                the textile industry ecosystem.
                            </p>
                        </div>
                    </div>
                </section>

                {/* =====================================================
                    SEARCH
                ===================================================== */}

                <section className="relative z-10 -mt-8">
                    <div className="mx-auto max-w-7xl px-6 lg:px-8">
                        <div className="rounded-2xl border border-slate-200 bg-white p-3 shadow-xl">
                            <div className="relative">
                                <Search
                                    size={18}
                                    className="
                                        absolute
                                        left-5
                                        top-1/2
                                        -translate-y-1/2
                                        text-slate-400
                                    "
                                />

                                <input
                                    type="text"
                                    value={search}
                                    onChange={(e) => setSearch(e.target.value)}
                                    placeholder="Search company, country..."
                                    className="
                                        w-full
                                        rounded-xl
                                        border-0
                                        bg-slate-50
                                        py-4
                                        pl-12
                                        pr-12
                                        text-sm
                                        text-slate-900
                                        outline-none
                                        ring-1
                                        ring-slate-200
                                        transition
                                        focus:ring-2
                                        focus:ring-amber-400
                                    "
                                />

                                {search && (
                                    <button
                                        type="button"
                                        onClick={clearSearch}
                                        className="
                                            absolute
                                            right-4
                                            top-1/2
                                            -translate-y-1/2
                                            rounded-full
                                            p-1
                                            text-slate-400
                                            transition
                                            hover:bg-slate-200
                                            hover:text-slate-700
                                        "
                                    >
                                        <X size={16} />
                                    </button>
                                )}
                            </div>
                        </div>
                    </div>
                </section>

                {/* =====================================================
                    DIRECTORY
                ===================================================== */}

                <section className="mx-auto max-w-7xl px-6 py-14 lg:px-8">
                    <div className="mb-8 flex flex-col justify-between gap-3 md:flex-row md:items-end">
                        <div>
                            <p className="text-[10px] font-black uppercase tracking-[0.25em] text-slate-400">
                                CANONICAL COMPANY DIRECTORY
                            </p>

                            <h2 className="mt-2 text-2xl font-black text-slate-900">
                                Textile Industry Companies
                            </h2>
                        </div>

                        <div className="text-xs font-semibold text-slate-500">
                            {identities?.total ?? 0} companies
                        </div>
                    </div>

                    {/* SEARCH RESULT INFO */}

                    {search && (
                        <div className="mb-6 rounded-xl border border-amber-100 bg-amber-50 px-4 py-3 text-xs text-amber-800">
                            Search results for{" "}
                            <strong>&quot;{search}&quot;</strong>
                        </div>
                    )}

                    {identities?.data?.length > 0 ? (
                        <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                            {identities.data.map((identity) => {
                                const primarySource =
                                    identity.sources?.find((source) =>
                                        Boolean(source.is_primary),
                                    ) ?? identity.sources?.[0];

                                const company = primarySource?.company;

                                return (
                                    <div
                                        key={identity.id}
                                        className="
                                            group
                                            rounded-2xl
                                            border
                                            border-slate-200
                                            bg-white
                                            p-6
                                            shadow-sm
                                            transition-all
                                            duration-300
                                            hover:-translate-y-1
                                            hover:shadow-xl
                                        "
                                    >
                                        <div className="flex items-start justify-between gap-4">
                                            <div>
                                                <h3 className="text-lg font-black text-slate-900">
                                                    {identity.canonical_name}
                                                </h3>

                                                <p className="mt-1 text-xs text-slate-500">
                                                    {identity.country_name ||
                                                        "Indonesia"}
                                                </p>
                                            </div>

                                            {identity.verification_status ===
                                                "verified" && (
                                                <ShieldCheck
                                                    size={20}
                                                    className="shrink-0 text-emerald-500"
                                                />
                                            )}
                                        </div>

                                        {company && (
                                            <div className="mt-6 space-y-2 text-xs text-slate-600">
                                                {company.city && (
                                                    <p>
                                                        <span className="font-bold">
                                                            Location:
                                                        </span>{" "}
                                                        {company.city}
                                                    </p>
                                                )}

                                                {company.sektor && (
                                                    <p>
                                                        <span className="font-bold">
                                                            Sector:
                                                        </span>{" "}
                                                        {company.sektor}
                                                    </p>
                                                )}
                                            </div>
                                        )}

                                        <div className="mt-6 border-t border-slate-100 pt-5">
                                            {company?.id ? (
                                                <Link
                                                    href={route(
                                                        "companies.passport",
                                                        {
                                                            company: company.id,
                                                        },
                                                    )}
                                                    className="
                                                        inline-flex
                                                        items-center
                                                        gap-2
                                                        text-[10px]
                                                        font-black
                                                        uppercase
                                                        tracking-widest
                                                        text-slate-900
                                                        transition
                                                        group-hover:text-amber-500
                                                    "
                                                >
                                                    View Company Passport
                                                    <ArrowRight size={14} />
                                                </Link>
                                            ) : (
                                                <Link
                                                    href={route(
                                                        "program.digital-directory-visibility",
                                                    )}
                                                    className="
                                                        text-[10px]
                                                        font-black
                                                        uppercase
                                                        tracking-widest
                                                        text-amber-600
                                                    "
                                                >
                                                    Claim & Complete Profile
                                                </Link>
                                            )}
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    ) : (
                        <div className="rounded-2xl border border-slate-200 bg-white p-16 text-center">
                            <Search
                                size={32}
                                className="mx-auto text-slate-300"
                            />

                            <h3 className="mt-4 text-lg font-black text-slate-800">
                                No company found
                            </h3>

                            <p className="mt-2 text-sm text-slate-500">
                                Try another company name or country.
                            </p>
                        </div>
                    )}

                    {/* =====================================================
                        PAGINATION
                    ===================================================== */}

                    {identities?.links?.length > 3 && (
                        <div className="mt-12 flex flex-wrap items-center justify-center gap-2">
                            {identities.links.map((link, index) => {
                                const isDisabled = !link.url;

                                return (
                                    <button
                                        key={index}
                                        type="button"
                                        disabled={isDisabled}
                                        onClick={() => goToPage(link.url)}
                                        className={`
                                            min-w-10
                                            rounded-lg
                                            px-3
                                            py-2
                                            text-xs
                                            font-bold
                                            transition
                                            ${
                                                link.active
                                                    ? "bg-amber-500 text-slate-900 shadow-md"
                                                    : isDisabled
                                                      ? "cursor-not-allowed bg-slate-100 text-slate-300"
                                                      : "bg-white text-slate-600 ring-1 ring-slate-200 hover:bg-slate-100"
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
            </div>
        </AppLayout>
    );
}
