import { useState } from "react";
import { router, Link, usePage } from "@inertiajs/react";
import ProgramNavbar from "@/Components/Program/ProgramNavbar";

import {
    CreditCard,
    QrCode,
    Landmark,
    Wallet,
    ArrowLeft,
    CheckCircle2,
    ArrowRight,
} from "lucide-react";

export default function Step5Payment({ company }) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const [method, setMethod] = useState("QRIS");
    const [receipt, setReceipt] = useState(null);

    const prices = {
        "Verified Company": "Rp 2.500.000",

        "Visibility Partner": "Rp 5.000.000",

        "Executive Partner": "Rp 10.000.000",
    };

    const methods = [
        {
            name: "QRIS",

            icon: QrCode,
        },

        {
            name: "Virtual Account",

            icon: Landmark,
        },

        {
            name: "Bank Transfer",

            icon: Landmark,
        },

        {
            name: "Credit Card",

            icon: CreditCard,

            disabled: true,
        },

        {
            name: "PayPal",

            icon: Wallet,

            disabled: true,
        },

        {
            name: "Wise",

            icon: Wallet,

            disabled: true,
        },
    ];
    // Tambahan
    const confirmPayment = () => {
        if (!receipt) {
            alert(
                isEn
                    ? "Please upload payment receipt first."
                    : "Silakan unggah bukti pembayaran terlebih dahulu.",
            );

            return;
        }

        const formData = new FormData();

        formData.append("receipt", receipt);

        router.post(
            route("program.digital-directory.payment.confirm"),
            formData,
        );
    };
    // Batas Tambahan
    return (
        <div className="min-h-screen bg-slate-50">
            <ProgramNavbar currentStep={5} />

            <main className="mx-auto max-w-7xl p-6">
                <div className="mx-auto max-w-5xl space-y-8">
                    {/* Header */}

                    <div className="text-center">
                        <p className="text-sm font-bold uppercase tracking-[0.2em] text-emerald-600">
                            STEP 5
                        </p>

                        <h1 className="mt-4 text-5xl font-black">
                            {isEn ? "Payment" : "Pembayaran"}
                        </h1>

                        <p className="mt-4 text-lg text-slate-500">
                            {isEn
                                ? "Complete your payment to activate your participation."
                                : "Selesaikan pembayaran untuk mengaktifkan partisipasi Anda."}
                        </p>
                    </div>

                    {/* Summary */}

                    <div className="rounded-3xl bg-emerald-50 p-8">
                        <div className="flex items-center gap-3">
                            <CheckCircle2 className="h-6 w-6 text-emerald-600" />

                            <div>
                                <div className="font-bold text-emerald-700">
                                    {company.package}
                                </div>

                                <div className="text-3xl font-black">
                                    {prices[company.package]}
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Payment Methods */}

                    <section className="rounded-3xl border bg-white p-8 shadow-sm">
                        <h2 className="text-2xl font-black">
                            {isEn
                                ? "Select Payment Method"
                                : "Pilih Metode Pembayaran"}
                        </h2>

                        <div className="mt-8 space-y-4">
                            {methods.map((payment) => {
                                const Icon = payment.icon;

                                return (
                                    <button
                                        key={payment.name}
                                        type="button"
                                        disabled={payment.disabled}
                                        onClick={() => setMethod(payment.name)}
                                        className={`
                                        flex
                                        w-full
                                        items-center
                                        justify-between
                                        rounded-2xl
                                        border
                                        p-5
                                        text-left

                                        ${
                                            method === payment.name
                                                ? "border-emerald-500 bg-emerald-50"
                                                : ""
                                        }

                                        ${
                                            payment.disabled
                                                ? "cursor-not-allowed opacity-50"
                                                : ""
                                        }
                                    `}
                                    >
                                        <div className="flex items-center gap-4">
                                            <Icon className="h-6 w-6" />

                                            <div>
                                                <div className="font-bold">
                                                    {payment.name}
                                                </div>

                                                {payment.disabled && (
                                                    <div className="text-sm text-slate-500">
                                                        Coming Soon
                                                    </div>
                                                )}
                                            </div>
                                        </div>

                                        {method === payment.name && (
                                            <CheckCircle2 className="h-5 w-5 text-emerald-500" />
                                        )}
                                    </button>
                                );
                            })}
                        </div>
                    </section>

                    {/* Payment Information */}

                    <section className="rounded-3xl border bg-slate-100 p-8">
                        <h2 className="text-2xl font-black">
                            {isEn
                                ? "Payment Information"
                                : "Informasi Pembayaran"}
                        </h2>

                        <div className="mt-6 space-y-3">
                            <p>
                                <strong>Account Name:</strong> PT DIGESTEX MEDIA
                                UTAMA
                            </p>

                            <p>
                                <strong>Bank:</strong> Mandiri
                            </p>

                            <p>
                                <strong>Account Number:</strong>{" "}
                                070-00-1354858-6
                            </p>

                            <p>
                                <strong>Amount:</strong>{" "}
                                {prices[company?.package]}
                            </p>

                            <p>
                                <strong>Reference:</strong> DDVP2026-
                                {company?.company_name
                                    ?.replaceAll(" ", "-")
                                    ?.toUpperCase()}
                            </p>
                        </div>
                    </section>

                    {/* Upload Bukti Transfer */}
                    <section
                        className="
        rounded-3xl
        border
        bg-white
        p-8
        shadow-sm
    "
                    >
                        <h2 className="text-2xl font-black">
                            {isEn
                                ? "Upload Payment Receipt"
                                : "Unggah Bukti Pembayaran"}
                        </h2>

                        <p className="mt-3 text-slate-500">
                            {isEn
                                ? "Accepted formats: JPG, PNG, PDF (Max 5 MB)."
                                : "Format yang diterima: JPG, PNG, PDF (Maks. 5 MB)."}
                        </p>

                        <input
                            type="file"
                            accept=".jpg,.jpeg,.png,.pdf"
                            onChange={(e) =>
                                setReceipt(e.target.files?.[0] ?? null)
                            }
                            className="
            mt-6
            block
            w-full
            rounded-2xl
            border
            p-4
        "
                        />

                        {receipt && (
                            <div
                                className="
                mt-6
                rounded-2xl
                bg-emerald-50
                p-4
            "
                            >
                                <p className="font-semibold text-emerald-700">
                                    {isEn
                                        ? "Receipt Uploaded"
                                        : "Bukti Pembayaran Berhasil Diunggah"}
                                </p>

                                <p className="mt-2 text-sm text-emerald-700">
                                    {receipt.name}
                                </p>
                            </div>
                        )}
                    </section>

                    {/* Notice */}

                    <section className="rounded-3xl bg-slate-900 p-8 text-white">
                        <h2 className="text-xl font-black">
                            {isEn ? "After Payment" : "Setelah Pembayaran"}
                        </h2>

                        <div className="mt-4 space-y-2 text-slate-300">
                            <p>
                                1.{" "}
                                {isEn
                                    ? "Upload payment proof."
                                    : "Unggah bukti pembayaran."}
                            </p>

                            <p>
                                2.{" "}
                                {isEn
                                    ? "Our team will verify your payment."
                                    : "Tim kami akan memverifikasi pembayaran Anda."}
                            </p>

                            <p>
                                3.{" "}
                                {isEn
                                    ? "Receive activation email."
                                    : "Terima email aktivasi."}
                            </p>

                            <p>
                                4.{" "}
                                {isEn
                                    ? "Start building your Company Passport™."
                                    : "Mulai lengkapi Company Passport™ Anda."}
                            </p>
                        </div>
                    </section>

                    {/* Footer */}

                    <div className="flex flex-wrap justify-between gap-4">
                        <Link
                            href={route("program.digital-directory.review")}
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

                        <button
                            type="button"
                            onClick={confirmPayment}
                            disabled={!receipt}
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
        disabled:cursor-not-allowed
        disabled:opacity-50
    "
                        >
                            {isEn ? "CONFIRM PAYMENT" : "KONFIRMASI PEMBAYARAN"}

                            <ArrowRight className="h-5 w-5" />
                        </button>
                    </div>
                </div>
            </main>
        </div>
    );
}
