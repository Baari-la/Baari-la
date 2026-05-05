import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, useForm } from "@inertiajs/react";

export default function Create({ auth }) {
    const { data, setData, post, processing, errors } = useForm({
        nama_perusahaan: "",
        sektor: "",
        wilayah: "",
        alamat_lengkap: "",
        city: "",
        telepon: "",
        email_web: "",
        pimpinan: "",
        tenaga_kerja: "",
        pasar_ekspor: "",
        produk: "",
        category: "",
        membership_type: "public",
        tahun_berdiri: "",
        status_verifikasi: "verified",
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route("companies.store"));
    };

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Input Big Data Industri" />
            <div className="py-12 bg-[#0a192f] min-h-screen text-white">
                <div className="max-w-5xl mx-auto px-6">
                    <div className="mb-10">
                        <h1 className="text-4xl font-black italic uppercase text-yellow-500 leading-none">
                            New Industrial Entry
                        </h1>
                        <p className="text-gray-500 text-[10px] font-bold uppercase tracking-widest mt-2">
                            Menambahkan entitas baru ke dalam Big Data Nasional
                        </p>
                    </div>

                    <form
                        onSubmit={handleSubmit}
                        className="bg-white/5 border border-white/10 p-10 rounded-[40px] space-y-8"
                    >
                        {/* SECTION 1: IDENTITAS UTAMA */}
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div className="md:col-span-2">
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                    Nama Perusahaan
                                </label>
                                <input
                                    type="text"
                                    value={data.nama_perusahaan}
                                    onChange={(e) =>
                                        setData(
                                            "nama_perusahaan",
                                            e.target.value,
                                        )
                                    }
                                    className="w-full bg-white/5 border-white/10 rounded-2xl focus:ring-yellow-500 text-white font-bold"
                                    placeholder="Contoh: PT LABDA ANUGERAH TEKSTIL"
                                />
                            </div>
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                    Pimpinan / CEO
                                </label>
                                <input
                                    type="text"
                                    value={data.pimpinan}
                                    onChange={(e) =>
                                        setData("pimpinan", e.target.value)
                                    }
                                    className="w-full bg-white/5 border-white/10 rounded-2xl focus:ring-yellow-500 text-white"
                                />
                            </div>
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                    Tahun Berdiri
                                </label>
                                <input
                                    type="text"
                                    value={data.tahun_berdiri}
                                    onChange={(e) =>
                                        setData("tahun_berdiri", e.target.value)
                                    }
                                    className="w-full bg-white/5 border-white/10 rounded-2xl focus:ring-yellow-500 text-white"
                                />
                            </div>
                        </div>

                        {/* SECTION 2: KLASIFIKASI */}
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                    Sektor
                                </label>
                                <input
                                    type="text"
                                    value={data.sektor}
                                    onChange={(e) =>
                                        setData("sektor", e.target.value)
                                    }
                                    className="w-full bg-white/5 border-white/10 rounded-2xl focus:ring-yellow-500 text-white"
                                    placeholder="MISAL: GARMENT"
                                />
                            </div>
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                    Category
                                </label>
                                <input
                                    type="text"
                                    value={data.category}
                                    onChange={(e) =>
                                        setData("category", e.target.value)
                                    }
                                    className="w-full bg-white/5 border-white/10 rounded-2xl focus:ring-yellow-500 text-white"
                                />
                            </div>
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                    Membership
                                </label>
                                <select
                                    value={data.membership_type}
                                    onChange={(e) =>
                                        setData(
                                            "membership_type",
                                            e.target.value,
                                        )
                                    }
                                    className="w-full bg-[#0a192f] border-white/10 rounded-2xl focus:ring-yellow-500"
                                >
                                    <option value="public">Public</option>
                                    <option value="gold_member">
                                        Gold Member
                                    </option>
                                </select>
                            </div>
                        </div>

                        {/* SECTION 3: LOKASI & KONTAK */}
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                    City
                                </label>
                                <input
                                    type="text"
                                    value={data.city}
                                    onChange={(e) =>
                                        setData("city", e.target.value)
                                    }
                                    className="w-full bg-white/5 border-white/10 rounded-2xl focus:ring-yellow-500 text-white"
                                />
                            </div>
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                    Telepon
                                </label>
                                <input
                                    type="text"
                                    value={data.telepon}
                                    onChange={(e) =>
                                        setData("telepon", e.target.value)
                                    }
                                    className="w-full bg-white/5 border-white/10 rounded-2xl focus:ring-yellow-500 text-white"
                                />
                            </div>
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                    Email / Web
                                </label>
                                <input
                                    type="text"
                                    value={data.email_web}
                                    onChange={(e) =>
                                        setData("email_web", e.target.value)
                                    }
                                    className="w-full bg-white/5 border-white/10 rounded-2xl focus:ring-yellow-500 text-white"
                                />
                            </div>
                            <div className="md:col-span-3">
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                    Alamat Lengkap
                                </label>
                                <textarea
                                    value={data.alamat_lengkap}
                                    onChange={(e) =>
                                        setData(
                                            "alamat_lengkap",
                                            e.target.value,
                                        )
                                    }
                                    className="w-full bg-white/5 border-white/10 rounded-2xl focus:ring-yellow-500 text-white"
                                    rows="2"
                                ></textarea>
                            </div>
                        </div>

                        {/* SECTION 4: PRODUKSI & PASAR */}
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                    Produk Utama
                                </label>
                                <input
                                    type="text"
                                    value={data.produk}
                                    onChange={(e) =>
                                        setData("produk", e.target.value)
                                    }
                                    className="w-full bg-white/5 border-white/10 rounded-2xl focus:ring-yellow-500 text-white"
                                />
                            </div>
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                    Tenaga Kerja
                                </label>
                                <input
                                    type="text"
                                    value={data.tenaga_kerja}
                                    onChange={(e) =>
                                        setData("tenaga_kerja", e.target.value)
                                    }
                                    className="w-full bg-white/5 border-white/10 rounded-2xl focus:ring-yellow-500 text-white"
                                    placeholder="Contoh: 500 Orang"
                                />
                            </div>
                            <div className="md:col-span-2">
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                    Pasar Ekspor
                                </label>
                                <input
                                    type="text"
                                    value={data.pasar_ekspor}
                                    onChange={(e) =>
                                        setData("pasar_ekspor", e.target.value)
                                    }
                                    className="w-full bg-white/5 border-white/10 rounded-2xl focus:ring-yellow-500 text-white"
                                    placeholder="Contoh: USA, JAPAN, GERMANY"
                                />
                            </div>
                        </div>

                        <button
                            type="submit"
                            disabled={processing}
                            className="w-full bg-yellow-500 text-[#0a192f] font-black py-5 rounded-3xl uppercase tracking-widest hover:bg-yellow-400 transition-all shadow-2xl active:scale-95"
                        >
                            {processing
                                ? "Processing..."
                                : "Sync to Big Data Hub"}
                        </button>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
