import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, useForm, usePage } from "@inertiajs/react";
import { useState, useEffect } from "react";
import { router } from "@inertiajs/react";
import { motion, AnimatePresence } from "framer-motion";

export default function EditProfile({ company }) {
    const [showPreview, setShowPreview] = useState(false); // State untuk modal
    const { auth } = usePage().props;
    const [showSuccessModal, setShowSuccessModal] = useState(false);
    const { flash } = usePage().props;
    const isEn = auth.locale === "en";

    // Mengambil data awal dari database agar perusahaan tinggal edit yang salah
    const { data, setData, post, processing, errors } = useForm({
        nama_perusahaan: company.nama_perusahaan || "",
        alamat_lengkap: company.alamat_lengkap || "",
        pimpinan: company.pimpinan || "",
        tenaga_kerja: company.tenaga_kerja || "",
        pasar_ekspor: company.pasar_ekspor || "",
        produk: company.produk || "",
        email_web: company.email_web || "",
        telepon: company.telepon || "",
    });

    const submit = (e) => {
        e.preventDefault();
        // Mengirim data ke antrean verifikasi Admin API Jakarta
        post(route("companies.update", company.id));
    };

    // Efek untuk memonitor flash message dari Controller
    useEffect(() => {
        if (flash.message) {
            setShowSuccessModal(true);

            // Timer 5 detik untuk Auto-Redirect
            const timer = setTimeout(() => {
                setShowSuccessModal(false);
                // Redirect ke halaman Deep Intelligence perusahaan tersebut
                router.get(route("companies.show", company.id));
            }, 5000);

            return () => clearTimeout(timer); // Bersihkan timer jika komponen di-unmount
        }
    }, [flash.message]);

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title={`Edit ${data.nama_perusahaan}`} />
            <div className="py-12 bg-[#0a192f] min-h-screen text-white">
                <div className="max-w-4xl mx-auto px-6">
                    {/* NOTIFIKASI STATUS (Jika ada) */}
                    {company.status_verifikasi === "rejected" && (
                        <div className="mb-10 p-6 bg-red-500/10 border border-red-500/30 rounded-3xl flex items-start gap-4 animate-pulse">
                            <i className="fas fa-exclamation-circle text-red-500 mt-1"></i>
                            <div>
                                <h4 className="text-red-500 font-black uppercase text-[10px] tracking-widest">
                                    Pembaruan Ditolak
                                </h4>
                                <p className="text-white/70 text-xs mt-1 font-medium italic">
                                    "Mohon perbaiki data alamat sesuai dengan
                                    domisili terbaru pabrik."
                                </p>
                            </div>
                        </div>
                    )}

                    <h1 className="text-3xl font-black uppercase italic mb-10 text-yellow-500">
                        {isEn
                            ? "Update Corporate Profile"
                            : "Mutakhirkan Profil Perusahaan"}
                    </h1>

                    <form
                        onSubmit={submit}
                        className="bg-white/5 p-10 rounded-[40px] border border-white/10 space-y-6"
                    >
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div className="space-y-1">
                                <label className="text-[10px] font-black uppercase tracking-widest text-gray-500">
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
                                    className="w-full bg-white/5 border-white/10 rounded-2xl p-4 text-sm font-bold"
                                />
                            </div>
                            <div className="space-y-1">
                                <label className="text-[10px] font-black uppercase tracking-widest text-gray-500">
                                    Pimpinan / Direktur
                                </label>
                                <input
                                    type="text"
                                    value={data.pimpinan}
                                    onChange={(e) =>
                                        setData("pimpinan", e.target.value)
                                    }
                                    className="w-full bg-white/5 border-white/10 rounded-2xl p-4 text-sm font-bold"
                                />
                            </div>
                        </div>

                        <div className="space-y-1">
                            <label className="text-[10px] font-black uppercase tracking-widest text-gray-500">
                                Alamat Lengkap
                            </label>
                            <textarea
                                value={data.alamat_lengkap}
                                onChange={(e) =>
                                    setData("alamat_lengkap", e.target.value)
                                }
                                className="w-full bg-white/5 border-white/10 rounded-2xl p-4 text-sm font-bold h-32"
                            ></textarea>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div className="space-y-1">
                                <label className="text-[10px] font-black uppercase tracking-widest text-gray-500">
                                    Tenaga Kerja
                                </label>
                                <input
                                    type="text"
                                    value={data.tenaga_kerja}
                                    onChange={(e) =>
                                        setData("tenaga_kerja", e.target.value)
                                    }
                                    className="w-full bg-white/5 border-white/10 rounded-2xl p-4 text-sm font-bold"
                                />
                            </div>
                            <div className="space-y-1">
                                <label className="text-[10px] font-black uppercase tracking-widest text-gray-500">
                                    Pasar Ekspor
                                </label>
                                <input
                                    type="text"
                                    value={data.pasar_ekspor}
                                    onChange={(e) =>
                                        setData("pasar_ekspor", e.target.value)
                                    }
                                    className="w-full bg-white/5 border-white/10 rounded-2xl p-4 text-sm font-bold"
                                />
                            </div>
                        </div>

                        <div className="space-y-1">
                            <label className="text-[10px] font-black uppercase tracking-widest text-gray-500">
                                Spesifikasi Produk
                            </label>
                            <textarea
                                value={data.produk}
                                onChange={(e) =>
                                    setData("produk", e.target.value)
                                }
                                className="w-full bg-white/5 border-white/10 rounded-2xl p-4 text-sm font-bold h-24"
                            ></textarea>
                        </div>

                        <div className="p-6 bg-yellow-500/10 border border-yellow-500/20 rounded-3xl flex items-center gap-4">
                            <i className="fas fa-shield-alt text-yellow-500 text-2xl"></i>
                            <p className="text-[10px] font-black uppercase tracking-wider text-yellow-500">
                                {isEn
                                    ? "Your changes will be verified by API Jakarta's administrator before going live."
                                    : "Perubahan Anda akan diverifikasi oleh administrator API Jakarta sebelum dipublikasikan."}
                            </p>
                        </div>

                        <div className="flex flex-col md:flex-row gap-4">
                            {/* TOMBOL PREVIEW */}
                            <button
                                type="button"
                                onClick={() => setShowPreview(true)}
                                className="flex-1 bg-white/5 border border-white/20 text-white font-black py-5 rounded-2xl uppercase tracking-widest text-xs hover:bg-white/10 transition-all"
                            >
                                <i className="fas fa-eye mr-2"></i>
                                {isEn ? "Preview Profile" : "Pratinjau Profil"}
                            </button>

                            {/* TOMBOL SIMPAN (LAMA) */}
                            <button
                                disabled={processing}
                                className="flex-1 bg-yellow-500 text-[#0a192f] font-black py-5 rounded-2xl uppercase tracking-widest text-xs hover:scale-[1.02] transition-all shadow-xl shadow-yellow-500/10"
                            >
                                {isEn ? "Submit Update" : "Kirim Pemutakhiran"}
                            </button>
                        </div>
                    </form>
                </div>
                {/* Preview */}
                {showPreview && (
                    <div className="fixed inset-0 z-50 flex items-center justify-center bg-[#0a192f]/95 backdrop-blur-xl p-6">
                        <div className="max-w-3xl w-full bg-[#0d1d36] border border-white/10 rounded-[50px] overflow-hidden shadow-2xl relative">
                            {/* WATERMARK DIAGONAL */}
                            <div className="absolute inset-0 flex items-center justify-center pointer-events-none overflow-hidden opacity-[0.03]">
                                <h1 className="text-[150px] font-black uppercase -rotate-45 text-white whitespace-nowrap">
                                    UNVERIFIED
                                </h1>
                            </div>

                            <div className="p-10 relative z-10">
                                <div className="flex justify-between items-start mb-10">
                                    <div>
                                        <span className="text-yellow-500 text-[8px] font-black uppercase tracking-[0.4em] mb-2 block">
                                            Intelligence Draft Preview
                                        </span>
                                        <h2 className="text-3xl font-black italic uppercase text-white">
                                            {data.nama_perusahaan ||
                                                "Company Name"}
                                        </h2>
                                    </div>
                                    {/* Badge Status di Pojok */}
                                    <div className="bg-yellow-500/10 border border-yellow-500/30 px-4 py-2 rounded-full">
                                        <span className="text-yellow-500 text-[8px] font-black uppercase tracking-widest">
                                            <i className="fas fa-clock mr-2"></i>
                                            Awaiting Audit from API Jakarta
                                        </span>
                                    </div>

                                    <button
                                        onClick={() => setShowPreview(false)}
                                        className="text-gray-500 hover:text-white"
                                    >
                                        <i className="fas fa-times text-xl"></i>
                                    </button>
                                </div>

                                <div className="grid grid-cols-2 gap-8 mb-10">
                                    <div className="space-y-4">
                                        <div>
                                            <label className="text-[8px] text-gray-500 uppercase font-black tracking-widest">
                                                Executive Director
                                            </label>
                                            <p className="text-sm font-bold text-white">
                                                {data.pimpinan || "-"}
                                            </p>
                                        </div>
                                        <div>
                                            <label className="text-[8px] text-gray-500 uppercase font-black tracking-widest">
                                                Production Focus
                                            </label>
                                            <p className="text-sm font-bold text-white">
                                                {data.produk || "-"}
                                            </p>
                                        </div>
                                    </div>
                                    <div className="space-y-4">
                                        <div>
                                            <label className="text-[8px] text-gray-500 uppercase font-black tracking-widest">
                                                Global Export Market
                                            </label>
                                            <p className="text-sm font-bold text-white">
                                                {data.pasar_ekspor || "-"}
                                            </p>
                                        </div>
                                        <div>
                                            <label className="text-[8px] text-gray-500 uppercase font-black tracking-widest">
                                                Workforce
                                            </label>
                                            <p className="text-sm font-bold text-white">
                                                {data.tenaga_kerja || "-"}{" "}
                                                Employees
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <button
                                    onClick={() => setShowPreview(false)}
                                    className="w-full bg-white/5 border border-white/10 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-white transition-all"
                                >
                                    Back to Editing
                                </button>
                            </div>
                        </div>
                    </div>
                )}
            </div>

            <AnimatePresence>
                {showSuccessModal && (
                    <div className="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-[#0a192f]/90 backdrop-blur-2xl">
                        <motion.div
                            initial={{ opacity: 0, scale: 0.9, y: 20 }}
                            animate={{ opacity: 1, scale: 1, y: 0 }}
                            exit={{ opacity: 0, scale: 0.9, y: 20 }}
                            className="max-w-md w-full bg-[#0d1d36] border border-yellow-500/20 rounded-[50px] p-12 text-center shadow-[0_0_50px_rgba(234,179,8,0.1)] relative overflow-hidden"
                        >
                            {/* EFEK CAHAYA DI BELAKANG */}
                            <div className="absolute -top-24 -left-24 w-48 h-48 bg-yellow-500/10 rounded-full blur-[80px]"></div>

                            {/* IKON ANIMASI */}
                            <div className="relative mb-8 flex justify-center">
                                <div className="h-24 w-24 bg-yellow-500 rounded-full flex items-center justify-center shadow-[0_0_30px_rgba(234,179,8,0.4)]">
                                    <i className="fas fa-check text-4xl text-[#0a192f]"></i>
                                </div>
                                <div className="absolute inset-0 h-24 w-24 border-4 border-yellow-500/30 rounded-full animate-ping mx-auto"></div>
                            </div>

                            <h3 className="text-2xl font-black italic uppercase text-white mb-4 tracking-tighter">
                                {isEn
                                    ? "Transmission Successful"
                                    : "Transmisi Berhasil"}
                            </h3>

                            <p className="text-gray-400 text-xs leading-relaxed mb-10 font-medium italic">
                                {isEn
                                    ? "Your intelligence data has been encrypted and moved to the audit queue. Our team will verify the integrity of the information shortly."
                                    : "Data intelijen Anda telah dienkripsi dan masuk ke antrean audit. Tim kami akan segera memverifikasi integritas informasi tersebut."}
                            </p>

                            <button
                                onClick={() => setShowSuccessModal(false)}
                                className="w-full bg-white text-black py-4 rounded-2xl font-black uppercase text-[10px] tracking-[0.3em] hover:bg-yellow-500 transition-all shadow-xl"
                            >
                                {isEn
                                    ? "Return to Dashboard"
                                    : "Kembali ke Dashboard"}
                            </button>
                        </motion.div>
                    </div>
                )}
            </AnimatePresence>
        </AuthenticatedLayout>
    );
}
