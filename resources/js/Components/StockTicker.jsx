import React, { useState, useEffect } from "react";
import { Link, usePage, router } from '@inertiajs/react';

export default function StockTicker({ topStocks = [] }) {
    const { auth } = usePage().props;
    const [showModal, setShowModal] = useState(false);
    const [lastUpdate, setLastUpdate] = useState("Just now");
    
    // Gandakan data agar animasi marquee berjalan tanpa putus
    const tickerData = [...topStocks, ...topStocks, ...topStocks];

    useEffect(() => {
        const timer = setInterval(() => {
            setLastUpdate("Updated 1 min ago");
        }, 60000);
        return () => clearInterval(timer);
    }, []);

    const handleTickerClick = (e, companyId) => {
        if (!auth.user) {
            e.preventDefault(); // Mencegah Link berpindah halaman
            // Save the URL they wanted to visit
        const intendedUrl = route("companies.show", companyId);
        sessionStorage.setItem('intended_url', intendedUrl);
            setShowModal(true);
        }
        // Jika auth.user ada, Link akan otomatis bekerja sesuai href-nya
    };

    return (
        <>
            <div className="w-full bg-blue-600/10 border-y border-blue-500/20 py-3 overflow-hidden whitespace-nowrap relative backdrop-blur-md flex items-center">
                {/* LABEL DINAMIS: LAST UPDATE */}
                <div className="absolute left-0 z-20 bg-blue-600 text-white px-4 py-3 text-[8px] font-black uppercase tracking-widest shadow-xl flex items-center gap-2">
                    <span className="h-1.5 w-1.5 rounded-full bg-white animate-ping"></span>
                    {lastUpdate}
                </div>

                <div className="flex gap-12 animate-marquee inline-block pl-40">
                    {tickerData.map((stock, index) => (
                        <Link
                            key={index}
                            href={route("companies.show", stock.company_id)}
                            onClick={(e) => handleTickerClick(e, stock.company_id)}
                            className="flex items-center gap-4 group cursor-pointer hover:bg-white/5 px-4 py-1 rounded-xl transition-all"
                        >
                            <span className="text-blue-400 text-[10px] font-black uppercase tracking-widest italic flex items-center gap-2 group-hover:text-yellow-500">
                                <i className="fas fa-chart-line text-[8px]"></i>
                                {stock.product_name}
                            </span>
                            <span className="text-white text-xs font-black italic">
                                {stock.total_qty.toLocaleString()}
                            </span>
                            <span className="text-gray-500 text-[9px] font-bold uppercase tracking-tighter group-hover:text-white transition-colors">
                                {stock.unit} Available
                            </span>
                            <div className="h-4 w-px bg-white/10 mx-4"></div>
                        </Link>
                    ))}
                </div>

                <style dangerouslySetInnerHTML={{
                    __html: `
                        @keyframes marquee {
                            0% { transform: translateX(0); }
                            100% { transform: translateX(-50%); }
                        }
                        .animate-marquee {
                            display: flex;
                            animation: marquee 40s linear infinite;
                            min-width: 200%;
                        }
                    `,
                }} />
            </div>

            {/* Modal Pop-up Gated Content */}
            {showModal && (
                <div className="fixed inset-0 z-[999] flex items-center justify-center bg-black/60 backdrop-blur-sm">
                    <div className="bg-white p-8 rounded-2xl shadow-2xl max-w-sm text-center border border-gray-100 animate-in fade-in zoom-in duration-300">
                        <div className="text-5xl mb-4">📈</div>
                        <h3 className="text-xl font-bold mb-2 text-gray-900">Lihat Analisis Detail</h3>
                        <p className="text-gray-600 mb-6">
                            Daftar gratis sekarang untuk melihat data real-time dan analisis mendalam dari **Digestex**.
                        </p>
                        
                        <a href="/auth/google" className="flex items-center justify-center w-full px-4 py-3 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-all mb-3">
                            <img src="https://wikimedia.org" className="w-5 h-5 mr-2" alt="Google" />
                            <span className="font-semibold text-gray-700">Lanjut dengan Google</span>
                        </a>

                        <button 
                            onClick={() => setShowModal(false)}
                            className="text-sm text-gray-400 hover:text-gray-600 font-medium"
                        >
                            Nanti saja
                        </button>
                    </div>
                </div>
            )}
        </>
    );
}
