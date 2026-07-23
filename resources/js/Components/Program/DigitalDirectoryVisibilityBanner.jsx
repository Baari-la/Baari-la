import { Link, usePage } from "@inertiajs/react";
import {
    Globe,
    Sparkles,
    Network,
    Building2,
    ShieldCheck,
    ArrowRight,
} from "lucide-react";

export default function DigitalDirectoryVisibilityBanner({
    participatingCompanies = 0,
    verifiedCompanies = 0,
    goldMembers = 0,
}) {
    const { locale } = usePage().props;

    const isEn = locale === "en";
    const features = [
        "Digital Company Passport™",
        "Executive Intelligence™",
        "Smart Business Matching™",
        "Build My Supply Chain™",
        "Visibility Score™",
        "Executive Dashboard™",
        "Verified Company Badge",
        isEn ? "Verified Company Badge" : "Lencana Perusahaan Terverifikasi",
    ];

    return (
        <section
            className="
                overflow-hidden
                rounded-3xl
                bg-gradient-to-r
                from-slate-900
                via-indigo-900
                to-slate-900
                p-10
                text-white
                shadow-2xl
            "
        >
            <div className="grid gap-10 lg:grid-cols-[2fr_1fr]">
                {/* =======================================================
                    LEFT
                ======================================================= */}

                <div>
                    <div
                        className="
                            inline-flex
                            items-center
                            gap-2
                            rounded-full
                            bg-emerald-500/20
                            px-4
                            py-2
                            text-sm
                            font-bold
                            text-emerald-300
                        "
                    >
                        <Sparkles className="h-4 w-4" />
                        {isEn
                            ? "NOW OPEN FOR PARTICIPATION"
                            : "PENDAFTARAN TELAH DIBUKA"}
                    </div>

                    <h1
                        className="
                            mt-6
                            text-4xl
                            font-black
                            leading-tight
                        "
                    >
                        DIGESTEX Digital Directory
                        <br />& Visibility Program 2026
                    </h1>
                    <p
                        className="
        mt-4
        text-sm
        uppercase
        tracking-[0.2em]
        text-emerald-300
    "
                    >
                        Global Textile Intelligence Ecosystem
                    </p>
                    <p
                        className="
                            mt-5
                            max-w-3xl
                            text-lg
                            text-slate-300
                        "
                    >
                        {isEn
                            ? "Complete Your Profile. Increase Your Visibility. Unlock New Opportunities."
                            : "Lengkapi Profil Perusahaan Anda. Tingkatkan Visibilitas. Buka Peluang Baru."}
                    </p>
                    <p className="mt-4 text-sm text-emerald-300">
                        {isEn
                            ? "Powering the next generation of Textile Intelligence."
                            : "Mendorong generasi baru Textile Intelligence."}
                    </p>
                    <div className="mt-8 grid gap-4 md:grid-cols-2">
                        {features.map((feature) => (
                            <div
                                key={feature}
                                className="
                                    flex
                                    items-center
                                    gap-3
                                "
                            >
                                <ShieldCheck className="h-5 w-5 text-emerald-400" />

                                <span>{feature}</span>
                            </div>
                        ))}
                    </div>

                    <div className="mt-10 flex flex-wrap gap-4">
                        <Link
                            href={route("program.digital-directory")}
                            className="
        inline-flex
        items-center
        gap-2
        rounded-xl
        bg-emerald-500
        px-6
        py-3
        font-bold
        transition
        hover:bg-emerald-600
    "
                        >
                            {isEn ? "JOIN THE PROGRAM" : "IKUTI PROGRAM"}

                            <ArrowRight className="h-4 w-4" />
                        </Link>

                        <Link
                            href="/company/passport/700"
                            className="
                                rounded-xl
                                border
                                border-white/30
                                px-6
                                py-3
                                font-bold
                                transition
                                hover:bg-white/10
                            "
                        >
                            {isEn
                                ? "VIEW SAMPLE PASSPORT"
                                : "LIHAT CONTOH PASSPORT PERUSAHAAN"}
                        </Link>
                    </div>
                </div>

                {/* =======================================================
                    RIGHT
                ======================================================= */}

                <div
                    className="
                        rounded-3xl
                        border
                        border-white/10
                        bg-white/10
                        p-8
                        backdrop-blur
                    "
                >
                    <h3 className="text-xl font-bold">
                        {" "}
                        {isEn ? "2026 Program Targets" : "Target Program 2026"}
                    </h3>

                    <div className="mt-8 space-y-6">
                        <div>
                            <div className="flex items-center gap-2 text-sm text-slate-300">
                                <Building2 className="h-4 w-4" />
                                {isEn
                                    ? "Participating Companies"
                                    : "Perusahaan Peserta"}
                            </div>

                            <div className="mt-1 text-3xl font-black">
                                {/* {participatingCompanies} / 500 */}
                                500
                            </div>
                        </div>

                        <div>
                            <div className="flex items-center gap-2 text-sm text-slate-300">
                                <ShieldCheck className="h-4 w-4" />
                                {isEn
                                    ? "Verified Companies"
                                    : "Perusahaan Terverifikasi"}
                            </div>

                            <div className="mt-1 text-3xl font-black">
                                {/* {verifiedCompanies} / 300 */}
                                300
                            </div>
                        </div>

                        <div>
                            <div className="flex items-center gap-2 text-sm text-slate-300">
                                <Globe className="h-4 w-4" />
                                Gold Visibility Members
                            </div>

                            <div className="mt-1 text-3xl font-black">
                                {/* {goldMembers} / 200 */}
                                200
                            </div>
                        </div>
                    </div>

                    <div
                        className="
                            mt-10
                            rounded-2xl
                            border
                            border-white/10
                            bg-white/5
                            p-5
                        "
                    >
                        <div className="flex items-start gap-3">
                            <Network className="mt-1 h-5 w-5 text-sky-300" />

                            <div>
                                <div className="font-semibold">
                                    {isEn
                                        ? "Preparing Companies for the Next Generation of Business Opportunities."
                                        : "Mempersiapkan Perusahaan Menuju Generasi Baru Peluang Bisnis."}
                                </div>

                                <div className="mt-2 text-sm text-slate-300">
                                    {isEn
                                        ? "Companies with complete and verified profiles are more likely to appear in Smart Business Matching™, Build My Supply Chain™, and future DIGESTEX Intelligence services."
                                        : "Perusahaan dengan profil lengkap dan terverifikasi memiliki peluang lebih besar untuk tampil di Smart Business Matching™, Build My Supply Chain™, dan layanan DIGESTEX Intelligence."}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}
