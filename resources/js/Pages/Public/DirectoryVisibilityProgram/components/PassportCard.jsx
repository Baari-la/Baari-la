import {
    Award,
    BadgeCheck,
    BarChart3,
    Building2,
    CheckCircle2,
    Factory,
    Globe2,
    MapPin,
    Package,
    ShieldCheck,
    Sparkles,
    Star,
    Users,
} from "lucide-react";

export default function PassportCard({ isEn = false }) {
    const readinessItems = isEn
        ? [
              "Verified Company",
              "Export Ready",
              "International Buyer Ready",
              "Domestic Buyer Ready",
              "Digital Passport Complete",
              "Global Visibility",
          ]
        : [
              "Perusahaan Terverifikasi",
              "Siap Ekspor",
              "Siap untuk Buyer Internasional",
              "Siap untuk Buyer Domestik",
              "Digital Passport Lengkap",
              "Visibilitas Global",
          ];

    return (
        <div className="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl shadow-slate-900/10">
            {/* ==========================================================
                SAMPLE NOTICE
            ========================================================== */}

            <div className="flex flex-col gap-3 border-b border-amber-200 bg-amber-50 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div className="flex items-center gap-3">
                    <div className="rounded-xl bg-amber-100 p-2 text-amber-700">
                        <Sparkles className="h-5 w-5" />
                    </div>

                    <div>
                        <p className="text-xs font-bold uppercase tracking-[0.18em] text-amber-700">
                            {isEn ? "Sample Company" : "Perusahaan Contoh"}
                        </p>

                        <p className="text-sm font-medium text-amber-900">
                            {isEn
                                ? "For demonstration purposes only"
                                : "Hanya untuk tujuan demonstrasi"}
                        </p>
                    </div>
                </div>

                <span className="inline-flex w-fit items-center rounded-full border border-amber-300 bg-white px-3 py-1.5 text-xs font-bold uppercase tracking-wide text-amber-700">
                    {isEn ? "Recommended Profile" : "Profil Rekomendasi"}
                </span>
            </div>

            {/* ==========================================================
                HEADER
            ========================================================== */}

            <div className="relative overflow-hidden bg-gradient-to-r from-slate-950 via-indigo-950 to-slate-950 p-8 text-white lg:p-10">
                <div className="absolute -right-20 -top-20 h-56 w-56 rounded-full bg-indigo-500/20 blur-3xl" />
                <div className="absolute -bottom-24 left-1/3 h-48 w-48 rounded-full bg-emerald-500/10 blur-3xl" />

                <div className="relative flex flex-col gap-8 lg:flex-row lg:items-start lg:justify-between">
                    <div className="max-w-2xl">
                        <div className="flex flex-wrap items-center gap-2">
                            <span className="inline-flex items-center gap-2 rounded-full border border-emerald-400/30 bg-emerald-400/10 px-3 py-1.5 text-xs font-bold uppercase tracking-[0.15em] text-emerald-300">
                                <BadgeCheck className="h-4 w-4" />
                                {isEn
                                    ? "Sample Verified"
                                    : "Contoh Terverifikasi"}
                            </span>

                            <span className="rounded-full border border-white/10 bg-white/5 px-3 py-1.5 text-xs font-semibold uppercase tracking-wide text-slate-300">
                                Digital Company Passport™
                            </span>
                        </div>

                        <p className="mt-6 text-xs font-bold uppercase tracking-[0.3em] text-emerald-300">
                            {isEn
                                ? "Recommended Company Profile"
                                : "Profil Perusahaan Rekomendasi"}
                        </p>

                        <h3 className="mt-3 text-3xl font-black tracking-tight sm:text-4xl">
                            PT. YOUR TEXTILE COMPANY
                            <span className="ml-2 text-indigo-300">
                                (SAMPLE)
                            </span>
                        </h3>

                        <p className="mt-4 text-base text-slate-300 sm:text-lg">
                            Integrated Textile Manufacturer • Exporter
                        </p>

                        <div className="mt-5 flex flex-wrap gap-3 text-sm text-slate-300">
                            <span className="inline-flex items-center gap-2">
                                <MapPin className="h-4 w-4 text-emerald-300" />
                                Jakarta, Indonesia
                            </span>

                            <span className="hidden text-slate-600 sm:inline">
                                •
                            </span>

                            <span className="inline-flex items-center gap-2">
                                <Globe2 className="h-4 w-4 text-emerald-300" />
                                Asia • Europe • USA
                            </span>
                        </div>
                    </div>

                    {/* Visibility Score */}

                    <div className="w-full shrink-0 rounded-2xl border border-white/10 bg-white/10 p-5 backdrop-blur-xl lg:w-56">
                        <div className="flex items-center justify-between">
                            <span className="text-xs font-semibold uppercase tracking-[0.15em] text-slate-300">
                                Visibility Score™
                            </span>

                            <BarChart3 className="h-5 w-5 text-emerald-300" />
                        </div>

                        <div className="mt-2 flex items-end gap-1">
                            <span className="text-5xl font-black tracking-tight">
                                92
                            </span>

                            <span className="mb-2 text-lg font-semibold text-emerald-300">
                                /100
                            </span>
                        </div>

                        <div className="mt-4 h-2 overflow-hidden rounded-full bg-white/10">
                            <div
                                className="h-full rounded-full bg-emerald-400"
                                style={{ width: "92%" }}
                            />
                        </div>

                        <p className="mt-3 text-xs text-slate-400">
                            {isEn
                                ? "Recommended visibility level"
                                : "Tingkat visibilitas yang direkomendasikan"}
                        </p>
                    </div>
                </div>
            </div>

            {/* ==========================================================
                BUYER RECOMMENDATION
            ========================================================== */}

            <div className="grid border-b border-slate-200 bg-white md:grid-cols-2">
                <BuyerReadinessCard
                    icon="🌐"
                    title={
                        isEn
                            ? "Recommended for International Buyers"
                            : "Direkomendasikan untuk Buyer Internasional"
                    }
                    score="92%"
                    description={
                        isEn
                            ? "Company profile demonstrates strong international buyer readiness."
                            : "Profil perusahaan menunjukkan kesiapan yang kuat untuk buyer internasional."
                    }
                />

                <BuyerReadinessCard
                    icon="🇮🇩"
                    title={
                        isEn
                            ? "Recommended for Domestic Buyers"
                            : "Direkomendasikan untuk Buyer Domestik"
                    }
                    score="96%"
                    description={
                        isEn
                            ? "Company profile provides comprehensive information for domestic sourcing."
                            : "Profil perusahaan menyediakan informasi yang lengkap untuk kebutuhan sourcing domestik."
                    }
                />
            </div>

            {/* ==========================================================
                QUICK STATUS
            ========================================================== */}

            <div className="grid border-b border-slate-200 bg-slate-50 md:grid-cols-3">
                <StatusBox
                    icon={ShieldCheck}
                    label={isEn ? "Verification" : "Verifikasi"}
                    value={isEn ? "Verified" : "Terverifikasi"}
                />

                <StatusBox
                    icon={BarChart3}
                    label={isEn ? "Profile Completion" : "Kelengkapan Profil"}
                    value="95%"
                />

                <StatusBox
                    icon={Star}
                    label={isEn ? "Visibility Tier" : "Tingkat Visibilitas"}
                    value="Gold Visibility"
                />
            </div>

            {/* ==========================================================
                COMPANY INTELLIGENCE
            ========================================================== */}

            <div className="grid gap-10 p-8 lg:grid-cols-2 lg:p-10">
                <div className="space-y-6">
                    <SectionLabel
                        title={
                            isEn
                                ? "Company Intelligence"
                                : "Company Intelligence"
                        }
                    />

                    <InfoRow
                        icon={Building2}
                        label={isEn ? "Industry" : "Industri"}
                        value="Integrated Textile Manufacturer"
                    />

                    <InfoRow
                        icon={MapPin}
                        label={isEn ? "Location" : "Lokasi"}
                        value="Jakarta, Indonesia"
                    />

                    <InfoRow
                        icon={Globe2}
                        label={isEn ? "Export Markets" : "Pasar Ekspor"}
                        value="Asia • Europe • USA"
                    />

                    <InfoRow
                        icon={Users}
                        label={isEn ? "Employees" : "Tenaga Kerja"}
                        value="850+"
                    />
                </div>

                <div className="space-y-6">
                    <SectionLabel
                        title={
                            isEn
                                ? "Manufacturing & Trade"
                                : "Manufaktur & Perdagangan"
                        }
                    />

                    <InfoRow
                        icon={Factory}
                        label={
                            isEn ? "Production Capacity" : "Kapasitas Produksi"
                        }
                        value="2.5 Million meters / month"
                    />

                    <InfoRow
                        icon={Award}
                        label={isEn ? "Certifications" : "Sertifikasi"}
                        value="OEKO-TEX®, ISO 9001, GRS"
                    />

                    <InfoRow
                        icon={Package}
                        label={isEn ? "Products" : "Produk"}
                        value="Yarn • Woven • Garment"
                    />

                    <InfoRow
                        icon={ShieldCheck}
                        label={isEn ? "Digital Readiness" : "Kesiapan Digital"}
                        value={
                            isEn
                                ? "Ready for Buyer Discovery"
                                : "Siap untuk Buyer Discovery"
                        }
                    />
                </div>
            </div>

            {/* ==========================================================
                BUSINESS READINESS
            ========================================================== */}

            <div className="border-t border-slate-200 bg-slate-50 p-8 lg:p-10">
                <div className="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p className="text-xs font-bold uppercase tracking-[0.2em] text-indigo-600">
                            {isEn
                                ? "DIGESTEX Intelligence"
                                : "DIGESTEX Intelligence"}
                        </p>

                        <h4 className="mt-1 text-xl font-black text-slate-900">
                            {isEn ? "Business Readiness™" : "Kesiapan Bisnis™"}
                        </h4>

                        <p className="mt-1 max-w-2xl text-sm text-slate-500">
                            {isEn
                                ? "A complete profile helps buyers understand your company's capabilities, readiness and business potential."
                                : "Profil yang lengkap membantu buyer memahami kapabilitas, kesiapan, dan potensi bisnis perusahaan Anda."}
                        </p>
                    </div>

                    <span className="inline-flex w-fit items-center gap-2 rounded-full bg-emerald-100 px-4 py-2 text-sm font-bold text-emerald-700">
                        <CheckCircle2 className="h-4 w-4" />
                        {isEn ? "Recommended Profile" : "Profil Rekomendasi"}
                    </span>
                </div>

                <div className="mt-6 flex flex-wrap gap-3">
                    {readinessItems.map((item) => (
                        <span
                            key={item}
                            className="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-white px-4 py-2 text-sm font-semibold text-emerald-700 shadow-sm"
                        >
                            <CheckCircle2 className="h-4 w-4" />
                            {item}
                        </span>
                    ))}
                </div>

                {/* Buyer readiness metrics */}

                <div className="mt-8 grid gap-4 sm:grid-cols-2">
                    <ReadinessMetric
                        icon="🌐"
                        title={
                            isEn
                                ? "International Buyer Readiness"
                                : "Kesiapan Buyer Internasional"
                        }
                        score="92%"
                        label={
                            isEn
                                ? "Recommended for International Buyers"
                                : "Direkomendasikan untuk Buyer Internasional"
                        }
                    />

                    <ReadinessMetric
                        icon="🇮🇩"
                        title={
                            isEn
                                ? "Domestic Buyer Readiness"
                                : "Kesiapan Buyer Domestik"
                        }
                        score="96%"
                        label={
                            isEn
                                ? "Recommended for Domestic Buyers"
                                : "Direkomendasikan untuk Buyer Domestik"
                        }
                    />
                </div>

                {/* Smart Business Matching */}

                <div className="mt-6 rounded-2xl border border-indigo-100 bg-indigo-50/70 p-5">
                    <div className="flex items-start gap-3">
                        <div className="rounded-xl bg-indigo-100 p-2 text-indigo-600">
                            <Sparkles className="h-5 w-5" />
                        </div>

                        <div>
                            <div className="flex flex-wrap items-center gap-2">
                                <h5 className="font-bold text-slate-900">
                                    Smart Business Matching™
                                </h5>

                                <span className="rounded-full bg-white px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-indigo-600 ring-1 ring-indigo-200">
                                    {isEn ? "Coming Soon" : "Segera Hadir"}
                                </span>
                            </div>

                            <p className="mt-1 text-sm leading-6 text-slate-600">
                                {isEn
                                    ? "Intelligent business matching will be introduced as the DIGESTEX ecosystem and company data continue to develop."
                                    : "Smart Business Matching akan diperkenalkan seiring berkembangnya ekosistem DIGESTEX dan kualitas data perusahaan."}
                            </p>
                        </div>
                    </div>
                </div>

                {/* Illustration note */}

                <div className="mt-8 flex items-center gap-3 text-sm text-slate-500">
                    <CheckCircle2 className="h-5 w-5 shrink-0 text-emerald-500" />

                    {isEn
                        ? "Illustration of a Digital Company Passport™ — sample data only."
                        : "Ilustrasi Digital Company Passport™ — hanya menggunakan data contoh."}
                </div>
            </div>
        </div>
    );
}

/* ==========================================================
   COMPONENTS
========================================================== */

function InfoRow({ icon: Icon, label, value }) {
    return (
        <div className="flex items-start gap-4">
            <div className="rounded-xl bg-slate-100 p-3 text-indigo-600">
                <Icon className="h-5 w-5" />
            </div>

            <div className="min-w-0">
                <p className="text-sm text-slate-500">{label}</p>

                <p className="mt-1 font-semibold leading-6 text-slate-900">
                    {value}
                </p>
            </div>
        </div>
    );
}

function StatusBox({ icon: Icon, label, value }) {
    return (
        <div className="flex items-center gap-4 p-5">
            <div className="rounded-xl bg-white p-3 text-emerald-600 shadow-sm ring-1 ring-slate-200">
                <Icon className="h-5 w-5" />
            </div>

            <div>
                <p className="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    {label}
                </p>

                <p className="mt-1 font-bold text-slate-900">{value}</p>
            </div>
        </div>
    );
}

function SectionLabel({ title }) {
    return (
        <div className="border-b border-slate-200 pb-3">
            <h4 className="text-sm font-black uppercase tracking-[0.15em] text-slate-900">
                {title}
            </h4>
        </div>
    );
}

function BuyerReadinessCard({ icon, title, score, description }) {
    return (
        <div className="border-b border-slate-200 p-6 last:border-b-0 md:border-b-0 md:border-r md:last:border-r-0">
            <div className="flex items-start gap-4">
                <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-slate-100 text-2xl">
                    {icon}
                </div>

                <div className="min-w-0 flex-1">
                    <div className="flex flex-wrap items-center justify-between gap-3">
                        <h4 className="font-bold text-slate-900">{title}</h4>

                        <span className="text-xl font-black text-emerald-600">
                            {score}
                        </span>
                    </div>

                    <div className="mt-3 h-2 overflow-hidden rounded-full bg-slate-100">
                        <div
                            className="h-full rounded-full bg-emerald-500"
                            style={{ width: score }}
                        />
                    </div>

                    <p className="mt-3 text-sm leading-6 text-slate-500">
                        {description}
                    </p>
                </div>
            </div>
        </div>
    );
}

function ReadinessMetric({ icon, title, score, label }) {
    return (
        <div className="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div className="flex items-start justify-between gap-4">
                <div className="flex items-center gap-3">
                    <span className="text-2xl">{icon}</span>

                    <div>
                        <p className="font-bold text-slate-900">{title}</p>

                        <p className="mt-1 text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Buyer Readiness™
                        </p>
                    </div>
                </div>

                <span className="text-2xl font-black text-emerald-600">
                    {score}
                </span>
            </div>

            <div className="mt-4 h-2 overflow-hidden rounded-full bg-slate-100">
                <div
                    className="h-full rounded-full bg-emerald-500"
                    style={{ width: score }}
                />
            </div>

            <p className="mt-3 text-sm font-semibold text-slate-600">{label}</p>
        </div>
    );
}
