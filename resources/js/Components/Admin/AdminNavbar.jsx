import { Link, usePage } from "@inertiajs/react";

import { Bell, Search, Globe, ChevronDown, UserCircle2 } from "lucide-react";

export default function AdminNavbar() {
    const { auth } = usePage().props;

    return (
        <header
            className="
                sticky
                top-0
                z-20
                border-b
                bg-white
                px-8
                py-5
                shadow-sm
            "
        >
            <div className="flex items-center justify-between gap-6">
                {/* Left */}

                <div>
                    <h1 className="text-2xl font-black">DIGESTEX Admin</h1>

                    <p className="text-sm text-slate-500">
                        Global Textile Intelligence Console
                    </p>
                </div>

                {/* Center */}

                <div className="hidden flex-1 lg:block">
                    <div
                        className="
                            relative
                            mx-auto
                            max-w-xl
                        "
                    >
                        <Search
                            className="
                                absolute
                                left-4
                                top-3.5
                                h-5
                                w-5
                                text-slate-400
                            "
                        />

                        <input
                            type="text"
                            placeholder="
                                Search companies, participants, users...
                            "
                            className="
                                w-full
                                rounded-2xl
                                border
                                py-3
                                pl-12
                                pr-4
                                outline-none
                            "
                        />
                    </div>
                </div>

                {/* Right */}

                <div className="flex items-center gap-4">
                    {/* Language */}

                    <button
                        className="
                            hidden
                            items-center
                            gap-2
                            rounded-xl
                            border
                            px-4
                            py-2
                            lg:flex
                        "
                    >
                        <Globe className="h-4 w-4" />
                        EN
                    </button>

                    {/* Notifications */}

                    <button
                        className="
                            relative
                            rounded-xl
                            border
                            p-3
                        "
                    >
                        <Bell className="h-5 w-5" />

                        <span
                            className="
                                absolute
                                -right-1
                                -top-1
                                flex
                                h-5
                                w-5
                                items-center
                                justify-center
                                rounded-full
                                bg-red-500
                                text-xs
                                font-bold
                                text-white
                            "
                        >
                            4
                        </span>
                    </button>

                    {/* User */}

                    <div
                        className="
                            flex
                            items-center
                            gap-3
                            rounded-2xl
                            border
                            px-4
                            py-2
                        "
                    >
                        <UserCircle2 className="h-9 w-9 text-slate-500" />

                        <div className="hidden lg:block">
                            <div className="font-bold">
                                {auth?.user?.name ?? "Administrator"}
                            </div>

                            <div className="text-xs text-slate-500">
                                DIGESTEX Admin
                            </div>
                        </div>

                        <ChevronDown className="h-4 w-4 text-slate-400" />
                    </div>
                </div>
            </div>

            {/* Quick Stats */}

            <div className="mt-5 flex flex-wrap gap-3">
                <Badge label="Pending Payments" value="4" />

                <Badge label="Participants" value="25" />

                <Badge label="Revenue" value="Rp125M" />

                <Badge label="Active Companies" value="18" />
            </div>
        </header>
    );
}

function Badge({ label, value }) {
    return (
        <div
            className="
                rounded-full
                bg-slate-100
                px-4
                py-2
                text-sm
            "
        >
            <span className="font-semibold">{label}:</span>{" "}
            <span className="font-bold">{value}</span>
        </div>
    );
}
