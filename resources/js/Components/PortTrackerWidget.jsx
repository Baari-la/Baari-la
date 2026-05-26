import React from "react";
import { Anchor, Globe, ArrowRight, Activity } from "lucide-react";

export default function PortTrackerWidget({ containerLogs = [] }) {
    return (
        <div className="bg-[#0b1329] p-6 lg:p-8 rounded-[35px] border border-white/5 shadow-2xl text-gray-100 font-mono">
            {/* Header Widget */}
            <div className="flex justify-between items-center mb-6 border-b border-white/5 pb-4">
                <div className="flex items-center gap-3">
                    <div className="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center border border-blue-500/30">
                        <Anchor className="w-4 h-4 text-blue-400" />
                    </div>
                    <div>
                        <h4 className="text-xs font-black uppercase tracking-widest text-white">
                            Global Supply Chain Manifest Radar
                        </h4>
                        <p className="text-[8px] text-gray-500 uppercase tracking-wider mt-0.5">
                            Real-Time Origin, Destination, and Terminal
                            Logistics Across Indonesia Ports
                        </p>
                    </div>
                </div>
                <div className="text-[8px] bg-purple-500/10 text-purple-400 px-2.5 py-1 rounded-lg font-black border border-purple-500/20 animate-pulse flex items-center gap-1">
                    <Globe className="w-2.5 h-2.5" /> GLOBAL ORIGIN-DESTINATION
                    ACTIVE
                </div>
            </div>

            {/* Tabel Log Kontainer Internasional */}
            <div className="overflow-x-auto">
                <table className="w-full text-[10px] text-left border-collapse">
                    <thead>
                        <tr className="border-b border-white/10 text-gray-400 text-[8px] uppercase tracking-wider">
                            <th className="py-3 px-2">Tanggal Manifes</th>
                            <th className="py-3 px-2">No. Kontainer</th>
                            <th className="py-3 px-2">
                                Rute Geografi (Asal{" "}
                                <ArrowRight className="w-2 h-2 inline" />{" "}
                                Tujuan)
                            </th>
                            <th className="py-3 px-2">Terminal Pelabuhan</th>
                            <th className="py-3 px-2">Komoditas Sektor</th>
                            <th className="py-3 px-2 text-right">Volume</th>
                            <th className="py-3 px-2 text-center">
                                Status Arus
                            </th>
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-white/5">
                        {containerLogs && containerLogs.length > 0 ? (
                            containerLogs.map((log, index) => {
                                const isExport =
                                    log.gate_status?.includes("EKSPOR") ||
                                    log.gate_status?.includes("COMPLETED");
                                return (
                                    <tr
                                        key={index}
                                        className="hover:bg-white/5 transition-colors group"
                                    >
                                        <td className="py-3.5 px-2 text-gray-400 text-[9px] whitespace-nowrap">
                                            {log.logistics_date
                                                ? log.logistics_date.substring(
                                                      0,
                                                      10,
                                                  )
                                                : new Date()
                                                      .toISOString()
                                                      .substring(0, 10)}
                                        </td>
                                        <td className="py-3.5 px-2 font-bold text-amber-400 group-hover:text-amber-300">
                                            {log.container_no}
                                        </td>
                                        <td className="py-3.5 px-2 font-bold text-white whitespace-nowrap">
                                            <span
                                                className={
                                                    isExport
                                                        ? "text-gray-400 font-normal"
                                                        : "text-emerald-400 font-black"
                                                }
                                            >
                                                {log.country_origin || "-"}
                                            </span>
                                            <ArrowRight className="w-2.5 h-2.5 inline mx-1.5 text-purple-400" />
                                            <span
                                                className={
                                                    isExport
                                                        ? "text-indigo-400 font-black"
                                                        : "text-gray-400 font-normal"
                                                }
                                            >
                                                {log.country_destination || "-"}
                                            </span>
                                        </td>
                                        <td className="py-3.5 px-2 text-gray-300 text-[9px]">
                                            {log.port_name}
                                        </td>
                                        <td className="py-3.5 px-2 text-gray-400">
                                            <span className="text-blue-400 font-bold mr-1">
                                                [{log.hs_code}]
                                            </span>{" "}
                                            {log.commodity_type}
                                        </td>
                                        <td className="py-3.5 px-2 text-right text-white font-black">
                                            {log.quantity
                                                ? parseInt(
                                                      log.quantity,
                                                  ).toLocaleString("id-ID")
                                                : "0"}{" "}
                                            <span className="text-[8px] text-gray-500 font-normal">
                                                {log.volume_unit}
                                            </span>
                                        </td>
                                        <td className="py-3.5 px-2 text-center">
                                            <span
                                                className={`px-2 py-0.5 rounded text-[8px] font-black tracking-wider uppercase ${isExport ? "bg-blue-500/20 text-blue-400 border border-blue-500/30" : "bg-orange-500/20 text-orange-400 border border-orange-500/30"}`}
                                            >
                                                {isExport
                                                    ? "📤 EKSPOR"
                                                    : "📥 IMPOR"}
                                            </span>
                                        </td>
                                    </tr>
                                );
                            })
                        ) : (
                            <tr>
                                <td
                                    colSpan="7"
                                    className="py-6 text-center text-gray-500 text-[9px] uppercase tracking-widest italic"
                                >
                                    No port telemetry logs streams latched
                                    inside database yet.
                                </td>
                            </tr>
                        )}
                    </tbody>
                </table>
            </div>
        </div>
    );
}
