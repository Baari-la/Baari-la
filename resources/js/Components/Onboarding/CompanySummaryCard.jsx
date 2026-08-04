import { usePage } from "@inertiajs/react";

import {
    Building2,
    User,
    Mail,
    Phone,
    Globe,
    MapPin,
    BadgeCheck,
    Award,
    ShieldCheck,
} from "lucide-react";

export default function CompanySummaryCard({ data }) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const completed = [
        data.company_name,
        data.pic_name,
        data.email,
        data.phone,
        data.website,
        data.country,
        data.city,
    ].filter((value) => {
        return (
            value !== null && value !== undefined && String(value).trim() !== ""
        );
    }).length;

    const totalFields = 7;

    const completion = Math.round((completed / totalFields) * 100);

    const passportStatus =
        completion >= 100
            ? isEn
                ? "Complete"
                : "Lengkap"
            : isEn
              ? "Draft"
              : "Draft";

    const readiness =
        completion >= 90
            ? isEn
                ? "Excellent"
                : "Sangat Baik"
            : completion >= 70
              ? isEn
                  ? "Good"
                  : "Baik"
              : completion >= 40
                ? isEn
                    ? "Fair"
                    : "Cukup"
                : isEn
                  ? "Getting Started"
                  : "Baru Dimulai";

    const readinessColor =
        completion >= 90
            ? "text-emerald-600"
            : completion >= 70
              ? "text-blue-600"
              : completion >= 40
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
                            Digital Company Passport™
                        </h2>

                        <p className="text-sm text-slate-500">
                            {isEn ? "Live Preview" : "Pratinjau Langsung"}
                        </p>
                    </div>
                </div>

                <div className="mt-8 space-y-4">
                    <SummaryRow
                        icon={Building2}
                        label={isEn ? "Company" : "Perusahaan"}
                        value={data.company_name || "-"}
                    />

                    <SummaryRow
                        icon={User}
                        label="PIC"
                        value={data.pic_name || "-"}
                    />

                    <SummaryRow
                        icon={Mail}
                        label="Email"
                        value={data.email || "-"}
                    />

                    <SummaryRow
                        icon={Phone}
                        label={isEn ? "Phone" : "Telepon"}
                        value={data.phone || "-"}
                    />

                    <SummaryRow
                        icon={Globe}
                        label="Website"
                        value={data.website || "-"}
                    />

                    <SummaryRow
                        icon={MapPin}
                        label={isEn ? "Country" : "Negara"}
                        value={data.country || "-"}
                    />

                    <SummaryRow
                        icon={MapPin}
                        label={isEn ? "Province" : "Provinsi"}
                        value={data.province || "-"}
                    />

                    <SummaryRow
                        icon={MapPin}
                        label={isEn ? "City" : "Kota"}
                        value={data.city || "-"}
                    />
                </div>
            </div>

            {/* Identity Score */}

            <div className="rounded-3xl border border-emerald-200 bg-emerald-50 p-8">
                <div className="flex items-center gap-2">
                    <Award className="h-6 w-6 text-emerald-600" />

                    <h3 className="font-black text-emerald-700">
                        Company Identity Score™
                    </h3>
                </div>

                <div className="mt-5 text-center">
                    <div className="text-5xl font-black text-emerald-600">
                        {completion}%
                    </div>

                    <div className="mt-3 text-sm text-slate-600">
                        {isEn ? "Profile Completion" : "Kelengkapan Profil"}
                    </div>
                    <div className="mt-2 text-sm text-slate-500">
                        {isEn
                            ? `${completed} of ${totalFields} fields completed`
                            : `${completed} dari ${totalFields} data telah dilengkapi`}
                    </div>
                </div>

                <div className="mt-6 h-3 overflow-hidden rounded-full bg-emerald-100">
                    <div
                        className="h-full rounded-full bg-emerald-500 transition-all duration-500"
                        style={{
                            width: `${completion}%`,
                        }}
                    />
                </div>
            </div>

            {/* Passport Status */}

            <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                <div className="flex items-center gap-2">
                    <ShieldCheck className="h-6 w-6 text-indigo-600" />

                    <h3 className="font-black">Passport Status</h3>
                </div>

                <div className="mt-5">
                    <span
                        className={`
            inline-flex
            items-center
            rounded-full
            px-4
            py-2
            text-sm
            font-bold

            ${
                completion >= 100
                    ? "bg-emerald-100 text-emerald-700"
                    : "bg-amber-100 text-amber-700"
            }
        `}
                    >
                        {passportStatus}
                    </span>
                </div>

                <p className="mt-3 text-sm leading-6 text-slate-600">
                    {isEn
                        ? "Complete your company identity to activate your Digital Company Passport™."
                        : "Lengkapi identitas perusahaan untuk mengaktifkan Digital Company Passport™."}
                </p>
            </div>

            {/* Buyer Readiness */}

            <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                <div className="flex items-center gap-2">
                    <BadgeCheck className={`h-6 w-6 ${readinessColor}`} />

                    <h3 className="font-black">Buyer Readiness™</h3>
                </div>

                <div className={`mt-5 text-2xl font-black ${readinessColor}`}>
                    {readiness}
                </div>
                <div className="mt-4 h-2 overflow-hidden rounded-full bg-slate-200">
                    <div
                        className="h-full rounded-full bg-emerald-500"
                        style={{
                            width: `${completion}%`,
                        }}
                    />
                </div>
                <p className="mt-3 text-sm leading-6 text-slate-600">
                    {isEn
                        ? "Your company identity helps buyers discover and trust your business within the DIGESTEX ecosystem."
                        : "Identitas perusahaan membantu buyer menemukan dan mempercayai perusahaan Anda di ekosistem DIGESTEX."}
                </p>
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

            <span
                className="
        max-w-[180px]
        text-right
        font-semibold
        text-slate-700
        break-words
    "
            >
                {value}
            </span>
        </div>
    );
}
