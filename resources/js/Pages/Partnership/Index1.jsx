import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, usePage } from "@inertiajs/react"; // Tambahkan usePage

export default function Partnership() {
    // Ambil locale yang sedang aktif dari props auth
    const { auth } = usePage().props;
    const isEn = auth.locale === "en";

    return (
        <AuthenticatedLayout
            header={
                <h2 className="font-black text-xl text-[#0a192f] uppercase tracking-tighter">
                    {isEn
                        ? "Strategic Global Partnership"
                        : "Kemitraan Strategis Global"}
                </h2>
            }
        >
            <Head
                title={
                    isEn
                        ? "Global Synergy - Digestex V2"
                        : "Sinergi Global - Digestex V2"
                }
            />

            <div className="py-12 bg-[#0a192f] min-h-screen text-white">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-12">
                    {/* HERO SECTION */}
                    <div className="text-center space-y-6 py-10">
                        <span className="bg-yellow-500 text-[#0a192f] px-6 py-1 rounded-full text-[10px] font-black uppercase tracking-[0.3em]">
                            Global Synergy Program
                        </span>
                        <h1 className="text-5xl md:text-7xl font-black uppercase tracking-tighter leading-none italic">
                            {isEn ? "Access Indonesia's" : "Akses Jaringan"}{" "}
                            <br />
                            <span className="text-yellow-500 text-6xl md:text-8xl">
                                {isEn ? "Textile Network" : "Tekstil Indonesia"}
                            </span>
                        </h1>
                        <p className="text-gray-400 text-lg max-w-3xl mx-auto font-light leading-relaxed italic border-y border-white/5 py-6">
                            {isEn
                                ? '"Connecting global technology innovation with hundreds of leading textile manufacturers in Indonesia through a centralized data intelligence platform."'
                                : '"Menghubungkan inovasi teknologi global dengan ratusan manufaktur tekstil terbesar di Indonesia melalui platform intelijen data yang terpusat."'}
                        </p>
                    </div>

                    {/* VALUE PROPOSITION GRID */}
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                        {/* Access */}
                        <div className="bg-white/5 p-10 rounded-[50px] border border-white/10 hover:border-yellow-500/30 transition group">
                            <div className="text-yellow-500 text-4xl mb-6">
                                <i className="fas fa-network-wired"></i>
                            </div>
                            <h4 className="text-xl font-black mb-4 uppercase tracking-tighter">
                                {isEn
                                    ? "Direct Factory Access"
                                    : "Akses Langsung Pabrik"}
                            </h4>
                            <p className="text-gray-500 text-xs leading-relaxed uppercase tracking-widest font-bold">
                                {isEn
                                    ? "Directly reach over 100+ active textile companies and key policymakers in Indonesia."
                                    : "Akses langsung ke database 100+ perusahaan pertekstilan aktif di Indonesia."}
                            </p>
                        </div>

                        {/* Intelligence */}
                        <div className="bg-white/5 p-10 rounded-[50px] border border-white/10 hover:border-yellow-500/30 transition group">
                            <div className="text-yellow-500 text-4xl mb-6">
                                <i className="fas fa-microchip"></i>
                            </div>
                            <h4 className="text-xl font-black mb-4 uppercase tracking-tighter">
                                {isEn
                                    ? "Tech Integration"
                                    : "Integrasi Teknologi"}
                            </h4>
                            <p className="text-gray-500 text-xs leading-relaxed uppercase tracking-widest font-bold">
                                {isEn
                                    ? "Integrate your PLM, IoT, or ERP solutions directly into our members' executive dashboard."
                                    : "Integrasikan solusi PLM, IoT, atau ERP Anda langsung ke dalam dashboard anggota kami."}
                            </p>
                        </div>

                        {/* Market Entry */}
                        <div className="bg-white/5 p-10 rounded-[50px] border border-white/10 hover:border-yellow-500/30 transition group">
                            <div className="text-yellow-500 text-4xl mb-6">
                                <i className="fas fa-rocket"></i>
                            </div>
                            <h4 className="text-xl font-black mb-4 uppercase tracking-tighter">
                                {isEn
                                    ? "Market Entry Support"
                                    : "Dukungan Penetrasi Pasar"}
                            </h4>
                            <p className="text-gray-500 text-xs leading-relaxed uppercase tracking-widest font-bold">
                                {isEn
                                    ? "Utilize the Digestex ecosystem as your bridge for technology expansion from US & China."
                                    : "Gunakan ekosistem kami sebagai jembatan ekspansi teknologi US & China ke Indonesia."}
                            </p>
                        </div>
                    </div>

                    {/* CALL TO ACTION */}
                    <div className="bg-yellow-500 p-12 rounded-[60px] flex flex-col md:flex-row justify-between items-center gap-8 shadow-2xl">
                        <div className="text-[#0a192f] space-y-2 text-center md:text-left">
                            <h3 className="text-3xl font-black uppercase tracking-tighter">
                                {isEn
                                    ? "Ready to Build Synergy?"
                                    : "Siap Berkolaborasi?"}
                            </h3>
                            <p className="font-bold text-sm uppercase opacity-70">
                                {isEn
                                    ? "Become a Strategic Technology Partner of Digestex V2 Today."
                                    : "Jadilah Mitra Teknologi Strategis Digestex V2 Hari Ini."}
                            </p>
                        </div>
                        <a
                            href="mailto:partnership@digestexmedia.com"
                            className="bg-[#0a192f] text-white px-12 py-5 rounded-full font-black uppercase text-xs tracking-[0.2em] hover:scale-105 transition shadow-2xl"
                        >
                            {isEn
                                ? "Contact Our Synergy Team"
                                : "Hubungi Tim Sinergi Kami"}
                        </a>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
