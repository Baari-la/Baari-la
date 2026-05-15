import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, useForm, usePage } from "@inertiajs/react";
import React from "react";

export default function Edit({ regulation = {} }) {
    const { auth, locale } = usePage().props;
    const isEn = locale === "en" || auth?.user?.locale === "en";

    // Inisialisasi useForm reaktif dengan memuat data default lama dari database
    const { data, setData, put, processing, errors } = useForm({
        title: regulation.title || "",
        speaker: regulation.speaker || "",
        category: regulation.category || "Regulasi",
        access_tier: regulation.access_tier || "Member",
        event_date: regulation.event_date || "",
    });

    const handleUpdate = (e) => {
        e.preventDefault();
        // Menembak ke rute PUT updateRegulation
        put(route("regulation.update", regulation.id));
    };

    return (
        <AuthenticatedLayout>
            <Head
                title={
                    isEn
                        ? "Modify Regulation Registry"
                        : "Ubah Data Dokumen Regulasi"
                }
            />
            <div className="py-12 bg-[#0a192f] min-h-screen text-white">
                <div className="max-w-3xl mx-auto px-6 lg:px-8">
                    {/* --- HEADER FORM --- */}
                    <div className="mb-10 border-l-4 border-yellow-500 pl-6">
                        <h1 className="text-3xl font-black uppercase tracking-tighter italic text-yellow-500">
                            {isEn
                                ? "Edit Official Document"
                                : "Ubah Dokumen Resmi"}
                        </h1>
                        <p className="text-gray-400 text-xs mt-1 uppercase tracking-wider">
                            {isEn
                                ? "Updating Database Record for:"
                                : "Mengedit Registri Pengetahuan:"}{" "}
                            {regulation.title}
                        </p>
                    </div>

                    {/* --- MAIN FORM CONTAINER --- */}
                    <form
                        onSubmit={handleUpdate}
                        className="bg-white/5 border border-white/10 p-10 rounded-[40px] shadow-2xl space-y-6 backdrop-blur-md"
                    >
                        {/* 1. Judul Regulasi / Materi */}
                        <div className="flex flex-col space-y-2">
                            <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                {isEn
                                    ? "Document / Presentation Title"
                                    : "Judul Regulasi / Materi Presentasi"}
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
                            {/* 2. Pembicara / Instansi Sumber */}
                            <div className="flex flex-col space-y-2">
                                <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    {isEn
                                        ? "Speaker / Source Institution"
                                        : "Pembicara / Instansi Sumber"}
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

                            {/* 3. Tanggal Pengesahan / Acara */}
                            <div className="flex flex-col space-y-2">
                                <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    {isEn
                                        ? "Event / Endorsement Date"
                                        : "Tanggal Pengesahan / Acara"}
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
                            {/* 4. Kategori Dokumen */}
                            <div className="flex flex-col space-y-2">
                                <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    {isEn
                                        ? "Classification Category"
                                        : "Kategori Dokumen"}
                                </label>
                                <select
                                    value={data.category}
                                    onChange={(e) =>
                                        setData("category", e.target.value)
                                    }
                                    className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-yellow-500 focus:outline-none w-full"
                                >
                                    <option value="Regulasi">
                                        {isEn
                                            ? "Regulation & Policy"
                                            : "Regulasi & Kebijakan"}
                                    </option>
                                    <option value="Seminar">
                                        {isEn
                                            ? "Seminar Material"
                                            : "Materi Seminar"}
                                    </option>
                                    <option value="Sosialisasi">
                                        {isEn
                                            ? "Official Socialization"
                                            : "Sosialisasi Pemerintah"}
                                    </option>
                                </select>
                            </div>

                            {/* 5. Tingkat Akses (Tier Security) */}
                            <div className="flex flex-col space-y-2">
                                <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    {isEn
                                        ? "Security Access Tier"
                                        : "Tingkat Hak Akses Dokumen"}
                                </label>
                                <select
                                    value={data.access_tier}
                                    onChange={(e) =>
                                        setData("access_tier", e.target.value)
                                    }
                                    className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-yellow-500 focus:outline-none w-full"
                                >
                                    <option value="Public">
                                        {isEn
                                            ? "Public (Guest Allowed)"
                                            : "Publik (Tanpa Login)"}
                                    </option>
                                    <option value="Member">
                                        {isEn
                                            ? "Member (Google Login Required)"
                                            : "Anggota (Wajib Login Google)"}
                                    </option>
                                    <option value="Premium">
                                        {isEn
                                            ? "Premium Corporate Member Exclusive"
                                            : "Eksklusif Korporasi Premium"}
                                    </option>
                                </select>
                            </div>
                        </div>

                        {/* --- EXECUTE BUTTON --- */}
                        <div className="pt-4 border-t border-white/5 flex justify-end">
                            <button
                                type="submit"
                                disabled={processing}
                                className="bg-gradient-to-r from-yellow-500 to-amber-500 text-[#0a192f] font-black px-10 py-4 rounded-xl uppercase text-[10px] tracking-widest hover:from-yellow-400 hover:to-amber-400 transition-all shadow-lg shadow-yellow-500/10 hover:scale-105 duration-300 disabled:opacity-50 cursor-pointer"
                            >
                                {processing
                                    ? isEn
                                        ? "Saving Changes..."
                                        : "Memproses..."
                                    : isEn
                                      ? "Save Document Changes"
                                      : "Simpan Perubahan Regulasi"}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
