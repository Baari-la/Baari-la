import React from "react";

export default function PricingSection({ isEn }) {
    const plans = [
        {
            name: "STANDARD MEMBER",
            price: "IDR 6.000.000",
            period: isEn ? "/ year" : "/ tahun",
            target: isEn ? "Official API Member" : "Anggota Resmi API Jakarta",
            features: isEn
                ? [
                      "Full Industry Intelligence Access",
                      "Official Regulatory PDF Vault",
                      "Verified Member Directory",
                      "National Association Voting Rights",
                  ]
                : [
                      "Akses Intelijen Industri Penuh",
                      "Gudang PDF Regulasi Resmi",
                      "Direktori Anggota Terverifikasi",
                      "Hak Suara Resmi Asosiasi",
                  ],
            buttonText: isEn ? "Register via API" : "Daftar via API",
            link: "https://wa.me/629928939", // Tambahkan link di sini
            premium: false,
        },
        {
            name: "PREMIUM INTELLIGENCE",
            price: "IDR 4.500.000",
            period: isEn ? "/ year" : "/ tahun",
            target: "Digestex Digital Suite",
            features: isEn
                ? [
                      "AI Business Matchmaking Hub",
                      "Real-time Market Radar (Cotton Index)",
                      "Global Partner Brand Exposure",
                      "Priority Technical Support",
                      "Executive Strategic Analytics",
                  ]
                : [
                      "Pusat AI Business Matchmaking",
                      "Radar Pasar Real-time (Indeks Kapas)",
                      "Eksposur Brand Partner Global",
                      "Dukungan Teknis Prioritas",
                      "Analisis Strategis Eksekutif",
                  ],
            buttonText: isEn ? "Upgrade to Premium" : "Tingkatkan ke Premium",
            link: "https://wa.me/629928939", // Contoh link internal/eksternal
            premium: true,
        },
    ];

    return (
        <section className="py-24 bg-[#0a192f] border-t border-white/5">
            <div className="max-w-7xl mx-auto px-6 lg:px-8">
                <div className="text-center mb-16">
                    <h2 className="text-4xl md:text-5xl font-black text-white uppercase tracking-tighter italic mb-4">
                        Investment{" "}
                        <span className="text-yellow-500">Structure</span>
                    </h2>
                    <p className="text-gray-400 text-[10px] uppercase tracking-[0.4em] font-bold">
                        {isEn
                            ? "Strategic access for industrial growth"
                            : "Akses strategis untuk pertumbuhan industri"}
                    </p>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-10 max-w-5xl mx-auto">
                    {plans.map((plan, idx) => (
                        <div
                            key={idx}
                            className={`relative p-[1px] rounded-[45px] ${
                                plan.premium
                                    ? "bg-gradient-to-br from-yellow-600 via-yellow-200 to-yellow-700 shadow-[0_0_50px_rgba(234,179,8,0.1)]"
                                    : "bg-white/10"
                            }`}
                        >
                            <div className="bg-[#0a192f] rounded-[42px] p-10 h-full flex flex-col relative overflow-hidden">
                                {plan.premium && (
                                    <div className="absolute top-0 right-0 p-8 opacity-5">
                                        <i className="fas fa-crown text-[120px] -rotate-12"></i>
                                    </div>
                                )}

                                <div className="mb-8">
                                    <span
                                        className={`text-[9px] font-black uppercase tracking-[0.3em] ${plan.premium ? "text-yellow-500" : "text-gray-400"}`}
                                    >
                                        {plan.target}
                                    </span>
                                    <h3 className="text-2xl font-black text-white uppercase mt-2 tracking-tight">
                                        {plan.name}
                                    </h3>
                                </div>

                                <div className="mb-10">
                                    <span className="text-4xl font-black text-white tracking-tighter">
                                        {plan.price}
                                    </span>
                                    <span className="text-gray-500 text-[10px] uppercase font-bold ml-2 tracking-widest">
                                        {plan.period}
                                    </span>
                                </div>

                                <ul className="space-y-5 mb-12 flex-grow">
                                    {plan.features.map((feature, fIdx) => (
                                        <li
                                            key={fIdx}
                                            className="flex items-start gap-4 text-xs text-gray-300 font-bold uppercase tracking-wide leading-relaxed"
                                        >
                                            <i
                                                className={`fas fa-check-circle mt-0.5 ${plan.premium ? "text-yellow-500" : "text-blue-500"}`}
                                            ></i>
                                            {feature}
                                        </li>
                                    ))}
                                </ul>

                                {/* TOMBOL DISINI */}
                                <a
                                    href={plan.link}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className={`w-full py-5 rounded-2xl font-black uppercase tracking-widest text-center text-[10px] transition-all duration-500 block
                                    ${
                                        plan.premium
                                            ? "bg-yellow-500 text-[#0a192f] hover:scale-[1.02] shadow-[0_15px_30px_rgba(234,179,8,0.25)]"
                                            : "bg-white/5 text-white border border-white/10 hover:bg-white/10"
                                    }`}
                                >
                                    {plan.buttonText}
                                </a>

                                {plan.premium && (
                                    <p className="text-center mt-6 text-[8px] text-yellow-500/50 font-black uppercase tracking-widest italic">
                                        *{" "}
                                        {isEn
                                            ? "Requires active API Membership"
                                            : "Membutuhkan Keanggotaan API aktif"}
                                    </p>
                                )}
                            </div>
                        </div>
                    ))}
                </div>

                <div className="mt-20 text-center">
                    <p className="text-gray-500 text-[9px] font-bold uppercase tracking-[0.2em]">
                        {isEn
                            ? "Secure payment processed via API Jakarta"
                            : "Pembayaran aman diproses melalui API Jakarta"}
                    </p>
                </div>
            </div>
        </section>
    );
}
