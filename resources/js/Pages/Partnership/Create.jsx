import ProgramNavbar from "@/Components/Program/ProgramNavbar";
import StickyWhatsAppButton from "@/Components/Program/StickyWhatsAppButton";

import { Link, useForm, usePage } from "@inertiajs/react";

import {
    ArrowLeft,
    ArrowRight,
    Building2,
    CheckCircle2,
    Globe2,
    Handshake,
    Mail,
    MessageSquare,
    Phone,
    Send,
    Sparkles,
    UserRound,
} from "lucide-react";

export default function Create() {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const { data, setData, post, processing, errors } = useForm({
        company_name: "",
        website_url: "",
        contact_name: "",
        job_title: "",
        email: "",
        phone: "",
        partner_category: "",
        solution_description: "",
        partnership_interest: "",
        target_market: "",
        proposed_value: "",
    });

    const categories = isEn
        ? [
              {
                  value: "machinery",
                  label: "Textile & Garment Machinery",
              },
              {
                  value: "testing_certification",
                  label: "Testing & Certification",
              },
              {
                  value: "energy",
                  label: "Energy & Utilities",
              },
              {
                  value: "logistics",
                  label: "Logistics & Supply Chain",
              },
              {
                  value: "erp_plm",
                  label: "ERP & PLM",
              },
              {
                  value: "ai_digital",
                  label: "AI & Digital Transformation",
              },
              {
                  value: "digital_printing",
                  label: "Digital Textile Printing",
              },
              {
                  value: "sustainability",
                  label: "Sustainability & Circularity",
              },
              {
                  value: "raw_material",
                  label: "Raw Materials & Textile Chemicals",
              },
              {
                  value: "finance",
                  label: "Trade Finance & Insurance",
              },
              {
                  value: "association",
                  label: "Exhibition & Event Organizers",
              },
              {
                  value: "institution",
                  label: "Industry Research & Education",
              },
          ]
        : [
              {
                  value: "machinery",
                  label: "Mesin Tekstil & Garmen",
              },
              {
                  value: "testing_certification",
                  label: "Testing & Certification",
              },
              {
                  value: "energy",
                  label: "Energi & Utilities",
              },
              {
                  value: "logistics",
                  label: "Logistik & Supply Chain",
              },
              {
                  value: "erp_plm",
                  label: "ERP & PLM",
              },
              {
                  value: "ai_digital",
                  label: "AI & Transformasi Digital",
              },
              {
                  value: "digital_printing",
                  label: "Digital Textile Printing",
              },
              {
                  value: "sustainability",
                  label: "Sustainability & Circularity",
              },
              {
                  value: "raw_material",
                  label: "Bahan Baku & Bahan Kimia Tekstil",
              },
              {
                  value: "finance",
                  label: "Trade Finance & Insurance",
              },
              {
                  value: "association",
                  label: "Penyelenggara Pameran & Event",
              },
              {
                  value: "institution",
                  label: "Riset & Pendidikan Industri",
              },
          ];

    const submit = (e) => {
        e.preventDefault();

        post(route("strategic-partnership.store"));
    };

    return (
        <div className="min-h-screen bg-slate-50">
            <ProgramNavbar />

            {/* ==========================================================
    HERO
========================================================== */}

            <section className="relative overflow-hidden bg-slate-950 text-white">
                <div className="absolute -right-40 -top-40 h-[500px] w-[500px] rounded-full bg-indigo-500/20 blur-3xl" />

                <div className="absolute -bottom-40 left-1/4 h-[450px] w-[450px] rounded-full bg-emerald-500/10 blur-3xl" />

                <div className="relative mx-auto max-w-7xl px-6 py-16 lg:px-8 lg:py-20">
                    <div className="grid gap-12 lg:grid-cols-[1fr_0.65fr] lg:items-center">
                        <div>
                            <div className="inline-flex items-center gap-2 rounded-full border border-amber-400/30 bg-amber-400/10 px-4 py-2 text-xs font-black uppercase tracking-[0.18em] text-amber-300">
                                <Sparkles className="h-4 w-4" />

                                {isEn
                                    ? "FOUNDING STRATEGIC SOLUTION PARTNER"
                                    : "FOUNDING STRATEGIC SOLUTION PARTNER"}
                            </div>

                            <h1 className="mt-6 max-w-3xl text-4xl font-black leading-tight sm:text-5xl lg:text-6xl">
                                {isEn ? (
                                    <>
                                        Build Your Place
                                        <br />
                                        in the Next Generation
                                        <br />
                                        Textile Ecosystem
                                    </>
                                ) : (
                                    <>
                                        Bangun Posisi Anda
                                        <br />
                                        dalam Generasi Berikutnya
                                        <br />
                                        Ekosistem Industri Tekstil
                                    </>
                                )}
                            </h1>

                            <p className="mt-6 max-w-2xl text-base leading-7 text-slate-300 sm:text-lg">
                                {isEn
                                    ? "DIGESTEX is building a connected One-Stop Textile Industry Ecosystem — bringing together companies, technologies, solutions, intelligence, sourcing and business opportunities across the textile value chain."
                                    : "DIGESTEX sedang membangun One-Stop Textile Industry Ecosystem yang terhubung — mempertemukan perusahaan, teknologi, solusi, intelligence, sourcing, dan peluang bisnis di seluruh rantai nilai industri tekstil."}
                            </p>

                            <p className="mt-5 max-w-2xl text-base leading-7 text-slate-400">
                                {isEn
                                    ? "Tell us about your company, capabilities and solutions. Explore how your business can become part of the ecosystem and contribute to the digital infrastructure shaping the next generation of textile industry connectivity."
                                    : "Ceritakan tentang perusahaan, kapabilitas, dan solusi Anda. Mari eksplorasi bagaimana perusahaan Anda dapat menjadi bagian dari ekosistem dan berkontribusi dalam membangun infrastruktur digital bagi konektivitas industri tekstil generasi berikutnya."}
                            </p>

                            <div className="mt-8 flex flex-wrap gap-3">
                                {(isEn
                                    ? [
                                          "Industry Visibility",
                                          "Solution Showcase",
                                          "Strategic Positioning",
                                          "Business Connectivity",
                                          "Ecosystem Participation",
                                      ]
                                    : [
                                          "Industry Visibility",
                                          "Solution Showcase",
                                          "Strategic Positioning",
                                          "Konektivitas Bisnis",
                                          "Partisipasi Ekosistem",
                                      ]
                                ).map((item) => (
                                    <span
                                        key={item}
                                        className="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-slate-200"
                                    >
                                        <CheckCircle2 className="h-4 w-4 text-emerald-400" />

                                        {item}
                                    </span>
                                ))}
                            </div>
                        </div>

                        {/* Digital Globe */}

                        <div className="relative flex min-h-[300px] items-center justify-center">
                            <div className="absolute h-72 w-72 rounded-full bg-cyan-400/10 blur-3xl" />

                            <div className="absolute h-[330px] w-[330px] rounded-full border border-indigo-400/10" />

                            <div className="absolute h-[260px] w-[260px] rounded-full border border-emerald-400/10" />

                            <img
                                src="/images/digestex/digital-globe.png"
                                alt="DIGESTEX Global Textile Intelligence Ecosystem"
                                className="
                        relative
                        z-10
                        h-80
                        w-80
                        object-contain
                        drop-shadow-[0_0_45px_rgba(56,189,248,0.30)]
                    "
                            />
                        </div>
                    </div>
                </div>
            </section>

            {/* ==========================================================
                MAIN FORM
            ========================================================== */}

            <main className="mx-auto max-w-6xl px-6 py-12 lg:px-8 lg:py-16">
                <form onSubmit={submit}>
                    <div className="grid gap-8 lg:grid-cols-[1fr_0.35fr]">
                        {/* ==================================================
                            FORM
                        ================================================== */}

                        <div className="space-y-8">
                            {/* Company Information */}

                            <section className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                                <SectionHeader
                                    icon={Building2}
                                    number="01"
                                    title={
                                        isEn
                                            ? "Company Information"
                                            : "Informasi Perusahaan"
                                    }
                                    description={
                                        isEn
                                            ? "Tell us about the company you represent and its role within the textile industry ecosystem."
                                            : "Informasikan perusahaan yang Anda wakili dan perannya dalam ekosistem industri tekstil."
                                    }
                                />

                                <div className="mt-8 grid gap-6 sm:grid-cols-2">
                                    <Field
                                        label={
                                            isEn
                                                ? "Company Name"
                                                : "Nama Perusahaan"
                                        }
                                        required
                                        value={data.company_name}
                                        onChange={(e) =>
                                            setData(
                                                "company_name",
                                                e.target.value,
                                            )
                                        }
                                        error={errors.company_name}
                                        placeholder={
                                            isEn
                                                ? "Your company name"
                                                : "Nama perusahaan"
                                        }
                                    />

                                    <Field
                                        label="Website"
                                        value={data.website_url}
                                        onChange={(e) =>
                                            setData(
                                                "website_url",
                                                e.target.value,
                                            )
                                        }
                                        error={errors.website_url}
                                        placeholder="https://www.company.com"
                                    />
                                </div>
                            </section>

                            {/* Contact */}

                            <section className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                                <SectionHeader
                                    icon={UserRound}
                                    number="02"
                                    title={
                                        isEn
                                            ? "Executive / Business Contact"
                                            : "Kontak Eksekutif / Bisnis"
                                    }
                                    description={
                                        isEn
                                            ? "Provide the contact person responsible for discussing your company's strategic partnership with DIGESTEX."
                                            : "Berikan informasi kontak yang bertanggung jawab untuk membahas kemitraan strategis perusahaan Anda dengan DIGESTEX."
                                    }
                                />

                                <div className="mt-8 grid gap-6 sm:grid-cols-2">
                                    <Field
                                        label={
                                            isEn
                                                ? "Contact Name"
                                                : "Nama Kontak"
                                        }
                                        required
                                        value={data.contact_name}
                                        onChange={(e) =>
                                            setData(
                                                "contact_name",
                                                e.target.value,
                                            )
                                        }
                                        error={errors.contact_name}
                                    />

                                    <Field
                                        label={isEn ? "Job Title" : "Jabatan"}
                                        value={data.job_title}
                                        onChange={(e) =>
                                            setData("job_title", e.target.value)
                                        }
                                        error={errors.job_title}
                                        placeholder={
                                            isEn
                                                ? "Managing Director / Director / Business Development"
                                                : "Managing Director / Director / Business Development"
                                        }
                                    />

                                    <Field
                                        label={
                                            isEn
                                                ? "Business Email"
                                                : "Email Bisnis"
                                        }
                                        required
                                        type="email"
                                        value={data.email}
                                        onChange={(e) =>
                                            setData("email", e.target.value)
                                        }
                                        error={errors.email}
                                    />

                                    <Field
                                        label={
                                            isEn
                                                ? "Phone / WhatsApp"
                                                : "Telepon / WhatsApp"
                                        }
                                        value={data.phone}
                                        onChange={(e) =>
                                            setData("phone", e.target.value)
                                        }
                                        error={errors.phone}
                                    />
                                </div>
                            </section>

                            {/* Solution & Industry Value */}

                            <section className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                                <SectionHeader
                                    icon={LightbulbIcon}
                                    number="03"
                                    title={
                                        isEn
                                            ? "Solution & Industry Value"
                                            : "Solusi & Nilai untuk Industri"
                                    }
                                    description={
                                        isEn
                                            ? "Tell us about the solutions, capabilities and expertise your company can bring to the DIGESTEX textile industry ecosystem."
                                            : "Jelaskan solusi, kapabilitas, dan keahlian yang dapat perusahaan Anda hadirkan ke dalam ekosistem industri tekstil DIGESTEX."
                                    }
                                />

                                <div className="mt-8 space-y-6">
                                    <div>
                                        <label className="text-sm font-bold text-slate-800">
                                            {isEn
                                                ? "Strategic Solution Category"
                                                : "Kategori Solusi Strategis"}

                                            <span className="ml-1 text-rose-500">
                                                *
                                            </span>
                                        </label>

                                        <select
                                            value={data.partner_category}
                                            onChange={(e) =>
                                                setData(
                                                    "partner_category",
                                                    e.target.value,
                                                )
                                            }
                                            className="
                    mt-2
                    w-full
                    rounded-2xl
                    border
                    border-slate-300
                    bg-white
                    px-4
                    py-3.5
                    text-sm
                    outline-none
                    transition
                    focus:border-emerald-500
                    focus:ring-2
                    focus:ring-emerald-500/10
                "
                                        >
                                            <option value="">
                                                {isEn
                                                    ? "Select a category"
                                                    : "Pilih kategori"}
                                            </option>

                                            {categories.map((category) => (
                                                <option
                                                    key={`${category.value}-${category.label}`}
                                                    value={category.value}
                                                >
                                                    {category.label}
                                                </option>
                                            ))}
                                        </select>

                                        {errors.partner_category && (
                                            <ErrorMessage
                                                message={
                                                    errors.partner_category
                                                }
                                            />
                                        )}
                                    </div>

                                    <TextArea
                                        label={
                                            isEn
                                                ? "Describe Your Solution & Industry Value"
                                                : "Jelaskan Solusi & Nilai untuk Industri"
                                        }
                                        required
                                        rows={7}
                                        value={data.solution_description}
                                        onChange={(e) =>
                                            setData(
                                                "solution_description",
                                                e.target.value,
                                            )
                                        }
                                        error={errors.solution_description}
                                        placeholder={
                                            isEn
                                                ? "Describe your products, technologies, services, expertise or capabilities, the industry challenges you address, and the value your company can bring to textile companies and decision makers..."
                                                : "Jelaskan produk, teknologi, layanan, keahlian atau kapabilitas perusahaan Anda, tantangan industri yang dapat Anda jawab, serta nilai yang dapat diberikan kepada perusahaan tekstil dan decision maker..."
                                        }
                                    />
                                </div>
                            </section>
                            {/* Partnership Opportunity */}

                            <section className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                                <SectionHeader
                                    icon={Handshake}
                                    number="04"
                                    title={
                                        isEn
                                            ? "Strategic Partnership Opportunity"
                                            : "Peluang Kemitraan Strategis"
                                    }
                                    description={
                                        isEn
                                            ? "Tell us how your company would like to participate, collaborate and create value within the DIGESTEX ecosystem."
                                            : "Jelaskan bagaimana perusahaan Anda ingin berpartisipasi, berkolaborasi, dan menciptakan nilai di dalam ekosistem DIGESTEX."
                                    }
                                />

                                <div className="mt-8 space-y-6">
                                    <TextArea
                                        label={
                                            isEn
                                                ? "Partnership Interest"
                                                : "Minat Kemitraan"
                                        }
                                        rows={5}
                                        value={data.partnership_interest}
                                        onChange={(e) =>
                                            setData(
                                                "partnership_interest",
                                                e.target.value,
                                            )
                                        }
                                        error={errors.partnership_interest}
                                        placeholder={
                                            isEn
                                                ? "What type of collaboration would you like to explore with DIGESTEX? For example: solution showcase, industry programs, knowledge initiatives, business connectivity, strategic campaigns, or other opportunities."
                                                : "Bentuk kolaborasi apa yang ingin Anda kembangkan bersama DIGESTEX? Misalnya: solution showcase, program industri, knowledge initiatives, business connectivity, strategic campaign, atau peluang lainnya."
                                        }
                                    />

                                    <TextArea
                                        label={
                                            isEn
                                                ? "Target Market & Industry Segment"
                                                : "Target Pasar & Segmen Industri"
                                        }
                                        rows={4}
                                        value={data.target_market}
                                        onChange={(e) =>
                                            setData(
                                                "target_market",
                                                e.target.value,
                                            )
                                        }
                                        error={errors.target_market}
                                        placeholder={
                                            isEn
                                                ? "Which markets, countries, textile segments or types of companies would you like to reach?"
                                                : "Pasar, negara, segmen tekstil, atau jenis perusahaan apa yang ingin Anda jangkau?"
                                        }
                                    />

                                    <TextArea
                                        label={
                                            isEn
                                                ? "Proposed Strategic Value"
                                                : "Nilai Strategis yang Ditawarkan"
                                        }
                                        rows={5}
                                        value={data.proposed_value}
                                        onChange={(e) =>
                                            setData(
                                                "proposed_value",
                                                e.target.value,
                                            )
                                        }
                                        error={errors.proposed_value}
                                        placeholder={
                                            isEn
                                                ? "How can your company contribute to the DIGESTEX ecosystem through your expertise, technology, solutions, industry knowledge, network or other capabilities?"
                                                : "Bagaimana perusahaan Anda dapat berkontribusi pada ekosistem DIGESTEX melalui expertise, teknologi, solusi, industry knowledge, jaringan, atau kapabilitas lainnya?"
                                        }
                                    />
                                </div>
                            </section>

                            {/* Submit */}

                            <section className="rounded-3xl bg-slate-950 p-6 text-white shadow-xl sm:p-8">
                                <div className="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <h2 className="text-xl font-black">
                                            {isEn
                                                ? "Ready to Explore a Strategic Partnership?"
                                                : "Siap Mengembangkan Kemitraan Strategis?"}
                                        </h2>

                                        <p className="mt-2 max-w-xl text-sm leading-6 text-slate-400">
                                            {isEn
                                                ? "Submit your partnership proposal and our team will review your company, solution and objectives before discussing the next steps."
                                                : "Kirim proposal kemitraan Anda dan tim kami akan meninjau perusahaan, solusi, dan tujuan Anda sebelum mendiskusikan langkah selanjutnya."}
                                        </p>
                                    </div>

                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="
                inline-flex
                shrink-0
                items-center
                justify-center
                gap-2
                rounded-2xl
                bg-amber-400
                px-7
                py-4
                font-black
                text-slate-950
                transition
                hover:bg-amber-300
                disabled:cursor-not-allowed
                disabled:opacity-60
            "
                                    >
                                        {processing
                                            ? isEn
                                                ? "SUBMITTING..."
                                                : "MENGIRIM..."
                                            : isEn
                                              ? "SUBMIT PARTNERSHIP PROPOSAL"
                                              : "KIRIM PERMOHONAN KEMITRAAN"}

                                        <Send className="h-5 w-5" />
                                    </button>
                                </div>
                            </section>
                        </div>

                        {/* ==================================================
    SIDE INFORMATION
================================================== */}

                        <aside className="space-y-6">
                            <div className="sticky top-6 space-y-6">
                                <div className="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                                    <div className="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50">
                                        <Globe2 className="h-6 w-6 text-emerald-600" />
                                    </div>

                                    <h3 className="mt-5 text-xl font-black text-slate-900">
                                        {isEn
                                            ? "Why Partner with DIGESTEX?"
                                            : "Mengapa Bermitra dengan DIGESTEX?"}
                                    </h3>

                                    <p className="mt-3 text-sm leading-6 text-slate-500">
                                        {isEn
                                            ? "Become part of a connected textile industry ecosystem designed to make companies, capabilities, solutions and opportunities easier to discover and connect."
                                            : "Menjadi bagian dari ekosistem industri tekstil yang terhubung untuk membuat perusahaan, kapabilitas, solusi, dan peluang bisnis lebih mudah ditemukan dan dihubungkan."}
                                    </p>

                                    <div className="mt-6 space-y-4">
                                        <SidePoint
                                            title={
                                                isEn
                                                    ? "Industry Visibility"
                                                    : "Industry Visibility"
                                            }
                                            text={
                                                isEn
                                                    ? "Strengthen your company's presence across the DIGESTEX textile industry ecosystem."
                                                    : "Memperkuat kehadiran perusahaan Anda di dalam ekosistem industri tekstil DIGESTEX."
                                            }
                                        />

                                        <SidePoint
                                            title={
                                                isEn
                                                    ? "Digital Discoverability"
                                                    : "Digital Discoverability"
                                            }
                                            text={
                                                isEn
                                                    ? "Make your company, capabilities and solutions easier for relevant industry audiences to discover and understand."
                                                    : "Membuat perusahaan, kapabilitas, dan solusi Anda lebih mudah ditemukan dan dipahami oleh audiens industri yang relevan."
                                            }
                                        />

                                        <SidePoint
                                            title={
                                                isEn
                                                    ? "Solution Positioning"
                                                    : "Solution Positioning"
                                            }
                                            text={
                                                isEn
                                                    ? "Showcase your technology, expertise, products and services in the context of real industry needs."
                                                    : "Menampilkan teknologi, keahlian, produk, dan layanan Anda dalam konteks kebutuhan nyata industri."
                                            }
                                        />

                                        <SidePoint
                                            title={
                                                isEn
                                                    ? "Industry Connectivity"
                                                    : "Konektivitas Industri"
                                            }
                                            text={
                                                isEn
                                                    ? "Connect with manufacturers, buyers, suppliers, decision makers and other relevant ecosystem participants."
                                                    : "Terhubung dengan manufacturer, buyer, supplier, decision maker, dan peserta ekosistem lainnya yang relevan."
                                            }
                                        />

                                        <SidePoint
                                            title={
                                                isEn
                                                    ? "Business Opportunities"
                                                    : "Peluang Bisnis"
                                            }
                                            text={
                                                isEn
                                                    ? "Explore collaboration, sourcing, market development and other relevant business opportunities."
                                                    : "Mengeksplorasi peluang kolaborasi, sourcing, pengembangan pasar, dan peluang bisnis lainnya yang relevan."
                                            }
                                        />
                                    </div>
                                </div>

                                <div className="rounded-3xl bg-slate-900 p-6 text-white">
                                    <div className="flex items-center gap-3">
                                        <MessageSquare className="h-5 w-5 text-amber-300" />

                                        <span className="text-sm font-black">
                                            {isEn
                                                ? "Prefer a Direct Discussion?"
                                                : "Ingin Berdiskusi Langsung?"}
                                        </span>
                                    </div>

                                    <p className="mt-3 text-sm leading-6 text-slate-400">
                                        {isEn
                                            ? "You can also speak directly with the DIGESTEX team to explore the most relevant partnership opportunity for your company."
                                            : "Anda juga dapat berdiskusi langsung dengan tim DIGESTEX untuk mengeksplorasi bentuk kemitraan yang paling relevan bagi perusahaan Anda."}
                                    </p>

                                    <a
                                        href={`https://wa.me/628129928939?text=${encodeURIComponent(
                                            isEn
                                                ? "Hello DIGESTEX, we would like to discuss a Strategic Solution Partnership."
                                                : "Halo DIGESTEX, kami ingin mendiskusikan Strategic Solution Partnership.",
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
                    bg-emerald-500
                    px-5
                    py-3.5
                    text-sm
                    font-black
                    text-white
                    transition
                    hover:bg-emerald-400
                "
                                    >
                                        <Phone className="h-4 w-4" />

                                        {isEn
                                            ? "CONTACT VIA WHATSAPP"
                                            : "HUBUNGI VIA WHATSAPP"}
                                    </a>
                                </div>

                                <Link
                                    href={route("program.digital-directory")}
                                    className="
                inline-flex
                w-full
                items-center
                justify-center
                gap-2
                rounded-2xl
                border
                border-slate-200
                bg-white
                px-5
                py-3.5
                text-sm
                font-bold
                text-slate-700
                transition
                hover:bg-slate-100
            "
                                >
                                    <ArrowLeft className="h-4 w-4" />

                                    {isEn
                                        ? "BACK TO PROGRAM"
                                        : "KEMBALI KE PROGRAM"}
                                </Link>
                            </div>
                        </aside>
                    </div>
                </form>
            </main>

            <StickyWhatsAppButton
                position="left"
                message={
                    isEn
                        ? "Hello DIGESTEX, we would like to discuss a Strategic Solution Partnership."
                        : "Halo DIGESTEX, kami ingin mendiskusikan Strategic Solution Partnership."
                }
            />
        </div>
    );
}

/* ==========================================================
   SECTION HEADER
========================================================== */

function SectionHeader({ icon: Icon, number, title, description }) {
    return (
        <div className="flex gap-4">
            <div className="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-slate-900 text-white">
                <Icon className="h-5 w-5" />
            </div>

            <div>
                <div className="text-xs font-black uppercase tracking-[0.18em] text-emerald-600">
                    {number}
                </div>

                <h2 className="mt-1 text-xl font-black text-slate-900">
                    {title}
                </h2>

                <p className="mt-1 text-sm leading-6 text-slate-500">
                    {description}
                </p>
            </div>
        </div>
    );
}

/* ==========================================================
   FIELD
========================================================== */

function Field({
    label,
    required = false,
    type = "text",
    value,
    onChange,
    error,
    placeholder,
}) {
    return (
        <div>
            <label className="text-sm font-bold text-slate-800">
                {label}

                {required && <span className="ml-1 text-rose-500">*</span>}
            </label>

            <input
                type={type}
                value={value}
                onChange={onChange}
                placeholder={placeholder}
                className={`
                    mt-2
                    w-full
                    rounded-2xl
                    border
                    bg-white
                    px-4
                    py-3.5
                    text-sm
                    text-slate-900
                    outline-none
                    transition
                    placeholder:text-slate-400
                    focus:ring-2
                    ${
                        error
                            ? "border-rose-400 focus:border-rose-500 focus:ring-rose-500/10"
                            : "border-slate-300 focus:border-emerald-500 focus:ring-emerald-500/10"
                    }
                `}
            />

            {error && <ErrorMessage message={error} />}
        </div>
    );
}

/* ==========================================================
   TEXT AREA
========================================================== */

function TextArea({
    label,
    required = false,
    rows = 5,
    value,
    onChange,
    error,
    placeholder,
}) {
    return (
        <div>
            <label className="text-sm font-bold text-slate-800">
                {label}

                {required && <span className="ml-1 text-rose-500">*</span>}
            </label>

            <textarea
                rows={rows}
                value={value}
                onChange={onChange}
                placeholder={placeholder}
                className={`
                    mt-2
                    w-full
                    resize-none
                    rounded-2xl
                    border
                    bg-white
                    px-4
                    py-3.5
                    text-sm
                    leading-6
                    text-slate-900
                    outline-none
                    transition
                    placeholder:text-slate-400
                    focus:ring-2
                    ${
                        error
                            ? "border-rose-400 focus:border-rose-500 focus:ring-rose-500/10"
                            : "border-slate-300 focus:border-emerald-500 focus:ring-emerald-500/10"
                    }
                `}
            />

            {error && <ErrorMessage message={error} />}
        </div>
    );
}

/* ==========================================================
   SIDE POINT
========================================================== */

function SidePoint({ title, text }) {
    return (
        <div className="border-b border-slate-100 pb-4 last:border-b-0 last:pb-0">
            <div className="flex items-start gap-3">
                <CheckCircle2 className="mt-0.5 h-5 w-5 shrink-0 text-emerald-500" />

                <div>
                    <h4 className="text-sm font-black text-slate-900">
                        {title}
                    </h4>

                    <p className="mt-1 text-xs leading-5 text-slate-500">
                        {text}
                    </p>
                </div>
            </div>
        </div>
    );
}

/* ==========================================================
   ERROR
========================================================== */

function ErrorMessage({ message }) {
    return <p className="mt-2 text-xs font-medium text-rose-600">{message}</p>;
}

/* ==========================================================
   LIGHTBULB ICON
========================================================== */

function LightbulbIcon(props) {
    return <Sparkles {...props} />;
}
