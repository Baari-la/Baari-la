/*
|--------------------------------------------------------------------------
| DIGESTEX Trade Countries Card™
|--------------------------------------------------------------------------
|
| Step 4
| Trade Profile™
|
| Launch Ready Version
|
| Country source:
| app/database/master-data/countries.json
|
|--------------------------------------------------------------------------
*/

import { usePage } from "@inertiajs/react";

import { Globe, Ship, PlusCircle, Trash2 } from "lucide-react";

export default function TradeCountriesCard({
    data,
    setData,
    errors = {},
    countries = [],
}) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const t = (en, id) => (isEn ? en : id);

    const isExporter = data.trade_roles?.includes("export");

    const isImporter = data.trade_roles?.includes("import");

    /*
    |--------------------------------------------------------------------------
    | DIGESTEX Country Master
    |--------------------------------------------------------------------------
    */

    const activeCountries = countries
        .filter((country) => country.is_active)
        .sort((a, b) => {
            const nameA = isEn ? a.country_name_en : a.country_name_id;

            const nameB = isEn ? b.country_name_en : b.country_name_id;

            return nameA.localeCompare(nameB);
        });

    /*
    |--------------------------------------------------------------------------
    | Country Label
    |--------------------------------------------------------------------------
    */

    const countryLabel = (country) => {
        const name = isEn ? country.country_name_en : country.country_name_id;

        return `${country.flag_emoji ?? ""} ${name}`;
    };

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

    /*
    |--------------------------------------------------------------------------
    | Do not display card if company has no trade role
    |--------------------------------------------------------------------------
    */

    if (!isExporter && !isImporter) {
        return null;
    }

    return (
        <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            {/* ===========================================================
                Header
            =========================================================== */}

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
                        {exportCountries.map((countryCode, index) => (
                            <div key={`export-${index}`} className="flex gap-3">
                                <div className="min-w-0 flex-1">
                                    <select
                                        value={countryCode}
                                        onChange={(e) =>
                                            updateExport(index, e.target.value)
                                        }
                                        className="
                                            w-full
                                            rounded-xl
                                            border
                                            border-slate-300
                                            bg-white
                                            px-4
                                            py-3
                                            text-slate-700
                                            outline-none
                                            transition
                                            focus:border-emerald-500
                                            focus:ring-2
                                            focus:ring-emerald-100
                                        "
                                    >
                                        <option value="">
                                            {t(
                                                "Select export destination country",
                                                "Pilih negara tujuan ekspor",
                                            )}
                                        </option>

                                        {activeCountries.map((country) => (
                                            <option
                                                key={country.country_code}
                                                value={country.country_code}
                                            >
                                                {countryLabel(country)}
                                            </option>
                                        ))}
                                    </select>
                                </div>

                                <button
                                    type="button"
                                    onClick={() => removeExport(index)}
                                    className="
                                        shrink-0
                                        rounded-xl
                                        border
                                        border-red-200
                                        px-4
                                        text-red-600
                                        transition
                                        hover:bg-red-50
                                    "
                                    title={t("Remove country", "Hapus negara")}
                                >
                                    <Trash2 className="h-5 w-5" />
                                </button>
                            </div>
                        ))}

                        <button
                            type="button"
                            onClick={addExport}
                            className="
                                inline-flex
                                items-center
                                gap-2
                                rounded-xl
                                border
                                border-dashed
                                border-emerald-400
                                px-5
                                py-3
                                font-semibold
                                text-emerald-700
                                transition
                                hover:bg-emerald-50
                            "
                        >
                            <PlusCircle className="h-5 w-5" />

                            {t("Add Export Country", "Tambah Negara Ekspor")}
                        </button>

                        {errors["export_countries"] && (
                            <p className="text-sm font-medium text-red-600">
                                {errors["export_countries"]}
                            </p>
                        )}
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
                        {importCountries.map((countryCode, index) => (
                            <div key={`import-${index}`} className="flex gap-3">
                                <div className="min-w-0 flex-1">
                                    <select
                                        value={countryCode}
                                        onChange={(e) =>
                                            updateImport(index, e.target.value)
                                        }
                                        className="
                                            w-full
                                            rounded-xl
                                            border
                                            border-slate-300
                                            bg-white
                                            px-4
                                            py-3
                                            text-slate-700
                                            outline-none
                                            transition
                                            focus:border-indigo-500
                                            focus:ring-2
                                            focus:ring-indigo-100
                                        "
                                    >
                                        <option value="">
                                            {t(
                                                "Select import origin country",
                                                "Pilih negara asal impor",
                                            )}
                                        </option>

                                        {activeCountries.map((country) => (
                                            <option
                                                key={country.country_code}
                                                value={country.country_code}
                                            >
                                                {countryLabel(country)}
                                            </option>
                                        ))}
                                    </select>
                                </div>

                                <button
                                    type="button"
                                    onClick={() => removeImport(index)}
                                    className="
                                        shrink-0
                                        rounded-xl
                                        border
                                        border-red-200
                                        px-4
                                        text-red-600
                                        transition
                                        hover:bg-red-50
                                    "
                                    title={t("Remove country", "Hapus negara")}
                                >
                                    <Trash2 className="h-5 w-5" />
                                </button>
                            </div>
                        ))}

                        <button
                            type="button"
                            onClick={addImport}
                            className="
                                inline-flex
                                items-center
                                gap-2
                                rounded-xl
                                border
                                border-dashed
                                border-indigo-400
                                px-5
                                py-3
                                font-semibold
                                text-indigo-700
                                transition
                                hover:bg-indigo-50
                            "
                        >
                            <PlusCircle className="h-5 w-5" />

                            {t(
                                "Add Import Country",
                                "Tambah Negara Asal Impor",
                            )}
                        </button>

                        {errors["import_countries"] && (
                            <p className="text-sm font-medium text-red-600">
                                {errors["import_countries"]}
                            </p>
                        )}
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
