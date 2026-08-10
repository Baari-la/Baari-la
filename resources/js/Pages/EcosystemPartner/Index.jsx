import WebsiteLayout from "@/Layouts/WebsiteLayout";
import { Head, Link, usePage } from "@inertiajs/react";

import {
    ArrowRight,
    Award,
    BadgeCheck,
    BarChart3,
    Building2,
    Check,
    ChevronRight,
    Globe2,
    Handshake,
    Lightbulb,
    Megaphone,
    ShieldCheck,
    Sparkles,
    Target,
    Users,
    Factory,
    FlaskConical,
    Truck,
    WalletCards,
    GraduationCap,
    CalendarDays,
} from "lucide-react";

export default function Index() {
    const { locale } = usePage().props;
    const isEn = locale === "en";

    const categories = isEn
        ? [
              {
                  title: "Testing & Certification",
                  description:
                      "Testing, certification, compliance, sustainability and laboratory solutions.",
                  icon: FlaskConical,
              },
              {
                  title: "Technology Solutions",
                  description:
                      "Digital transformation, PLM, software, automation and advanced technology solutions.",
                  icon: Lightbulb,
              },
              {
                  title: "Industrial Machinery",
                  description:
                      "Machinery, production equipment, automation and manufacturing technologies.",
                  icon: Factory,
              },
              {
                  title: "Raw Materials",
                  description:
                      "Fibers, yarns, chemicals, materials and other industrial inputs.",
                  icon: Sparkles,
              },
              {
                  title: "Logistics & Supply Chain",
                  description:
                      "Logistics, warehousing, freight and supply chain solutions.",
                  icon: Truck,
              },
              {
                  title: "Trade Finance",
                  description:
                      "Financial services, trade finance and business transaction solutions.",
                  icon: WalletCards,
              },
              {
                  title: "Exhibitions & Events",
                  description:
                      "Industry exhibitions, conferences, seminars and business events.",
                  icon: CalendarDays,
              },
              {
                  title: "Research & Education",
                  description:
                      "Research institutions, education, training and industry knowledge.",
                  icon: GraduationCap,
              },
          ]
        : [
              {
                  title: "Testing & Certification",
                  description:
                      "Testing, sertifikasi, compliance, sustainability dan solusi laboratorium.",
                  icon: FlaskConical,
              },
              {
                  title: "Technology Solutions",
                  description:
                      "Transformasi digital, PLM, software, automation dan solusi teknologi.",
                  icon: Lightbulb,
              },
              {
                  title: "Industrial Machinery",
                  description:
                      "Mesin, peralatan produksi, automation dan teknologi manufaktur.",
                  icon: Factory,
              },
              {
                  title: "Raw Materials",
                  description:
                      "Serat, benang, bahan kimia, material dan input industri lainnya.",
                  icon: Sparkles,
              },
              {
                  title: "Logistics & Supply Chain",
                  description:
                      "Logistik, pergudangan, freight dan solusi supply chain.",
                  icon: Truck,
              },
              {
                  title: "Trade Finance",
                  description:
                      "Layanan keuangan, trade finance dan solusi transaksi bisnis.",
                  icon: WalletCards,
              },
              {
                  title: "Exhibitions & Events",
                  description:
                      "Pameran industri, konferensi, seminar dan business events.",
                  icon: CalendarDays,
              },
              {
                  title: "Research & Education",
                  description:
                      "Lembaga riset, pendidikan, training dan industry knowledge.",
                  icon: GraduationCap,
              },
          ];

    const benefits = isEn
        ? [
              {
                  icon: Megaphone,
                  title: "Industry Visibility",
                  description:
                      "Increase visibility across the Indonesia and global textile industry ecosystem.",
              },
              {
                  icon: Lightbulb,
                  title: "Thought Leadership",
                  description:
                      "Share expertise, innovation, technology and industry knowledge with decision makers.",
              },
              {
                  icon: Handshake,
                  title: "Business Opportunities",
                  description:
                      "Connect with manufacturers, buyers, suppliers and strategic industry stakeholders.",
              },
              {
                  icon: Target,
                  title: "Strategic Positioning",
                  description:
                      "Position your organization as a relevant solution provider within the textile ecosystem.",
              },
          ]
        : [
              {
                  icon: Megaphone,
                  title: "Industry Visibility",
                  description:
                      "Meningkatkan visibilitas di dalam ekosistem industri tekstil Indonesia dan global.",
              },
              {
                  icon: Lightbulb,
                  title: "Thought Leadership",
                  description:
                      "Membagikan expertise, inovasi, teknologi dan pengetahuan industri kepada decision makers.",
              },
              {
                  icon: Handshake,
                  title: "Business Opportunities",
                  description:
                      "Terhubung dengan manufacturer, buyer, supplier dan stakeholder industri strategis.",
              },
              {
                  icon: Target,
                  title: "Strategic Positioning",
                  description:
                      "Memposisikan organisasi sebagai solution provider yang relevan dalam ekosistem tekstil.",
              },
          ];

    const partnerFeatures = isEn
        ? [
              "Strategic Solution Partner positioning",
              "Dedicated Solution Profile",
              "Industry Solutions Directory placement",
              "Enhanced ecosystem visibility",
              "Thought Leadership opportunities",
              "Partner Insights publication",
              "Industry campaign opportunities",
              "Strategic business introductions",
              "Ecosystem engagement opportunities",
              "Priority consideration for relevant DIGESTEX initiatives",
          ]
        : [
              "Positioning sebagai Strategic Solution Partner",
              "Dedicated Solution Profile",
              "Placement di Industry Solutions Directory",
              "Enhanced ecosystem visibility",
              "Kesempatan Thought Leadership",
              "Publikasi Partner Insights",
              "Kesempatan industry campaign",
              "Strategic business introductions",
              "Kesempatan ecosystem engagement",
              "Prioritas untuk inisiatif DIGESTEX yang relevan",
          ];

    const solutionExamples = isEn
        ? [
              {
                  title: "Technology & Digital Transformation",
                  text: "Digital printing, PLM, ERP, automation, AI and other technologies that improve industry performance.",
              },
              {
                  title: "Testing, Certification & Compliance",
                  text: "Testing laboratories, certification, sustainability, quality assurance and compliance solutions.",
              },
              {
                  title: "Machinery & Manufacturing Solutions",
                  text: "Production machinery, equipment, automation and manufacturing technologies.",
              },
          ]
        : [
              {
                  title: "Technology & Digital Transformation",
                  text: "Digital printing, PLM, ERP, automation, AI dan teknologi lainnya yang meningkatkan kinerja industri.",
              },
              {
                  title: "Testing, Certification & Compliance",
                  text: "Laboratorium testing, certification, sustainability, quality assurance dan compliance.",
              },
              {
                  title: "Machinery & Manufacturing Solutions",
                  text: "Mesin produksi, equipment, automation dan teknologi manufaktur.",
              },
          ];

    return (
        <>
            <Head
                title={
                    isEn
                        ? "DIGESTEX Strategic Solution Partner Program"
                        : "DIGESTEX Strategic Solution Partner Program"
                }
            />

            <WebsiteLayout>
                <div className="min-h-screen bg-slate-950 text-white">
                    {/* =====================================================
                        HERO
                    ===================================================== */}

                    <section className="relative overflow-hidden border-b border-white/5">
                        <div className="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(99,102,241,0.20),transparent_35%),radial-gradient(circle_at_bottom_left,rgba(16,185,129,0.12),transparent_35%)]" />

                        <div className="relative mx-auto max-w-7xl px-6 py-24 lg:py-32">
                            <div className="max-w-5xl">
                                <div className="inline-flex items-center gap-2 rounded-full border border-amber-400/20 bg-amber-400/10 px-4 py-2 text-xs font-black uppercase tracking-[0.22em] text-amber-300">
                                    <Award className="h-4 w-4" />

                                    {isEn
                                        ? "DIGESTEX STRATEGIC SOLUTION PARTNER PROGRAM"
                                        : "DIGESTEX STRATEGIC SOLUTION PARTNER PROGRAM"}
                                </div>

                                <h1 className="mt-8 text-5xl font-black uppercase leading-[0.98] tracking-tight sm:text-6xl lg:text-7xl">
                                    {isEn
                                        ? "Powering The Future Of The Textile Industry Ecosystem"
                                        : "Membangun Masa Depan Ekosistem Industri Tekstil"}
                                </h1>

                                <p className="mt-8 max-w-3xl text-lg leading-8 text-slate-300 lg:text-xl">
                                    {isEn
                                        ? "Position your company, technology, expertise and solutions within a connected textile industry ecosystem designed to create visibility, collaboration and business opportunities."
                                        : "Posisikan perusahaan, teknologi, expertise dan solusi Anda dalam ekosistem industri tekstil yang terhubung untuk menciptakan visibilitas, kolaborasi dan peluang bisnis."}
                                </p>

                                <div className="mt-10 flex flex-wrap gap-4">
                                    <a
                                        href="mailto:info@digestexmedia.com?subject=Strategic Solution Partner"
                                        className="inline-flex items-center gap-3 rounded-2xl bg-amber-400 px-7 py-4 text-sm font-black uppercase tracking-wide text-slate-950 transition hover:bg-amber-300"
                                    >
                                        {isEn
                                            ? "BECOME A STRATEGIC SOLUTION PARTNER"
                                            : "JADI STRATEGIC SOLUTION PARTNER"}

                                        <ArrowRight className="h-5 w-5" />
                                    </a>

                                    <a
                                        href="#partner-benefits"
                                        className="inline-flex items-center gap-3 rounded-2xl border border-white/15 bg-white/5 px-7 py-4 text-sm font-bold text-white backdrop-blur transition hover:bg-white/10"
                                    >
                                        {isEn
                                            ? "EXPLORE THE PROGRAM"
                                            : "PELAJARI PROGRAM"}

                                        <ChevronRight className="h-5 w-5" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    </section>

                    {/* =====================================================
                        STRATEGIC POSITIONING
                    ===================================================== */}

                    <section className="border-b border-white/5 py-20 lg:py-24">
                        <div className="mx-auto max-w-7xl px-6">
                            <div className="grid gap-10 lg:grid-cols-[1fr_1.2fr] lg:items-center">
                                <div>
                                    <span className="text-xs font-black uppercase tracking-[0.3em] text-amber-400">
                                        {isEn
                                            ? "WHY STRATEGIC PARTNERS MATTER"
                                            : "MENGAPA STRATEGIC PARTNER PENTING"}
                                    </span>

                                    <h2 className="mt-4 text-4xl font-black uppercase tracking-tight sm:text-5xl">
                                        {isEn
                                            ? "From Advertising To Ecosystem Value"
                                            : "Dari Advertising Menjadi Ecosystem Value"}
                                    </h2>
                                </div>

                                <div className="rounded-3xl border border-white/10 bg-white/5 p-8 backdrop-blur-xl">
                                    <p className="text-lg leading-8 text-slate-300">
                                        {isEn
                                            ? "DIGESTEX Strategic Solution Partners are not simply listed as advertisers. They become part of the industry solution ecosystem — helping manufacturers, buyers and decision makers discover relevant technologies, services and expertise."
                                            : "DIGESTEX Strategic Solution Partner bukan sekadar tampil sebagai advertiser. Mereka menjadi bagian dari ekosistem solusi industri — membantu manufacturer, buyer dan decision maker menemukan teknologi, layanan dan expertise yang relevan."}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {/* =====================================================
                        EXAMPLES
                    ===================================================== */}

                    <section className="border-b border-white/5 py-20 lg:py-24">
                        <div className="mx-auto max-w-7xl px-6">
                            <div className="mb-12 max-w-3xl">
                                <span className="text-xs font-black uppercase tracking-[0.3em] text-amber-400">
                                    {isEn
                                        ? "SOLUTION ECOSYSTEM"
                                        : "SOLUTION ECOSYSTEM"}
                                </span>

                                <h2 className="mt-4 text-4xl font-black uppercase tracking-tight">
                                    {isEn
                                        ? "Bring Solutions To The Industry"
                                        : "Membawa Solusi Untuk Industri"}
                                </h2>

                                <p className="mt-5 text-slate-400">
                                    {isEn
                                        ? "DIGESTEX provides a platform for solution providers to communicate how their technologies and services solve real industry challenges."
                                        : "DIGESTEX menyediakan platform bagi solution provider untuk menjelaskan bagaimana teknologi dan layanan mereka menjawab kebutuhan nyata industri."}
                                </p>
                            </div>

                            <div className="grid gap-6 lg:grid-cols-3">
                                {solutionExamples.map((item) => (
                                    <div
                                        key={item.title}
                                        className="rounded-3xl border border-white/10 bg-white/5 p-7 transition hover:border-amber-400/30 hover:bg-white/[0.07]"
                                    >
                                        <div className="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-400/10 text-amber-400">
                                            <Lightbulb className="h-6 w-6" />
                                        </div>

                                        <h3 className="mt-6 text-xl font-black">
                                            {item.title}
                                        </h3>

                                        <p className="mt-3 text-sm leading-7 text-slate-400">
                                            {item.text}
                                        </p>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </section>

                    {/* =====================================================
                        BENEFITS
                    ===================================================== */}

                    <section
                        id="partner-benefits"
                        className="border-b border-white/5 py-20 lg:py-24"
                    >
                        <div className="mx-auto max-w-7xl px-6">
                            <div className="mb-14 text-center">
                                <span className="text-xs font-black uppercase tracking-[0.3em] text-amber-400">
                                    {isEn
                                        ? "STRATEGIC PARTNER VALUE"
                                        : "NILAI STRATEGIC PARTNER"}
                                </span>

                                <h2 className="mt-4 text-4xl font-black uppercase tracking-tight sm:text-5xl">
                                    {isEn
                                        ? "Grow With The Ecosystem"
                                        : "Tumbuh Bersama Ekosistem"}
                                </h2>
                            </div>

                            <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                                {benefits.map((item) => {
                                    const Icon = item.icon;

                                    return (
                                        <div
                                            key={item.title}
                                            className="rounded-3xl border border-white/10 bg-white/5 p-8 backdrop-blur-xl transition hover:-translate-y-1 hover:border-amber-400/30"
                                        >
                                            <Icon className="h-9 w-9 text-amber-400" />

                                            <h3 className="mt-6 text-xl font-black">
                                                {item.title}
                                            </h3>

                                            <p className="mt-4 text-sm leading-7 text-slate-400">
                                                {item.description}
                                            </p>
                                        </div>
                                    );
                                })}
                            </div>
                        </div>
                    </section>

                    {/* =====================================================
                        CATEGORIES
                    ===================================================== */}

                    <section className="border-b border-white/5 py-20 lg:py-24">
                        <div className="mx-auto max-w-7xl px-6">
                            <div className="mb-14 text-center">
                                <span className="text-xs font-black uppercase tracking-[0.3em] text-amber-400">
                                    {isEn
                                        ? "PARTNER CATEGORIES"
                                        : "KATEGORI PARTNER"}
                                </span>

                                <h2 className="mt-4 text-4xl font-black uppercase tracking-tight sm:text-5xl">
                                    {isEn
                                        ? "Where Your Solution Fits"
                                        : "Tempat Solusi Anda Berada"}
                                </h2>
                            </div>

                            <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                                {categories.map((category) => {
                                    const Icon = category.icon;

                                    return (
                                        <div
                                            key={category.title}
                                            className="group rounded-2xl border border-white/10 bg-white/5 p-6 transition hover:border-amber-400/30 hover:bg-white/[0.08]"
                                        >
                                            <Icon className="h-7 w-7 text-amber-400 transition group-hover:scale-110" />

                                            <h3 className="mt-5 font-bold text-white">
                                                {category.title}
                                            </h3>

                                            <p className="mt-2 text-xs leading-6 text-slate-500">
                                                {category.description}
                                            </p>
                                        </div>
                                    );
                                })}
                            </div>
                        </div>
                    </section>

                    {/* =====================================================
                        SOLUTION PROFILE
                    ===================================================== */}

                    <section className="border-b border-white/5 py-20 lg:py-24">
                        <div className="mx-auto max-w-7xl px-6">
                            <div className="grid gap-10 lg:grid-cols-2 lg:items-center">
                                <div>
                                    <span className="text-xs font-black uppercase tracking-[0.3em] text-amber-400">
                                        {isEn
                                            ? "SOLUTION SHOWCASE"
                                            : "SOLUTION SHOWCASE"}
                                    </span>

                                    <h2 className="mt-4 text-4xl font-black uppercase tracking-tight sm:text-5xl">
                                        {isEn
                                            ? "Show More Than Your Company"
                                            : "Tampilkan Lebih Dari Sekadar Perusahaan"}
                                    </h2>

                                    <p className="mt-6 leading-8 text-slate-400">
                                        {isEn
                                            ? "A Strategic Solution Partner profile can explain the industry challenge, your solution, technology, business value, target users and relevant applications."
                                            : "Profil Strategic Solution Partner dapat menjelaskan industry challenge, solusi, teknologi, business value, target user dan aplikasi yang relevan."}
                                    </p>
                                </div>

                                <div className="rounded-3xl border border-white/10 bg-gradient-to-br from-white/10 to-white/5 p-8">
                                    <SolutionFlow
                                        number="01"
                                        title={
                                            isEn
                                                ? "Industry Challenge"
                                                : "Industry Challenge"
                                        }
                                    />

                                    <SolutionFlow
                                        number="02"
                                        title={
                                            isEn
                                                ? "Your Solution"
                                                : "Solusi Anda"
                                        }
                                    />

                                    <SolutionFlow
                                        number="03"
                                        title={
                                            isEn
                                                ? "Business Value"
                                                : "Business Value"
                                        }
                                    />

                                    <SolutionFlow
                                        number="04"
                                        title={
                                            isEn
                                                ? "Industry Connection"
                                                : "Industry Connection"
                                        }
                                        last
                                    />
                                </div>
                            </div>
                        </div>
                    </section>

                    {/* =====================================================
                        PARTNER PACKAGE
                    ===================================================== */}

                    <section className="border-b border-white/5 py-20 lg:py-24">
                        <div className="mx-auto max-w-5xl px-6">
                            <div className="relative overflow-hidden rounded-[40px] border border-amber-400/20 bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-950 p-8 shadow-2xl lg:p-12">
                                <div className="absolute -right-24 -top-24 h-72 w-72 rounded-full bg-amber-400/10 blur-3xl" />

                                <div className="relative">
                                    <div className="flex flex-col gap-6 sm:flex-row sm:items-start sm:justify-between">
                                        <div>
                                            <span className="inline-flex items-center gap-2 rounded-full bg-amber-400/10 px-4 py-2 text-xs font-black uppercase tracking-[0.18em] text-amber-300">
                                                <ShieldCheck className="h-4 w-4" />

                                                {isEn
                                                    ? "STRATEGIC PARTNERSHIP"
                                                    : "STRATEGIC PARTNERSHIP"}
                                            </span>

                                            <h2 className="mt-6 text-3xl font-black uppercase sm:text-4xl">
                                                Strategic Solution Partner
                                            </h2>

                                            <p className="mt-4 max-w-2xl text-slate-400">
                                                {isEn
                                                    ? "Designed for established corporations, global technology companies and strategic solution providers."
                                                    : "Dirancang untuk corporate besar, perusahaan teknologi global dan strategic solution provider."}
                                            </p>
                                        </div>

                                        <div className="shrink-0 sm:text-right">
                                            <p className="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">
                                                {isEn
                                                    ? "ANNUAL PARTNERSHIP"
                                                    : "PARTNERSHIP TAHUNAN"}
                                            </p>

                                            <p className="mt-2 text-4xl font-black text-white">
                                                US$12,000
                                            </p>

                                            <p className="text-sm text-slate-500">
                                                / year
                                            </p>
                                        </div>
                                    </div>

                                    <div className="mt-8 grid gap-3 sm:grid-cols-2">
                                        {partnerFeatures.map((feature) => (
                                            <div
                                                key={feature}
                                                className="flex items-start gap-3 text-sm text-slate-300"
                                            >
                                                <span className="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-400/10">
                                                    <Check className="h-3.5 w-3.5 text-emerald-400" />
                                                </span>

                                                {feature}
                                            </div>
                                        ))}
                                    </div>

                                    <div className="mt-10">
                                        <a
                                            href="https://wa.me/628129928939?text=Hello%20DIGESTEX%2C%20we%20would%20like%20to%20discuss%20a%20Strategic%20Solution%20Partnership."
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            className="
        inline-flex
        items-center
        gap-3
        rounded-2xl
        bg-amber-400
        px-7
        py-4
        text-sm
        font-black
        uppercase
        tracking-wide
        text-slate-950
        transition
        hover:bg-amber-300
    "
                                        >
                                            {isEn
                                                ? "DISCUSS STRATEGIC PARTNERSHIP"
                                                : "DISKUSIKAN STRATEGIC PARTNERSHIP"}

                                            <ArrowRight className="h-5 w-5" />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {/* =====================================================
                        FOUNDING PARTNER BENEFITS
                    ===================================================== */}

                    <section className="border-b border-white/5 py-20 lg:py-24">
                        <div className="mx-auto max-w-7xl px-6">
                            <div className="mb-14 text-center">
                                <span className="text-xs font-black uppercase tracking-[0.3em] text-amber-400">
                                    {isEn
                                        ? "FOUNDING PARTNER BENEFITS"
                                        : "MANFAAT FOUNDING PARTNER"}
                                </span>

                                <h2 className="mt-4 text-4xl font-black uppercase tracking-tight sm:text-5xl">
                                    {isEn
                                        ? "Why Join Early"
                                        : "Mengapa Bergabung Lebih Awal"}
                                </h2>

                                <p className="mx-auto mt-6 max-w-3xl text-slate-400">
                                    {isEn
                                        ? "Become part of the ecosystem from the beginning and help shape future industry collaboration, knowledge sharing and digital transformation."
                                        : "Menjadi bagian dari ekosistem sejak awal dan ikut membentuk kolaborasi industri, knowledge sharing dan transformasi digital di masa depan."}
                                </p>
                            </div>

                            <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                                <Benefit
                                    icon={Megaphone}
                                    title={
                                        isEn
                                            ? "Industry Visibility"
                                            : "Industry Visibility"
                                    }
                                    text={
                                        isEn
                                            ? "Increase visibility through industry directories, solution categories and ecosystem partner pages."
                                            : "Meningkatkan visibilitas melalui directory industri, kategori solusi dan halaman ecosystem partner."
                                    }
                                />

                                <Benefit
                                    icon={Lightbulb}
                                    title={
                                        isEn
                                            ? "Thought Leadership"
                                            : "Thought Leadership"
                                    }
                                    text={
                                        isEn
                                            ? "Share expertise, innovation, compliance updates, research and industry knowledge."
                                            : "Membagikan expertise, inovasi, compliance updates, research dan industry knowledge."
                                    }
                                />

                                <Benefit
                                    icon={Handshake}
                                    title={
                                        isEn
                                            ? "Strategic Collaboration"
                                            : "Strategic Collaboration"
                                    }
                                    text={
                                        isEn
                                            ? "Connect with manufacturers, exporters, suppliers, institutions and solution providers."
                                            : "Terhubung dengan manufacturer, exporter, supplier, institution dan solution provider."
                                    }
                                />

                                <Benefit
                                    icon={BarChart3}
                                    title={
                                        isEn
                                            ? "Market Intelligence Exposure"
                                            : "Market Intelligence Exposure"
                                    }
                                    text={
                                        isEn
                                            ? "Participate in industry discussions, market insights and ecosystem knowledge initiatives."
                                            : "Berpartisipasi dalam industry discussion, market insight dan ecosystem knowledge initiatives."
                                    }
                                />

                                <Benefit
                                    icon={BadgeCheck}
                                    title={
                                        isEn
                                            ? "Early Ecosystem Recognition"
                                            : "Early Ecosystem Recognition"
                                    }
                                    text={
                                        isEn
                                            ? "Be recognized as an organization supporting the development of the DIGESTEX ecosystem from its early stage."
                                            : "Diakui sebagai organisasi yang mendukung pengembangan ekosistem DIGESTEX sejak tahap awal."
                                    }
                                />

                                <Benefit
                                    icon={Globe2}
                                    title={
                                        isEn
                                            ? "Long-Term Partnership"
                                            : "Long-Term Partnership"
                                    }
                                    text={
                                        isEn
                                            ? "Explore future collaboration across solutions, sourcing, intelligence and ecosystem initiatives."
                                            : "Membuka peluang kolaborasi jangka panjang dalam solutions, sourcing, intelligence dan ecosystem initiatives."
                                    }
                                />
                            </div>
                        </div>
                    </section>

                    {/* =====================================================
                        FOUNDING PARTNERS
                    ===================================================== */}

                    <section className="border-b border-white/5 py-20 lg:py-24">
                        <div className="mx-auto max-w-6xl px-6 text-center">
                            <span className="text-xs font-black uppercase tracking-[0.3em] text-amber-400">
                                {isEn
                                    ? "FOUNDING STRATEGIC PARTNERS"
                                    : "FOUNDING STRATEGIC PARTNERS"}
                            </span>

                            <h2 className="mt-4 text-4xl font-black uppercase tracking-tight sm:text-5xl">
                                {isEn
                                    ? "Industry Leaders Joining Soon"
                                    : "Industry Leaders Segera Bergabung"}
                            </h2>

                            <p className="mx-auto mt-6 max-w-3xl leading-7 text-slate-400">
                                {isEn
                                    ? "DIGESTEX is engaging with technology providers, testing and certification organizations, machinery suppliers, financial institutions, logistics providers, exhibition organizers and research institutions across the textile ecosystem."
                                    : "DIGESTEX sedang membangun engagement dengan technology provider, organisasi testing dan certification, machinery supplier, financial institution, logistics provider, exhibition organizer dan research institution di seluruh ekosistem tekstil."}
                            </p>

                            <div className="mt-12 rounded-[40px] border border-dashed border-amber-400/20 bg-white/5 p-12 backdrop-blur-xl">
                                <Sparkles className="mx-auto h-10 w-10 text-amber-400" />

                                <p className="mt-5 text-lg font-black uppercase tracking-[0.25em] text-amber-300">
                                    {isEn ? "COMING SOON" : "SEGERA HADIR"}
                                </p>

                                <p className="mx-auto mt-4 max-w-xl text-sm leading-7 text-slate-500">
                                    {isEn
                                        ? "Strategic partner announcements will be published as partnerships are formally confirmed."
                                        : "Pengumuman strategic partner akan dipublikasikan setelah partnership dikonfirmasi secara resmi."}
                                </p>
                            </div>
                        </div>
                    </section>

                    {/* =====================================================
                        FINAL CTA
                    ===================================================== */}

                    <section className="py-24 lg:py-32">
                        <div className="mx-auto max-w-5xl px-6 text-center">
                            <span className="text-xs font-black uppercase tracking-[0.3em] text-amber-400">
                                {isEn ? "READY TO JOIN?" : "SIAP BERGABUNG?"}
                            </span>

                            <h2 className="mt-5 text-4xl font-black uppercase tracking-tight sm:text-5xl lg:text-6xl">
                                {isEn
                                    ? "Bring Your Solution To The Textile Industry"
                                    : "Bawa Solusi Anda Ke Industri Tekstil"}
                            </h2>

                            <p className="mx-auto mt-6 max-w-3xl text-lg leading-8 text-slate-400">
                                {isEn
                                    ? "Become part of the DIGESTEX Strategic Solution Partner ecosystem and build a stronger position within the textile industry value chain."
                                    : "Menjadi bagian dari ekosistem DIGESTEX Strategic Solution Partner dan membangun posisi yang lebih kuat dalam rantai nilai industri tekstil."}
                            </p>

                            <a
                                href="mailto:partnership@digtex.id?subject=Strategic Solution Partner"
                                className="mt-10 inline-flex items-center gap-3 rounded-full bg-amber-400 px-10 py-5 text-xs font-black uppercase tracking-[0.18em] text-slate-950 transition hover:bg-amber-300"
                            >
                                {isEn
                                    ? "BECOME A STRATEGIC SOLUTION PARTNER"
                                    : "JADI STRATEGIC SOLUTION PARTNER"}

                                <ArrowRight className="h-5 w-5" />
                            </a>
                        </div>
                    </section>
                </div>
            </WebsiteLayout>
        </>
    );
}

/* ==========================================================
   COMPONENTS
========================================================== */

function SolutionFlow({ number, title, last = false }) {
    return (
        <div className="flex gap-4">
            <div className="flex flex-col items-center">
                <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-400 text-xs font-black text-slate-950">
                    {number}
                </div>

                {!last && <div className="mt-2 h-10 w-px bg-white/10" />}
            </div>

            <div className="pb-6">
                <h3 className="font-bold text-white">{title}</h3>
            </div>
        </div>
    );
}

function Benefit({ icon: Icon, title, text }) {
    return (
        <div className="rounded-3xl border border-white/10 bg-white/5 p-8 transition hover:border-amber-400/30 hover:bg-white/[0.07]">
            <Icon className="h-8 w-8 text-amber-400" />

            <h3 className="mt-6 text-xl font-black text-white">{title}</h3>

            <p className="mt-4 text-sm leading-7 text-slate-400">{text}</p>
        </div>
    );
}
