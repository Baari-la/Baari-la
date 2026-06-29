import React from "react";
import { useForm, usePage } from "@inertiajs/react";

export default function ImportKemendag() {
    const { flash } = usePage().props;

    const { data, setData, post, processing, errors, reset } = useForm({
        file_excel: null,
    });

    const handleUpload = (e) => {
        e.preventDefault();

        post(route("admin.import-kemendag"), {
            forceFormData: true,

            preserveScroll: true,

            onSuccess: () => {
                reset("file_excel");
            },
        });
    };

    return (
        <div className="max-w-4xl mx-auto bg-white shadow rounded-xl p-8">
            <div className="mb-8">
                <h1 className="text-2xl font-bold text-gray-800">
                    Trade Statistics Import
                </h1>
                <div className="mt-2 inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                    Source : Kemendag Indonesia
                </div>
                <p className="text-gray-500 mt-2">
                    Upload workbook resmi Kemendag untuk memperbarui database
                    Trade Statistics Digestex.
                </p>
            </div>

            {flash.success && (
                <div className="mb-6 rounded-lg bg-green-100 border border-green-300 p-4 text-green-800">
                    {flash.success}
                </div>
            )}

            <form onSubmit={handleUpload} className="space-y-6">
                <div>
                    <label className="block text-sm font-semibold mb-2">
                        Excel Workbook
                    </label>
                    <input
                        type="file"
                        accept=".xlsx,.xls,.csv"
                        onChange={(e) =>
                            setData("file_excel", e.target.files[0])
                        }
                        className="block w-full rounded-lg border p-2"
                    />
                    {data.file_excel && (
                        <div className="mt-3 rounded-lg bg-gray-100 px-4 py-3">
                            <div className="text-xs uppercase tracking-wide text-gray-500">
                                Selected File
                            </div>
                            <div className="mt-1 font-semibold text-blue-700">
                                📄 {data.file_excel.name}
                            </div>
                        </div>
                    )}
                    <div className="mt-3 text-xs text-gray-500">
                        Supported format: XLSX, XLS, CSV
                    </div>
                    <div className="mt-4 rounded-lg border border-blue-200 bg-blue-50 p-4">
                        <div className="font-semibold text-blue-800">
                            Workbook Structure
                        </div>
                        <div className="mt-3 text-sm text-blue-700 space-y-1">
                            <div>✓ Ekspor 2025</div> <div>✓ Impor 2025</div>
                            <div>✓ Ekspor 2026</div>
                            <div>✓ Impor 2026</div>
                        </div>
                        <div className="mt-3 text-xs text-blue-600">
                            Tahun serta jenis perdagangan akan dideteksi
                            otomatis berdasarkan nama sheet.
                        </div>
                    </div>
                    {errors.file_excel && (
                        <p className="mt-2 text-sm text-red-600">
                            {errors.file_excel}
                        </p>
                    )}
                </div>
                <button
                    type="submit"
                    disabled={processing}
                    className={`w-full rounded-lg py-3 font-bold text-white transition ${processing ? "cursor-not-allowed bg-gray-400" : "bg-blue-600 hover:bg-blue-700"}`}
                >
                    {processing
                        ? "Processing Import..."
                        : "⬆ Import Trade Statistics"}
                </button>
            </form>

            {/* Summary */}

            {flash.summary && (
                <div className="mt-10 border rounded-xl p-6 bg-gray-50">
                    <h2 className="text-lg font-bold mb-4">Import Summary</h2>

                    <div className="grid grid-cols-2 gap-x-10 gap-y-3 text-sm">
                        <div>Sheets Processed</div>
                        <div className="font-bold text-indigo-700">
                            {flash.summary.processed_sheets}
                        </div>
                        <div>Total Rows</div>
                        <div className="font-bold">
                            {flash.summary.total_rows}
                        </div>

                        <div>Inserted</div>
                        <div className="font-bold text-green-700">
                            {flash.summary.inserted}
                        </div>

                        <div>Updated</div>
                        <div className="font-bold text-blue-700">
                            {flash.summary.updated}
                        </div>

                        <div>Skipped</div>
                        <div className="font-bold text-yellow-700">
                            {flash.summary.skipped}
                        </div>

                        {flash.summary.errors?.length > 0 && (
                            <>
                                <div>Errors</div>

                                <div className="font-bold text-red-700">
                                    {flash.summary.errors.length}
                                </div>
                            </>
                        )}
                    </div>
                </div>
            )}
        </div>
    );
}
