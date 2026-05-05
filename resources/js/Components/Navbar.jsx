import { Link, usePage, router } from "@inertiajs/react";
import { useState } from "react";

export default function Navbar() {
    const { props } = usePage();
    const { auth } = props;
    const isEn = props.locale === "en" || auth?.locale === "en";
    const [isOpen, setIsOpen] = useState(false);

    const handleLogout = (e) => {
        e.preventDefault();
        // Gunakan router.post agar token CSRF terkirim otomatis
        router.post(
            route("logout"),
            {},
            {
                onSuccess: () => {
                    // Paksa reload agar sesi benar-benar bersih
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
    // Style helper untuk link dengan animasi underline
    const navLinkStyle =
        "relative text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-white transition-all duration-300 group py-2";
    const underlineStyle =
        "absolute bottom-0 left-0 w-0 h-0.5 bg-yellow-500 transition-all duration-300 group-hover:w-full shadow-[0_0_8px_rgba(234,179,8,0.8)]";

    return (
        <nav className="bg-[#0a192f] border-b border-white/10 sticky top-0 z-50 shadow-2xl">
            <div className="max-w-7xl mx-auto px-6">
                <div className="flex justify-between h-20 items-center">
                    {/* LOGO */}
                    <Link href="/" className="flex items-center gap-3 shrink-0">
                        <img
                            src="/images/logo_api_digestex2.png"
                            className="h-10 w-auto"
                            alt="Logo"
                        />
                        <span className="text-white font-black italic text-xl tracking-tighter uppercase hidden sm:block">
                            Digestex<span className="text-yellow-500">V2</span>
                        </span>
                    </Link>

                    {/* DESKTOP MENU (PC) */}
                    <div className="hidden md:flex items-center gap-6">
                        <Link href={route("home")} className={navLinkStyle}>
                            {isEn ? "Home" : "Beranda"}
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

                        <Link
                            href={route("tools.calculator")}
                            className="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-blue-400 hover:text-white transition-all group relative py-2"
                        >
                            <span className="group-hover:scale-125 transition-transform">
                                🧮
                            </span>
                            <span>
                                {isEn ? "Industrial Toolbox" : "Alat Industri"}
                            </span>
                            <span className="bg-blue-600 text-[7px] text-white px-1.5 py-0.5 rounded-sm font-black animate-pulse">
                                PREMIUM
                            </span>
                            <span className="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-500 transition-all duration-300 group-hover:w-full"></span>
                        </Link>

                        <Link
                            href={route("companies.index")}
                            className={navLinkStyle}
                        >
                            Big Data
                            <span className={underlineStyle}></span>
                        </Link>

                        {/* AUTH SECTION */}
                        {auth.user ? (
                            <div className="flex items-center gap-6 ml-4 border-l border-white/10 pl-6">
                                <Link
                                    href={route("dashboard")}
                                    className="text-right group"
                                >
                                    <p className="text-[8px] font-black uppercase text-gray-500 tracking-widest leading-none mb-1 group-hover:text-yellow-500 transition-colors">
                                        {isEn
                                            ? "Welcome Back,"
                                            : "Selamat Datang,"}
                                    </p>
                                    <p className="text-[10px] font-black italic text-white uppercase leading-none">
                                        "{auth.user.name.split(" ")[0]}"
                                    </p>
                                </Link>
                                <button
                                    onClick={handleLogout}
                                    className="text-[10px] font-black uppercase tracking-widest text-gray-500 hover:text-red-500 transition-colors"
                                >
                                    {isEn ? "Logout" : "Keluar"}
                                </button>
                            </div>
                        ) : (
                            <Link
                                href={route("login")}
                                className="bg-yellow-500 text-[#0a192f] px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-yellow-400 hover:shadow-[0_0_20px_rgba(234,179,8,0.4)] transition-all"
                            >
                                {isEn ? "Login" : "Masuk"}
                            </Link>
                        )}

                        {/* LANG SWITCHER */}
                        <div className="flex bg-white/5 rounded-full p-1 border border-white/10 ms-2">
                            {["id", "en"].map((lang) => (
                                <button
                                    key={lang}
                                    onClick={() => toggleLanguage(lang)}
                                    className={`px-3 py-1 rounded-full text-[9px] font-black uppercase transition-all ${
                                        (lang === "en" ? isEn : !isEn)
                                            ? "bg-yellow-500 text-[#0a192f]"
                                            : "text-gray-500 hover:text-white"
                                    }`}
                                >
                                    {lang}
                                </button>
                            ))}
                        </div>
                    </div>

                    {/* MOBILE BUTTON */}
                    <div className="md:hidden flex items-center gap-4">
                        <button
                            onClick={() => setIsOpen(!isOpen)}
                            className="text-yellow-500 text-2xl p-2 z-50 relative"
                        >
                            <i
                                className={`fas ${isOpen ? "fa-times" : "fa-bars"}`}
                            ></i>
                        </button>
                    </div>
                </div>
            </div>

            {/* MOBILE MENU OVERLAY (HP) */}
            <div
                className={`md:hidden bg-[#0a192f] fixed inset-0 z-40 transition-all duration-500 ease-in-out ${
                    isOpen
                        ? "translate-y-0 opacity-100"
                        : "-translate-y-full opacity-0 pointer-events-none"
                }`}
            >
                <div className="px-8 pt-28 pb-10 flex flex-col gap-6 h-full overflow-y-auto">
                    {/* (Konten Mobile Menu Tetap Sama Seperti Sebelumnya) */}
                    {auth.user && (
                        <div className="pb-6 border-b border-white/10">
                            <p className="text-[9px] font-black text-yellow-500 uppercase tracking-[0.2em] mb-1">
                                {isEn
                                    ? "Authenticated As"
                                    : "Terautentikasi Sebagai"}
                            </p>
                            <p className="text-white font-bold text-lg truncate uppercase italic">
                                {auth.user.name}
                            </p>
                        </div>
                    )}
                    <div className="flex flex-col gap-6 mt-4">
                        <Link
                            href={route("home")}
                            onClick={() => setIsOpen(false)}
                            className="text-xs font-black uppercase text-gray-300 tracking-widest"
                        >
                            {isEn ? "Home" : "Beranda"}
                        </Link>
                        <Link
                            href={route("join.us")}
                            onClick={() => setIsOpen(false)}
                            className="text-xs font-black uppercase text-gray-300 tracking-widest"
                        >
                            {isEn ? "Join Us" : "Gabung Kami"}
                        </Link>

                        <Link
                            href={route("about")}
                            onClick={() => setIsOpen(false)}
                            className="text-xs font-black uppercase text-gray-300 tracking-widest hover:text-yellow-500 transition-colors"
                        >
                            {isEn ? "About Intelligence" : "Tentang Intelijen"}
                        </Link>

                        {/* MOBILE TOOLBOX LINK */}
                        <Link
                            href={route("tools.calculator")}
                            onClick={() => setIsOpen(false)}
                            className="flex justify-between items-center bg-blue-600/20 border border-blue-500/40 p-5 rounded-2xl shadow-lg mt-2"
                        >
                            <div className="flex items-center gap-3">
                                <span className="text-2xl">🧮</span>
                                <span className="text-blue-400 font-black uppercase text-[10px] tracking-widest">
                                    Industrial Toolbox
                                </span>
                            </div>
                            <span className="bg-blue-600 text-white text-[7px] font-black px-2 py-1 rounded animate-pulse">
                                PREMIUM
                            </span>
                        </Link>

                        <Link
                            href={route("companies.index")}
                            onClick={() => setIsOpen(false)}
                            className="text-xs font-black uppercase text-gray-300 tracking-widest hover:text-yellow-500 transition-colors"
                        >
                            Big Data
                        </Link>
                    </div>

                    <div className="mt-auto space-y-6">
                        {/* MOBILE LANG SWITCHER */}
                        <div className="flex bg-white/5 rounded-2xl p-1 border border-white/10">
                            <button
                                onClick={() => toggleLanguage("id")}
                                className={`flex-1 py-4 rounded-xl text-xs font-black uppercase ${!isEn ? "bg-yellow-500 text-[#0a192f]" : "text-gray-500"}`}
                            >
                                ID
                            </button>
                            <button
                                onClick={() => toggleLanguage("en")}
                                className={`flex-1 py-4 rounded-xl text-xs font-black uppercase ${isEn ? "bg-yellow-500 text-[#0a192f]" : "text-gray-500"}`}
                            >
                                EN
                            </button>
                        </div>

                        {/* MOBILE LOGIN/LOGOUT */}
                        {!auth.user ? (
                            <Link
                                href={route("login")}
                                onClick={() => setIsOpen(false)}
                                className="block bg-yellow-500 text-[#0a192f] text-center py-5 rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl italic font-black"
                            >
                                {isEn ? "Log In Console" : "Masuk Konsol"}
                            </Link>
                        ) : (
                            <button
                                onClick={handleLogout}
                                className="w-full text-center py-4 text-xs font-black uppercase text-red-500 tracking-widest border border-red-500/20 rounded-2xl transition-all active:bg-red-500/10"
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
