import { Link } from "@inertiajs/react";
import React, { useState } from "react";

export default function LocalSolutions({
    materials = [],
    inventoryItems = [],
    partnershipItems = [], // <-- Tangkap props kemitraan baru
    isLoggedIn = false,
    auth = null,
    memberStatus = "Free",
    isEn = false,
}) {
    // 1. STATE KONTROL TIGA JENDELA MODAL INDEPENDEN
    const [isBursaOpen, setIsBursaOpen] = useState(false);
    const [isRegulasiOpen, setIsRegulasiOpen] = useState(false);
    const [isMatchOpen, setIsMatchOpen] = useState(false); // <-- Modal Ke-3 Aktif

    // --- SENSOR PINTAR LEVEL ADMIN (TRUE/FALSE) ---
    const isAdmin = auth?.user?.role === "admin";

    // State Penyaringan Data masing-masing modal
    const [regSearch, setRegSearch] = useState("");
    const [regCategory, setRegCategory] = useState("");
    const [bursaSearch, setBursaSearch] = useState("");
    const [bursaCategory, setBursaCategory] = useState("");
    const [matchSearch, setMatchSearch] = useState("");
    const [matchCategory, setMatchCategory] = useState("");

    const regulasiCategories = [
        { id: "", label: isEn ? "All" : "Semua" },
        { id: "Regulasi", label: isEn ? "Regulations" : "Regulasi" },
        { id: "Seminar", label: isEn ? "Seminars" : "Seminar" },
        { id: "Sosialisasi", label: isEn ? "Official" : "Sosialisasi" },
    ];

    const bursaCategories = [
        { id: "", label: isEn ? "All Fabrics" : "Semua Bahan" },
        { id: "Fabric", label: isEn ? "Fabric" : "Kain" },
        { id: "Yarn", label: isEn ? "Yarn" : "Benang" },
        { id: "Accessories", label: isEn ? "Accessories" : "Aksesoris" },
        {
            id: "Footwear",
            label: isEn ? "Footwear Materials" : "Bahan Alas Kaki",
        },
        {
            id: "Bag",
            label: isEn ? "Bag & Leather Materials" : "Bahan Tas & Kulit",
        },
        /* 🥊 EXPANSION: KULIT JADI & SARUNG TANGAN */
        {
            id: "Gloves-Leather",
            label: isEn
                ? "Gloves & Tanned Leather"
                : "Sarung Tangan & Kulit Olahan",
        },
    ];

    const matchCategories = [
        { id: "", label: isEn ? "All Sectors" : "Semua Sektor" },
        { id: "Technology", label: isEn ? "Technology" : "Teknologi" },
        { id: "Machinery", label: isEn ? "Machinery" : "Mesin & Suku Cadang" },
        { id: "Raw Material", label: isEn ? "Raw Material" : "Bahan Baku" },
        {
            id: "Footwear",
            label: isEn ? "Footwear Assembly" : "Manufaktur Alas Kaki",
        },
        {
            id: "Bag",
            label: isEn
                ? "Bag & Luggage Manufacturing"
                : "Manufaktur Tas & Koper",
        },
        /* 🥊 EXPANSION: MANUFAKTUR SARUNG TANGAN & PENYAMAKAN KULIT */
        {
            id: "Gloves-Leather",
            label: isEn
                ? "Gloves & Leather Tanning"
                : "Manufaktur Sarung Tangan & Penyamakan",
        },
    ];

    // 2. SISTEM FILTER CLIENT-SIDE DENGAN OPTIONAL CHAINING (ANTI-CRASH)
    const filteredRegulations = materials.filter((item) => {
        const titleMatch = (item?.title || "")
            .toLowerCase()
            .includes(regSearch.toLowerCase());
        const speakerMatch = (item?.speaker || "")
            .toLowerCase()
            .includes(regSearch.toLowerCase());
        return (
            (titleMatch || speakerMatch) &&
            (regCategory === "" || item?.category === regCategory)
        );
    });

    const filteredInventory = inventoryItems.filter((item) => {
        const nameMatch = (item?.name || "")
            .toLowerCase()
            .includes(bursaSearch.toLowerCase());
        const locMatch = (item?.warehouse_location || "")
            .toLowerCase()
            .includes(bursaSearch.toLowerCase());
        return (
            (nameMatch || locMatch) &&
            (bursaCategory === "" ||
                (item?.category || "").toLowerCase() ===
                    bursaCategory.toLowerCase())
        );
    });

    const filteredPartnerships = partnershipItems.filter((item) => {
        const nameMatch = (item?.name || "")
            .toLowerCase()
            .includes(matchSearch.toLowerCase());
        const taglineMatch = (item?.tagline || "")
            .toLowerCase()
            .includes(matchSearch.toLowerCase());
        return (
            (nameMatch || taglineMatch) &&
            (matchCategory === "" ||
                (item?.category || "").toLowerCase() ===
                    matchCategory.toLowerCase())
        );
    });

    // 3. VALIDASI HAK AKSES PREMIUM HUBUNGI VENDOR
    const handleContactOwner = (whatsappNumber, title, context) => {
        if (
            !memberStatus?.includes("Premium") &&
            !auth?.user?.member_status?.includes("Premium")
        ) {
            alert(
                isEn
                    ? `🔒 Premium Access Restricted\nInitiating B2B connection for "${title}" requires an API Premium tier.`
                    : `🔒 Akses Transaksi Terkunci\nFitur pengajuan koneksi kemitraan dengan "${title}" eksklusif untuk Anggota Premium API.`,
            );
            return;
        }
        // PERBAIKAN: Menggunakan sintaks template literal dan protokol https:// yang valid
        window.open(
            `https://wa.me{whatsappNumber}?text=Halo%20${encodeURIComponent(title)},%20kami%20tertarik%20menjajaki%20B2B%20Partnership%20${context}%20melalui%20Digestex%20V2.`,
            "_blank",
        );
    };

    // 4. STRATEGI BYPASS ENKRIPSI PHP STREAMER (ANTI-404)
    const handleDownloadReg = (fileUrl, tier, title) => {
        const userIsLoggedIn = isLoggedIn || auth?.user !== null;
        const userMemberStatus =
            memberStatus || auth?.user?.member_status || "Free";
        const userRole = auth?.user?.role || "user";

        if (userRole === "admin") {
            if (!fileUrl) {
                alert(
                    isEn
                        ? "⚠️ File path empty."
                        : "⚠️ Berkas belum diunggah admin.",
                );
                return;
            }
            window.open(
                `/download-file?path=${encodeURIComponent(fileUrl)}`,
                "_blank",
            );
            return;
        }

        if (tier === "Premium" && !userMemberStatus.includes("Premium")) {
            alert(
                isEn
                    ? `❌ Premium Tier Required for: ${title}`
                    : `❌ Memerlukan Akun Premium untuk: ${title}`,
            );
            return;
        }

        if (
            (tier === "Member" ||
                tier === "Regulasi" ||
                tier === "Sosialisasi") &&
            !userIsLoggedIn
        ) {
            alert(
                isEn
                    ? `🔒 Google Login Required (Free Access)\nTo download "${title}", please sign in with Google for free promotional access.`
                    : `🔒 Diperlukan Login Google (Akses Gratis)\nUntuk mengunduh "${title}", silakan masuk menggunakan akun Google Anda secara gratis sebagai langkah promosi.`,
            );
            window.location.href = route("google.login");
            return;
        }

        if (!fileUrl) {
            alert(
                isEn
                    ? "⚠️ Berkas tidak ditemukan."
                    : "⚠️ Berkas dokumen tidak tersedia di server.",
            );
            return;
        }

        window.open(
            `/download-file?path=${encodeURIComponent(fileUrl)}`,
            "_blank",
        );
    };

    return (
        <section className="container mx-auto px-6 py-12 mb-20 max-w-7xl">
            {/* --- GRID UTAMA 3 KARTU SOLUSI STRATEGIS --- */}
            <div className="bg-gradient-to-b from-white/10 to-transparent p-10 rounded-[50px] border border-white/10 shadow-2xl">
                <div className="flex flex-col md:flex-row justify-between items-center gap-10 text-center md:text-left">
                    <div className="max-w-sm text-left border-l-4 border-yellow-500 pl-6">
                        <h2 className="text-3xl font-black uppercase leading-none text-white tracking-tighter">
                            {isEn ? (
                                <>
                                    Strategic Solutions <br />
                                    <span className="text-yellow-500">
                                        For Corporations
                                    </span>
                                </>
                            ) : (
                                <>
                                    Solusi Strategis <br />
                                    <span className="text-yellow-500">
                                        Untuk Perusahaan
                                    </span>
                                </>
                            )}
                        </h2>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6 w-full md:w-auto">
                        {/* KARTU 1: TOKO DIGITAL BAHAN */}
                        <div
                            onClick={() => setIsBursaOpen(true)}
                            className="bg-[#0a192f] p-8 rounded-[35px] border border-white/5 hover:border-yellow-500/30 transition-all duration-500 group hover:-translate-y-2 text-left block cursor-pointer"
                        >
                            <div className="text-yellow-500 mb-4 text-xl">
                                <i className="fas fa-shopping-cart"></i>
                            </div>
                            <h4 className="font-black text-white text-xs uppercase mb-3 tracking-widest">
                                {isEn
                                    ? "Digital Material Store"
                                    : "Toko Digital Bahan"}
                            </h4>
                            <p className="text-[10px] text-gray-500 leading-relaxed font-medium">
                                {isEn
                                    ? "Marketplace for deadstock fabrics & yarns."
                                    : "Lapak komoditas kain & benang sisa produksi."}
                            </p>
                        </div>

                        {/* KARTU 2: PUSAT DATA & REGULASI */}
                        <div
                            onClick={() => setIsRegulasiOpen(true)}
                            className="bg-[#0a192f] p-8 rounded-[35px] border border-white/5 hover:border-yellow-500/30 transition-all duration-500 group hover:-translate-y-2 text-left block cursor-pointer"
                        >
                            <div className="text-yellow-500 mb-4 text-xl">
                                <i className="fas fa-gavel"></i>
                            </div>
                            <h4 className="font-black text-white text-xs uppercase mb-3 tracking-widest">
                                {isEn
                                    ? "Data & Regulation Center"
                                    : "Pusat Data & Regulasi"}
                            </h4>
                            <p className="text-[10px] text-gray-500 leading-relaxed font-medium">
                                {isEn
                                    ? "Industrial policy updates & repository."
                                    : "Update kebijakan industri & repositori kementerian resmi."}
                            </p>
                        </div>

                        {/* KARTU 3: MATCHMAKING KEMITRAAN */}
                        <div
                            onClick={() => setIsMatchOpen(true)}
                            className="bg-[#0a192f] p-8 rounded-[35px] border border-white/5 hover:border-yellow-500/30 transition-all duration-500 group hover:-translate-y-2 text-left block cursor-pointer"
                        >
                            <div className="text-yellow-500 mb-4 text-xl">
                                <i className="fas fa-handshake"></i>
                            </div>
                            <h4 className="font-black text-white text-xs uppercase mb-3 tracking-widest">
                                {isEn
                                    ? "Partnership Matchmaking"
                                    : "Matchmaking Kemitraan"}
                            </h4>
                            <p className="text-[10px] text-gray-500 leading-relaxed font-medium">
                                {isEn
                                    ? "Connect local factories with suppliers."
                                    : "Hubungkan industri lokal dengan penyuplai hulu."}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            {/* ====================================================================== */}
            {/* 🛡️ MODAL POPUP 1: TOKO DIGITAL BAHAN */}
            {/* ====================================================================== */}
            {isBursaOpen && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
                    <div className="bg-gradient-to-b from-[#0B192C] to-[#1E3E62] border border-amber-500/20 w-full max-w-6xl rounded-[40px] shadow-2xl overflow-hidden flex flex-col max-h-[85vh]">
                        <div className="p-6 border-b border-white/5 flex justify-between items-center bg-[#001F3F]/60">
                            <div className="border-l-4 border-emerald-500 pl-4">
                                <h3 className="text-white font-black uppercase text-sm tracking-tight italic">
                                    Toko Digital Komoditas Bahan Baku Tekstil
                                </h3>
                            </div>
                            <button
                                onClick={() => setIsBursaOpen(false)}
                                className="text-gray-400 hover:text-amber-500 text-sm font-black p-2 cursor-pointer focus:outline-none"
                            >
                                <i className="fas fa-times"></i>
                            </button>
                        </div>
                        <div className="p-6 bg-[#001F3F]/30 border-b border-white/5 flex flex-col sm:flex-row gap-4 justify-between items-center">
                            <input
                                type="text"
                                placeholder="Saring nama produk..."
                                value={bursaSearch}
                                onChange={(e) => setBursaSearch(e.target.value)}
                                className="w-full sm:w-72 bg-[#0A192F] border border-white/10 px-5 py-2.5 rounded-full text-[11px] text-white focus:outline-none"
                            />
                            <div className="flex gap-1.5 overflow-x-auto max-w-full scrollbar-hide">
                                {bursaCategories.map((cat) => (
                                    <button
                                        key={cat.id}
                                        onClick={() => setBursaCategory(cat.id)}
                                        className={`px-4 py-2 rounded-xl text-[8px] font-black uppercase transition-all whitespace-nowrap ${
                                            (
                                                bursaCategory || ""
                                            ).toLowerCase() ===
                                            (cat.id || "").toLowerCase()
                                                ? "bg-emerald-500 text-white shadow-md"
                                                : "bg-white/5 text-gray-400"
                                        }`}
                                    >
                                        {cat.label}
                                    </button>
                                ))}
                            </div>
                        </div>
                        <div className="overflow-y-auto p-6 flex-1 bg-black/10">
                            <table className="w-full text-left border-collapse">
                                <thead className="bg-[#0a192f] text-gray-400 uppercase text-[9px] tracking-widest border-b border-white/5">
                                    <tr>
                                        {/* SINKRONISASI LABEL JUDUL TABEL BERDASARKAN LOCALE */}
                                        <th className="pb-4 pt-2 pl-4 w-20">
                                            {isEn ? "Photo" : "Foto"}
                                        </th>
                                        <th className="pb-4 pt-2">
                                            {isEn
                                                ? "Product Specification"
                                                : "Nama Komoditas"}
                                        </th>
                                        <th className="pb-4 pt-2">
                                            {isEn ? "Company" : "Perusahaan"}
                                        </th>
                                        <th className="pb-4 pt-2">
                                            {isEn
                                                ? "Available Stock"
                                                : "Sisa Stok"}
                                        </th>
                                        <th className="pb-4 pt-2">
                                            {isEn
                                                ? "Warehouse Location"
                                                : "Lokasi Gudang"}
                                        </th>
                                        <th className="pb-4 pt-2">
                                            {isEn
                                                ? "Market Price"
                                                : "Harga Lapak"}
                                        </th>
                                        <th className="pb-4 pt-2 text-right pr-4">
                                            {isEn ? "Action" : "Negosiasi"}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody className="text-xs text-gray-300">
                                    {filteredInventory.length === 0 ? (
                                        <tr>
                                            <td
                                                colSpan="7"
                                                className="text-center py-10 text-gray-500 uppercase font-black tracking-wider text-[10px]"
                                            >
                                                {isEn
                                                    ? "No resources listed in digital store"
                                                    : "Belum ada komoditas terdaftar di Toko Digital."}
                                            </td>
                                        </tr>
                                    ) : (
                                        filteredInventory.map((item) => (
                                            <tr
                                                key={item.id}
                                                className="border-b border-white/5 hover:bg-white/5 transition duration-300"
                                            >
                                                {/* COL 1: FOTO MINI KOMODITAS */}
                                                <td className="py-4 pl-4 w-20">
                                                    <div className="w-12 h-12 rounded-xl overflow-hidden bg-slate-800 border border-white/10 shadow-md">
                                                        {item.image ? (
                                                            <img
                                                                src={`/storage/${item.image}`}
                                                                className="w-full h-full object-cover"
                                                                alt={
                                                                    isEn
                                                                        ? item.name_en ||
                                                                          item.name
                                                                        : item.name
                                                                }
                                                            />
                                                        ) : (
                                                            <div className="w-full h-full flex items-center justify-center text-gray-600 bg-slate-800">
                                                                <i className="fas fa-box"></i>
                                                            </div>
                                                        )}
                                                    </div>
                                                </td>

                                                {/* COL 2: SPESIFIKASI PRODUK (DENGAN LOGIKA BILINGUAL JALUR GANDA) */}
                                                <td className="py-4 font-bold text-white max-w-xs">
                                                    <div className="flex flex-col gap-0.5">
                                                        <div className="flex items-center gap-2">
                                                            {/* Deteksi Nama Inggris Otomatis */}
                                                            <span className="text-white text-xs">
                                                                {isEn
                                                                    ? item.name_en ||
                                                                      item.name
                                                                    : item.name}
                                                            </span>
                                                            {item.is_api_member ? (
                                                                <span className="bg-emerald-500/20 text-emerald-400 text-[7px] font-black uppercase px-1.5 py-0.5 rounded-md border border-emerald-500/30 tracking-widest">
                                                                    <i className="fas fa-check-circle"></i>{" "}
                                                                    API Member
                                                                </span>
                                                            ) : (
                                                                <span className="bg-blue-500/10 text-blue-400 text-[7px] font-black uppercase px-1.5 py-0.5 rounded-md border border-blue-500/20 tracking-widest">
                                                                    Tenant Store
                                                                </span>
                                                            )}
                                                        </div>
                                                        <span className="text-[8px] text-gray-500 uppercase font-mono mt-0.5">
                                                            {item.category}
                                                        </span>
                                                        {/* Deteksi Deskripsi Inggris Otomatis */}
                                                        {(isEn
                                                            ? item.description_en ||
                                                              item.description
                                                            : item.description) && (
                                                            <p className="text-[10px] text-gray-400 font-normal normal-case italic mt-1 font-sans leading-normal">
                                                                "
                                                                {isEn
                                                                    ? item.description_en ||
                                                                      item.description
                                                                    : item.description}
                                                                "
                                                            </p>
                                                        )}
                                                    </div>
                                                </td>

                                                {/* COL 3: ENTITAS HUKUM PERUSAHAAN */}
                                                <td className="py-4 font-black text-gray-300 uppercase text-[10px] tracking-wide">
                                                    {item.nama_perusahaan ||
                                                        "PT. Vendor Tekstil Utama"}
                                                </td>

                                                {/* COL 4: VOLUMETRIK GUDANG */}
                                                <td className="py-4 font-black text-emerald-400 font-mono text-sm whitespace-nowrap">
                                                    {parseFloat(
                                                        item.stock || 0,
                                                    ).toLocaleString(
                                                        "id-ID",
                                                    )}{" "}
                                                    <span className="text-[10px] font-normal text-gray-400">
                                                        {item.unit}
                                                    </span>
                                                </td>

                                                {/* COL 5: KOORDINAT GEOGRAFIS */}
                                                <td className="py-4 font-medium uppercase text-[10px] tracking-wider text-gray-400 whitespace-nowrap">
                                                    <i className="fas fa-map-marker-alt text-rose-500/50 mr-1.5"></i>
                                                    {item.warehouse_location}
                                                </td>

                                                {/* COL 6: NOMINAL HARGA LAPAK */}
                                                <td className="py-4 font-bold font-mono text-white whitespace-nowrap">
                                                    {parseFloat(
                                                        item.price || 0,
                                                    ) > 0
                                                        ? `Rp ${parseFloat(item.price).toLocaleString("id-ID")}`
                                                        : isEn
                                                          ? "Serious Negotiation"
                                                          : "Nego Serius"}
                                                </td>
                                                <td className="py-4 text-right pr-4 whitespace-nowrap">
                                                    <div className="flex items-center justify-end gap-2">
                                                        {/* 1. SENSOR EDIT MULTI-VENDOR: Hanya mengembang untuk Admin atau Akun Pemilik Toko Terkait */}
                                                        {(isAdmin ||
                                                            (auth?.user
                                                                ?.company_id &&
                                                                Number(
                                                                    item.company_id,
                                                                ) ===
                                                                    Number(
                                                                        auth
                                                                            ?.user
                                                                            ?.company_id,
                                                                    ))) && (
                                                            <a
                                                                href={`/admin/inventory/${item.id}/edit`}
                                                                className="bg-blue-600/20 border border-blue-500/40 text-blue-400 hover:bg-blue-600 hover:text-white px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-wider transition-all duration-300 cursor-pointer"
                                                            >
                                                                <i className="fas fa-edit mr-1"></i>
                                                                {isEn
                                                                    ? "Edit"
                                                                    : "Ubah"}
                                                            </a>
                                                        )}

                                                        {/* 2. TOMBOL UNDUH E-BROCHURE SPESIFIKASI TEKNIS KAIN */}
                                                        {item.brochure && (
                                                            <a
                                                                href={`/storage/${item.brochure}`}
                                                                target="_blank"
                                                                rel="noopener noreferrer"
                                                                className="bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 hover:bg-cyan-500 hover:text-[#0a192f] px-3 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all inline-flex items-center gap-1.5"
                                                            >
                                                                <i className="fas fa-file-pdf"></i>{" "}
                                                                {isEn
                                                                    ? "Brochure"
                                                                    : "Brosur"}
                                                            </a>
                                                        )}

                                                        {/* 3. TOMBOL JALUR KOMUNIKASI WHATSAPP BUSINESS VENDOR */}
                                                        <button
                                                            onClick={() =>
                                                                handleContactOwner(
                                                                    item.whatsapp_contact,
                                                                    isEn
                                                                        ? item.name_en ||
                                                                              item.name
                                                                        : item.name,
                                                                    isEn
                                                                        ? "Product Storefront"
                                                                        : "Lapak",
                                                                )
                                                            }
                                                            className="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 hover:bg-emerald-500 hover:text-white px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all cursor-pointer"
                                                        >
                                                            <i className="fab fa-whatsapp mr-1.5 text-xs"></i>
                                                            {isEn
                                                                ? "Chat Seller"
                                                                : "Hubungi"}
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        ))
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            )}

            {/* ====================================================================== */}
            {/* 🛡️ MODAL POPUP 2: PUSAT DATA & REGULASI KEMENTERIAN */}
            {/* ====================================================================== */}
            {isRegulasiOpen && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-md">
                    <div className="bg-gradient-to-b from-[#0B192C] to-[#1E3E62] border border-amber-500/20 w-full max-w-4xl rounded-[40px] shadow-2xl overflow-hidden flex flex-col max-h-[85vh]">
                        <div className="p-6 border-b border-white/5 flex justify-between items-center bg-[#001F3F]/60">
                            <div className="border-l-4 border-amber-500 pl-4">
                                <h3 className="text-white font-black uppercase text-sm tracking-tight italic">
                                    Registri Dokumen & Pengetahuan Resmi
                                </h3>
                            </div>
                            <button
                                onClick={() => setIsRegulasiOpen(false)}
                                className="text-gray-400 hover:text-amber-500 text-sm font-black p-2 cursor-pointer focus:outline-none"
                            >
                                <i className="fas fa-times"></i>
                            </button>
                        </div>
                        <div className="p-6 bg-[#001F3F]/30 border-b border-white/5 flex flex-col sm:flex-row gap-4 justify-between items-center">
                            <input
                                type="text"
                                placeholder="Saring judul dokumen..."
                                value={regSearch}
                                onChange={(e) => setRegSearch(e.target.value)}
                                className="w-full sm:w-72 bg-[#0A192F] border border-white/10 px-5 py-2.5 rounded-full text-[11px] text-white focus:outline-none"
                            />
                            <div className="flex gap-1.5 overflow-x-auto max-w-full scrollbar-hide">
                                {regulasiCategories.map((cat) => (
                                    <button
                                        key={cat.id}
                                        onClick={() => setRegCategory(cat.id)}
                                        className={`px-4 py-2 rounded-xl text-[8px] font-black uppercase transition-all ${regCategory === cat.id ? "bg-yellow-500 text-[#0a192f]" : "bg-white/5 text-gray-400"}`}
                                    >
                                        {cat.label}
                                    </button>
                                ))}
                            </div>
                        </div>
                        <div className="overflow-y-auto p-6 flex-1 bg-black/10">
                            <table className="w-full text-left border-collapse">
                                <thead className="bg-[#0a192f] text-gray-400 uppercase text-[9px] tracking-widest border-b border-white/5">
                                    <tr>
                                        <th className="p-4 pl-4">
                                            Nama Dokumen
                                        </th>
                                        <th className="p-4">Sumber</th>
                                        <th className="p-4">Akses</th>
                                        <th className="p-4 text-right pr-4">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {filteredRegulations.map((item) => (
                                        <tr
                                            key={item.id}
                                            className="border-b border-white/5 hover:bg-white/5 transition duration-300"
                                        >
                                            <td className="p-4 pl-4 font-bold text-white max-w-xs">
                                                <div className="flex flex-col">
                                                    <span className="line-clamp-2">
                                                        {item.title}
                                                    </span>
                                                    <span className="text-[8px] text-gray-500 uppercase font-mono">
                                                        {item.category}
                                                    </span>
                                                </div>
                                            </td>
                                            <td className="p-4 text-gray-400 font-medium">
                                                {item.speaker}
                                            </td>
                                            <td className="p-4">
                                                <span
                                                    className={`text-[8px] font-black uppercase tracking-wider px-2 py-0.5 rounded ${item.access_tier === "Premium" ? "bg-amber-500 text-[#0a192f]" : "bg-white/10 text-white"}`}
                                                >
                                                    {item.access_tier}
                                                </span>
                                            </td>
                                            <td className="p-4 text-right pr-4 whitespace-nowrap">
                                                <div className="flex items-center justify-end gap-2">
                                                    {/* PERBAIKAN: Rute edit singular '/admin/regulation/' agar sinkron tanpa memicu 404 */}
                                                    {isAdmin && (
                                                        <a
                                                            href={`/admin/regulation/${item.id}/edit`}
                                                            className="bg-blue-600/20 border border-blue-500/40 text-blue-400 hover:bg-blue-600 hover:text-white px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-wider transition-all duration-300"
                                                        >
                                                            <i className="fas fa-edit mr-1"></i>
                                                            {isEn
                                                                ? "Edit"
                                                                : "Ubah"}
                                                        </a>
                                                    )}
                                                    <button
                                                        onClick={() =>
                                                            handleDownloadReg(
                                                                item.file_path,
                                                                item.access_tier,
                                                                item.title,
                                                            )
                                                        }
                                                        className="bg-white/5 border border-white/10 text-white hover:bg-yellow-500 hover:text-[#0a192f] px-4 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all cursor-pointer"
                                                    >
                                                        {isEn
                                                            ? "Download"
                                                            : "Unduh"}
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            )}

            {/* ====================================================================== */}
            {/* 🛡️ JENDELA MODAL POPUP 3: MATCHMAKING KEMITRAAN B2B MULTI-SEKTOR (FINAL) */}
            {/* ====================================================================== */}
            {isMatchOpen && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-md animate-fade-in">
                    <div className="bg-gradient-to-b from-[#0B192C] to-[#1E3E62] border border-amber-500/20 w-full max-w-6xl rounded-[40px] shadow-2xl overflow-hidden flex flex-col max-h-[85vh]">
                        {/* PERBAIKAN 1: Header Modal Diletakkan di Luar Kontainer Area Gulir (Sticky Posisi Atas) */}
                        <div className="p-6 border-b border-white/5 flex flex-col sm:flex-row justify-between items-start sm:items-center bg-[#001F3F]/60 gap-4">
                            <div className="border-l-4 border-amber-500 pl-4">
                                <h3 className="text-white font-black uppercase text-sm tracking-tight italic">
                                    {isEn
                                        ? "Advanced B2B Partnership Console"
                                        : "Konsol Perjodohan Kemitraan B2B Multi-Sektor"}
                                </h3>
                            </div>

                            <div className="flex items-center gap-3 ml-auto">
                                {/* SENSOR UTAMA: Tombol Tambah Lini Mesin Hanya Terlihat oleh Admin ATAU Perusahaan Terdaftar */}
                                {(isAdmin || auth?.user?.company_id) && (
                                    <Link
                                        href={route("matchmaking.create")}
                                        className="bg-gradient-to-r from-amber-500 to-yellow-500 text-[#0a192f] px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all shadow-md hover:scale-105 duration-300"
                                    >
                                        <i className="fas fa-plus-circle mr-1.5"></i>
                                        {isEn
                                            ? "Add My Machine Capacity"
                                            : "Daftarkan Lini Mesin Pabrik"}
                                    </Link>
                                )}

                                {/* Tombol Tautan Pintas Menuju Makro Dashboard PLM */}
                                <Link
                                    href={route("matchmaking.index")}
                                    className="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all shadow-md"
                                >
                                    <i className="fas fa-robot mr-1.5"></i>{" "}
                                    {isEn
                                        ? "Go to AI Software"
                                        : "Buka AI Software"}
                                </Link>

                                <button
                                    onClick={() => setIsMatchOpen(false)}
                                    className="text-gray-400 hover:text-amber-500 text-sm font-black p-2 cursor-pointer focus:outline-none"
                                >
                                    <i className="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        {/* Search & Categories Row */}
                        <div className="p-6 bg-[#001F3F]/30 border-b border-white/5 flex flex-col sm:flex-row gap-4 justify-between items-center">
                            <input
                                type="text"
                                placeholder={
                                    isEn
                                        ? "Search partner name or tagline..."
                                        : "Cari nama mitra atau deskripsi solusi..."
                                }
                                value={matchSearch}
                                onChange={(e) => setMatchSearch(e.target.value)}
                                className="w-full sm:w-72 bg-[#0A192F] border border-white/10 px-5 py-2.5 rounded-full text-[11px] text-white focus:outline-none focus:border-amber-500 shadow-inner"
                            />
                            <div className="flex gap-1.5 overflow-x-auto max-w-full scrollbar-hide">
                                {matchCategories.map((cat) => (
                                    <button
                                        key={cat.id}
                                        onClick={() => setMatchCategory(cat.id)}
                                        className={`px-4 py-2 rounded-xl text-[8px] font-black uppercase transition-all whitespace-nowrap ${matchCategory.toLowerCase() === cat.id.toLowerCase() ? "bg-amber-500 text-[#0a192f] shadow-md" : "bg-white/5 text-gray-400 hover:text-white"}`}
                                    >
                                        {cat.label}
                                    </button>
                                ))}
                            </div>
                        </div>

                        {/* BODY UTAMA AREA GULIR TABEL (HANYA AREA INI YANG BERGULIR KE BAWAH) */}
                        <div className="overflow-y-auto p-6 flex-1 bg-black/10">
                            <table className="w-full text-left border-collapse">
                                <thead className="bg-[#0a192f] text-gray-400 uppercase text-[9px] tracking-widest border-b border-white/5">
                                    <tr>
                                        <th className="pb-4 pt-2 pl-4 w-20">
                                            {isEn ? "Photo" : "Foto"}
                                        </th>
                                        <th className="pb-4 pt-2 pl-4">
                                            {isEn
                                                ? "Partner & Solution"
                                                : "Nama Vendor / Solusi Bisnis"}
                                        </th>
                                        <th className="pb-4 pt-2">
                                            {isEn ? "Sector" : "Sektor"}
                                        </th>
                                        <th className="pb-4 pt-2">
                                            {isEn ? "Region" : "Wilayah"}
                                        </th>
                                        <th className="pb-4 pt-2">
                                            {isEn ? "AI Match" : "Kecocokan"}
                                        </th>
                                        <th className="pb-4 pt-2 text-right pr-4">
                                            {isEn ? "Action" : "Sinergi B2B"}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody className="text-xs text-gray-300">
                                    {filteredPartnerships.length === 0 ? (
                                        <tr>
                                            <td
                                                colSpan="6"
                                                className="text-center py-10 text-gray-500 uppercase font-black tracking-wider text-[10px]"
                                            >
                                                {isEn
                                                    ? "No partnerships listed matching criteria"
                                                    : "Belum ada mitra bisnis terdaftar."}
                                            </td>
                                        </tr>
                                    ) : (
                                        filteredPartnerships.map((item) => (
                                            <tr
                                                key={item.id}
                                                className="border-b border-white/5 hover:bg-white/5 transition duration-300"
                                            >
                                                {/* COL 1: FOTO MINI SUPPLIER / MESIN */}
                                                <td className="py-4 pl-4 w-20">
                                                    <div className="w-12 h-12 rounded-xl overflow-hidden bg-slate-800 border border-white/10 shadow-md relative group/img bg-white flex items-center justify-center p-1">
                                                        {item.image_path ? (
                                                            <img
                                                                src={`/storage/${item.image_path}`}
                                                                className="w-full h-full object-contain group-hover/img:scale-110 transition-transform duration-300"
                                                                alt={item.name}
                                                            />
                                                        ) : (
                                                            <div className="w-full h-full flex flex-col items-center justify-center text-gray-600 bg-slate-800">
                                                                <i className="fas fa-industry text-xs"></i>
                                                                <span className="text-[6px] font-black uppercase text-center text-gray-500 leading-none mt-1">
                                                                    No Unit
                                                                </span>
                                                            </div>
                                                        )}
                                                    </div>
                                                </td>

                                                {/* COL 2: SPESIFIKASI DAN KETERANGAN DATA (TERMASUK DETAIL BARU) */}
                                                <td className="py-4 pl-4 font-bold text-white max-w-xs">
                                                    <div className="flex flex-col gap-0.5">
                                                        <span className="text-amber-400 text-sm">
                                                            {item.name}
                                                        </span>
                                                        <span className="text-[10px] text-yellow-500/80 font-bold uppercase mt-0.5">
                                                            {item.tagline}
                                                        </span>
                                                        <p className="text-[10px] text-gray-400 font-normal normal-case italic mt-1 font-sans">
                                                            "{item.description}"
                                                        </p>

                                                        {/* Kotak Mini Detail MOQ & SLA */}
                                                        <div className="text-[8px] text-gray-500 font-mono uppercase mt-3 flex flex-wrap gap-x-4 gap-y-1 bg-black/20 p-2 rounded-xl border border-white/5 w-fit">
                                                            <span className="whitespace-nowrap">
                                                                <i className="fas fa-shopping-bag text-yellow-500/40 mr-1"></i>
                                                                MOQ:{" "}
                                                                {item.moq_info ||
                                                                    "Nego"}
                                                            </span>
                                                            <span className="whitespace-nowrap">
                                                                <i className="fas fa-shield-alt text-emerald-500/40 mr-1"></i>
                                                                SLA:{" "}
                                                                {item.after_sales_sla ||
                                                                    "Terjamin"}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </td>

                                                {/* COL 3: KATEGORI */}
                                                <td className="py-4 uppercase font-black text-[9px] text-gray-400 font-mono whitespace-nowrap">
                                                    {item.category}
                                                </td>

                                                {/* COL 4: WILAYAH */}
                                                <td className="py-4 uppercase font-bold text-[10px] text-gray-400 whitespace-nowrap">
                                                    <i className="fas fa-globe text-blue-500/30 mr-1.5"></i>
                                                    {item.region}
                                                </td>

                                                {/* COL 5: PERSENTASE KECOCOKAN */}
                                                <td className="py-4 font-mono font-black text-sm text-emerald-400 whitespace-nowrap">
                                                    {item.match_percentage}%
                                                    Match
                                                </td>

                                                {/* COL 6: KONEKSI WHATSAPP + SENSOR EDIT KHUSUS ADMIN & SUPPLIER RESMI */}
                                                <td className="py-4 text-right pr-4 whitespace-nowrap">
                                                    <div className="flex items-center justify-end gap-2">
                                                        {(isAdmin ||
                                                            (auth?.user
                                                                ?.company_id &&
                                                                Number(
                                                                    item.company_id,
                                                                ) ===
                                                                    Number(
                                                                        auth
                                                                            ?.user
                                                                            ?.company_id,
                                                                    ))) && (
                                                            <a
                                                                href={`/admin/partnerships/${item.id}/edit`}
                                                                className="bg-blue-600/20 border border-blue-500/40 text-blue-400 hover:bg-blue-600 hover:text-white px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-wider transition-all duration-300 cursor-pointer"
                                                            >
                                                                <i className="fas fa-edit mr-1"></i>
                                                                {isEn
                                                                    ? "Edit"
                                                                    : "Ubah"}
                                                            </a>
                                                        )}
                                                        <button
                                                            onClick={() =>
                                                                handleContactOwner(
                                                                    item.whatsapp_contact,
                                                                    item.name,
                                                                    "Kemitraan Vendor",
                                                                )
                                                            }
                                                            className="bg-amber-500/10 border border-amber-500/30 text-amber-400 hover:bg-amber-500 hover:text-[#0a192f] px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all cursor-pointer"
                                                        >
                                                            <i className="fas fa-handshake mr-1.5"></i>
                                                            {isEn
                                                                ? "Connect"
                                                                : "Ajukan Sinergi"}
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        ))
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            )}
        </section>
    );
}
