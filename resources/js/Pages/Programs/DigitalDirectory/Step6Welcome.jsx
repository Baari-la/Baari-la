import { useEffect } from "react";
import ProgramNavbar from "@/Components/Program/ProgramNavbar";
import { Link, usePage } from "@inertiajs/react";

import {
    CheckCircle2,
    Globe,
    Building2,
    Sparkles,
    ArrowRight,
    ShieldCheck,
} from "lucide-react";

export default function Step6Welcome({ company }) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const benefits = {
        "Verified Company": [
            "Verified Company Badge",
            "Digital Company Passport™",
            "Product Listing",
        ],

        "Visibility Partner": [
            "Visibility Score™",
            "Featured Listing",
            "Executive Intelligence™",
        ],

        "Executive Partner": [
            "Executive Dashboard™",
            "Smart Business Matching™",
            "Build My Supply Chain™",
            "Executive AI Insight™",
        ],
    };

    useEffect(() => {
        localStorage.removeItem("digital-directory-company");
    }, []);

    return (
        <div className="min-h-screen bg-slate-50">
            <ProgramNavbar currentStep={6} />

            <main className="mx-auto max-w-7xl p-6">
                <div className="mx-auto max-w-6xl space-y-8">
                    {/* HERO */}

                    <section
                        className="
                    rounded-3xl
                    bg-gradient-to-r
                    from-slate-900
                    via-indigo-900
                    to-slate-900
                    p-12
                    text-center
                    text-white
                "
                    >
                        <CheckCircle2
                            className="
                        mx-auto
                        h-20
                        w-20
                        text-emerald-400
                    "
                        />

                        <p
                            className="
                        mt-6
                        text-sm
                        font-bold
                        uppercase
                        tracking-[0.25em]
                        text-emerald-300
                    "
                        >
                            STEP 6
                        </p>

                        <h1
                            className="
                        mt-4
                        text-5xl
                        font-black
                    "
                        >
                            {isEn
                                ? "Welcome to DIGESTEX"
                                : "Selamat Datang di DIGESTEX"}
                        </h1>

                        <p className="mt-6 text-xl text-slate-300">
                            {isEn
                                ? "Congratulations! Your company is now part of the DIGESTEX Global Textile Intelligence Ecosystem."
                                : "Selamat! Perusahaan Anda kini menjadi bagian dari DIGESTEX Global Textile Intelligence Ecosystem."}
                        </p>
                    </section>

                    {/* COMPANY */}

                    <section className="rounded-3xl border bg-white p-8 shadow-sm">
                        <h2 className="text-2xl font-black">
                            {isEn
                                ? "Participation Summary"
                                : "Ringkasan Partisipasi"}
                        </h2>

                        <div className="mt-8 grid gap-6 md:grid-cols-3">
                            <StatCard
                                title={isEn ? "Company" : "Perusahaan"}
                                value={company?.company_name ?? "-"}
                                icon={<Building2 className="h-5 w-5" />}
                            />

                            <StatCard
                                title={isEn ? "Package" : "Paket"}
                                value={company?.package ?? "-"}
                                icon={<Sparkles className="h-5 w-5" />}
                            />

                            <StatCard
                                title={isEn ? "Status" : "Status"}
                                value={
                                    isEn
                                        ? "Pending Verification"
                                        : "Menunggu Verifikasi"
                                }
                                icon={<ShieldCheck className="h-5 w-5" />}
                            />
                        </div>
                    </section>

                    {/* BENEFITS */}

                    <section className="rounded-3xl border bg-white p-8 shadow-sm">
                        <h2 className="text-2xl font-black">
                            {isEn
                                ? "You Now Have Access To"
                                : "Anda Kini Memiliki Akses Ke"}
                        </h2>

                        <div className="mt-8 grid gap-4 md:grid-cols-2">
                            {(benefits[company?.package] ?? []).map((item) => (
                                <div
                                    key={item}
                                    className="
            flex
            items-center
            gap-3
        "
                                >
                                    <CheckCircle2
                                        className="
                h-5
                w-5
                text-emerald-500
            "
                                    />

                                    {item}
                                </div>
                            ))}
                        </div>
                    </section>

                    {/* NEXT STEPS */}

                    <section className="rounded-3xl bg-slate-900 p-8 text-white">
                        <h2 className="text-2xl font-black">
                            {isEn ? "Next Steps" : "Langkah Selanjutnya"}
                        </h2>

                        <div className="mt-6 space-y-3 text-slate-300">
                            <p>
                                1.{" "}
                                {isEn
                                    ? "Complete your Digital Company Passport™."
                                    : "Lengkapi Digital Company Passport™ Anda."}
                            </p>

                            <p>
                                2.{" "}
                                {isEn
                                    ? "Add products and services."
                                    : "Tambahkan produk dan layanan."}
                            </p>

                            <p>
                                3.{" "}
                                {isEn
                                    ? "Add certifications and export markets."
                                    : "Tambahkan sertifikasi dan pasar ekspor."}
                            </p>

                            <p>
                                4.{" "}
                                {isEn
                                    ? "Unlock Executive Intelligence."
                                    : "Buka akses Executive Intelligence."}
                            </p>

                            <p>
                                5.{" "}
                                {isEn
                                    ? "Appear in Build My Supply Chain™."
                                    : "Tampil di Build My Supply Chain™."}
                            </p>
                        </div>
                    </section>

                    {/* CTA */}

                    <section className="rounded-3xl bg-emerald-50 p-10 text-center">
                        <Globe className="mx-auto h-10 w-10 text-emerald-600" />

                        <h2 className="mt-6 text-4xl font-black">
                            {isEn
                                ? "Create Your DIGESTEX Account"
                                : "Buat Akun DIGESTEX Anda"}
                        </h2>

                        <p className="mt-4 text-slate-600">
                            {isEn
                                ? "Create your account to manage your company profile and access DIGESTEX services."
                                : "Buat akun untuk mengelola profil perusahaan dan mengakses layanan DIGESTEX."}
                        </p>

                        <div className="mt-8 flex flex-wrap justify-center gap-4">
                            <Link
                                href={route("register")}
                                className="
        inline-flex
        items-center
        gap-2
        rounded-2xl
        bg-emerald-500
        px-8
        py-4
        font-bold
        text-white
    "
                            >
                                {isEn
                                    ? "CREATE YOUR ACCOUNT"
                                    : "BUAT AKUN ANDA"}

                                <ArrowRight className="h-5 w-5" />
                            </Link>

                            <Link
                                href="/"
                                className="
                            rounded-2xl
                            border
                            border-slate-300
                            px-8
                            py-4
                            font-bold
                        "
                            >
                                {isEn ? "RETURN TO HOME" : "KEMBALI KE BERANDA"}
                            </Link>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    );
}

function StatCard({ title, value, icon }) {
    return (
        <div className="rounded-2xl bg-slate-100 p-6">
            <div className="flex items-center gap-2 text-slate-500">
                {icon}

                <span className="text-sm font-semibold">{title}</span>
            </div>

            <div className="mt-3 text-2xl font-black">{value}</div>
        </div>
    );
}
