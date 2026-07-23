import ProgramNavbar from "@/Components/Program/ProgramNavbar";
import { Link, usePage } from "@inertiajs/react";
import StickyWhatsAppButton from "@/Components/Program/StickyWhatsAppButton";

import {
    Globe,
    ShieldCheck,
    Sparkles,
    Network,
    Building2,
    ArrowRight,
    CheckCircle2,
} from "lucide-react";

export default function Step1ProgramInformation() {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const benefits = [
        "Digital Company Passport™",
        "Executive Dashboard™",
        "Smart Business Matching™",
        "Build My Supply Chain™",
        "Executive AI Insight™",
        "Visibility Score™",
        isEn ? "Verified Company Badge" : "Lencana Perusahaan Terverifikasi",
    ];

    const participants = [
        isEn ? "Fiber Producers" : "Produsen Serat",

        isEn ? "Yarn Manufacturers" : "Produsen Benang",

        isEn ? "Fabric Manufacturers" : "Produsen Kain",

        isEn ? "Garment Manufacturers" : "Produsen Garmen",

        isEn ? "Textile Machinery Companies" : "Perusahaan Mesin Tekstil",

        isEn ? "Chemical Suppliers" : "Pemasok Bahan Kimia",

        isEn ? "Brands & Retailers" : "Merek & Retail",

        isEn ? "Technology Providers" : "Penyedia Teknologi",
    ];

    return (
        <>
            <ProgramNavbar currentStep={1} />
            <main className="mx-auto max-w-7xl p-6">
                <div className="space-y-8">
                    {/* HERO */}

                    <section className="rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-900 to-slate-900 p-10 text-white">
                        <div className="max-w-4xl">
                            <div className="inline-flex items-center gap-2 rounded-full bg-emerald-500/20 px-4 py-2 text-sm font-bold text-emerald-300">
                                <Sparkles className="h-4 w-4" />

                                {isEn
                                    ? "PROGRAM INFORMATION"
                                    : "INFORMASI PROGRAM"}
                            </div>

                            <p className="mt-6 text-sm uppercase tracking-[0.25em] text-emerald-300">
                                Global Textile Intelligence Ecosystem
                            </p>

                            <h1 className="mt-4 text-5xl font-black leading-tight">
                                DIGESTEX Digital Directory
                                <br />& Visibility Program 2026
                            </h1>

                            <p className="mt-6 text-xl text-slate-300">
                                {isEn
                                    ? "Become Part of the Global Textile Intelligence Ecosystem."
                                    : "Menjadi Bagian dari Ekosistem Global Textile Intelligence."}
                            </p>

                            <p className="mt-4 max-w-3xl text-slate-300">
                                {isEn
                                    ? "DIGESTEX Digital Directory & Visibility Program 2026 is designed to help companies improve their visibility, strengthen their digital presence, and unlock new business opportunities."
                                    : "DIGESTEX Digital Directory & Visibility Program 2026 dirancang untuk membantu perusahaan meningkatkan visibilitas, meningkatkan kehadiran secara digital, dan membuka peluang bisnis baru."}
                            </p>
                        </div>
                    </section>
                    {/* Tambahan */}
                    <div
                        className="
        fixed
        bottom-6
        right-6
        z-50
        flex
        flex-col
        gap-3
    "
                    >
                        {/* Ready To Join */}

                        <Link
                            href={route("program.digital-directory.package")}
                            className="
            inline-flex
            items-center
            justify-center
            gap-2
            rounded-full
            bg-emerald-500
            px-6
            py-4
            font-bold
            text-white
            shadow-2xl
        "
                        >
                            READY TO JOIN
                        </Link>
                    </div>
                    {/* WHY DIGESTEX EXISTS */}

                    <section className="rounded-3xl border bg-white p-10 shadow-sm">
                        <div className="max-w-6xl">
                            {/* Badge */}

                            <div
                                className="
                inline-flex
                rounded-full
                bg-blue-100
                px-4
                py-2
                text-sm
                font-bold
                text-blue-700
            "
                            >
                                {isEn
                                    ? "WHY DIGESTEX EXISTS"
                                    : "MENGAPA DIGESTEX HADIR"}
                            </div>

                            {/* Title */}

                            <h2 className="mt-6 text-4xl font-black leading-tight">
                                {isEn
                                    ? "The Textile Industry Continues to Evolve."
                                    : "Industri Tekstil Terus Berkembang."}
                            </h2>

                            {/* Description */}

                            <p className="mt-6 text-lg leading-8 text-slate-600">
                                {isEn
                                    ? "The textile industry continues to evolve with new challenges, changing market dynamics, and increasing global competition."
                                    : "Industri tekstil terus berkembang dengan berbagai tantangan, perubahan dinamika pasar, dan meningkatnya persaingan global."}
                            </p>

                            <p className="mt-4 text-lg leading-8 text-slate-600">
                                {isEn
                                    ? "Over the years, we have witnessed companies expanding, relocating, reducing capacity, and even ceasing operations. At the same time, new companies continue to emerge as part of Indonesia's evolving textile ecosystem."
                                    : "Selama bertahun-tahun, kami menyaksikan perusahaan melakukan ekspansi, relokasi, pengurangan kapasitas, bahkan menghentikan operasionalnya. Di saat yang sama, perusahaan-perusahaan baru terus lahir sebagai bagian dari ekosistem tekstil Indonesia yang terus berkembang."}
                            </p>

                            <p className="mt-4 text-lg leading-8 text-slate-600">
                                {isEn
                                    ? "The way buyers discover suppliers continues to evolve—from printed directories, industry references, and factory visits to Google, LinkedIn, company websites, and digital platforms."
                                    : "Cara buyer menemukan supplier juga terus berkembang—dari direktori cetak, referensi industri, dan kunjungan pabrik menuju Google, LinkedIn, website perusahaan, serta berbagai platform digital."}
                            </p>

                            <p className="mt-4 text-lg leading-8 text-slate-600">
                                {isEn
                                    ? "Today, digital visibility is often the first step before exhibitions, business meetings, and factory visits take place."
                                    : "Saat ini, digital visibility sering kali menjadi langkah awal sebelum pameran, pertemuan bisnis, dan kunjungan pabrik dilakukan."}
                            </p>

                            {/* Timeline */}

                            <div className="mt-10 grid gap-6 md:grid-cols-3">
                                <div className="rounded-2xl bg-slate-50 p-6 text-center">
                                    <div className="text-4xl font-black text-blue-600">
                                        2022
                                    </div>

                                    <div className="mt-3 font-bold">
                                        Printed Directory
                                    </div>

                                    <div className="text-sm text-slate-500">
                                        First Edition
                                    </div>
                                </div>

                                <div className="rounded-2xl bg-slate-50 p-6 text-center">
                                    <div className="text-4xl font-black text-blue-600">
                                        2024
                                    </div>

                                    <div className="mt-3 font-bold">
                                        Printed Directory
                                    </div>

                                    <div className="text-sm text-slate-500">
                                        Second Edition
                                    </div>
                                </div>

                                <div className="rounded-2xl bg-emerald-50 p-6 text-center">
                                    <div className="text-4xl font-black text-emerald-600">
                                        2026
                                    </div>

                                    <div className="mt-3 font-bold">
                                        DIGESTEX
                                    </div>

                                    <div className="text-sm text-slate-500">
                                        Living Directory
                                    </div>
                                </div>
                            </div>

                            {/* Pillars */}

                            <div className="mt-10 grid gap-4 md:grid-cols-3">
                                <div className="rounded-2xl bg-slate-100 p-6">
                                    <div className="text-lg font-black">
                                        ACCURATE
                                    </div>

                                    <div className="mt-2 text-sm text-slate-600">
                                        {isEn
                                            ? "Keep your company information up to date."
                                            : "Pastikan informasi perusahaan Anda selalu terkini."}
                                    </div>
                                </div>

                                <div className="rounded-2xl bg-slate-100 p-6">
                                    <div className="text-lg font-black">
                                        VISIBLE
                                    </div>

                                    <div className="mt-2 text-sm text-slate-600">
                                        {isEn
                                            ? "Increase visibility across the industry."
                                            : "Tingkatkan visibilitas perusahaan Anda."}
                                    </div>
                                </div>

                                <div className="rounded-2xl bg-slate-100 p-6">
                                    <div className="text-lg font-black">
                                        CONNECTED
                                    </div>

                                    <div className="mt-2 text-sm text-slate-600">
                                        {isEn
                                            ? "Connect with new opportunities."
                                            : "Terhubung dengan peluang bisnis baru."}
                                    </div>
                                </div>
                            </div>

                            {/* Closing Statement */}

                            <div
                                className="
                mt-10
                rounded-3xl
                bg-slate-900
                p-10
                text-center
                text-white
            "
                            >
                                <div
                                    className="
                    mx-auto
                    max-w-4xl
                    text-3xl
                    font-black
                    leading-relaxed
                "
                                >
                                    {isEn
                                        ? "DIGESTEX helps your company remain visible throughout the discovery and decision-making process in the digital era."
                                        : "DIGESTEX membantu perusahaan Anda tetap terlihat dalam proses pencarian dan pengambilan keputusan di era digital."}
                                </div>
                            </div>
                        </div>
                    </section>
                    {/* WHY JOIN */}

                    <section className="rounded-3xl border bg-white p-8 shadow-sm">
                        <h2 className="text-3xl font-black">
                            {isEn ? "Why Join?" : "Mengapa Bergabung?"}
                        </h2>

                        <div className="mt-6 grid gap-4 md:grid-cols-2">
                            {[
                                isEn
                                    ? "Increase Global Visibility"
                                    : "Meningkatkan Visibilitas Global",

                                isEn
                                    ? "Improve Discoverability"
                                    : "Meningkatkan Kemudahan Ditemukan",

                                isEn
                                    ? "Connect with Potential Buyers"
                                    : "Terhubung dengan Calon Pembeli",

                                isEn
                                    ? "Access DIGESTEX Intelligence"
                                    : "Mengakses DIGESTEX Intelligence",
                            ].map((item) => (
                                <div
                                    key={item}
                                    className="flex items-center gap-3"
                                >
                                    <CheckCircle2 className="h-5 w-5 text-emerald-500" />

                                    {item}
                                </div>
                            ))}
                        </div>
                    </section>

                    {/* BENEFITS */}

                    <section className="rounded-3xl border bg-white p-8 shadow-sm">
                        <h2 className="text-3xl font-black">
                            {isEn
                                ? "What You Will Receive"
                                : "Apa yang Akan Anda Dapatkan"}
                        </h2>

                        <div className="mt-8 grid gap-4 md:grid-cols-2">
                            {benefits.map((benefit) => (
                                <div
                                    key={benefit}
                                    className="flex items-center gap-3"
                                >
                                    <ShieldCheck className="h-5 w-5 text-emerald-500" />

                                    {benefit}
                                </div>
                            ))}
                        </div>
                    </section>

                    {/* VISIBILITY JOURNEY */}

                    <section className="rounded-3xl border bg-white p-8 shadow-sm">
                        <h2 className="text-3xl font-black">
                            Visibility Journey
                        </h2>

                        <div className="mt-8 grid gap-4 text-center md:grid-cols-5">
                            {[
                                "Profile",
                                "Visibility",
                                "Discoverability",
                                "Opportunity",
                                "Executive Intelligence",
                            ].map((step) => (
                                <div
                                    key={step}
                                    className="rounded-2xl bg-slate-100 p-5"
                                >
                                    {step}
                                </div>
                            ))}
                        </div>
                    </section>

                    {/* WHO SHOULD JOIN */}

                    <section className="rounded-3xl border bg-white p-8 shadow-sm">
                        <h2 className="text-3xl font-black">
                            {isEn
                                ? "Who Should Join?"
                                : "Siapa yang Dapat Bergabung?"}
                        </h2>

                        <div className="mt-8 grid gap-4 md:grid-cols-2">
                            {participants.map((item) => (
                                <div
                                    key={item}
                                    className="flex items-center gap-3"
                                >
                                    <Building2 className="h-5 w-5 text-sky-500" />

                                    {item}
                                </div>
                            ))}
                        </div>
                    </section>

                    {/* FOUNDING PARTICIPANTS */}

                    <section className="rounded-3xl border bg-white p-8 shadow-sm">
                        <div className="flex items-start gap-4">
                            <Network className="mt-1 h-6 w-6 text-indigo-600" />

                            <div>
                                <h2 className="text-3xl font-black">
                                    {isEn
                                        ? "Founding Participants"
                                        : "Peserta Pendiri"}
                                </h2>

                                <p className="mt-4 text-slate-600">
                                    {isEn
                                        ? "Be among the first companies shaping the future of Textile Intelligence."
                                        : "Jadilah bagian dari perusahaan-perusahaan pertama yang membentuk masa depan Textile Intelligence."}
                                </p>

                                <div className="mt-6 grid gap-4 md:grid-cols-3">
                                    <StatCard
                                        title={
                                            isEn
                                                ? "Participating Companies"
                                                : "Perusahaan Peserta"
                                        }
                                        value="500"
                                    />

                                    <StatCard
                                        title={
                                            isEn
                                                ? "Verified Companies"
                                                : "Perusahaan Terverifikasi"
                                        }
                                        value="300"
                                    />

                                    <StatCard
                                        title="Gold Visibility Members"
                                        value="200"
                                    />
                                </div>
                            </div>
                        </div>
                    </section>

                    {/* CTA */}

                    <section className="rounded-3xl bg-slate-900 p-10 text-center text-white">
                        <Globe className="mx-auto h-10 w-10 text-emerald-400" />

                        <h2 className="mt-6 text-4xl font-black">
                            {isEn ? "Ready To Join?" : "Siap Untuk Bergabung?"}
                        </h2>

                        <p className="mt-4 text-slate-300">
                            {isEn
                                ? "Join the next generation of Textile Intelligence."
                                : "Bergabunglah dengan generasi baru Textile Intelligence."}
                        </p>

                        <Link
                            href={route("program.digital-directory.package")}
                            className="
                        mt-8
                        inline-flex
                        items-center
                        gap-2
                        rounded-2xl
                        bg-emerald-500
                        px-8
                        py-4
                        font-bold
                        transition
                        hover:bg-emerald-600
                    "
                        >
                            {isEn
                                ? "SELECT YOUR PACKAGE"
                                : "PILIH PAKET PARTISIPASI"}

                            <ArrowRight className="h-5 w-5" />
                        </Link>
                    </section>
                </div>
                <StickyWhatsAppButton
                    message="
Halo DIGESTEX,

Saya ingin mengetahui lebih lanjut mengenai DIGESTEX Digital Directory & Visibility Program 2026.
"
                />
            </main>
        </>
    );
}

function StatCard({ title, value }) {
    return (
        <div className="rounded-2xl bg-slate-100 p-6 text-center">
            <div className="text-3xl font-black">{value}</div>

            <div className="mt-2 text-sm text-slate-600">{title}</div>
        </div>
    );
}
