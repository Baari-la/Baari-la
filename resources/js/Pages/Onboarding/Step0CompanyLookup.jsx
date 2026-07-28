import OnboardingLayout from "@/Layouts/OnboardingLayout";

import { Head, Link, router, useForm, usePage } from "@inertiajs/react";

import { useEffect } from "react";

import {
    Search,
    Building2,
    ArrowRight,
    CheckCircle,
    ShieldCheck,
} from "lucide-react";

export default function Step0CompanyLookup() {
    const { companies = [], filters = {}, locale } = usePage().props;

    const isEn = locale === "en";

    const { data, setData } = useForm({
        keyword: filters.keyword ?? "",
    });

    /*
    |--------------------------------------------------------------------------
    | Live Company Search
    |--------------------------------------------------------------------------
    */

    useEffect(() => {
        const keyword = data.keyword.trim();

        /*
        |--------------------------------------------------------------------------
        | Do Not Search Empty Keyword
        |--------------------------------------------------------------------------
        */

        if (keyword === "") {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Avoid Repeating Same Search
        |--------------------------------------------------------------------------
        */

        const currentKeyword = (filters.keyword ?? "").trim();

        if (keyword === currentKeyword) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Debounced Search
        |--------------------------------------------------------------------------
        */

        const timer = setTimeout(() => {
            router.get(
                route("onboarding.company-lookup"),
                {
                    keyword,
                },
                {
                    preserveState: true,
                    preserveScroll: true,
                    replace: true,

                    only: ["companies", "filters"],
                },
            );
        }, 400);

        return () => clearTimeout(timer);
    }, [data.keyword, filters.keyword]);

    /*
    |--------------------------------------------------------------------------
    | Select Existing Company
    |--------------------------------------------------------------------------
    */

    const selectCompany = (company) => {
        router.visit(
            route("companies.claim.create", {
                company: company.id,
            }),
        );
    };

    return (
        <OnboardingLayout>
            <Head title="Find Your Company" />

            <div className="min-h-screen bg-slate-50 py-16">
                <div className="mx-auto max-w-6xl px-6">
                    {/* Header */}

                    <div className="text-center">
                        <div
                            className="
                                inline-flex
                                rounded-full
                                bg-emerald-500/10
                                px-5
                                py-2
                                text-xs
                                font-black
                                uppercase
                                tracking-widest
                                text-emerald-600
                            "
                        >
                            STEP 0
                        </div>

                        <h1 className="mt-6 text-5xl font-black">
                            {isEn
                                ? "Find Your Company"
                                : "Cari Perusahaan Anda"}
                        </h1>

                        <p className="mx-auto mt-6 max-w-3xl text-lg text-slate-500">
                            {isEn
                                ? "DIGESTEX may already know your company. Search our directory before creating a new profile."
                                : "DIGESTEX mungkin sudah mengenal perusahaan Anda. Cari terlebih dahulu sebelum membuat profil baru."}
                        </p>
                    </div>

                    {/* Search Box */}

                    <div className="mx-auto mt-12 max-w-3xl">
                        <div className="relative">
                            <Search
                                className="
                                    absolute
                                    left-5
                                    top-5
                                    h-6
                                    w-6
                                    text-slate-400
                                "
                            />

                            <input
                                type="text"
                                value={data.keyword}
                                onChange={(e) =>
                                    setData("keyword", e.target.value)
                                }
                                placeholder={
                                    isEn
                                        ? "Search company name..."
                                        : "Cari nama perusahaan..."
                                }
                                className="
                                    w-full
                                    rounded-3xl
                                    border
                                    border-slate-300
                                    bg-white
                                    py-5
                                    pl-14
                                    pr-6
                                    text-lg
                                    outline-none
                                    transition
                                    focus:border-emerald-500
                                    focus:ring-4
                                    focus:ring-emerald-500/10
                                "
                            />
                        </div>
                    </div>

                    {/* Company Search Results */}

                    {companies.length > 0 && (
                        <div className="mx-auto mt-10 max-w-4xl space-y-4">
                            {companies.map((company) => (
                                <div
                                    key={company.id}
                                    className="
                                            rounded-3xl
                                            border
                                            border-slate-200
                                            bg-white
                                            p-6
                                            shadow-sm
                                            transition
                                            hover:border-emerald-300
                                            hover:shadow-md
                                            md:p-8
                                        "
                                >
                                    <div
                                        className="
                                                flex
                                                flex-col
                                                gap-6
                                                lg:flex-row
                                                lg:items-start
                                                lg:justify-between
                                            "
                                    >
                                        {/* Clickable Company */}

                                        <button
                                            type="button"
                                            onClick={() =>
                                                selectCompany(company)
                                            }
                                            className="
                                                    group
                                                    flex
                                                    min-w-0
                                                    flex-1
                                                    items-start
                                                    gap-4
                                                    text-left
                                                "
                                        >
                                            {/* Icon */}

                                            <div
                                                className="
                                                        flex
                                                        h-16
                                                        w-16
                                                        shrink-0
                                                        items-center
                                                        justify-center
                                                        rounded-2xl
                                                        bg-emerald-100
                                                        transition
                                                        group-hover:bg-emerald-200
                                                    "
                                            >
                                                <Building2 className="h-8 w-8 text-emerald-600" />
                                            </div>

                                            {/* Company Details */}

                                            <div className="min-w-0 flex-1">
                                                <h3
                                                    className="
                                                            text-2xl
                                                            font-black
                                                            text-slate-900
                                                            transition
                                                            group-hover:text-emerald-700
                                                        "
                                                >
                                                    {company.nama_perusahaan}
                                                </h3>

                                                {/* Directory Status */}

                                                <div
                                                    className="
                                                            mt-3
                                                            inline-flex
                                                            items-center
                                                            gap-2
                                                            rounded-full
                                                            bg-emerald-50
                                                            px-3
                                                            py-1.5
                                                            text-xs
                                                            font-black
                                                            uppercase
                                                            tracking-wide
                                                            text-emerald-700
                                                        "
                                                >
                                                    <CheckCircle className="h-4 w-4" />

                                                    {isEn
                                                        ? "Found in DIGESTEX Directory"
                                                        : "Ditemukan di Direktori DIGESTEX"}
                                                </div>

                                                {/* Type / Role */}

                                                {(company.company_type ||
                                                    company.company_role) && (
                                                    <p className="mt-4 text-sm font-semibold text-slate-600">
                                                        {[
                                                            company.company_type,
                                                            company.company_role,
                                                        ]
                                                            .filter(Boolean)
                                                            .join(" • ")}
                                                    </p>
                                                )}

                                                {/* Location */}

                                                {(company.city ||
                                                    company.country_name) && (
                                                    <p className="mt-1 text-sm text-slate-500">
                                                        {[
                                                            company.city,
                                                            company.country_name,
                                                        ]
                                                            .filter(Boolean)
                                                            .join(", ")}
                                                    </p>
                                                )}

                                                {/* Products */}

                                                {company.produk && (
                                                    <div className="mt-4">
                                                        <div className="text-xs font-black uppercase tracking-wide text-slate-400">
                                                            {isEn
                                                                ? "Products"
                                                                : "Produk"}
                                                        </div>

                                                        <p className="mt-1 text-sm leading-6 text-slate-600">
                                                            {company.produk}
                                                        </p>
                                                    </div>
                                                )}

                                                {/* Membership */}

                                                {company.membership_type && (
                                                    <div className="mt-3">
                                                        <span
                                                            className="
                                                                    inline-flex
                                                                    rounded-full
                                                                    bg-sky-50
                                                                    px-3
                                                                    py-1
                                                                    text-xs
                                                                    font-bold
                                                                    text-sky-700
                                                                "
                                                        >
                                                            {
                                                                company.membership_type
                                                            }
                                                        </span>
                                                    </div>
                                                )}

                                                {/* Selection Hint */}

                                                <div className="mt-5 flex items-center gap-2 text-sm font-black text-emerald-600">
                                                    {isEn
                                                        ? "Select this company"
                                                        : "Pilih perusahaan ini"}

                                                    <ArrowRight
                                                        className="
                                                                h-4
                                                                w-4
                                                                transition
                                                                group-hover:translate-x-1
                                                            "
                                                    />
                                                </div>
                                            </div>
                                        </button>

                                        {/* CTA */}

                                        <div className="shrink-0">
                                            <button
                                                type="button"
                                                onClick={() =>
                                                    selectCompany(company)
                                                }
                                                className="
                                                        inline-flex
                                                        w-full
                                                        items-center
                                                        justify-center
                                                        gap-2
                                                        rounded-2xl
                                                        bg-emerald-600
                                                        px-6
                                                        py-4
                                                        text-sm
                                                        font-black
                                                        uppercase
                                                        text-white
                                                        transition
                                                        hover:bg-emerald-700
                                                        lg:w-auto
                                                    "
                                            >
                                                {isEn
                                                    ? "VERIFY OWNERSHIP"
                                                    : "VERIFIKASI KEPEMILIKAN"}

                                                <ArrowRight className="h-4 w-4" />
                                            </button>
                                        </div>
                                    </div>

                                    {/* Verification Notice */}

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
                                        <div className="flex items-start gap-3">
                                            <ShieldCheck className="mt-0.5 h-5 w-5 shrink-0 text-emerald-600" />

                                            <div>
                                                <div className="text-xs font-black uppercase tracking-wide text-slate-500">
                                                    {isEn
                                                        ? "Ownership Verification Required"
                                                        : "Memerlukan Verifikasi Kepemilikan"}
                                                </div>

                                                <p className="mt-2 text-sm leading-6 text-slate-600">
                                                    {isEn
                                                        ? "Selecting this company will use the official company name stored in the DIGESTEX Directory. Ownership must be verified before management access is granted."
                                                        : "Dengan memilih perusahaan ini, nama resmi yang tersimpan di Direktori DIGESTEX akan digunakan. Kepemilikan harus diverifikasi sebelum akses pengelolaan diberikan."}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    )}

                    {/* Divider */}

                    {data.keyword.trim() !== "" && (
                        <div className="my-16 flex items-center gap-6">
                            <div className="h-px flex-1 bg-slate-300" />

                            <div className="font-black uppercase text-slate-400">
                                {isEn ? "OR" : "ATAU"}
                            </div>

                            <div className="h-px flex-1 bg-slate-300" />
                        </div>
                    )}

                    {/* New / Unmatched Company */}

                    {data.keyword.trim() !== "" && (
                        <div
                            className="
                                rounded-[40px]
                                bg-slate-900
                                p-8
                                text-center
                                text-white
                                md:p-12
                            "
                        >
                            {/* Icon */}

                            <div className="flex justify-center">
                                <div className="rounded-full bg-white/10 p-6">
                                    <CheckCircle className="h-12 w-12 text-emerald-400" />
                                </div>
                            </div>

                            {/* Title */}

                            <h2 className="mt-8 text-3xl font-black md:text-4xl">
                                {isEn
                                    ? "Can't Find the Right Company?"
                                    : "Tidak Menemukan Perusahaan yang Tepat?"}
                            </h2>

                            {/* Description */}

                            <p className="mx-auto mt-4 max-w-2xl leading-7 text-slate-300">
                                {isEn
                                    ? "If your company is not listed above, you can continue using the company name you entered. DIGESTEX will verify the company and your authority to represent it before granting management access."
                                    : "Jika perusahaan Anda tidak ditemukan di atas, Anda dapat melanjutkan menggunakan nama perusahaan yang telah dimasukkan. DIGESTEX akan memverifikasi perusahaan dan kewenangan Anda untuk mewakilinya sebelum memberikan akses pengelolaan."}
                            </p>

                            {/* Company Name Preview */}

                            <div
                                className="
                                    mx-auto
                                    mt-8
                                    max-w-xl
                                    rounded-3xl
                                    border
                                    border-white/10
                                    bg-white/5
                                    px-6
                                    py-5
                                "
                            >
                                <div
                                    className="
                                        text-xs
                                        font-black
                                        uppercase
                                        tracking-[0.18em]
                                        text-slate-400
                                    "
                                >
                                    {isEn
                                        ? "Company Name to Verify"
                                        : "Nama Perusahaan yang Akan Diverifikasi"}
                                </div>

                                <div className="mt-2 text-xl font-black text-white md:text-2xl">
                                    {data.keyword.trim()}
                                </div>
                            </div>

                            {/* Verification Notice */}

                            <div
                                className="
                                    mx-auto
                                    mt-6
                                    max-w-2xl
                                    rounded-2xl
                                    bg-emerald-500/10
                                    px-6
                                    py-4
                                    text-sm
                                    leading-6
                                    text-emerald-200
                                "
                            >
                                {isEn
                                    ? "This does not create a new company profile yet. You will first be asked to provide your NIB or company registration information and supporting verification document."
                                    : "Tahap ini belum membuat profil perusahaan baru. Anda akan diminta memberikan NIB atau informasi registrasi perusahaan beserta dokumen pendukung untuk proses verifikasi."}
                            </div>

                            {/* Manual Action */}

                            <Link
                                href={route("companies.claim.create-manual", {
                                    company_name: data.keyword.trim(),
                                })}
                                className="
                                    mt-8
                                    inline-flex
                                    items-center
                                    gap-2
                                    rounded-2xl
                                    bg-emerald-600
                                    px-8
                                    py-5
                                    font-black
                                    uppercase
                                    text-white
                                    transition
                                    hover:bg-emerald-500
                                "
                            >
                                {isEn
                                    ? "CONTINUE TO VERIFICATION"
                                    : "LANJUTKAN KE VERIFIKASI"}

                                <ArrowRight className="h-5 w-5" />
                            </Link>

                            <p className="mt-5 text-xs leading-5 text-slate-400">
                                {isEn
                                    ? "Please check the search results above carefully before continuing."
                                    : "Pastikan terlebih dahulu bahwa perusahaan Anda memang tidak terdapat pada hasil pencarian di atas."}
                            </p>
                        </div>
                    )}

                    {/* Footer */}

                    <div className="mt-10 text-center text-sm text-slate-500">
                        {isEn
                            ? "Digital Company Passport™ is the gateway to the DIGESTEX Global Textile Intelligence Ecosystem."
                            : "Digital Company Passport™ adalah gerbang menuju DIGESTEX Global Textile Intelligence Ecosystem."}
                    </div>
                </div>
            </div>
        </OnboardingLayout>
    );
}
