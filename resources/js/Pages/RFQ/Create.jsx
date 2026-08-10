import PublicNavbar from "@/Components/Public/PublicNavbar";
import Footer from "@/Components/Footer1";
import { Head, Link, useForm, usePage } from "@inertiajs/react";

import {
    ArrowLeft,
    ArrowRight,
    CalendarDays,
    CheckCircle2,
    FileText,
    Globe2,
    Hash,
    Package,
    Paperclip,
    Send,
    ShieldCheck,
    Sparkles,
    UploadCloud,
} from "lucide-react";

export default function Create({ auth }) {
    const { locale } = usePage().props;
    const isEn = locale === "en";

    const { data, setData, post, processing, errors } = useForm({
        product_name: "",
        hs_code: "",
        description: "",
        required_quantity: "",
        unit: "PCS",
        required_delivery_date: "",
        destination_country: "",
        incoterm: "",
        currency: "USD",
        quotation_deadline: "",
        attachments: [],
    });

    const submit = (e) => {
        e.preventDefault();

        post(route("rfqs.store"));
    };

    const inputClass = `
        w-full
        rounded-xl
        border
        border-slate-200
        bg-white
        px-4
        py-3
        text-sm
        text-slate-900
        outline-none
        transition
        placeholder:text-slate-400
        focus:border-amber-400
        focus:ring-4
        focus:ring-amber-500/10
    `;

    const labelClass = "mb-2 block text-sm font-semibold text-slate-800";

    const errorClass = "mt-1.5 text-xs font-medium text-red-500";

    return (
        <>
            <Head title="Create RFQ | Digestex Sourcing Hub" />

            {/* =====================================================
                GLOBAL DIGESTEX NAVBAR
            ===================================================== */}

            <PublicNavbar />

            {/* =====================================================
                PAGE
            ===================================================== */}

            <div className="min-h-screen bg-slate-50">
                {/* =================================================
                    PAGE HERO
                ================================================= */}

                <section className="relative overflow-hidden bg-slate-950">
                    <div className="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(245,158,11,0.16),transparent_32%),radial-gradient(circle_at_bottom_left,rgba(14,165,233,0.12),transparent_30%)]" />

                    <div className="relative mx-auto max-w-7xl px-6 py-12 lg:py-16">
                        <Link
                            href={route("rfqs.index")}
                            className="mb-7 inline-flex items-center gap-2 text-xs font-semibold text-slate-400 transition hover:text-white"
                        >
                            <ArrowLeft className="h-4 w-4" />
                            Back to RFQ Marketplace
                        </Link>

                        <div className="grid gap-10 lg:grid-cols-[1fr_auto] lg:items-end">
                            <div>
                                <div className="mb-4 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1.5">
                                    <Sparkles className="h-3.5 w-3.5 text-amber-400" />

                                    <span className="text-[10px] font-black uppercase tracking-[0.18em] text-amber-300">
                                        Digestex Global Sourcing
                                    </span>
                                </div>

                                <h1 className="max-w-3xl text-4xl font-black tracking-tight text-white sm:text-5xl">
                                    {isEn ? "Create a " : "Buat "}
                                    <span className="text-amber-400">
                                        {isEn
                                            ? "Sourcing Request"
                                            : "Permintaan Pengadaan"}
                                    </span>
                                </h1>

                                <p className="mt-5 max-w-2xl text-base leading-7 text-slate-300">
                                    {isEn
                                        ? "Tell qualified textile suppliers what you need and invite them to submit competitive quotations through the Digestex sourcing ecosystem."
                                        : "Jelaskan kebutuhan Anda kepada supplier tekstil yang memenuhi syarat dan undang mereka untuk mengirimkan penawaran melalui ekosistem sourcing Digestex."}
                                </p>
                            </div>

                            <div className="hidden lg:block">
                                <div className="rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur">
                                    <div className="flex items-center gap-3">
                                        <ShieldCheck className="h-6 w-6 text-emerald-400" />

                                        <div>
                                            <div className="text-sm font-bold text-white">
                                                Global B2B Sourcing
                                            </div>

                                            <div className="mt-1 text-xs text-slate-400">
                                                Structured RFQ workflow
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {/* =====================================================
                    MAIN CONTENT
                ===================================================== */}

                <main className="mx-auto max-w-7xl px-6 py-10 lg:py-12">
                    <div className="grid gap-8 lg:grid-cols-[minmax(0,1fr)_320px]">
                        {/* =================================================
                            FORM
                        ================================================= */}

                        <form onSubmit={submit} className="space-y-6">
                            {/* Product & Requirement */}
                            <section className="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                                <div className="border-b border-slate-100 px-6 py-5 sm:px-8">
                                    <div className="flex items-start gap-4">
                                        <div className="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-amber-50">
                                            <Package className="h-5 w-5 text-amber-600" />
                                        </div>

                                        <div>
                                            <div className="text-[10px] font-black uppercase tracking-[0.2em] text-amber-600">
                                                01 · Product
                                            </div>

                                            <h2 className="mt-1 text-xl font-black text-slate-900">
                                                Product & Requirement
                                            </h2>

                                            <p className="mt-1 text-sm text-slate-500">
                                                Tell suppliers exactly what
                                                product you are looking for.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div className="grid gap-6 p-6 sm:p-8 md:grid-cols-2">
                                    <div className="md:col-span-2">
                                        <label className={labelClass}>
                                            Product Name
                                        </label>

                                        <input
                                            type="text"
                                            value={data.product_name}
                                            onChange={(e) =>
                                                setData(
                                                    "product_name",
                                                    e.target.value,
                                                )
                                            }
                                            placeholder="e.g. Polyester Yarn 30s"
                                            className={inputClass}
                                        />

                                        {errors.product_name && (
                                            <div className={errorClass}>
                                                {errors.product_name}
                                            </div>
                                        )}
                                    </div>

                                    <div>
                                        <label className={labelClass}>
                                            <span className="flex items-center gap-2">
                                                <Hash className="h-3.5 w-3.5 text-slate-400" />
                                                HS Code
                                            </span>
                                        </label>

                                        <input
                                            type="text"
                                            value={data.hs_code}
                                            onChange={(e) =>
                                                setData(
                                                    "hs_code",
                                                    e.target.value,
                                                )
                                            }
                                            placeholder="e.g. 5509"
                                            className={inputClass}
                                        />

                                        <p className="mt-2 text-xs text-slate-400">
                                            Optional · Helps suppliers identify
                                            the product classification.
                                        </p>

                                        {errors.hs_code && (
                                            <div className={errorClass}>
                                                {errors.hs_code}
                                            </div>
                                        )}
                                    </div>

                                    <div>
                                        <label className={labelClass}>
                                            Required Quantity
                                        </label>

                                        <div className="grid grid-cols-[1fr_130px] gap-2">
                                            <input
                                                type="number"
                                                value={data.required_quantity}
                                                onChange={(e) =>
                                                    setData(
                                                        "required_quantity",
                                                        e.target.value,
                                                    )
                                                }
                                                placeholder="100000"
                                                className={inputClass}
                                            />

                                            <select
                                                value={data.unit}
                                                onChange={(e) =>
                                                    setData(
                                                        "unit",
                                                        e.target.value,
                                                    )
                                                }
                                                className={inputClass}
                                            >
                                                <option value="PCS">PCS</option>
                                                <option value="KG">KG</option>
                                                <option value="METER">
                                                    METER
                                                </option>
                                                <option value="YARD">
                                                    YARD
                                                </option>
                                            </select>
                                        </div>

                                        {errors.required_quantity && (
                                            <div className={errorClass}>
                                                {errors.required_quantity}
                                            </div>
                                        )}
                                    </div>
                                </div>
                            </section>

                            {/* Trade & Delivery */}
                            <section className="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                                <div className="border-b border-slate-100 px-6 py-5 sm:px-8">
                                    <div className="flex items-start gap-4">
                                        <div className="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-sky-50">
                                            <Globe2 className="h-5 w-5 text-sky-600" />
                                        </div>

                                        <div>
                                            <div className="text-[10px] font-black uppercase tracking-[0.2em] text-sky-600">
                                                02 · Trade
                                            </div>

                                            <h2 className="mt-1 text-xl font-black text-slate-900">
                                                Trade & Delivery
                                            </h2>

                                            <p className="mt-1 text-sm text-slate-500">
                                                Define your preferred commercial
                                                and delivery requirements.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div className="grid gap-6 p-6 sm:p-8 md:grid-cols-2">
                                    <div className="md:col-span-2">
                                        <label className={labelClass}>
                                            Destination Country
                                        </label>

                                        <div className="relative">
                                            <Globe2 className="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />

                                            <input
                                                type="text"
                                                value={data.destination_country}
                                                onChange={(e) =>
                                                    setData(
                                                        "destination_country",
                                                        e.target.value,
                                                    )
                                                }
                                                placeholder="e.g. Germany"
                                                className={`${inputClass} pl-11`}
                                            />
                                        </div>

                                        {errors.destination_country && (
                                            <div className={errorClass}>
                                                {errors.destination_country}
                                            </div>
                                        )}
                                    </div>

                                    <div>
                                        <label className={labelClass}>
                                            Currency
                                        </label>

                                        <select
                                            value={data.currency}
                                            onChange={(e) =>
                                                setData(
                                                    "currency",
                                                    e.target.value,
                                                )
                                            }
                                            className={inputClass}
                                        >
                                            <option value="USD">USD</option>
                                            <option value="EUR">EUR</option>
                                            <option value="IDR">IDR</option>
                                            <option value="JPY">JPY</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label className={labelClass}>
                                            Incoterm
                                        </label>

                                        <select
                                            value={data.incoterm}
                                            onChange={(e) =>
                                                setData(
                                                    "incoterm",
                                                    e.target.value,
                                                )
                                            }
                                            className={inputClass}
                                        >
                                            <option value="">
                                                Select Incoterm
                                            </option>

                                            <option value="EXW">EXW</option>
                                            <option value="FOB">FOB</option>
                                            <option value="CFR">CFR</option>
                                            <option value="CIF">CIF</option>
                                            <option value="DAP">DAP</option>
                                            <option value="DDP">DDP</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label className={labelClass}>
                                            <span className="flex items-center gap-2">
                                                <CalendarDays className="h-3.5 w-3.5 text-slate-400" />
                                                Required Delivery Date
                                            </span>
                                        </label>

                                        <input
                                            type="date"
                                            value={data.required_delivery_date}
                                            onChange={(e) =>
                                                setData(
                                                    "required_delivery_date",
                                                    e.target.value,
                                                )
                                            }
                                            className={inputClass}
                                        />

                                        {errors.required_delivery_date && (
                                            <div className={errorClass}>
                                                {errors.required_delivery_date}
                                            </div>
                                        )}
                                    </div>

                                    <div>
                                        <label className={labelClass}>
                                            <span className="flex items-center gap-2">
                                                <CalendarDays className="h-3.5 w-3.5 text-slate-400" />
                                                Quotation Deadline
                                            </span>
                                        </label>

                                        <input
                                            type="date"
                                            value={data.quotation_deadline}
                                            onChange={(e) =>
                                                setData(
                                                    "quotation_deadline",
                                                    e.target.value,
                                                )
                                            }
                                            className={inputClass}
                                        />

                                        {errors.quotation_deadline && (
                                            <div className={errorClass}>
                                                {errors.quotation_deadline}
                                            </div>
                                        )}
                                    </div>
                                </div>
                            </section>

                            {/* Specification */}
                            <section className="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                                <div className="border-b border-slate-100 px-6 py-5 sm:px-8">
                                    <div className="flex items-start gap-4">
                                        <div className="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-violet-50">
                                            <FileText className="h-5 w-5 text-violet-600" />
                                        </div>

                                        <div>
                                            <div className="text-[10px] font-black uppercase tracking-[0.2em] text-violet-600">
                                                03 · Specification
                                            </div>

                                            <h2 className="mt-1 text-xl font-black text-slate-900">
                                                Product Specification
                                            </h2>

                                            <p className="mt-1 text-sm text-slate-500">
                                                Provide technical details,
                                                quality requirements, or other
                                                information suppliers should
                                                know.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div className="p-6 sm:p-8">
                                    <label className={labelClass}>
                                        Description & Requirements
                                    </label>

                                    <textarea
                                        rows="7"
                                        value={data.description}
                                        onChange={(e) =>
                                            setData(
                                                "description",
                                                e.target.value,
                                            )
                                        }
                                        placeholder="Describe product specifications, material composition, quality standards, color, construction, packaging, certifications, or any other requirements..."
                                        className={`${inputClass} resize-y`}
                                    />

                                    {errors.description && (
                                        <div className={errorClass}>
                                            {errors.description}
                                        </div>
                                    )}
                                </div>
                            </section>

                            {/* Attachments */}
                            <section className="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                                <div className="border-b border-slate-100 px-6 py-5 sm:px-8">
                                    <div className="flex items-start gap-4">
                                        <div className="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-50">
                                            <Paperclip className="h-5 w-5 text-emerald-600" />
                                        </div>

                                        <div>
                                            <div className="text-[10px] font-black uppercase tracking-[0.2em] text-emerald-600">
                                                04 · Documents
                                            </div>

                                            <h2 className="mt-1 text-xl font-black text-slate-900">
                                                Supporting Documents
                                            </h2>

                                            <p className="mt-1 text-sm text-slate-500">
                                                Upload technical sheets,
                                                specifications, artwork, or
                                                other supporting documents.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div className="p-6 sm:p-8">
                                    <label
                                        className="
                                            flex
                                            cursor-pointer
                                            flex-col
                                            items-center
                                            justify-center
                                            rounded-2xl
                                            border-2
                                            border-dashed
                                            border-slate-200
                                            bg-slate-50
                                            px-6
                                            py-10
                                            text-center
                                            transition
                                            hover:border-amber-300
                                            hover:bg-amber-50/40
                                        "
                                    >
                                        <div className="flex h-14 w-14 items-center justify-center rounded-2xl bg-white shadow-sm">
                                            <UploadCloud className="h-7 w-7 text-amber-500" />
                                        </div>

                                        <div className="mt-4 text-sm font-bold text-slate-800">
                                            Upload supporting files
                                        </div>

                                        <div className="mt-1 max-w-md text-xs leading-5 text-slate-400">
                                            Product specifications, technical
                                            sheets, artwork, or other sourcing
                                            documents.
                                        </div>

                                        <div className="mt-4 rounded-full bg-white px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 shadow-sm">
                                            PDF · DOC · DOCX · XLS · XLSX
                                        </div>

                                        <input
                                            type="file"
                                            multiple
                                            className="hidden"
                                            onChange={(e) =>
                                                setData(
                                                    "attachments",
                                                    Array.from(e.target.files),
                                                )
                                            }
                                        />
                                    </label>

                                    {data.attachments?.length > 0 && (
                                        <div className="mt-4 space-y-2">
                                            {data.attachments.map(
                                                (file, index) => (
                                                    <div
                                                        key={`${file.name}-${index}`}
                                                        className="flex items-center justify-between rounded-xl border border-slate-100 bg-white px-4 py-3"
                                                    >
                                                        <div className="flex min-w-0 items-center gap-3">
                                                            <FileText className="h-4 w-4 shrink-0 text-slate-400" />

                                                            <span className="truncate text-sm font-medium text-slate-700">
                                                                {file.name}
                                                            </span>
                                                        </div>

                                                        <CheckCircle2 className="h-4 w-4 shrink-0 text-emerald-500" />
                                                    </div>
                                                ),
                                            )}
                                        </div>
                                    )}

                                    {errors.attachments && (
                                        <div className={errorClass}>
                                            {errors.attachments}
                                        </div>
                                    )}
                                </div>
                            </section>

                            {/* Submit */}
                            <div className="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <Link
                                    href={route("rfqs.index")}
                                    className="
                                        inline-flex
                                        items-center
                                        justify-center
                                        gap-2
                                        rounded-xl
                                        border
                                        border-slate-200
                                        bg-white
                                        px-5
                                        py-3
                                        text-sm
                                        font-bold
                                        text-slate-600
                                        transition
                                        hover:bg-slate-50
                                    "
                                >
                                    <ArrowLeft className="h-4 w-4" />
                                    Cancel
                                </Link>

                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="
                                        inline-flex
                                        items-center
                                        justify-center
                                        gap-2
                                        rounded-xl
                                        bg-slate-950
                                        px-7
                                        py-3.5
                                        text-sm
                                        font-black
                                        text-white
                                        shadow-xl
                                        shadow-slate-950/10
                                        transition
                                        hover:-translate-y-0.5
                                        hover:bg-slate-800
                                        disabled:cursor-not-allowed
                                        disabled:opacity-60
                                    "
                                >
                                    <Send className="h-4 w-4 text-amber-400" />

                                    {processing
                                        ? "Submitting..."
                                        : "Submit RFQ"}

                                    {!processing && (
                                        <ArrowRight className="h-4 w-4" />
                                    )}
                                </button>
                            </div>
                        </form>

                        {/* =================================================
                            SIDEBAR
                        ================================================= */}

                        <aside className="space-y-5 lg:sticky lg:top-28 lg:self-start">
                            <div className="overflow-hidden rounded-3xl bg-slate-950 text-white shadow-xl">
                                <div className="p-6">
                                    <div className="flex items-center gap-3">
                                        <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-400/10">
                                            <Sparkles className="h-5 w-5 text-amber-400" />
                                        </div>

                                        <div>
                                            <div className="text-sm font-black">
                                                Why Post an RFQ?
                                            </div>

                                            <div className="text-xs text-slate-400">
                                                Smarter textile sourcing
                                            </div>
                                        </div>
                                    </div>

                                    <div className="mt-6 space-y-4">
                                        {[
                                            "Reach qualified textile suppliers",
                                            "Compare supplier quotations",
                                            "Structure your sourcing requirements",
                                            "Manage the sourcing workflow digitally",
                                        ].map((item) => (
                                            <div
                                                key={item}
                                                className="flex gap-3"
                                            >
                                                <CheckCircle2 className="mt-0.5 h-4 w-4 shrink-0 text-emerald-400" />

                                                <span className="text-sm leading-5 text-slate-300">
                                                    {item}
                                                </span>
                                            </div>
                                        ))}
                                    </div>
                                </div>

                                <div className="border-t border-white/10 bg-white/5 px-6 py-4">
                                    <div className="flex items-center gap-2 text-xs text-slate-400">
                                        <ShieldCheck className="h-4 w-4 text-emerald-400" />
                                        Digestex Global Sourcing
                                    </div>
                                </div>
                            </div>

                            <div className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                                <div className="flex items-center gap-3">
                                    <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-50">
                                        <Package className="h-5 w-5 text-sky-600" />
                                    </div>

                                    <div>
                                        <div className="text-sm font-black text-slate-900">
                                            Sourcing Workflow
                                        </div>

                                        <div className="text-xs text-slate-400">
                                            From request to quotation
                                        </div>
                                    </div>
                                </div>

                                <div className="mt-6 space-y-4">
                                    {[
                                        "Create RFQ",
                                        "Suppliers review requirements",
                                        "Receive quotations",
                                        "Compare & select",
                                    ].map((step, index) => (
                                        <div
                                            key={step}
                                            className="flex items-center gap-3"
                                        >
                                            <div className="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-slate-100 text-[10px] font-black text-slate-600">
                                                {index + 1}
                                            </div>

                                            <span className="text-sm font-medium text-slate-600">
                                                {step}
                                            </span>
                                        </div>
                                    ))}
                                </div>
                            </div>

                            <div className="rounded-3xl border border-amber-100 bg-amber-50 p-6">
                                <div className="flex items-start gap-3">
                                    <Globe2 className="mt-0.5 h-5 w-5 text-amber-600" />

                                    <div>
                                        <div className="text-sm font-black text-slate-900">
                                            International Sourcing
                                        </div>

                                        <p className="mt-2 text-xs leading-5 text-slate-600">
                                            Specify your destination, currency,
                                            Incoterm and delivery requirements
                                            to help suppliers respond
                                            accurately.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </aside>
                    </div>
                </main>

                {/* =====================================================
                    FOOTER
                ===================================================== */}

                <footer className="border-t border-slate-200 bg-white">
                    <div className="mx-auto flex max-w-7xl flex-col gap-3 px-6 py-8 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <div className="text-sm font-black text-slate-900">
                                DIGESTEX
                            </div>

                            <div className="mt-1 text-xs text-slate-400">
                                Where Textile Meets Intelligence
                            </div>
                        </div>

                        <div className="text-xs text-slate-400">
                            Global Textile Sourcing Ecosystem
                        </div>
                    </div>
                </footer>
            </div>
            <Footer />
        </>
    );
}
