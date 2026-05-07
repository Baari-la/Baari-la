import ApplicationLogo from "@/Components/ApplicationLogo";
import Dropdown from "@/Components/Dropdown";
import NavLink from "@/Components/NavLink";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink";
import { Link, usePage } from "@inertiajs/react";
import { useState } from "react";

export default function AuthenticatedLayout({ header, children }) {
    const user = usePage().props.auth.user;
    const [showingNavigationDropdown, setShowingNavigationDropdown] =
        useState(false);

    return (
        <div className="min-h-screen bg-gray-100">
            <nav className="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between h-16">
                        <div className="flex">
                            {/* Logo */}
                            <div className="shrink-0 flex items-center">
                                <Link href="/">
                                    <ApplicationLogo className="block h-9 w-auto fill-current text-gray-800" />
                                </Link>
                            </div>

                            {/* MENU DESKTOP (Tampil di PC) */}
                            <div className="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink
                                    href={route("home")}
                                    active={route().current("dashboard")}
                                >
                                    Home
                                </NavLink>
                                <NavLink
                                    href={route("dashboard")}
                                    active={route().current("dashboard")}
                                >
                                    Dashboard
                                </NavLink>
                                <NavLink
                                    href={route("intelligence.center")}
                                    active={route().current(
                                        "intelligence.center",
                                    )}
                                >
                                    Intelligence Center
                                </NavLink>
                                <NavLink
                                    href={route("tools.calculator")}
                                    active={route().current("tools.calculator")}
                                    className="flex items-center gap-2"
                                >
                                    <span>🧮</span>
                                    <span>Industrial Toolbox</span>
                                    <span className="bg-blue-600 text-[8px] text-white px-1.5 py-0.5 rounded font-black uppercase tracking-tighter">
                                        Premium
                                    </span>
                                </NavLink>
                            </div>
                            {/* Bahasa */}
                        </div>

                        {/* Dropdown User (PC) */}
                        <div className="hidden sm:flex sm:items-center sm:ms-6">
                            <Dropdown>
                                <Dropdown.Trigger>
                                    <button className="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition underline decoration-blue-500/30">
                                        {user ? user.name : "Guest"}
                                    </button>
                                </Dropdown.Trigger>
                                <Dropdown.Content>
                                    <Dropdown.Link href={route("profile.edit")}>
                                        Profile
                                    </Dropdown.Link>
                                    <Dropdown.Link
                                        href={route("logout")}
                                        method="post"
                                        as="button"
                                    >
                                        Log Out
                                    </Dropdown.Link>
                                </Dropdown.Content>
                            </Dropdown>
                        </div>

                        {/* TOMBOL HAMBURGER (Tampil di HP) */}
                        <div className="-me-2 flex items-center sm:hidden">
                            <button
                                onClick={() =>
                                    setShowingNavigationDropdown(
                                        !showingNavigationDropdown,
                                    )
                                }
                                className="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition"
                            >
                                <svg
                                    className="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        className={
                                            !showingNavigationDropdown
                                                ? "inline-flex"
                                                : "hidden"
                                        }
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        strokeWidth="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        className={
                                            showingNavigationDropdown
                                                ? "inline-flex"
                                                : "hidden"
                                        }
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        strokeWidth="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {/* MENU MOBILE (Tampil saat di-klik di HP) */}
                <div
                    className={
                        (showingNavigationDropdown ? "block" : "hidden") +
                        " sm:hidden bg-white border-t border-gray-100"
                    }
                >
                    <div className="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink
                            href={route("home")}
                            active={route().current("home")}
                        >
                            Dashboard
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            href={route("intelligence.center")}
                            active={route().current("intelligence.center")}
                        >
                            Intelligence Center
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            href={route("tools.calculator")}
                            active={route().current("tools.calculator")}
                        >
                            🧮 Industrial Toolbox (Premium)
                        </ResponsiveNavLink>
                    </div>
                </div>
            </nav>

            {header && (
                <header className="bg-white shadow">
                    <div className="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                        {header}
                    </div>
                </header>
            )}

            <main className="animate-fade-in">{children}</main>
        </div>
    );
}
