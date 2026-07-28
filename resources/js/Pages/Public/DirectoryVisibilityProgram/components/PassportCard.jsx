import {
    Award,
    Building2,
    CheckCircle2,
    Factory,
    Globe,
    MapPin,
    Package,
    ShieldCheck,
    Star,
    Users,
    BarChart3,
    BadgeCheck,
} from "lucide-react";

export default function PassportCard({ isEn = false }) {
    return (
        <div
            className="
                overflow-hidden
                rounded-3xl
                border
                border-slate-200
                bg-white
                shadow-2xl
            "
        >
            {/* ==========================================================
                HEADER
            ========================================================== */}

            <div
                className="
                    bg-gradient-to-r
                    from-slate-900
                    via-indigo-900
                    to-slate-900
                    p-8
                    text-white
                "
            >
                <div className="flex flex-wrap items-start justify-between gap-6">
                    <div>
                        <p className="text-xs uppercase tracking-[0.3em] text-emerald-300">
                            DIGITAL COMPANY PASSPORT™
                        </p>

                        <h3 className="mt-3 text-3xl font-black">
                            PT DIGESTEX TEXTILE INDONESIA
                        </h3>

                        <p className="mt-3 text-slate-300">
                            Integrated Textile Manufacturer • Exporter
                        </p>
                    </div>

                    <div className="space-y-3">
                        <div
                            className="
                                inline-flex
                                items-center
                                gap-2
                                rounded-full
                                bg-emerald-500/20
                                px-4
                                py-2
                                text-sm
                                font-semibold
                                text-emerald-300
                            "
                        >
                            <BadgeCheck className="h-4 w-4" />
                            VERIFIED COMPANY
                        </div>

                        <div
                            className="
                                rounded-xl
                                bg-white/10
                                px-4
                                py-3
                                backdrop-blur
                            "
                        >
                            <div className="text-xs uppercase tracking-wide text-slate-300">
                                Visibility Score™
                            </div>

                            <div className="mt-1 text-3xl font-black">
                                92
                                <span className="text-lg text-emerald-300">
                                    /100
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* ==========================================================
                QUICK STATUS
            ========================================================== */}

            <div className="grid border-b border-slate-200 bg-slate-50 md:grid-cols-3">
                <StatusBox
                    icon={ShieldCheck}
                    label={isEn ? "Verification" : "Verifikasi"}
                    value="Verified"
                />

                <StatusBox
                    icon={BarChart3}
                    label={isEn ? "Profile Completion" : "Kelengkapan Profil"}
                    value="95%"
                />

                <StatusBox
                    icon={Star}
                    label={isEn ? "Membership" : "Keanggotaan"}
                    value="Gold Visibility"
                />
            </div>

            {/* ==========================================================
                BODY
            ========================================================== */}

            <div className="grid gap-10 p-8 lg:grid-cols-2">
                <div className="space-y-5">
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
                        icon={Globe}
                        label={isEn ? "Export Markets" : "Pasar Ekspor"}
                        value="Asia • Europe • USA"
                    />

                    <InfoRow
                        icon={Factory}
                        label={
                            isEn ? "Production Capacity" : "Kapasitas Produksi"
                        }
                        value="2.5 Million meters / month"
                    />
                </div>

                <div className="space-y-5">
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
                        icon={Users}
                        label={isEn ? "Employees" : "Tenaga Kerja"}
                        value="850+"
                    />

                    <InfoRow
                        icon={ShieldCheck}
                        label={isEn ? "Business Matching" : "Business Matching"}
                        value="Ready"
                    />
                </div>
            </div>

            {/* ==========================================================
                BUSINESS READINESS
            ========================================================== */}

            <div className="border-t border-slate-200 bg-slate-50 p-8">
                <h4 className="font-bold text-slate-900">
                    {isEn ? "Business Readiness" : "Kesiapan Bisnis"}
                </h4>

                <div className="mt-5 flex flex-wrap gap-3">
                    {[
                        "Verified Company",
                        "Export Ready",
                        "Business Matching Ready",
                        "Executive Intelligence",
                        "Digital Passport",
                        "Global Visibility",
                    ].map((item) => (
                        <span
                            key={item}
                            className="
                                rounded-full
                                bg-emerald-100
                                px-4
                                py-2
                                text-sm
                                font-medium
                                text-emerald-700
                            "
                        >
                            {item}
                        </span>
                    ))}
                </div>

                <div className="mt-8 flex items-center gap-3 text-sm text-slate-500">
                    <CheckCircle2 className="h-5 w-5 text-emerald-500" />

                    {isEn
                        ? "Illustration of a Digital Company Passport™"
                        : "Ilustrasi Digital Company Passport™"}
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
            <div
                className="
                    flex
                    h-11
                    w-11
                    items-center
                    justify-center
                    rounded-xl
                    bg-emerald-100
                "
            >
                <Icon className="h-5 w-5 text-emerald-600" />
            </div>

            <div>
                <p className="text-sm text-slate-500">{label}</p>

                <p className="font-semibold text-slate-900">{value}</p>
            </div>
        </div>
    );
}

function StatusBox({ icon: Icon, label, value }) {
    return (
        <div className="flex items-center gap-4 border-b border-slate-200 p-6 md:border-b-0 md:border-r last:border-r-0">
            <div
                className="
                    flex
                    h-12
                    w-12
                    items-center
                    justify-center
                    rounded-xl
                    bg-emerald-100
                "
            >
                <Icon className="h-6 w-6 text-emerald-600" />
            </div>

            <div>
                <p className="text-xs uppercase tracking-wide text-slate-500">
                    {label}
                </p>

                <p className="font-bold text-slate-900">{value}</p>
            </div>
        </div>
    );
}
