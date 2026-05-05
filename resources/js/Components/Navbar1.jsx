import { Link, usePage, router } from "@inertiajs/react";
import { useState } from "react";

export default function Navbar() {
    const { props } = usePage();
    const { auth, errors } = props;
    const isEn = props.locale === "en" || auth?.locale === "en";
    const [isOpen, setIsOpen] = useState(false);

    const handleLogout = (e) => {
        e.preventDefault();
        router.post(route("logout"));
    };

    const toggleLanguage = (lang) => {
        router.get(
            route("language.switch", { locale: lang }),
            {},
            {
                preserveScroll: true,
                onSuccess: () => setIsOpen(false),
            },
        );
    };

    return (
        <nav className="bg-[#0a192f] border-b border-white/10 sticky top-0 z-50">
            <div className="max-w-7xl mx-auto px-6">
                <div className="flex justify-between h-20 items-center">
                    {/* LOGO */}
                    <Link href="/" className="flex items-center gap-3">
                        <img
                            src="/images/logo_api_digestex2.png"
                            className="h-10 w-auto"
                            alt="Logo"
                        />
                        <span className="text-white font-black italic text-xl tracking-tighter uppercase">
                            Digestex<span className="text-yellow-500">V2</span>
                        </span>
                    </Link>

                    {/* DESKTOP MENU (Hidden on Mobile) */}
                    <div className="hidden md:flex items-center gap-6">
                        <Link
                            href={route("home")}
                            className="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-yellow-500"
                        >
                            {isEn ? "Home" : "Beranda"}
                        </Link>
                        <Link
                            href={route("companies.index")}
                            className="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-yellow-500"
                        >
                            Big Data
                        </Link>

                        {auth.user ? (
                            <div className="flex items-center gap-6">
                                {/* SAPAAN PERSONAL (LOKALISASI) */}
                                <div className="text-right hidden lg:block border-r border-white/10 pr-6">
                                    <p className="text-[8px] font-black uppercase text-gray-500 tracking-widest leading-none mb-1">
                                        {isEn
                                            ? "Welcome Back,"
                                            : "Selamat Datang,"}
                                    </p>
                                    <p className="text-[10px] font-black italic text-white uppercase leading-none">
                                        "{auth.user.name}"
                                    </p>
                                </div>

                                {/* Lencana ID Anggota */}
                                {/* <span className="text-[9px] font-black uppercase text-yellow-500 tracking-widest border border-yellow-500/20 px-3 py-1 rounded-full bg-yellow-500/5">
                                    {auth.user.role === "admin"
                                        ? "Admin Console"
                                        : `ID: ${auth.user.member_number || "Member"}`}
                                </span> */}

                                {/* Tombol Logout */}
                                <button
                                    onClick={handleLogout}
                                    className="text-[10px] font-black uppercase tracking-widest text-gray-500 hover:text-red-500 transition-colors"
                                >
                                    <i className="fas fa-sign-out-alt mr-2"></i>
                                    {isEn ? "Logout" : "Keluar"}
                                </button>
                            </div>
                        ) : (
                            <Link
                                href={route("login")}
                                className="bg-yellow-500 text-[#0a192f] px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest"
                            >
                                {isEn ? "Login" : "Masuk"}
                            </Link>
                        )}

                        {/* DESKTOP LANG SWITCHER */}
                        <div className="flex bg-white/5 rounded-full p-1 border border-white/10">
                            {["id", "en"].map((lang) => (
                                <button
                                    key={lang}
                                    onClick={() => toggleLanguage(lang)}
                                    className={`px-3 py-1 rounded-full text-[9px] font-black uppercase ${(lang === "en" ? isEn : !isEn) ? "bg-yellow-500 text-[#0a192f]" : "text-gray-500"}`}
                                >
                                    {lang}
                                </button>
                            ))}
                        </div>
                    </div>

                    {/* HAMBURGER BUTTON (Mobile Only) */}
                    <div className="md:hidden">
                        <button
                            onClick={() => setIsOpen(!isOpen)}
                            className="text-yellow-500 text-2xl p-2"
                        >
                            <i
                                className={`fas ${isOpen ? "fa-times" : "fa-bars"}`}
                            ></i>
                        </button>
                    </div>
                </div>
            </div>

            {/* MOBILE MENU (Slide Down) */}
            <div
                className={`md:hidden bg-[#0d1d36] border-t border-white/10 transition-all duration-300 overflow-hidden ${isOpen ? "max-h-[500px] opacity-100" : "max-h-0 opacity-0"}`}
            >
                <div className="px-6 py-6 flex flex-col gap-5">
                    {/* INFO USER DI HP */}
                    {auth.user && (
                        <div className="pb-4 border-b border-white/5">
                            <p className="text-[9px] font-black text-yellow-500 uppercase tracking-[0.2em] mb-1">
                                Authenticated As
                            </p>
                            <p className="text-white font-bold text-sm">
                                {auth.user.member_number || auth.user.name}
                            </p>
                        </div>
                    )}

                    <Link
                        href={route("home")}
                        className="text-xs font-black uppercase text-gray-300 tracking-widest"
                    >
                        {isEn ? "Home" : "Beranda"}
                    </Link>

                    <Link
                        href={route("companies.index")}
                        className="text-xs font-black uppercase text-gray-300 tracking-widest"
                    >
                        Big Data
                    </Link>

                    {/* MOBILE LANG SWITCHER */}
                    <div className="flex items-center gap-4 py-2">
                        <span className="text-[10px] font-black uppercase text-gray-500">
                            Language:
                        </span>
                        <div className="flex bg-white/5 rounded-lg p-1 border border-white/10">
                            <button
                                onClick={() => toggleLanguage("id")}
                                className={`px-4 py-1.5 rounded-md text-[10px] font-black ${!isEn ? "bg-yellow-500 text-[#0a192f]" : "text-white"}`}
                            >
                                ID
                            </button>
                            <button
                                onClick={() => toggleLanguage("en")}
                                className={`px-4 py-1.5 rounded-md text-[10px] font-black ${isEn ? "bg-yellow-500 text-[#0a192f]" : "text-white"}`}
                            >
                                EN
                            </button>
                        </div>
                    </div>

                    {auth.user ? (
                        <button
                            onClick={handleLogout}
                            className="w-full text-left text-xs font-black uppercase text-red-500 tracking-widest pt-4 border-t border-white/5"
                        >
                            {isEn ? "Logout" : "Keluar"}
                        </button>
                    ) : (
                        <Link
                            href={route("login")}
                            className="bg-yellow-500 text-[#0a192f] text-center py-4 rounded-xl text-xs font-black uppercase"
                        >
                            {isEn ? "Login Console" : "Masuk Konsol"}
                        </Link>
                    )}
                </div>
            </div>
        </nav>
    );
}
