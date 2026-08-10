import { Link, router, usePage } from "@inertiajs/react";

import {
    Building2,
    ChevronDown,
    CircleUserRound,
    Eye,
    Globe2,
    Handshake,
    HelpCircle,
    Home,
    LayoutDashboard,
    LogOut,
    Menu,
    ShieldCheck,
    X,
} from "lucide-react";

import { useEffect, useRef, useState } from "react";

export default function ProgramPortalNavbar({
    company = null,
    participant = null,
    programStatus = null,
}) {
    const { auth, locale } = usePage().props;

    /*
|--------------------------------------------------------------------------
| Language Switcher
|--------------------------------------------------------------------------
*/

    const switchLanguage = (newLocale) => {
        if (newLocale === locale) {
            return;
        }

        router.post(
            route("language.switch", {
                locale: newLocale,
            }),
            {},
            {
                preserveScroll: true,
                preserveState: false,
            },
        );
    };

    const user = auth?.user;

    const isEn = locale === "en";

    const [mobileOpen, setMobileOpen] = useState(false);

    const [accountOpen, setAccountOpen] = useState(false);

    const accountRef = useRef(null);

    /*
    |--------------------------------------------------------------------------
    | Program State
    |--------------------------------------------------------------------------
    */

    const ownershipStatus = programStatus?.ownership_status ?? "not_started";

    const companyConnected = programStatus?.company_connected ?? false;

    const onboardingCompleted = programStatus?.onboarding_completed ?? false;

    /*
    |--------------------------------------------------------------------------
    | Access Rules
    |--------------------------------------------------------------------------
    |
    | Company features remain locked until ownership has been approved
    | and the authenticated user is connected to the company.
    |
    */

    const ownershipApproved = ownershipStatus === "approved";

    const canManageCompany = ownershipApproved && companyConnected;

    const canUseProgramFeatures = canManageCompany && onboardingCompleted;

    /*
    |--------------------------------------------------------------------------
    | Package
    |--------------------------------------------------------------------------
    */

    const packageName = participant?.package ?? null;

    /*
    |--------------------------------------------------------------------------
    | Company Name
    |--------------------------------------------------------------------------
    */

    const companyName = company?.nama_perusahaan ?? null;

    /*
    |--------------------------------------------------------------------------
    | Navigation
    |--------------------------------------------------------------------------
    */

    const navigation = [
        {
            key: "home",

            label: isEn ? "Program Home" : "Beranda Program",

            icon: Home,

            routeName: "program.digital-directory.portal",

            enabled: true,
        },
        {
            key: "program",

            label: isEn ? "Strategic Program" : "Program Strategis",

            icon: Globe2,

            routeName: "program.digital-directory-visibility",

            enabled: true,
        },

        {
            key: "company",

            label: isEn ? "Company Profile" : "Profil Perusahaan",

            icon: Building2,

            routeName: canManageCompany
                ? "onboarding.company-information"
                : null,

            enabled: canManageCompany,
        },

        {
            key: "visibility",

            label: isEn ? "Visibility" : "Visibilitas",

            icon: Eye,

            routeName: null,

            enabled: canUseProgramFeatures,
        },

        {
            key: "opportunities",

            label: isEn ? "Opportunities" : "Peluang",

            icon: Handshake,

            routeName: null,

            enabled: canUseProgramFeatures,
        },

        {
            key: "support",

            label: isEn ? "Support" : "Bantuan",

            icon: HelpCircle,

            routeName: null,

            enabled: true,
        },
    ];

    /*
    |--------------------------------------------------------------------------
    | Close Account Menu
    |--------------------------------------------------------------------------
    */

    useEffect(() => {
        const handleClickOutside = (event) => {
            if (
                accountRef.current &&
                !accountRef.current.contains(event.target)
            ) {
                setAccountOpen(false);
            }
        };

        document.addEventListener("mousedown", handleClickOutside);

        return () => {
            document.removeEventListener("mousedown", handleClickOutside);
        };
    }, []);

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    const logout = () => {
        router.post(route("logout"));
    };

    /*
    |--------------------------------------------------------------------------
    | Status Badge
    |--------------------------------------------------------------------------
    */

    const renderStatusBadge = () => {
        if (ownershipStatus === "pending") {
            return (
                <div
                    className="
                        inline-flex
                        items-center
                        gap-2
                        rounded-full
                        bg-amber-50
                        px-3
                        py-1.5
                        text-xs
                        font-black
                        uppercase
                        tracking-wide
                        text-amber-700
                    "
                >
                    <span
                        className="
                            h-2
                            w-2
                            rounded-full
                            bg-amber-500
                        "
                    />

                    {isEn ? "Verification Pending" : "Menunggu Verifikasi"}
                </div>
            );
        }

        if (ownershipStatus === "approved" && companyConnected) {
            return (
                <div
                    className="
                        inline-flex
                        items-center
                        gap-2
                        rounded-full
                        bg-emerald-50
                        px-3
                        py-1.5
                        text-xs
                        font-black
                        uppercase
                        tracking-wide
                        text-emerald-700
                    "
                >
                    <ShieldCheck className="h-4 w-4" />

                    {isEn ? "Verified Company" : "Perusahaan Terverifikasi"}
                </div>
            );
        }

        if (ownershipStatus === "rejected") {
            return (
                <div
                    className="
                        inline-flex
                        items-center
                        gap-2
                        rounded-full
                        bg-rose-50
                        px-3
                        py-1.5
                        text-xs
                        font-black
                        uppercase
                        tracking-wide
                        text-rose-700
                    "
                >
                    <span
                        className="
                            h-2
                            w-2
                            rounded-full
                            bg-rose-500
                        "
                    />

                    {isEn ? "Action Required" : "Perlu Tindakan"}
                </div>
            );
        }

        return (
            <div
                className="
                    inline-flex
                    items-center
                    gap-2
                    rounded-full
                    bg-slate-100
                    px-3
                    py-1.5
                    text-xs
                    font-black
                    uppercase
                    tracking-wide
                    text-slate-600
                "
            >
                <span
                    className="
                        h-2
                        w-2
                        rounded-full
                        bg-slate-400
                    "
                />

                {isEn ? "Setup Required" : "Perlu Pengaturan"}
            </div>
        );
    };

    /*
    |--------------------------------------------------------------------------
    | Navigation Item
    |--------------------------------------------------------------------------
    */

    const NavItem = ({ item, mobile = false }) => {
        const Icon = item.icon;

        const active = item.routeName && route().current(item.routeName);

        /*
        |--------------------------------------------------------------------------
        | Locked
        |--------------------------------------------------------------------------
        */

        if (!item.enabled) {
            return (
                <div
                    className={`
                        flex
                        cursor-not-allowed
                        items-center
                        gap-2
                        rounded-xl
                        font-bold
                        text-slate-400

                        ${mobile ? "px-4 py-3" : "px-3 py-2 text-sm"}
                    `}
                    title={
                        isEn
                            ? "Complete company verification to unlock this feature."
                            : "Selesaikan verifikasi perusahaan untuk membuka fitur ini."
                    }
                >
                    <Icon className="h-4 w-4" />

                    <span>{item.label}</span>

                    <span
                        className="
                            ml-auto
                            rounded-full
                            bg-slate-100
                            px-2
                            py-0.5
                            text-[10px]
                            font-black
                            uppercase
                        "
                    >
                        {isEn ? "Locked" : "Terkunci"}
                    </span>
                </div>
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Placeholder
        |--------------------------------------------------------------------------
        |
        | Support / Visibility / Opportunities can remain visible before
        | their dedicated routes are created.
        |
        */

        if (!item.routeName) {
            return (
                <div
                    className={`
                        flex
                        items-center
                        gap-2
                        rounded-xl
                        font-bold
                        text-slate-500

                        ${mobile ? "px-4 py-3" : "px-3 py-2 text-sm"}
                    `}
                >
                    <Icon className="h-4 w-4" />

                    <span>{item.label}</span>

                    <span
                        className="
                            ml-auto
                            rounded-full
                            bg-slate-100
                            px-2
                            py-0.5
                            text-[10px]
                            font-black
                            uppercase
                            text-slate-500
                        "
                    >
                        {isEn ? "Soon" : "Segera"}
                    </span>
                </div>
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Active Link
        |--------------------------------------------------------------------------
        */

        return (
            <Link
                href={route(item.routeName)}
                onClick={() => setMobileOpen(false)}
                className={`
                    flex
                    items-center
                    gap-2
                    rounded-xl
                    font-bold
                    transition

                    ${mobile ? "px-4 py-3" : "px-3 py-2 text-sm"}

                    ${
                        active
                            ? "bg-emerald-50 text-emerald-700"
                            : "text-slate-600 hover:bg-slate-100 hover:text-slate-900"
                    }
                `}
            >
                <Icon className="h-4 w-4" />

                {item.label}
            </Link>
        );
    };

    return (
        <header
            className="
                sticky
                top-0
                z-50
                border-b
                border-slate-200
                bg-white/95
                backdrop-blur
            "
        >
            {/* Main Navbar */}

            <div
                className="
                    mx-auto
                    flex
                    max-w-[1600px]
                    items-center
                    gap-6
                    px-6
                    py-4
                "
            >
                {/* Brand */}

                <Link
                    href={route("program.digital-directory.portal")}
                    className="
                        flex
                        shrink-0
                        items-center
                        gap-4
                    "
                >
                    <div
                        className="
                            flex
                            h-11
                            w-11
                            items-center
                            justify-center
                            rounded-2xl
                            bg-slate-900
                            text-lg
                            font-black
                            text-white
                        "
                    >
                        D
                    </div>

                    <div>
                        <div
                            className="
                                text-xl
                                font-black
                                tracking-tight
                                text-slate-900
                            "
                        >
                            DIGESTEX
                        </div>

                        <div
                            className="
                                hidden
                                text-[10px]
                                font-black
                                uppercase
                                tracking-[0.12em]
                                text-slate-400
                                xl:block
                            "
                        >
                            {isEn
                                ? "Digital Directory & Visibility Program"
                                : "Program Digital Directory & Visibility"}
                        </div>
                    </div>
                </Link>

                {/* Desktop Navigation */}

                <nav
                    className="
                        hidden
                        flex-1
                        items-center
                        justify-center
                        gap-1
                        lg:flex
                    "
                >
                    {navigation.map((item) => (
                        <NavItem key={item.key} item={item} />
                    ))}
                </nav>

                {/* Desktop Account */}

                <div
                    className="
        ml-auto
        hidden
        items-center
        gap-3
        lg:flex
    "
                >
                    {/* Language Switcher */}

                    <div
                        className="
            flex
            items-center
            rounded-2xl
            border
            border-slate-200
            bg-slate-50
            p-1
        "
                    >
                        <div
                            className="
                flex
                items-center
                px-2
                text-slate-400
            "
                        >
                            <Globe2 className="h-4 w-4" />
                        </div>

                        <button
                            type="button"
                            onClick={() => switchLanguage("en")}
                            className={`
                rounded-xl
                px-3
                py-2
                text-xs
                font-black
                transition

                ${
                    isEn
                        ? "bg-white text-emerald-700 shadow-sm"
                        : "text-slate-500 hover:text-slate-900"
                }
            `}
                        >
                            EN
                        </button>

                        <button
                            type="button"
                            onClick={() => switchLanguage("id")}
                            className={`
                rounded-xl
                px-3
                py-2
                text-xs
                font-black
                transition

                ${
                    !isEn
                        ? "bg-white text-emerald-700 shadow-sm"
                        : "text-slate-500 hover:text-slate-900"
                }
            `}
                        >
                            ID
                        </button>
                    </div>

                    {/* Status */}

                    {renderStatusBadge()}

                    {/* Account */}

                    <div ref={accountRef} className="relative">
                        <button
                            type="button"
                            onClick={() =>
                                setAccountOpen((current) => !current)
                            }
                            className="
                                flex
                                items-center
                                gap-3
                                rounded-2xl
                                border
                                border-slate-200
                                bg-white
                                px-4
                                py-2.5
                                transition
                                hover:bg-slate-50
                            "
                        >
                            <div
                                className="
                                    flex
                                    h-9
                                    w-9
                                    items-center
                                    justify-center
                                    rounded-full
                                    bg-slate-100
                                "
                            >
                                <CircleUserRound className="h-5 w-5 text-slate-600" />
                            </div>

                            <div className="max-w-44 text-left">
                                <div
                                    className="
                                        truncate
                                        text-sm
                                        font-black
                                        text-slate-900
                                    "
                                >
                                    {user?.name ??
                                        (isEn
                                            ? "DIGESTEX User"
                                            : "Pengguna DIGESTEX")}
                                </div>

                                <div
                                    className="
                                        truncate
                                        text-xs
                                        text-slate-500
                                    "
                                >
                                    {companyName ??
                                        packageName ??
                                        (isEn
                                            ? "Program Member"
                                            : "Peserta Program")}
                                </div>
                            </div>

                            <ChevronDown
                                className={`
                                    h-4
                                    w-4
                                    text-slate-400
                                    transition

                                    ${accountOpen ? "rotate-180" : ""}
                                `}
                            />
                        </button>

                        {/* Account Dropdown */}

                        {accountOpen && (
                            <div
                                className="
                                    absolute
                                    right-0
                                    mt-3
                                    w-80
                                    overflow-hidden
                                    rounded-3xl
                                    border
                                    border-slate-200
                                    bg-white
                                    shadow-xl
                                "
                            >
                                {/* User */}

                                <div className="border-b border-slate-100 p-5">
                                    <div className="text-sm font-black text-slate-900">
                                        {user?.name}
                                    </div>

                                    <div className="mt-1 text-sm text-slate-500">
                                        {user?.email}
                                    </div>

                                    {packageName && (
                                        <div
                                            className="
                                                mt-4
                                                inline-flex
                                                rounded-full
                                                bg-slate-900
                                                px-3
                                                py-1.5
                                                text-xs
                                                font-black
                                                text-white
                                            "
                                        >
                                            {packageName}
                                        </div>
                                    )}
                                </div>

                                {/* Company */}

                                {companyName && (
                                    <div className="border-b border-slate-100 p-5">
                                        <div
                                            className="
                                                text-[10px]
                                                font-black
                                                uppercase
                                                tracking-widest
                                                text-slate-400
                                            "
                                        >
                                            {isEn ? "Company" : "Perusahaan"}
                                        </div>

                                        <div className="mt-2 font-black text-slate-800">
                                            {companyName}
                                        </div>
                                    </div>
                                )}

                                {/* Dashboard */}

                                <div className="p-2">
                                    <Link
                                        href={route("dashboard")}
                                        className="
                                            flex
                                            items-center
                                            gap-3
                                            rounded-2xl
                                            px-4
                                            py-3
                                            text-sm
                                            font-bold
                                            text-slate-700
                                            transition
                                            hover:bg-slate-100
                                        "
                                    >
                                        <LayoutDashboard className="h-5 w-5" />

                                        {isEn
                                            ? "DIGESTEX Dashboard"
                                            : "Dashboard DIGESTEX"}
                                    </Link>

                                    <button
                                        type="button"
                                        onClick={logout}
                                        className="
                                            flex
                                            w-full
                                            items-center
                                            gap-3
                                            rounded-2xl
                                            px-4
                                            py-3
                                            text-left
                                            text-sm
                                            font-bold
                                            text-rose-600
                                            transition
                                            hover:bg-rose-50
                                        "
                                    >
                                        <LogOut className="h-5 w-5" />

                                        {isEn ? "Sign Out" : "Keluar"}
                                    </button>
                                </div>
                            </div>
                        )}
                    </div>
                </div>

                {/* Mobile Button */}

                <button
                    type="button"
                    onClick={() => setMobileOpen((current) => !current)}
                    className="
                        ml-auto
                        flex
                        h-11
                        w-11
                        items-center
                        justify-center
                        rounded-xl
                        border
                        border-slate-200
                        text-slate-700
                        lg:hidden
                    "
                >
                    {mobileOpen ? (
                        <X className="h-6 w-6" />
                    ) : (
                        <Menu className="h-6 w-6" />
                    )}
                </button>
            </div>

            {/* Program Context Bar */}

            <div
                className="
                    border-t
                    border-slate-100
                    bg-slate-50
                "
            >
                <div
                    className="
                        mx-auto
                        flex
                        max-w-[1600px]
                        items-center
                        justify-between
                        gap-4
                        px-6
                        py-2
                    "
                >
                    <div
                        className="
                            truncate
                            text-xs
                            font-bold
                            text-slate-500
                        "
                    >
                        {companyName ? (
                            <>
                                <span className="text-slate-900">
                                    {companyName}
                                </span>

                                {packageName && (
                                    <>
                                        {" "}
                                        <span className="mx-2 text-slate-300">
                                            •
                                        </span>
                                        {packageName}
                                    </>
                                )}
                            </>
                        ) : (
                            <>
                                {isEn
                                    ? "DIGESTEX Digital Directory & Visibility Program"
                                    : "Program Digital Directory & Visibility DIGESTEX"}

                                {packageName && (
                                    <>
                                        {" "}
                                        <span className="mx-2 text-slate-300">
                                            •
                                        </span>
                                        {packageName}
                                    </>
                                )}
                            </>
                        )}
                    </div>

                    <div className="shrink-0 lg:hidden">
                        {renderStatusBadge()}
                    </div>
                </div>
            </div>

            {/* Mobile Navigation */}

            {mobileOpen && (
                <div
                    className="
                        border-t
                        border-slate-200
                        bg-white
                        px-6
                        py-6
                        lg:hidden
                    "
                >
                    {/* User */}

                    <div
                        className="
                            mb-6
                            rounded-3xl
                            bg-slate-50
                            p-5
                        "
                    >
                        <div className="flex items-center gap-3">
                            <div
                                className="
                                    flex
                                    h-11
                                    w-11
                                    items-center
                                    justify-center
                                    rounded-full
                                    bg-white
                                "
                            >
                                <CircleUserRound className="h-6 w-6 text-slate-600" />
                            </div>

                            <div className="min-w-0">
                                <div
                                    className="
                                        truncate
                                        font-black
                                        text-slate-900
                                    "
                                >
                                    {user?.name ??
                                        (isEn
                                            ? "DIGESTEX User"
                                            : "Pengguna DIGESTEX")}
                                </div>

                                <div
                                    className="
                                        truncate
                                        text-sm
                                        text-slate-500
                                    "
                                >
                                    {user?.email}
                                </div>
                            </div>
                        </div>

                        {packageName && (
                            <div
                                className="
                                    mt-4
                                    inline-flex
                                    rounded-full
                                    bg-slate-900
                                    px-3
                                    py-1.5
                                    text-xs
                                    font-black
                                    text-white
                                "
                            >
                                {packageName}
                            </div>
                        )}
                    </div>
                    {/* Language */}

                    <div
                        className="
        mb-6
        rounded-2xl
        border
        border-slate-200
        bg-white
        p-2
    "
                    >
                        <div
                            className="
            flex
            items-center
            gap-2
            px-3
            pb-2
            pt-1
            text-xs
            font-black
            uppercase
            tracking-wider
            text-slate-400
        "
                        >
                            <Globe2 className="h-4 w-4" />

                            {isEn ? "Language" : "Bahasa"}
                        </div>

                        <div className="grid grid-cols-2 gap-2">
                            <button
                                type="button"
                                onClick={() => switchLanguage("en")}
                                className={`
                rounded-xl
                px-4
                py-3
                text-sm
                font-black
                transition

                ${
                    isEn
                        ? "bg-slate-900 text-white"
                        : "bg-slate-50 text-slate-600 hover:bg-slate-100"
                }
            `}
                            >
                                English
                            </button>

                            <button
                                type="button"
                                onClick={() => switchLanguage("id")}
                                className={`
                rounded-xl
                px-4
                py-3
                text-sm
                font-black
                transition

                ${
                    !isEn
                        ? "bg-slate-900 text-white"
                        : "bg-slate-50 text-slate-600 hover:bg-slate-100"
                }
            `}
                            >
                                Indonesia
                            </button>
                        </div>
                    </div>

                    {/* Navigation */}
                    <nav className="space-y-1">
                        {navigation.map((item) => (
                            <NavItem key={item.key} item={item} mobile />
                        ))}
                    </nav>

                    {/* Mobile Actions */}

                    <div
                        className="
                            mt-6
                            border-t
                            border-slate-200
                            pt-6
                        "
                    >
                        <Link
                            href={route("dashboard")}
                            className="
                                flex
                                items-center
                                gap-3
                                rounded-2xl
                                px-4
                                py-3
                                font-bold
                                text-slate-700
                                hover:bg-slate-100
                            "
                        >
                            <LayoutDashboard className="h-5 w-5" />

                            {isEn ? "DIGESTEX Dashboard" : "Dashboard DIGESTEX"}
                        </Link>

                        <button
                            type="button"
                            onClick={logout}
                            className="
                                mt-1
                                flex
                                w-full
                                items-center
                                gap-3
                                rounded-2xl
                                px-4
                                py-3
                                text-left
                                font-bold
                                text-rose-600
                                hover:bg-rose-50
                            "
                        >
                            <LogOut className="h-5 w-5" />

                            {isEn ? "Sign Out" : "Keluar"}
                        </button>
                    </div>
                </div>
            )}
        </header>
    );
}
