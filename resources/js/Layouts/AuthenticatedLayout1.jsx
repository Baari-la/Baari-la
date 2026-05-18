import ApplicationLogo from "@/components/applicationlogo";
import Dropdown from "@/components/dropdown";
import NavLink from "@/components/navlink";
import ResponsiveNavLink from "@/components/responsivenavlink";
import { Link, usePage } from "@inertiajs/react";
import { useState } from "react";
import {
    ShieldCheck,
    Menu,
    X,
    LayoutDashboard,
    Settings,
    LogOut,
} from "lucide-react";

export default function AuthenticatedLayout({ header, children }) {
    const user = usePage().props.auth.user;
    const { locale } = usePage().props;
    const isEn = locale === "en";
    const [showingNavigationDropdown, setShowingNavigationDropdown] =
        useState(false);

    return (
        <div className="min-h-screen bg-[#030712] text-gray-100 font-sans selection:bg-amber-500 selection:text-black">
            {/* --- TOP GLOWING BORDER (GARIS INDIKATOR MEWAH) --- */}
            <div className="h-1 w-full bg-gradient-to-r from-amber-600 via-yellow-500 to-emerald-500 shadow-[0_2px_20px_rgba(245,158,11,0.5)]"></div>

            {/* --- HEADER NAVIGASI GLASSMORPHISM PREMIUM --- */}
            <nav className="bg-[#0b1329]/80 backdrop-blur-xl border-b border-white/5 shadow-2xl sticky top-0 z-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between h-20 items-center">
                        <div className="flex items-center gap-10">
                            {/* LOGO PERUSAHAAN UTAMA DENGAN LENCANA VERIFIKASI */}
                            <div className="shrink-0 flex items-center">
                                <Link
                                    href="/"
                                    className="flex items-center gap-3 group"
                                >
                                    <div className="w-9 h-9 rounded-xl bg-gradient-to-br from-amber-500 to-yellow-600 flex items-center justify-center shadow-lg shadow-amber-500/20 group-hover:scale-105 transition-transform duration-300">
                                        <ShieldCheck className="w-5 h-5 text-[#030712] stroke-[2.5]" />
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
                            </div>

                            {/* MENU UTAMA DESKTOP (PC LAYOUT) - PUTIH KABUT TAJAM */}
                            <div className="hidden space-x-2 sm:flex items-center">
                                <NavLink
                                    href={route("home")}
                                    active={route().current("home")}
                                    className="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-200 hover:text-amber-400 hover:bg-white/5 transition-all duration-300"
                                >
                                    Home
                                </NavLink>
                                <NavLink
                                    href={route("dashboard")}
                                    active={route().current("dashboard")}
                                    className="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-200 hover:text-amber-400 hover:bg-white/5 transition-all duration-300"
                                >
                                    Dashboard
                                </NavLink>
                                <NavLink
                                    href={route("intelligence.center")}
                                    active={route().current(
                                        "intelligence.center",
                                    )}
                                    className="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-200 hover:text-amber-400 hover:bg-white/5 transition-all duration-300"
                                >
                                    Intelligence Center
                                </NavLink>
                                <NavLink
                                    href={route("tools.calculator")}
                                    active={route().current("tools.calculator")}
                                    className="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-200 transition-all flex items-center gap-2 bg-white/5 border border-white/5 duration-300 hover:text-emerald-400 hover:bg-white/10 hover:border-emerald-500/30"
                                >
                                    <span>🧮</span>
                                    <span>Industrial Toolbox</span>
                                    <span className="bg-gradient-to-r from-emerald-500 to-teal-500 text-[7px] text-[#030712] px-1.5 py-0.5 rounded-md font-black uppercase tracking-tighter shadow-sm">
                                        Premium
                                    </span>
                                </NavLink>
                            </div>
                        </div>

                        {/* DROPDOWN USER EXECUTIVE (PC VIEW) */}
                        <div className="hidden sm:flex sm:items-center sm:ms-6">
                            <div className="bg-black/40 border border-white/10 px-4 py-2 rounded-2xl flex items-center gap-4 shadow-xl backdrop-blur-md">
                                <Dropdown>
                                    <Dropdown.Trigger>
                                        <button className="inline-flex items-center text-[11px] font-black uppercase tracking-widest text-slate-200 hover:text-amber-400 focus:outline-none transition cursor-pointer gap-2">
                                            <div className="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></div>
                                            {user
                                                ? user.name
                                                : "Executive Guest"}
                                            <svg
                                                className="h-3 w-3 text-slate-400"
                                                xmlns="http://w3.org"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fillRule="evenodd"
                                                    d="M5.293 7.293a1.414 1.414 0 011.414 0L10 10.586l3.293-3.293a1.414 1.414 0 111.414 1.414l-4 4a1.414 1.414 0 01-1.414 0l-4-4a1.414 1.414 0 010-1.414z"
                                                    clipRule="evenodd"
                                                />
                                            </svg>
                                        </button>
                                    </Dropdown.Trigger>
                                    <Dropdown.Content contentClasses="bg-[#0b1329] border border-white/10 rounded-2xl shadow-2xl p-1.5 mt-2 backdrop-blur-2xl">
                                        <Dropdown.Link
                                            href={route("profile.edit")}
                                            className="text-xs font-bold text-slate-300 hover:bg-white/5 hover:text-amber-400 rounded-xl px-4 py-2.5 flex items-center gap-2 transition-all duration-200"
                                        >
                                            <Settings className="w-3.5 h-3.5 text-slate-400" />{" "}
                                            Account Settings
                                        </Dropdown.Link>
                                        <div className="h-px bg-white/5 my-1"></div>
                                        <Dropdown.Link
                                            href={route("logout")}
                                            method="post"
                                            as="button"
                                            className="text-xs font-bold text-rose-400 hover:bg-rose-500/10 rounded-xl px-4 py-2.5 flex items-center gap-2 w-full text-left transition-all duration-200"
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
                                className="inline-flex items-center justify-center p-2.5 rounded-xl bg-white/5 border border-white/10 text-slate-200 hover:text-amber-400 hover:bg-white/10 focus:outline-none transition duration-300"
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

                {/* AREA PANEL NAVIGASI VERSI RESPONSIVE (MOBILE VIEW) */}
                <div
                    className={
                        (showingNavigationDropdown ? "block" : "hidden") +
                        " sm:hidden bg-[#0b1329]/95 backdrop-blur-xl border-t border-white/10 px-4 py-4 space-y-2"
                    }
                >
                    <ResponsiveNavLink
                        href={route("home")}
                        active={route().current("home")}
                        className="rounded-xl text-[10px] font-black uppercase tracking-widest block py-3 px-4 text-slate-200 hover:text-amber-400 hover:bg-white/5 transition-all duration-200"
                    >
                        Home Portal
                    </ResponsiveNavLink>
                    <ResponsiveNavLink
                        href={route("dashboard")}
                        active={route().current("dashboard")}
                        className="rounded-xl text-[10px] font-black uppercase tracking-widest block py-3 px-4 text-slate-200 hover:text-amber-400 hover:bg-white/5 transition-all duration-200"
                    >
                        Data Dashboard
                    </ResponsiveNavLink>
                    <ResponsiveNavLink
                        href={route("intelligence.center")}
                        active={route().current("intelligence.center")}
                        className="rounded-xl text-[10px] font-black uppercase tracking-widest block py-3 px-4 text-slate-200 hover:text-amber-400 hover:bg-white/5 transition-all duration-200"
                    >
                        Intelligence Center
                    </ResponsiveNavLink>
                    <ResponsiveNavLink
                        href={route("tools.calculator")}
                        active={route().current("tools.calculator")}
                        className="rounded-xl text-[10px] font-black uppercase tracking-widest py-3 px-4 bg-white/5 border border-white/10 text-emerald-300 hover:text-emerald-400 hover:bg-white/10 flex items-center justify-between transition-all duration-200"
                    >
                        <span className="flex items-center gap-2">
                            <span>🧮</span> Industrial Toolbox
                        </span>
                        <span className="bg-gradient-to-r from-emerald-500 to-teal-500 text-[#030712] text-[7px] font-black px-1.5 py-0.5 rounded-md uppercase tracking-tighter shadow-sm">
                            Premium
                        </span>
                    </ResponsiveNavLink>
                </div>
            </nav>

            {/* --- AREA HEADER DINAMIS SUB-HALAMAN (SINKRONISASI TEMA DARK PREMIUM) --- */}
            {header && (
                <header className="bg-gradient-to-r from-[#0b1329] to-transparent border-b border-white/5 shadow-2xl relative overflow-hidden">
                    <div className="absolute top-0 left-0 w-64 h-64 bg-amber-500/5 rounded-full blur-3xl -ml-20 -mt-20"></div>
                    <div className="mx-auto max-w-7xl px-6 py-6 sm:px-8 lg:px-10 relative z-10">
                        <div className="text-xl font-black uppercase tracking-tight text-white border-l-4 border-amber-500 pl-4 font-sans drop-shadow-[0_2px_8px_rgba(255,255,255,0.1)]">
                            {header}
                        </div>
                    </div>
                </header>
            )}

            {/* --- AREA MAIN STREAM VIEWPORT UTAMA --- */}
            <main className="relative z-10 container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
                {children}
            </main>
        </div>
    );
}
