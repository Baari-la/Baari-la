import React, { useState, useEffect } from "react";
import { jsPDF } from "jspdf";
import { usePage, Link } from "@inertiajs/react";

const GarmentCalculator = () => {
    const { auth, locale } = usePage().props;
    const isEn = locale === "en";
    // Anggota API adalah yang punya is_api_member true
    const isAPI = auth.user && auth.user.is_api_member;
    // Premium adalah yang role-nya 'premium' ATAU Anggota API (karena Anggota API otomatis dapat akses premium)
    const isPremium = (auth.user && auth.user.role === "premium") || isAPI;

    const [showWelcome, setShowWelcome] = useState(false);
    const [targetPcs, setTargetPcs] = useState(1000);
    const [productType, setProductType] = useState("tshirt");
    const [results, setResults] = useState({
        fabric: 0,
        containers: 0,
        weight: 0,
    });

    const specs = {
        tshirt: { consumption: 0.25, weight: 0.18, pcsPerCtn: 60 },
        shirt: { consumption: 1.5, weight: 0.25, pcsPerCtn: 40 },
        jeans: { consumption: 1.4, weight: 0.7, pcsPerCtn: 20 },
    };

    // GANTI BLOK useEffect UNTUK WELCOME DENGAN INI:
    useEffect(() => {
        // Hanya tampilkan log dan welcome jika user SUDAH login
        if (auth.user) {
            console.log("Status Member API:", auth.user.is_api_member);
            console.log(
                "Nomor Anggota:",
                auth.user.member_number || "Bukan Anggota",
            );

            if (auth.user.is_api_member) {
                setShowWelcome(true);
                const timer = setTimeout(() => setShowWelcome(false), 5000);
                return () => clearTimeout(timer);
            }
        }
    }, [auth.user]); // Pantau perubahan pada data user

    // 2. Efek untuk Kalkulasi Otomatis
    useEffect(() => {
        const spec = specs[productType];
        const totalFabric = targetPcs * spec.consumption;
        const totalWeight = targetPcs * spec.weight;
        const totalCtn = Math.ceil(targetPcs / spec.pcsPerCtn);
        const container20ft = (totalCtn / 450).toFixed(2);

        setResults({
            fabric: totalFabric.toLocaleString(),
            weight: (totalWeight / 1000).toFixed(2),
            containers: container20ft,
        });
    }, [targetPcs, productType]);

    const generatePDF = () => {
        const doc = new jsPDF();
        doc.setFontSize(18);
        doc.text("DIGESTEX V2 - INDUSTRIAL PRODUCTION PLAN", 10, 20);
        doc.setFontSize(12);
        doc.text(
            `${isEn ? "Production Target" : "Target Produksi"}: ${targetPcs} Pcs`,
            10,
            30,
        );
        doc.text(
            `${isEn ? "Product Category" : "Kategori Produk"}: ${productType.toUpperCase()}`,
            10,
            40,
        );
        doc.line(10, 45, 200, 45);
        doc.text(
            isEn
                ? "MATERIAL & LOGISTICS ESTIMATION:"
                : "ESTIMASI MATERIAL & LOGISTIK:",
            10,
            55,
        );
        doc.text(
            `- ${isEn ? "Fabric Needs" : "Kebutuhan Bahan"}: ${results.fabric} ${productType === "tshirt" ? "Kg" : "Meters"}`,
            10,
            65,
        );
        doc.text(
            `- ${isEn ? "Total Shipping Weight" : "Total Berat Kirim"}: ${results.weight} Ton`,
            10,
            75,
        );
        doc.text(
            `- ${isEn ? "Container Planning" : "Rencana Kontainer"}: ${results.containers} X 20ft`,
            10,
            85,
        );
        doc.save(`Production_Plan_${productType}.pdf`);
    };

    return (
        <div className="relative">
            {/* POP-UP SAPAAN ISTIMEWA */}
            {showWelcome && (
                <div className="fixed top-24 left-1/2 -translate-x-1/2 z-[100] animate-bounce">
                    <div className="bg-blue-600 text-white px-8 py-3 rounded-full shadow-[0_0_30px_rgba(37,99,235,0.6)] border border-blue-400 flex items-center gap-3">
                        <span className="text-xl">🌟</span>
                        <p className="text-xs font-black uppercase tracking-tighter">
                            {isEn
                                ? "Welcome Member API Jakarta, Your Special Access is Active!"
                                : "Selamat Datang Anggota API Jakarta, Akses Istimewa Anda telah Aktif!"}
                        </p>
                    </div>
                </div>
            )}

            <div className="relative overflow-hidden bg-white/5 border border-white/10 p-8 rounded-3xl backdrop-blur-xl shadow-2xl mt-10">
                {/* OVERLAY UNTUK NON-PREMIUM */}
                {!isPremium && (
                    <div className="absolute inset-0 z-50 flex flex-col items-center justify-center bg-black/60 backdrop-blur-md p-8 text-center">
                        <div className="bg-yellow-500/20 p-4 rounded-full mb-4 shadow-[0_0_20px_rgba(234,179,8,0.4)]">
                            <span className="text-3xl">🔒</span>
                        </div>
                        <h4 className="text-white font-black uppercase italic text-xl mb-3 tracking-tighter">
                            {isEn ? "RESTRICTED ACCESS" : "AKSES TERBATAS"}
                        </h4>
                        <p className="text-slate-300 text-xs mb-6 max-w-xs leading-relaxed">
                            {isEn
                                ? "This professional tool is reserved for Premium Members. API Jakarta Members are eligible for Special Access."
                                : "Alat profesional ini khusus untuk Member Premium. Anggota API Jakarta berhak mendapatkan Akses Istimewa."}
                        </p>
                        <div className="flex flex-col sm:flex-row gap-3 w-full max-w-sm justify-center">
                            <Link
                                href={route("login")}
                                className="bg-white text-black px-6 py-3 rounded-xl text-[10px] font-black uppercase text-center hover:bg-gray-200 transition-all shadow-lg active:scale-95 flex-1"
                            >
                                {isEn
                                    ? "Login Member API Jakarta"
                                    : "Login Anggota API Jakarta"}
                            </Link>
                            <a
                                href="https://wa.me/628129928939"
                                target="_blank"
                                className="bg-yellow-500 text-black px-6 py-4 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-yellow-400 transition-all shadow-[0_0_20px_rgba(234,179,8,0.4)] text-center flex-1"
                            >
                                {isEn
                                    ? "Register as Premium"
                                    : "Daftar Premium"}
                            </a>
                        </div>
                    </div>
                )}

                {/* CONTENT AREA */}
                <div
                    className={
                        !isPremium
                            ? "blur-sm pointer-events-none grayscale select-none"
                            : ""
                    }
                >
                    <div className="flex items-center gap-3 mb-8">
                        <div className="p-3 bg-blue-600 rounded-2xl">
                            <span className="text-xl">🧮</span>
                        </div>
                        <div>
                            <h3 className="text-white font-black uppercase italic text-xl">
                                Premium{" "}
                                <span className="text-blue-500">
                                    Industrial Calculator
                                </span>
                            </h3>
                            <p className="text-slate-400 text-[10px] font-bold uppercase tracking-widest">
                                PLM & Logistics Simulation
                            </p>
                        </div>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div className="space-y-6">
                            <div>
                                <label className="text-slate-400 text-[10px] font-black uppercase mb-2 block italic">
                                    {isEn ? "Product Category" : "Jenis Produk"}
                                </label>
                                <select
                                    value={productType}
                                    onChange={(e) =>
                                        setProductType(e.target.value)
                                    }
                                    className="w-full bg-black/40 border border-white/10 rounded-xl p-3 text-white font-bold outline-none"
                                >
                                    <option value="tshirt">T-Shirt</option>
                                    <option value="shirt">Shirt</option>
                                    <option value="jeans">Jeans</option>
                                </select>
                            </div>
                            <div>
                                <label className="text-slate-400 text-[10px] font-black uppercase mb-2 block italic">
                                    {isEn
                                        ? "Target Quantity"
                                        : "Target Produksi"}
                                </label>
                                <input
                                    type="number"
                                    value={targetPcs}
                                    onChange={(e) =>
                                        setTargetPcs(e.target.value)
                                    }
                                    className="w-full bg-black/40 border border-white/10 rounded-xl p-3 text-white font-black text-2xl outline-none"
                                />
                            </div>
                        </div>

                        <div className="grid grid-cols-1 gap-4">
                            <div className="bg-blue-600/10 border border-blue-500/30 p-4 rounded-2xl">
                                <p className="text-blue-400 text-[9px] font-black uppercase mb-1">
                                    {isEn
                                        ? "Material Needs"
                                        : "Kebutuhan Bahan"}
                                </p>
                                <h4 className="text-white text-2xl font-black">
                                    {results.fabric}{" "}
                                    <span className="text-sm font-normal">
                                        {productType === "tshirt"
                                            ? "Kg"
                                            : "Meters"}
                                    </span>
                                </h4>
                            </div>
                            <div className="bg-emerald-600/10 border border-emerald-500/30 p-4 rounded-2xl">
                                <p className="text-emerald-400 text-[9px] font-black uppercase mb-1">
                                    {isEn
                                        ? "Logistics Simulation"
                                        : "Simulasi Logistik"}
                                </p>
                                <h4 className="text-white text-2xl font-black">
                                    {results.containers}{" "}
                                    <span className="text-sm font-normal">
                                        X 20ft Cont.
                                    </span>
                                </h4>
                            </div>
                        </div>
                    </div>

                    <button
                        onClick={generatePDF}
                        className="mt-8 w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-2xl shadow-lg flex items-center justify-center gap-3 transition-all"
                    >
                        <span className="text-xl">📥</span>{" "}
                        {isEn
                            ? "DOWNLOAD PRODUCTION PLAN"
                            : "UNDUH RENCANA PRODUKSI"}{" "}
                        (PDF)
                    </button>
                </div>
            </div>
        </div>
    );
};

export default GarmentCalculator;
