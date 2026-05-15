import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, useForm, usePage } from "@inertiajs/react";
import React from "react";

export default function Create({ company = {} }) {
    const { auth, locale } = usePage().props;
    const isEn = locale === "en" || auth?.user?.locale === "en";

    // Reaktif Form Hook bawaan Inertia
    const { data, setData, post, processing, errors, reset } = useForm({
        jenis_mesin: "",
        kategori_proses: "Knitting",
        kapasitas_bulanan: "",
        satuan: "Ton",
        sertifikasi: "",
        lokasi_pabrik: company?.city || "", // Auto-fill kota pabrik
        whatsapp_contact: company?.phone_whatsapp || "", // Auto-fill WA pabrik
        spesifikasi_mesin: "",
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route("matchmaking.store"), {
            onSuccess: () => reset(),
        });
    };

    return (
        <AuthenticatedLayout>
            <Head
                title={
                    isEn
                        ? "Register Machine Capacity"
                        : "Daftarkan Kapasitas Lini Mesin"
                }
            />

            <div className="py-12 bg-[#0a192f] min-h-screen text-white">
                <div className="max-w-3xl mx-auto px-6 lg:px-8">
                    {/* --- HEADER FORM --- */}
                    <div className="mb-10 border-l-4 border-amber-500 pl-6">
                        <h1 className="text-3xl font-black uppercase tracking-tighter italic">
                            {isEn
                                ? "List Idle Machine Capacity"
                                : "Daftarkan Lini Mesin Kosong"}
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
                                        Garmen / Jahit
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
                                />
                                {errors.lokasi_pabrik && (
                                    <span className="text-xs text-red-400 font-bold">
                                        ⚠️ {errors.lokasi_pabrik}
                                    </span>
                                )}
                            </div>
                        </div>

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
                        <div className="flex flex-col space-y-2">
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
                            />
                            {errors.whatsapp_contact && (
                                <span className="text-xs text-red-400 font-bold">
                                    ⚠️ {errors.whatsapp_contact}
                                </span>
                            )}
                        </div>

                        {/* 8. Spesifikasi Detail Mesin */}
                        <div className="flex flex-col space-y-2">
                            <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                {isEn
                                    ? "Detailed Machine Technical Specs"
                                    : "Spesifikasi Teknis Detail & Kapabilitas Mesin"}
                            </label>
                            <textarea
                                value={data.spesifikasi_mesin}
                                onChange={(e) =>
                                    setData("spesifikasi_mesin", e.target.value)
                                }
                                placeholder="e.g., Diameter 30-34 inci, gauge 24-28, sangat mumpuni memproduksi kain poplin konstruksi padat..."
                                rows={4}
                                className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-amber-500 focus:outline-none w-full resize-none font-sans leading-relaxed"
                            />
                        </div>

                        {/* --- EXECUTE BUTTON --- */}
                        <div className="pt-4 border-t border-white/5 flex justify-end">
                            <button
                                type="submit"
                                disabled={processing}
                                className="bg-gradient-to-r from-amber-500 to-yellow-500 text-[#0a192f] font-black px-10 py-4 rounded-xl uppercase text-[10px] tracking-widest hover:from-amber-400 hover:to-yellow-400 transition-all shadow-lg shadow-amber-500/10 hover:scale-105 duration-300 disabled:opacity-50 cursor-pointer"
                            >
                                {processing
                                    ? isEn
                                        ? "Publishing..."
                                        : "Memproses..."
                                    : isEn
                                      ? "Publish Matchmaking Capacity"
                                      : "Daftarkan Kelonggaran Mesin"}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
