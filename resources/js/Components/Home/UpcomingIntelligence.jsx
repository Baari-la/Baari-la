import { usePage, Link } from "@inertiajs/react";

import {
    ArrowLeft,
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
            titleEn: "DIGESTEX Readable-AI Profile & Visibility Program",

            titleId: "DIGESTEX Readable-AI Profile & Visibility Program",

            descriptionEn:
                "The strategic entry point to the DIGESTEX Global Textile Intelligence Ecosystem — transforming company capabilities into a structured, trusted, and Readable-AI Profile designed to strengthen digital visibility and unlock future business opportunities.",

            descriptionId:
                "Pintu masuk strategis menuju DIGESTEX Global Textile Intelligence Ecosystem — mengubah kapabilitas perusahaan menjadi Readable-AI Profile yang terstruktur dan terpercaya untuk memperkuat visibilitas digital serta membuka peluang bisnis di masa depan.",

            // status: "NOW LIVE",
            status: "UPCOMING",

            icon: Globe2,
        },

        {
            titleEn: "Sourcing Hub™",

            titleId: "Sourcing Hub™",

            descriptionEn:
                "A connected sourcing ecosystem for buyers and suppliers — combining RFQ Marketplace, Smart Supplier Matching, MOQ Matching, and Collective Sourcing across the global textile value chain.",

            descriptionId:
                "Ekosistem sourcing terhubung untuk buyer dan supplier — menggabungkan RFQ Marketplace, Smart Supplier Matching, MOQ Matching, dan Collective Sourcing di seluruh rantai nilai industri tekstil global.",

            status: "UPCOMING",

            icon: Shirt,
        },

        {
            titleEn: "Cotton Intelligence™",

            titleId: "Cotton Intelligence™",

            descriptionEn:
                "A comprehensive cotton market intelligence platform covering trade flows, volume, value, market share, supplier dynamics, price movements, and spinning industry demand.",

            descriptionId:
                "Platform intelijen pasar kapas yang mencakup arus perdagangan, volume, nilai, pangsa pasar, dinamika pemasok, pergerakan harga, dan kebutuhan industri spinning.",

            status: "UPCOMING",

            icon: Package,
        },
        {
            titleEn: "Manufacturing Intelligence™",

            titleId: "Manufacturing Intelligence™",

            descriptionEn:
                "Industrial manufacturing intelligence connecting factories, machinery, production capacity, utilization, capabilities, and manufacturing activity across the textile value chain.",

            descriptionId:
                "Intelijen manufaktur industri yang menghubungkan pabrik, mesin, kapasitas produksi, utilisasi, kapabilitas, dan aktivitas manufaktur di seluruh rantai nilai tekstil.",

            status: "UPCOMING",

            icon: Building2,
        },
        {
            titleEn: "Raw Material Intelligence™",

            titleId: "Raw Material Intelligence™",

            descriptionEn:
                "Integrated intelligence for key textile raw materials — including cotton, polyester, viscose, nylon, and recycled fibers — connecting supply, trade, market movements, and industry demand.",

            descriptionId:
                "Intelijen terintegrasi untuk bahan baku utama tekstil — termasuk kapas, polyester, viscose, nylon, dan serat daur ulang — yang menghubungkan pasokan, perdagangan, pergerakan pasar, dan kebutuhan industri.",

            status: "UPCOMING",

            icon: Factory,
        },
        {
            titleEn: "Global Trade Radar™",

            titleId: "Global Trade Radar™",

            descriptionEn:
                "A global textile trade intelligence system monitoring trade flows, destination and sourcing markets, emerging movements, and market opportunities across countries and HS codes.",

            descriptionId:
                "Sistem intelijen perdagangan tekstil global yang memantau arus perdagangan, pasar tujuan dan sumber, pergerakan yang muncul, serta peluang pasar berdasarkan negara dan kode HS.",

            status: "UPCOMING",

            icon: Radar,
        },
        {
            titleEn: "Executive AI Insight™",

            titleId: "Executive AI Insight™",

            descriptionEn:
                "Executive-level intelligence that transforms complex industry data into strategic insights, market signals, risk alerts, and decision-support recommendations.",

            descriptionId:
                "Intelijen tingkat eksekutif yang mengubah data industri yang kompleks menjadi strategic insights, market signals, risk alerts, dan rekomendasi untuk mendukung pengambilan keputusan.",

            status: "UPCOMING",

            icon: Brain,
        },
        {
            titleEn: "Global Textile Operating System™",

            titleId: "Global Textile Operating System™",

            descriptionEn:
                "The long-term vision of DIGESTEX — a connected global operating layer linking Government, Industry, and Business across the textile and apparel value chain.",

            descriptionId:
                "Visi jangka panjang DIGESTEX — lapisan operating system global yang menghubungkan Pemerintah, Industri, dan Bisnis di seluruh rantai nilai tekstil dan apparel.",

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
                            ? "Building a connected Global Textile Intelligence Ecosystem that brings together industry intelligence, companies, technologies, solutions, suppliers, buyers, and business opportunities across the textile value chain."
                            : "Membangun Global Textile Intelligence Ecosystem yang terhubung untuk mempertemukan industry intelligence, perusahaan, teknologi, solusi, supplier, buyer, dan peluang bisnis di seluruh rantai nilai industri tekstil."}
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
                            ? "DIGESTEX Development Roadmap 2026–2030"
                            : "Roadmap Pengembangan DIGESTEX 2026–2030"}
                    </h3>

                    <p className="mx-auto mt-4 max-w-3xl text-center text-sm leading-7 text-slate-400">
                        {isEn
                            ? "A progressive development roadmap from digital industry identity and trade intelligence toward a connected global textile industry ecosystem."
                            : "Roadmap pengembangan bertahap dari digital industry identity dan trade intelligence menuju ekosistem industri tekstil global yang terhubung."}
                    </p>

                    <div className="mt-12 grid gap-6 md:grid-cols-5">
                        {/* 2026 */}
                        <div className="relative rounded-3xl border border-white/10 bg-white/5 p-6">
                            <div className="text-3xl font-black text-amber-400">
                                2026
                            </div>

                            <div className="mt-2 text-xs font-black uppercase tracking-widest text-white">
                                {isEn
                                    ? "Digital Foundation"
                                    : "Fondasi Digital"}
                            </div>

                            <div className="mt-5 text-sm leading-7 text-slate-300">
                                AI-Readable Company Profiles
                                <br />
                                Digital Industry Identity
                                <br />
                                Trade Intelligence Foundation
                                <br />
                                Textile Sector Intelligence
                                <br />
                                Early Ecosystem Partners
                            </div>
                        </div>

                        {/* 2027 */}
                        <div className="relative rounded-3xl border border-white/10 bg-white/5 p-6">
                            <div className="text-3xl font-black text-amber-400">
                                2027
                            </div>

                            <div className="mt-2 text-xs font-black uppercase tracking-widest text-white">
                                {isEn
                                    ? "Industry & Business Intelligence"
                                    : "Industry & Business Intelligence"}
                            </div>

                            <div className="mt-5 text-sm leading-7 text-slate-300">
                                Industry Intelligence
                                <br />
                                Manufacturing Intelligence
                                <br />
                                Market Intelligence
                                <br />
                                Sourcing Intelligence
                                <br />
                                Business Connectivity
                            </div>
                        </div>

                        {/* 2028 */}
                        <div className="relative rounded-3xl border border-white/10 bg-white/5 p-6">
                            <div className="text-3xl font-black text-amber-400">
                                2028
                            </div>

                            <div className="mt-2 text-xs font-black uppercase tracking-widest text-white">
                                {isEn
                                    ? "Global Trade & Market Intelligence"
                                    : "Global Trade & Market Intelligence"}
                            </div>

                            <div className="mt-5 text-sm leading-7 text-slate-300">
                                Global Trade Intelligence
                                <br />
                                Trade Radar™
                                <br />
                                Country & Product Intelligence
                                <br />
                                Global Market Intelligence
                                <br />
                                Trade Flow Analytics
                            </div>
                        </div>

                        {/* 2029 */}
                        <div className="relative rounded-3xl border border-white/10 bg-white/5 p-6">
                            <div className="text-3xl font-black text-amber-400">
                                2029
                            </div>

                            <div className="mt-2 text-xs font-black uppercase tracking-widest text-white">
                                {isEn
                                    ? "Decision Intelligence"
                                    : "Decision Intelligence"}
                            </div>

                            <div className="mt-5 text-sm leading-7 text-slate-300">
                                Executive AI Insight™
                                <br />
                                Smart Business Matching™
                                <br />
                                Predictive Market Signals
                                <br />
                                Decision Intelligence
                                <br />
                                Strategic Business Intelligence
                            </div>
                        </div>

                        {/* 2030 */}
                        <div className="relative rounded-3xl border border-amber-400/20 bg-amber-400/5 p-6">
                            <div className="text-3xl font-black text-amber-400">
                                2030
                            </div>

                            <div className="mt-2 text-xs font-black uppercase tracking-widest text-white">
                                {isEn
                                    ? "Connected Textile Industry"
                                    : "Connected Textile Industry"}
                            </div>

                            <div className="mt-5 text-sm leading-7 text-slate-300">
                                Connected Textile Intelligence Ecosystem
                                <br />
                                Global Textile Business Infrastructure
                                <br />
                                Integrated Industry Intelligence
                                <br />
                                AI-Enabled Industry Connectivity
                                <br />
                                Global Textile Ecosystem
                            </div>
                        </div>
                    </div>
                </div>
                {/* Footer */}
            </div>
            {/* TAmbahan */}

            {/* <div className="mt-20 flex justify-center">
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
                            ? "Start Your Digital Journey"
                            : "Mulai Perjalanan Digital Perusahaan Anda"}
                    </h3>

                    <p className="mx-auto mt-6 max-w-3xl leading-8 text-slate-300">
                        {isEn
                            ? "The DIGESTEX Readable-AI Profile & Visibility Program is the starting point for transforming your company capabilities into a structured digital identity — creating the foundation for greater visibility, intelligence, connectivity, and future business opportunities."
                            : "DIGESTEX Readable-AI Profile & Visibility Program merupakan langkah awal untuk mengubah kapabilitas perusahaan menjadi identitas digital yang terstruktur — membangun fondasi bagi visibilitas, intelligence, konektivitas, dan peluang bisnis yang lebih besar di masa depan."}
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
                            ? "BUILD YOUR READABLE-AI PROFILE"
                            : "BANGUN READABLE-AI PROFILE ANDA"}
                    </Link>
                </div>
            </div> */}

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
