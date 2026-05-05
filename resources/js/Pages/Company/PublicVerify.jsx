

import React from "react";
import { usePage, Head } from "@inertiajs/react";

// 1. KOMPONEN SEGEL INDEPENDEN (Internal Component agar tidak error)
const SealOfIndependence = () => {
    return (
        <div className="relative w-24 h-24 flex items-center justify-center group">
            {/* Background Glow */}
            <div className="absolute inset-0 bg-yellow-500/20 blur-xl rounded-full group-hover:bg-yellow-500/30 transition-all"></div>
            
            {/* The Seal Body */}
            <div className="relative w-20 h-20 border-2 border-yellow-500/50 rounded-full flex flex-col items-center justify-center bg-[#0a192f] shadow-2xl z-10">
                <svg className="w-8 h-8 text-yellow-500 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span className="text-[6px] font-black text-yellow-500 uppercase tracking-tighter text-center leading-none">
                    Independent<br/>Intelligence
                </span>
            </div>
            
            {/* Rotating Text Effect */}
            <div className="absolute inset-0 animate-[spin_10s_linear_infinite] opacity-40 pointer-events-none">
                <svg viewBox="0 0 100 100" className="w-full h-full">
                    <path id="circlePath" d="M 50, 50 m -37, 0 a 37,37 0 1,1 74,0 a 37,37 0 1,1 -74,0" fill="transparent" />
                    <text className="fill-yellow-500 text-[5px] font-bold uppercase tracking-[0.2em]">
                        <textPath xlinkHref="#circlePath">
                            • DIGESTEX GLOBAL • VERIFIED 8-DIGIT INTELLIGENCE • 
                        </textPath>
                    </text>
                </svg>
            </div>
        </div>
    );
};

// 2. KOMPONEN UTAMA HALAMAN VERIFIKASI
export default function PublicVerify({ company }) {
    // Ambil fungsi translasi dari Shared Props (HandleInertiaRequests)
    const { translations } = usePage().props;
    const t = (key) => (translations && translations[key]) ? translations[key] : key;

    return (
        <div className="min-h-screen bg-[#050c1b] flex items-center justify-center p-6 font-sans">
            <Head title={`Verify: ${company.nama_perusahaan}`} />
            
            <div className="max-w-2xl w-full bg-[#0a192f] rounded-[50px] border border-white/10 p-12 shadow-2xl relative overflow-hidden text-center">
                {/* Visual Effect: Background Glow */}
                <div className="absolute -top-24 -left-24 w-64 h-64 bg-emerald-500/10 blur-[100px] rounded-full"></div>
                <div className="absolute -bottom-24 -right-24 w-64 h-64 bg-blue-500/10 blur-[100px] rounded-full"></div>
                
                {/* TAMPILAN SEGEL */}
                <div className="flex justify-center mb-10">
                    <SealOfIndependence /> 
                </div>

                {/* STATUS BADGE */}
                <h4 className="text-emerald-400 text-[10px] font-black uppercase tracking-[0.4em] mb-4 drop-shadow-[0_0_10px_rgba(52,211,153,0.3)]">
                    {t('Verify_Status_Active')}
                </h4>
                
                {/* NAMA PERUSAHAAN */}
                <h1 className="text-white text-4xl font-black italic tracking-tighter uppercase mb-8 leading-none">
                    {company.nama_perusahaan}
                </h1>

                {/* DATA GRID */}
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-10 text-left">
                    <div className="p-5 bg-white/5 rounded-3xl border border-white/5 backdrop-blur-sm">
                        <p className="text-[8px] text-gray-500 uppercase font-black tracking-widest mb-1">
                            {t('Verify_Last_Update')}
                        </p>
                        <p className="text-white text-xs font-bold uppercase italic">
                            {company.last_verified_at || 'Pending Verification'}
                        </p>
                    </div>
                    <div className="p-5 bg-white/5 rounded-3xl border border-white/5 backdrop-blur-sm">
                        <p className="text-[8px] text-gray-500 uppercase font-black tracking-widest mb-1">
                            Industry Global ID
                        </p>
                        <p className="text-white text-xs font-bold uppercase tracking-tighter">
                            {company.nomor_anggota || `DGX-${company.id + 1000}`}
                        </p>
                    </div>
                </div>

                {/* NARASI INDEPENDENSI */}
                <div className="px-6 py-4 bg-yellow-500/5 border border-yellow-500/10 rounded-2xl mb-10">
                    <p className="text-gray-400 text-[11px] leading-relaxed italic">
                        "{t('Verify_Subtitle')}"
                    </p>
                </div>

                {/* FOOTER VERIFIKASI */}
                <div className="pt-8 border-t border-white/5 flex flex-col items-center gap-4">
                    <p className="text-[8px] text-gray-600 font-bold uppercase tracking-widest">
                        Official Verification Page by DigestexGlobal
                    </p>
                    <button className="text-[9px] text-gray-600 font-black uppercase tracking-widest hover:text-red-400 transition-colors">
                        {t('Verify_Report_Issue')}
                    </button>
                </div>
            </div>
        </div>
    );
}

