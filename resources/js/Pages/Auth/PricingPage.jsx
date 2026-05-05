import { Head, Link, usePage } from "@inertiajs/react";
import PricingSection from "@/Components/Home/PricingSection";
import Navbar from "@/Components/Navbar"; // Gunakan Navbar agar tetap bisa navigasi

export default function PricingPage() {
    const { props } = usePage();
    const isEn = props.locale === "en";

    return (
        <div className="min-h-screen bg-[#0a192f] text-white">
            <Head title={isEn ? "Membership Plans" : "Paket Keanggotaan"} />

            {/* 1. Pakai Navbar agar terlihat profesional */}
            <Navbar />

            <div className="py-20 px-6">
                <div className="max-w-7xl mx-auto">
                    {/* 2. Judul Besar & Mewah */}
                    <div className="text-center mb-20">
                        <span className="text-yellow-500 text-[10px] font-black uppercase tracking-[0.4em] mb-4 block">
                            {isEn
                                ? "Exclusive Intelligence"
                                : "Intelijen Eksklusif"}
                        </span>
                        <h1 className="text-5xl md:text-7xl font-black uppercase italic tracking-tighter leading-none mb-6">
                            Choose Your{" "}
                            <span className="text-yellow-500">Access Tier</span>
                        </h1>
                        <p className="text-slate-400 text-sm font-bold uppercase tracking-widest max-w-2xl mx-auto leading-relaxed">
                            {isEn
                                ? "Unlock the full power of textile industry big data and premium logistics tools."
                                : "Buka seluruh kekuatan big data industri tekstil dan alat logistik premium."}
                        </p>
                    </div>

                    {/* 3. Komponen Pricing Bapak (Sekarang punya ruang luas) */}
                    <div className="animate-in fade-in slide-in-from-bottom-10 duration-1000">
                        <PricingSection isEn={isEn} />
                    </div>

                    {/* 4. Tombol Kembali */}
                    <div className="mt-20 text-center">
                        <Link
                            href={route("login")}
                            className="inline-flex items-center gap-3 bg-white/5 border border-white/10 px-8 py-4 rounded-2xl hover:bg-white/10 transition-all group"
                        >
                            <span className="group-hover:-translate-x-2 transition-transform text-yellow-500 font-bold">
                                ←
                            </span>
                            <span className="text-[10px] font-black uppercase tracking-widest">
                                {isEn
                                    ? "Back to Login Console"
                                    : "Kembali ke Konsol Login"}
                            </span>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    );
}
