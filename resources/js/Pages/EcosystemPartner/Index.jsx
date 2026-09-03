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
    Zap,
    Network,
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
                  title: "Testing, Certification & Compliance",
                  description:
                      "Testing laboratories, certification bodies, compliance, sustainability, quality assurance and technical verification services.",
                  icon: FlaskConical,
              },
              {
                  title: "Technology & Digital Transformation",
                  description:
                      "AI, software, ERP, PLM, automation, digital transformation, digital printing and technologies shaping the future of textile manufacturing.",
                  icon: Lightbulb,
              },
              {
                  title: "Industrial Machinery & Manufacturing",
                  description:
                      "Spinning, weaving, knitting, dyeing, finishing, garment, printing, automation and other manufacturing technologies.",
                  icon: Factory,
              },
              {
                  title: "Raw Materials & Textile Inputs",
                  description:
                      "Fibers, yarns, fabrics, dyes, chemicals, auxiliaries and other materials supporting the textile value chain.",
                  icon: Sparkles,
              },
              {
                  title: "Energy, Utilities & Sustainability",
                  description:
                      "Energy, water, waste management, renewable solutions, resource efficiency and technologies supporting sustainable manufacturing.",
                  icon: Zap,
              },
              {
                  title: "Logistics & Supply Chain",
                  description:
                      "Freight, warehousing, logistics, customs, supply chain management and trade facilitation solutions.",
                  icon: Truck,
              },
              {
                  title: "Trade Finance & Insurance",
                  description:
                      "Trade finance, banking, insurance, payment solutions and financial services supporting international business.",
                  icon: WalletCards,
              },
              {
                  title: "Exhibitions, Events & Industry Platforms",
                  description:
                      "Trade fairs, exhibitions, conferences, seminars, industry events and platforms that connect the textile business community.",
                  icon: CalendarDays,
              },
              {
                  title: "Research, Education & Knowledge",
                  description:
                      "Universities, research institutions, training organizations, industry experts and knowledge providers.",
                  icon: GraduationCap,
              },
          ]
        : [
              {
                  title: "Testing, Certification & Compliance",
                  description:
                      "Laboratorium testing, lembaga sertifikasi, compliance, sustainability, quality assurance, dan layanan verifikasi teknis.",
                  icon: FlaskConical,
              },
              {
                  title: "Technology & Digital Transformation",
                  description:
                      "AI, software, ERP, PLM, automation, transformasi digital, digital printing, dan teknologi masa depan industri tekstil.",
                  icon: Lightbulb,
              },
              {
                  title: "Industrial Machinery & Manufacturing",
                  description:
                      "Teknologi spinning, weaving, knitting, dyeing, finishing, garment, printing, automation, dan teknologi manufaktur lainnya.",
                  icon: Factory,
              },
              {
                  title: "Raw Materials & Textile Inputs",
                  description:
                      "Serat, benang, kain, dyes, chemicals, auxiliaries, dan berbagai material pendukung rantai nilai tekstil.",
                  icon: Sparkles,
              },
              {
                  title: "Energy, Utilities & Sustainability",
                  description:
                      "Energi, water, waste management, renewable solutions, efisiensi sumber daya, dan teknologi manufaktur berkelanjutan.",
                  icon: Zap,
              },
              {
                  title: "Logistics & Supply Chain",
                  description:
                      "Freight, pergudangan, logistik, customs, supply chain management, dan solusi fasilitasi perdagangan.",
                  icon: Truck,
              },
              {
                  title: "Trade Finance & Insurance",
                  description:
                      "Trade finance, banking, insurance, payment solutions, dan layanan keuangan yang mendukung bisnis internasional.",
                  icon: WalletCards,
              },
              {
                  title: "Exhibitions, Events & Industry Platforms",
                  description:
                      "Pameran dagang, exhibitions, konferensi, seminar, industry events, dan platform yang menghubungkan komunitas bisnis tekstil.",
                  icon: CalendarDays,
              },
              {
                  title: "Research, Education & Knowledge",
                  description:
                      "Universitas, lembaga riset, organisasi training, industry experts, dan knowledge providers.",
                  icon: GraduationCap,
              },
          ];

    const benefits = isEn
        ? [
              {
                  icon: Megaphone,
                  title: "Industry Visibility",
                  description:
                      "Increase your visibility across a connected textile industry ecosystem and become easier for relevant companies and decision makers to discover.",
              },
              {
                  icon: Lightbulb,
                  title: "Thought Leadership",
                  description:
                      "Share your expertise, technology, innovation, and industry knowledge with companies and decision makers facing real industry challenges.",
              },
              {
                  icon: Handshake,
                  title: "Business Opportunities",
                  description:
                      "Create opportunities to connect your solutions with manufacturers, buyers, suppliers, brands, and other relevant industry stakeholders.",
              },
              {
                  icon: Target,
                  title: "Strategic Positioning",
                  description:
                      "Establish your organization as a relevant solution provider within the broader textile industry ecosystem, not simply as a standalone vendor.",
              },
          ]
        : [
              {
                  icon: Megaphone,
                  title: "Industry Visibility",
                  description:
                      "Meningkatkan visibilitas perusahaan dalam ekosistem industri tekstil yang terhubung dan membuat solusi Anda lebih mudah ditemukan oleh perusahaan serta decision maker yang relevan.",
              },
              {
                  icon: Lightbulb,
                  title: "Thought Leadership",
                  description:
                      "Membagikan keahlian, teknologi, inovasi, dan pengetahuan industri kepada perusahaan serta decision maker yang menghadapi kebutuhan nyata industri.",
              },
              {
                  icon: Handshake,
                  title: "Business Opportunities",
                  description:
                      "Membuka peluang untuk menghubungkan solusi perusahaan dengan manufacturer, buyer, supplier, brand, dan stakeholder industri lainnya yang relevan.",
              },
              {
                  icon: Target,
                  title: "Strategic Positioning",
                  description:
                      "Membangun posisi perusahaan sebagai solution provider yang relevan dalam ekosistem industri tekstil, bukan sekadar sebagai vendor yang berdiri sendiri.",
              },
              {
                  icon: BarChart3,
                  title: "Industry Intelligence",
                  description:
                      "Memperkuat posisi organisasi dalam ekosistem industri yang semakin didorong oleh structured industry data, market intelligence, dan digital discovery.",
              },
              {
                  icon: Network,
                  title: "Ecosystem Influence",
                  description:
                      "Berpartisipasi dalam pengembangan platform industri terhubung yang dirancang untuk mempertemukan kapabilitas, solusi, dan peluang bisnis.",
              },
          ];

    const partnerFeatures = isEn
        ? [
              "Strategic Solution Partner positioning",
              "Dedicated Solution / Company Profile",
              "Industry Solutions Directory presence",
              "Enhanced ecosystem visibility",
              "Thought Leadership opportunities",
              "Partner Insights & Industry Content",
              "Technology & Solution Showcase",
              "Industry campaign opportunities",
              "Business connectivity opportunities",
              "Strategic introductions where relevant",
              "Participation in selected DIGESTEX initiatives",
              "Long-term ecosystem partnership opportunities",
          ]
        : [
              "Positioning sebagai Strategic Solution Partner",
              "Dedicated Solution / Company Profile",
              "Kehadiran dalam Industry Solutions Directory",
              "Enhanced ecosystem visibility",
              "Kesempatan Thought Leadership",
              "Partner Insights & Industry Content",
              "Technology & Solution Showcase",
              "Kesempatan industry campaign",
              "Peluang business connectivity",
              "Strategic introductions apabila relevan",
              "Partisipasi dalam inisiatif DIGESTEX tertentu",
              "Peluang kemitraan ekosistem jangka panjang",
          ];

    const solutionExamples = isEn
        ? [
              {
                  title: "Technology & Digital Transformation",
                  text: "AI, ERP, PLM, automation, digital printing, software and other technologies helping textile companies become more productive, connected and digitally ready.",
              },
              {
                  title: "Testing, Certification & Compliance",
                  text: "Testing laboratories, certification bodies, sustainability, quality assurance and compliance solutions supporting trusted international business.",
              },
              {
                  title: "Machinery & Manufacturing Solutions",
                  text: "Spinning, weaving, knitting, dyeing, finishing, garment, printing, automation and other technologies improving manufacturing performance.",
              },
              {
                  title: "Energy, Sustainability & Circularity",
                  text: "Energy efficiency, renewable energy, water, waste, recycling, circularity and sustainability solutions for the future textile industry.",
              },
              {
                  title: "Logistics, Trade & Finance",
                  text: "Logistics, supply chain, trade finance, insurance and other services enabling more efficient international textile business.",
              },
              {
                  title: "Industry Knowledge & Market Development",
                  text: "Research, education, exhibitions, events, consulting and knowledge platforms that strengthen industry capability and market connectivity.",
              },
          ]
        : [
              {
                  title: "Technology & Digital Transformation",
                  text: "AI, ERP, PLM, automation, digital printing, software, dan teknologi lainnya yang membantu perusahaan tekstil menjadi lebih produktif, terhubung, dan siap menghadapi era digital.",
              },
              {
                  title: "Testing, Certification & Compliance",
                  text: "Laboratorium testing, lembaga sertifikasi, sustainability, quality assurance, dan compliance yang mendukung bisnis internasional yang terpercaya.",
              },
              {
                  title: "Machinery & Manufacturing Solutions",
                  text: "Spinning, weaving, knitting, dyeing, finishing, garment, printing, automation, dan teknologi lainnya yang meningkatkan kinerja manufaktur.",
              },
              {
                  title: "Energy, Sustainability & Circularity",
                  text: "Energy efficiency, renewable energy, water, waste, recycling, circularity, dan sustainability solutions untuk masa depan industri tekstil.",
              },
              {
                  title: "Logistics, Trade & Finance",
                  text: "Logistik, supply chain, trade finance, insurance, dan layanan lainnya yang mendukung bisnis tekstil internasional secara lebih efisien.",
              },
              {
                  title: "Industry Knowledge & Market Development",
                  text: "Riset, pendidikan, exhibition, event, consulting, dan knowledge platform yang memperkuat kapabilitas industri dan konektivitas pasar.",
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
                                        ? "POSITION YOUR SOLUTION IN THE TEXTILE INDUSTRY ECOSYSTEM"
                                        : "POSISIKAN SOLUSI ANDA DALAM EKOSISTEM INDUSTRI TEKSTIL"}
                                </h1>

                                <p className="mt-8 max-w-3xl text-lg leading-8 text-slate-300 lg:text-xl">
                                    {isEn
                                        ? "Become a DIGESTEX Strategic Solution Partner and position your technology, expertise and solutions within a connected, one-stop textile industry ecosystem — connecting the industry from upstream to downstream."
                                        : "Jadilah Strategic Solution Partner DIGESTEX dan posisikan teknologi, expertise, serta solusi perusahaan Anda dalam one-stop ecosystem industri tekstil yang terhubung — menghubungkan industri dari hulu hingga hilir."}
                                </p>

                                <div className="mt-10 flex flex-wrap gap-4">
                                    <Link
                                        href={route(
                                            "strategic-partnership.create",
                                        )}
                                        className="inline-flex items-center gap-3 rounded-2xl bg-amber-400 px-7 py-4 text-sm font-black uppercase tracking-wide text-slate-950 transition hover:bg-amber-300"
                                    >
                                        {isEn
                                            ? "BECOME A STRATEGIC SOLUTION PARTNER"
                                            : "JADI STRATEGIC SOLUTION PARTNER"}

                                        <ArrowRight className="h-5 w-5" />
                                    </Link>
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
                                            ? "DIGESTEX Strategic Solution Partners are not simply listed as advertisers. Their solutions become part of the industry ecosystem — positioned within relevant industry needs, explained through solution intelligence and made discoverable to professionals and decision makers across the textile value chain"
                                            : "DIGESTEX Strategic Solution Partner bukan sekadar tampil sebagai advertiser. Solusi mereka menjadi bagian dari ekosistem industri — ditempatkan berdasarkan kebutuhan industri yang relevan, dijelaskan melalui solution intelligence dan dapat ditemukan oleh professionals serta decision makers di sepanjang rantai nilai industri tekstil"}
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
                            <div className="mb-12 max-w-4xl">
                                <span className="text-xs font-black uppercase tracking-[0.3em] text-amber-400">
                                    {isEn
                                        ? "SOLUTION ECOSYSTEM"
                                        : "EKOSISTEM SOLUSI"}
                                </span>

                                <h2 className="mt-4 text-4xl font-black uppercase tracking-tight">
                                    {isEn
                                        ? "Connect Your Solution With Industry Needs"
                                        : "Hubungkan Solusi Anda Dengan Kebutuhan Industri"}
                                </h2>

                                <p className="mt-5 text-lg leading-8 text-slate-400">
                                    {isEn
                                        ? "DIGESTEX connects relevant technologies, expertise, services, and industrial solutions with companies and decision makers across the textile value chain — from raw materials and manufacturing to trade, sustainability, digital transformation, and supporting industries."
                                        : "DIGESTEX menghubungkan teknologi, expertise, layanan, dan solusi industri yang relevan dengan perusahaan serta decision maker di seluruh rantai nilai industri tekstil — mulai dari bahan baku dan manufaktur hingga perdagangan, sustainability, transformasi digital, dan supporting industries."}
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
                                        : "NILAI KEMITRAAN STRATEGIS"}
                                </span>

                                <h2 className="mt-4 text-4xl font-black uppercase tracking-tight sm:text-5xl">
                                    {isEn
                                        ? "Become Part of the One-Stop Textile Industry Ecosystem"
                                        : "Menjadi Bagian dari One-Stop Ecosystem Industri Tekstil"}
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
                                        ? "Where Your Expertise Connects With The Industry"
                                        : "Di Mana Keahlian Anda Terhubung Dengan Industri"}
                                </h2>

                                <p className="mx-auto mt-5 max-w-3xl text-base leading-7 text-slate-400">
                                    {isEn
                                        ? "DIGESTEX welcomes solution providers across the textile value chain and supporting industries — creating a connected environment where expertise, technology, services, and industry needs can meet."
                                        : "DIGESTEX membuka ruang bagi solution provider di seluruh rantai nilai industri tekstil dan supporting industries — menciptakan lingkungan yang terhubung tempat keahlian, teknologi, layanan, dan kebutuhan industri dapat dipertemukan."}
                                </p>
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
                                            ? "Turn Your Expertise Into Industry Value"
                                            : "Ubah Keahlian Anda Menjadi Nilai Bagi Industri"}
                                    </h2>

                                    <p className="mt-6 leading-8 text-slate-400">
                                        {isEn
                                            ? "A DIGESTEX Strategic Solution Partner profile goes beyond presenting your company. It gives your technology, expertise, services, and solutions a structured place within the textile industry ecosystem — helping decision makers understand what you solve, how you solve it, and where your solution creates value."
                                            : "Profil DIGESTEX Strategic Solution Partner tidak hanya menampilkan perusahaan Anda. Profil ini memberikan ruang terstruktur bagi teknologi, keahlian, layanan, dan solusi Anda di dalam ekosistem industri tekstil — membantu decision maker memahami kebutuhan yang Anda jawab, bagaimana solusi Anda bekerja, dan di mana solusi tersebut menciptakan nilai."}
                                    </p>

                                    <p className="mt-5 leading-8 text-slate-400">
                                        {isEn
                                            ? "From testing and certification to machinery, digital transformation, logistics, finance, sustainability, and other supporting industries, DIGESTEX connects relevant solutions with the companies and industry needs they are designed to serve."
                                            : "Mulai dari testing dan certification hingga machinery, transformasi digital, logistik, finance, sustainability, dan berbagai supporting industries lainnya, DIGESTEX menghubungkan solusi yang relevan dengan perusahaan serta kebutuhan industri yang ingin dilayani."}
                                    </p>
                                </div>

                                <div className="rounded-3xl border border-white/10 bg-gradient-to-br from-white/10 to-white/5 p-8">
                                    <SolutionFlow
                                        number="01"
                                        title={
                                            isEn
                                                ? "Industry Need"
                                                : "Kebutuhan Industri"
                                        }
                                    />

                                    <SolutionFlow
                                        number="02"
                                        title={
                                            isEn
                                                ? "Your Expertise & Solution"
                                                : "Keahlian & Solusi Anda"
                                        }
                                    />

                                    <SolutionFlow
                                        number="03"
                                        title={
                                            isEn
                                                ? "Business & Industry Value"
                                                : "Nilai Bisnis & Industri"
                                        }
                                    />

                                    <SolutionFlow
                                        number="04"
                                        title={
                                            isEn
                                                ? "Connection With The Ecosystem"
                                                : "Koneksi Dengan Ekosistem"
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
                                                    ? "STRATEGIC SOLUTION PARTNER"
                                                    : "STRATEGIC SOLUTION PARTNER"}
                                            </span>

                                            <h2 className="mt-6 text-3xl font-black uppercase sm:text-4xl">
                                                {isEn
                                                    ? "Become Part of the Connected Textile Industry Ecosystem"
                                                    : "Menjadi Bagian dari Ekosistem Industri Tekstil yang Terhubung"}
                                            </h2>

                                            <p className="mt-4 max-w-2xl text-lg leading-7 text-slate-400">
                                                {isEn
                                                    ? "Designed for established corporations, technology companies, solution providers and industry companies whose expertise can create value across the textile value chain."
                                                    : "Dirancang untuk perusahaan besar, perusahaan teknologi, solution provider, dan perusahaan industri yang keahliannya dapat menciptakan nilai di seluruh rantai nilai industri tekstil."}{" "}
                                            </p>
                                        </div>

                                        <div className="shrink-0 sm:text-right">
                                            <p className="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">
                                                {isEn
                                                    ? "ANNUAL PARTNERSHIP"
                                                    : "KEMITRAAN TAHUNAN"}
                                            </p>

                                            <p className="mt-2 text-4xl font-black text-white">
                                                US$12,000
                                            </p>

                                            <p className="text-sm text-slate-500">
                                                {isEn ? "/ year" : "/ tahun"}
                                            </p>
                                        </div>
                                    </div>

                                    <div className="mt-8">
                                        <p className="max-w-3xl text-sm leading-7 text-slate-300">
                                            {isEn
                                                ? "Your company is positioned not simply as a sponsor, but as a Strategic Solution Partner within the DIGESTEX ecosystem — where technologies, expertise, services and solutions can be discovered by companies and decision makers with relevant industry needs."
                                                : "Perusahaan Anda diposisikan bukan sekadar sebagai sponsor, tetapi sebagai Strategic Solution Partner dalam ekosistem DIGESTEX — tempat teknologi, keahlian, layanan, dan solusi dapat ditemukan oleh perusahaan serta decision maker yang memiliki kebutuhan industri yang relevan."}{" "}
                                        </p>
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
                                                : "DISKUSIKAN KEMITRAAN STRATEGIS"}

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
                                        ? "Why Build With DIGESTEX From The Beginning"
                                        : "Mengapa Membangun Bersama DIGESTEX Sejak Awal"}
                                </h2>

                                <p className="mx-auto mt-6 max-w-3xl leading-7 text-slate-400">
                                    {isEn
                                        ? "Founding Partners have the opportunity to participate in the early development of a connected textile industry ecosystem — helping shape how companies, solutions, intelligence, sourcing and business opportunities connect across the value chain."
                                        : "Founding Partner memiliki kesempatan untuk berpartisipasi dalam tahap awal pembangunan ekosistem industri tekstil yang terhubung — ikut membentuk bagaimana perusahaan, solusi, intelligence, sourcing, dan peluang bisnis saling terhubung di seluruh rantai nilai industri."}
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
                                            ? "Strengthen your company's visibility within the DIGESTEX ecosystem and become easier to discover by relevant companies, decision makers and industry stakeholders."
                                            : "Memperkuat visibilitas perusahaan dalam ekosistem DIGESTEX dan membuat perusahaan lebih mudah ditemukan oleh perusahaan, decision maker, dan stakeholder industri yang relevan."
                                    }
                                />

                                <Benefit
                                    icon={Lightbulb}
                                    title={
                                        isEn
                                            ? "Solution & Expertise Showcase"
                                            : "Showcase Solusi & Keahlian"
                                    }
                                    text={
                                        isEn
                                            ? "Present your technologies, solutions, expertise, capabilities and industry knowledge in a structured environment designed for business discovery."
                                            : "Menampilkan teknologi, solusi, keahlian, kapabilitas, dan pengetahuan industri dalam lingkungan terstruktur yang dirancang untuk business discovery."
                                    }
                                />

                                <Benefit
                                    icon={Handshake}
                                    title={
                                        isEn
                                            ? "Strategic Industry Connectivity"
                                            : "Konektivitas Strategis Industri"
                                    }
                                    text={
                                        isEn
                                            ? "Build relationships with manufacturers, suppliers, buyers, technology companies, solution providers and other relevant industry stakeholders."
                                            : "Membangun hubungan dengan manufacturer, supplier, buyer, perusahaan teknologi, solution provider, dan stakeholder industri lainnya yang relevan."
                                    }
                                />

                                <Benefit
                                    icon={BarChart3}
                                    title={
                                        isEn
                                            ? "Industry Intelligence Engagement"
                                            : "Industry Intelligence Engagement"
                                    }
                                    text={
                                        isEn
                                            ? "Participate in industry knowledge, market insight, technology and ecosystem initiatives that contribute to a more informed textile industry."
                                            : "Berpartisipasi dalam knowledge, market insight, teknologi, dan ecosystem initiatives yang mendukung industri tekstil yang semakin informed."
                                    }
                                />

                                <Benefit
                                    icon={BadgeCheck}
                                    title={
                                        isEn
                                            ? "Founding Ecosystem Recognition"
                                            : "Pengakuan sebagai Founding Ecosystem Partner"
                                    }
                                    text={
                                        isEn
                                            ? "Establish your company as an early strategic partner contributing to the development of the DIGESTEX Global Textile Intelligence Ecosystem."
                                            : "Menempatkan perusahaan sebagai strategic partner sejak tahap awal yang berkontribusi dalam pengembangan DIGESTEX Global Textile Intelligence Ecosystem."
                                    }
                                />

                                <Benefit
                                    icon={Globe2}
                                    title={
                                        isEn
                                            ? "Long-Term Ecosystem Opportunities"
                                            : "Peluang Ekosistem Jangka Panjang"
                                    }
                                    text={
                                        isEn
                                            ? "Explore future opportunities across solutions, sourcing, intelligence, digital visibility, business connectivity and other DIGESTEX ecosystem initiatives."
                                            : "Membuka peluang jangka panjang dalam solutions, sourcing, intelligence, digital visibility, konektivitas bisnis, dan berbagai inisiatif ekosistem DIGESTEX lainnya."
                                    }
                                />
                            </div>
                        </div>
                    </section>

                    {/* =====================================================
    FOUNDING STRATEGIC PARTNERS
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
                                    ? "Building The Ecosystem Together"
                                    : "Membangun Ekosistem Bersama"}
                            </h2>

                            <p className="mx-auto mt-6 max-w-3xl leading-7 text-slate-400">
                                {isEn
                                    ? "DIGESTEX is engaging with leading companies and institutions across the textile value chain — from manufacturers, raw materials and machinery to technology, testing and certification, logistics, finance, exhibitions, research and education."
                                    : "DIGESTEX sedang membangun engagement dengan perusahaan dan institusi terkemuka di seluruh rantai nilai industri tekstil — mulai dari manufacturer, bahan baku, mesin, teknologi, testing dan certification, logistik, finance, pameran, hingga riset dan pendidikan."}
                            </p>

                            <div className="mt-12 rounded-[40px] border border-dashed border-amber-400/20 bg-white/5 p-12 backdrop-blur-xl">
                                <Sparkles className="mx-auto h-10 w-10 text-amber-400" />

                                <p className="mt-5 text-lg font-black uppercase tracking-[0.25em] text-amber-300">
                                    {isEn
                                        ? "FOUNDING PARTNERS — COMING SOON"
                                        : "FOUNDING PARTNERS — SEGERA HADIR"}
                                </p>

                                <p className="mx-auto mt-4 max-w-xl text-sm leading-7 text-slate-500">
                                    {isEn
                                        ? "Founding Partner announcements will be published as strategic partnerships are formally established."
                                        : "Pengumuman Founding Partner akan dipublikasikan setelah strategic partnership secara resmi terbentuk."}
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
                                {isEn
                                    ? "BUILD THE ECOSYSTEM WITH DIGESTEX"
                                    : "BANGUN EKOSISTEM BERSAMA DIGESTEX"}
                            </span>

                            <h2 className="mt-5 text-4xl font-black uppercase tracking-tight sm:text-5xl lg:text-6xl">
                                {isEn ? (
                                    <>
                                        Your Solution.
                                        <br />
                                        Your Expertise.
                                        <br />
                                        Your Place In The Ecosystem.
                                    </>
                                ) : (
                                    <>
                                        Solusi Anda.
                                        <br />
                                        Keahlian Anda.
                                        <br />
                                        Tempat Anda Dalam Ekosistem.
                                    </>
                                )}
                            </h2>

                            <p className="mx-auto mt-6 max-w-3xl text-lg leading-8 text-slate-400">
                                {isEn
                                    ? "Join DIGESTEX as a Strategic Solution Partner and bring your technology, expertise, capabilities and solutions into a connected One-Stop Textile Industry Ecosystem — connecting industry needs with relevant solutions, intelligence and business opportunities."
                                    : "Bergabunglah sebagai Strategic Solution Partner DIGESTEX dan hadirkan teknologi, keahlian, kapabilitas, serta solusi perusahaan Anda ke dalam One-Stop Textile Industry Ecosystem yang terhubung — mempertemukan kebutuhan industri dengan solusi, intelligence, dan peluang bisnis yang relevan."}
                            </p>

                            <p className="mx-auto mt-5 max-w-2xl text-base leading-7 text-slate-500">
                                {isEn
                                    ? "This is more than visibility. It is an opportunity to participate in building the digital infrastructure of the next generation textile industry."
                                    : "Ini lebih dari sekadar visibility. Ini adalah kesempatan untuk ikut membangun infrastruktur digital bagi generasi berikutnya industri tekstil."}
                            </p>

                            <div className="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">
                                <Link
                                    href={route("strategic-partnership.create")}
                                    className="inline-flex items-center gap-3 rounded-2xl bg-amber-400 px-8 py-4 text-sm font-black uppercase tracking-wide text-slate-950 transition hover:bg-amber-300"
                                >
                                    {isEn
                                        ? "BECOME A STRATEGIC SOLUTION PARTNER"
                                        : "JADI STRATEGIC SOLUTION PARTNER"}

                                    <ArrowRight className="h-5 w-5" />
                                </Link>
                            </div>

                            <p className="mt-6 text-xs font-bold uppercase tracking-[0.2em] text-slate-600">
                                {isEn
                                    ? "Founding-Stage Strategic Partnership Opportunities"
                                    : "Peluang Strategic Partnership Tahap Awal"}
                            </p>
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
