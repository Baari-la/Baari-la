// resources/js/Pages/About/Index.jsx
import { useState } from "react"; // Tambahkan di atas
import { motion, AnimatePresence } from "framer-motion"; // Tambahkan di atas
import { Head, usePage } from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";

export default function Index() {
    const { locale, auth, galleries = [] } = usePage().props;
    const [selectedImg, setSelectedImg] = useState(null); // State untuk Lightbox
    const isEn = locale === "en";
    const t = (key) => usePage().props.translations[key] || key;

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title={t("About_Title")} />

            <div className="bg-[#0a192f] min-h-screen py-24 text-white font-sans">
                <div className="max-w-6xl mx-auto px-6">
                    {/* SECTION 1: HEADER & HISTORY */}
                    <div className="max-w-4xl">
                        <h4 className="text-yellow-500 text-[11px] font-black uppercase tracking-[0.5em] mb-4">
                            {t("About_Title")}
                        </h4>
                        <h1 className="text-5xl md:text-7xl font-black italic tracking-tighter mb-12 leading-none uppercase">
                            {t("About_History_Title")}
                        </h1>
                        <p className="text-gray-400 text-lg leading-relaxed mb-20 font-medium">
                            {t("About_History_Body")}
                        </p>
                    </div>

                    {/* SECTION 2: GLOBAL CONNECTIVITY MAP (Visual Utama) */}
                    <section className="mb-32 relative overflow-hidden">
                        <div className="mb-12">
                            <h3 className="text-white text-3xl font-black italic uppercase tracking-tighter mb-4">
                                {t("Map_Title")}
                            </h3>
                            <p className="text-gray-500 text-[10px] font-bold uppercase tracking-[0.3em] max-w-2xl leading-relaxed">
                                {t("Map_Subtitle")}
                            </p>
                        </div>

                        <div className="relative aspect-video bg-[#050c1b] border border-white/5 rounded-[60px] overflow-hidden group shadow-2xl">
                            <img
                                src="/images/global-connectivity-map.jpg"
                                className="w-full h-full object-cover opacity-40 grayscale group-hover:grayscale-0 transition-all duration-1000"
                                alt="Global Connectivity"
                            />

                            {/* Pulse Point Jakarta */}
                            <div className="absolute top-[68%] left-[78%]">
                                <span className="relative flex h-6 w-6">
                                    <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                                    <span className="relative inline-flex rounded-full h-6 w-6 bg-yellow-500 shadow-[0_0_20px_rgba(234,179,8,0.6)]"></span>
                                </span>
                            </div>

                            {/* Map Floating Stats */}
                            <div className="absolute bottom-10 left-10 right-10 grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div className="bg-[#050c1b]/80 backdrop-blur-md p-6 rounded-3xl border border-white/10">
                                    <p className="text-yellow-500 text-[8px] font-black uppercase tracking-widest mb-1">
                                        {t("Map_Stats_1")}
                                    </p>
                                    <h5 className="text-white text-lg font-black uppercase tracking-tighter">
                                        USA & EU FOCUS
                                    </h5>
                                </div>
                                <div className="bg-[#050c1b]/80 backdrop-blur-md p-6 rounded-3xl border border-white/10">
                                    <p className="text-yellow-500 text-[8px] font-black uppercase tracking-widest mb-1">
                                        {t("Map_Stats_2")}
                                    </p>
                                    <h5 className="text-white text-lg font-black uppercase tracking-tighter">
                                        124+ DESTINATIONS
                                    </h5>
                                </div>
                                <div className="bg-[#050c1b]/80 backdrop-blur-md p-6 rounded-3xl border border-white/10">
                                    <p className="text-yellow-500 text-[8px] font-black uppercase tracking-widest mb-1">
                                        {t("Map_Stats_3")}
                                    </p>
                                    <h5 className="text-white text-lg font-black uppercase tracking-tighter">
                                        8-DIGIT PRECISION
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </section>

                    {/* SECTION 3: INDUSTRIAL ECOSYSTEM */}
                    <div className="mb-32">
                        <h3 className="text-white text-2xl font-black italic mb-10 uppercase tracking-tighter">
                            {t("Ecosystem_Title")}
                        </h3>
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div className="p-10 bg-white/5 border border-white/10 rounded-[45px] hover:border-yellow-500/30 transition-all">
                                <i className="fas fa-microchip text-yellow-500 text-2xl mb-6"></i>
                                <h4 className="text-white font-black mb-4 uppercase text-sm tracking-widest">
                                    {t("Ecosystem_Hulu_Title")}
                                </h4>
                                <p className="text-gray-500 text-[10px] leading-relaxed font-bold uppercase italic">
                                    {t("Ecosystem_Hulu_Desc")}
                                </p>
                            </div>
                            <div className="p-10 bg-white/5 border border-white/10 rounded-[45px] hover:border-yellow-500/30 transition-all">
                                <i className="fas fa-shipping-fast text-yellow-500 text-2xl mb-6"></i>
                                <h4 className="text-white font-black mb-4 uppercase text-sm tracking-widest">
                                    {t("Ecosystem_Hilir_Title")}
                                </h4>
                                <p className="text-gray-500 text-[10px] leading-relaxed font-bold uppercase italic">
                                    {t("Ecosystem_Hilir_Desc")}
                                </p>
                            </div>
                            <div className="p-10 bg-white/5 border border-white/10 rounded-[45px] hover:border-yellow-500/30 transition-all">
                                <i className="fas fa-globe text-yellow-500 text-2xl mb-6"></i>
                                <h4 className="text-white font-black mb-4 uppercase text-sm tracking-widest">
                                    {t("Ecosystem_Connect_Title")}
                                </h4>
                                <p className="text-gray-500 text-[10px] leading-relaxed font-bold uppercase italic">
                                    {t("Ecosystem_Connect_Desc")}
                                </p>
                            </div>
                        </div>
                    </div>

                    {/* SECTION 4: DIGITAL INTEGRITY CHARTER */}
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-12 pt-20 border-t border-white/10">
                        <div className="space-y-4">
                            <h4 className="text-white text-sm font-black uppercase italic underline decoration-yellow-500 decoration-2 underline-offset-8">
                                {t("Charter_Point_1_Title")}
                            </h4>
                            <p className="text-gray-500 text-[10px] leading-relaxed uppercase font-bold tracking-wider">
                                {t("Charter_Point_1_Body")}
                            </p>
                        </div>
                        <div className="space-y-4">
                            <h4 className="text-white text-sm font-black uppercase italic underline decoration-yellow-500 decoration-2 underline-offset-8">
                                {t("Charter_Point_2_Title")}
                            </h4>
                            <p className="text-gray-500 text-[10px] leading-relaxed uppercase font-bold tracking-wider">
                                {t("Charter_Point_2_Body")}
                            </p>
                        </div>
                        <div className="space-y-4">
                            <h4 className="text-white text-sm font-black uppercase italic underline decoration-yellow-500 decoration-2 underline-offset-8">
                                {t("Charter_Point_3_Title")}
                            </h4>
                            <p className="text-gray-500 text-[10px] leading-relaxed uppercase font-bold tracking-wider">
                                {t("Charter_Point_3_Body")}
                            </p>
                        </div>
                    </div>
                </div>
                {/* SECTION: INDUSTRIAL INSIGHT GALLERY */}
                <section className="mt-32">
                    <h3 className="text-white text-2xl font-black italic mb-10 uppercase tracking-tighter">
                        {t("Industrial Documentation")}
                    </h3>
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {galleries.map((item) => (
                            <div
                                key={item.id}
                                className="group relative overflow-hidden rounded-[40px] bg-white/5 border border-white/10 aspect-square"
                            >
                                <img
                                    src={`/storage/${item.image_path}`}
                                    className="w-full h-full object-cover opacity-60 group-hover:opacity-100 group-hover:scale-110 transition-all duration-700"
                                    alt={item.title_id}
                                />
                                <div className="absolute inset-0 bg-gradient-to-t from-[#0a192f] via-transparent to-transparent opacity-80"></div>
                                <div className="absolute bottom-8 left-8 right-8">
                                    <span className="text-[8px] font-black text-yellow-500 uppercase tracking-widest">
                                        {item.category}
                                    </span>
                                    <h4 className="text-white text-sm font-bold uppercase mt-1 leading-tight">
                                        {isEn
                                            ? item.title_en || item.title_id
                                            : item.title_id}
                                    </h4>
                                </div>
                            </div>
                        ))}
                    </div>
                </section>

                {/* SECTION 5: STRATEGIC PARTNERS GRID */}
                <section className="mt-32 pt-20 border-t border-white/10 text-center">
                    <div className="mb-16">
                        <h3 className="text-white text-2xl font-black italic uppercase tracking-tighter mb-4">
                            {t("Partners_Title")}
                        </h3>
                        <p className="text-gray-500 text-[10px] font-bold uppercase tracking-[0.3em] max-w-xl mx-auto leading-relaxed">
                            {t("Partners_Subtitle")}
                        </p>
                    </div>

                    {/* Logo Grid: Pastikan file logo ini ada di public/images/partners/ */}
                    <div className="grid grid-cols-2 md:grid-cols-4 gap-12 items-center opacity-40 grayscale hover:grayscale-0 transition-all duration-700">
                        <div className="flex justify-center">
                            <img
                                src="/images/partners/centric.png"
                                className="h-10 object-contain"
                                alt="Centric Software"
                            />
                        </div>
                        <div className="flex justify-center">
                            <img
                                src="/images/partners/coats.png"
                                className="h-12 object-contain"
                                alt="Coats"
                            />
                        </div>
                        <div className="flex justify-center">
                            <img
                                src="/images/partners/epson.png"
                                className="h-8 object-contain"
                                alt="Epson"
                            />
                        </div>
                        <div className="flex justify-center">
                            <img
                                src="/images/partners/testex.png"
                                className="h-10 object-contain"
                                alt="Testex"
                            />
                        </div>
                    </div>

                    {/* CTA: Pintu Terbuka untuk Mitra Ketiga */}
                    <div className="mt-20">
                        <a
                            href="mailto:support@digestexmedia.com"
                            className="inline-block bg-white/5 border border-white/10 text-white px-10 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-600 transition-all shadow-xl"
                        >
                            {isEn
                                ? "Inquire for Strategic Partnership"
                                : "Ajukan Kemitraan Strategis"}
                        </a>
                    </div>
                </section>
            </div>

            {/* MODAL LIGHTBOX */}
            <AnimatePresence>
                {selectedImg && (
                    <motion.div
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        exit={{ opacity: 0 }}
                        onClick={() => setSelectedImg(null)} // Klik luar untuk tutup
                        className="fixed inset-0 z-[100] flex items-center justify-center bg-[#0a192f]/95 backdrop-blur-xl p-4 md:p-10"
                    >
                        <motion.div
                            initial={{ scale: 0.8, y: 20 }}
                            animate={{ scale: 1, y: 0 }}
                            exit={{ scale: 0.8, y: 20 }}
                            className="relative max-w-5xl w-full"
                        >
                            <img
                                src={`/storage/${selectedImg.image_path}`}
                                className="w-full h-auto max-h-[85vh] object-contain rounded-3xl shadow-2xl border border-white/10"
                            />
                            <div className="mt-6 text-center">
                                <h4 className="text-white text-xl font-black uppercase italic italic">
                                    {isEn
                                        ? selectedImg.title_en ||
                                          selectedImg.title_id
                                        : selectedImg.title_id}
                                </h4>
                                <p className="text-yellow-500 text-[10px] font-black uppercase tracking-[0.3em] mt-2">
                                    {selectedImg.category}
                                </p>
                            </div>
                            <button className="absolute -top-12 right-0 text-white text-sm font-black uppercase tracking-widest hover:text-yellow-500">
                                Close [x]
                            </button>
                        </motion.div>
                    </motion.div>
                )}
            </AnimatePresence>
        </AuthenticatedLayout>
    );
}
