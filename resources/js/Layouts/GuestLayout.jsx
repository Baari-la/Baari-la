import ApplicationLogo from "@/Components/ApplicationLogo";
import { Link } from "@inertiajs/react";
import React from "react";

export default function GuestLayout({ children }) {
    return (
        <div className="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[#030712] relative overflow-hidden selection:bg-amber-500 selection:text-black">
            {/* --- ORNAMEN PENDAPAN LAMPU NEON DI LATAR BELAKANG --- */}
            <div className="absolute top-1/4 left-1/2 -translate-x-1/2 w-[500px] h-[500px] bg-amber-500/5 rounded-full blur-[120px] pointer-events-none"></div>
            <div className="absolute bottom-10 left-10 w-72 h-72 bg-blue-500/5 rounded-full blur-[100px] pointer-events-none"></div>

            {/* --- SEKTOR LOGO MANDIRI: PT DIGESTEX GLOBAL INTELLIGENCE --- */}
            <div className="mb-6 animate-fade-in text-center flex flex-col items-center gap-2 relative z-10">
                <Link href="/" className="group">
                    {" "}
                    <img
                        src="/images/logoWeb.png"
                        className="h-10 w-auto rounded-xl shadow-lg shadow-amber-500/5"
                        alt="Digestex Global Logo"
                    />
                </Link>
            </div>

            {/* 🛡️ PERUBAHAN BESAR KARTU: SEKARANG MEKAR KONTRAS TINGGI DENGAN LINGKUNGAN SEKITAR */}
            <div className="w-full sm:max-w-md mt-2 px-8 py-8 bg-[#0b1329] border border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.5)] border-t-amber-500/30 overflow-hidden sm:rounded-[35px] relative z-10 backdrop-blur-2xl">
                {/* Garis Dekorasi Tipis Berpendar di Sudut Atas Kartu */}
                <div className="absolute top-0 inset-x-0 h-[1px] bg-gradient-to-r from-transparent via-amber-500/30 to-transparent"></div>

                {/* Konten Formulir Dalam */}
                {children}
            </div>
        </div>
    );
}
