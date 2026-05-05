import { useEffect } from "react";
import Checkbox from "@/Components/Checkbox";
import GuestLayout from "@/Layouts/GuestLayout";
import InputError from "@/Components/InputError";
import PrimaryButton from "@/Components/PrimaryButton";
import { Head, Link, useForm } from "@inertiajs/react";

export default function Login({ status, canResetPassword }) {
    // 1. Inisialisasi form dengan 'login_identity' agar sinkron dengan LoginRequest.php
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

            {status && (
                <div className="mb-4 text-sm font-medium text-green-500 italic">
                    {status}
                </div>
            )}

            <form onSubmit={submit} className="space-y-6">
                {/* INPUT IDENTITY: EMAIL ATAU NOMOR ANGGOTA */}
                <div>
                    <label className="text-[10px] font-black uppercase text-gray-400 tracking-widest block mb-2">
                        Member ID / Email Address
                    </label>
                    <input
                        type="text"
                        onChange={(e) =>
                            setData("login_identity", e.target.value)
                        }
                        value={data.login_identity}
                        className="w-full bg-white border border-gray-300 rounded-2xl py-4 px-6 text-black font-bold focus:ring-2 focus:ring-yellow-500 outline-none transition-all"
                        placeholder="example@mail.com / 1640/0006.P"
                        required
                        autoFocus
                    />
                    {/* Tambahkan pesan error jika ada */}
                    {errors.login_identity && (
                        <div className="text-red-500 text-xs mt-1">
                            {errors.login_identity}
                        </div>
                    )}
                </div>

                {/* INPUT PASSWORD */}
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

                {/* TOMBOL LOGIN */}
                <div className="flex items-center justify-end mt-4">
                    <button
                        type="submit"
                        disabled={processing}
                        className="bg-yellow-500 text-[#0a192f] hover:bg-[#0a192f] hover:text-white px-10 py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-xl transition-all active:scale-95 disabled:opacity-50"
                    >
                        {processing ? "Processing..." : "Log In Intelligence"}
                    </button>
                </div>
            </form>
        </GuestLayout>
    );
}
