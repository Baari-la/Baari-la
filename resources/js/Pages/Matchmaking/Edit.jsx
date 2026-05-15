import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, useForm, usePage } from "@inertiajs/react";
import React from "react";

export default function Edit({ partnership = {} }) {
    const { auth, locale } = usePage().props;
    const isEn = locale === "en" || auth?.user?.locale === "en";

    // Memuat seluruh 8 kolom parameter kemitraan B2B multi-sektor dari database
    const { data, setData, put, processing, errors } = useForm({
        name: partnership.name || "",
        tagline: partnership.tagline || "",
        category: partnership.category || "Raw Material",
        region: partnership.region || "West Java",
        description: partnership.description || "",
        moq_info: partnership.moq_info || "",
        after_sales_sla: partnership.after_sales_sla || "",
        whatsapp_contact: partnership.whatsapp_contact || "",
    });

    const handleUpdate = (e) => {
        e.preventDefault();
        // Menembak ke rute PUT updateMatchmaking
        put(route("partnership.update", partnership.id));
    };

    return (
        <AuthenticatedLayout>
            <Head
                title={
                    isEn
                        ? "Modify B2B Partnership Profile"
                        : "Perbarui Data Kemitraan B2B"
                }
            />
            <div className="py-12 bg-[#0a192f] min-h-screen text-white">
                <div className="max-w-3xl mx-auto px-6 lg:px-8">
                    {/* --- HEADER FORM --- */}
                    <div className="mb-10 border-l-4 border-amber-500 pl-6">
                        <h1 className="text-3xl font-black uppercase italic text-amber-500">
                            {isEn
                                ? "Modify Vendor Profile"
                                : "Ubah Profil Mitra Vendor"}
                        </h1>
                        <p className="text-gray-400 text-xs mt-1 uppercase tracking-wider">
                            {isEn
                                ? "Editing Technical Parameters for:"
                                : "Mengedit Parameter Bisnis:"}{" "}
                            {partnership.name}
                        </p>
                    </div>

                    {/* --- MAIN FORM CONTAINER --- */}
                    <form
                        onSubmit={handleUpdate}
                        className="bg-white/5 border border-white/10 p-10 rounded-[40px] shadow-2xl space-y-6 backdrop-blur-md"
                    >
                        {/* 1. Nama Vendor Korporasi */}
                        <div className="flex flex-col space-y-2">
                            <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                {isEn
                                    ? "Corporate Supplier Name"
                                    : "Nama Vendor Korporasi / Pabrik"}
                            </label>
                            <input
                                type="text"
                                value={data.name}
                                onChange={(e) =>
                                    setData("name", e.target.value)
                                }
                                className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-amber-500 focus:outline-none w-full shadow-inner"
                            />
                            {errors.name && (
                                <span className="text-xs text-red-400 font-bold">
                                    ⚠️ {errors.name}
                                </span>
                            )}
                        </div>

                        {/* 2. Tagline Keunggulan */}
                        <div className="flex flex-col space-y-2">
                            <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                {isEn
                                    ? "Value Proposition Tagline"
                                    : "Tagline Keunggulan Solusi Bisnis Vendor"}
                            </label>
                            <input
                                type="text"
                                value={data.tagline}
                                onChange={(e) =>
                                    setData("tagline", e.target.value)
                                }
                                className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-amber-500 focus:outline-none w-full"
                            />
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {/* 3. Sektor Kemitraan */}
                            <div className="flex flex-col space-y-2">
                                <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    Sektor Industri
                                </label>
                                <select
                                    value={data.category}
                                    onChange={(e) =>
                                        setData("category", e.target.value)
                                    }
                                    className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-amber-500 focus:outline-none w-full"
                                >
                                    <option value="Technology">
                                        Technology (PLM/ERP)
                                    </option>
                                    <option value="Machinery">
                                        Machinery & Spareparts
                                    </option>
                                    <option value="Raw Material">
                                        Raw Material Supplies
                                    </option>
                                </select>
                            </div>

                            {/* 4. Wilayah Operasional */}
                            <div className="flex flex-col space-y-2">
                                <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    Wilayah Operasional
                                </label>
                                <select
                                    value={data.region}
                                    onChange={(e) =>
                                        setData("region", e.target.value)
                                    }
                                    className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-amber-500 focus:outline-none w-full"
                                >
                                    <option value="West Java">
                                        Jawa Barat (West Java)
                                    </option>
                                    <option value="Central Java">
                                        Jawa Tengah (Central Java)
                                    </option>
                                    <option value="Central Java">(Bali)</option>
                                    <option value="Global">
                                        Jaringan Internasional (Global)
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {/* 5. Batas Minimum Order (MOQ) */}
                            <div className="flex flex-col space-y-2">
                                <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    Batas Minimum Order (MOQ)
                                </label>
                                <input
                                    type="text"
                                    value={data.moq_info}
                                    onChange={(e) =>
                                        setData("moq_info", e.target.value)
                                    }
                                    className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-amber-500 focus:outline-none w-full"
                                />
                            </div>

                            {/* 6. Jaminan Purnajual / SLA */}
                            <div className="flex flex-col space-y-2">
                                <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    Dukungan Garansi Purnajual / SLA
                                </label>
                                <input
                                    type="text"
                                    value={data.after_sales_sla}
                                    onChange={(e) =>
                                        setData(
                                            "after_sales_sla",
                                            e.target.value,
                                        )
                                    }
                                    className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-amber-500 focus:outline-none w-full"
                                />
                            </div>
                        </div>

                        {/* 7. Nomor WhatsApp Negosiasi */}
                        <div className="flex flex-col space-y-2">
                            <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                Nomor WhatsApp Negosiasi Maklon (Format: 628xxx)
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

                        {/* 8. Deskripsi Detail Solusi */}
                        <div className="flex flex-col space-y-2">
                            <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                Deskripsi Solusi & Kemampuan Teknis Vendor
                            </label>
                            <textarea
                                value={data.description}
                                onChange={(e) =>
                                    setData("description", e.target.value)
                                }
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
                                        ? "Processing..."
                                        : "Memproses..."
                                    : isEn
                                      ? "Save Partnership Profile"
                                      : "Simpan Perubahan Kemitraan"}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
