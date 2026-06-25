import ApplicationLogo from "@/Components/ApplicationLogo";
import Dropdown from "@/Components/Dropdown";
import NavLink from "@/Components/NavLink";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink";
import { Link, usePage, router } from "@inertiajs/react";
import { useState } from "react";
import HoverDropdown from "@/Components/HoverDropdown";
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
    Newspaper, // Tambahkan icon koran untuk menu berita
} from "lucide-react";

export default function AuthenticatedLayout({
    icon,
    label,
    header,
    width = "w-72",
    children,
}) {
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
    const dashboardNavStyle =
        "px-3 py-2 text-[11px] font-bold uppercase tracking-widest text-slate-700 hover:text-amber-500 transition-all duration-300";
    const mobileNavStyle =
        "block px-4 py-3 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100 hover:text-amber-500 transition";

    return (
        <div className="min-h-screen bg-[#030712] text-gray-100 font-sans selection:bg-amber-500 selection:text-black">
            {/* --- TOP GLOWING BORDER (GARIS INDIKATOR MEWAH) --- */}
            <div className="h-1 w-full bg-gradient-to-r from-amber-600 via-yellow-500 to-emerald-500 shadow-[0_2px_20px_rgba(245,158,11,0.5)]"></div>

            {/* --- HEADER NAVIGASI GLASSMORPHISM PREMIUM --- */}
            <nav className="bg-white/95 backdrop-blur-xl border-b border-slate-200 shadow-sm sticky top-0 z-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-[auto_1fr_auto] h-24 items-center gap-10">
                        <div className="flex items-center gap-10">
                            {/* LOGO PENYELARASAN BRANDING: API JAKARTA GLOBAL TRADE NODE */}
                            <div className="shrink-0 flex items-center">
                                <Link
                                    href="/"
                                    className="flex flex-col items-start shrink-0"
                                >
                                    <img
                                        src="/images/logoWeb.png"
                                        className="h-14 w-auto"
                                        alt="Digestex Global"
                                    />

                                    <p className="mt-1 text-[9px] tracking-[0.15em] leading-none">
                                        <span className="text-[#0B2E59]">
                                            Where Textile Meets
                                        </span>{" "}
                                        <span className="text-[#F97316] font-semibold">
                                            Intelligence
                                        </span>
                                    </p>
                                </Link>
                            </div>

                            {/* MENU UTAMA DESKTOP (PC LAYOUT) */}
                            <div className="hidden xl:flex items-center gap-1">
                                <div className="relative flex items-center">
                                    <HoverDropdown
                                        icon={
                                            <Globe className="w-4 h-4 text-blue-500" />
                                        }
                                        label="Intelligence"
                                    >
                                        <Link
                                            href={route("intelligence.center")}
                                            className="block px-4 py-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 transition-all"
                                        >
                                            Intelligence Center
                                        </Link>

                                        <Link
                                            href={route("market-intelligence")}
                                            className="block px-4 py-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 transition-all"
                                        >
                                            Market Intelligence
                                        </Link>
                                    </HoverDropdown>
                                </div>
                                <div className="relative flex items-center">
                                    <HoverDropdown
                                        icon={
                                            <FileText className="w-4 h-4 text-cyan-500" />
                                        }
                                        label="Trade Hub"
                                    >
                                        <Link
                                            href={route("rfqs.index")}
                                            className="block px-4 py-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 transition-all"
                                        >
                                            RFQ Marketplace
                                        </Link>

                                        <Link
                                            href={route(
                                                "purchase-orders.index",
                                            )}
                                            className="block px-4 py-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 transition-all"
                                        >
                                            Purchase Orders
                                        </Link>

                                        <Link
                                            href={route("quotations.index")}
                                            className="block px-4 py-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 transition-all"
                                        >
                                            {isEn
                                                ? "My Quotations"
                                                : "Penawaran Saya"}
                                        </Link>
                                    </HoverDropdown>
                                </div>

                                <div className="relative flex items-center">
                                    <HoverDropdown
                                        icon={
                                            <Layers className="w-4 h-4 text-amber-500" />
                                        }
                                        label="Sourcing Hub"
                                    >
                                        <Link
                                            href={route(
                                                "collective-sourcing.index",
                                            )}
                                            className="block px-4 py-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 transition-all"
                                        >
                                            Open Demand Groups
                                        </Link>

                                        <Link
                                            href={route(
                                                "collective-sourcing.create",
                                            )}
                                            className="block px-4 py-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 transition-all"
                                        >
                                            Create Requirement
                                        </Link>

                                        <Link
                                            href={route(
                                                "collective-sourcing.my-requests",
                                            )}
                                            className="block px-4 py-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 transition-all"
                                        >
                                            My Requests
                                        </Link>

                                        <div className="border-t border-slate-100 my-2"></div>
                                    </HoverDropdown>
                                </div>
                                <div className="relative flex items-center">
                                    <HoverDropdown
                                        icon={<span>🏭</span>}
                                        label="Directory"
                                    >
                                        <Link
                                            href={route("companies.index")}
                                            className="block px-4 py-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 transition-all"
                                        >
                                            Industry Directory
                                        </Link>

                                        <Link
                                            href={route("companies.index")}
                                            className="block px-4 py-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 transition-all"
                                        >
                                            Browse Companies
                                        </Link>
                                    </HoverDropdown>
                                </div>
                                <div className="relative flex items-center">
                                    <HoverDropdown
                                        icon={<span>🧮</span>}
                                        label="Tools"
                                    >
                                        <Link
                                            href={route("logistics.tracking")}
                                            className="block px-4 py-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 transition-all"
                                        >
                                            Port Tracking
                                        </Link>

                                        <Link
                                            href={route("tools.calculator")}
                                            className="block px-4 py-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 transition-all"
                                        >
                                            Industrial Toolbox
                                        </Link>
                                    </HoverDropdown>
                                </div>
                            </div>
                        </div>

                        {/* DROPDOWN USER EXECUTIVE (PC VIEW) */}
                        <div className="hidden xl:flex items-center gap-1">
                            <div className="flex items-center gap-2 bg-slate-100 rounded-full p-1 border border-slate-200 shadow-sm">
                                {[
                                    {
                                        code: "id",
                                        label: "Indonesia",
                                        flag: "/images/id.png",
                                    },
                                    {
                                        code: "en",
                                        label: "English",
                                        flag: "/images/en.png",
                                    },
                                ].map((lang, index) => (
                                    <div
                                        key={lang.code}
                                        className="flex items-center"
                                    >
                                        {index > 0 && (
                                            <div className="w-px h-5 bg-slate-300 mx-1" />
                                        )}

                                        <button
                                            onClick={() =>
                                                toggleLanguage(lang.code)
                                            }
                                            className={`flex items-center gap-2 px-3 py-1.5 rounded-full text-[10px] font-semibold transition-all duration-300 ${
                                                (
                                                    lang.code === "en"
                                                        ? isEn
                                                        : !isEn
                                                )
                                                    ? "bg-gradient-to-r from-amber-500 to-yellow-500 text-slate-900 shadow-md"
                                                    : "text-slate-600 hover:bg-slate-200"
                                            }`}
                                        >
                                            <img
                                                src={lang.flag}
                                                alt={lang.label}
                                                className="w-4 h-4 rounded-full object-cover"
                                            />

                                            <span>{lang.label}</span>
                                        </button>
                                    </div>
                                ))}
                            </div>
                            <div className="bg-slate-50 border border-slate-200 px-4 py-2 rounded-2xl flex items-center gap-4 shadow-sm">
                                <Dropdown>
                                    <Dropdown.Trigger>
                                        <button className="inline-flex items-center text-[11px] font-bold tracking-wide text-slate-700 hover:text-amber-500 focus:outline-none transition cursor-pointer">
                                            <i className="fas fa-user-shield text-amber-500 mr-2 text-xs"></i>

                                            <span className="max-w-[160px] truncate">
                                                {user
                                                    ? user.name
                                                    : "Executive Guest"}
                                            </span>

                                            <i className="fas fa-chevron-down text-[8px] text-slate-500 ms-2"></i>
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

                                        {/* SUNTIKAN SINKRONISASI MANAJEMEN NEWS INTELLIGENCE */}
                                        <Link
                                            href={route("admin.news.index")}
                                            className="flex items-center px-4 py-2.5 text-xs font-bold text-gray-300 hover:bg-white/5 hover:text-white rounded-xl transition-colors gap-2"
                                        >
                                            <Newspaper className="w-3.5 h-3.5 text-amber-500" />
                                            Manage Intelligence News
                                        </Link>

                                        <Link
                                            href="/admin/import-kemendag"
                                            className="flex items-center px-4 py-2.5 text-xs font-bold text-gray-300 hover:bg-white/5 hover:text-white rounded-xl transition-colors gap-2"
                                        >
                                            <svg
                                                className="w-3.5 h-3.5 text-gray-500"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    strokeLinecap="round"
                                                    strokeLinejoin="round"
                                                    strokeWidth="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"
                                                />
                                            </svg>
                                            Import Data Kemendag
                                        </Link>
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

                        {/* TOMBOL MENU MOBILE HAMBURGER */}
                        <div className="-me-2 flex items-center xl:hidden">
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

                {/* AREA PANEL NAVIGASI VERSI RESPONSIVE (MOBILE VIEW) */}
                <div
                    className={
                        (showingNavigationDropdown ? "block" : "hidden") +
                        " sm:hidden bg-white border-t border-slate-200 px-4 py-4 space-y-3 animate-fade-in"
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

                    {/* SINKRONISASI MOBILE: LINK CRUD BERITA INTELLIGENCE */}
                    {user?.role === "admin" && (
                        <ResponsiveNavLink
                            href={route("admin.news.index")}
                            active={route().current("admin.news.*")}
                            className="rounded-xl text-[10px] font-black uppercase tracking-widest block py-3 px-4 text-amber-600 bg-amber-50 border border-amber-200/50 flex items-center gap-2"
                        >
                            <Newspaper className="w-3.5 h-3.5" />
                            Manage Intelligence News
                        </ResponsiveNavLink>
                    )}

                    <ResponsiveNavLink
                        href={route("rfqs.index")}
                        active={route().current("rfqs.*")}
                        className="rounded-xl text-[10px] font-black uppercase tracking-widest block py-3 px-4 text-cyan-400 flex items-center gap-2"
                    >
                        <FileText className="w-3.5 h-3.5" />
                        RFQ Marketplace
                    </ResponsiveNavLink>

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

            {/* --- AREA HEADER DINAMIS SUB-HALAMAN --- */}
            {header && (
                <header className="bg-gradient-to-r from-[#0b1329] via-[#0f172a]/40 to-transparent border-b border-white/5 shadow-2xl relative overflow-hidden">
                    <div className="absolute top-0 left-0 w-64 h-64 bg-amber-500/5 rounded-full blur-3xl -ml-20 -mt-20"></div>
                    <div className="mx-auto max-w-7xl px-6 py-6 sm:px-8 lg:px-10 relative z-10">
                        <div className="border-l-4 border-amber-500 pl-4">
                            <h1 className="text-lg lg:text-xl font-black uppercase tracking-wider font-sans text-transparent bg-clip-text bg-gradient-to-r from-white via-gray-100 to-gray-400 drop-shadow-[0_2px_10px_rgba(255,255,255,0.1)]">
                                {header}
                            </h1>
                        </div>
                    </div>
                </header>
            )}

            {/* --- AREA MAIN STREAM VIEWPORT UTAMA --- */}
            <main className="relative z-10 animate-fade-in-up">{children}</main>

            {/* --- PROTEKSI FOOTER LEGAL --- */}
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
