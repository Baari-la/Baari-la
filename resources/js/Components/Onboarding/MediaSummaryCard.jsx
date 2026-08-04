import { usePage } from "@inertiajs/react";

import {
    Building2,
    Image,
    Package,
    FileText,
    Video,
    BadgeCheck,
    Award,
    Globe,
} from "lucide-react";

export default function MediaSummaryCard({ company, data }) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const branding = data?.branding || {};
    const factoryGallery = data?.factory_gallery || [];
    const productGallery = data?.product_gallery || [];
    const documents = data?.documents || {};
    const videos = data?.videos || {};

    const uploadedDocuments =
        (documents.company_brochure ? 1 : 0) +
        (documents.product_catalog ? 1 : 0) +
        (documents.certifications?.length || 0);

    const uploadedVideos = Object.values(videos).filter(Boolean).length;

    const completedItems = [
        branding.company_logo,
        branding.cover_image,
        factoryGallery.length > 0,
        productGallery.length > 0,
        uploadedDocuments > 0,
        uploadedVideos > 0,
    ].filter(Boolean).length;

    const visibilityScore = Math.round((completedItems / 6) * 100);

    const buyerReadiness =
        visibilityScore >= 90
            ? isEn
                ? "Excellent"
                : "Sangat Baik"
            : visibilityScore >= 70
              ? isEn
                  ? "Good"
                  : "Baik"
              : visibilityScore >= 40
                ? isEn
                    ? "Fair"
                    : "Cukup"
                : isEn
                  ? "Getting Started"
                  : "Baru Dimulai";

    const badgeColor =
        visibilityScore >= 90
            ? "text-emerald-600"
            : visibilityScore >= 70
              ? "text-blue-600"
              : visibilityScore >= 40
                ? "text-amber-600"
                : "text-slate-500";

    return (
        <div className="sticky top-6 space-y-6">
            {/* Passport */}

            <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                <div className="flex items-center gap-3">
                    <Building2 className="h-7 w-7 text-indigo-600" />

                    <div>
                        <h2 className="text-xl font-black">
                            Digital Media Passport™
                        </h2>

                        <p className="text-sm text-slate-500">
                            {isEn ? "Live Preview" : "Pratinjau Langsung"}
                        </p>
                    </div>
                </div>

                <div className="mt-8 space-y-4">
                    <SummaryRow
                        icon={Image}
                        label={isEn ? "Company Logo" : "Logo Perusahaan"}
                        value={branding.company_logo ? "Uploaded" : "-"}
                    />

                    <SummaryRow
                        icon={Image}
                        label={isEn ? "Cover Image" : "Cover"}
                        value={branding.cover_image ? "Uploaded" : "-"}
                    />

                    <SummaryRow
                        icon={Building2}
                        label={isEn ? "Factory Photos" : "Foto Pabrik"}
                        value={factoryGallery.length}
                    />

                    <SummaryRow
                        icon={Package}
                        label={isEn ? "Product Photos" : "Foto Produk"}
                        value={productGallery.length}
                    />

                    <SummaryRow
                        icon={FileText}
                        label={isEn ? "Documents" : "Dokumen"}
                        value={uploadedDocuments}
                    />

                    <SummaryRow
                        icon={Video}
                        label={isEn ? "Videos" : "Video"}
                        value={uploadedVideos}
                    />
                </div>
            </div>

            {/* Visibility */}

            <div className="rounded-3xl border border-emerald-200 bg-emerald-50 p-8">
                <div className="flex items-center gap-2">
                    <Award className="h-6 w-6 text-emerald-600" />

                    <h3 className="font-black text-emerald-700">
                        Company Visibility Score™
                    </h3>
                </div>

                <div className="mt-5 text-center">
                    <div className="text-5xl font-black text-emerald-600">
                        {visibilityScore}%
                    </div>

                    <div className="mt-3 text-sm text-slate-600">
                        {isEn ? "Profile Completeness" : "Kelengkapan Profil"}
                    </div>
                </div>

                <div className="mt-6 h-3 overflow-hidden rounded-full bg-emerald-100">
                    <div
                        className="h-full rounded-full bg-emerald-500 transition-all duration-500"
                        style={{
                            width: `${visibilityScore}%`,
                        }}
                    />
                </div>
            </div>

            {/* Buyer Readiness */}

            <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                <div className="flex items-center gap-2">
                    <BadgeCheck className={`h-6 w-6 ${badgeColor}`} />

                    <h3 className="font-black">Buyer Readiness™</h3>
                </div>

                <div className={`mt-5 text-2xl font-black ${badgeColor}`}>
                    {buyerReadiness}
                </div>

                <p className="mt-3 text-sm leading-6 text-slate-600">
                    {isEn
                        ? "Complete your media assets to improve visibility, increase buyer confidence, and strengthen Smart Business Matching™."
                        : "Lengkapi media perusahaan Anda untuk meningkatkan visibilitas, kepercayaan buyer, dan memperkuat Smart Business Matching™."}
                </p>
            </div>

            {/* Intelligence */}

            <div className="rounded-3xl bg-indigo-50 p-8">
                <div className="flex items-center gap-2">
                    <Globe className="h-6 w-6 text-indigo-600" />

                    <h3 className="font-black text-indigo-700">
                        Media Intelligence™
                    </h3>
                </div>

                <ul className="mt-5 space-y-3 text-sm text-slate-700">
                    <li>✓ Executive Dashboard™</li>
                    <li>✓ Digital Company Passport™</li>
                    <li>✓ Company Visibility Score™</li>
                    <li>✓ Smart Business Matching™</li>
                    <li>✓ Buyer Readiness™</li>
                    <li>✓ Company Intelligence™</li>
                </ul>
            </div>
        </div>
    );
}

function SummaryRow({ icon: Icon, label, value }) {
    return (
        <div className="flex items-center justify-between border-b border-slate-100 pb-3">
            <div className="flex items-center gap-3">
                <Icon className="h-5 w-5 text-slate-400" />

                <span className="text-sm font-medium">{label}</span>
            </div>

            <span className="font-bold text-slate-700">{value}</span>
        </div>
    );
}
