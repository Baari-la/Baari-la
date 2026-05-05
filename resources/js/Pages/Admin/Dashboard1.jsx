import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";

export default function Dashboard({ auth, stats }) {
    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Admin Management" />

            <div className="py-12 bg-[#0a192f] min-h-screen text-white">
                <div className="max-w-7xl mx-auto px-6">
                    {/* HEADER & QUICK ACTION */}
                    <div className="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
                        <div>
                            <h1 className="text-3xl font-black uppercase italic tracking-tighter">
                                Admin{" "}
                                <span className="text-yellow-500">
                                    Command Center
                                </span>
                            </h1>
                            <p className="text-gray-500 text-[10px] font-bold uppercase tracking-widest mt-1">
                                Management & Industrial Intelligence Oversight
                            </p>
                        </div>

                        {/* TOMBOL TAMBAH DATA (QUICK ACTION) */}
                        <Link
                            href={route("companies.create")}
                            className="bg-blue-600 hover:bg-blue-500 text-white px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all shadow-xl shadow-blue-600/20"
                        >
                            <i className="fas fa-plus mr-2"></i> Tambah
                            Perusahaan Baru
                        </Link>
                    </div>

                    {/* STATS GRID */}
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                        <div className="bg-white/5 border border-white/10 p-8 rounded-[35px]">
                            <p className="text-gray-500 text-[8px] font-black uppercase tracking-widest mb-2">
                                Total Database
                            </p>
                            <h3 className="text-4xl font-black italic">
                                {stats.total_companies}
                            </h3>
                        </div>
                        <div className="bg-yellow-500/10 border border-yellow-500/20 p-8 rounded-[35px]">
                            <p className="text-yellow-500 text-[8px] font-black uppercase tracking-widest mb-2">
                                Gold Members
                            </p>
                            <h3 className="text-4xl font-black text-yellow-500 italic">
                                {stats.gold_members}
                            </h3>
                        </div>
                        <div className="bg-blue-500/10 border border-blue-500/20 p-8 rounded-[35px]">
                            <p className="text-blue-400 text-[8px] font-black uppercase tracking-widest mb-2">
                                Pending Requests
                            </p>
                            <h3 className="text-4xl font-black text-blue-400 italic">
                                {stats.premium_requests}
                            </h3>
                        </div>
                    </div>

                    {/* MENU PENGELOLAAN DATA */}
                    <div className="bg-white/5 border border-white/10 rounded-[40px] overflow-hidden">
                        <div className="p-8 border-b border-white/5 flex justify-between items-center">
                            <h3 className="font-black uppercase italic text-sm tracking-tighter">
                                Database Management
                            </h3>
                            <Link
                                href={route("companies.index")}
                                className="text-gray-500 hover:text-white text-[10px] font-bold uppercase tracking-widest transition-all"
                            >
                                Lihat Semua Direktori →
                            </Link>
                        </div>

                        <div className="p-8">
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {/* MENU EDIT DATA */}
                                <div className="p-6 bg-white/5 rounded-3xl border border-white/5 hover:border-blue-500/30 transition-all group">
                                    <h4 className="text-xs font-black uppercase tracking-widest mb-4">
                                        Pemutakhiran Data
                                    </h4>
                                    <p className="text-gray-500 text-[10px] mb-6 leading-relaxed">
                                        Lakukan perubahan informasi, status
                                        verifikasi, atau nomor anggota untuk
                                        1.982 perusahaan.
                                    </p>
                                    <Link
                                        href={route("companies.index")}
                                        className="inline-block bg-white/10 group-hover:bg-blue-600 text-white px-6 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all"
                                    >
                                        Mulai Edit Data
                                    </Link>
                                </div>

                                {/* MENU STATUS PREMIUM */}
                                <div className="p-6 bg-white/5 rounded-3xl border border-white/5 hover:border-yellow-500/30 transition-all group">
                                    <h4 className="text-xs font-black uppercase tracking-widest mb-4">
                                        Kelola Gold Member
                                    </h4>
                                    <p className="text-gray-500 text-[10px] mb-6 leading-relaxed">
                                        Aktifkan status Premium/Gold Member bagi
                                        perusahaan yang telah melakukan
                                        registrasi resmi.
                                    </p>
                                    <Link
                                        href={route("admin.dashboard")} // Bapak bisa ganti ke route khusus request nantinya
                                        className="inline-block bg-white/10 group-hover:bg-yellow-500 group-hover:text-[#0a192f] text-white px-6 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all"
                                    >
                                        Proses Permintaan
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
