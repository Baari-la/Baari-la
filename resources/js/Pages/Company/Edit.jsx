import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, useForm, Link } from "@inertiajs/react";

export default function Edit({ auth, company }) {
    // Inisialisasi form dengan data lama dari database
    const isEn = auth.locale === "en";
    const { data, setData, post, processing, errors } = useForm({
        _method: "POST", // Gunakan spoofing method untuk upload file/data yang aman
        nama_perusahaan: company.nama_perusahaan || "",
        sektor: company.sektor || "",
        city: company.city || "",
        produk: company.produk || "",
        membership_type: company.membership_type || "public",
        pimpinan: company.pimpinan || "",
        tenaga_kerja: company.tenaga_kerja || "",
        pasar_ekspor: company.pasar_ekspor || "",
        alamat_lengkap: company.alamat_lengkap || "",
        telepon: company.telepon || "",
        email_web: company.email_web || "",
        stock_ready_caption: company.stock_ready_caption || "",
        stock_qty: company.stock_qty || 0,
        stock_unit: company.stock_unit || "Kg",
        price: company.price || 0,
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        // Arahkan ke rute update
        post(route("companies.update", company.id));
    };

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title={`Edit - ${company.nama_perusahaan}`} />

            <div className="py-12 bg-[#0a192f] min-h-screen text-white">
                <div className="max-w-5xl mx-auto px-6">
                    <div className="flex items-center gap-4 mb-10">
                        <div className="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-600/30">
                            <i className="fas fa-edit text-white"></i>
                        </div>
                        <div>
                            <h1 className="text-3xl font-black uppercase italic tracking-tighter text-white leading-none">
                                Admin{" "}
                                <span className="text-blue-500">
                                    Data Editor
                                </span>
                            </h1>
                            <p className="text-gray-500 text-[10px] font-bold uppercase tracking-[0.3em] mt-2">
                                Sedang mengedit: {company.nama_perusahaan}
                            </p>
                        </div>
                    </div>
                    <form
                        onSubmit={handleSubmit}
                        className="bg-white/5 border border-white/10 p-10 rounded-[40px] space-y-8 backdrop-blur-xl"
                    >
                        {/* SECTION 1: IDENTITAS & MEMBERSHIP */}
                        <div>
                            <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-3">
                                Membership Tier
                            </label>
                            <div
                                className={`w-full bg-white/5 border rounded-2xl py-4 px-6 flex items-center justify-between transition-all duration-500 ${
                                    company.membership_type === "gold_member"
                                        ? "border-yellow-500/40 shadow-[0_0_20px_rgba(234,179,8,0.1)]"
                                        : "border-white/10"
                                }`}
                            >
                                <div className="flex items-center gap-3">
                                    {/* Ikon Mahkota untuk Gold Member */}
                                    {company.membership_type ===
                                        "gold_member" && (
                                        <i className="fas fa-crown text-yellow-500 text-xs animate-bounce"></i>
                                    )}
                                    <span
                                        className={`font-black uppercase italic tracking-tighter text-sm ${
                                            company.membership_type ===
                                            "gold_member"
                                                ? "text-yellow-500"
                                                : "text-gray-300"
                                        }`}
                                    >
                                        {company.membership_type ===
                                        "gold_member"
                                            ? "GOLD MEMBER (PREMIUM)"
                                            : "PUBLIC (BASIC)"}
                                    </span>
                                </div>

                                <div className="flex flex-col items-end">
                                    <div className="flex items-center gap-2">
                                        <i className="fas fa-shield-check text-[10px] text-blue-400"></i>
                                        <span className="text-[7px] font-black text-blue-400 uppercase tracking-widest">
                                            Verified by API Jakarta
                                        </span>
                                    </div>
                                    <span className="text-[6px] text-gray-600 uppercase font-bold mt-1 tracking-tighter">
                                        System Lock Active
                                    </span>
                                </div>
                            </div>
                            <p className="text-[8px] text-gray-600 mt-3 italic font-medium leading-tight">
                                * Status ini merupakan identitas resmi Anda
                                dalam ekosistem intelijen industri nasional.
                            </p>
                        </div>

                        {/* SECTION 2: KLASIFIKASI & PIMPINAN */}
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-8 pt-6 border-t border-white/5">
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-3">
                                    Sektor Industri
                                </label>
                                <input
                                    type="text"
                                    value={data.sektor}
                                    onChange={(e) =>
                                        setData("sektor", e.target.value)
                                    }
                                    className="w-full bg-white/5 border-white/10 rounded-2xl focus:ring-blue-500"
                                />
                            </div>
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-3">
                                    Category Tag
                                </label>
                                <input
                                    type="text"
                                    value={data.category}
                                    onChange={(e) =>
                                        setData("category", e.target.value)
                                    }
                                    className="w-full bg-white/5 border-white/10 rounded-2xl focus:ring-blue-500"
                                />
                            </div>
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-3">
                                    Nama Pimpinan / CEO
                                </label>
                                <input
                                    type="text"
                                    value={data.pimpinan}
                                    onChange={(e) =>
                                        setData("pimpinan", e.target.value)
                                    }
                                    className="w-full bg-white/5 border-white/10 rounded-2xl focus:ring-blue-500"
                                />
                            </div>
                        </div>

                        {/* SECTION 3: KONTAK & LOKASI */}
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-8 pt-6 border-t border-white/5">
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-3">
                                    City / Kota
                                </label>
                                <input
                                    type="text"
                                    value={data.city}
                                    onChange={(e) =>
                                        setData("city", e.target.value)
                                    }
                                    className="w-full bg-white/5 border-white/10 rounded-2xl focus:ring-blue-500"
                                />
                            </div>
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-3">
                                    Wilayah / Provinsi
                                </label>
                                <input
                                    type="text"
                                    value={data.wilayah}
                                    onChange={(e) =>
                                        setData("wilayah", e.target.value)
                                    }
                                    className="w-full bg-white/5 border-white/10 rounded-2xl focus:ring-blue-500"
                                />
                            </div>
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-3">
                                    Telepon
                                </label>
                                <input
                                    type="text"
                                    value={data.telepon}
                                    onChange={(e) =>
                                        setData("telepon", e.target.value)
                                    }
                                    className="w-full bg-white/5 border-white/10 rounded-2xl focus:ring-blue-500"
                                />
                            </div>
                            <div className="md:col-span-2">
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-3">
                                    Email / Website
                                </label>
                                <input
                                    type="text"
                                    value={data.email_web}
                                    onChange={(e) =>
                                        setData("email_web", e.target.value)
                                    }
                                    className="w-full bg-white/5 border-white/10 rounded-2xl focus:ring-blue-500"
                                />
                            </div>
                        </div>

                        {/* SECTION 4: PRODUKSI & PASAR */}
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-8 pt-6 border-t border-white/5">
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-3">
                                    Tenaga Kerja
                                </label>
                                <input
                                    type="text"
                                    value={data.tenaga_kerja}
                                    onChange={(e) =>
                                        setData("tenaga_kerja", e.target.value)
                                    }
                                    className="w-full bg-white/5 border-white/10 rounded-2xl focus:ring-blue-500"
                                />
                            </div>
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-3">
                                    Produk Utama
                                </label>
                                <input
                                    type="text"
                                    value={data.produk}
                                    onChange={(e) =>
                                        setData("produk", e.target.value)
                                    }
                                    className="w-full bg-white/5 border-white/10 rounded-2xl focus:ring-blue-500"
                                />
                            </div>
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-3">
                                    Pasar Ekspor
                                </label>
                                <input
                                    type="text"
                                    value={data.pasar_ekspor}
                                    onChange={(e) =>
                                        setData("pasar_ekspor", e.target.value)
                                    }
                                    className="w-full bg-white/5 border-white/10 rounded-2xl focus:ring-blue-500"
                                />
                            </div>
                            <div className="md:col-span-3">
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-3">
                                    Alamat Lengkap Strategis
                                </label>
                                <textarea
                                    value={data.alamat_lengkap}
                                    onChange={(e) =>
                                        setData(
                                            "alamat_lengkap",
                                            e.target.value,
                                        )
                                    }
                                    className="w-full bg-white/5 border-white/10 rounded-2xl focus:ring-blue-500"
                                    rows="3"
                                ></textarea>
                            </div>
                        </div>

                        {/* <div className="flex gap-4 pt-6">
                            <button
                                disabled={processing}
                                className="flex-grow bg-blue-600 text-white font-black py-5 rounded-3xl uppercase tracking-widest hover:bg-blue-500 transition-all shadow-2xl shadow-blue-600/20 active:scale-95"
                            >
                                {processing
                                    ? "Updating..."
                                    : "Update Big Data Intelligence"}
                            </button>
                            <Link
                                href={route("companies.index")}
                                className="px-10 py-5 border border-white/10 rounded-3xl font-black uppercase text-[10px] tracking-widest hover:bg-white/5 transition-all flex items-center"
                            >
                                Cancel
                            </Link>
                        </div> */}
                        {/* Inventory */}
                        {/* MASUKKAN DI DALAM <form> PADA Edit.jsx */}
                        <div className="bg-emerald-500/5 border border-emerald-500/20 p-10 rounded-[50px] mb-10 shadow-2xl shadow-emerald-500/5">
                            <div className="flex items-center gap-4 mb-8 border-b border-white/5 pb-6">
                                <div className="h-12 w-12 bg-emerald-500/20 rounded-2xl flex items-center justify-center text-emerald-500 shadow-lg">
                                    <i className="fas fa-layer-group text-xl"></i>
                                </div>
                                <div>
                                    <h3 className="text-white text-lg font-black uppercase italic tracking-tighter">
                                        Inventory &{" "}
                                        <span className="text-emerald-500">
                                            Price Radar
                                        </span>
                                    </h3>
                                    <p className="text-gray-500 text-[10px] font-bold uppercase tracking-widest italic">
                                        Update your ready stock for the National
                                        Intelligence Bursa
                                    </p>
                                </div>
                            </div>

                            <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                                {/* Deskripsi Barang */}
                                <div className="md:col-span-2">
                                    <label className="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3 block italic">
                                        Ready Stock Description
                                    </label>
                                    <input
                                        type="text"
                                        value={data.stock_ready_caption || ""}
                                        onChange={(e) =>
                                            setData(
                                                "stock_ready_caption",
                                                e.target.value,
                                            )
                                        }
                                        placeholder="Ex: Benang Katun Combed 30s - High Grade"
                                        className="w-full bg-[#0a192f] border-white/10 rounded-2xl text-white py-4 px-6 focus:ring-emerald-500"
                                    />
                                </div>

                                {/* Qty & Unit */}
                                <div className="flex gap-4">
                                    <div className="flex-1">
                                        <label className="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3 block italic">
                                            Quantity
                                        </label>
                                        <input
                                            type="number"
                                            value={data.stock_qty || 0}
                                            onChange={(e) =>
                                                setData(
                                                    "stock_qty",
                                                    e.target.value,
                                                )
                                            }
                                            className="w-full bg-[#0a192f] border-white/10 rounded-2xl text-white py-4 px-6"
                                        />
                                    </div>
                                    <div className="w-1/3">
                                        <label className="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3 block italic">
                                            Unit
                                        </label>
                                        <select
                                            value={data.stock_unit || "Kg"}
                                            onChange={(e) =>
                                                setData(
                                                    "stock_unit",
                                                    e.target.value,
                                                )
                                            }
                                            className="w-full bg-[#0a192f] border-white/10 rounded-2xl text-white py-4 px-6"
                                        >
                                            <option value="Kg">Kg</option>
                                            <option value="Yard">Yard</option>
                                            <option value="Pcs">Pcs</option>
                                            <option value="Roll">Roll</option>
                                        </select>
                                    </div>
                                </div>

                                {/* Harga */}
                                <div>
                                    <label className="text-[10px] font-black text-yellow-500 uppercase tracking-widest mb-3 block italic">
                                        Price Benchmark (IDR)
                                    </label>
                                    <div className="relative">
                                        <span className="absolute left-6 top-1/2 -translate-y-1/2 text-gray-500 font-bold text-sm">
                                            Rp
                                        </span>
                                        <input
                                            type="number"
                                            value={data.price || 0}
                                            onChange={(e) =>
                                                setData("price", e.target.value)
                                            }
                                            className="w-full bg-[#0a192f] border-white/10 rounded-2xl text-white py-4 pl-14 pr-6 focus:ring-yellow-500"
                                        />
                                    </div>
                                </div>

                                {/* GLOBAL TRADE ADVISORY INSTRUCTION */}
                                <div className="mb-8 flex items-start gap-4 p-6 bg-blue-500/10 border border-blue-500/20 rounded-[30px]">
                                    <div className="h-10 w-10 bg-blue-500 rounded-2xl flex items-center justify-center text-white shadow-lg flex-shrink-0 animate-pulse">
                                        <i className="fas fa-globe-americas"></i>
                                    </div>
                                    <div>
                                        <h4 className="text-blue-400 text-[10px] font-black uppercase tracking-widest mb-1">
                                            Global Trade Advisory
                                        </h4>
                                        <p className="text-gray-300 text-[11px] leading-relaxed font-medium italic">
                                            {isEn
                                                ? "Your stock data will be broadcasted to international buyers. We highly recommend using English for product descriptions to maximize global reach."
                                                : "Data stok Anda akan disiarkan ke pembeli internasional. Kami sangat menyarankan penggunaan Bahasa Inggris untuk deskripsi produk guna memaksimalkan jangkauan global."}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div className="flex gap-4 pt-6">
                                <button
                                    disabled={processing}
                                    className="flex-grow bg-blue-600 text-white font-black py-5 rounded-3xl uppercase tracking-widest hover:bg-blue-500 transition-all shadow-2xl shadow-blue-600/20 active:scale-95"
                                >
                                    {processing
                                        ? "Updating..."
                                        : "Update Big Data Intelligence"}
                                </button>
                                <Link
                                    href={route("companies.index")}
                                    className="px-10 py-5 border border-white/10 rounded-3xl font-black uppercase text-[10px] tracking-widest hover:bg-white/5 transition-all flex items-center"
                                >
                                    Cancel
                                </Link>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
