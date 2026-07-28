import OnboardingLayout from "@/Layouts/OnboardingLayout";

import { Head, Link, usePage } from "@inertiajs/react";

import {
    CheckCircle2,
    Clock3,
    Building2,
    ShieldCheck,
    LayoutDashboard,
    Search,
    FileCheck2,
} from "lucide-react";

export default function OwnershipVerificationSubmitted({ claim = null }) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const companyName =
        claim?.claimed_company_name ?? claim?.company?.nama_perusahaan ?? null;

    return (
        <OnboardingLayout>
            <Head
                title={
                    isEn ? "Verification Submitted" : "Verifikasi Telah Dikirim"
                }
            />

            <div className="min-h-screen bg-slate-50 py-16">
                <div className="mx-auto max-w-5xl px-6">
                    {/* Success Header */}

                    <div className="text-center">
                        <div className="flex justify-center">
                            <div
                                className="
                                    flex
                                    h-24
                                    w-24
                                    items-center
                                    justify-center
                                    rounded-full
                                    bg-emerald-100
                                "
                            >
                                <CheckCircle2 className="h-12 w-12 text-emerald-600" />
                            </div>
                        </div>

                        <div
                            className="
                                mt-8
                                inline-flex
                                items-center
                                gap-2
                                rounded-full
                                bg-emerald-500/10
                                px-5
                                py-2
                                text-xs
                                font-black
                                uppercase
                                tracking-widest
                                text-emerald-700
                            "
                        >
                            <ShieldCheck className="h-4 w-4" />

                            {isEn
                                ? "Request Submitted"
                                : "Pengajuan Berhasil Dikirim"}
                        </div>

                        <h1
                            className="
                                mx-auto
                                mt-6
                                max-w-4xl
                                text-4xl
                                font-black
                                text-slate-900
                                md:text-5xl
                            "
                        >
                            {isEn
                                ? "Ownership Verification Is Being Reviewed"
                                : "Verifikasi Kepemilikan Sedang Ditinjau"}
                        </h1>

                        <p
                            className="
                                mx-auto
                                mt-6
                                max-w-3xl
                                text-lg
                                leading-8
                                text-slate-500
                            "
                        >
                            {isEn
                                ? "Your company ownership verification request has been successfully submitted to the DIGESTEX Verification Team."
                                : "Pengajuan verifikasi kepemilikan perusahaan Anda telah berhasil dikirim kepada Tim Verifikasi DIGESTEX."}
                        </p>
                    </div>

                    {/* Company */}

                    {companyName && (
                        <section
                            className="
                                mt-12
                                rounded-[32px]
                                border
                                border-slate-200
                                bg-white
                                p-8
                                shadow-sm
                            "
                        >
                            <div className="flex items-start gap-5">
                                <div
                                    className="
                                        flex
                                        h-16
                                        w-16
                                        shrink-0
                                        items-center
                                        justify-center
                                        rounded-2xl
                                        bg-slate-100
                                    "
                                >
                                    <Building2 className="h-8 w-8 text-slate-700" />
                                </div>

                                <div className="min-w-0">
                                    <div
                                        className="
                                            text-xs
                                            font-black
                                            uppercase
                                            tracking-[0.16em]
                                            text-slate-400
                                        "
                                    >
                                        {isEn
                                            ? "Company Being Verified"
                                            : "Perusahaan yang Diverifikasi"}
                                    </div>

                                    <h2 className="mt-2 text-3xl font-black text-slate-900">
                                        {companyName}
                                    </h2>

                                    {claim?.nib && (
                                        <p className="mt-2 text-sm text-slate-500">
                                            NIB:{" "}
                                            <span className="font-bold text-slate-700">
                                                {claim.nib}
                                            </span>
                                        </p>
                                    )}
                                </div>
                            </div>
                        </section>
                    )}

                    {/* Status */}

                    <section
                        className="
                            mt-8
                            rounded-[32px]
                            bg-slate-900
                            p-8
                            text-white
                            md:p-10
                        "
                    >
                        <div
                            className="
                                flex
                                flex-col
                                gap-8
                                md:flex-row
                                md:items-center
                                md:justify-between
                            "
                        >
                            <div className="flex items-start gap-5">
                                <div
                                    className="
                                        flex
                                        h-14
                                        w-14
                                        shrink-0
                                        items-center
                                        justify-center
                                        rounded-2xl
                                        bg-white/10
                                    "
                                >
                                    <Clock3 className="h-7 w-7 text-amber-300" />
                                </div>

                                <div>
                                    <div
                                        className="
                                            text-xs
                                            font-black
                                            uppercase
                                            tracking-[0.16em]
                                            text-slate-400
                                        "
                                    >
                                        {isEn
                                            ? "Verification Status"
                                            : "Status Verifikasi"}
                                    </div>

                                    <div className="mt-2 text-2xl font-black">
                                        {isEn
                                            ? "Pending Verification"
                                            : "Menunggu Verifikasi"}
                                    </div>

                                    <p
                                        className="
                                            mt-3
                                            max-w-2xl
                                            leading-7
                                            text-slate-300
                                        "
                                    >
                                        {isEn
                                            ? "Your submitted company information and supporting document will be reviewed before management access is granted."
                                            : "Informasi perusahaan dan dokumen pendukung yang Anda kirim akan ditinjau sebelum akses pengelolaan perusahaan diberikan."}
                                    </p>
                                </div>
                            </div>

                            <div
                                className="
                                    inline-flex
                                    shrink-0
                                    items-center
                                    gap-2
                                    self-start
                                    rounded-full
                                    bg-amber-400/10
                                    px-5
                                    py-3
                                    text-sm
                                    font-black
                                    uppercase
                                    text-amber-300
                                "
                            >
                                <Clock3 className="h-4 w-4" />
                                PENDING
                            </div>
                        </div>
                    </section>

                    {/* What Happens Next */}

                    <section
                        className="
                            mt-8
                            rounded-[32px]
                            border
                            border-slate-200
                            bg-white
                            p-8
                            shadow-sm
                            md:p-10
                        "
                    >
                        <h2 className="text-2xl font-black text-slate-900">
                            {isEn
                                ? "What Happens Next?"
                                : "Apa Langkah Selanjutnya?"}
                        </h2>

                        <p className="mt-3 text-slate-500">
                            {isEn
                                ? "DIGESTEX will complete the ownership verification process before connecting your account to the company profile."
                                : "DIGESTEX akan menyelesaikan proses verifikasi kepemilikan sebelum menghubungkan akun Anda dengan profil perusahaan."}
                        </p>

                        <div className="mt-8 grid gap-5 md:grid-cols-3">
                            {/* Step 1 */}

                            <div className="rounded-3xl bg-slate-50 p-6">
                                <div
                                    className="
                                        flex
                                        h-12
                                        w-12
                                        items-center
                                        justify-center
                                        rounded-2xl
                                        bg-emerald-100
                                    "
                                >
                                    <FileCheck2 className="h-6 w-6 text-emerald-600" />
                                </div>

                                <div className="mt-5 text-xs font-black uppercase tracking-widest text-emerald-600">
                                    {isEn ? "Step 1" : "Tahap 1"}
                                </div>

                                <h3 className="mt-2 text-lg font-black text-slate-900">
                                    {isEn
                                        ? "Document Review"
                                        : "Pemeriksaan Dokumen"}
                                </h3>

                                <p className="mt-3 text-sm leading-6 text-slate-500">
                                    {isEn
                                        ? "Our team reviews the NIB or company registration information and supporting document."
                                        : "Tim kami memeriksa NIB atau informasi registrasi perusahaan beserta dokumen pendukung."}
                                </p>
                            </div>

                            {/* Step 2 */}

                            <div className="rounded-3xl bg-slate-50 p-6">
                                <div
                                    className="
                                        flex
                                        h-12
                                        w-12
                                        items-center
                                        justify-center
                                        rounded-2xl
                                        bg-sky-100
                                    "
                                >
                                    <ShieldCheck className="h-6 w-6 text-sky-600" />
                                </div>

                                <div className="mt-5 text-xs font-black uppercase tracking-widest text-sky-600">
                                    {isEn ? "Step 2" : "Tahap 2"}
                                </div>

                                <h3 className="mt-2 text-lg font-black text-slate-900">
                                    {isEn
                                        ? "Ownership Verification"
                                        : "Verifikasi Kepemilikan"}
                                </h3>

                                <p className="mt-3 text-sm leading-6 text-slate-500">
                                    {isEn
                                        ? "DIGESTEX verifies your authority to represent and manage the selected company."
                                        : "DIGESTEX memverifikasi kewenangan Anda untuk mewakili dan mengelola perusahaan yang dipilih."}
                                </p>
                            </div>

                            {/* Step 3 */}

                            <div className="rounded-3xl bg-slate-50 p-6">
                                <div
                                    className="
                                        flex
                                        h-12
                                        w-12
                                        items-center
                                        justify-center
                                        rounded-2xl
                                        bg-violet-100
                                    "
                                >
                                    <Building2 className="h-6 w-6 text-violet-600" />
                                </div>

                                <div className="mt-5 text-xs font-black uppercase tracking-widest text-violet-600">
                                    {isEn ? "Step 3" : "Tahap 3"}
                                </div>

                                <h3 className="mt-2 text-lg font-black text-slate-900">
                                    {isEn
                                        ? "Management Access"
                                        : "Akses Pengelolaan"}
                                </h3>

                                <p className="mt-3 text-sm leading-6 text-slate-500">
                                    {isEn
                                        ? "After approval, your DIGESTEX account will be connected to the company and management access can be activated."
                                        : "Setelah disetujui, akun DIGESTEX Anda akan dihubungkan dengan perusahaan dan akses pengelolaan dapat diaktifkan."}
                                </p>
                            </div>
                        </div>
                    </section>

                    {/* Account Remains Active */}

                    <section
                        className="
                            mt-8
                            rounded-[32px]
                            border
                            border-emerald-200
                            bg-emerald-50
                            p-8
                        "
                    >
                        <div className="flex items-start gap-4">
                            <CheckCircle2 className="mt-1 h-7 w-7 shrink-0 text-emerald-600" />

                            <div>
                                <h2 className="text-xl font-black text-emerald-900">
                                    {isEn
                                        ? "Your DIGESTEX Account Remains Active"
                                        : "Akun DIGESTEX Anda Tetap Aktif"}
                                </h2>

                                <p className="mt-3 leading-7 text-emerald-800">
                                    {isEn
                                        ? "You can continue using your DIGESTEX account while verification is in progress. Only company profile management remains restricted until the ownership request is approved."
                                        : "Anda tetap dapat menggunakan akun DIGESTEX selama proses verifikasi berlangsung. Hanya akses pengelolaan profil perusahaan yang masih dibatasi sampai pengajuan kepemilikan disetujui."}
                                </p>
                            </div>
                        </div>
                    </section>

                    {/* Actions */}

                    <div
                        className="
                            mt-10
                            flex
                            flex-col
                            gap-4
                            sm:flex-row
                            sm:justify-center
                        "
                    >
                        <Link
                            href={route("dashboard")}
                            className="
                                inline-flex
                                items-center
                                justify-center
                                gap-2
                                rounded-2xl
                                bg-emerald-600
                                px-8
                                py-4
                                font-black
                                uppercase
                                text-white
                                transition
                                hover:bg-emerald-700
                            "
                        >
                            <LayoutDashboard className="h-5 w-5" />

                            {isEn ? "GO TO DASHBOARD" : "KE DASHBOARD"}
                        </Link>

                        <Link
                            href={route("onboarding.company-lookup")}
                            className="
                                inline-flex
                                items-center
                                justify-center
                                gap-2
                                rounded-2xl
                                border
                                border-slate-300
                                bg-white
                                px-8
                                py-4
                                font-black
                                uppercase
                                text-slate-700
                                transition
                                hover:bg-slate-100
                            "
                        >
                            <Search className="h-5 w-5" />

                            {isEn
                                ? "BACK TO COMPANY SEARCH"
                                : "KEMBALI KE PENCARIAN"}
                        </Link>
                    </div>

                    {/* Footer */}

                    <div className="mt-12 text-center">
                        <p className="text-sm text-slate-500">
                            {isEn
                                ? "DIGESTEX Company Ownership Verification protects company information and ensures that management access is granted only to authorized representatives."
                                : "Verifikasi Kepemilikan Perusahaan DIGESTEX melindungi informasi perusahaan dan memastikan akses pengelolaan hanya diberikan kepada perwakilan yang berwenang."}
                        </p>
                    </div>
                </div>
            </div>
        </OnboardingLayout>
    );
}
