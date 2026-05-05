import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";

export default function InventoryIndex({ auth, inventories = [] }) {
    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Industrial Bursa - Digestex V2" />
            
            <div className="py-12 bg-[#0a192f] min-h-screen">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    
                    {/* Header Bursa */}
                    <div className="flex justify-between items-end mb-10 border-b border-white/10 pb-8">
                        <div>
                            <h2 className="text-3xl font-black text-yellow-500 uppercase tracking-tighter">Industrial Bursa</h2>
                            <p className="text-gray-400 text-xs mt-2 uppercase tracking-widest">Bursa Bahan Baku & Sisa Produksi Antar Anggota</p>
                        </div>
                        <Link href={route('inventory.create')} className="bg-yellow-500 text-[#0a192f] px-6 py-3 rounded-2xl font-black text-xs uppercase hover:bg-yellow-400 transition">
                            + List New Material
                        </Link>
                    </div>

                    {/* Grid Inventori */}
                    <div className="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-8">
                        {inventories.map((item) => (
                            <div key={item.id} className="bg-white/5 rounded-[30px] border border-white/10 overflow-hidden hover:border-yellow-500/30 transition group">
                                {/* Placeholder Gambar */}
                                <div className="h-48 bg-gray-800 flex items-center justify-center relative">
                                    <span className="text-[10px] bg-yellow-500 text-[#0a192f] px-3 py-1 rounded-full font-black absolute top-4 left-4 uppercase">
                                        {item.category}
                                    </span>
                                    <i className="fas fa-box-open text-4xl text-white/10"></i>
                                </div>

                                <div className="p-6 space-y-4">
                                    <div>
                                        <h4 className="text-lg font-bold text-white leading-tight line-clamp-1">{item.name}</h4>
                                        <p className="text-[10px] text-gray-500 uppercase font-bold mt-1 tracking-widest">
                                            <i className="fas fa-map-marker-alt mr-1"></i> {item.warehouse_location}
                                        </p>
                                    </div>

                                    <div className="flex justify-between items-center border-y border-white/5 py-3">
                                        <div className="text-center">
                                            <p className="text-[9px] text-gray-500 uppercase font-black">Stock</p>
                                            <p className="text-sm font-bold text-white">{item.stock} {item.unit}</p>
                                        </div>
                                        <div className="h-8 w-[1px] bg-white/10"></div>
                                        <div className="text-center">
                                            <p className="text-[9px] text-gray-500 uppercase font-black">Price</p>
                                            <p className="text-sm font-bold text-yellow-500">Inquiry</p>
                                        </div>
                                    </div>

                                    <a 
                                        href={`https://wa.me{item.whatsapp_contact}?text=Halo, saya tertarik dengan ${item.name} di Digestex V2`}
                                        target="_blank"
                                        className="block w-full text-center py-4 bg-white/5 border border-white/10 rounded-2xl text-xs font-black uppercase hover:bg-yellow-500 hover:text-[#0a192f] transition"
                                    >
                                        Contact Seller
                                    </a>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
