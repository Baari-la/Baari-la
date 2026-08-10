/*
|--------------------------------------------------------------------------
| DIGESTEX Buyer Decision Dashboard™
|--------------------------------------------------------------------------
|
| Live Buyer Readiness Dashboard
|
| Launch Ready Version
|--------------------------------------------------------------------------
*/

import { useMemo } from "react";
import { usePage } from "@inertiajs/react";

import { ShieldCheck, Globe, Eye, CheckCircle2, Clock3 } from "lucide-react";

export default function BuyerDecisionDashboard({ company, data }) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const t = (en, id) => (isEn ? en : id);

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    const hasValues = (value) => {
        if (!Array.isArray(value)) {
            return false;
        }

        return value.some(
            (item) =>
                item !== null &&
                item !== undefined &&
                String(item).trim() !== "",
        );
    };

    const hasText = (value) => {
        return (
            value !== null && value !== undefined && String(value).trim() !== ""
        );
    };

    /*
    |--------------------------------------------------------------------------
    | Buyer Trust Score™
    |--------------------------------------------------------------------------
    | Launch Ready scoring.
    |
    | This is intentionally simple.
    | Future versions can replace this with backend Trade Profile Score™.
    |--------------------------------------------------------------------------
    */

    const buyerTrust = useMemo(() => {
        let score = 50;

        if (hasValues(data.trade_roles)) {
            score += 8;
        }

        if (hasText(data.export_experience)) {
            score += 8;
        }

        if (hasValues(data.export_countries)) {
            score += 10;
        }

        if (hasValues(data.import_countries)) {
            score += 6;
        }

        if (hasValues(data.main_industries)) {
            score += 8;
        }

        return Math.min(score, 100);
    }, [data]);

    /*
    |--------------------------------------------------------------------------
    | Visibility Score™
    |--------------------------------------------------------------------------
    */

    const visibility = useMemo(() => {
        return Math.min(Math.round(buyerTrust * 0.9), 100);
    }, [buyerTrust]);

    /*
    |--------------------------------------------------------------------------
    | Buyer Trust Rating
    |--------------------------------------------------------------------------
    */

    const stars = useMemo(() => {
        if (buyerTrust >= 90) return "★★★★★";
        if (buyerTrust >= 80) return "★★★★☆";
        if (buyerTrust >= 70) return "★★★☆☆";
        if (buyerTrust >= 60) return "★★☆☆☆";

        return "★☆☆☆☆";
    }, [buyerTrust]);

    /*
    |--------------------------------------------------------------------------
    | Buyer Readiness
    |--------------------------------------------------------------------------
    */

    const readiness = {
        tradeRole: hasValues(data.trade_roles),

        exportExperience: hasText(data.export_experience),

        exportCountries: hasValues(data.export_countries),

        supplyChain: hasValues(data.main_industries),
    };

    /*
    |--------------------------------------------------------------------------
    | Company Name
    |--------------------------------------------------------------------------
    */

    const companyName =
        company?.company_name ??
        company?.nama_perusahaan ??
        company?.canonical_name ??
        "-";

    return (
        <div className="space-y-6">
            {/* ==========================================================
                BUYER DECISION DASHBOARD
            ========================================================== */}

            <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                <div className="flex items-center gap-3">
                    <ShieldCheck className="h-7 w-7 text-emerald-600" />

                    <div>
                        <div className="text-xl font-black text-slate-900">
                            Buyer Decision Dashboard™
                        </div>

                        <div className="text-sm text-slate-500">
                            {t("Launch Ready", "Siap Launch")}
                        </div>
                    </div>
                </div>

                {/* Company */}

                <div className="mt-8">
                    <div className="text-xs font-bold uppercase tracking-widest text-slate-500">
                        {t("Company", "Perusahaan")}
                    </div>

                    <div className="mt-2 text-xl font-black text-slate-900">
                        {companyName}
                    </div>
                </div>

                {/* Buyer Trust */}

                <div className="mt-8 rounded-2xl bg-emerald-50 p-6">
                    <div className="text-sm font-bold text-emerald-700">
                        Buyer Trust Score™
                    </div>

                    <div className="mt-3 flex items-end gap-3">
                        <div className="text-5xl font-black text-emerald-700">
                            {buyerTrust}
                        </div>

                        <div className="pb-2 text-sm text-slate-500">/100</div>
                    </div>

                    <div className="mt-3 text-xl">{stars}</div>
                </div>

                {/* Visibility */}

                <div className="mt-5 rounded-2xl border border-slate-200 p-5">
                    <div className="flex items-center justify-between">
                        <div className="flex items-center gap-2">
                            <Eye className="h-5 w-5 text-indigo-600" />

                            <span className="font-semibold">Visibility™</span>
                        </div>

                        <div className="font-black text-indigo-700">
                            {visibility}%
                        </div>
                    </div>
                </div>
            </div>

            {/* ==========================================================
                BUYER READINESS
            ========================================================== */}

            <div className="rounded-3xl border border-slate-200 bg-white p-8">
                <div className="text-lg font-black">
                    {t("Buyer Readiness", "Kesiapan Buyer")}
                </div>

                <div className="mt-6 space-y-4">
                    <Status
                        done={readiness.tradeRole}
                        title={t("Trade Role", "Peran Perdagangan")}
                        isEn={isEn}
                    />

                    <Status
                        done={readiness.exportExperience}
                        title={t("Export Experience", "Pengalaman Ekspor")}
                        isEn={isEn}
                    />

                    <Status
                        done={readiness.exportCountries}
                        title={t("Export Countries", "Negara Ekspor")}
                        isEn={isEn}
                    />

                    <Status
                        done={readiness.supplyChain}
                        title={t("Supply Chain", "Supply Chain")}
                        isEn={isEn}
                    />
                </div>
            </div>

            {/* ==========================================================
                SOURCING HUB
            ========================================================== */}

            <div className="rounded-3xl border border-amber-200 bg-amber-50 p-8">
                <div className="flex items-center gap-3">
                    <Globe className="h-6 w-6 text-amber-600" />

                    <div className="font-black text-amber-700">
                        Sourcing Hub™
                    </div>
                </div>

                <div className="mt-4 leading-7 text-slate-600">
                    {t(
                        "Companies with complete Trade Profiles will receive higher visibility when Global Buyer RFQ launches.",
                        "Perusahaan dengan Trade Profile yang lengkap akan memperoleh visibilitas lebih tinggi saat Global Buyer RFQ diluncurkan.",
                    )}
                </div>

                <div className="mt-6 rounded-xl bg-white p-4">
                    <div className="font-bold text-slate-700">Coming Soon</div>

                    <div className="mt-2 text-sm text-slate-500">
                        ✓ Global Buyer RFQ™
                        <br />
                        ✓ Smart Business Matching™
                        <br />
                        ✓ Verified Supplier Ranking™
                        <br />✓ Buyer Shortlist™
                    </div>
                </div>
            </div>
        </div>
    );
}

/*
|--------------------------------------------------------------------------
| Status Component
|--------------------------------------------------------------------------
*/

function Status({ done, title, isEn }) {
    return (
        <div className="flex items-center justify-between">
            <div className="text-slate-700">{title}</div>

            {done ? (
                <div className="flex items-center gap-2 text-emerald-600">
                    <CheckCircle2 className="h-5 w-5" />

                    <span className="text-sm font-bold">
                        {isEn ? "Complete" : "Lengkap"}
                    </span>
                </div>
            ) : (
                <div className="flex items-center gap-2 text-amber-600">
                    <Clock3 className="h-5 w-5" />

                    <span className="text-sm font-bold">
                        {isEn ? "Pending" : "Belum Lengkap"}
                    </span>
                </div>
            )}
        </div>
    );
}
