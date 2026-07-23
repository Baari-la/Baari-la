import { usePage } from "@inertiajs/react";
import { ArrowLeft } from "lucide-react";
import { Link } from "@inertiajs/react";
import {
    Cpu,
    Globe2,
    Brain,
    Radar,
    Package,
    Factory,
    Shirt,
    Sparkles,
    Building2,
} from "lucide-react";

export default function UpcomingIntelligence() {
    const { locale } = usePage().props;
    const isEn = locale === "en";
    const modules = [
        {
            titleEn: "DIGESTEX Digital Directory & Visibility Program",

            titleId: "Program DIGESTEX Digital Directory & Visibility",

            descriptionEn:
                "The foundation of the Global Textile Intelligence Ecosystem with Digital Company Passport™, Verified Company Badge, and Visibility Score™.",

            descriptionId:
                "Fondasi Global Textile Intelligence Ecosystem dengan Digital Company Passport™, Verified Company Badge, dan Visibility Score™.",

            status: "NOW LIVE",

            icon: Globe2,
        },

        {
            titleEn: "Sourcing Hub™",

            titleId: "Sourcing Hub™",

            descriptionEn:
                "RFQ Marketplace, MOQ Matching, and Collective Sourcing for the global textile industry.",

            descriptionId:
                "RFQ Marketplace, MOQ Matching, dan Collective Sourcing untuk industri tekstil global.",

            status: "UPCOMING",

            icon: Shirt,
        },
        {
            titleEn: "Cotton Intelligence™",

            titleId: "Cotton Intelligence™",

            descriptionEn:
                "Indonesia Cotton Dashboard including volume, value, market share, supplier ranking, and spinner consumption.",

            descriptionId:
                "Dashboard kapas Indonesia yang mencakup volume, nilai, pangsa pasar, peringkat pemasok, dan konsumsi industri spinning.",

            status: "UPCOMING",

            icon: Package,
        },
        {
            titleEn: "Manufacturing Intelligence™",

            titleId: "Manufacturing Intelligence™",

            descriptionEn:
                "Machinery, production capacity, utilization, and factory intelligence.",

            descriptionId:
                "Intelijen mesin, kapasitas produksi, utilisasi, dan manufaktur.",

            status: "UPCOMING",

            icon: Building2,
        },
        {
            titleEn: "Raw Material Intelligence™",

            titleId: "Raw Material Intelligence™",

            descriptionEn:
                "Intelligence for cotton, polyester, viscose, nylon, and recycled fibers.",

            descriptionId:
                "Intelijen untuk kapas, polyester, viscose, nylon, dan serat daur ulang.",

            status: "UPCOMING",

            icon: Factory,
        },

        {
            titleEn: "Global Trade Radar™",

            titleId: "Global Trade Radar™",

            descriptionEn:
                "Monitor global textile trade movements and market opportunities.",

            descriptionId:
                "Memantau pergerakan perdagangan tekstil global dan peluang pasar.",

            status: "UPCOMING",

            icon: Radar,
        },

        {
            titleEn: "Executive AI Insight™",

            titleId: "Executive AI Insight™",

            descriptionEn:
                "Executive-level AI intelligence and strategic recommendations.",

            descriptionId:
                "Intelijen AI tingkat eksekutif dan rekomendasi strategis.",

            status: "UPCOMING",

            icon: Brain,
        },

        {
            titleEn: "Global Textile Operating System™",

            titleId: "Global Textile Operating System™",

            descriptionEn:
                "Connecting Government, Industry, and Business by 2030.",

            descriptionId:
                "Menghubungkan Pemerintah, Industri, dan Bisnis pada tahun 2030.",

            status: "VISION",

            icon: Cpu,
        },
    ];
    return (
        <section className="bg-slate-50 py-24">
            <div className="mx-auto max-w-7xl px-6">
                {/* Header */}

                <div className="mx-auto max-w-4xl text-center">
                    <div
                        className="
        inline-flex
        rounded-full
        bg-emerald-100
        px-5
        py-2
        text-sm
        font-black
        text-emerald-700
    "
                    >
                        2026–2030 STRATEGIC ROADMAP
                    </div>

                    <h2 className="mt-6 text-5xl font-black">
                        {isEn
                            ? "THE FUTURE OF DIGESTEX"
                            : "MASA DEPAN DIGESTEX"}
                    </h2>

                    <p className="mt-6 text-lg leading-8 text-slate-600">
                        {isEn
                            ? "Building the Global Textile Intelligence Ecosystem through data, intelligence, and industrial connectivity."
                            : "Membangun Global Textile Intelligence Ecosystem melalui data, intelijen, dan konektivitas industri."}
                    </p>
                </div>

                {/* Cards */}

                <div className="mt-16 grid gap-8 md:grid-cols-2 xl:grid-cols-4">
                    {modules.map((item) => {
                        const Icon = item.icon;

                        return (
                            <div
                                key={item.title}
                                className="
                                    rounded-3xl
                                    border
                                    border-slate-200
                                    bg-white
                                    p-8
                                    shadow-sm
                                    transition
                                    hover:-translate-y-1
                                    hover:shadow-xl
                                "
                            >
                                <div className="flex items-center justify-between">
                                    <Icon className="h-10 w-10 text-indigo-600" />

                                    <span
                                        className={`
        rounded-full
        px-3
        py-1
        text-xs
        font-bold

        ${
            item.status === "NOW LIVE"
                ? "bg-emerald-100 text-emerald-700"
                : item.status === "VISION"
                  ? "bg-purple-100 text-purple-700"
                  : "bg-amber-100 text-amber-700"
        }
    `}
                                    >
                                        {item.status}
                                    </span>
                                </div>

                                <h3 className="mt-6 text-2xl font-black">
                                    {isEn ? item.titleEn : item.titleId}
                                </h3>

                                <p className="mt-4 text-sm leading-7 text-slate-600">
                                    {isEn
                                        ? item.descriptionEn
                                        : item.descriptionId}
                                </p>
                            </div>
                        );
                    })}
                </div>
                {/* Tambahan */}
                <div
                    className="
        mx-auto
        mt-20
        max-w-6xl
        rounded-3xl
        bg-slate-900
        p-10
        text-white
    "
                >
                    <h3 className="text-center text-3xl font-black">
                        {isEn
                            ? "DIGESTEX Roadmap 2026–2030"
                            : "Roadmap DIGESTEX 2026–2030"}
                    </h3>

                    <div className="mt-10 grid gap-6 md:grid-cols-5">
                        <div>
                            <div className="text-2xl font-black text-amber-400">
                                2026
                            </div>

                            <div className="mt-2 text-sm text-slate-300">
                                Digital Directory
                                <br />
                                Cotton Intelligence
                                <br />
                                Sourcing Hub
                            </div>
                        </div>

                        <div>
                            <div className="text-2xl font-black text-amber-400">
                                2027
                            </div>

                            <div className="mt-2 text-sm text-slate-300">
                                Raw Material
                                <br />
                                Manufacturing
                                <br />
                                Intelligence
                            </div>
                        </div>

                        <div>
                            <div className="text-2xl font-black text-amber-400">
                                2028
                            </div>

                            <div className="mt-2 text-sm text-slate-300">
                                Global Trade
                                <br />
                                Radar
                            </div>
                        </div>

                        <div>
                            <div className="text-2xl font-black text-amber-400">
                                2029
                            </div>

                            <div className="mt-2 text-sm text-slate-300">
                                Executive AI
                                <br />
                                Smart Matching
                            </div>
                        </div>

                        <div>
                            <div className="text-2xl font-black text-amber-400">
                                2030
                            </div>

                            <div className="mt-2 text-sm text-slate-300">
                                Global Textile
                                <br />
                                Operating System
                            </div>
                        </div>
                    </div>
                </div>
                {/* Footer */}
            </div>
            {/* TAmbahan */}
            <div className="mt-20 flex justify-center">
                <div
                    className="
            w-full
            max-w-5xl
            rounded-3xl
            bg-slate-900
            p-10
            text-center
            text-white
            shadow-2xl
        "
                >
                    <h3 className="text-4xl font-black">
                        {isEn
                            ? "Join the Journey"
                            : "Bergabung dalam Perjalanan Ini"}
                    </h3>

                    <p className="mx-auto mt-6 max-w-3xl leading-8 text-slate-300">
                        {isEn
                            ? "DIGESTEX Digital Directory & Visibility Program 2026 is not the destination. It is the foundation of the Global Textile Intelligence Ecosystem."
                            : "DIGESTEX Digital Directory & Visibility Program 2026 bukanlah tujuan akhir. Program ini merupakan fondasi dari Global Textile Intelligence Ecosystem."}
                    </p>

                    <Link
                        href={route("program.digital-directory")}
                        className="
                mt-8
                inline-flex
                rounded-2xl
                bg-emerald-500
                px-8
                py-4
                font-bold
                text-white
                transition
                hover:bg-emerald-600
            "
                    >
                        {isEn
                            ? "JOIN DIGITAL DIRECTORY"
                            : "IKUT DIGITAL DIRECTORY"}
                    </Link>
                </div>
            </div>

            <div className="mx-auto max-w-7xl px-6 pt-8">
                <Link
                    href="/"
                    className="
            inline-flex
            items-center
            gap-2
            rounded-2xl
            border
            px-5
            py-3
            font-bold
            hover:bg-slate-100
        "
                >
                    <ArrowLeft className="h-4 w-4" />

                    {isEn ? "BACK TO HOME" : "KEMBALI KE BERANDA"}
                </Link>
            </div>
        </section>
    );
}
