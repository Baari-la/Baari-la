import { Link } from "@inertiajs/react";

export default function GuestLayout({ children }) {
    const isLightTheme = [
        "/register",
        "/forgot-password",
        "/verify-email",
    ].includes(window.location.pathname);

    return (
        <div
            className={`
                min-h-screen
                flex
                flex-col
                items-center
                justify-center
                px-6
                py-10
                relative
                overflow-hidden
                selection:bg-amber-500
                selection:text-black

                ${
                    isLightTheme
                        ? "bg-gradient-to-br from-slate-100 via-white to-slate-300"
                        : "bg-[#030712]"
                }
            `}
        >
            {/* ======================================================
                BACKGROUND EFFECTS
            ====================================================== */}

            {!isLightTheme && (
                <>
                    <div
                        className="
                            absolute
                            top-1/4
                            left-1/2
                            -translate-x-1/2
                            h-[500px]
                            w-[500px]
                            rounded-full
                            bg-amber-500/5
                            blur-[120px]
                            pointer-events-none
                        "
                    />

                    <div
                        className="
                            absolute
                            bottom-10
                            left-10
                            h-72
                            w-72
                            rounded-full
                            bg-blue-500/5
                            blur-[100px]
                            pointer-events-none
                        "
                    />
                </>
            )}

            {isLightTheme && (
                <>
                    <div
                        className="
                            absolute
                            top-0
                            left-0
                            h-[400px]
                            w-[400px]
                            rounded-full
                            bg-white/60
                            blur-[120px]
                        "
                    />

                    <div
                        className="
                            absolute
                            bottom-0
                            right-0
                            h-[300px]
                            w-[300px]
                            rounded-full
                            bg-slate-200
                            blur-[100px]
                        "
                    />
                </>
            )}

            {/* ======================================================
                LOGO
            ====================================================== */}

            <div className="relative z-10 mb-6 text-center">
                <Link href="/">
                    <img
                        src="/images/logoWeb.png"
                        alt="DIGESTEX"
                        className="
                            h-10
                            w-auto
                            rounded-xl
                            shadow-lg
                        "
                    />
                </Link>
            </div>

            {/* ======================================================
    AUTH CARD
====================================================== */}

            <div
                className="
        relative
        z-10
        w-full
        overflow-hidden
        px-8
        py-8
        sm:max-w-md
        sm:rounded-[35px]

        bg-[#0b1329]
        border
        border-white/10
        border-t-amber-500/30
        shadow-[0_20px_50px_rgba(0,0,0,0.5)]
        backdrop-blur-2xl
    "
            >
                <div
                    className="
            absolute
            inset-x-0
            top-0
            h-[1px]
            bg-gradient-to-r
            from-transparent
            via-amber-500/30
            to-transparent
        "
                />

                {children}
            </div>
        </div>
    );
}
