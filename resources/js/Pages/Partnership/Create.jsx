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
                                Strategic Solution Partner
                            </div>

                            <h1 className="mt-6 max-w-3xl text-4xl font-black leading-tight sm:text-5xl lg:text-6xl">
                                {isEn
                                    ? "Let's Build the Future of the Textile Industry Together"
                                    : "Mari Membangun Masa Depan Industri Tekstil Bersama"}
                            </h1>

                            <p className="mt-6 max-w-2xl text-base leading-7 text-slate-300 sm:text-lg">
                                {isEn
                                    ? "Tell us about your company, solution and partnership objectives. Our team will review your proposal and explore how your organization can contribute to the DIGESTEX ecosystem."
                                    : "Ceritakan tentang perusahaan, solusi, dan tujuan kemitraan Anda. Tim kami akan meninjau proposal Anda dan mengeksplorasi bagaimana perusahaan Anda dapat berkontribusi dalam ekosistem DIGESTEX."}
                            </p>

                            <div className="mt-8 flex flex-wrap gap-3">
                                {(isEn
                                    ? [
                                          "Global Industry Visibility",
                                          "Solution Showcase",
                                          "Executive Engagement",
                                          "Business Opportunities",
                                      ]
                                    : [
                                          "Global Industry Visibility",
                                          "Solution Showcase",
                                          "Executive Engagement",
                                          "Peluang Bisnis",
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
                                            ? "Tell us about the organization you represent."
                                            : "Informasikan perusahaan atau organisasi yang Anda wakili."
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
                                            ? "Contact Person"
                                            : "Kontak Perusahaan"
                                    }
                                    description={
                                        isEn
                                            ? "Please provide the executive or business contact responsible for this inquiry."
                                            : "Berikan informasi kontak eksekutif atau PIC yang bertanggung jawab atas inquiry ini."
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
                                                ? "Managing Director / Business Development"
                                                : "Managing Director / Business Development"
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

                            {/* Solution */}

                            <section className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                                <SectionHeader
                                    icon={LightbulbIcon}
                                    number="03"
                                    title={
                                        isEn
                                            ? "Your Solution"
                                            : "Solusi yang Ditawarkan"
                                    }
                                    description={
                                        isEn
                                            ? "Tell us what your company brings to the textile industry ecosystem."
                                            : "Jelaskan solusi yang perusahaan Anda tawarkan untuk ekosistem industri tekstil."
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
                                                ? "Describe Your Solution"
                                                : "Jelaskan Solusi Anda"
                                        }
                                        required
                                        rows={6}
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
                                                ? "Describe your products, technology, services or expertise and how they can support the textile industry..."
                                                : "Jelaskan produk, teknologi, layanan atau keahlian perusahaan Anda dan bagaimana solusi tersebut dapat mendukung industri tekstil..."
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
                                            ? "Partnership Opportunity"
                                            : "Peluang Kemitraan"
                                    }
                                    description={
                                        isEn
                                            ? "Help us understand the type of strategic relationship you would like to explore."
                                            : "Bantu kami memahami bentuk hubungan strategis yang ingin Anda kembangkan."
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
                                                ? "What would you like to explore with DIGESTEX?"
                                                : "Apa yang ingin Anda eksplorasi bersama DIGESTEX?"
                                        }
                                    />

                                    <TextArea
                                        label={
                                            isEn
                                                ? "Target Market"
                                                : "Target Pasar"
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
                                                ? "Indonesia, ASEAN, Asia, Europe, USA, Global, etc."
                                                : "Indonesia, ASEAN, Asia, Eropa, USA, Global, dll."
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
                                                ? "How can your company contribute to the DIGESTEX ecosystem?"
                                                : "Bagaimana perusahaan Anda dapat berkontribusi terhadap ekosistem DIGESTEX?"
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
                                                : "Siap Mendiskusikan Kemitraan Strategis?"}
                                        </h2>

                                        <p className="mt-2 max-w-xl text-sm leading-6 text-slate-400">
                                            {isEn
                                                ? "Submit your inquiry and our team will contact you to discuss the next steps."
                                                : "Kirim inquiry Anda dan tim kami akan menghubungi Anda untuk mendiskusikan langkah selanjutnya."}
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
                                              ? "SUBMIT INQUIRY"
                                              : "KIRIM INQUIRY"}

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

                                    <div className="mt-5 space-y-4">
                                        <SidePoint
                                            title={
                                                isEn
                                                    ? "Industry Visibility"
                                                    : "Industry Visibility"
                                            }
                                            text={
                                                isEn
                                                    ? "Position your solution within a dedicated textile intelligence ecosystem."
                                                    : "Posisikan solusi Anda dalam ekosistem intelligence industri tekstil."
                                            }
                                        />

                                        <SidePoint
                                            title={
                                                isEn
                                                    ? "Executive Engagement"
                                                    : "Executive Engagement"
                                            }
                                            text={
                                                isEn
                                                    ? "Connect with decision makers and industry leaders."
                                                    : "Terhubung dengan pengambil keputusan dan pemimpin industri."
                                            }
                                        />

                                        <SidePoint
                                            title={
                                                isEn
                                                    ? "Solution Showcase"
                                                    : "Solution Showcase"
                                            }
                                            text={
                                                isEn
                                                    ? "Present your technology, expertise and solutions to relevant industry audiences."
                                                    : "Tampilkan teknologi, keahlian, dan solusi kepada audiens industri yang relevan."
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
                                                    ? "Explore opportunities for collaboration, matching and market development."
                                                    : "Eksplorasi peluang kolaborasi, matching, dan pengembangan pasar."
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
                                            ? "You can also contact the DIGESTEX team directly through WhatsApp."
                                            : "Anda juga dapat menghubungi tim DIGESTEX secara langsung melalui WhatsApp."}
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
