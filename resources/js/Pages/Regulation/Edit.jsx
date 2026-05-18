import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, useForm, usePage } from "@inertiajs/react";
import React, { useState } from "react";

export default function Edit({ regulation = {} }) {
    const { auth, locale } = usePage().props;
    const isEn = locale === "en" || auth?.user?.locale === "en";

    const { data, setData, post, processing, errors } = useForm({
        _method: "PUT", // <--- TRIK PENYAMARAN BERKAS BINER LARAVEL RESTFUL
        title: regulation.title || "",
        speaker: regulation.speaker || "",
        category: regulation.category || "Regulasi",
        access_tier: regulation.access_tier || "Member",
        event_date: regulation.event_date || "",
        file: null, // Wadah dokumen PDF baru
    });

    const handleFileChange = (e) => {
        setData("file", e.target.files[0]);
    };

    const handleUpdate = (e) => {
        e.preventDefault();
        // Eksekusi wajib menggunakan metode post() akibat pengiriman file biner
        post(route("regulation.update", regulation.id));
    };

    return (
        <AuthenticatedLayout>
            <Head title="Perbarui Data Regulasi & Materi" />
            <div className="py-12 bg-[#0a192f] min-h-screen text-white">
                <div className="max-w-3xl mx-auto px-6 lg:px-8">
                    <div className="mb-10 border-l-4 border-yellow-500 pl-6">
                        <h1 className="text-3xl font-black uppercase italic text-yellow-500">
                            {isEn
                                ? "Modify Official Document"
                                : "Ubah Dokumen Resmi"}
                        </h1>
                        <p className="text-gray-400 text-xs mt-1">
                            Mengedit Registri: {regulation.title}
                        </p>
                    </div>

                    <form
                        onSubmit={handleUpdate}
                        className="bg-white/5 border border-white/10 p-10 rounded-[40px] shadow-2xl space-y-6 backdrop-blur-md"
                    >
                        {/* KOTAK INTEGRASI UNGGAH FILE PDF REGULASI BARU */}
                        <div className="flex flex-col space-y-3 bg-black/20 p-6 rounded-3xl border border-white/5">
                            <label className="text-[10px] font-black uppercase tracking-widest text-yellow-500">
                                {isEn
                                    ? "Upload New Document PDF (Leave blank to keep current file)"
                                    : "Ganti Dokumen PDF Materi / Regulasi (Kosongkan jika ingin memakai file lama)"}
                            </label>

                            <div className="flex items-center gap-4">
                                <div className="p-3 bg-yellow-500/10 border border-yellow-500/20 text-yellow-500 rounded-xl text-xl">
                                    <i className="fas fa-file-pdf"></i>
                                </div>
                                <div className="flex-1">
                                    <input
                                        type="file"
                                        accept=".pdf"
                                        onChange={handleFileChange}
                                        className="w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[9px] file:font-black file:uppercase file:tracking-widest file:bg-yellow-500 file:text-[#0a192f] hover:file:bg-yellow-400 file:transition-all file:cursor-pointer"
                                    />
                                    {errors.file && (
                                        <span className="text-xs text-red-400 font-bold block mt-1">
                                            ⚠️ {errors.file}
                                        </span>
                                    )}
                                </div>
                            </div>
                            {regulation.file_path && (
                                <p className="text-[9px] text-gray-500 font-mono italic mt-1">
                                    ✓ Berkas aktif terdaftar: storage/
                                    {regulation.file_path}
                                </p>
                            )}
                        </div>

                        {/* Judul Dokumen */}
                        <div className="flex flex-col space-y-2">
                            <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                Judul Regulasi / Materi Presentasi
                            </label>
                            <input
                                type="text"
                                value={data.title}
                                onChange={(e) =>
                                    setData("title", e.target.value)
                                }
                                className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-yellow-500 focus:outline-none w-full shadow-inner"
                            />
                            {errors.title && (
                                <span className="text-xs text-red-400 font-bold">
                                    ⚠️ {errors.title}
                                </span>
                            )}
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {/* Instansi Sumber */}
                            <div className="flex flex-col space-y-2">
                                <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    Pembicara / Instansi Sumber
                                </label>
                                <input
                                    type="text"
                                    value={data.speaker}
                                    onChange={(e) =>
                                        setData("speaker", e.target.value)
                                    }
                                    className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-yellow-500 focus:outline-none w-full"
                                />
                                {errors.speaker && (
                                    <span className="text-xs text-red-400 font-bold">
                                        ⚠️ {errors.speaker}
                                    </span>
                                )}
                            </div>

                            {/* Tanggal Acara */}
                            <div className="flex flex-col space-y-2">
                                <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    Tanggal Pengesahan / Acara
                                </label>
                                <input
                                    type="date"
                                    value={data.event_date}
                                    onChange={(e) =>
                                        setData("event_date", e.target.value)
                                    }
                                    className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-yellow-500 focus:outline-none w-full"
                                />
                                {errors.event_date && (
                                    <span className="text-xs text-red-400 font-bold">
                                        ⚠️ {errors.event_date}
                                    </span>
                                )}
                            </div>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {/* Kategori */}
                            <div className="flex flex-col space-y-2">
                                <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    Kategori Dokumen
                                </label>
                                <select
                                    value={data.category}
                                    onChange={(e) =>
                                        setData("category", e.target.value)
                                    }
                                    className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-yellow-500 focus:outline-none w-full"
                                >
                                    <option value="Regulasi">
                                        Regulasi & Kebijakan
                                    </option>
                                    <option value="Seminar">
                                        Materi Seminar
                                    </option>
                                    <option value="Sosialisasi">
                                        Sosialisasi Pemerintah
                                    </option>
                                </select>
                            </div>

                            {/* Tingkat Akses */}
                            <div className="flex flex-col space-y-2">
                                <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    Tingkat Hak Akses Dokumen
                                </label>
                                <select
                                    value={data.access_tier}
                                    onChange={(e) =>
                                        setData("access_tier", e.target.value)
                                    }
                                    className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-yellow-500 focus:outline-none w-full"
                                >
                                    <option value="Public">
                                        Publik (Tanpa Login)
                                    </option>
                                    <option value="Member">
                                        Anggota (Wajib Login Google)
                                    </option>
                                    <option value="Premium">
                                        Eksklusif Korporasi Premium
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div className="pt-4 border-t border-white/5 flex justify-end">
                            <button
                                type="submit"
                                disabled={processing}
                                className="bg-gradient-to-r from-yellow-500 to-amber-500 text-[#0a192f] font-black px-10 py-4 rounded-xl uppercase text-[10px] tracking-widest hover:from-yellow-400 hover:to-amber-400 transition-all shadow-lg shadow-yellow-500/10 hover:scale-105 duration-300"
                            >
                                {processing
                                    ? "Memproses..."
                                    : "Simpan Perubahan Regulasi"}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
