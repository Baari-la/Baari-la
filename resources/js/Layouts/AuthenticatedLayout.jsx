import ApplicationLogo from "@/Components/ApplicationLogo";
import Dropdown from "@/Components/Dropdown";
import NavLink from "@/Components/NavLink";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink";
import { Link, usePage, router } from "@inertiajs/react";
import { useState } from "react";
import {
    ShieldCheck,
    Menu,
    X,
    LayoutDashboard,
    Globe,
    Layers,
    Settings,
    LogOut,
    FileText,
} from "lucide-react";

export default function AuthenticatedLayout({ header, children }) {
    const user = usePage().props.auth.user;
    const { locale } = usePage().props;
    const isEn = locale === "en";
    const [showingNavigationDropdown, setShowingNavigationDropdown] =
        useState(false);

    // 🌐 METODE PEMICU PERALIHAN BAHASA SECARA REAKTIF DI KONSOL INTERNAL
    const toggleLanguage = (lang) => {
        router.post(
            route("language.switch", { locale: lang }),
            {},
            {
                preserveScroll: true,
                onSuccess: () => setShowingNavigationDropdown(false),
            },
        );
    };

    return (
        <div className="min-h-screen bg-[#030712] text-gray-100 font-sans selection:bg-amber-500 selection:text-black">
            {/* --- TOP GLOWING BORDER (GARIS INDIKATOR MEWAH) --- */}
            <div className="h-1 w-full bg-gradient-to-r from-amber-600 via-yellow-500 to-emerald-500 shadow-[0_2px_20px_rgba(245,158,11,0.5)]"></div>

            {/* --- HEADER NAVIGASI GLASSMORPHISM PREMIUM --- */}
            <nav className="bg-[#0b1329]/80 backdrop-blur-xl border-b border-white/5 shadow-2xl sticky top-0 z-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between h-20 items-center">
                        <div className="flex items-center gap-10">
                            {/* LOGO PENYELARASAN BRANDING: API JAKARTA GLOBAL TRADE NODE */}
                            <div className="shrink-0 flex items-center">
                                <Link
                                    href="/"
                                    className="flex items-center gap-3 group"
                                >
                                    <img
                                        src="/images/logoWeb.png"
                                        className="h-10 w-auto rounded-xl shadow-lg shadow-amber-500/5"
                                        alt="Digestex Global Logo"
                                    />
                                    {/* <span className="font-black tracking-tighter text-sm uppercase text-white leading-none">
                                            API JAKARTA
                                        </span>
                                        <span className="text-[7px] text-amber-500 font-mono tracking-widest uppercase font-bold mt-1">
                                            GLOBAL TRADE NODE
                                        </span> */}
                                    {/* </div> */}
                                </Link>
                            </div>

                            {/* MENU UTAMA DESKTOP (PC LAYOUT) - HIGH CONTRAST READABILITY & ADDITIONAL TERMINAL */}
                            <div className="hidden space-x-1 sm:flex items-center">
                                <NavLink
                                    href={route("home")}
                                    active={route().current("home")}
                                    className="px-3 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest text-white transition-all duration-300 hover:text-amber-400"
                                >
                                    Home
                                </NavLink>
                                <NavLink
                                    href={route("dashboard")}
                                    active={route().current("dashboard")}
                                    className="px-3 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest text-white transition-all duration-300 hover:text-amber-400"
                                >
                                    Dashboard
                                </NavLink>
                                <NavLink
                                    href={route("intelligence.center")}
                                    active={route().current(
                                        "intelligence.center",
                                    )}
                                    className="px-3 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest text-white transition-all duration-300 hover:text-amber-400"
                                >
                                    Intelligence Center
                                </NavLink>
                                <NavLink
                                    href={route("rfqs.index")}
                                    active={route().current("rfqs.*")}
                                    className="px-3 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest text-white transition-all duration-300 hover:text-cyan-400 flex items-center gap-1.5"
                                >
                                    <FileText className="w-3.5 h-3.5 text-cyan-400/80" />
                                    RFQ Marketplace
                                </NavLink>
                                <NavLink
                                    href={route("purchase-orders.index")}
                                    active={route().current(
                                        "purchase-orders.*",
                                    )}
                                    className="px-3 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest text-white hover:text-amber-400"
                                >
                                    Purchase Orders
                                </NavLink>
                                <NavLink
                                    href={route("quotations.index")}
                                    active={route().current("quotations.index")}
                                >
                                    My Quotations
                                </NavLink>
                                {/* FITUR AMUNISI BARU: DIGITAL CONTAINER TRACKING JICT & NPCT1 */}
                                <NavLink
                                    href={route("logistics.tracking")}
                                    active={route().current(
                                        "logistics.tracking",
                                    )}
                                    className="px-3 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest text-white transition-all duration-300 hover:text-amber-400 flex items-center gap-1.5"
                                >
                                    <Globe className="w-3.5 h-3.5 text-amber-500/80" />
                                    {isEn
                                        ? "Port Tracking"
                                        : "Pelacakan Kontainer"}
                                </NavLink>

                                <NavLink
                                    href={route("tools.calculator")}
                                    active={route().current("tools.calculator")}
                                    className="px-3 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest text-white transition-all flex items-center gap-1.5 bg-white/5 border border-white/5 duration-300 hover:text-emerald-400"
                                >
                                    <span>🧮</span>
                                    <span>Industrial Toolbox</span>
                                    <span className="bg-gradient-to-r from-emerald-500 to-teal-500 text-[7px] text-[#030712] px-1.5 py-0.5 rounded-md font-black uppercase tracking-tighter">
                                        Premium
                                    </span>
                                </NavLink>
                                {/* ✨ SUNTIKAN INTEGRASI BARU: DROPDOWN COLLECTIVE SOURCING DESKTOP */}
                                <div className="relative flex items-center px-2">
                                    <Dropdown>
                                        <Dropdown.Trigger>
                                            <button className="flex items-center gap-1.5 px-3 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest text-white hover:text-amber-400 focus:outline-none transition duration-150 cursor-pointer">
                                                <Layers className="w-3.5 h-3.5 text-amber-500/80" />
                                                <span>Collective Sourcing</span>
                                                <span className="text-[8px] text-gray-400">
                                                    ▼
                                                </span>
                                            </button>
                                        </Dropdown.Trigger>
                                        <Dropdown.Content className="bg-[#0b1329] border border-white/10 rounded-2xl shadow-2xl p-1 mt-2 min-w-[200px]">
                                            <Dropdown.Link
                                                href={route(
                                                    "collective-sourcing.index",
                                                )}
                                                className="text-xs font-bold text-gray-300 hover:bg-white/5 hover:text-white rounded-xl px-4 py-2.5 block w-full text-left"
                                            >
                                                Open Demand Groups
                                            </Dropdown.Link>
                                            <Dropdown.Link
                                                href={route(
                                                    "collective-sourcing.create",
                                                )}
                                                className="text-xs font-bold text-gray-300 hover:bg-white/5 hover:text-white rounded-xl px-4 py-2.5 block w-full text-left"
                                            >
                                                Create Requirement
                                            </Dropdown.Link>
                                            <Dropdown.Link
                                                href={route(
                                                    "collective-sourcing.my-requests",
                                                )}
                                                className="text-xs font-bold text-gray-300 hover:bg-white/5 hover:text-white rounded-xl px-4 py-2.5 block w-full text-left"
                                            >
                                                My Requests
                                            </Dropdown.Link>
                                        </Dropdown.Content>
                                    </Dropdown>
                                </div>
                            </div>
                        </div>

                        {/* DROPDOWN USER EXECUTIVE (PC VIEW DENGAN BAHASA) */}
                        <div className="hidden sm:flex sm:items-center sm:ms-6 gap-3">
                            {/* 🌐 SUNTIKAN INTEGRASI BARU: TOMBOL PILIHAN BAHASA DI INDOOR DASBOR */}
                            <div className="flex bg-white/5 rounded-xl p-1 border border-white/5">
                                {["id", "en"].map((lang) => (
                                    <button
                                        key={lang}
                                        onClick={() => toggleLanguage(lang)}
                                        className={`px-3 py-1 rounded-lg text-[9px] font-black uppercase transition-all duration-300 cursor-pointer ${
                                            (lang === "en" ? isEn : !isEn)
                                                ? "bg-gradient-to-r from-amber-500 to-yellow-500 text-[#030712] shadow-md"
                                                : "text-gray-400 hover:text-white"
                                        }`}
                                    >
                                        {lang}
                                    </button>
                                ))}
                            </div>

                            <div className="bg-black/30 border border-white/5 px-4 py-2 rounded-2xl flex items-center gap-4 shadow-inner">
                                <Dropdown>
                                    <Dropdown.Trigger>
                                        <button className="inline-flex items-center text-[11px] font-black uppercase tracking-widest text-white hover:text-amber-400 focus:outline-none transition cursor-pointer">
                                            <i className="fas fa-user-shield text-amber-500/60 mr-2 text-xs"></i>
                                            {user
                                                ? user.name
                                                : "Executive Guest"}
                                            <i className="fas fa-chevron-down text-[8px] text-gray-500 ms-2"></i>
                                        </button>
                                    </Dropdown.Trigger>
                                    <Dropdown.Content className="bg-[#0b1329] border border-white/10 rounded-2xl shadow-2xl p-1 mt-2">
                                        <Dropdown.Link
                                            href={route("profile.edit")}
                                            className="text-xs font-bold text-gray-300 hover:bg-white/5 hover:text-white rounded-xl px-4 py-2.5 flex items-center gap-2"
                                        >
                                            <Settings className="w-3.5 h-3.5 text-gray-500" />{" "}
                                            Account Settings
                                        </Dropdown.Link>
                                        <Dropdown.Link
                                            href={route("logout")}
                                            method="post"
                                            as="button"
                                            className="text-xs font-bold text-red-400 hover:bg-red-500/10 rounded-xl px-4 py-2.5 flex items-center gap-2 w-full text-left"
                                        >
                                            <LogOut className="w-3.5 h-3.5" />{" "}
                                            Log Out
                                        </Dropdown.Link>
                                    </Dropdown.Content>
                                </Dropdown>
                            </div>
                        </div>

                        {/* TOMBOL MENU MOBILE HAMBURGER (RESPONSIVE VIEWPORT) */}
                        <div className="-me-2 flex items-center sm:hidden">
                            <button
                                onClick={() =>
                                    setShowingNavigationDropdown(
                                        !showingNavigationDropdown,
                                    )
                                }
                                className="inline-flex items-center justify-center p-2.5 rounded-xl bg-white/5 border border-white/5 text-gray-400 hover:text-amber-400 focus:outline-none transition duration-300"
                            >
                                {showingNavigationDropdown ? (
                                    <X className="w-5 h-5" />
                                ) : (
                                    <Menu className="w-5 h-5" />
                                )}
                            </button>
                        </div>
                    </div>
                </div>

                {/* AREA PANEL NAVIGASI VERSI RESPONSIVE (MOBILE VIEW HIGH CONTRAST) */}
                <div
                    className={
                        (showingNavigationDropdown ? "block" : "hidden") +
                        " sm:hidden bg-[#0b1329]/95 backdrop-blur-xl border-t border-white/5 px-4 py-4 space-y-3 animate-fade-in"
                    }
                >
                    <ResponsiveNavLink
                        href={route("home")}
                        active={route().current("home")}
                        className="rounded-xl text-[10px] font-black uppercase tracking-widest block py-3 px-4 text-white hover:text-amber-400"
                    >
                        Home Portal
                    </ResponsiveNavLink>
                    <ResponsiveNavLink
                        href={route("dashboard")}
                        active={route().current("dashboard")}
                        className="rounded-xl text-[10px] font-black uppercase tracking-widest block py-3 px-4 text-white hover:text-amber-400"
                    >
                        Data Dashboard
                    </ResponsiveNavLink>
                    <ResponsiveNavLink
                        href={route("intelligence.center")}
                        active={route().current("intelligence.center")}
                        className="rounded-xl text-[10px] font-black uppercase tracking-widest block py-3 px-4 text-white hover:text-amber-400"
                    >
                        Intelligence Center
                    </ResponsiveNavLink>

                    <ResponsiveNavLink
                        href={route("rfqs.index")}
                        active={route().current("rfqs.*")}
                        className="rounded-xl text-[10px] font-black uppercase tracking-widest block py-3 px-4 text-cyan-400 flex items-center gap-2"
                    >
                        <FileText className="w-3.5 h-3.5" />
                        RFQ Marketplace
                    </ResponsiveNavLink>

                    {/* SINKRONISASI MOBILE MENU: LIVE CONTAINER TRACKING GATEWAY */}
                    <ResponsiveNavLink
                        href={route("logistics.tracking")}
                        active={route().current("logistics.tracking")}
                        className="rounded-xl text-[10px] font-black uppercase tracking-widest block py-3 px-4 text-amber-400 flex items-center gap-2"
                    >
                        <Globe className="w-3.5 h-3.5" />{" "}
                        {isEn ? "Port Tracking" : "Pelacakan Kontainer"}
                    </ResponsiveNavLink>

                    <ResponsiveNavLink
                        href={route("tools.calculator")}
                        active={route().current("tools.calculator")}
                        className="rounded-xl text-[10px] font-black uppercase tracking-widest py-3 px-4 bg-white/5 border border-white/5 text-emerald-400 flex items-center justify-between"
                    >
                        <span>🧮 Industrial Toolbox</span>
                        <span className="bg-emerald-500 text-black text-[7px] font-black px-1.5 py-0.5 rounded uppercase">
                            Premium
                        </span>
                    </ResponsiveNavLink>
                    {/* ✨ SUBMENU COLLECTIVE SOURCING VERSI MOBILE */}
                    <div className="pt-2 border-t border-white/5 space-y-1">
                        <div className="px-4 py-1 text-[9px] font-black text-amber-500/80 uppercase tracking-widest flex items-center gap-1.5">
                            <Layers className="w-3 h-3" /> Collective Sourcing
                        </div>
                        <ResponsiveNavLink
                            href={route("collective-sourcing.index")}
                            active={route().current(
                                "collective-sourcing.index",
                            )}
                            className="rounded-xl text-[10px] font-bold uppercase tracking-widest block py-2.5 px-6 text-gray-300 hover:text-white"
                        >
                            - Open Demand Groups
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            href={route("collective-sourcing.create")}
                            active={route().current(
                                "collective-sourcing.create",
                            )}
                            className="rounded-xl text-[10px] font-bold uppercase tracking-widest block py-2.5 px-6 text-gray-300 hover:text-white"
                        >
                            - Create Requirement
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            href={route("collective-sourcing.my-requests")}
                            active={route().current(
                                "collective-sourcing.my-requests",
                            )}
                            className="rounded-xl text-[10px] font-bold uppercase tracking-widest block py-2.5 px-6 text-gray-300 hover:text-white"
                        >
                            - My Requests
                        </ResponsiveNavLink>
                    </div>

                    {/* 🌐 SUNTIKAN REAKTIF: LANGUAGE SWITCHER KHUSUS MOBILE VIEWPORT DI INDOOR DASBOR */}
                    <div className="pt-2 border-t border-white/5">
                        <div className="flex bg-white/5 rounded-xl p-1 border border-white/5 w-fit">
                            {["id", "en"].map((lang) => (
                                <button
                                    key={lang}
                                    onClick={() => toggleLanguage(lang)}
                                    className={`px-4 py-2 rounded-lg text-[9px] font-black uppercase transition-all duration-300 cursor-pointer ${
                                        (lang === "en" ? isEn : !isEn)
                                            ? "bg-gradient-to-r from-amber-500 to-yellow-500 text-[#030712] shadow-md"
                                            : "text-gray-400 hover:text-white"
                                    }`}
                                >
                                    {lang}
                                </button>
                            ))}
                        </div>
                    </div>
                </div>
            </nav>

            {/* --- AREA HEADER DINAMIS SUB-HALAMAN (SINKRONISASI TEMA DARK PREMIUM) --- */}
            {header && (
                <header className="bg-gradient-to-r from-[#0b1329] via-[#0f172a]/40 to-transparent border-b border-white/5 shadow-2xl relative overflow-hidden">
                    {/* Ornamen Pendaran Cahaya Neon Emas di Sudut Belakang */}
                    <div className="absolute top-0 left-0 w-64 h-64 bg-amber-500/5 rounded-full blur-3xl -ml-20 -mt-20"></div>

                    <div className="mx-auto max-w-7xl px-6 py-6 sm:px-8 lg:px-10 relative z-10">
                        {/* Garis Vertikal Emas Kokoh Sebagai Penopang Fokus Visual */}
                        <div className="border-l-4 border-amber-500 pl-4">
                            <h1 className="text-lg lg:text-xl font-black uppercase tracking-wider font-sans text-transparent bg-clip-text bg-gradient-to-r from-white via-gray-100 to-gray-400 drop-shadow-[0_2px_10px_rgba(255,255,255,0.1)]">
                                {header}
                            </h1>
                        </div>
                    </div>
                </header>
            )}

            {/* --- AREA MAIN STREAM VIEWPORT UTAMA (ANIMASI SMOOTH LOADING) --- */}
            <main className="relative z-10 animate-fade-in-up">{children}</main>

            {/* --- PROTEKSI FOOTER LEGAL BADAN HUKUM PIHAK KETIGA INDEPENDEN --- */}
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="border-t border-white/5 pt-8 pb-12 mt-12 flex flex-col md:flex-row justify-between items-center gap-4 text-[10px] text-gray-500 font-medium font-mono">
                    <p>
                        &copy; 2026{" "}
                        <span className="text-amber-500/80 font-bold">
                            PT. Digestex Global Intelligence
                        </span>
                        . All Rights Reserved.
                    </p>
                </div>
            </div>
        </div>
    );
}
