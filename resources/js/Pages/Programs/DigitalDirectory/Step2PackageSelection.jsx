import ProgramNavbar from "@/Components/Program/ProgramNavbar";
import StickyWhatsAppButton from "@/Components/Program/StickyWhatsAppButton";

import { Link, usePage } from "@inertiajs/react";

import {
    ArrowLeft,
    ArrowRight,
    BarChart3,
    BadgeCheck,
    Check,
    Crown,
    Factory,
    Globe2,
    Leaf,
    Lightbulb,
    LockKeyhole,
    ShieldCheck,
    Sparkles,
    Star,
    Target,
    Truck,
    Users,
    Zap,
} from "lucide-react";

export default function Step2PackageSelection() {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    /*
    |--------------------------------------------------------------------------
    | PROGRAM PACKAGES
    |--------------------------------------------------------------------------
    */

    const packages = [
        {
            key: "Free",

            name: "FREE / BASIC",

            subtitle: isEn
                ? "Build Your Digital Presence"
                : "Bangun Kehadiran Digital Anda",

            description: isEn
                ? "Start your DIGESTEX journey with a free digital company presence and begin building your visibility."
                : "Mulai perjalanan DIGESTEX dengan kehadiran digital perusahaan secara gratis dan mulai membangun visibilitas Anda.",

            price: "Rp 0",

            period: isEn ? "Free" : "Gratis",

            icon: Globe2,

            accent: "slate",

            features: isEn
                ? [
                      "Company Identity",
                      "Basic Company Profile",
                      "Company Directory Listing",
                      "Claim Your Company",
                      "Products & Capabilities",
                      "Basic MOQ",
                      "Profile Completion",
                      "Basic Visibility Score™",
                  ]
                : [
                      "Company Identity",
                      "Profil Perusahaan Dasar",
                      "Listing di Company Directory",
                      "Claim Perusahaan",
                      "Produk & Kapabilitas",
                      "MOQ Dasar",
                      "Kelengkapan Profil",
                      "Basic Visibility Score™",
                  ],

            cta: isEn ? "START FREE" : "MULAI GRATIS",

            free: true,
        },

        {
            key: "Verified Company",

            name: "VERIFIED COMPANY",

            subtitle: isEn
                ? "Build Trust & Credibility"
                : "Bangun Kepercayaan & Kredibilitas",

            description: isEn
                ? "Strengthen your company's credibility with verified business information and a trusted digital company identity."
                : "Perkuat kredibilitas perusahaan dengan informasi bisnis terverifikasi dan identitas digital perusahaan yang terpercaya.",

            price: "Rp 2.500.000",

            period: isEn ? "/ year" : "/ tahun",

            icon: ShieldCheck,

            accent: "emerald",

            features: isEn
                ? [
                      "Everything in FREE",
                      "Verified Company Badge",
                      "Digital Company Passport™",
                      "Product & Capability Listing",
                      "Company Contact Information",
                      "Verification Status",
                      "Enhanced Company Profile",
                      "Export Market Profile",
                  ]
                : [
                      "Semua fitur FREE",
                      "Verified Company Badge",
                      "Digital Company Passport™",
                      "Listing Produk & Kapabilitas",
                      "Informasi Kontak Perusahaan",
                      "Status Verifikasi",
                      "Enhanced Company Profile",
                      "Profil Pasar Ekspor",
                  ],

            cta: isEn ? "SELECT PACKAGE" : "PILIH PAKET",
        },

        {
            key: "Visibility Partner",

            name: "PREMIUM / VISIBILITY PARTNER",

            subtitle: isEn
                ? "Increase Your Visibility"
                : "Tingkatkan Visibilitas Anda",

            description: isEn
                ? "Designed for companies seeking stronger visibility, discoverability and access to more business opportunities."
                : "Dirancang untuk perusahaan yang ingin meningkatkan visibilitas, discoverability, dan peluang bisnis.",

            price: "Rp 5.000.000",

            period: isEn ? "/ year" : "/ tahun",

            icon: Sparkles,

            accent: "indigo",

            recommended: true,

            features: isEn
                ? [
                      "Everything in Verified",
                      "Visibility Score™",
                      "Featured Company Listing",
                      "Enhanced Company Profile",
                      "Production Capacity",
                      "Machinery Information",
                      "Certifications",
                      "Export Markets",
                      "Lead Time",
                      "Business Visibility",
                      "RFQ Opportunity Access",
                  ]
                : [
                      "Semua fitur Verified",
                      "Visibility Score™",
                      "Featured Company Listing",
                      "Enhanced Company Profile",
                      "Kapasitas Produksi",
                      "Informasi Mesin",
                      "Sertifikasi",
                      "Pasar Ekspor",
                      "Lead Time",
                      "Business Visibility",
                      "Akses Peluang RFQ",
                  ],

            cta: isEn ? "CHOOSE RECOMMENDED" : "PILIH REKOMENDASI",
        },

        {
            key: "Executive Partner",

            name: "EXECUTIVE PARTNER",

            subtitle: isEn
                ? "Turn Intelligence Into Decisions"
                : "Ubah Intelligence Menjadi Keputusan",

            description: isEn
                ? "For executives and decision makers who require deeper industry intelligence and advanced market insight."
                : "Untuk pimpinan dan pengambil keputusan yang membutuhkan industry intelligence dan market insight yang lebih mendalam.",

            price: "Rp 10.000.000",

            period: isEn ? "/ year" : "/ tahun",

            icon: Crown,

            accent: "amber",

            features: isEn
                ? [
                      "Everything in Visibility",
                      "Executive Dashboard™",
                      "Executive Intelligence™",
                      "Market Intelligence",
                      "Trade Intelligence",
                      "Advanced Analytics",
                      "Executive-level Insights",
                      "Priority Visibility",
                  ]
                : [
                      "Semua fitur Visibility",
                      "Executive Dashboard™",
                      "Executive Intelligence™",
                      "Market Intelligence",
                      "Trade Intelligence",
                      "Advanced Analytics",
                      "Executive-level Insights",
                      "Priority Visibility",
                  ],

            comingSoon: isEn
                ? ["Smart Business Matching™", "Build My Supply Chain™"]
                : ["Smart Business Matching™", "Build My Supply Chain™"],

            cta: isEn ? "SELECT PACKAGE" : "PILIH PAKET",
        },
    ];

    /*
    |--------------------------------------------------------------------------
    | RENDER
    |--------------------------------------------------------------------------
    */

    return (
        <div className="min-h-screen bg-slate-50">
            <ProgramNavbar currentStep={2} />

            <main className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-12">
                <div className="space-y-10">
                    {/* =====================================================
                        HEADER
                    ===================================================== */}

                    <section className="text-center">
                        <div className="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] text-emerald-700">
                            <Sparkles className="h-4 w-4" />

                            {isEn
                                ? "DIGESTEX PROGRAM LEVELS"
                                : "LEVEL PROGRAM DIGESTEX"}
                        </div>

                        <p className="mt-6 text-sm font-black uppercase tracking-[0.25em] text-slate-400">
                            {isEn ? "STEP 2" : "LANGKAH 2"}
                        </p>

                        <h1 className="mt-3 text-4xl font-black tracking-tight text-slate-950 sm:text-5xl lg:text-6xl">
                            {isEn
                                ? "Choose Your DIGESTEX Program Level"
                                : "Pilih Level Program DIGESTEX Anda"}
                        </h1>

                        <p className="mx-auto mt-5 max-w-3xl text-base leading-7 text-slate-500 sm:text-lg">
                            {isEn
                                ? "Start free, build your digital presence, increase your visibility and grow toward greater intelligence and strategic industry participation."
                                : "Mulai gratis, bangun kehadiran digital, tingkatkan visibilitas dan berkembang menuju intelligence serta partisipasi strategis dalam ekosistem industri."}
                        </p>

                        <div className="mt-6 inline-flex items-center gap-2 rounded-full bg-slate-900 px-5 py-2.5 text-sm font-bold text-white">
                            <Globe2 className="h-4 w-4 text-emerald-400" />

                            {isEn
                                ? "START FREE. BUILD VISIBILITY. GROW WITH DIGESTEX."
                                : "MULAI GRATIS. BANGUN VISIBILITAS. TUMBUH BERSAMA DIGESTEX."}
                        </div>
                    </section>

                    {/* =====================================================
                        JOURNEY STRIP
                    ===================================================== */}

                    <section className="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                        <div className="grid md:grid-cols-5">
                            <JourneyStep
                                number="01"
                                title={isEn ? "Build" : "Bangun"}
                                text={
                                    isEn
                                        ? "Digital Presence"
                                        : "Kehadiran Digital"
                                }
                                active
                            />

                            <JourneyStep
                                number="02"
                                title={isEn ? "Verify" : "Verifikasi"}
                                text={
                                    isEn ? "Build Trust" : "Bangun Kepercayaan"
                                }
                            />

                            <JourneyStep
                                number="03"
                                title={isEn ? "Grow" : "Tumbuh"}
                                text={
                                    isEn
                                        ? "Increase Visibility"
                                        : "Tingkatkan Visibilitas"
                                }
                            />

                            <JourneyStep
                                number="04"
                                title={isEn ? "Discover" : "Ditemukan"}
                                text={
                                    isEn
                                        ? "Business Opportunities"
                                        : "Peluang Bisnis"
                                }
                            />

                            <JourneyStep
                                number="05"
                                title={isEn ? "Connect" : "Terhubung"}
                                text={
                                    isEn
                                        ? "Global Ecosystem"
                                        : "Ekosistem Global"
                                }
                            />
                        </div>
                    </section>

                    {/* =====================================================
                        PACKAGE CARDS
                    ===================================================== */}

                    <section className="grid gap-6 xl:grid-cols-4">
                        {packages.map((pkg) => {
                            const Icon = pkg.icon;

                            return (
                                <PackageCard
                                    key={pkg.key}
                                    pkg={pkg}
                                    isEn={isEn}
                                    Icon={Icon}
                                />
                            );
                        })}
                    </section>

                    {/* =====================================================
    STRATEGIC SOLUTION PARTNER
===================================================== */}

                    <section className="relative overflow-hidden rounded-3xl bg-slate-950 p-6 text-white shadow-2xl sm:p-8 lg:p-10">
                        {/* =================================================
        DECORATIVE BACKGROUND
    ================================================= */}

                        <div className="pointer-events-none absolute -right-32 -top-32 h-96 w-96 rounded-full bg-indigo-500/20 blur-3xl" />

                        <div className="pointer-events-none absolute -bottom-32 left-1/4 h-96 w-96 rounded-full bg-emerald-500/10 blur-3xl" />

                        <div className="pointer-events-none absolute right-1/3 top-1/3 h-64 w-64 rounded-full bg-amber-400/10 blur-3xl" />

                        {/* =================================================
        MAIN GRID
    ================================================= */}

                        <div className="relative grid gap-8 lg:grid-cols-[0.9fr_1.6fr]">
                            {/* =================================================
            LEFT COLUMN
        ================================================= */}

                            <div>
                                {/* Benefits */}

                                <div className="grid grid-cols-2 gap-3">
                                    {(isEn
                                        ? [
                                              "Industry Visibility",
                                              "Ecosystem Positioning",
                                              "Solution Showcase",
                                              "Thought Leadership",
                                              "Executive Engagement",
                                              "Partnership Opportunities",
                                          ]
                                        : [
                                              "Industry Visibility",
                                              "Strategis dalam Ekosistem",
                                              "Solution Showcase",
                                              "Thought Leadership",
                                              "Executive Engagement",
                                              "Peluang Partnership",
                                          ]
                                    ).map((item) => (
                                        <div
                                            key={item}
                                            className="
                            min-h-[82px]
                            rounded-2xl
                            border
                            border-white/10
                            bg-white/[0.035]
                            p-4
                            transition
                            hover:border-emerald-400/30
                            hover:bg-white/[0.06]
                        "
                                        >
                                            <div className="flex items-start gap-3">
                                                <span
                                                    className="
                                    flex
                                    h-7
                                    w-7
                                    shrink-0
                                    items-center
                                    justify-center
                                    rounded-full
                                    bg-emerald-400/10
                                "
                                                >
                                                    <Check className="h-4 w-4 text-emerald-400" />
                                                </span>

                                                <span className="text-sm font-bold leading-5 text-slate-200">
                                                    {item}
                                                </span>
                                            </div>
                                        </div>
                                    ))}
                                </div>

                                {/* =================================================
                STRATEGIC SOLUTION CATEGORIES
            ================================================= */}

                                <div className="mt-8">
                                    <p className="text-xs font-black uppercase tracking-[0.18em] text-slate-500">
                                        {isEn
                                            ? "Strategic Solution Partners We Welcome"
                                            : "Mitra Solusi Strategis yang Kami Sambut"}
                                    </p>

                                    <div className="mt-4 flex flex-wrap gap-2">
                                        {(isEn
                                            ? [
                                                  "Textile & Garment Machinery",
                                                  "Testing & Certification",
                                                  "Energy & Utilities",
                                                  "Logistics & Supply Chain",
                                                  "ERP & PLM",
                                                  "AI & Digital Transformation",
                                                  "Digital Textile Printing",
                                                  "Sustainability & Circularity",
                                                  "Raw Materials & Textile Chemicals",
                                                  "Trade Finance & Insurance",
                                                  "Exhibition & Event Organizers",
                                                  "Industry Research & Education",
                                              ]
                                            : [
                                                  "Mesin Tekstil & Garmen",
                                                  "Testing & Certification",
                                                  "Energi & Utilities",
                                                  "Logistik & Supply Chain",
                                                  "ERP & PLM",
                                                  "AI & Transformasi Digital",
                                                  "Digital Textile Printing",
                                                  "Sustainability & Circularity",
                                                  "Bahan Baku & Bahan Kimia Tekstil",
                                                  "Trade Finance & Insurance",
                                                  "Penyelenggara Pameran & Event",
                                                  "Riset & Pendidikan Industri",
                                              ]
                                        ).map((item) => (
                                            <span
                                                key={item}
                                                className="
                                rounded-full
                                border
                                border-white/10
                                bg-white/[0.035]
                                px-3
                                py-2
                                text-xs
                                font-medium
                                text-slate-300
                                transition
                                hover:border-emerald-400/30
                                hover:bg-emerald-400/10
                                hover:text-emerald-300
                            "
                                            >
                                                {item}
                                            </span>
                                        ))}
                                    </div>

                                    <p className="mt-5 max-w-md text-xs leading-5 text-slate-500">
                                        {isEn
                                            ? "If your company provides a solution that can improve textile manufacturing, sourcing, trade, sustainability or digital transformation, we would like to hear from you."
                                            : "Jika perusahaan Anda menyediakan solusi yang dapat meningkatkan manufaktur tekstil, sourcing, perdagangan, sustainability, atau transformasi digital, kami ingin berdiskusi dengan Anda."}
                                    </p>
                                </div>
                            </div>

                            {/* =================================================
            RIGHT — STRATEGIC PARTNERSHIP SHOWCASE
        ================================================= */}

                            <div className="relative overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-br from-slate-900 via-indigo-950/40 to-slate-950 p-6 sm:p-8">
                                {/* Inner Glow */}

                                <div className="pointer-events-none absolute right-0 top-0 h-64 w-64 rounded-full bg-indigo-500/10 blur-3xl" />

                                <div className="relative">
                                    {/* Partnership Badge */}

                                    <div className="flex justify-center">
                                        <div className="flex h-14 w-14 items-center justify-center rounded-full border border-amber-400/50 bg-amber-400/10 shadow-lg shadow-amber-400/10">
                                            <Sparkles className="h-7 w-7 text-amber-300" />
                                        </div>
                                    </div>

                                    {/* Heading */}

                                    <div className="mx-auto mt-5 max-w-3xl text-center">
                                        <div className="inline-flex items-center gap-2 rounded-full border border-amber-400/30 bg-amber-400/10 px-4 py-2 text-xs font-black uppercase tracking-[0.16em] text-amber-300">
                                            <Star className="h-4 w-4 fill-current" />

                                            {isEn
                                                ? "STRATEGIC SOLUTION PARTNER"
                                                : "STRATEGIC SOLUTION PARTNER"}
                                        </div>

                                        <h2 className="mt-5 text-3xl font-black leading-tight sm:text-4xl">
                                            {isEn
                                                ? "Powering the Textile Industry Ecosystem"
                                                : "Kemitraan Strategis untuk Menggerakkan Industri Tekstil"}
                                        </h2>

                                        <p className="mx-auto mt-4 max-w-2xl text-sm leading-6 text-slate-300 sm:text-base">
                                            {isEn
                                                ? "Connect your solutions, innovation and expertise with textile companies, decision makers and business opportunities across the global industry ecosystem."
                                                : "Hubungkan solusi, inovasi, dan keahlian perusahaan Anda dengan perusahaan tekstil, pengambil keputusan, dan peluang bisnis dalam ekosistem industri global."}
                                        </p>
                                    </div>

                                    {/* =================================================
    CTA + ECOSYSTEM VISUAL
================================================= */}

                                    <div className="mt-8 grid gap-6 lg:grid-cols-[0.8fr_1.2fr] lg:items-center">
                                        {/* =================================================
        CTA PANEL
    ================================================= */}

                                        <div className="relative z-20 rounded-3xl border border-white/10 bg-white/[0.045] p-5 shadow-xl backdrop-blur-sm">
                                            <p className="text-sm leading-6 text-slate-400">
                                                {isEn
                                                    ? "Let's explore how your company can contribute to and grow within the DIGESTEX ecosystem."
                                                    : "Mari diskusikan bagaimana perusahaan Anda dapat berkontribusi dan berkembang bersama ekosistem DIGESTEX."}
                                            </p>

                                            {/* WhatsApp */}

                                            <a
                                                href={`https://wa.me/628129928939?text=${encodeURIComponent(
                                                    isEn
                                                        ? "Hello DIGESTEX, we would like to discuss becoming a Strategic Solution Partner."
                                                        : "Halo DIGESTEX, kami ingin mendiskusikan peluang menjadi Strategic Solution Partner.",
                                                )}`}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                className="
                mt-5
                inline-flex
                w-full
                items-center
                justify-center
                gap-2
                rounded-2xl
                bg-amber-400
                px-6
                py-4
                text-sm
                font-black
                text-slate-950
                shadow-lg
                shadow-amber-500/10
                transition
                hover:bg-amber-300
                hover:shadow-amber-400/20
            "
                                            >
                                                {isEn
                                                    ? "DISCUSS ON WHATSAPP"
                                                    : "DISKUSI VIA WHATSAPP"}

                                                <ArrowRight className="h-5 w-5" />
                                            </a>

                                            {/* Formal Inquiry */}

                                            <Link
                                                href={route(
                                                    "strategic-partnership.create",
                                                )}
                                                className="
                mt-3
                inline-flex
                w-full
                items-center
                justify-center
                gap-2
                rounded-2xl
                border
                border-white/15
                bg-white/5
                px-6
                py-4
                text-sm
                font-black
                text-white
                transition
                hover:border-white/25
                hover:bg-white/10
            "
                                            >
                                                {isEn
                                                    ? "SUBMIT PARTNERSHIP INQUIRY"
                                                    : "KIRIM INQUIRY PARTNERSHIP"}

                                                <ArrowRight className="h-5 w-5" />
                                            </Link>

                                            {/* Commercial Note */}

                                            <div className="mt-5 border-t border-white/10 pt-4 text-center">
                                                <p className="text-xs leading-5 text-slate-500">
                                                    {isEn
                                                        ? "Partnership scope and commercial terms are discussed directly with DIGESTEX."
                                                        : "Ruang lingkup kemitraan dan ketentuan komersial dibahas langsung bersama DIGESTEX."}
                                                </p>
                                            </div>
                                        </div>

                                        {/* =================================================
        DIGITAL GLOBE
    ================================================= */}

                                        <div className="relative flex min-h-[330px] items-center justify-center overflow-visible">
                                            {/* Ambient Glow */}

                                            <div
                                                className="
            pointer-events-none
            absolute
            h-72
            w-72
            rounded-full
            bg-cyan-400/10
            blur-3xl
        "
                                            />

                                            <div
                                                className="
            pointer-events-none
            absolute
            h-56
            w-56
            rounded-full
            bg-indigo-500/10
            blur-3xl
        "
                                            />

                                            {/* Outer Orbit */}

                                            <div
                                                className="
                pointer-events-none
                absolute
                h-[300px]
                w-[300px]
                rounded-full
                border
                border-indigo-400/10
            "
                                            />

                                            {/* Inner Orbit */}

                                            <div
                                                className="
                pointer-events-none
                absolute
                h-[245px]
                w-[245px]
                rounded-full
                border
                border-emerald-400/10
            "
                                            />

                                            {/* Digital Globe */}

                                            <div className="relative z-10 flex h-80 w-80 items-center justify-center">
                                                <img
                                                    src="/images/digestex/digital-globe.png"
                                                    alt="DIGESTEX Global Textile Intelligence Ecosystem"
                                                    className="
            h-full
            w-full
            object-contain
            drop-shadow-[0_0_45px_rgba(56,189,248,0.30)]
            transition
            duration-700
            hover:scale-[1.03]
        "
                                                />
                                            </div>

                                            {/* =================================================
            ECOSYSTEM CONNECTION POINTS
        ================================================= */}

                                            <div
                                                className="
            absolute
            left-[18%]
            top-[32%]
            z-20
            h-2
            w-2
            rounded-full
            bg-emerald-400
            shadow-[0_0_12px_rgba(52,211,153,0.8)]
        "
                                            />

                                            <div
                                                className="
            absolute
            right-[16%]
            top-[38%]
            z-20
            h-2
            w-2
            rounded-full
            bg-indigo-400
            shadow-[0_0_12px_rgba(129,140,248,0.8)]
        "
                                            />

                                            <div
                                                className="
            absolute
            bottom-[18%]
            left-[34%]
            z-20
            h-2
            w-2
            rounded-full
            bg-amber-400
            shadow-[0_0_12px_rgba(251,191,36,0.8)]
        "
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* =================================================
        BOTTOM VALUE PROPOSITION
    ================================================= */}

                        <div className="relative mt-6 overflow-hidden rounded-3xl border border-white/10 bg-white/[0.035]">
                            <div className="grid md:grid-cols-2 lg:grid-cols-4">
                                <StrategicValue
                                    icon={Users}
                                    title={
                                        isEn
                                            ? "Connect with Decision Makers"
                                            : "Terhubung dengan Decision Makers"
                                    }
                                    text={
                                        isEn
                                            ? "Direct access to executives and industry decision makers."
                                            : "Akses langsung ke eksekutif dan pengambil keputusan industri."
                                    }
                                />

                                <StrategicValue
                                    icon={BarChart3}
                                    title={
                                        isEn
                                            ? "Expand Visibility"
                                            : "Perluas Jangkauan & Visibilitas"
                                    }
                                    text={
                                        isEn
                                            ? "Increase your company's presence across the DIGESTEX ecosystem."
                                            : "Tingkatkan kehadiran perusahaan Anda dalam ekosistem DIGESTEX."
                                    }
                                />

                                <StrategicValue
                                    icon={Globe2}
                                    title={
                                        isEn
                                            ? "Enter the Global Ecosystem"
                                            : "Masuk ke Ekosistem Industri Global"
                                    }
                                    text={
                                        isEn
                                            ? "Reach textile and apparel companies across Indonesia and global markets."
                                            : "Jangkau perusahaan tekstil dan apparel di Indonesia dan pasar global."
                                    }
                                />

                                <StrategicValue
                                    icon={Lightbulb}
                                    title={
                                        isEn
                                            ? "Drive Innovation"
                                            : "Mendorong Inovasi & Transformasi"
                                    }
                                    text={
                                        isEn
                                            ? "Help build a more efficient, digital and sustainable textile industry."
                                            : "Bersama membangun industri tekstil yang lebih efisien, digital, dan berkelanjutan."
                                    }
                                />
                            </div>
                        </div>
                    </section>
                    {/* =====================================================
                        PROGRAM PRINCIPLE
                    ===================================================== */}

                    <section className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm lg:p-10">
                        <div className="grid gap-8 lg:grid-cols-3">
                            <Principle
                                icon={Target}
                                title={
                                    isEn
                                        ? "Start Where You Are"
                                        : "Mulai dari Posisi Anda"
                                }
                                text={
                                    isEn
                                        ? "Every company can begin with a free digital presence and grow when it is ready."
                                        : "Setiap perusahaan dapat memulai dengan kehadiran digital gratis dan berkembang ketika sudah siap."
                                }
                            />

                            <Principle
                                icon={BadgeCheck}
                                title={
                                    isEn ? "Build Trust" : "Bangun Kepercayaan"
                                }
                                text={
                                    isEn
                                        ? "Complete and verified information strengthens buyer confidence and business credibility."
                                        : "Informasi yang lengkap dan terverifikasi meningkatkan kepercayaan buyer dan kredibilitas bisnis."
                                }
                            />

                            <Principle
                                icon={Users}
                                title={
                                    isEn
                                        ? "Grow Your Opportunities"
                                        : "Kembangkan Peluang"
                                }
                                text={
                                    isEn
                                        ? "Greater visibility and better company data create stronger opportunities to connect with the industry."
                                        : "Visibilitas dan data perusahaan yang lebih baik membuka peluang koneksi bisnis yang lebih luas."
                                }
                            />
                        </div>
                    </section>

                    {/* =====================================================
                        BACK
                    ===================================================== */}

                    <div className="flex justify-start">
                        <Link
                            href={route("program.digital-directory")}
                            className="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-6 py-4 font-bold text-slate-700 shadow-sm transition hover:bg-slate-50"
                        >
                            <ArrowLeft className="h-5 w-5" />

                            {isEn ? "BACK" : "KEMBALI"}
                        </Link>
                    </div>

                    {/* =====================================================
                        FOOTER NOTE
                    ===================================================== */}

                    <div className="rounded-3xl border border-slate-200 bg-white p-6 text-center shadow-sm">
                        <div className="flex flex-col items-center gap-3">
                            <LockKeyhole className="h-5 w-5 text-slate-400" />

                            <p className="max-w-3xl text-sm leading-6 text-slate-500">
                                {isEn
                                    ? "Free access is available for companies beginning their DIGESTEX digital journey. Paid program levels are billed annually. Features marked Coming Soon will be introduced as the ecosystem develops."
                                    : "Akses Free tersedia bagi perusahaan yang memulai perjalanan digital bersama DIGESTEX. Level program berbayar ditagihkan secara tahunan. Fitur bertanda Coming Soon akan diperkenalkan seiring berkembangnya ekosistem."}
                            </p>
                        </div>
                    </div>
                </div>

                <StickyWhatsAppButton
                    position="left"
                    message={
                        isEn
                            ? "Hello DIGESTEX, I would like to know more about the Strategic Industry & Visibility Program."
                            : "Halo DIGESTEX, saya ingin mengetahui lebih lanjut tentang Strategic Industry & Visibility Program."
                    }
                />
            </main>
        </div>
    );
}

/* ==========================================================
   PACKAGE CARD
========================================================== */

function PackageCard({ pkg, isEn, Icon }) {
    const isRecommended = pkg.recommended;

    /*
    |--------------------------------------------------------------------------
    | FREE → DIRECT REGISTRATION
    |--------------------------------------------------------------------------
    */

    const href = pkg.free
        ? route("register")
        : route("program.digital-directory.company-information", {
              package: pkg.key,
          });

    return (
        <div
            className={`
                relative flex h-full flex-col overflow-hidden
                rounded-3xl bg-white
                transition duration-300
                hover:-translate-y-1 hover:shadow-2xl
                ${
                    isRecommended
                        ? "border-2 border-indigo-500 shadow-xl shadow-indigo-100"
                        : "border border-slate-200 shadow-sm"
                }
            `}
        >
            {isRecommended && (
                <div className="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-indigo-500 via-emerald-500 to-indigo-500" />
            )}

            {isRecommended && (
                <div className="absolute right-5 top-5 inline-flex items-center gap-1.5 rounded-full bg-indigo-600 px-3 py-1.5 text-[10px] font-black uppercase tracking-wider text-white shadow-lg">
                    <Star className="h-3 w-3 fill-current" />

                    {isEn ? "RECOMMENDED" : "REKOMENDASI"}
                </div>
            )}

            <div className="flex flex-1 flex-col p-7 lg:p-8">
                {/* Icon */}

                <div
                    className={`
                        flex h-14 w-14 items-center justify-center rounded-2xl
                        ${
                            pkg.accent === "emerald"
                                ? "bg-emerald-50 text-emerald-600"
                                : pkg.accent === "indigo"
                                  ? "bg-indigo-50 text-indigo-600"
                                  : pkg.accent === "amber"
                                    ? "bg-amber-50 text-amber-600"
                                    : "bg-slate-100 text-slate-700"
                        }
                    `}
                >
                    <Icon className="h-7 w-7" />
                </div>

                {/* Name */}

                <h2 className="mt-6 text-2xl font-black tracking-tight text-slate-950">
                    {pkg.name}
                </h2>

                {/* Subtitle */}

                <p className="mt-2 font-bold text-slate-700">{pkg.subtitle}</p>

                {/* Description */}

                <p className="mt-3 min-h-[88px] text-sm leading-6 text-slate-500">
                    {pkg.description}
                </p>

                {/* Price */}

                <div className="mt-6 border-y border-slate-100 py-5">
                    <div className="flex items-end gap-1">
                        <span className="text-3xl font-black tracking-tight text-slate-950">
                            {pkg.price}
                        </span>

                        <span className="mb-1 text-sm font-medium text-slate-400">
                            {pkg.period}
                        </span>
                    </div>
                </div>

                {/* Features */}

                <div className="mt-6 flex-1 space-y-3">
                    {pkg.features.map((feature) => (
                        <div
                            key={feature}
                            className="flex items-start gap-3 text-sm leading-5 text-slate-700"
                        >
                            <span className="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-50">
                                <Check className="h-3.5 w-3.5 text-emerald-600" />
                            </span>

                            <span>{feature}</span>
                        </div>
                    ))}
                </div>

                {/* Coming Soon */}

                {pkg.comingSoon?.length > 0 && (
                    <div className="mt-6 rounded-2xl border border-indigo-100 bg-indigo-50 p-4">
                        <div className="text-[10px] font-black uppercase tracking-[0.16em] text-indigo-500">
                            {isEn ? "COMING SOON" : "SEGERA HADIR"}
                        </div>

                        <div className="mt-3 space-y-2">
                            {pkg.comingSoon.map((item) => (
                                <div
                                    key={item}
                                    className="flex items-center gap-2 text-xs font-semibold text-slate-600"
                                >
                                    <Sparkles className="h-3.5 w-3.5 text-indigo-500" />

                                    {item}
                                </div>
                            ))}
                        </div>
                    </div>
                )}

                {/* CTA */}

                <Link
                    href={href}
                    className={`
                        mt-8 flex items-center justify-center gap-2
                        rounded-2xl px-5 py-4
                        text-center text-sm font-black
                        transition
                        ${
                            pkg.free
                                ? "border border-slate-300 bg-white text-slate-900 hover:bg-slate-50"
                                : isRecommended
                                  ? "bg-indigo-600 text-white shadow-lg shadow-indigo-200 hover:bg-indigo-700"
                                  : pkg.accent === "amber"
                                    ? "bg-slate-950 text-white hover:bg-slate-800"
                                    : "bg-slate-900 text-white hover:bg-slate-800"
                        }
                    `}
                >
                    {pkg.cta}

                    <ArrowRight className="h-4 w-4" />
                </Link>

                {pkg.free && (
                    <p className="mt-3 text-center text-xs text-slate-400">
                        {isEn
                            ? "No payment required"
                            : "Tidak memerlukan pembayaran"}
                    </p>
                )}
            </div>
        </div>
    );
}

/* ==========================================================
   JOURNEY STEP
========================================================== */

function JourneyStep({ number, title, text, active = false }) {
    return (
        <div
            className={`
                relative border-b border-slate-100 p-5 last:border-b-0
                md:border-b-0 md:border-r md:last:border-r-0
                ${active ? "bg-emerald-50/70" : "bg-white"}
            `}
        >
            <div className="flex items-center gap-3">
                <span
                    className={`
                        flex h-9 w-9 shrink-0 items-center justify-center rounded-xl
                        text-xs font-black
                        ${
                            active
                                ? "bg-emerald-600 text-white"
                                : "bg-slate-100 text-slate-500"
                        }
                    `}
                >
                    {number}
                </span>

                <div>
                    <p className="text-sm font-black text-slate-900">{title}</p>

                    <p className="mt-0.5 text-xs leading-5 text-slate-500">
                        {text}
                    </p>
                </div>
            </div>
        </div>
    );
}

/* ==========================================================
   PRINCIPLE
========================================================== */

function Principle({ icon: Icon, title, text }) {
    return (
        <div className="flex gap-4">
            <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-slate-100 text-indigo-600">
                <Icon className="h-6 w-6" />
            </div>

            <div>
                <h3 className="font-bold text-slate-900">{title}</h3>

                <p className="mt-2 text-sm leading-6 text-slate-500">{text}</p>
            </div>
        </div>
    );
}

/* ==========================================================
   ECOSYSTEM NODE
========================================================== */

function EcosystemNode({ icon: Icon, className = "" }) {
    return (
        <div
            className={`
                absolute
                z-20
                flex
                h-11
                w-11
                items-center
                justify-center
                rounded-full
                border
                border-indigo-400/30
                bg-slate-900
                shadow-lg
                shadow-indigo-500/10
                ${className}
            `}
        >
            <Icon className="h-5 w-5 text-indigo-300" />
        </div>
    );
}

/* ==========================================================
   STRATEGIC VALUE
========================================================== */

function StrategicValue({ icon: Icon, title, text }) {
    return (
        <div className="border-b border-white/10 p-6 md:border-r lg:border-b-0">
            <div className="flex items-start gap-4">
                {/* Icon */}

                <div className="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-amber-400/10">
                    <Icon className="h-5 w-5 text-amber-300" />
                </div>

                {/* Content */}

                <div>
                    <h3 className="text-sm font-black leading-5 text-white">
                        {title}
                    </h3>

                    <p className="mt-2 text-xs leading-5 text-slate-500">
                        {text}
                    </p>
                </div>
            </div>
        </div>
    );
}
