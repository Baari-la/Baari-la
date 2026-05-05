import ApplicationLogo from "@/Components/ApplicationLogo";
import { Link } from "@inertiajs/react";

export default function GuestLayout({ children }) {
    return (
        /* Mengubah bg-gray-100 menjadi bg-[#0a192f] agar nyambung dengan tema Big Data */
        <div className="flex min-h-screen flex-col items-center bg-[#0a192f] pt-6 sm:justify-center sm:pt-0 px-4">
            <div className="mb-4">
                <Link href="/">
                    {/* Logo dibuat sedikit lebih besar */}
                    <ApplicationLogo className="h-24 w-24 fill-current text-yellow-500" />
                </Link>
            </div>

            {/* 
               PERUBAHAN UTAMA: 
               1. sm:max-w-md (448px) diubah ke sm:max-w-xl (576px) agar lebih luas.
               2. bg-white diubah ke bg-white/5 dengan backdrop-blur untuk kesan premium.
            */}
            <div className="mt-6 w-full overflow-hidden bg-white px-8 py-10 shadow-2xl sm:max-w-xl sm:rounded-3xl border border-white/10 backdrop-blur-md">
                {children}
            </div>

            {/* Footer tambahan agar tidak kosong */}
            <p className="mt-8 text-[10px] font-black text-gray-500 uppercase tracking-[0.5em]">
                Digestex Intelligence System v2.0
            </p>
        </div>
    );
}
