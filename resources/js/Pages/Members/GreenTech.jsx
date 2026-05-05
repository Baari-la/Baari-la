import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";
import { useState, useEffect } from "react";

export default function GreenTech({ auth }) {
    const isEn = auth.locale === "en";
    const [showWelcome, setShowWelcome] = useState(false);
    // Efek pop-up muncul 1 detik setelah halaman terbuka
    useEffect(() => {
        const timer = setTimeout(() => setShowWelcome(true), 1000);
        return () => clearTimeout(timer);
    }, []);
    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title={isEn ? "Green Tech Hub" : "Pusat Teknologi Hijau"} />
            {/* Tambahan Welcome message */}
            {/* POP-UP WELCOME MESSAGE */}
            {showWelcome && (
                <div className="fixed inset-0 z-[60] flex items-center justify-center px-6">
                    <div
                        className="absolute inset-0 bg-[#0a192f]/80 backdrop-blur-sm"
                        onClick={() => setShowWelcome(false)}
                    ></div>
                    <div className="bg-white rounded-[40px] p-10 max-w-lg w-full relative z-10 shadow-2xl border border-blue-100 animate-in fade-in zoom-in duration-300">
                        <div className="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mb-6 shadow-xl">
                            <i className="fas fa-handshake text-white text-2xl"></i>
                        </div>
                        <h2 className="text-2xl font-black uppercase italic text-[#0a192f] mb-4">
                            {isEn
                                ? "A Strategic Partnership"
                                : "Kemitraan Strategis"}
                        </h2>
                        <p className="text-gray-500 text-sm leading-relaxed mb-8 font-light italic">
                            {isEn
                                ? "Welcome to our Green Technology Hub. We are honored to collaborate with Epson in driving sustainable innovation for Indonesia's textile industry."
                                : "Selamat datang di Green Technology Hub. Kami merasa terhormat dapat berkolaborasi dengan Epson dalam mendorong inovasi berkelanjutan bagi industri tekstil Indonesia."}
                        </p>
                        <button
                            onClick={() => setShowWelcome(false)}
                            className="w-full bg-[#0a192f] text-white py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-blue-600 transition-all"
                        >
                            {isEn ? "Enter Hub" : "Masuk ke Pusat Teknologi"}
                        </button>
                    </div>
                </div>
            )}
            {/* Batas */}
            <div className="py-12 bg-white min-h-screen">
                <div className="max-w-7xl mx-auto px-6 lg:px-8">
                    {/* WELCOME SECTION FOR EPSON */}
                    <div className="flex flex-col md:flex-row justify-between items-end mb-16 gap-8">
                        <div className="md:w-2/3">
                            <span className="text-blue-600 font-black text-xs uppercase tracking-[0.4em] mb-4 block">
                                {isEn
                                    ? "Strategic Technology Partnership"
                                    : "Kemitraan Teknologi Strategis"}
                            </span>
                            <h1 className="text-5xl md:text-7xl font-black text-[#0a192f] leading-none tracking-tighter uppercase italic">
                                Green{" "}
                                <span className="text-blue-600">
                                    Technology
                                </span>{" "}
                                Hub
                            </h1>
                            <p className="mt-6 text-gray-500 text-lg font-light leading-relaxed max-w-2xl italic whitespace-normal">
                                {isEn
                                    ? "Accelerating Indonesia's textile transformation through sustainable digital precision. In collaboration with global technology leaders."
                                    : "Akselerasi transformasi tekstil Indonesia melalui presisi digital yang berkelanjutan. Berkolaborasi dengan pemimpin teknologi global."}
                            </p>
                        </div>
                        <div className="md:w-1/3 flex justify-end">
                            <img
                                src="/images/epson_logo.png"
                                className="h-12 w-auto grayscale hover:grayscale-0 transition-all opacity-50 hover:opacity-100"
                                alt="Epson"
                            />
                        </div>
                    </div>

                    {/* CORE VALUES (Standard Jepang: Efisiensi & Ekologi) */}
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-1 shadow-2xl rounded-[40px] overflow-hidden border border-gray-100 mb-20">
                        {/* VALUE 1 */}
                        <div className="bg-[#0a192f] p-12 text-white">
                            <i className="fas fa-tint-slash text-blue-400 text-3xl mb-6"></i>
                            <h3 className="font-black uppercase italic mb-4">
                                {isEn ? "Zero Water Waste" : "Nol Limbah Air"}
                            </h3>
                            <p className="text-sm text-gray-400 font-light leading-relaxed">
                                {isEn
                                    ? "Reducing water consumption by up to 90% compared to conventional dyeing methods."
                                    : "Mereduksi penggunaan air hingga 90% dibandingkan metode pewarnaan konvensional."}
                            </p>
                        </div>

                        {/* VALUE 2 */}
                        <div className="bg-blue-600 p-12 text-white">
                            <i className="fas fa-leaf text-white text-3xl mb-6"></i>
                            <h3 className="font-black uppercase italic mb-4">
                                {isEn
                                    ? "Eco-Certified"
                                    : "Tersertifikasi Ekologi"}
                            </h3>
                            <p className="text-sm text-blue-100 font-light leading-relaxed">
                                {isEn
                                    ? "Sustainable inks and processes meeting global certification standards (OEKO-TEX)."
                                    : "Tinta dan proses yang ramah lingkungan, memenuhi standar sertifikasi global (OEKO-TEX)."}
                            </p>
                        </div>

                        {/* VALUE 3 */}
                        <div className="bg-gray-50 p-12 text-[#0a192f]">
                            <i className="fas fa-microchip text-blue-600 text-3xl mb-6"></i>
                            <h3 className="font-black uppercase italic mb-4">
                                {isEn ? "Digital Precision" : "Presisi Digital"}
                            </h3>
                            <p className="text-sm text-gray-500 font-light leading-relaxed">
                                {isEn
                                    ? "High-fidelity color accuracy with shorter production cycles and cost-efficient outputs."
                                    : "Akurasi warna tinggi dengan proses produksi yang lebih pendek dan efisien secara biaya."}
                            </p>
                        </div>
                    </div>

                    {/* CALL TO ACTION UNTUK ANGGOTA */}
                    <div className="bg-gray-100 rounded-[50px] p-12 flex flex-col md:flex-row items-center justify-between gap-8 border border-gray-200 shadow-sm">
                        <div>
                            <h2 className="text-2xl font-black uppercase italic text-[#0a192f] mb-2">
                                {isEn
                                    ? "Ready to Switch to Green Tech?"
                                    : "Siap Beralih ke Teknologi Hijau?"}
                            </h2>
                            <p className="text-gray-500 text-sm italic">
                                {isEn
                                    ? "Get exclusive consultations and special Epson machinery offers for API Jakarta members."
                                    : "Dapatkan konsultasi eksklusif dan penawaran khusus mesin Epson untuk Anggota API Jakarta."}
                            </p>
                        </div>
                        <button className="bg-[#0a192f] text-white px-10 py-5 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-blue-600 transition-all shadow-xl active:scale-95">
                            {isEn
                                ? "Contact Account Manager"
                                : "Hubungi Account Manager"}
                        </button>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
