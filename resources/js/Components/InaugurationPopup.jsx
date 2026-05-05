import React, { useState, useEffect } from "react";
import { motion, AnimatePresence } from "framer-motion";
import confetti from "canvas-confetti";

export default function InaugurationPopup({ isEn }) {
    const [isOpen, setIsOpen] = useState(false);

    useEffect(() => {
        const hasSeenPopup = localStorage.getItem("inauguration_v1");
        if (!hasSeenPopup) {
            setTimeout(() => {
                setIsOpen(true);
                confetti({
                    particleCount: 150,
                    spread: 70,
                    origin: { y: 0.6 },
                    colors: ["#EAB308", "#3B82F6", "#FFFFFF"],
                });
            }, 1500);
        }
    }, []);
    const handleClose = () => {
        setIsOpen(false);
        localStorage.setItem("inauguration_v1", "true");
    };

    return (
        <AnimatePresence>
            {isOpen && (
                <div className="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-[#0a192f]/90 backdrop-blur-xl">
                    <motion.div
                        initial={{ opacity: 0, scale: 0.9, y: 20 }}
                        animate={{ opacity: 1, scale: 1, y: 0 }}
                        exit={{ opacity: 0, scale: 0.9 }}
                        className="max-w-2xl w-full bg-gradient-to-br from-[#0d1d36] to-[#0a192f] border border-yellow-500/30 rounded-[50px] p-12 text-center relative overflow-hidden shadow-[0_0_50px_rgba(234,179,8,0.2)]"
                    >
                        {/* Efek Cahaya Latar */}
                        <div className="absolute -top-24 -left-24 w-48 h-48 bg-yellow-500/10 rounded-full blur-3xl"></div>

                        <div className="relative z-10">
                            <div className="flex justify-center gap-4 mb-8">
                                <img
                                    src="/images/logo-api.png"
                                    className="h-12 object-contain"
                                    alt="API"
                                />
                                <div className="h-12 w-px bg-white/20"></div>
                                <div className="h-12 flex items-center font-black text-blue-500 italic uppercase tracking-tighter">
                                    Digestex
                                </div>
                            </div>

                            <h2 className="text-white text-3xl md:text-4xl font-black uppercase italic tracking-tighter leading-tight mb-6">
                                {isEn
                                    ? "A New Era of Intelligence has Begun"
                                    : "Era Baru Intelijen Industri Telah Dimulai"}
                            </h2>

                            <p className="text-gray-400 text-sm md:text-base leading-relaxed italic mb-10">
                                {isEn
                                    ? "Welcome to the integrated API Jakarta & DigestexGlobal ecosystem. Your dashboard is now equipped with 8-digit precision mapping and real-time national stock radar."
                                    : "Selamat datang di ekosistem terpadu API Jakarta & DigestexGlobal. Dashboard Anda kini dilengkapi pemetaan presisi 8-digit dan radar stok nasional real-time."}
                            </p>

                            <button
                                onClick={handleClose} // <--- Ganti dari setIsOpen(false) menjadi handleClose
                                className="bg-yellow-500 text-[#0a192f] px-12 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] hover:bg-white transition-all shadow-xl"
                            >
                                {isEn
                                    ? "Enter Command Center"
                                    : "Masuk ke Pusat Kendali"}
                            </button>
                        </div>
                    </motion.div>
                </div>
            )}
        </AnimatePresence>
    );
}
