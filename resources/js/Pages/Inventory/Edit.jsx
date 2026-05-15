import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, useForm, usePage } from "@inertiajs/react";
import React from "react";

export default function Edit({ item = {} }) {
    const { auth, locale } = usePage().props;
    const isEn = locale === "en" || auth?.user?.locale === "en";

    const { data, setData, put, processing, errors } = useForm({
        name: item.name || "",
        category: item.category || "Fabric",
        stock: item.stock || "",
        unit: item.unit || "KG",
        warehouse_location: item.warehouse_location || "",
        whatsapp_contact: item.whatsapp_contact || "",
        price: item.price || "",
        description: item.description || "",
        nama_perusahaan: item.nama_perusahaan || "", // <-- Kolom baru
    });

    const handleUpdateSubmit = (e) => {
        e.preventDefault();
        put(route("inventory.update", item.id));
    };

    return (
        <AuthenticatedLayout>
            <Head title="Perbarui Data Komoditas Lapak" />
            <div className="py-12 bg-[#0a192f] min-h-screen text-white">
                <div className="max-w-3xl mx-auto px-6 lg:px-8">
                    <div className="mb-10 border-l-4 border-blue-500 pl-6">
                        <h1 className="text-3xl font-black uppercase italic text-blue-400">
                            Ubah Data Inventoris Lapak
                        </h1>
                        <p className="text-gray-400 text-xs mt-1">
                            Modifying Live Registry Data for: {item.name}
                        </p>
                    </div>

                    <form
                        onSubmit={handleUpdateSubmit}
                        className="bg-white/5 border border-white/10 p-10 rounded-[40px] shadow-2xl space-y-6 backdrop-blur-md"
                    >
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
                            {errors.name && (
                                <span className="text-xs text-red-400">
                                    ⚠️ {errors.name}
                                </span>
                            )}
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
