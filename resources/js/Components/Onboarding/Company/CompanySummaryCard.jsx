/*
|--------------------------------------------------------------------------
| DIGESTEX Company Summary Card™
|--------------------------------------------------------------------------
|
| Live summary of Company Identity
| and Business Locations driven dynamically by CompanyBlueprint.
|
|--------------------------------------------------------------------------
*/

import { useMemo } from "react";
import { usePage } from "@inertiajs/react";
import { Building2, MapPin } from "lucide-react";

export default function CompanySummaryCard({
    blueprint,
    company = {},
    locations = [],
}) {
    const { locale } = usePage().props;
    const isEn = locale === "en";

    // Helper Translation
    const t = (en, id) => (isEn ? en : id);

    // 1. Safe Company Name Memoization
    const companyName = useMemo(() => {
        return company.company_name ?? company.nama_perusahaan ?? "-";
    }, [company]);

    // 2. Single-pass Aggregation untuk Hitungan Lokasi per Kategori
    const locationCounts = useMemo(() => {
        return locations.reduce((acc, location) => {
            if (location.location_type) {
                acc[location.location_type] =
                    (acc[location.location_type] ?? 0) + 1;
            }
            return acc;
        }, {});
    }, [locations]);

    // 3. Dynamic Summaries dari Blueprint (Eksplisit Dependency Locale)
    const locationSummaries = useMemo(() => {
        return (
            blueprint?.locations?.map((item) => ({
                key: item.key,
                icon: item.icon ?? MapPin,
                label: t(item.title, item.titleId),
                value: locationCounts[item.key] ?? 0,
            })) ?? []
        );
    }, [blueprint, locationCounts, locale]);

    // 4. Hitungan Total Lokasi Aktif Operasional
    const totalActiveLocations = useMemo(() => {
        return locations.filter((location) => location.is_active ?? true)
            .length;
    }, [locations]);

    return (
        <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            {/* Header Card */}
            <h3 className="text-xl font-black text-slate-900">
                {t("Company Summary™", "Ringkasan Perusahaan™")}
            </h3>

            <div className="mt-8 space-y-6">
                {/* Identity: Company Name */}
                <SummaryRow
                    icon={Building2}
                    label={t("Company", "Perusahaan")}
                    value={companyName}
                />

                {/* Separator Line */}
                <hr className="border-slate-100" />

                {/* Dynamic Locations Summary */}
                <div className="space-y-4">
                    {locationSummaries.map((item) => (
                        <SummaryRow
                            key={item.key}
                            icon={item.icon}
                            label={item.label}
                            value={item.value}
                        />
                    ))}
                </div>

                {/* Empty State Banner */}
                {locations.length === 0 && (
                    <div className="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center">
                        <MapPin className="mx-auto mb-3 h-8 w-8 text-slate-400" />
                        <p className="text-sm font-medium text-slate-500">
                            {t(
                                "No business locations configured yet.",
                                "Belum ada lokasi bisnis yang dikonfigurasi.",
                            )}
                        </p>
                    </div>
                )}

                {/* Total Active Locations Divider & Row */}
                <div className="border-t border-slate-200 pt-6">
                    <SummaryRow
                        icon={MapPin}
                        label={t(
                            "Total Active Locations",
                            "Total Lokasi Aktif",
                        )}
                        value={totalActiveLocations}
                        isHighlight
                    />
                </div>
            </div>
        </div>
    );
}

/*
|--------------------------------------------------------------------------
| Sub-component: SummaryRow
|--------------------------------------------------------------------------
*/
function SummaryRow({ icon: Icon, label, value, isHighlight = false }) {
    return (
        <div className="flex items-center justify-between">
            <div className="flex items-center gap-3">
                <div
                    className={`rounded-xl p-2 ${
                        isHighlight
                            ? "bg-emerald-600 text-white"
                            : "bg-emerald-100 text-emerald-700"
                    }`}
                >
                    <Icon className="h-5 w-5" />
                </div>

                <span
                    className={`font-medium ${
                        isHighlight
                            ? "font-bold text-slate-900"
                            : "text-slate-700"
                    }`}
                >
                    {label}
                </span>
            </div>

            <span
                className={`font-black ${
                    isHighlight ? "text-lg text-emerald-600" : "text-slate-900"
                }`}
            >
                {value ?? "-"}
            </span>
        </div>
    );
}
