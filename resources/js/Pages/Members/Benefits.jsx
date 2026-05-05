import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";

export default function Benefits({ auth }) {
    const isEn = auth.locale === "en";

    const benefits = [
        {
            icon: "fa-certificate",
            title: isEn ? "Verified Gold Badge" : "Lencana Verified Gold",
            desc: isEn
                ? "Boost global buyer trust with an official API Jakarta validation badge."
                : "Tingkatkan kepercayaan buyer global dengan lencana validasi resmi API Jakarta.",
        },
        {
            icon: "fa-chart-line",
            title: isEn ? "Market Intelligence" : "Intelijen Pasar",
            desc: isEn
                ? "Full access to importer data, US export roadmaps, and tariff quota analysis."
                : "Akses penuh data importir, peta jalan ekspor AS, dan analisis kuota tarif.",
        },
        {
            icon: "fa-shield-alt",
            title: isEn ? "Strategic Advocacy" : "Advokasi Strategis",
            desc: isEn
                ? "Direct bridge to Kemendag & US Embassy for industry protection and lobbying."
                : "Jembatan langsung ke Kemendag & US Embassy untuk perlindungan industri dan lobi.",
        },
    ];

    return (
        <AuthenticatedLayout>
            {/* Letakkan tombol tutup di sini */}
            <div className="max-w-7xl mx-auto px-6 lg:px-8 pt-10">
                <Link
                    href={route("home")}
                    className="inline-flex items-center gap-3 text-gray-500 hover:text-yellow-500 transition-all group"
                >
                    <div className="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center group-hover:border-yellow-500/50 group-hover:bg-yellow-500/10 transition-all">
                        <i className="fas fa-times"></i>
                    </div>
                    <span className="text-[10px] font-black uppercase tracking-[0.3em]">
                        {isEn ? "Close Hub" : "Tutup Dashboard"}
                    </span>
                </Link>
            </div>

            {/* Sisa kode konten Benefits Bapak di bawahnya... */}
            <Head title={isEn ? "Membership Benefits" : "Keuntungan Anggota"} />
            <div className="py-12 bg-[#0a192f] min-h-screen text-white">
                <div className="max-w-7xl mx-auto px-6 lg:px-8">
                    <h1 className="text-4xl font-black italic uppercase tracking-tighter mb-10 text-yellow-500">
                        {isEn ? "Exclusive Benefits" : "Keuntungan Eksklusif"}
                    </h1>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                        {benefits.map((item, index) => (
                            <div
                                key={index}
                                className="bg-white/5 border border-white/10 p-8 rounded-[40px] hover:border-yellow-500/50 transition-all group"
                            >
                                <div className="w-14 h-14 bg-yellow-500 rounded-2xl flex items-center justify-center mb-6 shadow-[0_0_20px_rgba(234,179,8,0.3)] group-hover:scale-110 transition-transform">
                                    <i
                                        className={`fas ${item.icon} text-[#0a192f] text-2xl`}
                                    ></i>
                                </div>
                                <h3 className="text-lg font-black uppercase italic mb-4">
                                    {item.title}
                                </h3>
                                <p className="text-gray-400 text-sm leading-relaxed font-light italic">
                                    {item.desc}
                                </p>
                            </div>
                        ))}
                    </div>

                    <div className="mt-12 p-8 bg-yellow-500/10 border border-yellow-500/20 rounded-[40px] flex flex-col md:flex-row items-center justify-between gap-6">
                        <p className="text-xs font-bold uppercase tracking-widest text-yellow-500">
                            {isEn
                                ? "Your membership status: "
                                : "Status keanggotaan Anda: "}
                            <span className="underline">
                                {auth.user.membership_type || "Standard"}
                            </span>
                        </p>
                        <button className="bg-yellow-500 text-[#0a192f] px-10 py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-xl">
                            {isEn ? "Upgrade to Gold" : "Aktivasi Member Gold"}
                        </button>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
