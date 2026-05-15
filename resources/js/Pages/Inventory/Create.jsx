import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, useForm, usePage } from "@inertiajs/react";
import React from "react";

export default function Create({ company = {} }) {
    const { auth, locale } = usePage().props;
    const isEn = locale === "en" || auth?.user?.locale === "en";

    // Inisialisasi formulir otomatis menggunakan React Hook useForm bawaan Inertia
    const { data, setData, post, processing, errors, reset } = useForm({
        name: "",
        category: "Fabric", // Nilai bawaan awal
        stock: "",
        unit: "KG",
        warehouse_location: company?.city || "", // Otomatis mengambil data kota perusahaan
        whatsapp_contact: company?.phone_whatsapp || "", // Otomatis mengambil data kontak WA perusahaan
        price: "",
        description: "",
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route("inventory.store"), {
            onSuccess: () => reset(),
        });
    };

    return (
        <AuthenticatedLayout>
            <Head
                title={
                    isEn
                        ? "Add New B2B Material Listing"
                        : "Tambah Produk Toko Digital Baru"
                }
            />

            <div className="py-12 bg-[#0a192f] min-h-screen text-white">
                <div className="max-w-3xl mx-auto px-6 lg:px-8">
                    {/* --- HEADER FORM --- */}
                    <div className="mb-10 border-l-4 border-emerald-500 pl-6">
                        <h1 className="text-3xl font-black uppercase tracking-tighter italic">
                            {isEn
                                ? "List Your Excess Stock"
                                : "Pajang Produk Komoditas Baru"}
                        </h1>
                        <p className="text-gray-400 text-xs mt-1 uppercase tracking-wider">
                            {isEn
                                ? "B2B Digital Storefront Product Registry"
                                : "Registri Manajemen Inventoris Toko Digital API"}
                        </p>
                    </div>

                    {/* --- MAIN FORM CONTAINER --- */}
                    <form
                        onSubmit={handleSubmit}
                        className="bg-white/5 border border-white/10 p-10 rounded-[40px] shadow-2xl space-y-6 backdrop-blur-md"
                    >
                        {/* 1. Nama Komoditas / Spesifikasi */}
                        <div className="flex flex-col space-y-2">
                            <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                {isEn
                                    ? "Product Specification Name"
                                    : "Nama Komoditas / Spesifikasi Bahan"}
                            </label>
                            <input
                                type="text"
                                value={data.name}
                                onChange={(e) =>
                                    setData("name", e.target.value)
                                }
                                placeholder="e.g., Cotton Combed 30s Super Black"
                                className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-emerald-500 focus:outline-none w-full shadow-inner"
                            />
                            {errors.name && (
                                <span className="text-xs text-red-400 font-bold font-mono">
                                    ⚠️ {errors.name}
                                </span>
                            )}
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {/* 2. Sektor Kategori */}
                            <div className="flex flex-col space-y-2">
                                <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    {isEn
                                        ? "Material Category"
                                        : "Kategori Sektor"}
                                </label>
                                <select
                                    value={data.category}
                                    onChange={(e) =>
                                        setData("category", e.target.value)
                                    }
                                    className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-emerald-500 focus:outline-none w-full"
                                >
                                    <option value="Fabric">
                                        {isEn
                                            ? "Fabric (Kain)"
                                            : "Kain (Fabric)"}
                                    </option>
                                    <option value="Yarn">
                                        {isEn
                                            ? "Yarn (Benang)"
                                            : "Benang (Yarn)"}
                                    </option>
                                    <option value="Accessories">
                                        {isEn ? "Accessories" : "Aksesoris"}
                                    </option>
                                </select>
                            </div>

                            {/* 3. Lokasi Gudang */}
                            <div className="flex flex-col space-y-2">
                                <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    {isEn
                                        ? "Warehouse City Location"
                                        : "Lokasi Kota Gudang"}
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
                                    placeholder="e.g., Bandung, Jawa Barat"
                                    className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-emerald-500 focus:outline-none w-full"
                                />
                                {errors.warehouse_location && (
                                    <span className="text-xs text-red-400 font-bold font-mono">
                                        ⚠️ {errors.warehouse_location}
                                    </span>
                                )}
                            </div>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {/* 4. Sisa Kuantitas */}
                            <div className="flex flex-col space-y-2">
                                <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    {isEn
                                        ? "Available Quantity"
                                        : "Jumlah Sisa Stok"}
                                </label>
                                <input
                                    type="number"
                                    step="any"
                                    value={data.stock}
                                    onChange={(e) =>
                                        setData("stock", e.target.value)
                                    }
                                    placeholder="e.g., 500"
                                    className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-emerald-500 focus:outline-none w-full"
                                />
                                {errors.stock && (
                                    <span className="text-xs text-red-400 font-bold font-mono">
                                        ⚠️ {errors.stock}
                                    </span>
                                )}
                            </div>

                            {/* 5. Satuan */}
                            <div className="flex flex-col space-y-2">
                                <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    {isEn ? "Unit Measure" : "Satuan Ukur"}
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

                            {/* 6. Harga Satuan */}
                            <div className="flex flex-col space-y-2">
                                <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    {isEn
                                        ? "Price / Unit (IDR)"
                                        : "Harga per Satuan (Rp)"}
                                </label>
                                <input
                                    type="number"
                                    value={data.price}
                                    onChange={(e) =>
                                        setData("price", e.target.value)
                                    }
                                    placeholder={
                                        isEn
                                            ? "Leave blank for negotiation"
                                            : "Kosongkan jika ingin nego"
                                    }
                                    className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-emerald-500 focus:outline-none w-full"
                                />
                            </div>
                        </div>

                        {/* 7. Kontak WhatsApp Lapak */}
                        <div className="flex flex-col space-y-2">
                            <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                {isEn
                                    ? "WhatsApp Business Contact"
                                    : "Nomor Kontak WhatsApp Lapak (Format: 628xxx)"}
                            </label>
                            <input
                                type="text"
                                value={data.whatsapp_contact}
                                onChange={(e) =>
                                    setData("whatsapp_contact", e.target.value)
                                }
                                placeholder="e.g., 62812345678"
                                className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-emerald-500 focus:outline-none w-full"
                            />
                            {errors.whatsapp_contact && (
                                <span className="text-xs text-red-400 font-bold font-mono">
                                    ⚠️ {errors.whatsapp_contact}
                                </span>
                            )}
                        </div>

                        {/* 8. Deskripsi Kondisi Produk */}
                        <div className="flex flex-col space-y-2">
                            <label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                {isEn
                                    ? "Detailed Condition Description"
                                    : "Deskripsi Detail Kondisi Material"}
                            </label>
                            <textarea
                                value={data.description}
                                onChange={(e) =>
                                    setData("description", e.target.value)
                                }
                                placeholder={
                                    isEn
                                        ? "Specify GSM, condition, reasons for clearance..."
                                        : "Sebutkan nilai GSM, kondisi palet, alasan likuidasi gudang..."
                                }
                                rows={4}
                                className="bg-[#0f172a] border border-white/10 rounded-xl text-xs font-bold p-3.5 text-white focus:border-emerald-500 focus:outline-none w-full resize-none font-sans leading-relaxed"
                            />
                        </div>

                        {/* --- TOMBOL EKSEKUSI PENYIMPANAN --- */}
                        <div className="pt-4 border-t border-white/5 flex justify-end">
                            <button
                                type="submit"
                                disabled={processing}
                                className="bg-gradient-to-r from-emerald-500 to-teal-500 text-[#0a192f] font-black px-10 py-4 rounded-xl uppercase text-[10px] tracking-widest hover:from-emerald-400 hover:to-teal-400 transition-all shadow-lg shadow-emerald-500/10 hover:scale-105 duration-300 disabled:opacity-50 disabled:pointer-events-none cursor-pointer"
                            >
                                {processing
                                    ? isEn
                                        ? "Processing..."
                                        : "Menyimpan..."
                                    : isEn
                                      ? "Publish to Digital Store"
                                      : "Pajang di Toko Digital"}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
