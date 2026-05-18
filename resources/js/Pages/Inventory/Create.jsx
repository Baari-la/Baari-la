import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, useForm, usePage } from "@inertiajs/react";
import React, { useState } from "react";

export default function Create({ company = {} }) {
    const { auth, locale } = usePage().props;
    const isEn = locale === "en" || auth?.user?.locale === "en";

    // State lokal untuk menampung URL Pratinjau Gambar di browser
    const [previewUrl, setPreviewUrl] = useState(null);

    const { data, setData, post, processing, errors, reset } = useForm({
        name: "",
        name_en: "",
        category: "Fabric",
        stock: "",
        unit: "KG",
        warehouse_location: company?.city || "",
        whatsapp_contact: company?.phone_whatsapp || "",
        price: "",
        description: "",
        description_en: "",
        image: null, // Definisikan objek file gambar awal kosong
    });

    // Menangani seleksi file gambar dan membuat URL pratinjau instan
    const handleImageChange = (e) => {
        const file = e.target.files[0];
        if (file) {
            setData("image", file);
            setPreviewUrl(URL.createObjectURL(file)); // Membuat URL bayangan lokal browser
        }
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        // Inertia otomatis mendeteksi objek file biner dan mengubah payload menjadi FormData
        post(route("inventory.store"), {
            onSuccess: () => {
                reset();
                setPreviewUrl(null);
            },
        });
    };

    return (
        <AuthenticatedLayout>
            <Head
                title={
                    isEn
                        ? "Add New Material Listing"
                        : "Tambah Produk Toko Digital"
                }
            />
            <div className="py-12 bg-[#0a192f] min-h-screen text-white">
                <div className="max-w-3xl mx-auto px-6 lg:px-8">
                    <div className="mb-10 border-l-4 border-emerald-500 pl-6">
                        <h1 className="text-3xl font-black uppercase tracking-tighter italic">
                            {isEn
                                ? "List Your Excess Stock"
                                : "Pajang Produk Komoditas Baru"}
                        </h1>
                    </div>

                    <form
                        onSubmit={handleSubmit}
                        className="bg-white/5 border border-white/10 p-10 rounded-[40px] shadow-2xl space-y-6 backdrop-blur-md"
                    >
                        {/* INPUT GAMBAR PRODUK TEKSTIL (INTEGRASI BARU) */}
                        <div className="flex flex-col space-y-3 bg-black/20 p-6 rounded-3xl border border-white/5">
                            <label className="text-[10px] font-black uppercase tracking-widest text-emerald-400">
                                {isEn
                                    ? "Product Photo (JPG/PNG Max 2MB)"
                                    : "Foto Fisik Kain / Benang (Maks 2MB)"}
                            </label>

                            <div className="flex flex-col sm:flex-row items-center gap-6">
                                {/* Kotak Pratinjau Live Preview */}
                                <div className="w-24 h-24 rounded-2xl bg-slate-800 border border-white/10 flex items-center justify-center overflow-hidden shadow-inner relative">
                                    {previewUrl ? (
                                        <img
                                            src={previewUrl}
                                            className="w-full h-full object-cover animate-fade-in"
                                            alt="Preview"
                                        />
                                    ) : (
                                        <div className="text-center text-gray-600 flex flex-col items-center gap-1">
                                            <i className="fas fa-camera text-xl"></i>
                                            <span className="text-[8px] font-black uppercase tracking-widest text-gray-500">
                                                No Image
                                            </span>
                                        </div>
                                    )}
                                </div>

                                {/* Tombol Pilih File File Browser */}
                                <div className="flex-1 w-full">
                                    <input
                                        type="file"
                                        accept="image/*"
                                        onChange={handleImageChange}
                                        className="w-full text-xs text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-[9px] file:font-black file:uppercase file:tracking-widest file:bg-emerald-500 file:text-[#0a192f] file:cursor-pointer hover:file:bg-emerald-400 file:transition-all"
                                    />
                                    {errors.image && (
                                        <span className="text-xs text-red-400 font-bold block mt-1">
                                            ⚠️ {errors.image}
                                        </span>
                                    )}
                                </div>
                            </div>
                        </div>

                        {/* 1. Nama Komoditas */}
                        {/* --- INPUT SELEKTOR NAMA BILINGUAL --- */}
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div className="flex flex-col space-y-2">
                                <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    Nama Komoditas (Bahasa Indonesia)
                                </label>
                                <input
                                    type="text"
                                    value={data.name}
                                    onChange={(e) =>
                                        setData("name", e.target.value)
                                    }
                                    placeholder="Contoh: Kain Katun Kombed 30s Super Black"
                                    className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-emerald-500 focus:outline-none w-full shadow-inner"
                                />
                                {errors.name && (
                                    <span className="text-xs text-red-400 font-bold">
                                        ⚠️ {errors.name}
                                    </span>
                                )}
                            </div>
                            <div className="flex flex-col space-y-2">
                                <label className="text-[10px] font-black uppercase tracking-widest text-emerald-400">
                                    Product Specification Name (English)
                                </label>
                                <input
                                    type="text"
                                    value={data.name_en}
                                    onChange={(e) =>
                                        setData("name_en", e.target.value)
                                    }
                                    placeholder="e.g., Cotton Combed 30s Fabric Super Black"
                                    className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-emerald-500 focus:outline-none w-full shadow-inner"
                                />
                            </div>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {/* 2. Kategori Sektor */}
                            <div className="flex flex-col space-y-2">
                                <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    Deskripsi Detail Material (ID)
                                </label>
                                <textarea
                                    value={data.description}
                                    onChange={(e) =>
                                        setData("description", e.target.value)
                                    }
                                    placeholder="Sebutkan nilai GSM kain, kondisi gulungan palet..."
                                    rows={4}
                                    className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-emerald-500 focus:outline-none w-full resize-none font-sans"
                                />
                            </div>
                            <div className="flex flex-col space-y-2">
                                <label className="text-[10px] font-black uppercase tracking-widest text-emerald-400">
                                    Detailed Condition Description (EN)
                                </label>
                                <textarea
                                    value={data.description_en}
                                    onChange={(e) =>
                                        setData(
                                            "description_en",
                                            e.target.value,
                                        )
                                    }
                                    placeholder="Specify GSM rating, roll condition, clearance notes..."
                                    rows={4}
                                    className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-emerald-500 focus:outline-none w-full resize-none font-sans"
                                />
                            </div>
                            {/* 3. Lokasi Gudang */}
                            <div className="flex flex-col space-y-2">
                                <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    Kota Lokasi Gudang
                                </label>
                                <input
                                    type="text"
                                    value={data.warehouse_location}
                                    onChange={(e) =>
                                        setData(
                                            "warehouse_location",
                                            e.target.value,
                                        )
                                    }
                                    className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-emerald-500 focus:outline-none w-full"
                                />
                            </div>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {/* 4. Jumlah Stok */}
                            <div className="flex flex-col space-y-2">
                                <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    Jumlah Sisa Stok
                                </label>
                                <input
                                    type="number"
                                    value={data.stock}
                                    onChange={(e) =>
                                        setData("stock", e.target.value)
                                    }
                                    className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-emerald-500 focus:outline-none w-full"
                                />
                            </div>
                            {/* 5. Satuan */}
                            <div className="flex flex-col space-y-2">
                                <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    Satuan Ukur
                                </label>
                                <select
                                    value={data.unit}
                                    onChange={(e) =>
                                        setData("unit", e.target.value)
                                    }
                                    className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-emerald-500 focus:outline-none w-full"
                                >
                                    <option value="Roll">Roll</option>
                                    <option value="KG">KG</option>
                                    <option value="Yard">Yard</option>
                                    <option value="Meter">Meter</option>
                                </select>
                            </div>
                            {/* 6. Harga */}
                            <div className="flex flex-col space-y-2">
                                <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    Harga Jual Satuan (Rp)
                                </label>
                                <input
                                    type="number"
                                    value={data.price}
                                    onChange={(e) =>
                                        setData("price", e.target.value)
                                    }
                                    className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-emerald-500 focus:outline-none w-full"
                                />
                            </div>
                        </div>

                        {/* 7. Kontak WhatsApp */}
                        <div className="flex flex-col space-y-2">
                            <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                Nomor WhatsApp Lapak (Format: 628xxx)
                            </label>
                            <input
                                type="text"
                                value={data.whatsapp_contact}
                                onChange={(e) =>
                                    setData("whatsapp_contact", e.target.value)
                                }
                                className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-emerald-500 focus:outline-none w-full"
                            />
                        </div>

                        {/* 8. Deskripsi */}
                        <div className="flex flex-col space-y-2">
                            <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                Deskripsi Detail Kondisi Material
                            </label>
                            <textarea
                                value={data.description}
                                onChange={(e) =>
                                    setData("description", e.target.value)
                                }
                                rows={4}
                                className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-emerald-500 focus:outline-none w-full resize-none font-sans"
                            />
                        </div>

                        <div className="pt-4 border-t border-white/5 flex justify-end">
                            <button
                                type="submit"
                                disabled={processing}
                                className="bg-gradient-to-r from-emerald-500 to-teal-500 text-[#0a192f] font-black px-10 py-4 rounded-xl uppercase text-[10px] tracking-widest hover:from-emerald-400 hover:to-teal-400 transition-all shadow-lg shadow-emerald-500/10 hover:scale-105 duration-300"
                            >
                                {processing
                                    ? "Menyimpan..."
                                    : "Pajang di Toko Digital"}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
