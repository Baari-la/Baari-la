import { Link } from "@inertiajs/react";

import { Building2, ArrowRight } from "lucide-react";

export default function DashboardHeader() {
    return (
        <div
            className="
                flex
                flex-col
                gap-6
                lg:flex-row
                lg:items-center
                lg:justify-between
            "
        >
            {/* Left */}

            <div>
                <div
                    className="
                        text-xs
                        font-black
                        uppercase
                        tracking-[0.35em]
                        text-emerald-600
                    "
                >
                    DIGESTEX ADMIN
                </div>

                <h1
                    className="
                        mt-2
                        text-5xl
                        font-black
                        leading-tight
                    "
                >
                    Admin{" "}
                    <span className="text-emerald-600">Command Center</span>
                </h1>

                <p
                    className="
                        mt-4
                        max-w-3xl
                        text-slate-500
                    "
                >
                    Manage Digital Directory, Companies, Payments, Build My
                    Supply Chain™, and DIGESTEX Intelligence services from a
                    single administration console.
                </p>
            </div>

            {/* Right */}

            <div className="flex flex-wrap gap-4">
                <Link
                    href={route("companies.create")}
                    className="
                        inline-flex
                        items-center
                        gap-2
                        rounded-2xl
                        bg-slate-900
                        px-6
                        py-4
                        font-bold
                        text-white
                        transition
                        hover:bg-slate-800
                    "
                >
                    <Building2 className="h-5 w-5" />
                    Add Company
                </Link>

                <Link
                    href={route("admin.digital-directory.index")}
                    className="
                        inline-flex
                        items-center
                        gap-2
                        rounded-2xl
                        bg-emerald-500
                        px-6
                        py-4
                        font-bold
                        text-white
                        transition
                        hover:bg-emerald-600
                    "
                >
                    Digital Directory
                    <ArrowRight className="h-5 w-5" />
                </Link>
            </div>
        </div>
    );
}
