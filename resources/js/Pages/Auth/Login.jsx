import { useEffect, useState } from "react"; // Tambahkan useState
import GuestLayout from "@/Layouts/GuestLayout";
import InputError from "@/Components/InputError";
import { Head, Link, useForm, usePage } from "@inertiajs/react"; // Tambahkan usePage
import PricingSection from "@/Components/Home/PricingSection";

export default function Login({ status, canResetPassword }) {
    const { props } = usePage();
    const isEn = props.locale === "en";
    const [showPricing, setShowPricing] = useState(false);

    // Gunakan login_identity agar cocok dengan Laravel Fortify/Breeze kustom Bapak
    const { data, setData, post, processing, errors, reset } = useForm({
        login_identity: "",
        password: "",
        remember: false,
    });

    useEffect(() => {
        return () => {
            reset("password");
        };
    }, []);

    const submit = (e) => {
        e.preventDefault();
        post(route("login"));
    };

    return (
        <GuestLayout>
            <Head title="Log in Intelligence Console" />

            <div className="mb-8 text-center">
                <h2 className="text-2xl font-black uppercase italic text-white tracking-tighter">
                    Access <span className="text-yellow-500">Big Data</span>
                </h2>
                <p className="text-[10px] font-bold text-gray-500 uppercase tracking-[0.3em] mt-2">
                    Enter your credentials to continue
                </p>
            </div>

            {/* 🛡️ NOTIFIKASI GERBANG AKSES MANDIRI BERBASIS NOMOR ANGGOTA ASOSIASI */}
            <div className="mb-6 bg-gradient-to-r from-amber-500/10 to-yellow-600/5 border border-amber-500/20 p-4 rounded-2xl shadow-xl backdrop-blur-sm space-y-1">
                <p className="text-[9px] font-black uppercase text-amber-400 tracking-widest flex items-center justify-center gap-1.5">
                    <i className="fas fa-id-card"></i>
                    {isEn
                        ? "Association Member Access Routing"
                        : "Gerbang Validasi Anggota Asosiasi"}
                </p>
                {/* Mengubah warna dari text-gray-300 menjadi text-white tebal */}
                <p className="text-white text-[11px] text-center leading-relaxed font-sans font-bold">
                    {isEn
                        ? "Active members can directly log in using your official Association Membership ID (e.g., 1241/0006.P) as your login identity."
                        : "Anggota aktif dapat langsung masuk menggunakan Nomor Anggota Resmi Asosiasi Anda (Contoh: 1241/0006.P) pada kolom identitas."}
                </p>
            </div>

            {status && (
                <div className="mb-4 text-sm font-medium text-green-500 italic">
                    {status}
                </div>
            )}

            <form onSubmit={submit} className="space-y-6">
                <div>
                    <label className="text-[10px] font-black uppercase text-gray-400 tracking-widest block mb-2">
                        Email address / Member ID
                    </label>
                    <input
                        type="text"
                        name="login_identity"
                        value={data.login_identity}
                        onChange={(e) =>
                            setData("login_identity", e.target.value)
                        }
                        className="w-full bg-white border border-gray-300 rounded-2xl py-4 px-6 text-black font-bold focus:ring-2 focus:ring-yellow-500 outline-none transition-all"
                        placeholder="example@mail.com / 1241/0006.P"
                        required
                        autoFocus
                    />
                    {/* Menampilkan pesan error dari server jika ada */}
                    {errors.login_identity && (
                        <p className="text-red-500 text-xs mt-2 italic font-bold">
                            {errors.login_identity}
                        </p>
                    )}
                </div>

                <div>
                    <label className="text-[10px] font-black uppercase text-gray-400 tracking-widest block mb-2">
                        Security Password
                    </label>
                    <input
                        type="password"
                        name="password"
                        value={data.password}
                        onChange={(e) => setData("password", e.target.value)}
                        className="w-full bg-white border border-gray-300 rounded-2xl py-4 px-6 text-black font-bold focus:ring-2 focus:ring-yellow-500 outline-none transition-all"
                        placeholder="••••••••"
                        required
                    />
                    <InputError message={errors.password} className="mt-2" />
                </div>

                <div className="mt-6">
                    <button
                        type="submit"
                        disabled={processing}
                        className="w-full bg-gradient-to-r from-yellow-500 to-yellow-600 text-[#0a192f] hover:from-yellow-400 hover:to-yellow-500 px-10 py-4 rounded-2xl font-black uppercase text-[11px] tracking-[0.2em] shadow-[0_10px_20px_rgba(234,179,8,0.3)] transition-all active:scale-[0.98] disabled:opacity-50"
                    >
                        {processing
                            ? isEn
                                ? "Initializing..."
                                : "Memproses..."
                            : isEn
                              ? "Authorize Access"
                              : "Otorisasi Akses"}
                    </button>
                </div>

                {/* 2. Divider */}
                <div className="relative my-10">
                    <div className="absolute inset-0 flex items-center">
                        <span className="w-full border-t border-white/10"></span>
                    </div>
                    <div className="relative flex justify-center text-[10px] uppercase font-black tracking-[0.3em]">
                        <span className="px-4 bg-[#0a192f] text-gray-300">
                            {isEn ? "Secure Gateway" : "Gerbang Aman"}
                        </span>
                    </div>
                </div>

                {/* 3. Tombol Google */}
                <a
                    href="/auth/google"
                    className="flex items-center justify-center w-full px-4 py-4 space-x-3 bg-white/[0.03] border border-white/10 rounded-2xl hover:bg-white/[0.08] hover:border-white/20 transition-all duration-300 group"
                >
                    <div className="bg-white p-1.5 rounded-lg shadow-sm group-hover:scale-110 transition-transform">
                        <img
                            src="https://www.google.com/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png"
                            alt="Google Logo"
                            className="w-auto h-6"
                        />
                    </div>
                    <span className="text-white font-black text-[10px] uppercase tracking-[0.15em] group-hover:text-yellow-400 transition-colors duration-300">
                        {isEn
                            ? "Continue with Google"
                            : "Lanjutkan dengan Google"}
                    </span>
                </a>

                {/* 4. Keterangan Keamanan */}
                {/* Jarak mt-4 dan pt-4 membuat teks ini naik setengah dari sebelumnya */}
                <div className="mt-4 flex flex-col items-center gap-3 border-t border-white/5 pt-4">
                    <div className="flex items-center gap-2 px-3 py-1 bg-green-500/10 rounded-full border border-green-500/20">
                        <div className="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></div>
                        <span className="text-[8px] font-black uppercase tracking-[0.2em] text-green-500">
                            {isEn ? "System Encrypted" : "Sistem Terenkripsi"}
                        </span>
                    </div>
                    <div className="flex items-center gap-2 opacity-40">
                        <svg
                            className="w-3 h-3 text-gray-400"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path d="M10 2a5 5 0 00-5 5v2a2 2 0 00-2 2v5a2 2 0 002 2h10a2 2 0 002-2v-5a2 2 0 00-2-2V7a5 5 0 00-5-5zM7 7a3 3 0 016 0v2H7V7z" />
                        </svg>
                        <span className="text-[8px] font-black uppercase tracking-[0.25em] text-gray-900">
                            {isEn
                                ? "Bank-Grade AES-256 Protocol"
                                : "Protokol AES-256 Standar Bank"}
                        </span>
                    </div>
                </div>
            </form>

            {/* SEKSI DAFTAR / PRICING */}
            <div className="mt-5 text-center border-t border-white/10 pt-4">
                {/* Mengubah warna dari text-gray-800 menjadi text-gray-300 yang tajam memancar */}
                <p className="text-gray-300 text-[10px] font-black uppercase tracking-widest mb-1.5">
                    {isEn ? "Don't have an account?" : "Belum punya akun?"}
                </p>
                <Link
                    href={route("pricing.index")}
                    className="text-yellow-500 hover:text-white font-black uppercase italic text-xs tracking-tighter transition-all underline decoration-yellow-500/30 block hover:scale-105 duration-300"
                >
                    {isEn
                        ? "Register & See Pricing →"
                        : "Daftar & Lihat Paket Premium →"}
                </Link>
            </div>
        </GuestLayout>
    );
}
