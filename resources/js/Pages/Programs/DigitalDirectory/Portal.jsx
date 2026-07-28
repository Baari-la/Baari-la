import ProgramPortalNavbar from "@/Components/Program/ProgramPortalNavbar";

import { Head, Link, usePage } from "@inertiajs/react";

import {
    ArrowRight,
    BadgeCheck,
    Building2,
    Check,
    CheckCircle2,
    Circle,
    Clock3,
    FileCheck2,
    LayoutDashboard,
    LockKeyhole,
    MailCheck,
    ShieldCheck,
    Sparkles,
    UserRoundCheck,
    WalletCards,
} from "lucide-react";

export default function Portal({
    participant = null,
    claim = null,
    company = null,
    programStatus = {},
    nextAction = null,
    services = [],
}) {
    const { auth, locale } = usePage().props;

    const user = auth?.user;

    const isEn = locale === "en";

    /*
    |--------------------------------------------------------------------------
    | Next Action Translations
    |--------------------------------------------------------------------------
    */

    const nextActionContent = {
        program_not_linked: {
            en: {
                title: "Digital Directory Program",
                description:
                    "No Digital Directory & Visibility Program participation is linked to this account.",
                button: "VIEW PROGRAM",
            },

            id: {
                title: "Program Digital Directory",
                description:
                    "Belum ada partisipasi Digital Directory & Visibility Program yang terhubung dengan akun ini.",
                button: "LIHAT PROGRAM",
            },
        },

        email_verification: {
            en: {
                title: "Verify Your Email",
                description:
                    "Verify your email address to continue your DIGESTEX Digital Directory & Visibility Program setup.",
                button: null,
            },

            id: {
                title: "Verifikasi Email Anda",
                description:
                    "Verifikasi alamat email Anda untuk melanjutkan proses Digital Directory & Visibility Program DIGESTEX.",
                button: null,
            },
        },

        ownership_rejected: {
            en: {
                title: "Ownership Verification Requires Attention",
                description:
                    "Your company ownership verification could not be approved. Please review the verification result and submit the required company information or documents.",
                button: "REVIEW VERIFICATION",
            },

            id: {
                title: "Verifikasi Kepemilikan Memerlukan Tindakan",
                description:
                    "Verifikasi kepemilikan perusahaan Anda belum dapat disetujui. Silakan tinjau hasil verifikasi dan lengkapi informasi atau dokumen perusahaan yang diperlukan.",
                button: "TINJAU VERIFIKASI",
            },
        },

        verification_in_progress: {
            en: {
                title: "Verification in Progress",
                description:
                    "Your payment and company ownership information have been submitted successfully and are currently being reviewed by DIGESTEX. No action is required from you at this time.",
                button: null,
            },

            id: {
                title: "Verifikasi Sedang Berlangsung",
                description:
                    "Informasi pembayaran dan kepemilikan perusahaan Anda telah berhasil diajukan dan sedang ditinjau oleh DIGESTEX. Saat ini tidak ada tindakan tambahan yang perlu Anda lakukan.",
                button: null,
            },
        },

        ownership_pending: {
            en: {
                title: "Ownership Verification Pending",
                description:
                    "Your company ownership information and verification documents are currently being reviewed by the DIGESTEX Verification Team. No action is required from you at this time.",
                button: null,
            },

            id: {
                title: "Menunggu Verifikasi Kepemilikan",
                description:
                    "Informasi kepemilikan dan dokumen verifikasi perusahaan Anda sedang ditinjau oleh Tim Verifikasi DIGESTEX. Saat ini tidak ada tindakan tambahan yang perlu Anda lakukan.",
                button: null,
            },
        },

        payment_pending_verification: {
            en: {
                title: "Payment Verification Pending",
                description:
                    "Your payment has been submitted and is currently awaiting verification by the DIGESTEX team. No additional payment is required at this time.",
                button: null,
            },

            id: {
                title: "Menunggu Verifikasi Pembayaran",
                description:
                    "Pembayaran Anda telah diajukan dan sedang menunggu verifikasi oleh tim DIGESTEX. Saat ini tidak diperlukan pembayaran tambahan.",
                button: null,
            },
        },

        payment: {
            en: {
                title: "Complete Payment",
                description:
                    "Complete your program payment to continue your DIGESTEX Digital Directory & Visibility Program setup.",
                button: "VIEW PAYMENT",
            },

            id: {
                title: "Selesaikan Pembayaran",
                description:
                    "Selesaikan pembayaran program untuk melanjutkan proses Digital Directory & Visibility Program DIGESTEX.",
                button: "LIHAT PEMBAYARAN",
            },
        },

        ownership_verification: {
            en: {
                title: "Verify Your Company",
                description:
                    "Find your company in the DIGESTEX Directory and submit ownership verification before company management access can be activated.",
                button: "FIND MY COMPANY",
            },

            id: {
                title: "Verifikasi Perusahaan Anda",
                description:
                    "Temukan perusahaan Anda di Direktori DIGESTEX dan ajukan verifikasi kepemilikan sebelum akses pengelolaan perusahaan dapat diaktifkan.",
                button: "CARI PERUSAHAAN SAYA",
            },
        },

        company_connection: {
            en: {
                title: "Company Connection in Progress",
                description:
                    "Your company ownership has been verified. DIGESTEX is completing the connection between your account and the verified company profile.",
                button: null,
            },

            id: {
                title: "Proses Menghubungkan Perusahaan",
                description:
                    "Kepemilikan perusahaan Anda telah diverifikasi. DIGESTEX sedang menyelesaikan proses untuk menghubungkan akun Anda dengan profil perusahaan yang telah diverifikasi.",
                button: null,
            },
        },

        company_profile: {
            en: {
                title: "Complete Your Company Profile",
                description:
                    "Your company is verified and connected to your account. Complete the company profile to activate your Digital Directory presence and program services.",
                button: "COMPLETE COMPANY PROFILE",
            },

            id: {
                title: "Lengkapi Profil Perusahaan",
                description:
                    "Perusahaan Anda telah diverifikasi dan terhubung dengan akun Anda. Lengkapi profil perusahaan untuk mengaktifkan kehadiran di Digital Directory dan layanan program.",
                button: "LENGKAPI PROFIL PERUSAHAAN",
            },
        },

        program_ready: {
            en: {
                title: "Your Program Is Ready for Activation",
                description:
                    "Your company setup has been completed successfully. Your DIGESTEX Digital Directory & Visibility Program is now awaiting activation.",
                button: null,
            },

            id: {
                title: "Program Anda Siap Diaktifkan",
                description:
                    "Pengaturan perusahaan Anda telah berhasil diselesaikan. Digital Directory & Visibility Program DIGESTEX Anda sekarang menunggu aktivasi.",
                button: null,
            },
        },

        program_active: {
            en: {
                title: "Your DIGESTEX Program Is Active",
                description:
                    "Your company is verified, connected, and active in the DIGESTEX Digital Directory & Visibility Program. You can now access the services included in your program package.",
                button: null,
            },

            id: {
                title: "Program DIGESTEX Anda Aktif",
                description:
                    "Perusahaan Anda telah terverifikasi, terhubung, dan aktif dalam DIGESTEX Digital Directory & Visibility Program. Anda sekarang dapat mengakses layanan yang termasuk dalam paket program Anda.",
                button: null,
            },
        },
        program_inactive: {
            en: {
                title: "Your DIGESTEX Program Is Inactive",
                description:
                    "Your company setup remains complete, but access to DIGESTEX program services is currently inactive. Please contact DIGESTEX Support if you need assistance.",
                button: null,
            },

            id: {
                title: "Program DIGESTEX Anda Tidak Aktif",
                description:
                    "Pengaturan perusahaan Anda tetap lengkap, tetapi akses ke layanan program DIGESTEX saat ini tidak aktif. Silakan hubungi DIGESTEX Support jika Anda memerlukan bantuan.",
                button: null,
            },
        },
    };

    /*
    |--------------------------------------------------------------------------
    | Current Next Action
    |--------------------------------------------------------------------------
    */

    const actionContent =
        nextActionContent[nextAction?.key]?.[isEn ? "en" : "id"] ?? null;

    /*
    |--------------------------------------------------------------------------
    | Program Information
    |--------------------------------------------------------------------------
    */

    const packageName =
        participant?.package ??
        (isEn ? "Digital Directory Program" : "Program Digital Directory");

    const companyName =
        company?.nama_perusahaan ??
        claim?.claimed_company_name ??
        participant?.company_name ??
        null;

    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */

    const paymentCompleted = Boolean(programStatus?.payment_completed);
    const paymentPendingVerification = Boolean(
        programStatus?.payment_pending_verification,
    );

    const paymentStatus = programStatus?.payment_status ?? "not_started";
    const emailVerified = Boolean(programStatus?.email_verified);

    const ownershipStatus = programStatus?.ownership_status ?? "not_started";

    const ownershipVerified = Boolean(programStatus?.ownership_verified);

    const companyConnected = Boolean(programStatus?.company_connected);

    const onboardingCompleted = Boolean(programStatus?.onboarding_completed);

    const onboardingStep = Number(programStatus?.onboarding_step ?? 0);

    /*
    |--------------------------------------------------------------------------
    | Program Progress
    |--------------------------------------------------------------------------
    */

    const progressItems = [
        {
            key: "payment",

            label: isEn ? "Program Payment" : "Pembayaran Program",

            description: paymentPendingVerification
                ? isEn
                    ? "Payment submitted and awaiting verification"
                    : "Pembayaran telah dikirim dan menunggu verifikasi"
                : paymentCompleted
                  ? isEn
                      ? "Program payment verified"
                      : "Pembayaran program telah diverifikasi"
                  : isEn
                    ? "Program participation payment"
                    : "Pembayaran partisipasi program",

            completed: paymentCompleted,

            pending: paymentPendingVerification,

            active: !paymentCompleted && !paymentPendingVerification,

            icon: WalletCards,
        },

        {
            key: "account",

            label: isEn ? "DIGESTEX Account" : "Akun DIGESTEX",

            description: isEn
                ? "Your program account has been created"
                : "Akun program Anda telah dibuat",

            completed: Boolean(user),

            active: false,

            icon: UserRoundCheck,
        },

        {
            key: "email",

            label: isEn ? "Email Verification" : "Verifikasi Email",

            description: isEn
                ? "Verified account email"
                : "Email akun telah diverifikasi",

            completed: emailVerified,

            active: Boolean(user) && !emailVerified,

            icon: MailCheck,
        },

        {
            key: "ownership",

            label: isEn ? "Ownership Verification" : "Verifikasi Kepemilikan",

            description: ownershipDescription(ownershipStatus, isEn),

            completed: ownershipVerified,

            active: emailVerified && !ownershipVerified,

            pending: ownershipStatus === "pending",

            rejected: ownershipStatus === "rejected",

            icon: ShieldCheck,
        },

        {
            key: "company",

            label: isEn ? "Company Connection" : "Koneksi Perusahaan",

            description: companyConnected
                ? isEn
                    ? "Your account is connected to the company"
                    : "Akun Anda telah terhubung dengan perusahaan"
                : isEn
                  ? "Available after ownership approval"
                  : "Tersedia setelah verifikasi kepemilikan disetujui",

            completed: companyConnected,

            active: ownershipVerified && !companyConnected,

            icon: Building2,
        },

        {
            key: "profile",

            label: isEn
                ? "Company Profile Setup"
                : "Pengaturan Profil Perusahaan",

            description: onboardingCompleted
                ? isEn
                    ? "Company profile setup completed"
                    : "Pengaturan profil perusahaan selesai"
                : companyConnected
                  ? isEn
                      ? `Company setup progress: step ${onboardingStep}`
                      : `Proses pengaturan perusahaan: tahap ${onboardingStep}`
                  : isEn
                    ? "Available after company connection"
                    : "Tersedia setelah perusahaan terhubung",

            completed: onboardingCompleted,

            active: companyConnected && !onboardingCompleted,

            icon: FileCheck2,
        },
    ];

    const completedCount = progressItems.filter(
        (item) => item.completed,
    ).length;

    const progressPercentage = Math.round(
        (completedCount / progressItems.length) * 100,
    );

    return (
        <div className="min-h-screen bg-slate-50">
            <Head
                title={
                    isEn
                        ? "Digital Directory Program"
                        : "Program Digital Directory"
                }
            />

            <ProgramPortalNavbar
                company={company}
                participant={participant}
                programStatus={programStatus}
            />

            <main>
                {/* Hero */}

                <section
                    className="
                        border-b
                        border-slate-200
                        bg-white
                    "
                >
                    <div
                        className="
                            mx-auto
                            max-w-[1600px]
                            px-6
                            py-12
                            lg:py-16
                        "
                    >
                        <div
                            className="
                                grid
                                gap-10
                                xl:grid-cols-[1fr_420px]
                                xl:items-center
                            "
                        >
                            {/* Welcome */}

                            <div>
                                <div
                                    className="
                                        inline-flex
                                        items-center
                                        gap-2
                                        rounded-full
                                        bg-emerald-50
                                        px-4
                                        py-2
                                        text-xs
                                        font-black
                                        uppercase
                                        tracking-[0.16em]
                                        text-emerald-700
                                    "
                                >
                                    <Sparkles className="h-4 w-4" />
                                    DIGESTEX Digital Directory & Visibility
                                    Program
                                </div>

                                <h1
                                    className="
                                        mt-6
                                        max-w-4xl
                                        text-4xl
                                        font-black
                                        tracking-tight
                                        text-slate-900
                                        md:text-5xl
                                    "
                                >
                                    {isEn ? "Welcome" : "Selamat Datang"}
                                    {user?.name ? `, ${user.name}` : ""}
                                </h1>

                                <p
                                    className="
                                        mt-5
                                        max-w-3xl
                                        text-lg
                                        leading-8
                                        text-slate-500
                                    "
                                >
                                    {isEn
                                        ? "Manage your program participation, company verification, profile activation, and DIGESTEX visibility services from one place."
                                        : "Kelola partisipasi program, verifikasi perusahaan, aktivasi profil, dan layanan visibilitas DIGESTEX dari satu tempat."}
                                </p>

                                {/* Company */}

                                {companyName && (
                                    <div
                                        className="
                                            mt-8
                                            flex
                                            max-w-3xl
                                            items-start
                                            gap-4
                                            rounded-3xl
                                            border
                                            border-slate-200
                                            bg-slate-50
                                            p-5
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
                                                bg-white
                                                shadow-sm
                                            "
                                        >
                                            <Building2 className="h-6 w-6 text-emerald-600" />
                                        </div>

                                        <div>
                                            <div
                                                className="
                                                    text-xs
                                                    font-black
                                                    uppercase
                                                    tracking-widest
                                                    text-slate-400
                                                "
                                            >
                                                {companyConnected
                                                    ? isEn
                                                        ? "Your Company"
                                                        : "Perusahaan Anda"
                                                    : isEn
                                                      ? "Company Being Verified"
                                                      : "Perusahaan yang Diverifikasi"}
                                            </div>

                                            <div
                                                className="
                                                    mt-1
                                                    text-xl
                                                    font-black
                                                    text-slate-900
                                                "
                                            >
                                                {companyName}
                                            </div>
                                        </div>
                                    </div>
                                )}
                            </div>

                            {/* Package Card */}

                            <div
                                className="
                                    rounded-[32px]
                                    bg-slate-900
                                    p-8
                                    text-white
                                    shadow-xl
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
                                    {isEn ? "Your Program" : "Program Anda"}
                                </div>

                                <div
                                    className="
                                        mt-3
                                        text-3xl
                                        font-black
                                    "
                                >
                                    {packageName}
                                </div>

                                {participant?.invoice_number && (
                                    <div
                                        className="
                                            mt-3
                                            text-sm
                                            text-slate-400
                                        "
                                    >
                                        {isEn ? "Invoice" : "Invoice"}:{" "}
                                        {participant.invoice_number}
                                    </div>
                                )}

                                <div
                                    className="
                                        mt-8
                                        border-t
                                        border-white/10
                                        pt-6
                                    "
                                >
                                    <div
                                        className="
                                            flex
                                            items-center
                                            justify-between
                                            gap-4
                                        "
                                    >
                                        <span className="text-sm text-slate-400">
                                            {isEn
                                                ? "Program Setup"
                                                : "Pengaturan Program"}
                                        </span>

                                        <span className="text-lg font-black">
                                            {progressPercentage}%
                                        </span>
                                    </div>

                                    <div
                                        className="
                                            mt-3
                                            h-2
                                            overflow-hidden
                                            rounded-full
                                            bg-white/10
                                        "
                                    >
                                        <div
                                            className="
                                                h-full
                                                rounded-full
                                                bg-emerald-400
                                                transition-all
                                                duration-500
                                            "
                                            style={{
                                                width: `${progressPercentage}%`,
                                            }}
                                        />
                                    </div>

                                    <p
                                        className="
                                            mt-4
                                            text-sm
                                            leading-6
                                            text-slate-400
                                        "
                                    >
                                        {completedCount}{" "}
                                        {isEn
                                            ? `of ${progressItems.length} setup stages completed`
                                            : `dari ${progressItems.length} tahap telah selesai`}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {/* Portal Content */}

                <section
                    className="
                        mx-auto
                        max-w-[1600px]
                        px-6
                        py-10
                        lg:py-12
                    "
                >
                    <div
                        className="
                            grid
                            gap-8
                            xl:grid-cols-[1fr_400px]
                        "
                    >
                        {/* Left */}

                        <div className="space-y-8">
                            {/* Next Action */}

                            {nextAction && (
                                <section
                                    className="
                                        overflow-hidden
                                        rounded-[32px]
                                        border
                                        border-emerald-200
                                        bg-white
                                        shadow-sm
                                    "
                                >
                                    <div
                                        className="
                                            border-b
                                            border-emerald-100
                                            bg-emerald-50
                                            px-8
                                            py-5
                                        "
                                    >
                                        <div
                                            className="
                                                flex
                                                items-center
                                                gap-3
                                            "
                                        >
                                            <div
                                                className="
                                                    flex
                                                    h-10
                                                    w-10
                                                    items-center
                                                    justify-center
                                                    rounded-xl
                                                    bg-emerald-600
                                                    text-white
                                                "
                                            >
                                                <ArrowRight className="h-5 w-5" />
                                            </div>

                                            <div>
                                                <div
                                                    className="
                                                        text-xs
                                                        font-black
                                                        uppercase
                                                        tracking-widest
                                                        text-emerald-700
                                                    "
                                                >
                                                    {isEn
                                                        ? "Next Action"
                                                        : "Langkah Berikutnya"}
                                                </div>

                                                <div className="mt-1 text-sm text-emerald-800">
                                                    {nextAction?.key ===
                                                    "program_active"
                                                        ? isEn
                                                            ? "Explore your DIGESTEX program services"
                                                            : "Jelajahi layanan program DIGESTEX Anda"
                                                        : nextAction?.key ===
                                                            "program_inactive"
                                                          ? isEn
                                                              ? "Your DIGESTEX program services are currently inactive"
                                                              : "Layanan program DIGESTEX Anda saat ini tidak aktif"
                                                          : nextAction?.key ===
                                                              "program_ready"
                                                            ? isEn
                                                                ? "Awaiting DIGESTEX program activation"
                                                                : "Menunggu aktivasi program DIGESTEX"
                                                            : isEn
                                                              ? "Continue your DIGESTEX program setup"
                                                              : "Lanjutkan proses program DIGESTEX Anda"}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div className="p-8">
                                        <h2
                                            className="
                                                text-3xl
                                                font-black
                                                text-slate-900
                                            "
                                        >
                                            {actionContent?.title}
                                        </h2>

                                        <p
                                            className="
                                                mt-4
                                                max-w-3xl
                                                text-base
                                                leading-7
                                                text-slate-500
                                            "
                                        >
                                            {actionContent?.description}
                                        </p>

                                        {nextAction?.route &&
                                            actionContent?.button && (
                                                <Link
                                                    href={route(
                                                        nextAction.route,
                                                    )}
                                                    className="
                                                    mt-7
                                                    inline-flex
                                                    items-center
                                                    gap-2
                                                    rounded-2xl
                                                    bg-emerald-600
                                                    px-7
                                                    py-4
                                                    text-sm
                                                    font-black
                                                    uppercase
                                                    text-white
                                                    transition
                                                    hover:bg-emerald-700
                                                "
                                                >
                                                    {actionContent?.button}

                                                    <ArrowRight className="h-5 w-5" />
                                                </Link>
                                            )}
                                    </div>
                                </section>
                            )}

                            {/* Program Journey */}

                            <section
                                className="
                                    rounded-[32px]
                                    border
                                    border-slate-200
                                    bg-white
                                    p-8
                                    shadow-sm
                                "
                            >
                                <div>
                                    <div
                                        className="
                                            text-xs
                                            font-black
                                            uppercase
                                            tracking-[0.16em]
                                            text-emerald-600
                                        "
                                    >
                                        {isEn
                                            ? "Program Journey"
                                            : "Perjalanan Program"}
                                    </div>

                                    <h2
                                        className="
                                            mt-2
                                            text-3xl
                                            font-black
                                            text-slate-900
                                        "
                                    >
                                        {isEn
                                            ? "Your Program Status"
                                            : "Status Program Anda"}
                                    </h2>

                                    <p
                                        className="
                                            mt-3
                                            max-w-3xl
                                            leading-7
                                            text-slate-500
                                        "
                                    >
                                        {isEn
                                            ? "Track the stages required to connect, verify, and activate your company in the DIGESTEX ecosystem."
                                            : "Pantau tahapan untuk menghubungkan, memverifikasi, dan mengaktifkan perusahaan Anda dalam ekosistem DIGESTEX."}
                                    </p>
                                </div>

                                <div className="mt-8 space-y-3">
                                    {progressItems.map((item, index) => (
                                        <ProgressItem
                                            key={item.key}
                                            item={item}
                                            number={index + 1}
                                            isEn={isEn}
                                        />
                                    ))}
                                </div>
                            </section>

                            {/* Services */}

                            <section
                                className="
                                    rounded-[32px]
                                    border
                                    border-slate-200
                                    bg-white
                                    p-8
                                    shadow-sm
                                "
                            >
                                <div
                                    className="
                                        flex
                                        flex-col
                                        gap-4
                                        md:flex-row
                                        md:items-end
                                        md:justify-between
                                    "
                                >
                                    <div>
                                        <div
                                            className="
                                                text-xs
                                                font-black
                                                uppercase
                                                tracking-[0.16em]
                                                text-violet-600
                                            "
                                        >
                                            {isEn
                                                ? "Program Services"
                                                : "Layanan Program"}
                                        </div>

                                        <h2
                                            className="
                                                mt-2
                                                text-3xl
                                                font-black
                                                text-slate-900
                                            "
                                        >
                                            {isEn
                                                ? "Your DIGESTEX Services"
                                                : "Layanan DIGESTEX Anda"}
                                        </h2>
                                    </div>

                                    <div
                                        className="
                                            rounded-full
                                            bg-slate-100
                                            px-4
                                            py-2
                                            text-xs
                                            font-black
                                            uppercase
                                            text-slate-600
                                        "
                                    >
                                        {packageName}
                                    </div>
                                </div>

                                {services.length > 0 ? (
                                    <div
                                        className="
                                            mt-8
                                            grid
                                            gap-4
                                            md:grid-cols-2
                                        "
                                    >
                                        {services.map((service) => (
                                            <ServiceCard
                                                key={service.key}
                                                service={service}
                                                isEn={isEn}
                                            />
                                        ))}
                                    </div>
                                ) : (
                                    <div
                                        className="
                                            mt-8
                                            rounded-3xl
                                            bg-slate-50
                                            p-8
                                            text-center
                                        "
                                    >
                                        <LayoutDashboard
                                            className="
                                                mx-auto
                                                h-10
                                                w-10
                                                text-slate-300
                                            "
                                        />

                                        <p
                                            className="
                                                mt-4
                                                text-slate-500
                                            "
                                        >
                                            {isEn
                                                ? "Program services will appear after your participation is linked to this account."
                                                : "Layanan program akan tampil setelah partisipasi program terhubung dengan akun ini."}
                                        </p>
                                    </div>
                                )}
                            </section>
                        </div>

                        {/* Right Sidebar */}

                        <aside className="space-y-6">
                            {/* Verification Status */}

                            <section
                                className="
                                    rounded-[32px]
                                    border
                                    border-slate-200
                                    bg-white
                                    p-7
                                    shadow-sm
                                "
                            >
                                <div
                                    className="
                                        flex
                                        items-center
                                        gap-3
                                    "
                                >
                                    <div
                                        className="
                                            flex
                                            h-11
                                            w-11
                                            items-center
                                            justify-center
                                            rounded-2xl
                                            bg-slate-100
                                        "
                                    >
                                        <ShieldCheck className="h-6 w-6 text-slate-700" />
                                    </div>

                                    <div>
                                        <div
                                            className="
                                                text-xs
                                                font-black
                                                uppercase
                                                tracking-widest
                                                text-slate-400
                                            "
                                        >
                                            {isEn ? "Ownership" : "Kepemilikan"}
                                        </div>

                                        <div
                                            className="
                                                mt-1
                                                font-black
                                                text-slate-900
                                            "
                                        >
                                            {ownershipStatusLabel(
                                                ownershipStatus,
                                                isEn,
                                            )}
                                        </div>
                                    </div>
                                </div>

                                {claim && (
                                    <div
                                        className="
                                            mt-6
                                            space-y-4
                                            border-t
                                            border-slate-100
                                            pt-6
                                        "
                                    >
                                        {claim.claimed_company_name && (
                                            <InfoItem
                                                label={
                                                    isEn
                                                        ? "Company"
                                                        : "Perusahaan"
                                                }
                                                value={
                                                    claim.claimed_company_name
                                                }
                                            />
                                        )}

                                        {claim.nib && (
                                            <InfoItem
                                                label="NIB"
                                                value={claim.nib}
                                            />
                                        )}

                                        {claim.submitted_at && (
                                            <InfoItem
                                                label={
                                                    isEn
                                                        ? "Submitted"
                                                        : "Diajukan"
                                                }
                                                value={formatDate(
                                                    claim.submitted_at,
                                                    isEn,
                                                )}
                                            />
                                        )}
                                    </div>
                                )}
                            </section>

                            {/* Company Access */}

                            <section
                                className="
                                    rounded-[32px]
                                    bg-slate-900
                                    p-7
                                    text-white
                                "
                            >
                                <div
                                    className="
                                        flex
                                        h-12
                                        w-12
                                        items-center
                                        justify-center
                                        rounded-2xl
                                        bg-white/10
                                    "
                                >
                                    {companyConnected ? (
                                        <BadgeCheck className="h-6 w-6 text-emerald-300" />
                                    ) : (
                                        <LockKeyhole className="h-6 w-6 text-slate-300" />
                                    )}
                                </div>

                                <h3
                                    className="
                                        mt-5
                                        text-xl
                                        font-black
                                    "
                                >
                                    {companyConnected
                                        ? isEn
                                            ? "Company Access Active"
                                            : "Akses Perusahaan Aktif"
                                        : isEn
                                          ? "Company Access Protected"
                                          : "Akses Perusahaan Dilindungi"}
                                </h3>

                                <p
                                    className="
                                        mt-3
                                        text-sm
                                        leading-6
                                        text-slate-400
                                    "
                                >
                                    {companyConnected
                                        ? isEn
                                            ? "Your account is connected to the verified company profile."
                                            : "Akun Anda telah terhubung dengan profil perusahaan yang diverifikasi."
                                        : isEn
                                          ? "Company management access will be granted only after ownership verification is approved."
                                          : "Akses pengelolaan perusahaan hanya diberikan setelah verifikasi kepemilikan disetujui."}
                                </p>

                                {companyConnected && !onboardingCompleted && (
                                    <Link
                                        href={route(
                                            "onboarding.company-information",
                                        )}
                                        className="
                                                mt-6
                                                inline-flex
                                                items-center
                                                gap-2
                                                rounded-2xl
                                                bg-white
                                                px-5
                                                py-3
                                                text-sm
                                                font-black
                                                text-slate-900
                                                transition
                                                hover:bg-slate-100
                                            "
                                    >
                                        {isEn
                                            ? "COMPLETE PROFILE"
                                            : "LENGKAPI PROFIL"}

                                        <ArrowRight className="h-4 w-4" />
                                    </Link>
                                )}
                            </section>

                            {/* Security */}

                            <section
                                className="
                                    rounded-[32px]
                                    border
                                    border-emerald-200
                                    bg-emerald-50
                                    p-7
                                "
                            >
                                <CheckCircle2 className="h-7 w-7 text-emerald-600" />

                                <h3
                                    className="
                                        mt-4
                                        text-lg
                                        font-black
                                        text-emerald-900
                                    "
                                >
                                    {isEn
                                        ? "Your Account Remains Active"
                                        : "Akun Anda Tetap Aktif"}
                                </h3>

                                <p
                                    className="
                                        mt-3
                                        text-sm
                                        leading-6
                                        text-emerald-800
                                    "
                                >
                                    {isEn
                                        ? "You can continue using your DIGESTEX account while company verification or profile activation is in progress."
                                        : "Anda tetap dapat menggunakan akun DIGESTEX selama proses verifikasi perusahaan atau aktivasi profil berlangsung."}
                                </p>
                            </section>
                        </aside>
                    </div>
                </section>
            </main>
        </div>
    );
}

/*
|--------------------------------------------------------------------------
| Progress Item
|--------------------------------------------------------------------------
*/

function ProgressItem({ item, number, isEn }) {
    const Icon = item.icon;

    let statusLabel = isEn ? "Not Started" : "Belum Dimulai";

    let statusClass = "bg-slate-100 text-slate-500";

    let IconStatus = Circle;

    if (item.completed) {
        statusLabel = isEn ? "Completed" : "Selesai";

        statusClass = "bg-emerald-50 text-emerald-700";

        IconStatus = CheckCircle2;
    } else if (item.pending) {
        statusLabel = isEn ? "Pending" : "Menunggu";

        statusClass = "bg-amber-50 text-amber-700";

        IconStatus = Clock3;
    } else if (item.rejected) {
        statusLabel = isEn ? "Action Required" : "Perlu Tindakan";

        statusClass = "bg-rose-50 text-rose-700";
    } else if (item.active) {
        statusLabel = isEn ? "Next" : "Berikutnya";

        statusClass = "bg-sky-50 text-sky-700";
    }

    return (
        <div
            className="
                flex
                flex-col
                gap-4
                rounded-3xl
                border
                border-slate-200
                p-5
                transition
                hover:border-slate-300
                md:flex-row
                md:items-center
            "
        >
            <div
                className={`
                    flex
                    h-12
                    w-12
                    shrink-0
                    items-center
                    justify-center
                    rounded-2xl

                    ${
                        item.completed
                            ? "bg-emerald-100"
                            : item.pending
                              ? "bg-amber-100"
                              : "bg-slate-100"
                    }
                `}
            >
                <Icon
                    className={`
                        h-6
                        w-6

                        ${
                            item.completed
                                ? "text-emerald-600"
                                : item.pending
                                  ? "text-amber-600"
                                  : "text-slate-500"
                        }
                    `}
                />
            </div>

            <div className="min-w-0 flex-1">
                <div
                    className="
                        text-xs
                        font-black
                        uppercase
                        tracking-widest
                        text-slate-400
                    "
                >
                    {isEn ? `Stage ${number}` : `Tahap ${number}`}
                </div>

                <div
                    className="
                        mt-1
                        font-black
                        text-slate-900
                    "
                >
                    {item.label}
                </div>

                <p
                    className="
                        mt-1
                        text-sm
                        text-slate-500
                    "
                >
                    {item.description}
                </p>
            </div>

            <div
                className={`
                    inline-flex
                    shrink-0
                    items-center
                    gap-2
                    self-start
                    rounded-full
                    px-3
                    py-2
                    text-xs
                    font-black
                    uppercase
                    md:self-auto

                    ${statusClass}
                `}
            >
                <IconStatus className="h-4 w-4" />

                {statusLabel}
            </div>
        </div>
    );
}

/*
|--------------------------------------------------------------------------
| Service Card
|--------------------------------------------------------------------------
*/

function ServiceCard({ service, isEn }) {
    const available = Boolean(service.available);

    const active = Boolean(service.active);

    return (
        <div
            className={`
                rounded-3xl
                border
                p-6

                ${
                    available
                        ? "border-slate-200 bg-white"
                        : "border-slate-200 bg-slate-50"
                }
            `}
        >
            <div
                className="
                    flex
                    items-start
                    justify-between
                    gap-4
                "
            >
                <div
                    className={`
                        flex
                        h-11
                        w-11
                        items-center
                        justify-center
                        rounded-2xl

                        ${
                            active
                                ? "bg-emerald-100"
                                : available
                                  ? "bg-violet-100"
                                  : "bg-slate-200"
                        }
                    `}
                >
                    {active ? (
                        <Check className="h-5 w-5 text-emerald-600" />
                    ) : available ? (
                        <Sparkles className="h-5 w-5 text-violet-600" />
                    ) : (
                        <LockKeyhole className="h-5 w-5 text-slate-500" />
                    )}
                </div>

                <ServiceBadge
                    available={available}
                    active={active}
                    isEn={isEn}
                />
            </div>

            <h3
                className="
                    mt-5
                    text-lg
                    font-black
                    text-slate-900
                "
            >
                {service.name}
            </h3>

            <p
                className="
                    mt-2
                    text-sm
                    leading-6
                    text-slate-500
                "
            >
                {active
                    ? isEn
                        ? "This service is active for your company."
                        : "Layanan ini sudah aktif untuk perusahaan Anda."
                    : available
                      ? isEn
                          ? "Included in your package and will be activated according to your program status."
                          : "Termasuk dalam paket Anda dan akan diaktifkan sesuai status program."
                      : isEn
                        ? "This service is not included in your current package."
                        : "Layanan ini tidak termasuk dalam paket Anda saat ini."}
            </p>
        </div>
    );
}

function ServiceBadge({ available, active, isEn }) {
    if (active) {
        return (
            <span
                className="
                    rounded-full
                    bg-emerald-50
                    px-3
                    py-1.5
                    text-xs
                    font-black
                    uppercase
                    text-emerald-700
                "
            >
                {isEn ? "Active" : "Aktif"}
            </span>
        );
    }

    if (available) {
        return (
            <span
                className="
                    rounded-full
                    bg-violet-50
                    px-3
                    py-1.5
                    text-xs
                    font-black
                    uppercase
                    text-violet-700
                "
            >
                {isEn ? "Included" : "Termasuk"}
            </span>
        );
    }

    return (
        <span
            className="
                rounded-full
                bg-slate-100
                px-3
                py-1.5
                text-xs
                font-black
                uppercase
                text-slate-500
            "
        >
            {isEn ? "Locked" : "Terkunci"}
        </span>
    );
}

/*
|--------------------------------------------------------------------------
| Info Item
|--------------------------------------------------------------------------
*/

function InfoItem({ label, value }) {
    return (
        <div>
            <div
                className="
                    text-xs
                    font-black
                    uppercase
                    tracking-widest
                    text-slate-400
                "
            >
                {label}
            </div>

            <div
                className="
                    mt-1
                    break-words
                    text-sm
                    font-bold
                    text-slate-800
                "
            >
                {value}
            </div>
        </div>
    );
}

/*
|--------------------------------------------------------------------------
| Ownership Helpers
|--------------------------------------------------------------------------
*/

function ownershipStatusLabel(status, isEn) {
    switch (status) {
        case "pending":
            return isEn ? "Pending Verification" : "Menunggu Verifikasi";

        case "approved":
            return isEn ? "Ownership Verified" : "Kepemilikan Terverifikasi";

        case "rejected":
            return isEn ? "Action Required" : "Perlu Tindakan";

        default:
            return isEn ? "Not Started" : "Belum Dimulai";
    }
}

function ownershipDescription(status, isEn) {
    switch (status) {
        case "pending":
            return isEn
                ? "Your ownership documents are being reviewed"
                : "Dokumen kepemilikan sedang ditinjau";

        case "approved":
            return isEn
                ? "Company ownership has been verified"
                : "Kepemilikan perusahaan telah diverifikasi";

        case "rejected":
            return isEn
                ? "Verification requires additional action"
                : "Verifikasi memerlukan tindakan lanjutan";

        default:
            return isEn
                ? "Find and verify your company"
                : "Cari dan verifikasi perusahaan Anda";
    }
}

/*
|--------------------------------------------------------------------------
| Date
|--------------------------------------------------------------------------
*/

function formatDate(value, isEn) {
    if (!value) {
        return "-";
    }

    const date = new Date(value);

    if (Number.isNaN(date.getTime())) {
        return value;
    }

    return new Intl.DateTimeFormat(isEn ? "en-US" : "id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
    }).format(date);
}
