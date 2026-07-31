import OnboardingLayout from "@/Layouts/OnboardingLayout";

import { Head, Link, router, useForm, usePage } from "@inertiajs/react";

import { useEffect } from "react";

import {
    Search,
    Building2,
    ArrowRight,
    CheckCircle,
    ShieldCheck,
    Layers3,
    MapPin,
    Database,
    BadgeCheck,
} from "lucide-react";

export default function Step0CompanyLookup() {
    const {
        companies = [],
        filters = {},
        lookup = {},
        locale,
    } = usePage().props;

    const isEn = locale === "en";

    const { data, setData } = useForm({
        keyword: filters.keyword ?? "",
    });

    /*
    |--------------------------------------------------------------------------
    | Live Canonical Company Search
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

                    only: ["companies", "filters", "lookup"],
                },
            );
        }, 400);

        return () => clearTimeout(timer);
    }, [data.keyword, filters.keyword]);

    /*
    |--------------------------------------------------------------------------
    | Select Canonical Company Identity
    |--------------------------------------------------------------------------
    */

    const selectCompany = (company) => {
        const companyIdentityId = company.company_identity_id ?? company.id;

        if (!companyIdentityId) {
            return;
        }

        router.visit(
            route("companies.claim.create-identity", {
                companyIdentity: companyIdentityId,
            }),
        );
    };

    /*
    |--------------------------------------------------------------------------
    | Capability Label
    |--------------------------------------------------------------------------
    */

    const capabilityLabel = (capability) => {
        const labels = {
            fiber_manufacturer: isEn ? "Fiber Manufacturer" : "Produsen Serat",

            yarn_spinner: isEn ? "Yarn Spinner" : "Pemintalan Benang",

            weaving_mill: isEn ? "Weaving Mill" : "Pabrik Tenun",

            knitting_mill: isEn ? "Knitting Mill" : "Pabrik Rajut",

            dyeing_finishing_mill: isEn
                ? "Dyeing & Finishing"
                : "Dyeing & Finishing",

            printing_mill: isEn ? "Printing Mill" : "Printing",

            garment_manufacturer: isEn
                ? "Garment Manufacturer"
                : "Produsen Garmen",

            trading_company: isEn ? "Trading Company" : "Perusahaan Trading",

            testing_laboratory: isEn
                ? "Testing Laboratory"
                : "Laboratorium Pengujian",

            certification_body: isEn
                ? "Certification Body"
                : "Lembaga Sertifikasi",
        };

        if (labels[capability]) {
            return labels[capability];
        }

        return capability
            .replaceAll("_", " ")
            .replace(/\b\w/g, (character) => character.toUpperCase());
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
                                ? "Search the DIGESTEX company identity directory before creating or verifying a new company profile."
                                : "Cari identitas perusahaan di Direktori DIGESTEX sebelum membuat atau memverifikasi profil perusahaan baru."}
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

                        {data.keyword.trim() !== "" && lookup?.canonical && (
                            <div className="mt-3 flex items-center justify-center gap-2 text-xs font-bold text-slate-400">
                                <Database className="h-4 w-4" />

                                {isEn
                                    ? "Searching canonical DIGESTEX company identities"
                                    : "Mencari identitas perusahaan canonical DIGESTEX"}
                            </div>
                        )}
                    </div>

                    {/* Company Search Results */}

                    {companies.length > 0 && (
                        <div className="mx-auto mt-10 max-w-4xl space-y-4">
                            {companies.map((company) => {
                                const capabilities = Array.isArray(
                                    company.capabilities,
                                )
                                    ? company.capabilities
                                    : [];

                                const sourceCount = Number(
                                    company.source_count ?? 0,
                                );

                                const verified =
                                    company.verification_status === "verified";

                                return (
                                    <div
                                        key={
                                            company.company_identity_id ??
                                            company.id
                                        }
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
                                            {/* Company */}

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

                                                {/* Details */}

                                                <div className="min-w-0 flex-1">
                                                    <div className="flex flex-wrap items-center gap-3">
                                                        <h3
                                                            className="
                                                                    text-2xl
                                                                    font-black
                                                                    text-slate-900
                                                                    transition
                                                                    group-hover:text-emerald-700
                                                                "
                                                        >
                                                            {company.canonical_name ??
                                                                company.name}
                                                        </h3>

                                                        {verified && (
                                                            <BadgeCheck className="h-5 w-5 text-emerald-600" />
                                                        )}
                                                    </div>

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
                                                            ? "DIGESTEX Company Identity"
                                                            : "Identitas Perusahaan DIGESTEX"}
                                                    </div>

                                                    {/* Country */}

                                                    {company.country_name && (
                                                        <div className="mt-4 flex items-center gap-2 text-sm text-slate-500">
                                                            <MapPin className="h-4 w-4 shrink-0" />

                                                            {
                                                                company.country_name
                                                            }
                                                        </div>
                                                    )}

                                                    {/* Capabilities */}

                                                    {capabilities.length >
                                                        0 && (
                                                        <div className="mt-5">
                                                            <div className="flex items-center gap-2 text-xs font-black uppercase tracking-wide text-slate-400">
                                                                <Layers3 className="h-4 w-4" />

                                                                {isEn
                                                                    ? "Capabilities"
                                                                    : "Kapabilitas"}
                                                            </div>

                                                            <div className="mt-3 flex flex-wrap gap-2">
                                                                {capabilities.map(
                                                                    (
                                                                        capability,
                                                                    ) => (
                                                                        <span
                                                                            key={
                                                                                capability
                                                                            }
                                                                            className="
                                                                                    rounded-full
                                                                                    bg-slate-100
                                                                                    px-3
                                                                                    py-1.5
                                                                                    text-xs
                                                                                    font-bold
                                                                                    text-slate-700
                                                                                "
                                                                        >
                                                                            {capabilityLabel(
                                                                                capability,
                                                                            )}
                                                                        </span>
                                                                    ),
                                                                )}
                                                            </div>
                                                        </div>
                                                    )}

                                                    {/* Evidence */}

                                                    {sourceCount > 0 && (
                                                        <div className="mt-5 text-xs leading-5 text-slate-400">
                                                            {isEn
                                                                ? `${sourceCount} directory source record${sourceCount === 1 ? "" : "s"} consolidated into this company identity.`
                                                                : `${sourceCount} record sumber direktori dikonsolidasikan ke dalam identitas perusahaan ini.`}
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
                                                            ? "This is a canonical DIGESTEX company identity. Ownership must be verified before management access is granted."
                                                            : "Ini merupakan identitas perusahaan canonical DIGESTEX. Kepemilikan harus diverifikasi sebelum akses pengelolaan diberikan."}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    )}

                    {/* No Result */}

                    {data.keyword.trim() !== "" && companies.length === 0 && (
                        <div className="mx-auto mt-10 max-w-3xl rounded-3xl border border-slate-200 bg-white p-8 text-center">
                            <Building2 className="mx-auto h-10 w-10 text-slate-300" />

                            <h3 className="mt-4 text-xl font-black text-slate-800">
                                {isEn
                                    ? "No matching company identity found"
                                    : "Identitas perusahaan tidak ditemukan"}
                            </h3>

                            <p className="mt-2 text-sm leading-6 text-slate-500">
                                {isEn
                                    ? "Check the company name or continue below to submit the company for verification."
                                    : "Periksa kembali nama perusahaan atau lanjutkan di bawah untuk mengajukan perusahaan untuk verifikasi."}
                            </p>
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
                            <div className="flex justify-center">
                                <div className="rounded-full bg-white/10 p-6">
                                    <CheckCircle className="h-12 w-12 text-emerald-400" />
                                </div>
                            </div>

                            <h2 className="mt-8 text-3xl font-black md:text-4xl">
                                {isEn
                                    ? "Can't Find the Right Company?"
                                    : "Tidak Menemukan Perusahaan yang Tepat?"}
                            </h2>

                            <p className="mx-auto mt-4 max-w-2xl leading-7 text-slate-300">
                                {isEn
                                    ? "If your company is not listed above, continue with the company name you entered. DIGESTEX will verify the company and your authority to represent it."
                                    : "Jika perusahaan Anda tidak ditemukan di atas, lanjutkan menggunakan nama perusahaan yang telah dimasukkan. DIGESTEX akan memverifikasi perusahaan dan kewenangan Anda untuk mewakilinya."}
                            </p>

                            {/* Company Name */}

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

                            {/* Notice */}

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
                                    ? "This does not create a new company identity yet. Company registration information and supporting documents will be reviewed first."
                                    : "Tahap ini belum membuat identitas perusahaan baru. Informasi registrasi perusahaan dan dokumen pendukung akan diperiksa terlebih dahulu."}
                            </div>

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
                                    ? "Please check the canonical search results above carefully before continuing."
                                    : "Pastikan terlebih dahulu bahwa perusahaan Anda memang tidak terdapat pada hasil pencarian canonical di atas."}
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
