import React from "react";
import { ShieldAlert, ArrowRight } from "lucide-react";

export default function DomesticEwsWidget({ alertsData = [] }) {
    return (
        <div className="bg-[#0b1329] p-6 lg:p-8 rounded-[35px] border border-red-500/20 shadow-2xl text-gray-100 font-mono relative overflow-hidden">
            <div className="absolute top-0 left-0 w-64 h-64 bg-red-500/5 rounded-full blur-3xl -ml-20 -mt-20 animate-pulse"></div>

            <div className="flex justify-between items-center mb-6 border-b border-white/5 pb-4 relative z-10">
                <div className="flex items-center gap-3">
                    <div className="w-9 h-9 rounded-xl bg-red-500/20 flex items-center justify-center border border-red-500/30 animate-pulse">
                        <ShieldAlert className="w-5 h-5 text-red-500" />
                    </div>
                    <div>
                        <h4 className="text-xs font-black uppercase tracking-widest text-white flex items-center gap-2">
                            Domestic Market Early Warning System (EWS)
                            <span className="bg-red-600 text-[6px] text-white px-1.5 py-0.5 rounded font-black tracking-widest uppercase animate-pulse">
                                LIVE PRODUCTION SHOCK RADAR
                            </span>
                        </h4>
                        <p className="text-[8px] text-gray-500 font-mono mt-0.5 uppercase tracking-wider">
                            Real-Time Import Inflow Shock Analytics - PT.
                            DIGESTEX MEDIA UTAMA
                        </p>
                    </div>
                </div>
            </div>

            <div className="space-y-4 relative z-10">
                {alertsData && alertsData.length > 0 ? (
                    alertsData.map((item, index) => {
                        const isCritical = item.risk === "CRITICAL";
                        return (
                            <div
                                key={index}
                                className={`p-5 rounded-2xl border transition-all ${isCritical ? "bg-red-950/20 border-red-500/30" : "bg-amber-950/20 border-amber-500/20"}`}
                            >
                                <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 border-b border-white/5 pb-3 mb-3">
                                    <div>
                                        <span className="text-[8px] text-gray-400 font-bold uppercase block">
                                            Komoditas Tekstil / Kode HS
                                        </span>
                                        <h5 className="text-xs font-black text-white mt-0.5">
                                            <span className="text-blue-400 font-black mr-1">
                                                [{item.hs}]
                                            </span>{" "}
                                            {item.commodity}
                                        </h5>
                                    </div>
                                    <div className="flex items-center gap-3">
                                        <div className="text-right">
                                            <span className="text-[7px] text-gray-500 uppercase block">
                                                Inflow Volume
                                            </span>
                                            <span
                                                className={`text-xs font-black ${isCritical ? "text-red-400" : "text-amber-400"}`}
                                            >
                                                {item.containers} /{" "}
                                                {item.threshold}{" "}
                                                <span className="text-[8px] text-gray-400 font-normal">
                                                    Cont.
                                                </span>
                                            </span>
                                        </div>
                                        <span
                                            className={`px-2 py-0.5 rounded text-[8px] font-black ${isCritical ? "bg-red-600 text-white animate-pulse" : "bg-amber-500 text-gray-900"}`}
                                        >
                                            {item.risk}
                                        </span>
                                    </div>
                                </div>

                                <div className="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                    <div className="md:col-span-2 text-[10px] text-gray-300 leading-relaxed">
                                        <span className="text-[8px] text-red-400 font-black uppercase block mb-0.5">
                                            AI IMPACT ANALYTICS REPORT:
                                        </span>
                                        {item.impact}
                                    </div>
                                    <div className="bg-black/40 p-3 rounded-xl border border-white/5 text-center flex flex-col justify-center">
                                        <span className="text-[7px] text-purple-400 font-black uppercase tracking-widest block">
                                            Est. Market Inundation
                                        </span>
                                        <span className="text-white font-black text-lg mt-0.5 animate-pulse">
                                            ⏱️ {item.days} Hari Lagi
                                        </span>
                                        <span className="text-[7px] text-gray-500 uppercase mt-0.5">
                                            Membanjiri Pasar Grosir
                                        </span>
                                    </div>
                                </div>
                            </div>
                        );
                    })
                ) : (
                    <div className="text-center py-4 text-gray-500 text-xs italic">
                        No EWS telemetry metrics calculated yet.
                    </div>
                )}
            </div>

            <div className="text-[7px] text-gray-500 italic text-right uppercase tracking-wider mt-4">
                *Overproduction defense algorithms compiled live against raw
                port database rows.
            </div>
        </div>
    );
}
