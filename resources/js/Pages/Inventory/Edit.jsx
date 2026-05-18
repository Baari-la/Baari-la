import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, useForm, usePage } from "@inertiajs/react";
import React, { useState } from "react";

export default function Edit({ item = {} }) {
    const { auth, locale } = usePage().props;
    const isEn = locale === "en" || auth?.user?.locale === "en";

    // State untuk pratinjau gambar dinamis (Gunakan foto lama database sebagai dasaran)
    const [previewUrl, setPreviewUrl] = useState(
        item.image ? `/storage/${item.image}` : null,
    );

    const { data, setData, post, processing, errors } = useForm({
        _method: "PUT", // <--- TRIK VITAL LARAVEL: Menyamar sebagai PUT via metode POST agar file gambar terkirim
        name: item.name || "",
        category: item.category || "Fabric",
        stock: item.stock || "",
        unit: item.unit || "KG",
        warehouse_location: item.warehouse_location || "",
        whatsapp_contact: item.whatsapp_contact || "",
        price: item.price || "",
        description: item.description || "",
        image: null, // Siapkan objek penampung file baru
    });

    const handleImageChange = (e) => {
        const file = e.target.files[0];
        if (file) {
            setData("image", file);
            setPreviewUrl(URL.createObjectURL(file)); // Perbarui visual kotak pratinjau secara instan
        }
    };

    const handleUpdateSubmit = (e) => {
        e.preventDefault();
        // Sesuai trik penyamaran file, eksekusi wajib menggunakan metode post() menuju rute update
        post(route("inventory.update", item.id));
    };

    return (
        <AuthenticatedLayout>
            <Head title="Perbarui Data Komoditas Lapak" />
            <div className="py-12 bg-[#0a192f] min-h-screen text-white">
                <div className="max-w-3xl mx-auto px-6 lg:px-8">
                    <div className="mb-10 border-l-4 border-blue-500 pl-6">
                        <h1 className="text-3xl font-black uppercase tracking-tighter italic text-blue-400">
                            {isEn
                                ? "Modify Listed Product"
                                : "Ubah Data Inventoris Lapak"}
                        </h1>
                        <p className="text-gray-400 text-xs mt-1">
                            Modifying Live Registry Data for: {item.name}
                        </p>
                    </div>

                    <form
                        onSubmit={handleUpdateSubmit}
                        className="bg-white/5 border border-white/10 p-10 rounded-[40px] shadow-2xl space-y-6 backdrop-blur-md"
                    >
                        {/* KOTAK SUB-FORMULIR EDIT GAMBAR PRODUK */}
                        <div className="flex flex-col space-y-3 bg-black/20 p-6 rounded-3xl border border-white/5">
                            <label className="text-[10px] font-black uppercase tracking-widest text-blue-400">
                                {isEn
                                    ? "Update Product Photo (Leave blank to keep current photo)"
                                    : "Ganti Foto Komoditas (Kosongkan jika ingin memakai foto lama)"}
                            </label>

                            <div className="flex flex-col sm:flex-row items-center gap-6">
                                <div className="w-24 h-24 rounded-2xl bg-slate-800 border border-white/10 flex items-center justify-center overflow-hidden shadow-inner relative">
                                    {previewUrl ? (
                                        <img
                                            src={previewUrl}
                                            className="w-full h-full object-cover"
                                            alt="Current Listing"
                                        />
                                    ) : (
                                        <div className="text-center text-gray-600 flex flex-col items-center gap-1">
                                            <i className="fas fa-camera text-xl"></i>
                                            <span className="text-[8px] font-black uppercase tracking-widest text-gray-500">
                                                No Photo
                                            </span>
                                        </div>
                                    )}
                                </div>

                                <div className="flex-1 w-full">
                                    <input
                                        type="file"
                                        accept="image/*"
                                        onChange={handleImageChange}
                                        className="w-full text-xs text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-[9px] file:font-black file:uppercase file:tracking-widest file:bg-blue-600 file:text-white file:cursor-pointer hover:file:bg-blue-500 file:transition-all"
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
                        <div className="flex flex-col space-y-2">
                            <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                Nama Komoditas / Spesifikasi Bahan
                            </label>
                            <input
                                type="text"
                                value={data.name}
                                onChange={(e) =>
                                    setData("name", e.target.value)
                                }
                                className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-blue-500 focus:outline-none w-full shadow-inner"
                            />
                        </div>

                        {/* 2. Nama Perusahaan */}
                        <div className="flex flex-col space-y-2">
                            <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                Nama Perusahaan Pemilik Gudang
                            </label>
                            <input
                                type="text"
                                value={data.nama_perusahaan}
                                onChange={(e) =>
                                    setData("nama_perusahaan", e.target.value)
                                }
                                className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-blue-500 focus:outline-none w-full"
                            />
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {/* 3. Kategori Sektor */}
                            <div className="flex flex-col space-y-2">
                                <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    Kategori Sektor
                                </label>
                                <select
                                    value={data.category}
                                    onChange={(e) =>
                                        setData("category", e.target.value)
                                    }
                                    className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-blue-500 focus:outline-none w-full"
                                >
                                    <option value="Fabric">
                                        Kain (Fabric)
                                    </option>
                                    <option value="Yarn">Benang (Yarn)</option>
                                    <option value="Accessories">
                                        Aksesoris
                                    </option>
                                </select>
                            </div>
                            {/* 4. Lokasi Gudang */}
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
                                    className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-blue-500 focus:outline-none w-full"
                                />
                            </div>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {/* 5. Jumlah Stok */}
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
                                    className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-blue-500 focus:outline-none w-full"
                                />
                            </div>
                            {/* 6. Satuan */}
                            <div className="flex flex-col space-y-2">
                                <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    Satuan Ukur
                                </label>
                                <select
                                    value={data.unit}
                                    onChange={(e) =>
                                        setData("unit", e.target.value)
                                    }
                                    className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-blue-500 focus:outline-none w-full"
                                >
                                    <option value="Roll">Roll</option>
                                    <option value="KG">KG</option>
                                    <option value="Yard">Yard</option>
                                    <option value="Meter">Meter</option>
                                </select>
                            </div>
                            {/* 7. Harga */}
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
                                    className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-blue-500 focus:outline-none w-full"
                                />
                            </div>
                        </div>

                        {/* 8. Kontak WhatsApp */}
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
                                className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-blue-500 focus:outline-none w-full"
                            />
                        </div>

                        {/* 9. Deskripsi */}
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
                                className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-blue-500 focus:outline-none w-full resize-none font-sans"
                            />
                        </div>

                        {/* --- BUTTON SAVE CHANGES --- */}
                        <div className="pt-4 border-t border-white/5 flex justify-end">
                            <button
                                type="submit"
                                disabled={processing}
                                className="bg-gradient-to-r from-blue-500 to-indigo-500 text-white font-black px-10 py-4 rounded-xl uppercase text-[10px] tracking-widest hover:from-blue-400 hover:to-indigo-400 transition-all shadow-lg shadow-blue-500/10 hover:scale-105 duration-300"
                            >
                                {processing
                                    ? "Menyimpan..."
                                    : "Simpan Perubahan Data"}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
