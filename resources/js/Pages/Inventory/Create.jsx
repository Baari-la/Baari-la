import { useForm, Head } from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";

export default function CreateMaterial({ auth }) {
    const { data, setData, post, processing, errors } = useForm({
        name: "",
        category: "Fabric",
        stock: "",
        unit: "Yard",
        warehouse_location: "", 
        whatsapp_contact: "", 
        description: "",
        price: "",
        photo: null,
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route("inventory.store"));
    };

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Upload Material - Digestex V2" />

            <div className="py-12 bg-[#0a192f] min-h-screen">
                <div className="max-w-4xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white/5 overflow-hidden shadow-2xl rounded-[30px] border border-white/10 p-10">
                        <div className="mb-10 border-b border-white/10 pb-6">
                            <h2 className="text-2xl font-black text-yellow-500 uppercase tracking-tighter">
                                List New Material / Unggah Bahan Baru
                            </h2>
                            <p className="text-gray-400 text-[10px] mt-2 uppercase tracking-[0.3em]">
                                Industrial Inventory Matchmaking System
                            </p>
                        </div>

                        <form onSubmit={handleSubmit} className="space-y-6">
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {/* Nama Bahan */}
                                <div>
                                    <label className="block text-[10px] font-black text-gray-500 uppercase mb-2">Material Name / Nama Bahan</label>
                                    <input type="text" className="w-full bg-white/5 border border-white/10 rounded-xl text-white focus:ring-yellow-500 text-sm" placeholder="Contoh: Cotton Combed 30s" onChange={(e) => setData("name", e.target.value)} />
                                </div>

                                {/* Kategori */}
                                <div>
                                    <label className="block text-[10px] font-black text-gray-500 uppercase mb-2">Category / Kategori</label>
                                    <select className="w-full bg-white/5 border border-white/10 rounded-xl text-white focus:ring-yellow-500 text-sm" onChange={(e) => setData("category", e.target.value)}>
                                        <option value="Fabric">Fabric (Kain)</option>
                                        <option value="Yarn">Yarn (Benang)</option>
                                        <option value="Machine">Machine (Mesin)</option>
                                        <option value="Accessories">Accessories</option>
                                    </select>
                                </div>
                            </div>

                            {/* Baris Baru: Stok, Satuan, Lokasi */}
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label className="block text-[10px] font-black text-gray-500 uppercase mb-2">Stock / Jumlah</label>
                                    <input type="number" className="w-full bg-white/5 border border-white/10 rounded-xl text-white focus:ring-yellow-500 text-sm" placeholder="1000" onChange={(e) => setData("stock", e.target.value)} />
                                </div>
                                <div>
                                    <label className="block text-[10px] font-black text-gray-500 uppercase mb-2">Unit / Satuan</label>
                                    <select className="w-full bg-white/5 border border-white/10 rounded-xl text-white focus:ring-yellow-500 text-sm" onChange={(e) => setData("unit", e.target.value)}>
                                        <option value="Yard">Yard</option>
                                        <option value="KG">Kilogram</option>
                                        <option value="Roll">Roll</option>
                                    </select>
                                </div>
                                <div>
                                    <label className="block text-[10px] font-black text-gray-500 uppercase mb-2">Warehouse Location / Lokasi</label>
                                    <input type="text" className="w-full bg-white/5 border border-white/10 rounded-xl text-white focus:ring-yellow-500 text-sm" placeholder="Cikarang / Bandung" onChange={(e) => setData("warehouse_location", e.target.value)} />
                                </div>
                            </div>

                            {/* WhatsApp Contact */}
                            <div>
                                <label className="block text-[10px] font-black text-yellow-500 uppercase mb-2">WhatsApp Inquiry / Kontak Penjual</label>
                                <div className="relative">
                                    <span className="absolute left-4 top-3 text-gray-500 text-sm">+</span>
                                    <input type="text" className="w-full bg-yellow-500/5 border border-yellow-500/20 pl-8 rounded-xl text-white focus:ring-yellow-500 text-sm" placeholder="628123456789" onChange={(e) => setData("whatsapp_contact", e.target.value)} />
                                </div>
                            </div>

                            {/* Deskripsi */}
                            <div>
                                <label className="block text-[10px] font-black text-gray-500 uppercase mb-2">Technical Specs / Spesifikasi</label>
                                <textarea className="w-full bg-white/5 border border-white/10 rounded-xl text-white h-24 focus:ring-yellow-500 text-sm" placeholder="Warna, komposisi bahan, dll..." onChange={(e) => setData("description", e.target.value)}></textarea>
                            </div>

                            <button type="submit" disabled={processing} className="w-full bg-yellow-500 text-[#0a192f] py-4 rounded-2xl font-black text-xs uppercase hover:bg-yellow-400 transition shadow-xl shadow-yellow-500/10">
                                {processing ? "Uploading..." : "Publish to Market / Tayangkan di Bursa"}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
