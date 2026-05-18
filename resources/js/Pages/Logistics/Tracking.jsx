import React, { useState } from "react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import { Container, Ship, MapPin, Search, ShieldCheck, AlertCircle, Calendar, Anchor, ArrowRight, Clock } from "lucide-react";

export default function Tracking({ locale }) {
    const isEn = locale === "en";
    const [searchId, setSearchId] = useState("");
    const [activeData, setActiveData] = useState(null);
    const [hasSearched, setHasSearched] = useState(false);

    // 📊 1. BANK DATA SIMULASI INTERAKTIF (TRACKING KONTANER)
    const mockDatabase = {
        "COATS58700": {
            container_no: "COATS58700 (COATS REJO INDONESIA)",
            terminal: "JICT - Terminal 1 (Jakarta International Container Terminal)",
            vessel: "ONE OLYMPUS V.024N",
            shipping_line: "ONE (Ocean Network Express) - Japan Direct Route",
            status: "GATE-IN SUCCESS (Stacking Yard)",
            status_en: "GATE-IN SUCCESS (Stacking Yard)",
            status_color: "bg-emerald-500/10 border-emerald-500/20 text-emerald-400",
            gate_in_time: "18 Mei 2026 - 08:30 WIB",
            pod: "Tokyo, Japan (JPTYO) - Central Apparel Market",
            block_position: "CY Block 4A - Slot 12 - Tier 3 (Ready for Loading)"
        },
        "LABDA70021": {
            container_no: "LABDA70021 (LABDA ANUGERAH TEKSTIL)",
            terminal: "NPCT1 - New Priok Container Terminal 1",
            vessel: "MAERSK MC-KINNEY MOLLER V.2604",
            shipping_line: "MAERSK LINE - Europe Eco-Trade Corridor",
            status: "CUSTOMS RELEASED (SPPB Approved)",
            status_en: "CUSTOMS RELEASED (SPPB Approved)",
            status_color: "bg-blue-500/10 border-blue-500/20 text-blue-400",
            gate_in_time: "17 Mei 2026 - 14:15 WIB",
            pod: "Rotterdam, Netherlands (NLRTM) - Sustainable Textile Hub",
            block_position: "CY Block B2 - Slot 08 - Tier 2 (Customs Clear)"
        },
        "KREASI1504": {
            container_no: "KREASI1504 (KREASI INDAH BUSANA)",
            terminal: "JICT - Terminal 2",
            vessel: "CMA CGM CORAL V.1264",
            shipping_line: "CMA CGM - Medical Cargo Priority Access",
            status: "VESSEL BERTHED (Loading Process Active)",
            status_en: "VESSEL BERTHED (Loading Process Active)",
            status_color: "bg-amber-500/10 border-amber-500/20 text-amber-400",
            gate_in_time: "16 Mei 2026 - 21:05 WIB",
            pod: "Osaka, Japan (KIX) - Hospital Apparel Destination",
            block_position: "Onboard Vessel - Hold Section 3 (Transit Mode)"
        }
    };

    // 🚢 2. DATA DUMMY JADWAL KAPAL EKSPOR (VESSEL SAILING SCHEDULE)
    const vesselSchedules = [
        {
            vessel_name: "ONE OLYMPUS V.024N",
            shipping_line: "ONE (Ocean Network Express)",
            terminal: "JICT - Terminal 1",
            pol: "Tanjung Priok (IDTPP)",
            pod: "Tokyo, Japan (JPTYO)",
            open_stack: "15 May 2026 - 08:00",
            closing_time: "19 May 2026 - 12:00",
            etd: "20 May 2026 - 22:00",
            status: "Open Stack"
        },
        {
            vessel_name: "CMA CGM CORAL V.1264",
            shipping_line: "CMA CGM",
            terminal: "JICT - Terminal 2",
            pol: "Tanjung Priok (IDTPP)",
            pod: "Osaka, Japan (KIX)",
            open_stack: "13 May 2026 - 09:00",
            closing_time: "16 May 2026 - 18:00",
            etd: "18 May 2026 - 06:00",
            status: "Berthed"
        },
        {
            vessel_name: "MAERSK MC-KINNEY MOLLER V.2604",
            shipping_line: "MAERSK LINE",
            terminal: "NPCT1",
            pol: "Tanjung Priok (IDTPP)",
            pod: "Rotterdam, Netherlands (NLRTM)",
            open_stack: "16 May 2026 - 07:00",
            closing_time: "20 May 2026 - 23:59",
            etd: "22 May 2026 - 10:00",
            status: "Open Stack"
        },
        {
            vessel_name: "COSCO SHIPPING ALPS V.032E",
            shipping_line: "COSCO SHIPPING",
            terminal: "NPCT1",
            pol: "Tanjung Priok (IDTPP)",
            pod: "Shanghai, China (CNSHA)",
            open_stack: "18 May 2026 - 10:00",
            closing_time: "22 May 2026 - 17:00",
            etd: "24 May 2026 - 04:00",
            status: "Pending"
        }
    ];

    const handleTrackingSearch = (e) => {
        e.preventDefault();
        const cleanId = searchId.trim().toUpperCase();
        if (mockDatabase[cleanId]) {
            setActiveData(mockDatabase[cleanId]);
        } else {
            setActiveData(null);
        }
        setHasSearched(true);
    };

    return (
        <AuthenticatedLayout>
            <Head title={isEn ? "Live Port Container Tracking Terminal" : "Terminal Pelacakan Kontainer Pelabuhan Live"} />

            <div className="p-6 lg:p-10 max-w-6xl mx-auto space-y-10">
                {/* --- HEADER INTEGRASI API --- */}
                <div className="border-l-4 border-amber-500 pl-4">
                    <h3 className="text-xl font-black uppercase text-white tracking-tight">
                        {isEn ? "JICT & NPCT1 Live API Integration" : "Integrasi API Live JICT & NPCT1"}
                    </h3>
                    <p className="text-gray-400 text-xs mt-0.5">
                        {isEn ? "Real-time read-only container tracking console terminal via API Jakarta networks." : "Konsol pelacakan peti kemas real-time read-only melalui jaringan API Jakarta."}
                    </p>
                </div>

                {/* --- SEKTOR 1: INPUT BAR PENGENAL KONTANER --- */}
                <form onSubmit={handleTrackingSearch} className="bg-[#0b1329]/40 border border-white/5 p-6 rounded-3xl shadow-xl flex flex-col md:flex-row gap-4 items-center backdrop-blur-xl">
                    <div className="relative flex-1 w-full">
                        <Container className="absolute left-4 top-3.5 w-4 h-4 text-gray-500" />
                        <input 
                            type="text" 
                            placeholder={isEn ? "Try typing: COATS58700, LABDA70021, or KREASI1504..." : "Coba ketik: COATS58700, LABDA70021, atau KREASI1504..."}
                            value={searchId}
                            onChange={(e) => setSearchId(e.target.value)}
                            className="w-full bg-[#0f172a] border border-white/10 pl-12 pr-4 py-3.5 rounded-xl text-xs font-mono text-white focus:outline-none focus:border-amber-500 uppercase tracking-widest font-bold shadow-inner"
                            required
                        />
                    </div>
                    <button 
                        type="submit"
                        className="w-full md:w-auto bg-gradient-to-r from-amber-500 to-yellow-500 text-[#030712] text-[10px] font-black uppercase tracking-widest px-6 py-4 rounded-xl flex items-center justify-center gap-2 shadow-lg cursor-pointer hover:scale-[1.02] transition-transform duration-300"
                    >
                        <Search className="w-4 h-4 stroke-[2.5]" /> {isEn ? "Fetch API Data" : "Tarik Data API"}
                    </button>
                </form>

                {/* --- PANEL HASIL RENDER LIVE DATA JICT/NPCT1 --- */}
                {hasSearched && activeData && (
                    <div className="bg-[#0b1329]/20 border border-white/5 p-8 rounded-[35px] shadow-2xl space-y-6 animate-fade-in relative overflow-hidden">
                        <div className="absolute top-0 right-0 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 font-mono font-bold text-[8px] tracking-widest uppercase px-4 py-1.5 rounded-bl-3xl flex items-center gap-1.5">
                            <ShieldCheck className="w-3 h-3" /> API READ-ONLY CONNECTED
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6 border-b border-white/5 pb-6">
                            <div className="space-y-1">
                                <span className="text-gray-500 uppercase text-[9px] font-mono tracking-wider">{isEn ? "Container Identifier / Owner" : "Identitas Kontainer / Pemilik"}</span>
                                <p className="text-white text-base font-mono font-black tracking-tight">{activeData.container_no}</p>
                            </div>
                            <div className="space-y-1">
                                <span className="text-gray-500 uppercase text-[9px] font-mono tracking-wider">{isEn ? "Active Port Operator" : "Operator Pelabuhan Aktif"}</span>
                                <p className="text-amber-400 text-sm font-black uppercase flex items-center gap-1.5"><Anchor className="w-4 h-4 text-amber-500/40" /> {activeData.terminal}</p>
                            </div>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs border-b border-white/5 pb-6">
                            <div className="space-y-1">
                                <span className="text-gray-500 uppercase text-[9px] font-mono tracking-wider">{isEn ? "Vessel / Ocean Carrier" : "Kapal Pengangkut / Pelayaran"}</span>
                                <p className="text-white font-bold flex items-center gap-2 font-sans text-[11px]"><Ship className="w-4 h-4 text-blue-400/60" /> {activeData.vessel} <br/><span className="text-[9px] text-gray-400 font-mono font-normal">({activeData.shipping_line})</span></p>
                            </div>
                            <div className="space-y-1">
                                <span className="text-gray-500 uppercase text-[9px] font-mono tracking-wider">{isEn ? "Port of Discharge (POD)" : "Pelabuhan Tujuan Bongkar (POD)"}</span>
                                <p className="text-white font-black flex items-center gap-2 uppercase font-mono text-[11px]"><Globe className="w-4 h-4 text-indigo-400/60" /> {activeData.pod}</p>
                            </div>
                            <div className="space-y-1">
                                <span className="text-gray-500 uppercase text-[9px] font-mono tracking-wider">{isEn ? "Yard Coordinates / Block" : "Koordinat Lapangan Penumpukan"}</span>
                                <p className="text-gray-300 font-bold flex items-center gap-2 text-[11px]"><MapPin className="w-4 h-4 text-rose-400/60" /> {activeData.block_position}</p>
                            </div>
                        </div>

                        <div className={`${activeData.status_color} border p-4 rounded-2xl flex items-center gap-3 text-[11px] font-black uppercase font-mono tracking-wider shadow-inner`}>
                            <Calendar className="w-4 h-4 stroke-[2.5]" />
                            <span>{isEn ? `Live Tracking Status: ${activeData.status_en}` : `Status Terkini Pelabuhan: ${activeData.status}`}</span>
                            <span className="ml-auto text-gray-500 font-normal normal-case font-sans text-[10px]">Logged: {activeData.gate_in_time}</span>
                        </div>
                    </div>
                )}

                {hasSearched && !activeData && (
                    <div className="bg-red-500/5 border border-red-500/10 p-6 rounded-2xl flex items-center gap-4 text-red-400 text-xs font-bold animate-fade-in">
                        <AlertCircle className="w-5 h-5 shrink-0" />
                        <div>
                            <p className="uppercase font-black tracking-wider">{isEn ? "ISO Container Number Not Registered" : "Nomor Kontainer Tidak Terdaftar"}</p>
                            <p className="text-gray-400 font-normal normal-case mt-0.5">{isEn ? "Please verify the shipping prefix or contact API Jakarta Logistics Desk." : "Silakan periksa kembali kode prefiks kontainer ekspor Anda atau hubungi Desk Logistik API Jakarta."}</p>
                        </div>
                    </div>
                )}

                {/* --- SEKTOR 2: JADWAL KEBERANGKATAN KAPAL EKSPOR (VESSEL SAILING SCHEDULE) --- */}
                <div className="bg-[#0b1329]/30 border border-white/5 p-6 lg:p-8 rounded-[40px] shadow-2xl space-y-6">
                    <div className="border-l-4 border-amber-500 pl-4 flex justify-between items-center">
                        <div>
                            <h4 className="text-xs font-black uppercase tracking-widest text-white">{isEn ? "JICT & NPCT1 Ocean Freight Sailing Schedule" : "Jadwal Pelayaran Kapal Kontainer JICT & NPCT1"}</h4>
                            <p className="text-[10px] text-gray-500 mt-0.5">{isEn ? "Direct ocean freight allocations validated via GPEI port authorities." : "Alokasi kapal langsung yang divalidasi melalui otoritas pelabuhan GPEI."}</p>
                        </div>
                        <div className="bg-amber-500/10 border border-amber-500/20 text-amber-400 font-mono text-[9px] font-bold px-3 py-1.5 rounded-xl uppercase tracking-widest flex items-center gap-1.5">
                            <Clock className="w-3.5 h-3.5" /> Live Data Feed
                        </div>
                    </div>

                    {/* TABEL AREA RESPONSIVE */}
                    <div className="overflow-x-auto rounded-2xl border border-white/5">
                        <table className="w-full text-left border-collapse">
                            <thead className="bg-[#0a192f] text-gray-400 uppercase text-[8px] tracking-widest border-b border-white/5">
                                <tr>
                                    <th className="py-4 pl-6">{isEn ? "Vessel / Voyage" : "Nama Kapal / Voyage"}</th>
                                    <th className="py-4">{isEn ? "Port Link" : "Rute Jalur Ekspor"}</th>
                                    <th className="py-4">{isEn ? "Open Stack" : "Buka Tumpuk (Open Stack)"}</th>
                                    <th className="py-4 text-amber-400">{isEn ? "Closing Time" : "Batas Akhir (Closing)"}</th>
                                    <th className="py-4 text-emerald-400">{isEn ? "ETD (Sailing)" : "Waktu Berangkat (ETD)"}</th>
                                    <th className="py-4 text-right pr-6">{isEn ? "Terminal Status" : "Status Terminal"}</th>
                                </tr>
                            </thead>
                            <tbody className="text-[11px] text-gray-300 font-medium font-sans">
                                {vesselSchedules.map((vessel, idx) => (
                                    <tr key={idx} className="border-b border-white/5 hover:bg-white/5 transition duration-200">
                                        {/* NAMA KAPAL */}
                                        <td className="py-4 pl-6 font-bold text-white">
                                            <div className="flex flex-col gap-0.5">
                                                <span className="text-white font-black text-xs flex items-center gap-1.5"><Ship className="w-3.5 h-3.5 text-blue-400/60" /> {vessel.vessel_name}</span>
                                                <span className="text-[9px] text-gray-500 font-mono font-normal">{vessel.shipping_line}</span>
                                            </div>
                                        </td>
                                        {/* RUTE JALUR */}
                                        <td className="py-4 font-mono font-bold text-gray-300">
                                            <div className="flex items-center gap-2">
                                                <span>{vessel.pol.replace(" (IDTPP)", "")}</span>
                                                <ArrowRight className="w-3 h-3 text-amber-500/50" />
                                                <span className="text-amber-400">{vessel.pod.split(" (")[0]}</span>
                                            </div>
                                        </td>
                                        {/* OPEN STACK */}
                                        <td className="py-4 font-mono text-gray-400">{vessel.open_stack}</td>
                                        {/* CLOSING TIME */}
                                        <td className="py-4 font-mono font-bold text-amber-400/90">{vessel.closing_time}</td>
                                        {/* ETD DEPARTURE */}
                                        <td className="py-4 font-mono font-black text-emerald-400">{vessel.etd}</td>
                                        {/* STATUS BADGE */}
                                        <td className="py-4 text-right pr-6 font-mono font-bold">
                                            {vessel.status === "Berthed" ? (
                                                <span className="bg-amber-500/10 border border-amber-500/20 text-amber-400 text-[8px] uppercase px-2 py-1 rounded-md">Berthed</span>
                                            ) : vessel.status === "Open Stack" ? (
                                                <span className="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[8px] uppercase px-2 py-1 rounded-md">Active</span>
                                            ) : (
                                                <span className="bg-white/5 border border-white/5 text-gray-500 text-[8px] uppercase px-2 py-1 rounded-md">Pending</span>
                                            )}
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
