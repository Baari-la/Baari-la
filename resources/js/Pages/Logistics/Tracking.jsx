import React, { useState } from "react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import {
    Container,
    Ship,
    MapPin,
    Search,
    ShieldCheck,
    AlertCircle,
    Calendar,
    Anchor,
} from "lucide-react";

export default function Tracking({ locale }) {
    const isEn = locale === "en";
    const [searchId, setSearchId] = useState("");
    const [activeData, setActiveData] = useState(null);
    const [hasSearched, setHasSearched] = useState(false);

    // 📊 BANK DATA SIMULASI INTERAKTIF (FOUNDING PARTNERS PIPELINE)
    const mockDatabase = {
        // 1. DATA KONTANER PT. COATS REJO INDONESIA (company_id: 58)
        COATS58700: {
            container_no: "COATS58700 (COATS REJO INDONESIA)",
            terminal:
                "JICT - Terminal 1 (Jakarta International Container Terminal)",
            vessel: "ONE OLYMPUS V.024N",
            shipping_line: "ONE (Ocean Network Express) - Japan Direct Route",
            status: "GATE-IN SUCCESS (Stacking Yard)",
            status_en: "GATE-IN SUCCESS (Stacking Yard)",
            status_color:
                "bg-emerald-500/10 border-emerald-500/20 text-emerald-400",
            gate_in_time: "18 Mei 2026 - 08:30 WIB",
            pod: "Tokyo, Japan (JPTYO) - Central Apparel Market",
            block_position:
                "CY Block 4A - Slot 12 - Tier 3 (Ready for Loading)",
        },
        // 2. DATA KONTANER LABDA ANUGERAH TEKSTIL (company_id: 700)
        LABDA70021: {
            container_no: "LABDA70021 (LABDA ANUGERAH TEKSTIL)",
            terminal: "NPCT1 - New Priok Container Terminal 1",
            vessel: "MAERSK MC-KINNEY MOLLER V.2604",
            shipping_line: "MAERSK LINE - Europe Eco-Trade Corridor",
            status: "CUSTOMS RELEASED (SPPB Approved)",
            status_en: "CUSTOMS RELEASED (SPPB Approved)",
            status_color: "bg-blue-500/10 border-blue-500/20 text-blue-400",
            gate_in_time: "17 Mei 2026 - 14:15 WIB",
            pod: "Rotterdam, Netherlands (NLRTM) - Sustainable Textile Hub",
            block_position: "CY Block B2 - Slot 08 - Tier 2 (Customs Clear)",
        },
        // 3. DATA KONTANER PT. KREASI INDAH BUSANA (company_id: 1504)
        KREASI1504: {
            container_no: "KREASI1504 (KREASI INDAH BUSANA)",
            terminal: "JICT - Terminal 2",
            vessel: "CMA CGM CORAL V.1264",
            shipping_line: "CMA CGM - Medical Cargo Priority Access",
            status: "VESSEL BERTHED (Loading Process Active)",
            status_en: "VESSEL BERTHED (Loading Process Active)",
            status_color: "bg-amber-500/10 border-amber-500/20 text-amber-400",
            gate_in_time: "16 Mei 2026 - 21:05 WIB",
            pod: "Osaka, Japan (KIX) - Hospital Apparel Destination",
            block_position: "Onboard Vessel - Hold Section 3 (Transit Mode)",
        },
    };

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
            <Head
                title={
                    isEn
                        ? "Live Port Container Tracking Terminal"
                        : "Terminal Pelacakan Kontainer Pelabuhan Live"
                }
            />

            <div className="p-6 lg:p-10 max-w-5xl mx-auto space-y-8">
                {/* --- HEADER INTEGRASI API --- */}
                <div className="border-l-4 border-amber-500 pl-4 mb-6">
                    <h3 className="text-xl font-black uppercase text-white tracking-tight">
                        {isEn
                            ? "JICT & NPCT1 Live API Integration"
                            : "Integrasi API Live JICT & NPCT1"}
                    </h3>
                    <p className="text-gray-400 text-xs mt-0.5">
                        {isEn
                            ? "Real-time read-only container tracking console terminal via API Jakarta networks."
                            : "Konsol pelacakan peti kemas real-time read-only melalui jaringan API Jakarta."}
                    </p>
                </div>

                {/* --- INPUT BAR PENGENAL KONTANER --- */}
                <form
                    onSubmit={handleTrackingSearch}
                    className="bg-[#0b1329]/40 border border-white/5 p-6 rounded-3xl shadow-xl flex flex-col md:flex-row gap-4 items-center"
                >
                    <div className="relative flex-1 w-full">
                        <Container className="absolute left-4 top-3.5 w-4 h-4 text-gray-500" />
                        <input
                            type="text"
                            placeholder={
                                isEn
                                    ? "Try typing: COATS58700, LABDA70021, or KREASI1504..."
                                    : "Coba ketik: COATS58700, LABDA70021, atau KREASI1504..."
                            }
                            value={searchId}
                            onChange={(e) => setSearchId(e.target.value)}
                            className="w-full bg-[#0f172a] border border-white/10 pl-12 pr-4 py-3.5 rounded-xl text-xs font-mono text-white focus:outline-none focus:border-amber-500 uppercase tracking-widest font-bold"
                            required
                        />
                    </div>
                    <button
                        type="submit"
                        className="w-full md:w-auto bg-gradient-to-r from-amber-500 to-yellow-500 text-[#030712] text-[10px] font-black uppercase tracking-widest px-6 py-4 rounded-xl flex items-center justify-center gap-2 shadow-lg cursor-pointer hover:scale-[1.02] transition-transform duration-300"
                    >
                        <Search className="w-4 h-4 stroke-[2.5]" />{" "}
                        {isEn ? "Fetch API Data" : "Tarik Data API"}
                    </button>
                </form>

                {/* --- PANEL 1: HASIL KONTANER DITEMUKAN --- */}
                {hasSearched && activeData && (
                    <div className="bg-[#0b1329]/20 border border-white/5 p-8 rounded-[35px] shadow-2xl space-y-6 animate-fade-in relative overflow-hidden">
                        <div className="absolute top-0 right-0 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 font-mono font-bold text-[8px] tracking-widest uppercase px-4 py-1.5 rounded-bl-3xl flex items-center gap-1.5">
                            <ShieldCheck className="w-3 h-3" /> API READ-ONLY
                            CONNECTED
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6 border-b border-white/5 pb-6">
                            <div className="space-y-1">
                                <span className="text-gray-500 uppercase text-[9px] font-mono tracking-wider">
                                    {isEn
                                        ? "Container Identifier / Owner"
                                        : "Identitas Kontainer / Pemilik"}
                                </span>
                                <p className="text-white text-base font-mono font-black tracking-tight">
                                    {activeData.container_no}
                                </p>
                            </div>
                            <div className="space-y-1">
                                <span className="text-gray-500 uppercase text-[9px] font-mono tracking-wider">
                                    {isEn
                                        ? "Active Port Operator"
                                        : "Operator Pelabuhan Aktif"}
                                </span>
                                <p className="text-amber-400 text-sm font-black uppercase flex items-center gap-1.5">
                                    <Anchor className="w-4 h-4 text-amber-500/40" />{" "}
                                    {activeData.terminal}
                                </p>
                            </div>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs border-b border-white/5 pb-6">
                            <div className="space-y-1">
                                <span className="text-gray-500 uppercase text-[9px] font-mono tracking-wider">
                                    {isEn
                                        ? "Vessel / Ocean Carrier"
                                        : "Kapal Pengangkut / Pelayaran"}
                                </span>
                                <p className="text-white font-bold flex items-center gap-2 font-sans text-[11px]">
                                    <Ship className="w-4 h-4 text-blue-400/60" />{" "}
                                    {activeData.vessel} <br />
                                    <span className="text-[9px] text-gray-400 font-mono font-normal">
                                        ({activeData.shipping_line})
                                    </span>
                                </p>
                            </div>
                            <div className="space-y-1">
                                <span className="text-gray-500 uppercase text-[9px] font-mono tracking-wider">
                                    {isEn
                                        ? "Port of Discharge (POD)"
                                        : "Pelabuhan Tujuan Bongkar (POD)"}
                                </span>
                                <p className="text-white font-black flex items-center gap-2 uppercase font-mono text-[11px]">
                                    <Globe className="w-4 h-4 text-indigo-400/60" />{" "}
                                    {activeData.pod}
                                </p>
                            </div>
                            <div className="space-y-1">
                                <span className="text-gray-500 uppercase text-[9px] font-mono tracking-wider">
                                    {isEn
                                        ? "Yard Coordinates / Block"
                                        : "Koordinat Lapangan Penumpukan"}
                                </span>
                                <p className="text-gray-300 font-bold flex items-center gap-2 text-[11px]">
                                    <MapPin className="w-4 h-4 text-rose-400/60" />{" "}
                                    {activeData.block_position}
                                </p>
                            </div>
                        </div>

                        {/* STATUS REAKTIF BAR */}
                        <div
                            className={`${activeData.status_color} border p-4 rounded-2xl flex items-center gap-3 text-[11px] font-black uppercase font-mono tracking-wider shadow-inner`}
                        >
                            <Calendar className="w-4 h-4 stroke-[2.5]" />
                            <span>
                                {isEn
                                    ? `Live Tracking Status: ${activeData.status_en}`
                                    : `Status Terkini Pelabuhan: ${activeData.status}`}
                            </span>
                            <span className="ml-auto text-gray-500 font-normal normal-case font-sans text-[10px]">
                                Logged: {activeData.gate_in_time}
                            </span>
                        </div>
                    </div>
                )}

                {/* --- PANEL 2: JIKA DATA TIDAK DITEMUKAN --- */}
                {hasSearched && !activeData && (
                    <div className="bg-red-500/5 border border-red-500/10 p-6 rounded-2xl flex items-center gap-4 text-red-400 text-xs font-bold animate-fade-in">
                        <AlertCircle className="w-5 h-5 shrink-0" />
                        <div>
                            <p className="uppercase font-black tracking-wider">
                                {isEn
                                    ? "ISO Container Number Not Registered"
                                    : "Nomor Kontainer Tidak Terdaftar"}
                            </p>
                            <p className="text-gray-400 font-normal normal-case mt-0.5">
                                {isEn
                                    ? "Please verify the shipping prefix or contact API Jakarta Logistics Desk."
                                    : "Silakan periksa kembali kode prefiks kontainer ekspor Anda atau hubungi Desk Logistik API Jakarta."}
                            </p>
                        </div>
                    </div>
                )}
            </div>
        </AuthenticatedLayout>
    );
}
