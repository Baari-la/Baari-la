import React, { useState, useEffect } from "react";

export default function PremiumCountdown({ isEn }) {
    const [timeLeft, setTimeLeft] = useState({
        days: 2,
        hours: 14,
        mins: 45,
        secs: 0,
    });

    useEffect(() => {
        const timer = setInterval(() => {
            // Simulasi hitung mundur sederhana
            setTimeLeft((prev) => ({
                ...prev,
                secs: prev.secs > 0 ? prev.secs - 1 : 59,
            }));
        }, 1000);
        return () => clearInterval(timer);
    }, []);

    return (
        <div className="max-w-7xl mx-auto px-6 mb-12">
            <div className="bg-gradient-to-r from-red-600/20 to-transparent border border-red-500/30 p-6 rounded-[35px] flex flex-col md:flex-row items-center justify-between gap-6 backdrop-blur-md">
                <div className="flex items-center gap-4">
                    <div className="h-12 w-12 bg-red-500 rounded-2xl flex items-center justify-center text-[#0a192f] shadow-lg animate-pulse">
                        <i className="fas fa-hourglass-start"></i>
                    </div>
                    <div>
                        <h4 className="text-white text-xs font-black uppercase italic tracking-widest leading-none mb-1">
                            {isEn
                                ? "Limited Exclusive Access"
                                : "Akses Eksklusif Terbatas"}
                        </h4>
                        <p className="text-red-400 text-[10px] font-bold uppercase italic tracking-tighter">
                            {isEn
                                ? "Upgrade to Premium to unlock 8-digit factory intelligence."
                                : "Upgrade ke Premium untuk membuka intelijen pabrik 8-digit."}
                        </p>
                    </div>
                </div>

                <div className="flex items-center gap-4">
                    <div className="flex gap-2">
                        {[
                            timeLeft.days,
                            timeLeft.hours,
                            timeLeft.mins,
                            timeLeft.secs,
                        ].map((val, i) => (
                            <div
                                key={i}
                                className="bg-[#0a192f] border border-white/10 px-3 py-2 rounded-xl text-center min-w-[45px]"
                            >
                                <span className="text-white text-sm font-black italic block leading-none">
                                    {val}
                                </span>
                                <span className="text-[6px] text-gray-500 uppercase font-black tracking-tighter">
                                    {["Days", "Hrs", "Min", "Sec"][i]}
                                </span>
                            </div>
                        ))}
                    </div>
                    <button className="bg-red-600 hover:bg-red-500 text-white px-6 py-3 rounded-2xl text-[9px] font-black uppercase tracking-widest transition-all">
                        <a
                            href="https://wa.me/628129928939"
                            target="_blank"
                            className="bg-red-600 hover:bg-white hover:text-red-600 text-white px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all shadow-[0_0_30px_rgba(220,38,38,0.4)] flex items-center gap-3 group"
                        >
                            <i className="fab fa-whatsapp text-lg group-hover:scale-125 transition-transform"></i>
                            {isEn ? "Upgrade Now" : "Upgrade Sekarang"}
                        </a>
                    </button>
                </div>
            </div>
        </div>
    );
}
