import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, useForm, usePage } from "@inertiajs/react";
import React from "react";

export default function Edit({ matchmaking = {} }) {
    const { auth, locale } = usePage().props;
    const isEn = locale === "en" || auth?.user?.locale === "en";

    // Reaktif Form Hook bawaan Inertia untuk Update Data
    const { data, setData, put, processing, errors } = useForm({
        jenis_mesin: matchmaking?.jenis_mesin || "",
        kategori_proses: matchmaking?.kategori_proses || "Knitting",
        kapasitas_bulanan: matchmaking?.kapasitas_bulanan || "",
        satuan: matchmaking?.satuan || "Ton",
        sertifikasi: matchmaking?.sertifikasi || "",
        lokasi_pabrik: matchmaking?.lokasi_pabrik || "",
        whatsapp_contact: matchmaking?.whatsapp_contact || "",
        spesifikasi_mesin: matchmaking?.spesifikasi_mesin || "",
        spesifikasi_mesin_en: matchmaking?.spesifikasi_mesin_en || "",
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        put(route("matchmaking.update", matchmaking.id));
    };

    return (
        <AuthenticatedLayout>
            <Head
                title={
                    isEn
                        ? "Update Machine Capacity"
                        : "Perbarui Kapasitas Lini Mesin"
                }
            />

            <div className="py-12 bg-[#0a192f] min-h-screen text-white">
                <div className="max-w-3xl mx-auto px-6 lg:px-8">
                    {/* --- HEADER FORM --- */}
                    <div className="mb-10 border-l-4 border-amber-500 pl-6">
                        <h1 className="text-3xl font-black uppercase tracking-tighter italic">
                            {isEn
                                ? "Modify Machine Capacity Data"
                                : "Ubah Data Kapasitas Lini Mesin"}
                        </h1>
                        <p className="text-gray-400 text-xs mt-1 uppercase tracking-wider">
                            {isEn
                                ? "B2B Industrial Matchmaking Network"
                                : "Jaringan Perjodohan Kapasitas Produksi Maklon API"}
                        </p>
                    </div>

                    {/* --- FORM CONTAINER --- */}
                    <form
                        onSubmit={handleSubmit}
                        className="bg-white/5 border border-white/10 p-10 rounded-[40px] shadow-2xl space-y-6 backdrop-blur-md"
                    >
                        {/* 1. Nama/Jenis Mesin */}
                        <div className="flex flex-col space-y-2">
                            <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                {isEn
                                    ? "Machine Model & Brand"
                                    : "Nama & Merek Mesin Tekstil"}
                            </label>
                            <input
                                type="text"
                                value={data.jenis_mesin}
                                onChange={(e) =>
                                    setData("jenis_mesin", e.target.value)
                                }
                                placeholder="e.g., Circular Knitting Machine (Mayer & Cie)"
                                className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-amber-500 focus:outline-none w-full shadow-inner"
                                required
                            />
                            {errors.jenis_mesin && (
                                <span className="text-xs text-red-400 font-bold">
                                    ⚠️ {errors.jenis_mesin}
                                </span>
                            )}
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {/* 2. Sektor Proses */}
                            <div className="flex flex-col space-y-2">
                                <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    {isEn
                                        ? "Production Sector"
                                        : "Sektor Proses Produksi"}
                                </label>
                                <select
                                    value={data.kategori_proses}
                                    onChange={(e) =>
                                        setData(
                                            "kategori_proses",
                                            e.target.value,
                                        )
                                    }
                                    className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-amber-500 focus:outline-none w-full"
                                >
                                    <option value="Knitting">
                                        Rajut (Knitting)
                                    </option>
                                    <option value="Weaving">
                                        Tenun (Weaving)
                                    </option>
                                    <option value="Garment">
                                        Garmen / Jahit (Apparel)
                                    </option>
                                    <option value="Footwear">
                                        Perakitan Alas Kaki (Footwear)
                                    </option>
                                    <option value="Bag">
                                        Manufaktur Tas & Koper (Bag Line)
                                    </option>
                                    <option value="Leather">
                                        Penyamakan Kulit Olahan (Leather
                                        Tanning)
                                    </option>
                                </select>
                            </div>

                            {/* 3. Lokasi Pabrik */}
                            <div className="flex flex-col space-y-2">
                                <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    {isEn
                                        ? "Factory Location (City)"
                                        : "Kota Lokasi Pabrik"}
                                </label>
                                <input
                                    type="text"
                                    value={data.lokasi_pabrik}
                                    onChange={(e) =>
                                        setData("lokasi_pabrik", e.target.value)
                                    }
                                    placeholder="e.g., Sukoharjo, Jawa Tengah"
                                    className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-amber-500 focus:outline-none w-full"
                                    required
                                />
                                {errors.lokasi_pabrik && (
                                    <span className="text-xs text-red-400 font-bold">
                                        ⚠️ {errors.lokasi_pabrik}
                                    </span>
                                )}
                            </div>
                        </div>

                        {/* 4. Jumlah Kapasitas, 5. Satuan Ukur, dan 6. Sertifikasi Pabrik */}
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {/* 4. Jumlah Kapasitas */}
                            <div className="flex flex-col space-y-2">
                                <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    {isEn
                                        ? "Monthly Idle Capacity"
                                        : "Jumlah Kapasitas Kosong / Bulan"}
                                </label>
                                <input
                                    type="number"
                                    value={data.kapasitas_bulanan}
                                    onChange={(e) =>
                                        setData(
                                            "kapasitas_bulanan",
                                            e.target.value,
                                        )
                                    }
                                    placeholder="e.g., 150"
                                    className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-amber-500 focus:outline-none w-full"
                                    required
                                />
                                {errors.kapasitas_bulanan && (
                                    <span className="text-xs text-red-400 font-bold">
                                        ⚠️ {errors.kapasitas_bulanan}
                                    </span>
                                )}
                            </div>

                            {/* 5. Satuan Ukur */}
                            <div className="flex flex-col space-y-2">
                                <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    {isEn ? "Capacity Unit" : "Satuan Ukur"}
                                </label>
                                <select
                                    value={data.satuan}
                                    onChange={(e) =>
                                        setData("satuan", e.target.value)
                                    }
                                    className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-amber-500 focus:outline-none w-full"
                                >
                                    <option value="Ton">Ton</option>
                                    <option value="Meter">Meter</option>
                                    <option value="Pcs">Pcs</option>
                                    <option value="Pairs">
                                        Pairs (Footwear)
                                    </option>
                                </select>
                            </div>

                            {/* 6. Sertifikasi Pabrik */}
                            <div className="flex flex-col space-y-2">
                                <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    {isEn
                                        ? "Industrial Certification"
                                        : "Sertifikasi Industri (e.g., GOTS)"}
                                </label>
                                <input
                                    type="text"
                                    value={data.sertifikasi}
                                    onChange={(e) =>
                                        setData("sertifikasi", e.target.value)
                                    }
                                    placeholder="e.g., OEKO-TEX Standard 100"
                                    className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-amber-500 focus:outline-none w-full"
                                />
                            </div>
                        </div>

                        {/* 7. Kontak WhatsApp Maklon */}
                        <div className="flex flex-col space-y-2 border-b border-white/5 pb-6">
                            <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                {isEn
                                    ? "WhatsApp Contact for Order"
                                    : "Nomor WhatsApp Negosiasi Maklon (Format: 628xxx)"}
                            </label>
                            <input
                                type="text"
                                value={data.whatsapp_contact}
                                onChange={(e) =>
                                    setData("whatsapp_contact", e.target.value)
                                }
                                className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-amber-500 focus:outline-none w-full"
                                required
                            />
                            {errors.whatsapp_contact && (
                                <span className="text-xs text-red-400 font-bold">
                                    ⚠️ {errors.whatsapp_contact}
                                </span>
                            )}
                        </div>

                        {/* 🌐 8. SEPASANG INPUT SPESIFIKASI DETEIL TEKNIS (BILINGUAL LAYOUT) */}
                        <div className="space-y-6 pt-2">
                            {/* Spesifikasi Mesin - Bahasa Indonesia */}
                            <div className="flex flex-col space-y-2">
                                <label className="text-[10px] font-black uppercase tracking-widest text-amber-500">
                                    Spesifikasi Teknis Detail & Kapabilitas
                                    Mesin (ID)
                                </label>
                                <textarea
                                    value={data.spesifikasi_mesin}
                                    onChange={(e) =>
                                        setData(
                                            "spesifikasi_mesin",
                                            e.target.value,
                                        )
                                    }
                                    placeholder="e.g., Diameter 30-34 inci, gauge 24-28..."
                                    rows={3}
                                    className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-medium p-4 text-white focus:border-amber-500 focus:outline-none w-full resize-none font-sans leading-relaxed shadow-inner"
                                    required
                                />
                                {errors.spesifikasi_mesin && (
                                    <span className="text-xs text-red-400 font-bold">
                                        ⚠️ {errors.spesifikasi_mesin}
                                    </span>
                                )}
                            </div>

                            {/* Spesifikasi Mesin - English Extension */}
                            <div className="flex flex-col space-y-2">
                                <label className="text-[10px] font-black uppercase tracking-widest text-indigo-400">
                                    Detailed Machine Technical Specs &
                                    Capabilities (EN)
                                </label>
                                <textarea
                                    value={data.spesifikasi_mesin_en || ""}
                                    onChange={(e) =>
                                        setData(
                                            "spesifikasi_mesin_en",
                                            e.target.value,
                                        )
                                    }
                                    placeholder="e.g., Diameter 30-34 inches, gauge 24-28..."
                                    rows={3}
                                    className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-medium p-4 text-white focus:border-indigo-500 focus:outline-none w-full resize-none font-sans leading-relaxed shadow-inner"
                                />
                            </div>
                        </div>

                        {/* --- EXECUTE BUTTON (SUBMIT UPDATE MATANG) --- */}
                        <div className="pt-4 border-t border-white/5 flex justify-end">
                            <button
                                type="submit"
                                disabled={processing}
                                className="bg-gradient-to-r from-amber-500 to-yellow-500 text-[#0a192f] font-black px-10 py-4 rounded-xl uppercase text-[10px] tracking-widest hover:from-amber-400 hover:to-yellow-400 transition-all shadow-lg shadow-amber-500/10 hover:scale-105 duration-300 disabled:opacity-50 cursor-pointer"
                            >
                                {processing
                                    ? isEn
                                        ? "Updating..."
                                        : "Memperbarui..."
                                    : isEn
                                      ? "Save Capacity Changes"
                                      : "Simpan Perubahan Lini Mesin"}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
