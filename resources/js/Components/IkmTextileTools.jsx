import React, { useState } from "react";
import {
    Scissors,
    DollarSign,
    RefreshCw,
    Layers,
    TrendingUp,
} from "lucide-react";

export default function IkmTextileTools() {
    // STATE TOOL 1: Fabric Yield Calculator
    const [targetPcs, setTargetPcs] = useState(100);
    const [clothingType, setClothingType] = useState("tshirt"); // tshirt, hoodie, kemeja
    const [calculatedFabricKg, setCalculatedFabricKg] = useState(25);

    // STATE TOOL 2: HPP Konfeksi Engine
    const [fabricCostPerKg, setFabricCostPerKg] = useState(95000);
    const [sewingWage, setSewingWage] = useState(7000);
    const [accCost, setAccCost] = useState(3000); // kancing, plastik, label
    const [ikmMargin, setIkmMargin] = useState(25);
    const [hppPerPcs, setHppPerPcs] = useState(0);
    const [suggestedRetailPrice, setSuggestedRetailPrice] = useState(0);

    // ACTION TOOL 1: Hitung Kebutuhan Bahan Kain
    const calculateFabricNeeded = () => {
        let consumptionPerPcs = 0.25; // Default Kaos oblong dewasa (250 gram)
        if (clothingType === "hoodie") consumptionPerPcs = 0.65; // Hoodie tebal (650 gram)
        if (clothingType === "shirt") consumptionPerPcs = 0.35; // Kemeja katun (350 gram)

        const totalKg = parseInt(targetPcs || 0) * consumptionPerPcs;
        setCalculatedFabricKg(totalKg);
    };

    // ACTION TOOL 2: Hitung HPP & Harga Jual Konfeksi
    const calculateIkmPricing = () => {
        let consumptionPerPcs = 0.25;
        if (clothingType === "hoodie") consumptionPerPcs = 0.65;
        if (clothingType === "shirt") consumptionPerPcs = 0.35;

        // Modal kain per helai baju
        const baseFabricCost =
            consumptionPerPcs * parseFloat(fabricCostPerKg || 0);
        // Total Modal HPP
        const totalHpp =
            baseFabricCost +
            parseFloat(sewingWage || 0) +
            parseFloat(accCost || 0);
        setHppPerPcs(totalHpp);

        // Harga jual rekomendasi berdasarkan margin
        const sellingPrice = totalHpp * (1 + parseFloat(ikmMargin || 0) / 100);
        setSuggestedRetailPrice(sellingPrice);
    };

    return (
        <div className="bg-[#0b1329] p-6 lg:p-8 rounded-[35px] border border-white/5 shadow-2xl text-gray-100 font-mono">
            {/* Header Utama Section */}
            <div className="flex items-center gap-3 mb-6 border-b border-white/5 pb-4">
                <div className="w-9 h-9 rounded-xl bg-purple-500/20 flex items-center justify-center border border-purple-500/30">
                    <Scissors className="w-5 h-5 text-purple-400" />
                </div>
                <div>
                    <h4 className="text-xs font-black uppercase tracking-widest text-white">
                        IKM Tekstil & Konfeksi Productivity Tools
                    </h4>
                    <p className="text-[8px] text-gray-500 font-mono mt-0.5 uppercase tracking-wider">
                        Sovereign Micro-Business Optimization Modules - PT.
                        DIGESTEX MEDIA UTAMA
                    </p>
                </div>
            </div>

            {/* Layout Split Dua Kolom Alat Hitung */}
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {/* TOOL 1: FABRIC YIELD CALCULATOR */}
                <div className="bg-white/5 p-5 rounded-2xl border border-white/5 flex flex-col justify-between">
                    <div>
                        <span className="text-[9px] font-black text-purple-400 uppercase tracking-widest flex items-center gap-1.5 mb-4">
                            <Layers className="w-3.5 h-3.5" /> 1. Fabric Yield
                            Calculator (Kebutuhan Kain)
                        </span>
                        <div className="space-y-3">
                            <div>
                                <label className="text-[8px] text-gray-400 uppercase block mb-1">
                                    Target Jumlah Produksi (Pcs)
                                </label>
                                <input
                                    type="number"
                                    value={targetPcs}
                                    onChange={(e) =>
                                        setTargetPcs(e.target.value)
                                    }
                                    className="w-full bg-[#0b1329] border border-white/10 rounded-xl px-3 py-1.5 text-xs text-white outline-none focus:border-purple-500 font-bold"
                                />
                            </div>
                            <div>
                                <label className="text-[8px] text-gray-400 uppercase block mb-1">
                                    Jenis Item Pakaian
                                </label>
                                <select
                                    value={clothingType}
                                    onChange={(e) =>
                                        setClothingType(e.target.value)
                                    }
                                    className="w-full bg-[#0b1329] border border-white/10 rounded-xl px-2 py-1.5 text-xs text-white outline-none focus:border-purple-500 font-bold cursor-pointer"
                                >
                                    <option value="tshirt">
                                        Kaos Oblong Dewasa (Est. 250gr)
                                    </option>
                                    <option value="shirt">
                                        Kemeja Lengan Panjang (Est. 350gr)
                                    </option>
                                    <option value="hoodie">
                                        Hoodie / Jumper Tebal (Est. 650gr)
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div className="mt-4 pt-4 border-t border-white/5">
                        <button
                            onClick={calculateFabricNeeded}
                            className="w-full bg-purple-600 hover:bg-purple-700 text-white text-[10px] font-black uppercase tracking-wider py-2 rounded-xl transition-all flex items-center justify-center gap-1.5 cursor-pointer shadow-lg"
                        >
                            <RefreshCw className="w-3 h-3" /> Hitung Kebutuhan
                            Bahan
                        </button>
                        <div className="mt-3 bg-black/30 p-3 rounded-xl border border-white/5 flex justify-between items-center text-[10px]">
                            <span className="text-gray-400">
                                Estimasi Total Kain Yang Harus Dibeli:
                            </span>
                            <span className="text-purple-400 font-black text-xs">
                                {calculatedFabricKg} Kg{" "}
                                <span className="text-[8px] text-gray-500 font-normal">
                                    ({Math.ceil(calculatedFabricKg / 25)} Rol)
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                {/* TOOL 2: CLOTHING HPP & PRICING ENGINE */}
                <div className="bg-white/5 p-5 rounded-2xl border border-white/5 flex flex-col justify-between">
                    <div>
                        <span className="text-[9px] font-black text-emerald-400 uppercase tracking-widest flex items-center gap-1.5 mb-4">
                            <DollarSign className="w-3.5 h-3.5" /> 2. Clothing
                            HPP & Pricing Engine (Harga Jual)
                        </span>
                        <div className="grid grid-cols-2 gap-3 mb-3">
                            <div>
                                <label className="text-[8px] text-gray-400 uppercase block mb-1">
                                    Harga Kain Grosir (IDR/Kg)
                                </label>
                                <input
                                    type="number"
                                    value={fabricCostPerKg}
                                    onChange={(e) =>
                                        setFabricCostPerKg(e.target.value)
                                    }
                                    className="w-full bg-[#0b1329] border border-white/10 rounded-xl px-3 py-1.5 text-xs text-white outline-none focus:border-emerald-500 font-bold"
                                />
                            </div>
                            <div>
                                <label className="text-[8px] text-gray-400 uppercase block mb-1">
                                    Upah CMT / Joki Jahit (Pcs)
                                </label>
                                <input
                                    type="number"
                                    value={sewingWage}
                                    onChange={(e) =>
                                        setSewingWage(e.target.value)
                                    }
                                    className="w-full bg-[#0b1329] border border-white/10 rounded-xl px-3 py-1.5 text-xs text-white outline-none focus:border-emerald-500 font-bold"
                                />
                            </div>
                        </div>
                        <div className="grid grid-cols-2 gap-3">
                            <div>
                                <label className="text-[8px] text-gray-400 uppercase block mb-1">
                                    Aksesoris & Packaging (Pcs)
                                </label>
                                <input
                                    type="number"
                                    value={accCost}
                                    onChange={(e) => setAccCost(e.target.value)}
                                    className="w-full bg-[#0b1329] border border-white/10 rounded-xl px-3 py-1.5 text-xs text-white outline-none focus:border-emerald-500 font-bold"
                                />
                            </div>
                            <div>
                                <label className="text-[8px] text-gray-400 uppercase block mb-1">
                                    Target Margin Profit (%)
                                </label>
                                <input
                                    type="number"
                                    value={ikmMargin}
                                    onChange={(e) =>
                                        setIkmMargin(e.target.value)
                                    }
                                    className="w-full bg-[#0b1329] border border-white/10 rounded-xl px-3 py-1.5 text-xs text-white outline-none focus:border-emerald-500 font-bold"
                                />
                            </div>
                        </div>
                    </div>

                    <div className="mt-4 pt-4 border-t border-white/5">
                        <button
                            onClick={calculateIkmPricing}
                            className="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-black uppercase tracking-wider py-2 rounded-xl transition-all flex items-center justify-center gap-1.5 cursor-pointer shadow-lg"
                        >
                            <TrendingUp className="w-3 h-3" /> Kalkulasi Modal &
                            Harga Jual
                        </button>
                        <div className="mt-3 grid grid-cols-2 gap-2 text-[9px]">
                            <div className="bg-black/30 p-2.5 rounded-xl border border-white/5 flex flex-col">
                                <span className="text-gray-400 uppercase text-[7px]">
                                    HPP Modal Rill / Pcs
                                </span>
                                <span className="text-white font-black text-xs mt-0.5">
                                    Rp{" "}
                                    {parseInt(hppPerPcs).toLocaleString(
                                        "id-ID",
                                    )}
                                </span>
                            </div>
                            <div className="bg-emerald-500/10 p-2.5 rounded-xl border border-emerald-500/20 flex flex-col">
                                <span className="text-emerald-400 uppercase text-[7px] font-black">
                                    Rekomendasi Harga Jual
                                </span>
                                <span className="text-emerald-400 font-black text-xs mt-0.5">
                                    Rp{" "}
                                    {parseInt(
                                        suggestedRetailPrice,
                                    ).toLocaleString("id-ID")}
                                </span>
                                <input
                                    type="number"
                                    value={accCost}
                                    onChange={(e) => setAccCost(e.target.value)}
                                    className="w-full bg-[#0b1329] border border-white/10 rounded-xl px-3 py-1.5 text-xs text-white outline-none focus:border-emerald-500 font-bold"
                                />
                            </div>
                            <div>
                                <label className="text-[8px] text-gray-400 uppercase block mb-1">
                                    Target Margin Profit (%)
                                </label>
                                <input
                                    type="number"
                                    value={ikmMargin}
                                    onChange={(e) =>
                                        setIkmMargin(e.target.value)
                                    }
                                    className="w-full bg-[#0b1329] border border-white/10 rounded-xl px-3 py-1.5 text-xs text-white outline-none focus:border-emerald-500 font-bold"
                                />
                            </div>
                        </div>
                    </div>

                    <div className="mt-4 pt-4 border-t border-white/5">
                        <button
                            onClick={calculateIkmPricing}
                            className="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-black uppercase tracking-wider py-2 rounded-xl transition-all flex items-center justify-center gap-1.5 cursor-pointer shadow-lg"
                        >
                            <TrendingUp className="w-3 h-3" /> Kalkulasi Modal &
                            Harga Jual
                        </button>
                        <div className="mt-3 grid grid-cols-2 gap-2 text-[9px]">
                            <div className="bg-black/30 p-2.5 rounded-xl border border-white/5 flex flex-col">
                                <span className="text-gray-400 uppercase text-[7px]">
                                    HPP Modal Rill / Pcs
                                </span>
                                <span className="text-white font-black text-xs mt-0.5">
                                    Rp{" "}
                                    {parseInt(hppPerPcs).toLocaleString(
                                        "id-ID",
                                    )}
                                </span>
                            </div>
                            <div className="bg-emerald-500/10 p-2.5 rounded-xl border border-emerald-500/20 flex flex-col">
                                <span className="text-emerald-400 uppercase text-[7px] font-black">
                                    Rekomendasi Harga Jual
                                </span>
                                <span className="text-emerald-400 font-black text-xs mt-0.5">
                                    Rp{" "}
                                    {parseInt(
                                        suggestedRetailPrice,
                                    ).toLocaleString("id-ID")}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
