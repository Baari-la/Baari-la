import React, { useState, useEffect } from "react";
import {
    Calculator,
    DollarSign,
    Activity,
    TrendingUp,
    Ship,
} from "lucide-react";

export default function PredictiveCalculator({
    usd_idr = 17712,
    cottonPrice = 77.7,
    isEn = false,
}) {
    // 🧵 STATE UTAMA: PILIHAN SPESIFIKASI MATERIAL
    const [yarnType, setYarnType] = useState("cotton_30s");
    const [fabricType, setFabricType] = useState("medium_plain");

    // State Input Parameter Industri & Logistik
    const [containerCost, setContainerCost] = useState(8000000);
    const [containerLoad, setContainerLoad] = useState(20000);
    const [efficiency, setEfficiency] = useState(85);
    const [lohSpinning, setLohSpinning] = useState(4500);
    const [yarnConsumption, setYarnConsumption] = useState(0.25);
    const [weavingCost, setWeavingCost] = useState(6500); // Fokus murni ke ongkos tenun kain mentah
    const [profitMargin, setProfitMargin] = useState(15);

    // STATE LOCKER: Melacak input manual kustom pengguna
    const [isCustomEff, setIsCustomEff] = useState(false);
    const [isCustomLoh, setIsCustomLoh] = useState(false);
    const [isCustomCons, setIsCustomCons] = useState(false);
    const [isCustomWeave, setIsCustomWeave] = useState(false);

    // Output Hasil Kalkulasi
    const [logisticCostPerKg, setLogisticCostPerKg] = useState(0);
    const [rawMaterialCostKg, setRawMaterialCostKg] = useState(0);
    const [yarnHppKg, setYarnHppKg] = useState(0);
    const [fabricHppMeter, setFabricHppMeter] = useState(0);
    const [suggestedYarnPrice, setSuggestedYarnPrice] = useState(0);

    // 🚨 TRIGGER 1: Preset Benang
    useEffect(() => {
        if (!isCustomEff) {
            if (yarnType === "cotton_20s") setEfficiency(88);
            else if (yarnType === "cotton_30s") setEfficiency(85);
            else if (yarnType === "cotton_40s") setEfficiency(73);
            else if (yarnType === "cvc_30s") setEfficiency(90);
        }
        if (!isCustomLoh) {
            if (yarnType === "cotton_20s") setLohSpinning(3800);
            else if (yarnType === "cotton_30s") setLohSpinning(4500);
            else if (yarnType === "cotton_40s") setLohSpinning(5800);
            else if (yarnType === "cvc_30s") setLohSpinning(4000);
        }
    }, [yarnType, isCustomEff, isCustomLoh]);

    // 🚨 TRIGGER 2: Preset Kain Mentah
    useEffect(() => {
        if (!isCustomCons) {
            if (fabricType === "light_shirt") setYarnConsumption(0.15);
            else if (fabricType === "medium_plain") setYarnConsumption(0.25);
            else if (fabricType === "heavy_twill") setYarnConsumption(0.4);
        }
        if (!isCustomWeave) {
            if (fabricType === "light_shirt")
                setWeavingCost(4500); // Lebih murah karena tanpa dyes kimia
            else if (fabricType === "medium_plain") setWeavingCost(5500);
            else if (fabricType === "heavy_twill") setWeavingCost(7500);
        }
    }, [fabricType, isCustomCons, isCustomWeave]);

    // 📊 TRIGGER 3: Engine Perhitungan Komputasi Finansial
    useEffect(() => {
        const calcLogisticPerKg =
            parseFloat(containerCost || 0) / parseFloat(containerLoad || 20000);
        setLogisticCostPerKg(calcLogisticPerKg);

        const cottonPerKgUsd = cottonPrice * 2.20462;
        let baseRawCostIdr = (cottonPerKgUsd / 100) * usd_idr;

        if (yarnType === "cvc_30s") {
            baseRawCostIdr = baseRawCostIdr * 0.6 + 18000 * 0.4;
        }

        const totalRawCostIdr = baseRawCostIdr + calcLogisticPerKg;
        setRawMaterialCostKg(totalRawCostIdr);

        const effFactor = parseFloat(efficiency || 1) / 100;
        const yarnHpp =
            totalRawCostIdr / effFactor + parseFloat(lohSpinning || 0);
        setYarnHppKg(yarnHpp);

        const yarnPrice = yarnHpp * (1 + parseFloat(profitMargin || 0) / 100);
        setSuggestedYarnPrice(yarnPrice);

        // RUMUS FOKUS: HPP Kain Mentah (Grey Fabric)
        const fabricHpp =
            yarnHpp * parseFloat(yarnConsumption || 0) +
            parseFloat(weavingCost || 0);
        setFabricHppMeter(fabricHpp);
    }, [
        usd_idr,
        cottonPrice,
        containerCost,
        containerLoad,
        efficiency,
        lohSpinning,
        yarnConsumption,
        weavingCost,
        profitMargin,
        yarnType,
    ]);

    return (
        <div className="bg-[#0b1329] p-6 lg:p-8 rounded-[35px] border border-white/5 shadow-2xl text-gray-100">
            {/* Header Kalkulator */}
            <div className="flex items-center gap-3 mb-6 border-b border-white/5 pb-4 relative z-10">
                <div className="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center border border-blue-500/30">
                    <Calculator className="w-4 h-4 text-blue-400" />
                </div>
                <div>
                    <h4 className="text-xs font-black uppercase tracking-widest text-white">
                        {isEn
                            ? "Predictive Costing Calculator"
                            : "Kalkulator Prediksi Costing"}
                    </h4>
                    <p className="text-[9px] text-gray-500 font-mono mt-0.5 uppercase tracking-wider">
                        Raw Grey Fabric & Spinning Costing Matrix
                    </p>
                </div>
            </div>

            {/* Grid Layout Konten */}
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 font-mono relative z-10">
                {/* KOLOM KIRI: INPUT PARAMETER */}
                <div className="space-y-4">
                    {/* Sektor Dropdown Seleksi Material */}
                    <div className="bg-gradient-to-r from-amber-500/10 to-transparent p-4 rounded-2xl border border-amber-500/20 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label className="text-[8px] text-amber-400 font-black uppercase block mb-1">
                                Jenis & Nomor Benang
                            </label>
                            <select
                                value={yarnType}
                                onChange={(e) => {
                                    setYarnType(e.target.value);
                                    setIsCustomEff(false);
                                    setIsCustomLoh(false);
                                }}
                                className="w-full bg-[#0b1329] border border-white/10 rounded-xl px-2 py-1.5 text-xs text-white outline-none focus:border-amber-500 cursor-pointer font-bold"
                            >
                                <option value="cotton_20s">
                                    Carded Cotton Ne 20s (Tebal)
                                </option>
                                <option value="cotton_30s">
                                    Combed Cotton Ne 30s (Sedang)
                                </option>
                                <option value="cotton_40s">
                                    Combed Cotton Ne 40s (Halus)
                                </option>
                                <option value="cvc_30s">
                                    CVC Combed 30s (Campuran)
                                </option>
                            </select>
                        </div>
                        <div>
                            <label className="text-[8px] text-emerald-400 font-black uppercase block mb-1">
                                Spesifikasi Kain Mentah (Grey)
                            </label>
                            <select
                                value={fabricType}
                                onChange={(e) => {
                                    setFabricType(e.target.value);
                                    setIsCustomCons(false);
                                    setIsCustomWeave(false);
                                }}
                                className="w-full bg-[#0b1329] border border-white/10 rounded-xl px-2 py-1.5 text-xs text-white outline-none focus:border-emerald-500 cursor-pointer font-bold"
                            >
                                <option value="light_shirt">
                                    Kemeja Ringan (Poplin Grey)
                                </option>
                                <option value="medium_plain">
                                    Polos Sedang (Kain Percal Grey)
                                </option>
                                <option value="heavy_twill">
                                    Twill Tebal (Denim/Seragam Grey)
                                </option>
                            </select>
                        </div>
                    </div>

                    {/* Parameter Logistik */}
                    {/* 🚢 KOTAK 2: LOGISTIK TRUCKING PELABUHAN */}
                    <div className="bg-gradient-to-r from-blue-500/10 to-transparent p-4 rounded-2xl border border-blue-500/20 space-y-3.5">
                        <span className="text-[9px] font-black text-blue-400 uppercase tracking-widest flex items-center gap-1.5">
                            <Ship className="w-3 h-3" />
                            {isEn
                                ? "Port & Trucking Logistics"
                                : "Logistik Pelabuhan & Truk Kontainer"}
                        </span>
                        <div className="grid grid-cols-2 gap-3">
                            <div>
                                <label className="text-[8px] text-gray-400 uppercase block mb-1">
                                    {isEn
                                        ? "Container Cost (IDR)"
                                        : "Ongkos Kontainer (IDR)"}
                                </label>
                                <input
                                    type="number"
                                    value={containerCost}
                                    onChange={(e) =>
                                        setContainerCost(e.target.value)
                                    }
                                    className="w-full bg-white/5 border border-white/10 rounded-xl px-3 py-1.5 text-xs text-white outline-none focus:border-blue-500 font-bold"
                                />
                            </div>
                            <div>
                                <label className="text-[8px] text-gray-400 uppercase block mb-1">
                                    {isEn
                                        ? "Fiber Load (Kg)"
                                        : "Muatan Serat (Kg)"}
                                </label>
                                <input
                                    type="number"
                                    value={containerLoad}
                                    onChange={(e) =>
                                        setContainerLoad(e.target.value)
                                    }
                                    className="w-full bg-white/5 border border-white/10 rounded-xl px-3 py-1.5 text-xs text-white outline-none focus:border-blue-500 font-bold"
                                />
                            </div>
                        </div>
                    </div>

                    {/* Parameter Pemintalan */}
                    <div className="bg-black/20 p-4 rounded-2xl border border-white/5 grid grid-cols-2 gap-4">
                        <div>
                            <div className="flex justify-between items-center mb-1">
                                <label className="text-[8px] text-gray-400 uppercase">
                                    {isEn
                                        ? "Yield Efficiency (%)"
                                        : "Yield Efisiensi (%)"}
                                </label>
                                <span
                                    className={`text-[7px] px-1 rounded ${isCustomEff ? "bg-purple-500 text-white" : "bg-white/10 text-gray-400"}`}
                                >
                                    {isCustomEff ? "Kustom" : "Preset"}
                                </span>
                            </div>
                            <input
                                type="number"
                                value={efficiency}
                                onChange={(e) => {
                                    setEfficiency(e.target.value);
                                    setIsCustomEff(true);
                                }}
                                className="w-full bg-white/5 border border-white/10 rounded-xl px-3 py-1.5 text-xs text-white outline-none focus:border-blue-500 font-bold"
                            />
                        </div>
                        <div>
                            <div className="flex justify-between items-center mb-1">
                                <label className="text-[8px] text-gray-400 uppercase">
                                    {isEn
                                        ? "Factory LOH (IDR/Kg)"
                                        : "Biaya LOH Pabrik (IDR/Kg)"}
                                </label>
                                <span
                                    className={`text-[7px] px-1 rounded ${isCustomLoh ? "bg-purple-500 text-white" : "bg-white/10 text-gray-400"}`}
                                >
                                    {isCustomLoh ? "Kustom" : "Preset"}
                                </span>
                            </div>
                            <input
                                type="number"
                                value={lohSpinning}
                                onChange={(e) => {
                                    setLohSpinning(e.target.value);
                                    setIsCustomLoh(true);
                                }}
                                className="w-full bg-white/5 border border-white/10 rounded-xl px-3 py-1.5 text-xs text-white outline-none focus:border-blue-500 font-bold"
                            />
                        </div>
                    </div>

                    {/* Parameter Pertenunan */}
                    <div className="bg-black/20 p-4 rounded-2xl border border-white/5 grid grid-cols-2 gap-4">
                        <div>
                            <div className="flex justify-between items-center mb-1">
                                <label className="text-[8px] text-gray-400 uppercase">
                                    {isEn
                                        ? "Yarn Cons. (Kg/Mtr)"
                                        : "Konsumsi Benang (Kg/Mtr)"}
                                </label>
                                <span
                                    className={`text-[7px] px-1 rounded ${isCustomCons ? "bg-purple-500 text-white" : "bg-white/10 text-gray-400"}`}
                                >
                                    {isCustomCons ? "Kustom" : "Preset"}
                                </span>
                            </div>
                            <input
                                type="number"
                                step="0.01"
                                value={yarnConsumption}
                                onChange={(e) => {
                                    setYarnConsumption(e.target.value);
                                    setIsCustomCons(true);
                                }}
                                className="w-full bg-white/5 border border-white/10 rounded-xl px-3 py-1.5 text-xs text-white outline-none focus:border-blue-500 font-bold"
                            />
                        </div>
                        <div>
                            <div className="flex justify-between items-center mb-1">
                                <label className="text-[8px] text-gray-400 uppercase">
                                    {isEn
                                        ? "Raw Grey Weaving Cost"
                                        : "Ongkos Tenun Kain Mentah (Meter)"}
                                </label>
                                <span
                                    className={`text-[7px] px-1 rounded ${isCustomWeave ? "bg-purple-500 text-white" : "bg-white/10 text-gray-400"}`}
                                >
                                    {isCustomWeave ? "Kustom" : "Preset"}
                                </span>
                            </div>
                            {/* 🚨 SINKRONISASI KRUSIAL: Menghubungkan value ke weavingCost agar variabelnya sinkron dengan output kain mentah */}
                            <input
                                type="number"
                                value={weavingCost}
                                onChange={(e) => {
                                    setWeavingCost(e.target.value);
                                    setIsCustomWeave(true);
                                }}
                                className="w-full bg-white/5 border border-white/10 rounded-xl px-3 py-1.5 text-xs text-white outline-none focus:border-blue-500 font-bold"
                            />
                        </div>
                    </div>

                    {/* Target Profit Margin */}
                    <div>
                        <label className="text-[8px] text-gray-400 uppercase block mb-1">
                            {isEn
                                ? "Target Profit Margin (%)"
                                : "Target Margin Keuntungan Perusahaan (%)"}
                        </label>
                        <input
                            type="number"
                            value={profitMargin}
                            onChange={(e) => setProfitMargin(e.target.value)}
                            className="w-full bg-white/5 border border-white/10 rounded-xl px-3 py-2 text-xs text-white outline-none focus:border-blue-500 font-bold"
                        />
                    </div>
                </div>

                {/* KOLOM KANAN: OUTPUT HASIL SEKTOR KAIN MENTAH (GREY) */}
                <div className="flex flex-col justify-between gap-4">
                    <div className="bg-white/5 border border-white/5 p-4 rounded-2xl flex items-center justify-between text-[9px] text-gray-400">
                        <span className="flex items-center gap-1">
                            <DollarSign className="w-3 h-3 text-amber-500" />{" "}
                            ICE Cotton:{" "}
                            <strong className="text-white">
                                ${cottonPrice}
                            </strong>
                        </span>
                        <span className="flex items-center gap-1">
                            <Activity className="w-3 h-3 text-blue-400" /> Kurs
                            BI:{" "}
                            <strong className="text-white">
                                Rp {parseInt(usd_idr).toLocaleString("id-ID")}
                            </strong>
                        </span>
                    </div>

                    <div className="bg-black/30 border border-white/5 p-4 rounded-2xl text-[9px] text-gray-400 space-y-1">
                        <p className="font-black text-blue-400 uppercase tracking-widest text-[8px]">
                            LOGISTICS DEPLOYMENT COST REVEALED
                        </p>
                        <div className="flex justify-between">
                            <span>Beban Truk Pelabuhan ke Gudang:</span>
                            <span className="text-white font-bold">
                                Rp{" "}
                                {parseInt(logisticCostPerKg).toLocaleString(
                                    "id-ID",
                                )}{" "}
                                / Kg Serat
                            </span>
                        </div>
                    </div>

                    <div className="bg-gradient-to-br from-amber-500/10 to-transparent border border-amber-500/20 p-5 rounded-2xl relative overflow-hidden">
                        <div className="absolute top-2 right-2 opacity-10">
                            <TrendingUp className="w-12 h-12 text-amber-500" />
                        </div>
                        <p className="text-[8px] text-amber-400 uppercase tracking-widest font-black">
                            PROYEKSI HPP BENANG JADI
                        </p>
                        <h3 className="text-xl font-black text-white mt-1">
                            Rp {parseInt(yarnHppKg).toLocaleString("id-ID")}{" "}
                            <span className="text-[10px] text-gray-400 font-normal">
                                / Kg
                            </span>
                        </h3>
                        <p className="text-[8px] text-gray-400 mt-2">
                            Harga Jual Benang Rekomendasi (+{profitMargin}%):{" "}
                            <strong className="text-amber-400">
                                Rp{" "}
                                {parseInt(suggestedYarnPrice).toLocaleString(
                                    "id-ID",
                                )}{" "}
                                / Kg
                            </strong>
                        </p>
                    </div>

                    <div className="bg-gradient-to-br from-emerald-500/10 to-transparent border border-emerald-500/20 p-5 rounded-2xl relative overflow-hidden">
                        <p className="text-[8px] text-emerald-400 uppercase tracking-widest font-black">
                            PROYEKSI HPP KAIN MENTAH (GREY FABRIC)
                        </p>
                        <h3 className="text-xl font-black text-white mt-1">
                            Rp{" "}
                            {parseInt(fabricHppMeter).toLocaleString("id-ID")}{" "}
                            <span className="text-[10px] text-gray-400 font-normal">
                                / Meter
                            </span>
                        </h3>
                        <p className="text-[8px] text-gray-400 mt-2">
                            Total Modal Serat Kapas Impor + Logistik Priok:{" "}
                            <strong className="text-gray-300">
                                Rp{" "}
                                {parseInt(rawMaterialCostKg).toLocaleString(
                                    "id-ID",
                                )}{" "}
                                / Kg
                            </strong>
                        </p>
                    </div>

                    <div className="text-[8px] text-gray-500 italic text-right uppercase tracking-wider">
                        *Logistics, Yarn Count & Raw Grey Weaving Formulations
                        Verified By PT. Digestex Engineering Stream
                    </div>
                </div>
            </div>
        </div>
    );
}
