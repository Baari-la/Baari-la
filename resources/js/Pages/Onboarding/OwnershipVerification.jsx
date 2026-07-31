import { Head, Link, useForm, usePage } from "@inertiajs/react";

import {
    ArrowLeft,
    ArrowRight,
    Building2,
    CheckCircle2,
    FileCheck2,
    FileText,
    Info,
    LockKeyhole,
    Mail,
    Phone,
    ShieldCheck,
    Upload,
    User,
} from "lucide-react";

export default function OwnershipVerification({
    company = null,
    companyIdentityId = null,
    canonicalCompany = false,
    manualCompany = false,
    claimedCompanyName = "",
    existingClaim = null,
    user = null,
}) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    /*
    |--------------------------------------------------------------------------
    | Company Name
    |--------------------------------------------------------------------------
    |
    | Existing company:
    | company.name berasal dari master companies.
    |
    | Manual company:
    | claimedCompanyName berasal dari nama yang dimasukkan user.
    |
    */

    const initialCompanyName = company?.name ?? claimedCompanyName ?? "";

    /*
    |--------------------------------------------------------------------------
    | Form
    |--------------------------------------------------------------------------
    */

    const { data, setData, post, processing, errors, progress } = useForm({
        /*
    |--------------------------------------------------------------------------
    | Company Identity
    |--------------------------------------------------------------------------
    */

        company_identity_id: canonicalCompany ? companyIdentityId : null,

        /*
    |--------------------------------------------------------------------------
    | Legacy Company
    |--------------------------------------------------------------------------
    */

        company_id:
            !canonicalCompany && !manualCompany ? (company?.id ?? null) : null,

        claimed_company_name: initialCompanyName,

        full_name: existingClaim?.full_name ?? user?.name ?? "",

        position: existingClaim?.position ?? "",

        phone: existingClaim?.phone ?? "",

        nib: existingClaim?.nib ?? "",

        verification_document_type:
            existingClaim?.verification_document_type ?? "",

        verification_document: null,

        notes: existingClaim?.notes ?? "",

        declaration: false,
    });

    /*
    |--------------------------------------------------------------------------
    | Submit
    |--------------------------------------------------------------------------
    */

    const submit = (event) => {
        event.preventDefault();

        if (!data.declaration) {
            return;
        }

        post(route("companies.claim.store"), {
            forceFormData: true,

            preserveScroll: true,
        });
    };

    const companyDisplayName = company?.name || data.claimed_company_name;

    return (
        <>
            <Head
                title={
                    isEn
                        ? "Ownership Verification"
                        : "Verifikasi Kepemilikan Perusahaan"
                }
            />

            <div className="min-h-screen bg-slate-50">
                {/* Header */}

                <header className="border-b border-slate-200 bg-white">
                    <div className="mx-auto flex max-w-7xl items-center justify-between px-6 py-5">
                        <div className="flex items-center gap-3">
                            <div className="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-900 text-white">
                                <ShieldCheck className="h-6 w-6" />
                            </div>

                            <div>
                                <div className="font-black text-slate-900">
                                    DIGESTEX
                                </div>

                                <div className="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                                    {isEn
                                        ? "Company Verification"
                                        : "Verifikasi Perusahaan"}
                                </div>
                            </div>
                        </div>

                        <div className="hidden items-center gap-2 text-sm font-semibold text-slate-500 md:flex">
                            <LockKeyhole className="h-4 w-4" />

                            {isEn ? "Secure Verification" : "Verifikasi Aman"}
                        </div>
                    </div>
                </header>

                <main className="mx-auto max-w-6xl px-6 py-10 lg:py-14">
                    <div className="space-y-8">
                        {/* Page Header */}

                        <section className="text-center">
                            <div className="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-emerald-100">
                                <ShieldCheck className="h-8 w-8 text-emerald-600" />
                            </div>

                            <p className="mt-6 text-sm font-black uppercase tracking-[0.2em] text-emerald-600">
                                {isEn
                                    ? "Ownership Verification"
                                    : "Verifikasi Kepemilikan"}
                            </p>

                            <h1 className="mx-auto mt-4 max-w-4xl text-4xl font-black tracking-tight text-slate-900 md:text-5xl">
                                {isEn
                                    ? "Verify Your Company Ownership"
                                    : "Verifikasi Kepemilikan Perusahaan Anda"}
                            </h1>

                            <p className="mx-auto mt-5 max-w-3xl text-lg leading-8 text-slate-600">
                                {isEn
                                    ? "To protect company information, DIGESTEX verifies your authority to represent the company before granting profile management access."
                                    : "Untuk melindungi informasi perusahaan, DIGESTEX memverifikasi kewenangan Anda untuk mewakili perusahaan sebelum memberikan akses pengelolaan profil."}
                            </p>
                        </section>
                        <InputError message={errors.company_identity_id} />
                        <InputError message={errors.company_id} />
                        {/* Existing Pending Claim */}

                        {existingClaim && (
                            <section className="rounded-3xl border border-amber-200 bg-amber-50 p-6">
                                <div className="flex gap-4">
                                    <FileCheck2 className="mt-0.5 h-6 w-6 shrink-0 text-amber-600" />

                                    <div>
                                        <h2 className="font-black text-amber-900">
                                            {isEn
                                                ? "Verification Already Submitted"
                                                : "Verifikasi Sudah Diajukan"}
                                        </h2>

                                        <p className="mt-2 leading-7 text-amber-800">
                                            {isEn
                                                ? "You already have a pending ownership verification request for this company. DIGESTEX will review the submitted information before granting company management access."
                                                : "Anda sudah memiliki pengajuan verifikasi kepemilikan untuk perusahaan ini. DIGESTEX akan meninjau informasi yang diajukan sebelum memberikan akses pengelolaan perusahaan."}
                                        </p>
                                    </div>
                                </div>
                            </section>
                        )}

                        {/* Company */}

                        <section className="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                            <div className="border-b border-slate-100 px-7 py-6 md:px-8">
                                <div className="flex items-center gap-3">
                                    <Building2 className="h-6 w-6 text-sky-600" />

                                    <div>
                                        <h2 className="text-xl font-black text-slate-900">
                                            {isEn
                                                ? "Company to Verify"
                                                : "Perusahaan yang Diverifikasi"}
                                        </h2>

                                        <p className="mt-1 text-sm text-slate-500">
                                            {manualCompany
                                                ? isEn
                                                    ? "This company has not been matched with the DIGESTEX company database."
                                                    : "Perusahaan ini belum dicocokkan dengan database perusahaan DIGESTEX."
                                                : isEn
                                                  ? "This company was selected from the DIGESTEX company database."
                                                  : "Perusahaan ini dipilih dari database perusahaan DIGESTEX."}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div className="p-7 md:p-8">
                                {!manualCompany && company ? (
                                    <div className="rounded-3xl bg-slate-50 p-6">
                                        <div className="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
                                            <div>
                                                <div className="text-2xl font-black text-slate-900">
                                                    {companyDisplayName}
                                                </div>

                                                {(company.city ||
                                                    company.country) && (
                                                    <div className="mt-2 text-slate-500">
                                                        {[
                                                            company.city,
                                                            company.country,
                                                        ]
                                                            .filter(Boolean)
                                                            .join(", ")}
                                                    </div>
                                                )}
                                            </div>

                                            <div className="inline-flex w-fit items-center gap-2 rounded-full bg-sky-100 px-4 py-2 text-sm font-bold text-sky-700">
                                                <CheckCircle2 className="h-4 w-4" />

                                                {isEn
                                                    ? "DATABASE MATCH"
                                                    : "TERDAFTAR DI DATABASE"}
                                            </div>
                                        </div>
                                    </div>
                                ) : (
                                    <div>
                                        <label
                                            htmlFor="claimed_company_name"
                                            className="text-sm font-bold text-slate-700"
                                        >
                                            {isEn
                                                ? "Company Name"
                                                : "Nama Perusahaan"}{" "}
                                            *
                                        </label>

                                        <input
                                            id="claimed_company_name"
                                            type="text"
                                            value={data.claimed_company_name}
                                            onChange={(event) =>
                                                setData(
                                                    "claimed_company_name",
                                                    event.target.value,
                                                )
                                            }
                                            placeholder={
                                                isEn
                                                    ? "Enter the legal company name"
                                                    : "Masukkan nama legal perusahaan"
                                            }
                                            className="mt-2 w-full rounded-2xl border-slate-300 px-4 py-3.5 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                        />

                                        <InputError
                                            message={
                                                errors.claimed_company_name
                                            }
                                        />

                                        <div className="mt-4 flex gap-3 rounded-2xl bg-sky-50 p-4 text-sm leading-6 text-sky-800">
                                            <Info className="mt-0.5 h-5 w-5 shrink-0" />

                                            <p>
                                                {isEn
                                                    ? "Entering a company name here does not create a new company in the DIGESTEX directory. The company will first be reviewed and verified by our team."
                                                    : "Memasukkan nama perusahaan di sini tidak langsung membuat perusahaan baru di Direktori DIGESTEX. Perusahaan akan ditinjau dan diverifikasi terlebih dahulu oleh tim kami."}
                                            </p>
                                        </div>
                                    </div>
                                )}
                            </div>
                        </section>

                        <form onSubmit={submit} className="space-y-8">
                            {/* Applicant */}

                            <section className="rounded-3xl border border-slate-200 bg-white p-7 shadow-sm md:p-8">
                                <div className="flex items-center gap-3">
                                    <User className="h-6 w-6 text-indigo-600" />

                                    <div>
                                        <h2 className="text-xl font-black text-slate-900">
                                            {isEn
                                                ? "Applicant Information"
                                                : "Informasi Pemohon"}
                                        </h2>

                                        <p className="mt-1 text-sm text-slate-500">
                                            {isEn
                                                ? "Tell us who is requesting management access."
                                                : "Informasikan siapa yang mengajukan akses pengelolaan perusahaan."}
                                        </p>
                                    </div>
                                </div>

                                <div className="mt-8 grid gap-6 md:grid-cols-2">
                                    <Field>
                                        <Label>
                                            {isEn
                                                ? "Full Name"
                                                : "Nama Lengkap"}{" "}
                                            *
                                        </Label>

                                        <div className="relative">
                                            <User className="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />

                                            <input
                                                type="text"
                                                value={data.full_name}
                                                onChange={(event) =>
                                                    setData(
                                                        "full_name",
                                                        event.target.value,
                                                    )
                                                }
                                                className="w-full rounded-2xl border-slate-300 py-3.5 pl-12 pr-4 focus:border-emerald-500 focus:ring-emerald-500"
                                            />
                                        </div>

                                        <InputError
                                            message={errors.full_name}
                                        />
                                    </Field>

                                    <Field>
                                        <Label>
                                            {isEn
                                                ? "Position / Job Title"
                                                : "Jabatan"}{" "}
                                            *
                                        </Label>

                                        <input
                                            type="text"
                                            value={data.position}
                                            onChange={(event) =>
                                                setData(
                                                    "position",
                                                    event.target.value,
                                                )
                                            }
                                            placeholder={
                                                isEn
                                                    ? "e.g. Director, Owner, Manager"
                                                    : "Contoh: Direktur, Pemilik, Manager"
                                            }
                                            className="w-full rounded-2xl border-slate-300 px-4 py-3.5 focus:border-emerald-500 focus:ring-emerald-500"
                                        />

                                        <InputError message={errors.position} />
                                    </Field>

                                    <Field>
                                        <Label>
                                            {isEn
                                                ? "Verified Account Email"
                                                : "Email Akun Terverifikasi"}
                                        </Label>

                                        <div className="relative">
                                            <Mail className="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />

                                            <input
                                                type="email"
                                                value={user?.email ?? ""}
                                                disabled
                                                className="w-full cursor-not-allowed rounded-2xl border-slate-200 bg-slate-100 py-3.5 pl-12 pr-4 text-slate-600"
                                            />
                                        </div>

                                        <p className="text-xs leading-5 text-slate-500">
                                            {isEn
                                                ? "The claim will be linked to your verified DIGESTEX account."
                                                : "Pengajuan akan terhubung dengan akun DIGESTEX Anda yang telah terverifikasi."}
                                        </p>
                                    </Field>

                                    <Field>
                                        <Label>
                                            {isEn
                                                ? "Phone / WhatsApp"
                                                : "Telepon / WhatsApp"}{" "}
                                            *
                                        </Label>

                                        <div className="relative">
                                            <Phone className="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />

                                            <input
                                                type="text"
                                                value={data.phone}
                                                onChange={(event) =>
                                                    setData(
                                                        "phone",
                                                        event.target.value,
                                                    )
                                                }
                                                placeholder="+62..."
                                                className="w-full rounded-2xl border-slate-300 py-3.5 pl-12 pr-4 focus:border-emerald-500 focus:ring-emerald-500"
                                            />
                                        </div>

                                        <InputError message={errors.phone} />
                                    </Field>
                                </div>
                            </section>

                            {/* Verification */}

                            <section className="rounded-3xl border border-slate-200 bg-white p-7 shadow-sm md:p-8">
                                <div className="flex items-center gap-3">
                                    <FileCheck2 className="h-6 w-6 text-emerald-600" />

                                    <div>
                                        <h2 className="text-xl font-black text-slate-900">
                                            {isEn
                                                ? "Company Verification"
                                                : "Verifikasi Perusahaan"}
                                        </h2>

                                        <p className="mt-1 text-sm text-slate-500">
                                            {isEn
                                                ? "Provide company information that can support the ownership verification."
                                                : "Berikan informasi perusahaan yang dapat mendukung proses verifikasi kepemilikan."}
                                        </p>
                                    </div>
                                </div>

                                <div className="mt-8 grid gap-6 md:grid-cols-2">
                                    <Field>
                                        <Label>
                                            {isEn
                                                ? "NIB / Business Registration Number"
                                                : "NIB / Nomor Registrasi Perusahaan"}{" "}
                                            *
                                        </Label>

                                        <input
                                            type="text"
                                            value={data.nib}
                                            onChange={(event) =>
                                                setData(
                                                    "nib",
                                                    event.target.value,
                                                )
                                            }
                                            placeholder={
                                                isEn
                                                    ? "Enter registration number"
                                                    : "Masukkan nomor registrasi"
                                            }
                                            className="w-full rounded-2xl border-slate-300 px-4 py-3.5 focus:border-emerald-500 focus:ring-emerald-500"
                                        />

                                        <InputError message={errors.nib} />
                                    </Field>

                                    <Field>
                                        <Label>
                                            {isEn
                                                ? "Verification Document Type"
                                                : "Jenis Dokumen Verifikasi"}{" "}
                                            *
                                        </Label>

                                        <select
                                            value={
                                                data.verification_document_type
                                            }
                                            onChange={(event) =>
                                                setData(
                                                    "verification_document_type",
                                                    event.target.value,
                                                )
                                            }
                                            className="w-full rounded-2xl border-slate-300 px-4 py-3.5 focus:border-emerald-500 focus:ring-emerald-500"
                                        >
                                            <option value="">
                                                {isEn
                                                    ? "Select document type"
                                                    : "Pilih jenis dokumen"}
                                            </option>

                                            <option value="NIB">NIB</option>

                                            <option value="Business Registration">
                                                {isEn
                                                    ? "Business Registration"
                                                    : "Registrasi Perusahaan"}
                                            </option>

                                            <option value="Authorization Letter">
                                                {isEn
                                                    ? "Authorization Letter"
                                                    : "Surat Kuasa / Otorisasi"}
                                            </option>

                                            <option value="Other Corporate Document">
                                                {isEn
                                                    ? "Other Corporate Document"
                                                    : "Dokumen Perusahaan Lainnya"}
                                            </option>
                                        </select>

                                        <InputError
                                            message={
                                                errors.verification_document_type
                                            }
                                        />
                                    </Field>
                                </div>

                                {/* Upload */}

                                <div className="mt-6">
                                    <Label>
                                        {isEn
                                            ? "Verification Document"
                                            : "Dokumen Verifikasi"}{" "}
                                        *
                                    </Label>

                                    <label
                                        htmlFor="verification_document"
                                        className="
                                            mt-2
                                            flex
                                            cursor-pointer
                                            flex-col
                                            items-center
                                            justify-center
                                            rounded-3xl
                                            border-2
                                            border-dashed
                                            border-slate-300
                                            bg-slate-50
                                            px-6
                                            py-10
                                            text-center
                                            transition
                                            hover:border-emerald-400
                                            hover:bg-emerald-50
                                        "
                                    >
                                        <div className="flex h-14 w-14 items-center justify-center rounded-2xl bg-white shadow-sm">
                                            <Upload className="h-6 w-6 text-slate-600" />
                                        </div>

                                        <div className="mt-4 font-black text-slate-800">
                                            {data.verification_document
                                                ? data.verification_document
                                                      .name
                                                : isEn
                                                  ? "Upload company verification document"
                                                  : "Upload dokumen verifikasi perusahaan"}
                                        </div>

                                        <div className="mt-2 text-sm text-slate-500">
                                            {isEn
                                                ? "PDF, JPG or PNG — maximum 10 MB"
                                                : "PDF, JPG atau PNG — maksimum 10 MB"}
                                        </div>

                                        <input
                                            id="verification_document"
                                            type="file"
                                            accept=".pdf,.jpg,.jpeg,.png"
                                            className="hidden"
                                            onChange={(event) =>
                                                setData(
                                                    "verification_document",
                                                    event.target.files?.[0] ??
                                                        null,
                                                )
                                            }
                                        />
                                    </label>

                                    <InputError
                                        message={errors.verification_document}
                                    />

                                    {progress && (
                                        <div className="mt-4">
                                            <div className="mb-2 flex justify-between text-xs font-bold text-slate-500">
                                                <span>
                                                    {isEn
                                                        ? "Uploading"
                                                        : "Mengunggah"}
                                                </span>

                                                <span>
                                                    {progress.percentage}%
                                                </span>
                                            </div>

                                            <div className="h-2 overflow-hidden rounded-full bg-slate-200">
                                                <div
                                                    className="h-full bg-emerald-500 transition-all"
                                                    style={{
                                                        width: `${progress.percentage}%`,
                                                    }}
                                                />
                                            </div>
                                        </div>
                                    )}
                                </div>

                                {/* Notes */}

                                <div className="mt-6">
                                    <Label>
                                        {isEn
                                            ? "Additional Notes"
                                            : "Catatan Tambahan"}
                                    </Label>

                                    <textarea
                                        rows={4}
                                        value={data.notes}
                                        onChange={(event) =>
                                            setData("notes", event.target.value)
                                        }
                                        placeholder={
                                            isEn
                                                ? "Add information that may help our verification team."
                                                : "Tambahkan informasi yang dapat membantu tim verifikasi kami."
                                        }
                                        className="mt-2 w-full rounded-2xl border-slate-300 px-4 py-3.5 focus:border-emerald-500 focus:ring-emerald-500"
                                    />

                                    <InputError message={errors.notes} />
                                </div>
                            </section>

                            {/* Security */}

                            <section className="rounded-3xl bg-slate-900 p-7 text-white md:p-8">
                                <div className="flex gap-4">
                                    <LockKeyhole className="mt-1 h-7 w-7 shrink-0 text-emerald-400" />

                                    <div>
                                        <h2 className="text-xl font-black">
                                            {isEn
                                                ? "Your Account Remains Active"
                                                : "Akun Anda Tetap Aktif"}
                                        </h2>

                                        <p className="mt-3 max-w-3xl leading-7 text-slate-300">
                                            {isEn
                                                ? "You can continue using your DIGESTEX account while verification is pending. Company profile management access will only be enabled after your ownership verification has been approved."
                                                : "Anda tetap dapat menggunakan akun DIGESTEX selama proses verifikasi berlangsung. Akses untuk mengelola profil perusahaan baru akan diberikan setelah verifikasi kepemilikan disetujui."}
                                        </p>
                                    </div>
                                </div>
                            </section>

                            {/* Declaration */}

                            <section className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                                <label className="flex cursor-pointer items-start gap-4">
                                    <input
                                        type="checkbox"
                                        checked={data.declaration}
                                        onChange={(event) =>
                                            setData(
                                                "declaration",
                                                event.target.checked,
                                            )
                                        }
                                        className="mt-1 h-5 w-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                                    />

                                    <span className="text-sm leading-7 text-slate-600">
                                        {isEn
                                            ? `I confirm that I am authorized to represent ${companyDisplayName || "this company"} and that the information and documents submitted are accurate.`
                                            : `Saya menyatakan bahwa saya berwenang mewakili ${companyDisplayName || "perusahaan ini"} dan informasi serta dokumen yang saya ajukan adalah benar.`}
                                    </span>
                                </label>
                            </section>

                            {/* Actions */}

                            <div className="flex flex-col-reverse gap-4 pb-10 sm:flex-row sm:items-center sm:justify-between">
                                <Link
                                    href={route("onboarding.company-lookup")}
                                    className="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-300 bg-white px-6 py-4 font-black text-slate-700 transition hover:bg-slate-100"
                                >
                                    <ArrowLeft className="h-5 w-5" />

                                    {isEn
                                        ? "BACK TO COMPANY SEARCH"
                                        : "KEMBALI KE PENCARIAN"}
                                </Link>

                                <button
                                    type="submit"
                                    disabled={
                                        processing ||
                                        !data.declaration ||
                                        existingClaim
                                    }
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
                                        text-white
                                        transition
                                        hover:bg-emerald-700
                                        disabled:cursor-not-allowed
                                        disabled:opacity-50
                                    "
                                >
                                    {processing
                                        ? isEn
                                            ? "SUBMITTING..."
                                            : "MENGIRIM..."
                                        : existingClaim
                                          ? isEn
                                              ? "VERIFICATION PENDING"
                                              : "MENUNGGU VERIFIKASI"
                                          : isEn
                                            ? "SUBMIT FOR VERIFICATION"
                                            : "AJUKAN VERIFIKASI"}

                                    {!processing && !existingClaim && (
                                        <ArrowRight className="h-5 w-5" />
                                    )}
                                </button>
                            </div>
                        </form>
                    </div>
                </main>
            </div>
        </>
    );
}

/*
|--------------------------------------------------------------------------
| Small UI Components
|--------------------------------------------------------------------------
*/

function Field({ children }) {
    return <div className="space-y-2">{children}</div>;
}

function Label({ children }) {
    return <div className="text-sm font-bold text-slate-700">{children}</div>;
}

function InputError({ message }) {
    if (!message) {
        return null;
    }

    return <p className="text-sm font-semibold text-red-600">{message}</p>;
}
