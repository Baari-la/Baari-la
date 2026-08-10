import ProgramNavbar from "@/Components/Program/ProgramNavbar";
import StickyWhatsAppButton from "@/Components/Program/StickyWhatsAppButton";

import { Link, usePage } from "@inertiajs/react";

import {
    ArrowLeft,
    ArrowRight,
    CheckCircle2,
    Globe2,
    Handshake,
    Mail,
    MessageSquare,
    Sparkles,
} from "lucide-react";

export default function ThankYou() {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    return (
        <div className="min-h-screen bg-slate-50">
            <ProgramNavbar />

            <main className="relative overflow-hidden">
                {/* =====================================================
                    BACKGROUND
                ===================================================== */}

                <div className="absolute inset-0 pointer-events-none">
                    <div className="absolute left-1/2 top-0 h-[500px] w-[500px] -translate-x-1/2 rounded-full bg-emerald-500/10 blur-3xl" />

                    <div className="absolute right-0 top-40 h-[400px] w-[400px] rounded-full bg-indigo-500/10 blur-3xl" />
                </div>

                {/* =====================================================
                    CONTENT
                ===================================================== */}

                <section className="relative mx-auto flex min-h-[calc(100vh-80px)] max-w-5xl items-center justify-center px-6 py-16">
                    <div className="w-full max-w-3xl text-center">
                        {/* Success Icon */}

                        <div className="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-emerald-100">
                            <CheckCircle2 className="h-10 w-10 text-emerald-600" />
                        </div>

                        {/* Label */}

                        <div className="mt-8 inline-flex items-center gap-2 rounded-full border border-amber-300 bg-amber-50 px-4 py-2 text-xs font-black uppercase tracking-[0.18em] text-amber-700">
                            <Sparkles className="h-4 w-4" />
                            Strategic Solution Partner
                        </div>

                        {/* Heading */}

                        <h1 className="mt-6 text-4xl font-black leading-tight text-slate-950 sm:text-5xl">
                            {isEn
                                ? "Thank You for Your Inquiry"
                                : "Terima Kasih atas Inquiry Anda"}
                        </h1>

                        {/* Description */}

                        <p className="mx-auto mt-6 max-w-2xl text-base leading-7 text-slate-500 sm:text-lg">
                            {isEn
                                ? "Your Strategic Partnership Inquiry has been successfully submitted to the DIGESTEX team."
                                : "Strategic Partnership Inquiry Anda telah berhasil dikirimkan kepada tim DIGESTEX."}
                        </p>

                        <p className="mx-auto mt-3 max-w-2xl text-sm leading-6 text-slate-400">
                            {isEn
                                ? "Our team will review your company profile, solution and partnership objectives. We will contact you to discuss the next steps."
                                : "Tim kami akan meninjau profil perusahaan, solusi, dan tujuan kemitraan Anda. Kami akan menghubungi Anda untuk mendiskusikan langkah selanjutnya."}
                        </p>

                        {/* =================================================
                            STATUS CARD
                        ================================================= */}

                        <div className="mx-auto mt-10 grid max-w-3xl gap-4 sm:grid-cols-3">
                            <StatusCard
                                icon={CheckCircle2}
                                title={
                                    isEn
                                        ? "Inquiry Received"
                                        : "Inquiry Diterima"
                                }
                                text={
                                    isEn
                                        ? "Successfully submitted"
                                        : "Berhasil dikirim"
                                }
                            />

                            <StatusCard
                                icon={Globe2}
                                title={isEn ? "Under Review" : "Dalam Review"}
                                text={
                                    isEn
                                        ? "DIGESTEX team review"
                                        : "Review tim DIGESTEX"
                                }
                            />

                            <StatusCard
                                icon={Handshake}
                                title={
                                    isEn
                                        ? "Next Discussion"
                                        : "Diskusi Berikutnya"
                                }
                                text={
                                    isEn
                                        ? "Partnership exploration"
                                        : "Eksplorasi kemitraan"
                                }
                            />
                        </div>

                        {/* =================================================
                            NEXT STEP
                        ================================================= */}

                        <div className="mx-auto mt-8 max-w-3xl rounded-3xl bg-slate-950 p-7 text-left text-white shadow-xl sm:p-8">
                            <div className="flex items-start gap-4">
                                <div className="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-amber-400/10">
                                    <MessageSquare className="h-5 w-5 text-amber-300" />
                                </div>

                                <div>
                                    <h2 className="text-lg font-black">
                                        {isEn
                                            ? "What Happens Next?"
                                            : "Apa yang Terjadi Selanjutnya?"}
                                    </h2>

                                    <p className="mt-2 text-sm leading-6 text-slate-400">
                                        {isEn
                                            ? "The DIGESTEX Strategic Partnership Team will review your submission and may contact your company for additional information or to arrange a strategic discussion."
                                            : "Tim Strategic Partnership DIGESTEX akan meninjau submission Anda dan dapat menghubungi perusahaan Anda untuk mendapatkan informasi tambahan atau menjadwalkan diskusi strategis."}
                                    </p>
                                </div>
                            </div>

                            <div className="mt-6 border-t border-white/10 pt-5">
                                <p className="text-xs leading-5 text-slate-500">
                                    {isEn
                                        ? "For urgent discussions, you may also contact the DIGESTEX team directly."
                                        : "Untuk diskusi yang membutuhkan respons segera, Anda juga dapat menghubungi tim DIGESTEX secara langsung."}
                                </p>
                            </div>
                        </div>

                        {/* =================================================
                            ACTIONS
                        ================================================= */}

                        <div className="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                            <Link
                                href={route("program.digital-directory")}
                                className="
                                    inline-flex
                                    items-center
                                    justify-center
                                    gap-2
                                    rounded-2xl
                                    border
                                    border-slate-200
                                    bg-white
                                    px-6
                                    py-4
                                    text-sm
                                    font-black
                                    text-slate-700
                                    shadow-sm
                                    transition
                                    hover:bg-slate-100
                                "
                            >
                                <ArrowLeft className="h-5 w-5" />

                                {isEn
                                    ? "BACK TO PROGRAM"
                                    : "KEMBALI KE PROGRAM"}
                            </Link>

                            <Link
                                href={route("strategic-partnership.create")}
                                className="
                                    inline-flex
                                    items-center
                                    justify-center
                                    gap-2
                                    rounded-2xl
                                    bg-slate-900
                                    px-6
                                    py-4
                                    text-sm
                                    font-black
                                    text-white
                                    transition
                                    hover:bg-slate-800
                                "
                            >
                                {isEn
                                    ? "SUBMIT ANOTHER INQUIRY"
                                    : "KIRIM INQUIRY LAIN"}

                                <ArrowRight className="h-5 w-5" />
                            </Link>
                        </div>

                        {/* =================================================
                            BRAND
                        ================================================= */}

                        <div className="mt-12">
                            <p className="text-xs font-black uppercase tracking-[0.25em] text-slate-400">
                                DIGESTEX GLOBAL
                            </p>

                            <p className="mt-2 text-sm font-semibold text-slate-500">
                                Where Textile Meets Intelligence
                            </p>
                        </div>
                    </div>
                </section>
            </main>

            <StickyWhatsAppButton
                position="left"
                message={
                    isEn
                        ? "Hello DIGESTEX, we have submitted a Strategic Partnership Inquiry and would like to discuss further."
                        : "Halo DIGESTEX, kami sudah mengirim Strategic Partnership Inquiry dan ingin mendiskusikannya lebih lanjut."
                }
            />
        </div>
    );
}

/* ==========================================================
   STATUS CARD
========================================================== */

function StatusCard({ icon: Icon, title, text }) {
    return (
        <div className="rounded-3xl border border-slate-200 bg-white p-5 text-left shadow-sm">
            <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50">
                <Icon className="h-5 w-5 text-emerald-600" />
            </div>

            <h3 className="mt-4 text-sm font-black text-slate-900">{title}</h3>

            <p className="mt-1 text-xs leading-5 text-slate-500">{text}</p>
        </div>
    );
}
