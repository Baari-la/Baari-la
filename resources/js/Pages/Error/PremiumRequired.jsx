import { Link, Head } from "@inertiajs/react";

export default function PremiumRequired() {
    // Pengaturan WhatsApp (Bisa Bapak ganti nomornya di sini)
    const whatsappNumber = "628123456789";
    const waMessage = encodeURIComponent(
        "Halo Admin Digestex, saya tertarik untuk upgrade ke akses PREMIUM.",
    );

    return (
        <div className="bg-[#0a192f] min-h-screen flex items-center justify-center text-white p-6 relative">
            <Head title="Premium Access Required" />

            {/* BENDERA TOGGLE (Simple View di Pojok Kanan Atas) */}
            <div className="absolute top-8 right-8 flex gap-4 opacity-50">
                <img
                    src="https://flagcdn.com"
                    className="w-5 h-auto"
                    alt="ID"
                />
                <img
                    src="https://flagcdn.com"
                    className="w-5 h-auto"
                    alt="EN"
                />
            </div>

            <div className="max-w-xl w-full text-center">
                {/* ICON LOCK MEWAH */}
                <div className="mb-8 flex justify-center">
                    <div className="p-6 bg-yellow-500/10 rounded-full border border-yellow-500/20">
                        <svg
                            className="w-16 h-16 text-yellow-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                strokeWidth="1.5"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                            />
                        </svg>
                    </div>
                </div>

                {/* VERSI INDONESIA */}
                <div className="mb-8">
                    <h1 className="text-2xl font-black mb-2 uppercase tracking-tighter text-yellow-500">
                        Akses Premium Diperlukan
                    </h1>
                    <p className="text-gray-400 text-sm leading-relaxed">
                        Laporan intelijen mendalam ini khusus untuk{" "}
                        <span className="font-bold">Anggota Premium</span>{" "}
                        Asosiasi Pertekstilan Indonesia.
                    </p>
                </div>

                {/* DIVIDER HALUS */}
                <div className="w-12 h-[1px] bg-white/20 mx-auto mb-8"></div>

                {/* ENGLISH VERSION (For Centric) */}
                <div className="mb-12">
                    <h1 className="text-2xl font-black mb-2 uppercase tracking-tighter text-yellow-500">
                        Premium Access Required
                    </h1>
                    <p className="text-gray-400 text-sm leading-relaxed font-light">
                        This deep-dive intelligence report is reserved
                        exclusively for{" "}
                        <span className="font-bold">Premium Members</span> of
                        the Indonesian Textile Association.
                    </p>
                </div>

                {/* TOMBOL AKSI */}
                <div className="space-y-4">
                    {/* TOMBOL WHATSAPP (Aksi Utama) */}
                    <a
                        href={`https://wa.me{whatsappNumber}?text=${waMessage}`}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="flex items-center justify-center gap-3 w-full bg-green-600 text-white py-4 rounded-xl font-black text-xs uppercase hover:bg-green-500 transition shadow-2xl"
                    >
                        {/* Jika FontAwesome belum ada, bisa pakai teks saja atau SVG logo WA */}
                        CHAT VIA WHATSAPP / HUBUNGI ADMIN
                    </a>

                    {/* TOMBOL UPGRADE (Opsional - Bisa ke halaman payment jika ada) */}
                    <button className="w-full border border-yellow-500/50 text-yellow-500 py-4 rounded-xl font-black text-xs uppercase hover:bg-yellow-500 hover:text-[#0a192f] transition">
                        Upgrade to Premium / Tingkatkan ke Premium
                    </button>

                    <Link
                        href="/dashboard"
                        className="block w-full py-4 text-gray-500 text-[10px] font-black uppercase hover:text-white transition tracking-widest"
                    >
                        Back to Dashboard / Kembali ke Dashboard
                    </Link>
                </div>
            </div>
        </div>
    );
}
