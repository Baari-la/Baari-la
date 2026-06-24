import React from "react";
import { useForm } from "@inertiajs/react";

export default function ImportKemendag() {
    // 💡 Menggunakan Inertia Form Helper
    const { data, setData, post, processing, errors, reset } = useForm({
        tahun: "2026",
        file_excel: null,
    });

    const handleUpload = (e) => {
        e.preventDefault();

        post("/admin/import-kemendag", {
            forceFormData: true,
            onSuccess: (page) => {
                // 💡 Inertia akan membawa flash message dari Laravel ke page.props.flash
                alert("🎉 Sukses: Data berhasil ditransformasi!");
                reset("file_excel");
            },
            onError: (err) => {
                console.error(err);
                alert(
                    "❌ Gagal mengunggah data. Periksa kembali format Excel Anda.",
                );
            },
        });
    };

    return (
        <div className="p-6 bg-white rounded-lg shadow-md max-w-md mx-auto mt-10">
            <h2 className="text-xl font-bold mb-4 text-gray-800">
                Import Data Kemendag (SOP Opsi A)
            </h2>

            <form onSubmit={handleUpload} className="space-y-4">
                {/* Input Pilihan Tahun */}
                <div>
                    <label className="block text-sm font-medium text-gray-700 mb-1">
                        Tahun Data
                    </label>
                    <select
                        value={data.tahun}
                        onChange={(e) => setData("tahun", e.target.value)}
                        className="w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                        <option value="2027">2027</option>
                    </select>
                    {errors.tahun && (
                        <p className="text-red-500 text-xs mt-1">
                            {errors.tahun}
                        </p>
                    )}
                </div>

                {/* Input File Excel */}
                <div>
                    <label className="block text-sm font-medium text-gray-700 mb-1">
                        File Excel (.xlsx / .csv)
                    </label>
                    <input
                        type="file"
                        accept=".xlsx, .xls, .csv"
                        onChange={(e) =>
                            setData("file_excel", e.target.files[0])
                        }
                        className="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                    />
                    <p className="text-xs text-gray-400 mt-1">
                        *Pastikan header sudah dirapikan menjadi 1 baris.
                    </p>
                    {errors.file_excel && (
                        <p className="text-red-500 text-xs mt-1">
                            {errors.file_excel}
                        </p>
                    )}
                </div>

                {/* Tombol Submit */}
                <button
                    type="submit"
                    disabled={processing}
                    className={`w-full text-white font-bold py-2 px-4 rounded-md transition duration-200 ${
                        processing
                            ? "bg-gray-400 cursor-not-allowed"
                            : "bg-blue-600 hover:bg-blue-700"
                    }`}
                >
                    {processing
                        ? "Sedang Memproses Data..."
                        : "Mulai Impor Data"}
                </button>
            </form>
        </div>
    );
}
