/*
|--------------------------------------------------------------------------
| DIGESTEX Trade Countries Card™
|--------------------------------------------------------------------------
|
| Step 4
| Trade Profile™
|
| Launch Ready Version
|--------------------------------------------------------------------------
*/

import { usePage } from "@inertiajs/react";

import { Globe, Ship, PlusCircle, Trash2 } from "lucide-react";

import TextField from "@/Components/Common/Forms/TextField";

export default function TradeCountriesCard({ data, setData }) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const t = (en, id) => (isEn ? en : id);

    const isExporter = data.trade_roles?.includes("export");

    const isImporter = data.trade_roles?.includes("import");

    if (!isExporter && !isImporter) {
        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | Export Countries
    |--------------------------------------------------------------------------
    */

    const exportCountries = data.export_countries ?? [];

    const updateExport = (index, value) => {
        const updated = [...exportCountries];

        updated[index] = value;

        setData("export_countries", updated);
    };

    const addExport = () => {
        setData("export_countries", [...exportCountries, ""]);
    };

    const removeExport = (index) => {
        setData(
            "export_countries",
            exportCountries.filter((_, i) => i !== index),
        );
    };

    /*
    |--------------------------------------------------------------------------
    | Import Countries
    |--------------------------------------------------------------------------
    */

    const importCountries = data.import_countries ?? [];

    const updateImport = (index, value) => {
        const updated = [...importCountries];

        updated[index] = value;

        setData("import_countries", updated);
    };

    const addImport = () => {
        setData("import_countries", [...importCountries, ""]);
    };

    const removeImport = (index) => {
        setData(
            "import_countries",
            importCountries.filter((_, i) => i !== index),
        );
    };

    return (
        <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <h2 className="text-2xl font-black text-slate-900">
                {t("Trade Countries™", "Negara Perdagangan™")}
            </h2>

            <p className="mt-3 text-slate-600">
                {t(
                    "Help buyers understand your international trade coverage.",
                    "Bantu buyer memahami cakupan perdagangan internasional perusahaan Anda.",
                )}
            </p>

            {/* ===========================================================
                Export Countries
            =========================================================== */}

            {isExporter && (
                <div className="mt-10">
                    <div className="mb-5 flex items-center gap-3">
                        <Globe className="h-6 w-6 text-emerald-600" />

                        <h3 className="text-lg font-black">
                            {t(
                                "Export Destination Countries",
                                "Negara Tujuan Ekspor",
                            )}
                        </h3>
                    </div>

                    <div className="space-y-4">
                        {exportCountries.map((country, index) => (
                            <div key={index} className="flex gap-3">
                                <div className="flex-1">
                                    <TextField
                                        value={country}
                                        placeholder={t(
                                            "Example: Japan",
                                            "Contoh: Jepang",
                                        )}
                                        onChange={(e) =>
                                            updateExport(index, e.target.value)
                                        }
                                    />
                                </div>

                                <button
                                    type="button"
                                    onClick={() => removeExport(index)}
                                    className="rounded-xl border border-red-200 px-4 text-red-600 hover:bg-red-50"
                                >
                                    <Trash2 className="h-5 w-5" />
                                </button>
                            </div>
                        ))}

                        <button
                            type="button"
                            onClick={addExport}
                            className="inline-flex items-center gap-2 rounded-xl border border-dashed border-emerald-400 px-5 py-3 font-semibold text-emerald-700 hover:bg-emerald-50"
                        >
                            <PlusCircle className="h-5 w-5" />

                            {t("Add Export Country", "Tambah Negara Ekspor")}
                        </button>
                    </div>
                </div>
            )}

            {/* ===========================================================
                Import Countries
            =========================================================== */}

            {isImporter && (
                <div className="mt-12">
                    <div className="mb-5 flex items-center gap-3">
                        <Ship className="h-6 w-6 text-indigo-600" />

                        <h3 className="text-lg font-black">
                            {t("Import Origin Countries", "Negara Asal Impor")}
                        </h3>
                    </div>

                    <div className="space-y-4">
                        {importCountries.map((country, index) => (
                            <div key={index} className="flex gap-3">
                                <div className="flex-1">
                                    <TextField
                                        value={country}
                                        placeholder={t(
                                            "Example: China",
                                            "Contoh: China",
                                        )}
                                        onChange={(e) =>
                                            updateImport(index, e.target.value)
                                        }
                                    />
                                </div>

                                <button
                                    type="button"
                                    onClick={() => removeImport(index)}
                                    className="rounded-xl border border-red-200 px-4 text-red-600 hover:bg-red-50"
                                >
                                    <Trash2 className="h-5 w-5" />
                                </button>
                            </div>
                        ))}

                        <button
                            type="button"
                            onClick={addImport}
                            className="inline-flex items-center gap-2 rounded-xl border border-dashed border-indigo-400 px-5 py-3 font-semibold text-indigo-700 hover:bg-indigo-50"
                        >
                            <PlusCircle className="h-5 w-5" />

                            {t(
                                "Add Import Country",
                                "Tambah Negara Asal Impor",
                            )}
                        </button>
                    </div>
                </div>
            )}

            {/* ===========================================================
                Information
            =========================================================== */}

            <div className="mt-10 rounded-2xl border border-blue-100 bg-blue-50 p-5">
                <div className="font-bold text-blue-700">
                    {t("Launch Version", "Versi Launch")}
                </div>

                <p className="mt-2 leading-7 text-slate-600">
                    {t(
                        "Only destination and origin countries are required for launch. Detailed trade intelligence such as HS Codes, export volume, import value, and annual statistics will be available in future updates.",
                        "Pada tahap peluncuran hanya diperlukan negara tujuan dan negara asal. Informasi lanjutan seperti HS Code, volume ekspor, nilai impor, dan statistik tahunan akan ditambahkan pada pengembangan berikutnya.",
                    )}
                </p>
            </div>
        </div>
    );
}
