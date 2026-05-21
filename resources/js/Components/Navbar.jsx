import { Link, usePage, router } from "@inertiajs/react";
import { useState } from "react";
import {
    ShieldCheck,
    LogOut,
    Menu,
    X,
    Globe,
    Library,
    LayoutDashboard,
} from "lucide-react";

export default function Navbar() {
    const { props } = usePage();
    const { auth } = props;
    const isEn = props.locale === "en" || auth?.locale === "en";
    const [isOpen, setIsOpen] = useState(false);

    const handleLogout = (e) => {
        e.preventDefault();
        router.post(
            route("logout"),
            {},
            {
                onSuccess: () => {
                    window.location.href = "/";
                },
            },
        );
    };

    const toggleLanguage = (lang) => {
        router.post(
            route("language.switch", { locale: lang }),
            {},
            {
                preserveScroll: true,
                onSuccess: () => setIsOpen(false),
            },
        );
    };

    // Style helper premium dengan kontras ketajaman tinggi sejak awal muat halaman
    const navLinkStyle =
        "relative text-[10px] font-black uppercase tracking-widest text-gray-200 hover:text-amber-400 transition-all duration-300 group py-2 flex items-center gap-1";
    const underlineStyle =
        "absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-amber-500 to-yellow-400 transition-all duration-300 group-hover:w-full shadow-[0_0_10px_rgba(245,158,11,0.8)]";

    return (
        <nav className="bg-[#0b1329]/90 backdrop-blur-xl border-b border-white/5 sticky top-0 z-50 shadow-2xl">
            <div className="max-w-7xl mx-auto px-6">
                <div className="flex justify-between h-20 items-center">
                    
                    {/* 🛡️ LOGO RESMI MANDIRI: PT DIGESTEX GLOBAL INTELLIGENCE */}
                    <Link
                        href="/"
                        className="flex items-center gap-3 shrink-0 group"
                    >
                        <div className="hover:scale-105 transition-transform duration-300">
                            <img
                                src="/images/logoWeb.png"
                                className="h-10 w-auto rounded-xl shadow-lg shadow-amber-500/5 border border-white/5"
                                alt="Digestex Global Logo"
                            />
                        </div>
                        <div className="flex flex-col">
                            <span className="font-black tracking-tighter text-sm uppercase text-white leading-none">
                                DIGESTEX
                            </span>
                            <span className="text-[8px] text-amber-500 font-mono tracking-widest uppercase font-bold mt-1">
                                GLOBAL HUB
                            </span>
                        </div>
                    </Link>

                    {/* DESKTOP MENU (PC VIEWPORT HIGH CONTRAST) */}
                    <div className="hidden md:flex items-center gap-6">
                        <Link href={route("home")} className={navLinkStyle}>
                            {isEn ? "Home" : "Beranda"}
                            <span className={underlineStyle}></span>
                        </Link>

                        {/* 📊 SUNTIKAN TOMBOL UTAMA BARU: DASHBOARD AKSES DI NAV BAR DEPAN */}
                        <Link
                            href={route("dashboard")}
                            className={navLinkStyle}
                        >
                            <LayoutDashboard className="w-3.5 h-3.5 text-amber-500/80" />
                            {isEn ? "Dashboard" : "Dashboard"}
                            <span className={underlineStyle}></span>
                        </Link>

                        <Link href={route("join.us")} className={navLinkStyle}>
                            {isEn ? "Join Us" : "Gabung Kami"}
                            <span className={underlineStyle}></span>
                        </Link>

                        <Link href={route("about")} className={navLinkStyle}>
                            {isEn ? "About Us" : "Tentang Kami"}
                            <span className={underlineStyle}></span>
                        </Link>

                        {/* MENU ALAT INDUSTRI / TOOLBOX PREMIUM */}
                        <Link
                            href={route("tools.calculator")}
                            className="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-white hover:text-emerald-400 transition-all group relative py-2 bg-white/5 border border-white/5 px-3 py-1.5 rounded-xl duration-300"
                        >
                            <span>🧮</span>
                            <span>
                                {isEn ? "Industrial Toolbox" : "Alat Industri"}
                            </span>
                            <span className="bg-gradient-to-r from-emerald-500 to-teal-500 text-[6px] text-[#030712] px-1.5 py-0.5 rounded-md font-black tracking-tighter">
                                PREMIUM
                            </span>
                            <span className="absolute bottom-0 left-0 w-0 h-0.5 bg-emerald-500 transition-all duration-300 group-hover:w-full"></span>
                        </Link>

                        <Link
                            href={route("companies.index")}
                            className={navLinkStyle}
                        >
                            <Library className="w-3.5 h-3.5 text-amber-500/60" />{" "}
                            Big Data
                            <span className={underlineStyle}></span>
                        </Link>

                        {/* AUTHENTICATION ROUTING NODE SECTION */}
                        {auth.user ? (
                            <div className="flex items-center gap-6 ml-2 border-l border-white/10 pl-6">
                                <Link
                                    href={route("dashboard")}
                                    className="text-right group"
                                >
                                    <p className="text-[8px] font-black uppercase text-gray-500 tracking-widest leading-none mb-1 group-hover:text-amber-400 transition-colors">
                                        {isEn
                                            ? "Welcome Back,"
                                            : "Selamat Datang,"}
                                    </p>
                                    <p className="text-[10px] font-black italic text-white uppercase leading-none tracking-wide">
                                        "
                                        {auth.user.name
                                            ? auth.user.name.split(" ")[0]
                                            : "User"}
                                        "
                                    </p>
                                </Link>
                                <button
                                    onClick={handleLogout}
                                    className="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-red-400 transition-colors flex items-center gap-1 cursor-pointer"
                                >
                                    <LogOut className="w-3.5 h-3.5" />
                                </button>
                            </div>
                        ) : (
                            <Link
                                href={route("login")}
                                className="bg-gradient-to-r from-amber-500 to-yellow-500 text-[#030712] px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:scale-105 hover:shadow-[0_0_20px_rgba(234,179,8,0.3)] transition-all duration-300"
                            >
                                {isEn ? "Login" : "Masuk"}
                            </Link>
                        )}

                        {/* BILINGUAL LANGUAGE SELECTOR TOGGLES */}
                        <div className="flex bg-white/5 rounded-full p-1 border border-white/5 ms-2">
                            {["id", "en"].map((lang) => (
                                <button
                                    key={lang}
                                    onClick={() => toggleLanguage(lang)}
                                    className={`px-3 py-1 rounded-full text-[9px] font-black uppercase transition-all duration-300 cursor-pointer ${
                                        (lang === "en" ? isEn : !isEn)
                                            ? "bg-gradient-to-r from-amber-500 to-yellow-500 text-[#030712]"
                                            : "text-gray-400 hover:text-white"
                                    }`}
                                >
                                    {lang}
                                </button>
                            ))}
                        </div>
                    </div>

                    {/* MOBILE HAMBURGER BUTTON VIEWPORT */}
                    <div className="md:hidden flex items-center gap-4">
                        <button
                            onClick={() => setIsOpen(!isOpen)}
                            className="text-amber-500 bg-white/5 border border-white/5 rounded-xl p-2.5 z-50 relative transition-transform active:scale-95"
                        >
                            {isOpen ? <X className="w-5 h-5" /> : <Menu className="w-5 h-5" />}
                        </button>
                    </div>
                </div>
            </div>

            {/* 🛡️ REPARASI MUTLAK: OVERLAY DIUBAH MENJADI MURNI DI BAWAH KEPALA NAVBAR (ANTI-TUMPANG TINDIH KHUSUS HOME) */}
            <div
                className={`md:hidden bg-[#0b1329]/98 backdrop-blur-2xl border-t border-white/5 fixed top-20 left-0 right-0 bottom-0 z-50 transition-all duration-500 ease-in-out ${
                    isOpen
                        ? "translate-y-0 opacity-100 pointer-events-auto"
                        : "-translate-y-full opacity-0 pointer-events-none"
                }`}
            >
                {/* Jarak padding atas disesuaikan menjadi pt-6 agar teks menu mekar besar sempurna */}
                <div className="px-8 pt-6 pb-12 flex flex-col gap-6 h-full overflow-y-auto">
                    {/* TAYANGAN PROFIL USER DI SELULER */}
                    {auth.user && (
                        <div className="pb-4 border-b border-white/5 flex justify-between items-center bg-black/20 p-4 rounded-xl">
                            <div>
                                <p className="text-[8px] font-black text-amber-500 uppercase tracking-[0.2em] mb-1">
                                    {isEn ? "Authenticated Executive" : "Terautentikasi Sebagai"}
                                </p>
                                <p className="text-white font-black text-sm uppercase italic tracking-tight">
                                    {auth.user.name}
                                </p>
                            </div>
                            <Link
                                href={route("dashboard")}
                                onClick={() => setIsOpen(false)}
                                className="bg-gradient-to-r from-amber-500 to-yellow-500 text-[#030712] font-mono text-[9px] font-black px-3 py-2 rounded-lg uppercase tracking-wider shadow-md"
                            >
                                Console &rarr;
                            </Link>
                        </div>
                    )}

                    {/* LINK NAVIGASI RESPONSIVE SELULER BESAR & KONTRAS TINGGI */}
                    <div className="flex flex-col gap-5 mt-2 text-sm">
                        <Link
                            href={route("home")}
                            onClick={() => setIsOpen(false)}
                            className="text-xs font-black uppercase text-white tracking-widest hover:text-amber-400 transition-colors py-2 border-b border-white/5 block"
                        >
                            {isEn ? "Home Portal" : "Beranda Portal"}
                        </Link>

                        {/* 📊 SUNTIKAN TOMBOL RESPONSIVE NEW BAR DI HANDPHONE */}
                        <Link
                            href={route("dashboard")}
                            onClick={() => setIsOpen(false)}
                            className="text-xs font-black uppercase text-amber-400 tracking-widest hover:text-amber-400 transition-colors flex items-center gap-2 py-2 border-b border-white/5 block"
                        >
                            <LayoutDashboard className="w-4 h-4" />{" "}
                            {isEn ? "Data Dashboard" : "Dashboard Data"}
                        </Link>

                        <Link
                            href={route("join.us")}
                            onClick={() => setIsOpen(false)}
                            className="text-xs font-black uppercase text-white tracking-widest hover:text-amber-400 transition-colors py-2 border-b border-white/5 block"
                        >
                            {isEn ? "Join Us" : "Gabung Kami"}
                        </Link>

                        <Link
                            href={route("about")}
                            onClick={() => setIsOpen(false)}
                            className="text-xs font-black uppercase text-white tracking-widest hover:text-amber-400 transition-colors py-2 border-b border-white/5 block"
                        >
                            {isEn ? "About Intelligence" : "Tentang Intelijen"}
                        </Link>

                        {/* MOBILE TOOLBOX GLASS KARTU PREMIUM */}
                        <Link
                            href={route("tools.calculator")}
                            onClick={() => setIsOpen(false)}
                            className="flex justify-between items-center bg-emerald-500/5 border border-emerald-500/20 p-4 rounded-xl shadow-xl mt-1 duration-300"
                        >
                            <div className="flex items-center gap-3">
                                <span className="text-xl">🧮</span>
                                <span className="text-emerald-400 font-black uppercase text-[10px] tracking-widest">
                                    {isEn ? "Industrial Toolbox" : "Alat Industri"}
                                </span>
                            </div>
                            <span className="bg-gradient-to-r from-emerald-500 to-teal-500 text-[#030712] text-[7px] font-black px-2 py-1 rounded shadow-md">
                                PREMIUM
                            </span>
                        </Link>

                        <Link
                            href={route("companies.index")}
                            onClick={() => setIsOpen(false)}
                            className="text-xs font-black uppercase text-white tracking-widest hover:text-amber-400 transition-colors py-2 block"
                        >
                            Big Data Matrix
                        </Link>
                    </div>

                    <div className="mt-auto space-y-5">
                        {/* MOBILE LANGUAGE SWITCHER BUTTONS */}
                        <div className="flex bg-white/5 rounded-2xl p-1 border border-white/5">
                            <button
                                onClick={() => toggleLanguage("id")}
                                className={`flex-1 py-3.5 rounded-xl text-[10px] font-black uppercase transition-all duration-300 ${!isEn ? "bg-gradient-to-r from-amber-500 to-yellow-500 text-[#030712] shadow-md" : "text-gray-500"}`}
                            >
                                ID
                            </button>
                            <button
                                onClick={() => toggleLanguage("en")}
                                className={`flex-1 py-3.5 rounded-xl text-[10px] font-black uppercase transition-all duration-300 ${isEn ? "bg-gradient-to-r from-amber-500 to-yellow-500 text-[#030712] shadow-md" : "text-gray-500"}`}
                            >
                                EN
                            </button>
                        </div>

                        {/* MOBILE LOGIN/LOGOUT ACTION FLOW */}
                        {!auth.user ? (
                            <Link
                                href={route("login")}
                                onClick={() => setIsOpen(false)}
                                className="block bg-gradient-to-r from-amber-500 to-yellow-500 text-[#030712] text-center py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl tracking-wider font-bold"
                            >
                                {isEn ? "Log In Console" : "Masuk Konsol"}
                            </Link>
                        ) : (
                            <button
                                onClick={handleLogout}
                                className="w-full text-center py-4 text-[10px] font-black uppercase text-red-400 tracking-widest border border-red-500/10 rounded-2xl transition-all bg-red-500/5 cursor-pointer"
                            >
                                {isEn ? "Logout Account" : "Keluar Akun"}
                            </button>
                        )}
                    </div>
                </div>
            </div>
        </nav>
    );
}
