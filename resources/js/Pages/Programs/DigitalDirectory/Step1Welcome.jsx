import WebsiteLayout from "@/Layouts/WebsiteLayout";
import { Head, Link, usePage } from "@inertiajs/react";
import { CheckCircle, Building2, ArrowRight } from "lucide-react";

export default function Step1Welcome() {
    const { locale, auth } = usePage().props;

    const isEn = locale === "en";

    return (
        <WebsiteLayout>
            <Head title="Welcome to DIGESTEX" />

            <div className="min-h-screen bg-slate-950 py-20 text-white">
                <div className="mx-auto max-w-5xl px-6">
                    {/* Progress */}

                    <div className="mb-10">
                        <div className="flex items-center justify-between text-xs font-black uppercase tracking-widest text-slate-400">
                            <span>
                                {isEn
                                    ? "DIGESTEX ONBOARDING"
                                    : "ONBOARDING DIGESTEX"}
                            </span>

                            <span>50% COMPLETE</span>
                        </div>

                        <div className="mt-4 h-3 overflow-hidden rounded-full bg-slate-800">
                            <div className="h-full w-1/2 rounded-full bg-emerald-500"></div>
                        </div>
                    </div>

                    {/* Welcome Card */}

                    <div
                        className="
                            rounded-[40px]
                            border
                            border-white/10
                            bg-slate-900
                            p-12
                            shadow-2xl
                        "
                    >
                        <div className="text-center">
                            <div className="flex justify-center">
                                <div className="rounded-full bg-emerald-500/20 p-6">
                                    <CheckCircle className="h-14 w-14 text-emerald-400" />
                                </div>
                            </div>

                            <div
                                className="
                                    mt-6
                                    inline-flex
                                    rounded-full
                                    bg-emerald-500/20
                                    px-5
                                    py-2
                                    text-xs
                                    font-black
                                    uppercase
                                    tracking-widest
                                    text-emerald-400
                                "
                            >
                                {isEn
                                    ? "EMAIL VERIFIED SUCCESSFULLY"
                                    : "EMAIL BERHASIL DIVERIFIKASI"}
                            </div>

                            <h1 className="mt-8 text-5xl font-black">
                                {isEn
                                    ? "WELCOME TO DIGESTEX"
                                    : "SELAMAT DATANG DI DIGESTEX"}
                            </h1>

                            <p className="mx-auto mt-6 max-w-3xl text-lg leading-8 text-slate-300">
                                {isEn
                                    ? `Congratulations ${
                                          auth?.user?.name ?? ""
                                      }! Your account has been successfully created and verified.`
                                    : `Selamat ${
                                          auth?.user?.name ?? ""
                                      }! Akun Anda telah berhasil dibuat dan diverifikasi.`}
                            </p>
                        </div>

                        {/* Checklist */}

                        <div className="mx-auto mt-12 max-w-3xl">
                            <div className="space-y-5">
                                {[
                                    isEn
                                        ? "Account Created"
                                        : "Akun Berhasil Dibuat",

                                    isEn
                                        ? "Email Verified"
                                        : "Email Berhasil Diverifikasi",

                                    isEn ? "Ready to Begin" : "Siap Memulai",
                                ].map((item) => (
                                    <div
                                        key={item}
                                        className="
                                            flex
                                            items-center
                                            gap-4
                                            rounded-2xl
                                            bg-slate-800
                                            p-5
                                        "
                                    >
                                        <CheckCircle className="h-6 w-6 text-emerald-400" />

                                        <span className="font-semibold">
                                            {item}
                                        </span>
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* Digital Company Passport */}

                        <div
                            className="
                                mt-12
                                rounded-3xl
                                border
                                border-amber-500/20
                                bg-amber-500/10
                                p-8
                            "
                        >
                            <div className="flex items-center gap-4">
                                <Building2 className="h-10 w-10 text-amber-400" />

                                <div>
                                    <h3 className="text-2xl font-black">
                                        Digital Company Passport™
                                    </h3>

                                    <p className="mt-2 text-slate-300">
                                        {isEn
                                            ? "Your Digital Company Passport™ is waiting. Complete your company profile and increase your visibility in the global textile industry."
                                            : "Digital Company Passport™ Anda sedang menunggu. Lengkapi profil perusahaan Anda dan tingkatkan visibilitas di industri tekstil global."}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {/* CTA */}

                        <div className="mt-12 text-center">
                            <Link
                                href={route("onboarding.company-information")}
                                className="
                                    inline-flex
                                    items-center
                                    gap-3
                                    rounded-2xl
                                    bg-gradient-to-r
                                    from-emerald-500
                                    to-emerald-600
                                    px-10
                                    py-5
                                    text-sm
                                    font-black
                                    uppercase
                                    tracking-widest
                                    text-white
                                    shadow-xl
                                    transition
                                    hover:scale-[1.02]
                                "
                            >
                                {isEn
                                    ? "START BUILDING COMPANY PROFILE"
                                    : "MULAI MEMBANGUN PROFIL PERUSAHAAN"}

                                <ArrowRight className="h-5 w-5" />
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </WebsiteLayout>
    );
}
