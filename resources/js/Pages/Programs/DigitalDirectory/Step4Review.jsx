import ProgramNavbar from "@/Components/Program/ProgramNavbar";
import { Link, usePage } from "@inertiajs/react";

import {
    Building2,
    User,
    Mail,
    Phone,
    Globe,
    CreditCard,
    ArrowRight,
    ArrowLeft,
    CheckCircle2,
} from "lucide-react";

export default function Step4Review({ company }) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const prices = {
        "Verified Company": "Rp 2.500.000",

        "Visibility Partner": "Rp 5.000.000",

        "Executive Partner": "Rp 10.000.000",
    };

    return (
        <div className="min-h-screen bg-slate-50">
            <ProgramNavbar currentStep={4} />

            <main className="mx-auto max-w-7xl p-6">
                <div className="mx-auto max-w-5xl space-y-8">
                    {/* Header */}

                    <div className="text-center">
                        <p className="text-sm font-bold uppercase tracking-[0.2em] text-emerald-600">
                            STEP 4
                        </p>

                        <h1 className="mt-4 text-5xl font-black">
                            {isEn
                                ? "Review Your Application"
                                : "Tinjau Pendaftaran Anda"}
                        </h1>

                        <p className="mt-4 text-lg text-slate-500">
                            {isEn
                                ? "Please review your information before proceeding to payment."
                                : "Silakan tinjau informasi Anda sebelum melanjutkan ke pembayaran."}
                        </p>
                    </div>

                    {/* Success Banner */}

                    <div className="rounded-3xl bg-emerald-50 p-6">
                        <div className="flex items-center gap-3">
                            <CheckCircle2 className="h-6 w-6 text-emerald-600" />

                            <div>
                                <div className="font-bold text-emerald-700">
                                    {isEn ? "Almost There!" : "Hampir Selesai!"}
                                </div>

                                <div className="text-sm text-emerald-600">
                                    {isEn
                                        ? "You are one step away from joining the DIGESTEX Global Textile Intelligence Ecosystem."
                                        : "Anda tinggal satu langkah lagi untuk bergabung dengan DIGESTEX Global Textile Intelligence Ecosystem."}
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Package */}

                    <section className="rounded-3xl border bg-white p-8 shadow-sm">
                        <div className="flex items-center gap-3">
                            <CreditCard className="h-6 w-6 text-indigo-600" />

                            <h2 className="text-2xl font-black">
                                {isEn ? "Selected Package" : "Paket Terpilih"}
                            </h2>
                        </div>

                        <div className="mt-6 rounded-2xl bg-slate-100 p-6">
                            <div className="text-3xl font-black">
                                {company.package}
                            </div>

                            <div className="mt-2 text-lg text-slate-500">
                                {prices[company.package]} / year
                            </div>
                        </div>
                    </section>

                    {/* Company Information */}

                    <section className="rounded-3xl border bg-white p-8 shadow-sm">
                        <div className="flex items-center gap-3">
                            <Building2 className="h-6 w-6 text-sky-600" />

                            <h2 className="text-2xl font-black">
                                {isEn
                                    ? "Company Information"
                                    : "Informasi Perusahaan"}
                            </h2>
                        </div>

                        <div className="mt-8 grid gap-6 md:grid-cols-2">
                            <InfoRow
                                icon={Building2}
                                label="Company Name"
                                value={company.company_name}
                            />

                            <InfoRow
                                icon={User}
                                label="PIC Name"
                                value={company.pic_name}
                            />

                            <InfoRow
                                icon={User}
                                label="Position"
                                value={company.position}
                            />

                            <InfoRow
                                icon={Mail}
                                label="Email"
                                value={company.email}
                            />

                            <InfoRow
                                icon={Phone}
                                label="Phone"
                                value={company.phone}
                            />

                            <InfoRow
                                icon={Globe}
                                label="Website"
                                value={company.website}
                            />

                            <InfoRow
                                icon={Building2}
                                label="Company Type"
                                value={company.company_type}
                            />

                            <InfoRow
                                icon={Globe}
                                label="Country"
                                value={company.country}
                            />
                        </div>
                    </section>

                    {/* What's Next */}

                    <section className="rounded-3xl bg-slate-900 p-8 text-white">
                        <h2 className="text-2xl font-black">
                            {isEn
                                ? "What Happens Next?"
                                : "Apa Langkah Selanjutnya?"}
                        </h2>

                        <div className="mt-6 space-y-3">
                            <p>
                                1.{" "}
                                {isEn
                                    ? "Create your DIGESTEX account"
                                    : "Buat Akun DIGESTEX anda"}
                            </p>

                            <p>
                                2.{" "}
                                {isEn
                                    ? "Verify your email address"
                                    : "Verifikasi alamat email anda"}
                            </p>

                            <p>
                                3.{" "}
                                {isEn
                                    ? "Our team will review your payment"
                                    : "Tim kami akan Cek Pembayaran anda"}
                            </p>
                        </div>
                    </section>

                    {/* Actions */}

                    <div className="flex flex-wrap justify-between gap-4">
                        <Link
                            href={route(
                                "program.digital-directory.company-information",
                            )}
                            className="
                        inline-flex
                        items-center
                        gap-2
                        rounded-2xl
                        border
                        px-6
                        py-4
                        font-bold
                    "
                        >
                            <ArrowLeft className="h-5 w-5" />

                            {isEn ? "BACK" : "KEMBALI"}
                        </Link>

                        <Link
                            href={route("program.digital-directory.payment")}
                            className="
                        inline-flex
                        items-center
                        gap-2
                        rounded-2xl
                        bg-emerald-500
                        px-8
                        py-4
                        font-bold
                        text-white
                        transition
                        hover:bg-emerald-600
                    "
                        >
                            {isEn
                                ? "PROCEED TO PAYMENT"
                                : "LANJUTKAN KE PEMBAYARAN"}

                            <ArrowRight className="h-5 w-5" />
                        </Link>
                    </div>
                </div>
            </main>
        </div>
    );
}

function InfoRow({ icon: Icon, label, value }) {
    return (
        <div className="rounded-2xl bg-slate-50 p-5">
            <div className="flex items-center gap-2">
                <Icon className="h-4 w-4 text-slate-500" />

                <span className="text-sm font-semibold text-slate-500">
                    {label}
                </span>
            </div>

            <div className="mt-2 text-lg font-semibold">{value || "-"}</div>
        </div>
    );
}
