/*
|--------------------------------------------------------------------------
| DIGESTEX Trade Experience Card™
|--------------------------------------------------------------------------
|
| Step 4
| Trade Profile™
|
| Launch Ready Version
|--------------------------------------------------------------------------
*/

import { usePage } from "@inertiajs/react";

import { Globe, Calendar, Award } from "lucide-react";

import TextField from "@/Components/Common/Forms/TextField";

export default function TradeExperienceCard({ data, setData }) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const t = (en, id) => (isEn ? en : id);

    /*
    |--------------------------------------------------------------------------
    | Show only when company exports
    |--------------------------------------------------------------------------
    */

    const isExporter = data.trade_roles?.includes("export");

    if (!isExporter) {
        return null;
    }

    const experiences = [
        {
            value: "ACTIVE",
            label: t("Active Exporter", "Eksportir Aktif"),
        },

        {
            value: "OCCASIONAL",
            label: t("Occasional Exporter", "Eksportir Insidental"),
        },

        {
            value: "NEW",
            label: t("New Exporter", "Eksportir Baru"),
        },
    ];

    return (
        <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            {/* ------------------------------------------------ */}
            {/* Header                                           */}
            {/* ------------------------------------------------ */}

            <div className="flex items-center gap-3">
                <div className="rounded-2xl bg-emerald-100 p-3 text-emerald-700">
                    <Globe className="h-6 w-6" />
                </div>

                <div>
                    <h2 className="text-2xl font-black text-slate-900">
                        {t("Export Experience™", "Pengalaman Ekspor™")}
                    </h2>

                    <p className="mt-1 text-slate-600">
                        {t(
                            "Tell buyers about your export experience.",
                            "Berikan gambaran mengenai pengalaman ekspor perusahaan Anda.",
                        )}
                    </p>
                </div>
            </div>

            {/* ------------------------------------------------ */}
            {/* Experience                                       */}
            {/* ------------------------------------------------ */}

            <div className="mt-8">
                <label className="mb-3 block text-sm font-semibold text-slate-700">
                    {t("Export Experience", "Pengalaman Ekspor")}
                </label>

                <div className="grid gap-4 md:grid-cols-3">
                    {experiences.map((item) => {
                        const selected = data.export_experience === item.value;

                        return (
                            <button
                                key={item.value}
                                type="button"
                                onClick={() =>
                                    setData("export_experience", item.value)
                                }
                                className={`
                                    rounded-2xl
                                    border
                                    p-5
                                    text-left
                                    transition

                                    ${
                                        selected
                                            ? "border-emerald-500 bg-emerald-50"
                                            : "border-slate-200 hover:border-emerald-300"
                                    }
                                `}
                            >
                                <Award className="mb-4 h-6 w-6 text-emerald-600" />

                                <div className="font-bold text-slate-900">
                                    {item.label}
                                </div>
                            </button>
                        );
                    })}
                </div>
            </div>

            {/* ------------------------------------------------ */}
            {/* Export Since                                     */}
            {/* ------------------------------------------------ */}

            <div className="mt-8 grid gap-6 md:grid-cols-2">
                <div>
                    <div className="mb-2 flex items-center gap-2">
                        <Calendar className="h-5 w-5 text-slate-500" />

                        <span className="font-semibold text-slate-700">
                            {t("Export Since", "Mulai Ekspor")}
                        </span>
                    </div>

                    <TextField
                        type="number"
                        value={data.export_since}
                        placeholder={t("Example: 2019", "Contoh: 2019")}
                        onChange={(e) =>
                            setData("export_since", e.target.value)
                        }
                    />
                </div>
            </div>

            {/* ------------------------------------------------ */}
            {/* Information Box                                  */}
            {/* ------------------------------------------------ */}

            <div className="mt-8 rounded-2xl border border-blue-100 bg-blue-50 p-5">
                <div className="font-bold text-blue-700">
                    {t("Buyer Insight", "Informasi untuk Buyer")}
                </div>

                <p className="mt-2 leading-7 text-slate-600">
                    {t(
                        "Export experience helps buyers understand your international market exposure. Detailed export information such as HS Code, destinations, and annual trade volume can be completed after launch.",
                        "Pengalaman ekspor membantu buyer memahami pengalaman perusahaan di pasar internasional. Informasi detail seperti HS Code, negara tujuan, dan volume perdagangan dapat dilengkapi setelah peluncuran DIGESTEX.",
                    )}
                </p>
            </div>
        </div>
    );
}
