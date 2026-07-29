import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, useForm, router, Link, usePage } from "@inertiajs/react";
import LocationsSection from "@/Components/Company/LocationsSection";
import Swal from "sweetalert2";
import MachinesSection from "@/Components/Company/MachinesSection";
import CertificationsSection from "@/Components/Company/CertificationsSection";
import LinksSection from "@/Components/Company/LinksSection";
import MoqsSection from "@/Components/Company/MoqsSection";
import ImagesSection from "@/Components/Company/ImagesSection";
import CapacitiesSection from "@/Components/Company/CapacitiesSection";
import ProductsSection from "@/Components/Company/ProductsSection";
import ContactsSection from "@/Components/Company/ContactsSection";
import MarketsSection from "@/Components/Company/MarketsSection";
import LeadTimesSection from "@/Components/Company/LeadTimesSection";

export default function Edit({ auth, countries, company }) {
    const { locale } = usePage().props;

    const isEn = locale === "en";
    const { data, setData, post, processing, errors } = useForm({
        _method: "post",
        /*

        |--------------------------------------------------------------------------
        | Basic Company Data
        |--------------------------------------------------------------------------
        */
        nama_perusahaan: company.nama_perusahaan || "",
        country_code: company.country_code || "ID",
        country_name: company.country_name || "Indonesia",

        category: company.category || "", // Ditambahkan agar tidak undefined
        pimpinan: company.pimpinan || "",
        tenaga_kerja: company.tenaga_kerja || "",
        alamat_lengkap: company.alamat_lengkap || "",
        telepon: company.telepon || "",
        email_web: company.email_web || "",
        membership_type: company.membership_type || "public",
        /*

        |--------------------------------------------------------------------------
        | Location Fields
        |--------------------------------------------------------------------------
        */
        city: company.city || "", // Ditambahkan agar tidak undefined
        wilayah: company.wilayah || "", // Ditambahkan agar tidak undefined
        /*

        |--------------------------------------------------------------------------
        | Legacy Fallback Fields
        |--------------------------------------------------------------------------
        */
        produk: company.produk || "",
        pasar_ekspor: company.pasar_ekspor || "",
        /*

        |--------------------------------------------------------------------------
        | Relational Data
        |--------------------------------------------------------------------------
        */
        products: company.products || [],
        markets: company.markets || [],
        certifications: company.certifications || [],
        capacities: company.capacities || [],
        machines: company.machines || [],
        moqs: company.moqs || [],
        lead_times: company.leadTimes || [],

        locations: company.locations || [],

        contacts: company.contacts || [],
        links: company.links || [],
        images: company.images || [],
        /*

        |--------------------------------------------------------------------------
        | Inventory
        |--------------------------------------------------------------------------
        */
        stock_ready_caption: company.stock_ready_caption || "",
        stock_qty: company.stock_qty || 0,
        stock_unit: company.stock_unit || "kg",
        price: company.price || 0,
    });

    const handleSubmit = async (e) => {
        e.preventDefault();

        const result = await Swal.fire({
            icon: "question",
            title: "Submit Changes?",
            html: `
            <div style="text-align:left">
                <p>
                    Your company profile updates will be submitted
                    for verification.
                </p>

                <br/>

                <p>
                    Changes will not appear publicly until approved
                    by an administrator.
                </p>
            </div>
        `,
            showCancelButton: true,
            confirmButtonText: "Submit for Review",
            cancelButtonText: "Cancel",
            confirmButtonColor: "#2563eb",
        });

        if (!result.isConfirmed) {
            return;
        }

        post(route("companies.update", company.id), {
            forceFormData: true,

            onSuccess: () => {
                Swal.fire({
                    icon: "success",
                    title: "Update Submitted",
                    html: `
                    <div style="text-align:center">
                        <p>
                            Your update request has been submitted
                            successfully.
                        </p>

                        <br/>

                        <p>
                            The changes are now waiting for
                            administrator verification.
                        </p>
                    </div>
                `,
                    confirmButtonText: "OK",
                });
            },

            onError: () => {
                Swal.fire({
                    icon: "error",
                    title: "Submission Failed",
                    text: "Unable to submit your update request.",
                });
            },
        });
    };

    const hasText = (value) =>
        typeof value === "string" && value.trim().length > 0;

    const hasItems = (value) => Array.isArray(value) && value.length > 0;

    const dimensionStatus = (checks) => {
        const completed = checks.filter(Boolean).length;

        if (completed === 0) {
            return "needs_data";
        }

        if (completed === checks.length) {
            return "complete";
        }

        return "partial";
    };

    const intelligenceDimensions = [
        {
            number: "01",
            label: isEn ? "Identity" : "Identitas",
            target: "identity-section",
            status: dimensionStatus([
                hasText(data.nama_perusahaan),
                hasText(data.country_code),
                hasText(data.category),
                hasText(data.pimpinan),
            ]),
        },

        {
            number: "02",
            label: isEn ? "Facilities" : "Fasilitas",
            target: "locations-section",
            status: dimensionStatus([hasItems(data.locations)]),
        },

        {
            number: "03",
            label: isEn ? "Products" : "Produk",
            target: "products-section",
            status: dimensionStatus([hasItems(data.products)]),
        },

        {
            number: "04",
            label: isEn ? "Capacity" : "Kapasitas",
            target: "capacity-section",
            status: dimensionStatus([hasItems(data.capacities)]),
        },

        {
            number: "05",
            label: isEn ? "Machinery" : "Mesin",
            target: "machines-section",
            status: dimensionStatus([hasItems(data.machines)]),
        },

        {
            number: "06",
            label: isEn ? "Commercial" : "Komersial",
            target: "commercial-section",
            status: dimensionStatus([
                hasItems(data.moqs),
                hasItems(data.lead_times),
            ]),
        },

        {
            number: "07",
            label: isEn ? "Markets" : "Pasar",
            target: "markets-section",
            status: dimensionStatus([hasItems(data.markets)]),
        },

        {
            number: "08",
            label: isEn ? "Compliance" : "Kepatuhan",
            target: "certifications-section",
            status: dimensionStatus([hasItems(data.certifications)]),
        },

        {
            number: "09",
            label: isEn ? "Contacts" : "Kontak",
            target: "contacts-section",
            status: dimensionStatus([
                hasItems(data.contacts),
                hasItems(data.links),
            ]),
        },

        {
            number: "10",
            label: isEn ? "Media" : "Media",
            target: "media-section",
            status: dimensionStatus([hasItems(data.images)]),
        },
    ];

    const completedDimensions = intelligenceDimensions.filter(
        (dimension) => dimension.status === "complete",
    ).length;

    const partialDimensions = intelligenceDimensions.filter(
        (dimension) => dimension.status === "partial",
    ).length;

    const intelligenceReadiness = Math.round(
        (intelligenceDimensions.reduce((score, dimension) => {
            if (dimension.status === "complete") return score + 1;
            if (dimension.status === "partial") return score + 0.5;

            return score;
        }, 0) /
            intelligenceDimensions.length) *
            100,
    );

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title={`Edit - ${company.nama_perusahaan}`} />

            <div className="py-12 bg-[#0a192f] min-h-screen text-white">
                <div className="max-w-6xl mx-auto px-6">
                    {/* HEADER */}
                    {/* =========================================================
    DIGITAL COMPANY PASSPORT HEADER
========================================================= */}
                    <div className="mb-8">
                        <div className="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                            <div className="flex items-start gap-4">
                                <div className="w-12 h-12 shrink-0 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-600/30">
                                    <i className="fas fa-building text-white"></i>
                                </div>

                                <div>
                                    <p className="text-blue-400 text-[10px] font-black uppercase tracking-[0.35em] mb-2">
                                        DIGESTEX Company Intelligence
                                    </p>

                                    <h1 className="text-3xl lg:text-4xl font-black tracking-tight text-white">
                                        Digital Company Passport™
                                    </h1>

                                    <p className="mt-2 text-lg font-bold text-slate-300">
                                        {company.nama_perusahaan}
                                    </p>

                                    <p className="mt-3 max-w-2xl text-sm text-slate-400 leading-relaxed">
                                        {isEn
                                            ? "Build and maintain your company intelligence profile across the DIGESTEX ecosystem."
                                            : "Bangun dan perbarui profil intelligence perusahaan Anda di seluruh ekosistem DIGESTEX."}
                                    </p>
                                </div>
                            </div>

                            <div className="flex flex-wrap gap-2">
                                <span className="inline-flex items-center gap-2 rounded-full border border-emerald-500/20 bg-emerald-500/10 px-4 py-2 text-[10px] font-black uppercase tracking-widest text-emerald-400">
                                    <span className="w-2 h-2 rounded-full bg-emerald-400"></span>
                                    Passport Active
                                </span>

                                <span className="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-[10px] font-black uppercase tracking-widest text-slate-300">
                                    {company.status_verifikasi === "verified"
                                        ? "Verified Company"
                                        : "Verification Pending"}
                                </span>
                            </div>
                        </div>
                    </div>
                    {/* Info */}
                    {/* =========================================================
    DATA GOVERNANCE NOTICE
========================================================= */}
                    <div className="mb-8 rounded-2xl border border-amber-500/20 bg-amber-500/10 px-5 py-4">
                        <div className="flex items-start gap-3">
                            <span className="mt-1 w-2 h-2 shrink-0 rounded-full bg-amber-400 animate-pulse"></span>

                            <div>
                                <p className="text-xs font-black uppercase tracking-widest text-amber-400">
                                    Company Managed Data
                                </p>

                                <p className="mt-1 text-sm text-slate-300 leading-relaxed">
                                    {isEn
                                        ? "Updates submitted by your company are reviewed before becoming part of verified DIGESTEX intelligence."
                                        : "Pembaruan yang dikirim perusahaan akan ditinjau sebelum menjadi bagian dari intelligence DIGESTEX yang terverifikasi."}
                                </p>
                            </div>
                        </div>
                    </div>
                    {/* =========================================================
    COMPANY INTELLIGENCE SECTION MAP
========================================================= */}
                    <div className="mb-8">
                        <div className="flex items-center justify-between mb-4">
                            <div>
                                <p className="text-[10px] font-black uppercase tracking-[0.3em] text-blue-400">
                                    Intelligence Profile
                                </p>

                                <h2 className="mt-1 text-lg font-black text-white">
                                    Company Intelligence Data
                                </h2>
                            </div>

                            <p className="hidden md:block text-xs text-slate-500">
                                {isEn
                                    ? "Complete each intelligence dimension"
                                    : "Lengkapi setiap dimensi intelligence"}
                            </p>
                        </div>
                        <div className="mb-5 rounded-2xl border border-white/10 bg-white/[0.03] p-4">
                            <div className="flex items-end justify-between gap-4">
                                <div>
                                    <p className="text-[9px] font-black uppercase tracking-[0.25em] text-slate-500">
                                        {isEn
                                            ? "Company Intelligence Readiness"
                                            : "Kesiapan Intelligence Perusahaan"}
                                    </p>

                                    <p className="mt-1 text-2xl font-black text-white">
                                        {intelligenceReadiness}%
                                    </p>
                                </div>

                                <div className="text-right">
                                    <p className="text-[10px] font-bold text-emerald-400">
                                        {completedDimensions}{" "}
                                        {isEn ? "Complete" : "Lengkap"}
                                    </p>

                                    {partialDimensions > 0 && (
                                        <p className="mt-1 text-[10px] font-bold text-amber-400">
                                            {partialDimensions}{" "}
                                            {isEn ? "Partial" : "Sebagian"}
                                        </p>
                                    )}
                                </div>
                            </div>

                            <div className="mt-4 h-2 overflow-hidden rounded-full bg-white/5">
                                <div
                                    className="h-full rounded-full bg-gradient-to-r from-blue-500 to-emerald-400 transition-all duration-500"
                                    style={{
                                        width: `${intelligenceReadiness}%`,
                                    }}
                                />
                            </div>

                            <p className="mt-3 text-[10px] text-slate-500">
                                {isEn
                                    ? "Readiness reflects profile completeness and does not represent DIGESTEX verification."
                                    : "Readiness menunjukkan kelengkapan profil dan bukan status verifikasi DIGESTEX."}
                            </p>
                        </div>

                        <div className="grid grid-cols-2 md:grid-cols-5 gap-3">
                            {intelligenceDimensions.map((section) => (
                                <button
                                    key={section.number}
                                    type="button"
                                    onClick={() => {
                                        document
                                            .getElementById(section.target)
                                            ?.scrollIntoView({
                                                behavior: "smooth",
                                                block: "start",
                                            });
                                    }}
                                    className="
                    group
                    text-left
                    rounded-2xl
                    border
                    border-white/10
                    bg-white/[0.03]
                    p-4
                    hover:border-blue-500/40
                    hover:bg-blue-500/10
                    transition-all
                "
                                >
                                    <div className="flex items-center justify-between">
                                        <span className="text-[10px] font-black text-blue-400">
                                            {section.number}
                                        </span>

                                        <span
                                            className={`w-2 h-2 rounded-full ${
                                                section.status === "complete"
                                                    ? "bg-emerald-400"
                                                    : section.status ===
                                                        "partial"
                                                      ? "bg-amber-400"
                                                      : "bg-slate-600"
                                            }`}
                                        />
                                    </div>

                                    <p className="mt-3 text-xs font-black uppercase tracking-wider text-slate-300 group-hover:text-white">
                                        {section.label}
                                    </p>

                                    <p
                                        className={`mt-2 text-[9px] font-black uppercase tracking-widest ${
                                            section.status === "complete"
                                                ? "text-emerald-400"
                                                : section.status === "partial"
                                                  ? "text-amber-400"
                                                  : "text-slate-500"
                                        }`}
                                    >
                                        {section.status === "complete"
                                            ? isEn
                                                ? "Complete"
                                                : "Lengkap"
                                            : section.status === "partial"
                                              ? isEn
                                                  ? "Partial"
                                                  : "Sebagian"
                                              : isEn
                                                ? "Needs Data"
                                                : "Perlu Data"}
                                    </p>
                                </button>
                            ))}
                        </div>
                    </div>
                    <form
                        onSubmit={handleSubmit}
                        className="bg-white/5 border border-white/10 p-10 rounded-[40px] space-y-10 backdrop-blur-xl"
                    >
                        {/* BUTTON ACTION */}
                        {/* =======================================================
    STICKY SAVE BAR
======================================================= */}

                        <div
                            className="
        sticky
        top-0
        z-50
        mb-6
        bg-slate-900/95
        backdrop-blur-xl
        border
        border-white/10
        rounded-3xl
        p-4
        shadow-2xl
    "
                        >
                            <div className="flex items-center justify-between">
                                <div>
                                    <h3 className="text-sm font-black text-white uppercase tracking-widest">
                                        Company Intelligence Profile
                                    </h3>

                                    <p className="text-xs text-gray-400">
                                        {isEn
                                            ? "Complete your company intelligence data and submit it for review."
                                            : "Lengkapi data intelligence perusahaan dan kirim untuk proses review."}
                                    </p>
                                </div>

                                <div className="flex gap-3">
                                    <Link
                                        href={route("companies.index")}
                                        className="
                    px-6
                    py-3
                    border
                    border-white/10
                    rounded-2xl
                    font-black
                    uppercase
                    text-[10px]
                    tracking-widest
                    hover:bg-white/5
                    transition-all
                "
                                    >
                                        Cancel
                                    </Link>

                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="
                    bg-blue-600
                    text-white
                    font-black
                    px-8
                    py-3
                    rounded-2xl
                    uppercase
                    tracking-widest
                    hover:bg-blue-500
                    transition-all
                    shadow-xl
                    shadow-blue-600/30
                "
                                    >
                                        {processing
                                            ? "Submitting..."
                                            : "Submit for Review"}
                                    </button>
                                </div>
                            </div>
                        </div>
                        {/* =========================================================
    01 — COMPANY IDENTITY
========================================================= */}
                        <section
                            id="identity-section"
                            className="scroll-mt-36 rounded-[32px] border border-white/10 bg-white/[0.02] p-6 md:p-8"
                        >
                            {/* SECTION HEADER */}
                            <div className="mb-8">
                                <div className="flex items-center gap-3">
                                    <span className="flex h-9 w-9 items-center justify-center rounded-xl border border-blue-500/20 bg-blue-500/10 text-xs font-black text-blue-400">
                                        01
                                    </span>

                                    <div>
                                        <p className="text-[10px] font-black uppercase tracking-[0.3em] text-blue-400">
                                            Company Identity
                                        </p>

                                        <h2 className="mt-1 text-xl font-black text-white">
                                            {isEn
                                                ? "Corporate Identity"
                                                : "Identitas Perusahaan"}
                                        </h2>
                                    </div>
                                </div>

                                <p className="mt-4 max-w-3xl text-sm leading-relaxed text-slate-400">
                                    {isEn
                                        ? "Core information used to identify and classify your company across the DIGESTEX intelligence ecosystem."
                                        : "Informasi utama yang digunakan untuk mengidentifikasi dan mengklasifikasikan perusahaan dalam ekosistem intelligence DIGESTEX."}
                                </p>
                            </div>

                            {/* IDENTITY FIELDS */}
                            <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                                {/* HEADQUARTERS COUNTRY */}
                                <div>
                                    <label className="mb-3 block text-[10px] font-black uppercase tracking-widest text-slate-500">
                                        {isEn
                                            ? "Headquarters Country"
                                            : "Kantor Pusat Negara"}
                                    </label>

                                    <select
                                        value={data.country_code}
                                        onChange={(e) => {
                                            const selected = countries.find(
                                                (country) =>
                                                    country.country_code ===
                                                    e.target.value,
                                            );

                                            if (!selected) return;

                                            setData(
                                                "country_code",
                                                selected.country_code,
                                            );

                                            setData(
                                                "country_name",
                                                selected.country_name,
                                            );
                                        }}
                                        className="w-full rounded-2xl border border-white/10 bg-white/5 p-3 text-white transition focus:border-blue-500 focus:ring-blue-500"
                                    >
                                        {countries.map((country) => (
                                            <option
                                                key={country.country_code}
                                                value={country.country_code}
                                                className="text-slate-900"
                                            >
                                                {country.country_name}
                                            </option>
                                        ))}
                                    </select>

                                    <div className="mt-2 flex items-center gap-2">
                                        <span className="h-1.5 w-1.5 rounded-full bg-blue-400"></span>

                                        <span className="text-xs font-medium text-slate-400">
                                            {data.country_code} —{" "}
                                            {data.country_name}
                                        </span>
                                    </div>

                                    {errors.country_code && (
                                        <p className="mt-2 text-xs font-semibold text-red-400">
                                            {errors.country_code}
                                        </p>
                                    )}
                                </div>

                                {/* CATEGORY */}
                                <div>
                                    <label className="mb-3 block text-[10px] font-black uppercase tracking-widest text-slate-500">
                                        {isEn
                                            ? "Company Category"
                                            : "Kategori Perusahaan"}
                                    </label>

                                    <input
                                        type="text"
                                        value={data.category}
                                        onChange={(e) =>
                                            setData("category", e.target.value)
                                        }
                                        placeholder={
                                            isEn
                                                ? "e.g. Textile Manufacturer"
                                                : "contoh: Produsen Tekstil"
                                        }
                                        className="w-full rounded-2xl border border-white/10 bg-white/5 p-3 text-white placeholder:text-slate-600 transition focus:border-blue-500 focus:ring-blue-500"
                                    />

                                    {errors.category && (
                                        <p className="mt-2 text-xs font-semibold text-red-400">
                                            {errors.category}
                                        </p>
                                    )}
                                </div>

                                {/* CEO / DIRECTOR */}
                                <div>
                                    <label className="mb-3 block text-[10px] font-black uppercase tracking-widest text-slate-500">
                                        {isEn
                                            ? "CEO / President Director"
                                            : "Direktur Utama / Pimpinan"}
                                    </label>

                                    <input
                                        type="text"
                                        value={data.pimpinan}
                                        onChange={(e) =>
                                            setData("pimpinan", e.target.value)
                                        }
                                        placeholder={
                                            isEn
                                                ? "Name of company leader"
                                                : "Nama pimpinan perusahaan"
                                        }
                                        className="w-full rounded-2xl border border-white/10 bg-white/5 p-3 text-white placeholder:text-slate-600 transition focus:border-blue-500 focus:ring-blue-500"
                                    />

                                    {errors.pimpinan && (
                                        <p className="mt-2 text-xs font-semibold text-red-400">
                                            {errors.pimpinan}
                                        </p>
                                    )}
                                </div>
                            </div>
                        </section>

                        {/* =========================================================
    02 — FACILITIES & LOCATIONS
========================================================= */}
                        <section
                            id="locations-section"
                            className="scroll-mt-36"
                        >
                            <div className="mb-6">
                                <div className="flex items-center gap-3">
                                    <span className="flex h-9 w-9 items-center justify-center rounded-xl border border-blue-500/20 bg-blue-500/10 text-xs font-black text-blue-400">
                                        02
                                    </span>

                                    <div>
                                        <p className="text-[10px] font-black uppercase tracking-[0.3em] text-blue-400">
                                            Facilities & Locations
                                        </p>

                                        <h2 className="mt-1 text-xl font-black text-white">
                                            {isEn
                                                ? "Operational Footprint"
                                                : "Lokasi & Fasilitas Operasional"}
                                        </h2>
                                    </div>
                                </div>

                                <p className="mt-4 max-w-3xl text-sm leading-relaxed text-slate-400">
                                    {isEn
                                        ? "Map your headquarters, factories, warehouses, offices, and other operational facilities."
                                        : "Petakan kantor pusat, pabrik, gudang, kantor, dan fasilitas operasional perusahaan lainnya."}
                                </p>
                            </div>

                            <LocationsSection
                                data={data}
                                setData={setData}
                                company={company}
                                countries={countries}
                            />
                        </section>

                        {/* =========================================================
    03 — PRODUCTS & CAPABILITIES
========================================================= */}
                        <section id="products-section" className="scroll-mt-36">
                            <div className="mb-6">
                                <div className="flex items-center gap-3">
                                    <span className="flex h-9 w-9 items-center justify-center rounded-xl border border-blue-500/20 bg-blue-500/10 text-xs font-black text-blue-400">
                                        03
                                    </span>

                                    <div>
                                        <p className="text-[10px] font-black uppercase tracking-[0.3em] text-blue-400">
                                            Products & Capabilities
                                        </p>

                                        <h2 className="mt-1 text-xl font-black text-white">
                                            {isEn
                                                ? "Product Intelligence"
                                                : "Intelligence Produk"}
                                        </h2>
                                    </div>
                                </div>

                                <p className="mt-4 max-w-3xl text-sm leading-relaxed text-slate-400">
                                    {isEn
                                        ? "Define the products, product categories, HS classifications, applications, and core capabilities offered by your company."
                                        : "Lengkapi produk, kategori produk, klasifikasi HS, aplikasi, dan kapabilitas utama yang ditawarkan perusahaan."}
                                </p>
                            </div>

                            <ProductsSection
                                data={data}
                                setData={setData}
                                company={company}
                            />
                        </section>

                        {/* =========================================================
    04 — PRODUCTION CAPACITY
========================================================= */}
                        <section id="capacity-section" className="scroll-mt-36">
                            <div className="mb-6">
                                <div className="flex items-center gap-3">
                                    <span className="flex h-9 w-9 items-center justify-center rounded-xl border border-blue-500/20 bg-blue-500/10 text-xs font-black text-blue-400">
                                        04
                                    </span>

                                    <div>
                                        <p className="text-[10px] font-black uppercase tracking-[0.3em] text-blue-400">
                                            Production Capacity
                                        </p>

                                        <h2 className="mt-1 text-xl font-black text-white">
                                            {isEn
                                                ? "Manufacturing Capacity"
                                                : "Kapasitas Produksi"}
                                        </h2>
                                    </div>
                                </div>

                                <p className="mt-4 max-w-3xl text-sm leading-relaxed text-slate-400">
                                    {isEn
                                        ? "Describe your production capacity to help buyers and partners understand the scale and availability of your manufacturing operations."
                                        : "Jelaskan kapasitas produksi untuk membantu buyer dan mitra memahami skala serta kemampuan operasi manufaktur perusahaan."}
                                </p>
                            </div>

                            <CapacitiesSection
                                data={data}
                                setData={setData}
                                company={company}
                            />
                        </section>

                        {/* =========================================================
    05 — MACHINERY & TECHNOLOGY
========================================================= */}
                        <section id="machines-section" className="scroll-mt-36">
                            <div className="mb-6">
                                <div className="flex items-center gap-3">
                                    <span className="flex h-9 w-9 items-center justify-center rounded-xl border border-blue-500/20 bg-blue-500/10 text-xs font-black text-blue-400">
                                        05
                                    </span>

                                    <div>
                                        <p className="text-[10px] font-black uppercase tracking-[0.3em] text-blue-400">
                                            Machinery & Technology
                                        </p>

                                        <h2 className="mt-1 text-xl font-black text-white">
                                            {isEn
                                                ? "Manufacturing Technology"
                                                : "Teknologi Manufaktur"}
                                        </h2>
                                    </div>
                                </div>

                                <p className="mt-4 max-w-3xl text-sm leading-relaxed text-slate-400">
                                    {isEn
                                        ? "Build an intelligence view of your machinery, installed technology, equipment scale, and manufacturing capabilities."
                                        : "Bangun informasi intelligence mengenai mesin, teknologi terpasang, skala peralatan, dan kemampuan manufaktur perusahaan."}
                                </p>
                            </div>

                            <MachinesSection
                                data={data}
                                setData={setData}
                                company={company}
                            />
                        </section>
                        {/* =========================================================
    06 — COMMERCIAL & SUPPLY CAPABILITY
========================================================= */}
                        <section
                            id="commercial-section"
                            className="scroll-mt-36"
                        >
                            <div className="mb-6">
                                <div className="flex items-center gap-3">
                                    <span className="flex h-9 w-9 items-center justify-center rounded-xl border border-blue-500/20 bg-blue-500/10 text-xs font-black text-blue-400">
                                        06
                                    </span>

                                    <div>
                                        <p className="text-[10px] font-black uppercase tracking-[0.3em] text-blue-400">
                                            Commercial & Supply Capability
                                        </p>

                                        <h2 className="mt-1 text-xl font-black text-white">
                                            {isEn
                                                ? "Commercial Readiness"
                                                : "Kesiapan Komersial"}
                                        </h2>
                                    </div>
                                </div>

                                <p className="mt-4 max-w-3xl text-sm leading-relaxed text-slate-400">
                                    {isEn
                                        ? "Define minimum order requirements and production lead times to help buyers evaluate your company's commercial and supply capability."
                                        : "Tentukan minimum order dan waktu produksi untuk membantu buyer mengevaluasi kemampuan komersial dan kesiapan supply perusahaan."}
                                </p>
                            </div>

                            <div className="space-y-8">
                                {/* MOQ INTELLIGENCE */}
                                <div className="rounded-[28px] border border-white/10 bg-white/[0.02] p-1">
                                    <div className="px-5 pt-5 md:px-6 md:pt-6">
                                        <p className="text-[10px] font-black uppercase tracking-[0.25em] text-slate-500">
                                            06A — Order Capability
                                        </p>

                                        <h3 className="mt-1 text-base font-black text-white">
                                            Minimum Order Quantity
                                        </h3>

                                        <p className="mt-2 text-xs leading-relaxed text-slate-400">
                                            {isEn
                                                ? "Specify minimum order requirements by product or production category."
                                                : "Tentukan minimum pemesanan berdasarkan produk atau kategori produksi."}
                                        </p>
                                    </div>

                                    <div className="p-4 md:p-5">
                                        <MoqsSection
                                            data={data}
                                            setData={setData}
                                            company={company}
                                        />
                                    </div>
                                </div>

                                {/* LEAD TIME INTELLIGENCE */}
                                <div className="rounded-[28px] border border-white/10 bg-white/[0.02] p-1">
                                    <div className="px-5 pt-5 md:px-6 md:pt-6">
                                        <p className="text-[10px] font-black uppercase tracking-[0.25em] text-slate-500">
                                            06B — Supply Responsiveness
                                        </p>

                                        <h3 className="mt-1 text-base font-black text-white">
                                            Production Lead Time
                                        </h3>

                                        <p className="mt-2 text-xs leading-relaxed text-slate-400">
                                            {isEn
                                                ? "Define typical production and delivery lead times for your commercial operations."
                                                : "Tentukan estimasi waktu produksi dan pemenuhan pesanan untuk operasi komersial perusahaan."}
                                        </p>
                                    </div>

                                    <div className="p-4 md:p-5">
                                        <LeadTimesSection
                                            data={data}
                                            setData={setData}
                                            company={company}
                                        />
                                    </div>
                                </div>
                            </div>
                        </section>
                        {/* =========================================================
    09 — CONTACTS & DIGITAL PRESENCE
========================================================= */}
                        <section id="contacts-section" className="scroll-mt-36">
                            {/* SECTION HEADER */}
                            <div className="mb-6">
                                <div className="flex items-center gap-3">
                                    <span className="flex h-9 w-9 items-center justify-center rounded-xl border border-blue-500/20 bg-blue-500/10 text-xs font-black text-blue-400">
                                        09
                                    </span>

                                    <div>
                                        <p className="text-[10px] font-black uppercase tracking-[0.3em] text-blue-400">
                                            Contacts & Digital Presence
                                        </p>

                                        <h2 className="mt-1 text-xl font-black text-white">
                                            {isEn
                                                ? "Business Connectivity"
                                                : "Konektivitas Bisnis"}
                                        </h2>
                                    </div>
                                </div>

                                <p className="mt-4 max-w-3xl text-sm leading-relaxed text-slate-400">
                                    {isEn
                                        ? "Maintain business contacts and official digital channels so buyers, suppliers, and partners can connect with the right people in your organization."
                                        : "Kelola kontak bisnis dan kanal digital resmi agar buyer, supplier, dan mitra dapat terhubung dengan pihak yang tepat di perusahaan."}
                                </p>
                            </div>

                            <div className="space-y-8">
                                {/* =====================================================
            09A — BUSINESS CONTACTS
        ===================================================== */}
                                <div className="rounded-[28px] border border-white/10 bg-white/[0.02] p-1">
                                    <div className="px-5 pt-5 md:px-6 md:pt-6">
                                        <div className="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                                            <div>
                                                <p className="text-[10px] font-black uppercase tracking-[0.25em] text-slate-500">
                                                    09A — Business Contacts
                                                </p>

                                                <h3 className="mt-1 text-base font-black text-white">
                                                    {isEn
                                                        ? "Key Contact Persons"
                                                        : "Kontak Utama Perusahaan"}
                                                </h3>

                                                <p className="mt-2 max-w-2xl text-xs leading-relaxed text-slate-400">
                                                    {isEn
                                                        ? "Add relevant contact persons for sales, sourcing, export, management, technical inquiries, and business partnerships."
                                                        : "Tambahkan kontak yang relevan untuk sales, sourcing, ekspor, manajemen, kebutuhan teknis, dan kemitraan bisnis."}
                                                </p>
                                            </div>

                                            <div className="shrink-0 rounded-xl border border-blue-500/20 bg-blue-500/10 px-3 py-2">
                                                <p className="text-[9px] font-black uppercase tracking-widest text-blue-400">
                                                    Intelligence Signal
                                                </p>

                                                <p className="mt-1 text-[11px] font-semibold text-slate-300">
                                                    {isEn
                                                        ? "Business Accessibility"
                                                        : "Aksesibilitas Bisnis"}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div className="p-4 md:p-5">
                                        <ContactsSection
                                            data={data}
                                            setData={setData}
                                            company={company}
                                        />
                                    </div>
                                </div>

                                {/* =====================================================
            09B — DIGITAL PRESENCE
        ===================================================== */}
                                <div className="rounded-[28px] border border-white/10 bg-white/[0.02] p-1">
                                    <div className="px-5 pt-5 md:px-6 md:pt-6">
                                        <div className="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                                            <div>
                                                <p className="text-[10px] font-black uppercase tracking-[0.25em] text-slate-500">
                                                    09B — Digital Presence
                                                </p>

                                                <h3 className="mt-1 text-base font-black text-white">
                                                    {isEn
                                                        ? "Official Digital Channels"
                                                        : "Kanal Digital Resmi"}
                                                </h3>

                                                <p className="mt-2 max-w-2xl text-xs leading-relaxed text-slate-400">
                                                    {isEn
                                                        ? "Connect your official website, company profiles, catalogs, social channels, and other trusted digital resources."
                                                        : "Hubungkan website resmi, profil perusahaan, katalog, kanal sosial, dan sumber digital terpercaya lainnya."}
                                                </p>
                                            </div>

                                            <div className="shrink-0 rounded-xl border border-violet-500/20 bg-violet-500/10 px-3 py-2">
                                                <p className="text-[9px] font-black uppercase tracking-widest text-violet-400">
                                                    Intelligence Signal
                                                </p>

                                                <p className="mt-1 text-[11px] font-semibold text-slate-300">
                                                    {isEn
                                                        ? "Digital Presence"
                                                        : "Kehadiran Digital"}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div className="p-4 md:p-5">
                                        <LinksSection
                                            data={data}
                                            setData={setData}
                                            company={company}
                                        />
                                    </div>
                                </div>
                            </div>
                        </section>

                        {/* =========================================================
    07 — MARKET PRESENCE & EXPORT INTELLIGENCE
========================================================= */}
                        <section id="markets-section" className="scroll-mt-36">
                            <div className="mb-6">
                                <div className="flex items-center gap-3">
                                    <span className="flex h-9 w-9 items-center justify-center rounded-xl border border-blue-500/20 bg-blue-500/10 text-xs font-black text-blue-400">
                                        07
                                    </span>

                                    <div>
                                        <p className="text-[10px] font-black uppercase tracking-[0.3em] text-blue-400">
                                            Market Presence
                                        </p>

                                        <h2 className="mt-1 text-xl font-black text-white">
                                            {isEn
                                                ? "Market & Export Intelligence"
                                                : "Intelligence Pasar & Ekspor"}
                                        </h2>
                                    </div>
                                </div>

                                <p className="mt-4 max-w-3xl text-sm leading-relaxed text-slate-400">
                                    {isEn
                                        ? "Identify the domestic and international markets served by your company to strengthen market visibility, buyer discovery, and business matching."
                                        : "Identifikasi pasar domestik dan internasional yang dilayani perusahaan untuk memperkuat visibilitas pasar, buyer discovery, dan business matching."}
                                </p>
                            </div>

                            {/* MARKET INTELLIGENCE */}
                            <div className="rounded-[28px] border border-white/10 bg-white/[0.02] p-1">
                                <div className="px-5 pt-5 md:px-6 md:pt-6">
                                    <div className="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                                        <div>
                                            <p className="text-[10px] font-black uppercase tracking-[0.25em] text-slate-500">
                                                07A — Market Coverage
                                            </p>

                                            <h3 className="mt-1 text-base font-black text-white">
                                                {isEn
                                                    ? "Domestic & Export Markets"
                                                    : "Pasar Domestik & Ekspor"}
                                            </h3>

                                            <p className="mt-2 max-w-2xl text-xs leading-relaxed text-slate-400">
                                                {isEn
                                                    ? "Add the countries and markets where your products are currently sold, exported, or commercially active."
                                                    : "Tambahkan negara dan pasar tempat produk perusahaan saat ini dijual, diekspor, atau memiliki aktivitas komersial."}
                                            </p>
                                        </div>

                                        <div className="shrink-0 rounded-xl border border-blue-500/20 bg-blue-500/10 px-3 py-2">
                                            <p className="text-[9px] font-black uppercase tracking-widest text-blue-400">
                                                Intelligence Signal
                                            </p>

                                            <p className="mt-1 text-[11px] font-semibold text-slate-300">
                                                {isEn
                                                    ? "Market Reach"
                                                    : "Jangkauan Pasar"}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div className="p-4 md:p-5">
                                    <MarketsSection
                                        data={data}
                                        setData={setData}
                                        company={company}
                                    />
                                </div>
                            </div>
                        </section>
                        {/* =========================================================
    08 — COMPLIANCE & CERTIFICATIONS
========================================================= */}
                        <section
                            id="certifications-section"
                            className="scroll-mt-36"
                        >
                            {/* SECTION HEADER */}
                            <div className="mb-6">
                                <div className="flex items-center gap-3">
                                    <span className="flex h-9 w-9 items-center justify-center rounded-xl border border-blue-500/20 bg-blue-500/10 text-xs font-black text-blue-400">
                                        08
                                    </span>

                                    <div>
                                        <p className="text-[10px] font-black uppercase tracking-[0.3em] text-blue-400">
                                            Compliance & Certifications
                                        </p>

                                        <h2 className="mt-1 text-xl font-black text-white">
                                            {isEn
                                                ? "Compliance Intelligence"
                                                : "Intelligence Kepatuhan"}
                                        </h2>
                                    </div>
                                </div>

                                <p className="mt-4 max-w-3xl text-sm leading-relaxed text-slate-400">
                                    {isEn
                                        ? "Document certifications, standards, and compliance credentials that demonstrate your company's manufacturing, quality, environmental, and social capabilities."
                                        : "Dokumentasikan sertifikasi, standar, dan kredensial kepatuhan yang menunjukkan kemampuan manufaktur, kualitas, lingkungan, dan sosial perusahaan."}
                                </p>
                            </div>

                            {/* COMPLIANCE INTELLIGENCE CARD */}
                            <div className="rounded-[28px] border border-white/10 bg-white/[0.02] p-1">
                                <div className="px-5 pt-5 md:px-6 md:pt-6">
                                    <div className="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                                        <div>
                                            <p className="text-[10px] font-black uppercase tracking-[0.25em] text-slate-500">
                                                08A — Certification Portfolio
                                            </p>

                                            <h3 className="mt-1 text-base font-black text-white">
                                                {isEn
                                                    ? "Standards & Certifications"
                                                    : "Standar & Sertifikasi"}
                                            </h3>

                                            <p className="mt-2 max-w-2xl text-xs leading-relaxed text-slate-400">
                                                {isEn
                                                    ? "Add certifications held by your company and maintain their validity information and supporting documentation."
                                                    : "Tambahkan sertifikasi yang dimiliki perusahaan serta kelola masa berlaku dan dokumen pendukungnya."}
                                            </p>
                                        </div>

                                        {/* INTELLIGENCE SIGNAL */}
                                        <div className="shrink-0 rounded-xl border border-emerald-500/20 bg-emerald-500/10 px-3 py-2">
                                            <p className="text-[9px] font-black uppercase tracking-widest text-emerald-400">
                                                Intelligence Signal
                                            </p>

                                            <p className="mt-1 text-[11px] font-semibold text-slate-300">
                                                {isEn
                                                    ? "Compliance Readiness"
                                                    : "Kesiapan Kepatuhan"}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div className="p-4 md:p-5">
                                    <CertificationsSection
                                        data={data}
                                        setData={setData}
                                        company={company}
                                    />
                                </div>
                            </div>
                        </section>

                        {/* =========================================================
    10 — COMPANY MEDIA & VISUAL ASSETS
========================================================= */}
                        <section id="media-section" className="scroll-mt-36">
                            {/* SECTION HEADER */}
                            <div className="mb-6">
                                <div className="flex items-center gap-3">
                                    <span className="flex h-9 w-9 items-center justify-center rounded-xl border border-blue-500/20 bg-blue-500/10 text-xs font-black text-blue-400">
                                        10
                                    </span>

                                    <div>
                                        <p className="text-[10px] font-black uppercase tracking-[0.3em] text-blue-400">
                                            Company Media & Visual Assets
                                        </p>

                                        <h2 className="mt-1 text-xl font-black text-white">
                                            {isEn
                                                ? "Visual Company Intelligence"
                                                : "Intelligence Visual Perusahaan"}
                                        </h2>
                                    </div>
                                </div>

                                <p className="mt-4 max-w-3xl text-sm leading-relaxed text-slate-400">
                                    {isEn
                                        ? "Build a trusted visual representation of your company, facilities, products, machinery, and manufacturing capabilities."
                                        : "Bangun representasi visual terpercaya mengenai perusahaan, fasilitas, produk, mesin, dan kemampuan manufaktur."}
                                </p>
                            </div>

                            {/* VISUAL INTELLIGENCE CARD */}
                            <div className="rounded-[28px] border border-white/10 bg-white/[0.02] p-1">
                                <div className="px-5 pt-5 md:px-6 md:pt-6">
                                    <div className="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                                        <div>
                                            <p className="text-[10px] font-black uppercase tracking-[0.25em] text-slate-500">
                                                10A — Visual Assets
                                            </p>

                                            <h3 className="mt-1 text-base font-black text-white">
                                                {isEn
                                                    ? "Company & Manufacturing Media"
                                                    : "Media Perusahaan & Manufaktur"}
                                            </h3>

                                            <p className="mt-2 max-w-2xl text-xs leading-relaxed text-slate-400">
                                                {isEn
                                                    ? "Add visual assets that help buyers and partners understand your company's facilities, products, technology, and manufacturing operations."
                                                    : "Tambahkan aset visual yang membantu buyer dan mitra memahami fasilitas, produk, teknologi, dan operasi manufaktur perusahaan."}
                                            </p>
                                        </div>

                                        {/* INTELLIGENCE SIGNAL */}
                                        <div className="shrink-0 rounded-xl border border-cyan-500/20 bg-cyan-500/10 px-3 py-2">
                                            <p className="text-[9px] font-black uppercase tracking-widest text-cyan-400">
                                                Intelligence Signal
                                            </p>

                                            <p className="mt-1 text-[11px] font-semibold text-slate-300">
                                                {isEn
                                                    ? "Visual Evidence"
                                                    : "Bukti Visual"}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div className="p-4 md:p-5">
                                    <ImagesSection
                                        data={data}
                                        setData={setData}
                                        company={company}
                                    />
                                </div>
                            </div>
                        </section>
                        {/* STOCK & INVENTORY */}
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
