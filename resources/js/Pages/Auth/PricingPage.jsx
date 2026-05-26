import { Head, Link, usePage } from "@inertiajs/react";
import PricingSection from "@/Components/Home/PricingSection";
import Navbar from "@/Components/Navbar";

export default function PricingPage() {
    const { props } = usePage();
    const isEn = props.locale === "en";

    return (
        <div className="min-h-screen bg-[#0a192f] text-white">
            <Head title={isEn ? "Access Plans" : "Paket Akses"} />

            <Navbar />

            <div className="py-20 px-6">
                <div className="max-w-7xl mx-auto">
                    <div className="text-center mb-20">
                        <span className="text-yellow-500 text-[10px] font-black uppercase tracking-[0.4em] mb-4 block">
                            {isEn
                                ? "Industrial Intelligence Access"
                                : "Akses Intelijen Industri"}
                        </span>

                        <h1 className="text-5xl md:text-7xl font-black uppercase italic tracking-tighter leading-none mb-6">
                            Choose Your{" "}
                            <span className="text-yellow-500">Access Tier</span>
                        </h1>

                        <p className="text-slate-400 text-sm font-bold uppercase tracking-widest max-w-3xl mx-auto leading-relaxed">
                            {isEn
                                ? "Access advanced textile trade intelligence, manufacturing visibility, and premium digital tools designed for global industrial operations."
                                : "Akses intelijen perdagangan tekstil tingkat lanjut, visibilitas manufaktur, dan alat digital premium untuk operasional industri global."}
                        </p>
                    </div>

                    <div className="animate-in fade-in slide-in-from-bottom-10 duration-1000">
                        <PricingSection isEn={isEn} />
                    </div>

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
