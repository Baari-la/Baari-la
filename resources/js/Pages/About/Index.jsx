import { useEffect, useState } from "react";
import { motion, AnimatePresence } from "framer-motion";
import { Head, usePage } from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";

export default function Index() {
    const { locale, auth, galleries = [], translations = {} } = usePage().props;

    const [selectedImg, setSelectedImg] = useState(null);

    const isEn = locale === "en";
    const t = (key) => translations[key] || key;

    useEffect(() => {
        if (!selectedImg) {
            document.body.style.overflow = "";
            return;
        }

        document.body.style.overflow = "hidden";

        const handleEsc = (e) => {
            if (e.key === "Escape") setSelectedImg(null);
        };

        window.addEventListener("keydown", handleEsc);

        return () => {
            document.body.style.overflow = "";
            window.removeEventListener("keydown", handleEsc);
        };
    }, [selectedImg]);

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title={t("About_Title")} />

            <div className="bg-[#0a192f] min-h-screen py-24 text-white font-sans">
                <div className="max-w-6xl mx-auto px-6">
                    {/* HERO / COMPANY STORY */}
                    <section className="max-w-4xl mb-24">
                        <h4 className="text-yellow-500 text-[11px] font-black uppercase tracking-[0.5em] mb-4">
                            {t("About_Title")}
                        </h4>
                        <h1 className="text-5xl md:text-7xl font-black italic tracking-tighter leading-none uppercase mb-10">
                            {isEn
                                ? "The Legacy and the Future"
                                : "Warisan dan Masa Depan"}
                        </h1>
                        <p className="text-gray-400 text-lg leading-relaxed font-medium">
                            {isEn
                                ? "DigestexGlobal is an independent industrial intelligence platform focused on accelerating digital transformation across the textile sector. We support the full industrial value chain—from fiber development to finished garment production—through data-driven insights, trade visibility, and seamless manufacturing connectivity. Built with a strong foundation in Indonesia’s textile ecosystem, DigestexGlobal serves as a global gateway for 8-digit trade intelligence, cross-border supply chain visibility, and industrial collaboration."
                                : "DigestexGlobal adalah platform intelijen industri independen yang berfokus pada percepatan transformasi digital di sektor tekstil. Kami mendukung seluruh rantai nilai industri—mulai dari pengembangan serat hingga produksi garmen jadi—melalui insight berbasis data, visibilitas perdagangan, dan konektivitas manufaktur yang terintegrasi. Dibangun dari fondasi kuat dalam ekosistem tekstil Indonesia, DigestexGlobal berperan sebagai gerbang global untuk intelijen perdagangan 8-digit, visibilitas rantai pasok lintas batas, dan kolaborasi industri."}
                        </p>
                    </section>

                    {/* GLOBAL NETWORK */}
                    <section className="mb-32 relative overflow-hidden">
                        <div className="mb-12">
                            <h3 className="text-white text-3xl font-black italic uppercase tracking-tighter mb-4">
                                {t("Map_Title")}
                            </h3>
                            <p className="text-gray-500 text-[10px] font-bold uppercase tracking-[0.3em] max-w-2xl leading-relaxed">
                                {isEn
                                    ? "Visualizing the movement of Indonesia’s textile intelligence across key manufacturing, sourcing, and trade corridors worldwide."
                                    : "Memvisualisasikan pergerakan intelijen tekstil Indonesia melalui koridor manufaktur, sourcing, dan perdagangan utama di berbagai wilayah dunia."}
                            </p>
                        </div>

                        <div className="relative aspect-video bg-[#050c1b] border border-white/5 rounded-[60px] overflow-hidden group shadow-2xl">
                            <img
                                src="/images/global-connectivity-map.jpg"
                                alt="Global Connectivity"
                                className="w-full h-full object-cover opacity-40 grayscale group-hover:grayscale-0 transition-all duration-1000"
                            />

                            <div className="absolute top-[68%] left-[78%]">
                                <span className="relative flex h-6 w-6">
                                    <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75" />
                                    <span className="relative inline-flex rounded-full h-6 w-6 bg-yellow-500 shadow-[0_0_20px_rgba(234,179,8,0.6)]" />
                                </span>
                            </div>

                            <div className="absolute bottom-10 left-10 right-10 grid grid-cols-1 md:grid-cols-3 gap-6">
                                {[
                                    ["Map_Stats_1", "USA & EU FOCUS"],
                                    ["Map_Stats_2", "124+ DESTINATIONS"],
                                    ["Map_Stats_3", "8-DIGIT PRECISION"],
                                ].map(([label, value]) => (
                                    <div
                                        key={label}
                                        className="bg-[#050c1b]/80 backdrop-blur-md p-6 rounded-3xl border border-white/10"
                                    >
                                        <p className="text-yellow-500 text-[8px] font-black uppercase tracking-widest mb-1">
                                            {t(label)}
                                        </p>
                                        <h5 className="text-white text-lg font-black uppercase tracking-tighter">
                                            {value}
                                        </h5>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </section>

                    {/* INDUSTRIAL VALUE CHAIN */}
                    <section className="mb-32">
                        <h3 className="text-white text-2xl font-black italic mb-10 uppercase tracking-tighter">
                            {t("Ecosystem_Title")}
                        </h3>

                        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                            {[
                                [
                                    "fas fa-microchip",
                                    "Ecosystem_Hulu_Title",
                                    "Ecosystem_Hulu_Desc",
                                ],
                                [
                                    "fas fa-shipping-fast",
                                    "Ecosystem_Hilir_Title",
                                    "Ecosystem_Hilir_Desc",
                                ],
                                [
                                    "fas fa-globe",
                                    "Ecosystem_Connect_Title",
                                    "Ecosystem_Connect_Desc",
                                ],
                            ].map(([icon, title, desc]) => (
                                <div
                                    key={title}
                                    className="p-10 bg-white/5 border border-white/10 rounded-[45px] hover:border-yellow-500/30 transition-all"
                                >
                                    <i
                                        className={`${icon} text-yellow-500 text-2xl mb-6`}
                                    />
                                    <h4 className="text-white font-black mb-4 uppercase text-sm tracking-widest">
                                        {t(title)}
                                    </h4>
                                    <p className="text-gray-500 text-[10px] leading-relaxed font-bold uppercase italic">
                                        {t(desc)}
                                    </p>
                                </div>
                            ))}
                        </div>
                    </section>

                    {/* CORE PRINCIPLES */}
                    <section className="grid grid-cols-1 md:grid-cols-3 gap-12 pt-20 border-t border-white/10">
                        {[1, 2, 3].map((i) => (
                            <div key={i} className="space-y-4">
                                <h4 className="text-white text-sm font-black uppercase italic underline decoration-yellow-500 decoration-2 underline-offset-8">
                                    {t(`Charter_Point_${i}_Title`)}
                                </h4>
                                <p className="text-gray-500 text-[10px] leading-relaxed uppercase font-bold tracking-wider">
                                    {t(`Charter_Point_${i}_Body`)}
                                </p>
                            </div>
                        ))}
                    </section>

                    {/* DOCUMENTATION / GALLERY */}
                    <section className="mt-32">
                        <h3 className="text-white text-2xl font-black italic mb-10 uppercase tracking-tighter">
                            {isEn
                                ? "Industrial Documentation"
                                : "Dokumentasi Aktivitas Industri"}
                        </h3>

                        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {galleries.map((item) => (
                                <button
                                    key={item.id}
                                    type="button"
                                    onClick={() => setSelectedImg(item)}
                                    className="group relative overflow-hidden rounded-[40px] bg-white/5 border border-white/10 aspect-square text-left cursor-pointer"
                                >
                                    <img
                                        src={`/storage/${item.image_path}`}
                                        alt={item.title_id}
                                        className="w-full h-full object-cover opacity-60 group-hover:opacity-100 group-hover:scale-110 transition-all duration-700"
                                    />
                                    <div className="absolute inset-0 bg-gradient-to-t from-[#0a192f] via-transparent to-transparent opacity-80" />
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
                                </button>
                            ))}
                        </div>
                    </section>

                    {/* PARTNERS */}
                    <section className="mt-32 pt-20 border-t border-white/10 text-center">
                        <div className="mb-16">
                            <h3 className="text-white text-2xl font-black italic uppercase tracking-tighter mb-4">
                                {t("Partners_Title")}
                            </h3>
                            <p className="text-gray-500 text-[10px] font-bold uppercase tracking-[0.3em] max-w-xl mx-auto leading-relaxed">
                                {t("Partners_Subtitle")}
                            </p>
                        </div>

                        <div className="grid grid-cols-2 md:grid-cols-4 gap-12 items-center opacity-50 grayscale group hover:opacity-100 transition-all duration-700">
                            {["centric", "coats", "epson", "testex"].map(
                                (logo) => (
                                    <div
                                        key={logo}
                                        className="flex justify-center"
                                    >
                                        <img
                                            src={`/images/partners/${logo}.png`}
                                            alt={logo}
                                            className="h-10 object-contain group-hover:grayscale-0"
                                        />
                                    </div>
                                ),
                            )}
                        </div>

                        <div className="mt-20">
                            <a
                                href="mailto:support@digestexmedia.com"
                                className="inline-block bg-white/5 border border-white/10 text-white px-10 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-600 transition-all shadow-xl"
                            >
                                {isEn
                                    ? "Build a Strategic Partnership"
                                    : "Bangun Kemitraan Strategis"}
                            </a>
                        </div>
                    </section>
                </div>
            </div>

            <AnimatePresence>
                {selectedImg && (
                    <motion.div
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        exit={{ opacity: 0 }}
                        onClick={() => setSelectedImg(null)}
                        className="fixed inset-0 z-[100] flex items-center justify-center bg-[#0a192f]/95 backdrop-blur-xl p-4 md:p-10"
                    >
                        <motion.div
                            initial={{ scale: 0.9, y: 20 }}
                            animate={{ scale: 1, y: 0 }}
                            exit={{ scale: 0.9, y: 20 }}
                            onClick={(e) => e.stopPropagation()}
                            role="dialog"
                            aria-modal="true"
                            className="relative max-w-5xl w-full"
                        >
                            <img
                                src={`/storage/${selectedImg.image_path}`}
                                alt={selectedImg.title_id}
                                className="w-full h-auto max-h-[85vh] object-contain rounded-3xl shadow-2xl border border-white/10"
                            />

                            <div className="mt-6 text-center">
                                <h4 className="text-white text-xl font-black uppercase italic">
                                    {isEn
                                        ? selectedImg.title_en ||
                                          selectedImg.title_id
                                        : selectedImg.title_id}
                                </h4>
                                <p className="text-yellow-500 text-[10px] font-black uppercase tracking-[0.3em] mt-2">
                                    {selectedImg.category}
                                </p>
                            </div>

                            <button
                                type="button"
                                onClick={() => setSelectedImg(null)}
                                className="absolute -top-12 right-0 text-white text-sm font-black uppercase tracking-widest hover:text-yellow-500"
                            >
                                Close [x]
                            </button>
                        </motion.div>
                    </motion.div>
                )}
            </AnimatePresence>
        </AuthenticatedLayout>
    );
}
